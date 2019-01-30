<?php

namespace App;

use App\Exceptions\UploadParserException;
use Log;
use ZipArchive;

class UploadParser
{
    /**
     * @var ZipArchive
     */
    protected $zipFile = null;

    /**
     * Cached/Parsed song data.
     *
     * @var array
     */
    protected $songData = [];

    /**
     * ZipArchive file index.
     *
     * @var array
     */
    protected $indexData = [];

    /**
     * @var string
     */
    protected $file;

    /**
     * UploadParser constructor.
     *
     * @param $file
     *
     * @throws UploadParserException
     */
    public function __construct($file)
    {
        Log::debug('parser started');
        $this->file = $file;
        $this->openZip();
        $this->indexData = $this->createZipIndex();
        Log::debug('loaded ' . $file);
    }


    public function __destruct()
    {
        $this->closeZip();
    }

    /**
     * @param bool $noCache
     *
     * @return array
     * @throws UploadParserException
     */
    public function getSongData($noCache = false)
    {
        if ($noCache || empty($this->songData)) {
            Log::debug('force parse song data');
            $this->songData = $this->parseSong();
        }

        return $this->songData;
    }

    /**
     * Parse the zip file for song metadata.
     *
     * @return array
     * @throws UploadParserException
     */
    protected function parseSong()
    {
        $songData = [];

        $info = $this->readFromZip('info.json');
        // workaround for info.json files with non UTF8 encoded characters
        // remove BOM
        $info = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $info);
        $info = json_decode($info, true);

        if ($info) {
            Log::debug('found info.json');

            $songData['songName'] = trim($info['songName']);
            $songData['songSubName'] = trim(($info['songSubName'] ?? ''));
            $songData['authorName'] = trim($info['authorName']);
            $songData['beatsPerMinute'] = $info['beatsPerMinute'] > 0 ? $info['beatsPerMinute'] : 0;
            $songData['difficultyLevels'] = [];
            $songData['hashMD5'] = null;
            $songData['hashSHA1'] = null;

            $songData['coverType'] = null;
            $songData['coverData'] = null;
            if ($this->zipHasFile($info['coverImagePath'])) {
                $songData['coverType'] = pathinfo($info['coverImagePath'], PATHINFO_EXTENSION);
                $songData['coverData'] = base64_encode($this->readFromZip($info['coverImagePath']));
            } else {
                throw new UploadParserException('Cannot find cover image ' . $info['coverImagePath'] . '!');
            }

            $cover = $this->readFromZip($info['coverImagePath']);
            list($width, $height, $type, $attr) = getimagesizefromstring($cover);
            if ($width !== $height) {
                throw new UploadParserException('Cover image is not a square!');
            }

            function map($v)
            {
                return $v['name'];
            }

            $indices = $this->createZipIndex();
            $files = array_map(function ($i) { return $i['name']; }, $indices);
            foreach ($files as $file)
            {
                $path_parts = pathinfo($file);
                $dir = $path_parts['dirname'];
                $ext = '.' . $path_parts['extension'];

                // Check for autosaves
                if (strpos($dir, 'autosaves') !== false) {
                    throw new UploadParserException('Please strip out autosaves folder!');
                }

                // Check for illegal file extensions
                $legal = ['.json', '.ogg', '.wav', '.jpg', '.jpeg', '.png'];
                if (!in_array($ext, $legal)) {
                    throw new UploadParserException('Found illegal file extension: ' . $ext);
                }
            }

            $hashBase = '';
            $hasSongPreviewData = false;
            foreach ($info['difficultyLevels'] as $difficultyLevel) {

                if ($this->zipHasFile($difficultyLevel['audioPath']) && $this->zipHasFile($difficultyLevel['jsonPath'])) {
                    if (!$hasSongPreviewData) {
                        $songData['songPreviewData'] = base64_encode($this->readFromZip($difficultyLevel['audioPath']));
                        $hasSongPreviewData = true;
                    }

                    $songData['difficultyLevels'][$difficultyLevel['difficulty']] = [
                        'difficulty' => $difficultyLevel['difficulty'],
                        'rank'       => $difficultyLevel['difficultyRank'],
                        'audioPath'  => $difficultyLevel['audioPath'],
                        'jsonPath'   => $difficultyLevel['jsonPath'],
                        'stats'      => [],
                    ];

                    $difficultyDataRaw = $this->readFromZip($difficultyLevel['jsonPath']);
                    if ($difficultyDataRaw) {
                        // add raw data to hashBase
                        $hashBase .= $difficultyDataRaw;

                        $songData['difficultyLevels'][$difficultyLevel['difficulty']]['stats'] = $this->analyzeDifficulty($difficultyDataRaw);
                    }
                } else {
                    Log::debug('error parsing difficulty level: ' . $difficultyLevel['difficulty']);
                    Log::debug('audio file "' . $difficultyLevel['audioPath'] . '": ' . $this->zipHasFile($difficultyLevel['audioPath']));
                    Log::debug('json file "' . $difficultyLevel['jsonPath'] . '": ' . $this->zipHasFile($difficultyLevel['jsonPath']));

                }
            }

            if ($hashBase) {
                $songData['hashMD5'] = md5($hashBase);
                $songData['hashSHA1'] = sha1_file($this->file);
            } else {
                // without hashes the parsing failed
                throw new UploadParserException('Song hash could not be calculated!');
            }
        }

