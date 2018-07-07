<?php

namespace App;

use App\Exceptions\UploadParserException;
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
    protected $fileName;

    /**
     * @var string
     */
    protected $fullPath;

    /**
     * UploadParser constructor.
     *
     * @param $file
     *
     * @throws UploadParserException
     */
    public function __construct($file)
    {
        $this->fileName = $file;
        $this->fullPath = storage_path('app/') . $file;
        $this->openZip();
        $this->indexData = $this->createZipIndex();
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
            $songData['songName'] = $info['songName'];
            $songData['songSubName'] = $info['songSubName'];
            $songData['authorName'] = $info['authorName'];
            $songData['beatsPerMinute'] = $info['beatsPerMinute'];
            $songData['difficultyLevels'] = [];
            $songData['hashMD5'] = null;
            $songData['hashSHA1'] = null;

            $songData['coverType'] = null;
            $songData['coverData'] = null;
            if ($this->zipHasFile($info['coverImagePath'])) {
                $songData['coverType'] = pathinfo($info['coverImagePath'], PATHINFO_EXTENSION);
                $songData['coverData'] = base64_encode($this->readFromZip($info['coverImagePath']));
            }

            $hashBase = '';
            foreach ($info['difficultyLevels'] as $difficultyLevel) {

                if ($this->zipHasFile($difficultyLevel['audioPath']) && $this->zipHasFile($difficultyLevel['jsonPath'])) {
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
                }
            }

            if ($hashBase) {
                $songData['hashMD5'] = md5($hashBase);
                $songData['hashSHA1'] = sha1_file($this->fullPath);
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

            $difficultyStats['time'] = max($noteTime);
            $difficultyStats['slashstat'] = $noteType;
            $difficultyStats['events'] = count($difficultyData['_events']);
            $difficultyStats['notes'] = count($difficultyData['_notes']);
            $difficultyStats['obstacles'] = count($difficultyData['_obstacles']);
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

        if ($this->zipFile && array_key_exists($indexName, $this->indexData)) {
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
            return $index;
        }

        throw new UploadParserException('Cannot create index. Zipfile not open');
    }

    /**
     * Open a ZipArchive.
     * The zip file has to be under storage/app !
     *
     * @throws UploadParserException
     */
    protected function openZip()
    {
        $zip = new ZipArchive();
        if (!$zip->open($this->fullPath)) {
            throw new UploadParserException('Cannot open zip file (' . $this->fullPath . ')');
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