        return $songData;
    }

    /**
     * @param string $difficultyData
     *
     * @return array
     */
    protected function analyzeDifficulty(string $difficultyData): array
    {
        $difficultyStats = [
            'time'      => 0,
            'slashstat' => 0,
            'events'    => 0,
            'notes'     => 0,
            'obstacles' => 0,
        ];

        $difficultyData = json_decode($difficultyData, true) ?: [];
        if ($difficultyData) {
            $noteTime = [];
            $noteType = [];
            foreach ($difficultyData['_notes'] as $data) {
                $noteTime[] = $data['_time'];
                @$noteType[$data['_cutDirection']]++; // suppress invalid index on first insert
            }

            $difficultyStats['time'] = $noteTime ? max($noteTime) : 0;
            $difficultyStats['slashstat'] = $noteType;
            $difficultyStats['events'] = count($difficultyData['_events'] ?? []);
            $difficultyStats['notes'] = count($difficultyData['_notes'] ?? []);
            $difficultyStats['obstacles'] = count($difficultyData['_obstacles'] ?? []);
        }

        return $difficultyStats;
    }

    /**
     * @param $fileName
     *
     * @return bool
     */
    protected function zipHasFile($fileName): bool
    {
        Log::debug('check zip index for file: ' . strtolower($fileName));
        Log::debug(array_keys($this->indexData));
        return array_key_exists(strtolower($fileName), $this->indexData);
    }

    /**
     * Read a single file from the zip.
     *
     * @param string $indexName
     *
     * @return string
     * @throws UploadParserException
     */
    protected function readFromZip(string $indexName): string
    {
        // every index is lowercase
        $indexName = strtolower($indexName);
        Log::debug('search index for: ' . $indexName);
        if ($this->zipFile && array_key_exists($indexName, $this->indexData)) {
            Log::debug('found index ' . $this->indexData[$indexName]['index']);
            return $this->zipFile->getFromIndex($this->indexData[$indexName]['index']);
        }
        throw new UploadParserException('Invalid index (' . $indexName . ')');
    }

    /**
     * Create a file index for the opened zip file.
     *
     * @return array
     * @throws UploadParserException
     */
    protected function createZipIndex(): array
    {
        if ($this->zipFile) {
            $index = [];
            for ($i = 0; $i < $this->zipFile->numFiles; $i++) {
                $stat = $this->zipFile->statIndex($i);
                // do not index empty files
                if ($stat['size']) {
                    $index[strtolower(basename($stat['name']))] = $stat;
                }
            }
            Log::debug($index);
            return $index;
        }

        throw new UploadParserException('Cannot create index. Zipfile not open');
    }

    /**
     * Open a ZipArchive.
     *
     * @throws UploadParserException
     */
    protected function openZip()
    {
        $zip = new ZipArchive();
        if (!$this->file || !$zip->open($this->file)) {
            throw new UploadParserException('Cannot open zip file (' . $this->file . ')');
        }
        $this->zipFile = $zip;

    }

    /**
     * Close open ZipArchive handle (if one exists).
     */
    protected function closeZip()
    {
        if ($this->zipFile && $this->zipFile->close()) {
            $this->zipFile = null;
        }
    }
}
