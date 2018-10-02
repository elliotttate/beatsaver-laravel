<?php

namespace App;

use App\Contracts\Song\ComposerContract;
use App\Events\SongUpdated;
use App\Events\SongUploaded;
use App\Models\Song;
use App\Models\SongDetail;
use App\Models\User;
use App\Models\Vote;
use Cache;
use File;
use Log;
use Storage;

class SongComposer implements ComposerContract
{

    /**
     * Get the song info from $key, return cached data (if exists).
     * If updateCache is true the cache will be updated from the database
     * and a fresh result is returned.
     *
     * @param string $key
     * @param bool   $noCache
     *
     * @return array
     */
    public function get(string $key, $noCache = false): array
    {
        if ($noCache) {
            Log::debug('Force Cache Update: ' . $key);
            $song = $this->compose($key);
            return $song;
        }

        $split = $this->parseKey($key);

        // no version selected try to find default one
        if (is_null($split['detailId'])) {
            $defaultKey = Cache::tags(['song-' . $split['songId']])->get('default');
            Log::debug("Key $key Default = $defaultKey");
            if ($defaultKey) {
                $split = $this->parseKey($defaultKey);
            }
        }

        $song = Cache::tags(['song-' . $split['songId']])->get('info');
        if ($song) {
            Log::debug('load from cache ' . $key);
            foreach ($song['version'] as &$version) {
                $version['upVotes'] = Cache::tags(['song-' . $split['songId']])->get("votes-{$split['detailId']}-1", 0);
                $version['downVotes'] = Cache::tags(['song-' . $split['songId']])->get("votes-{$split['detailId']}-0", 0);
                $version['downloadCount'] = Cache::tags(['song-' . $split['songId']])->get("downloads-{$split['detailId']}", 0);
            }
            return $song;
        }

        Log::debug('cache empty ' . $key . ' try compose');
        if ($song = $this->compose($key)) {
            return $song;
        }
        Log::debug('compose failed');

        return [];
    }

    /**
     * Store a new or update an existing song in the database.
     *
     * @param array  $metadata
     * @param string $file
     *
     * @return array
     */
    protected function createOrUpdate(array $metadata, string $file): array
    {
        //check if song fingerprint already exists
        if (!empty($file)) {
            try {
                $parser = new UploadParser($file);
                $songData = $parser->getSongData();
            } catch (Exceptions\UploadParserException $e) {
                Log::error($e->getMessage());
                return ['status' => static::ERROR_INVALID_FORMAT];
            }

            //check if song hash already exists
            // if a songId is present assume that we want to create an update and check the SHA1 hash too
            if ($metadata['songId'] && SongDetail::where('hash_md5', $songData['hashMD5'])->where('hash_sha1', $songData['hashSHA1'])->first()) {
                return ['status' => static::ERROR_ALREADY_EXISTS];
            }

            // if a songId is not present assume that we want to create a new song and only check the MD5 hash
            if (!$metadata['songId'] && SongDetail::where('hash_md5', $songData['hashMD5'])->first()) {
                return ['status' => static::ERROR_ALREADY_EXISTS];
            }

            if (empty($songData)) {
                return ['status' => static::ERROR_INVALID_FORMAT];
            }
        }

        /**
         * @var $user User
         */
        if (!$user = User::find($metadata['userId'])) {
            return ['status' => static::ERROR_INVALID_USER];
        }

        // do song master data update
        $song = $user->songs()->updateOrCreate([
            'id' => $metadata['songId'],
        ], [
            'name'        => trim(strip_tags($metadata['name'])),
            'description' => trim(strip_tags($metadata['description'])),
        ]);

        if (!empty($metadata['created_at'])) {
            $song->setCreatedAt($metadata['created_at']);
            $song->save();
        }

        // return song early if we only update meta data
        if (empty($file)) {
            return [
                'status' => static::SONG_UPDATED,
                'key'    => $song->id,
            ];
        }

        // create song data entry
        $songDetails = new SongDetail([
            'song_name'         => $songData['songName'],
            'song_sub_name'     => $songData['songSubName'],
            'author_name'       => $songData['authorName'],
            'cover'             => $songData['coverType'],
            'bpm'               => $songData['beatsPerMinute'],
            'difficulty_levels' => json_encode($songData['difficultyLevels']),
            'hash_md5'          => $songData['hashMD5'],
            'hash_sha1'         => $songData['hashSHA1'],
        ]);

        if (!empty($metadata['created_at'])) {
            $songDetails->setCreatedAt($metadata['created_at']);
        }

        $song->details()->save($songDetails);

        if (!Storage::disk()->exists('public/songs/' . $song->id)) {
            Storage::disk()->makeDirectory('public/songs/' . $song->id);
        }

        File::move($file, storage_path('app/public/songs') . "/{$song->id}/{$song->id}-{$songDetails->id}.zip");
        Storage::disk()->put("public/songs/{$song->id}/{$song->id}-{$songDetails->id}.{$songData['coverType']}", base64_decode($songData['coverData']));

        return [
            'status' => static::SONG_CREATED,
            'key'    => $song->id . '-' . $songDetails->id,
        ];
    }

    /**
     * @param array  $metadata
     * @param string $file
     *
     * @return array
     */
    public function create(array $metadata, string $file): array
    {
        $songData = $this->createOrUpdate($metadata, $file);
        if ($songData['status'] == static::SONG_CREATED) {
            $songData['song'] = $this->compose($songData['key']);
            event(new SongUploaded($songData['song']));
        }

        return $songData;
    }

    /**
     * @param Song  $song
     * @param array $metadata
     *
     * @return array
     */
    public function update(Song $song, array $metadata): array
    {
        $songData = ['status' => static::SONG_UPDATE_FAILED];
        if ($song) {
            // update song version if we have a new song archive
            if (!empty($metadata['updateFile'])) {
                Log::debug('found update file');

                $songData = $this->createOrUpdate($metadata, $metadata['updateFile']);
                if ($songData['status'] == static::SONG_CREATED) {
                    $songData['status'] = static::SONG_UPDATED;
                    $songData['song'] = $this->compose($songData['key']);
                    event(new SongUpdated($songData['song']));
                }
                return $songData;
            }

            Log::debug('only update meta data');
            // only update meta data
            $song->name = $metadata['name'];
            $song->description = empty($metadata['description']) ? '' : $metadata['description'];

            if ($song->save()) {
                $songData['status'] = static::SONG_CREATED;
                $songData['song'] = $this->compose($song->id);
                event(new SongUploaded($songData['song']));
                return $songData;
            }
        }

        Log::debug('no song');

        return $songData;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function delete(string $key): bool
    {
        $split = $this->parseKey($key);

        if (is_null($split['detailId'])) {
            if ($destroyed = Song::destroy($split['songId'])) {
                $filesToDelete = collect(Storage::allFiles())->filter(function ($value, $key) use ($split) {
                    $pi = pathinfo($value);
                    return preg_match("/^{$split['songId']}-(.*)\.(.*)/", strtolower($pi['basename']));
                });
                if (Storage::delete($filesToDelete->toArray())) {
                    Cache::tags(['song-' . $split['songId']])->flush();
                    return true;
                }
            }
            return false;
        } else {
            if ($destroyed = SongDetail::destroy($split['detailId'])) {
                $filesToDelete = collect(Storage::allFiles())->filter(function ($value, $key) use ($split) {
                    $pi = pathinfo($value);
                    return preg_match("/^{$split['songId']}-{$split['detailId']}\.(.*)/", strtolower($pi['basename']));
                });
                if (Storage::delete($filesToDelete->toArray())) {
                    // @todo delete cache
                    return true;
                }
            }
            return false;
        }
    }

    /**
     * @param string $key
     * @param User   $user
     * @param int    $direction
     *
     * @return bool
     */
    public function vote(string $key, User $user, int $direction): bool
    {
        $split = $this->parseKey($key);

        if (is_null($split['detailId'])) {
            return false;
        }

        // prevent cache vote count abuse
        $vote = Vote::where('song_id', $split['songId'])
            ->where('detail_id', $split['detailId'])
            ->where('user_id', $user->id)
            ->first();

        if ($vote && $vote->direction == $direction) {
            return true;
        }

        if (!$songDetail = SongDetail::where('song_id', $split['songId'])->where('id', $split['detailId'])->first()) {
            return false;
        }

        if ($direction == static::VOTE_UP) {
            $vote = $songDetail->voteUp($user);
        } elseif ($direction == static::VOTE_DOWN) {
            $vote = $songDetail->voteDown($user);
        } else {
            return false;
        }

        if ($vote->wasRecentlyCreated && $direction == static::VOTE_UP) {
            Cache::tags(['song-' . $split['songId']])->increment("votes-{$split['detailId']}-1", 1);
        } elseif ($vote->wasRecentlyCreated && $direction == static::VOTE_DOWN) {
            Cache::tags(['song-' . $split['songId']])->increment("votes-{$split['detailId']}-0", 1);
        } else {
            Log::debug($direction);
            Cache::tags(['song-' . $split['songId']])->increment("votes-{$split['detailId']}-{$direction}", 1);
            $decrement = $direction == static::VOTE_UP ? static::VOTE_DOWN : static::VOTE_UP;
            Cache::tags(['song-' . $split['songId']])->decrement("votes-{$split['detailId']}-{$decrement}", 1);
            Log::debug($decrement);
        }

        return true;
    }

    /**
     * @param $key
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function serveFileDownload($key)
    {
        $split = $this->parseKey($key);

        // @todo stop/prevent download count faking
        if ($downloadCount = SongDetail::where('id', $split['detailId'])->where('song_id', $split['songId'])->increment('download_count', 1)) {
            Cache::tags(['song-' . $split['songId']])->increment("downloads-{$split['detailId']}", 1);
            return \response()->redirectTo(asset("storage/songs/{$split['songId']}/{$split['songId']}-{$split['detailId']}.zip"));
        }

        return abort(404);
    }

    /**
     * Compose a song info for an existing song.
     *
     * @param string $key
     *
     * @return array
     */
    protected function compose(string $key): array
    {
        $split = $this->parseKey($key);

        $song = Song::with([
            'uploader',
            'details' => function ($query) use ($split) {
                $query->withCount([
                    'votes as upVotes'   => function ($query) {
                        $query->where('direction', 1);
                    },
                    'votes as downVotes' => function ($query) {
                        $query->where('direction', 0);
                    }
                ]);
                if ($split['detailId']) {
                    $query->where('id', $split['detailId']);
                } else {
                    $query->orderByDesc('id');
                }
            },
        ])->findOrFail($split['songId']);

        // empty song details, return early because valid songedata cannot be returned
        if ($song->details->isEmpty()) {
            return [];
        }

        $songData = [
            'id'          => $song->id,
            'key'         => $song->id . '-' . $song->details->first()->id,
            'name'        => $song->name,
            'description' => $song->description,
            'uploader'    => $song->uploader->name,
            'uploaderId'  => $song->uploader->id,
            'version'     => [

            ],
            'createdAt'   => $song->created_at,
        ];

        foreach ($song->details as $detail) {
            $songData['version'][$song->id . '-' . $detail->id] = [
                'songName'       => $detail->song_name,
                'songSubName'    => $detail->song_sub_name,
                'authorName'     => $detail->author_name,
                'bpm'            => $detail->bpm,
                'difficulties'   => json_decode($detail->difficulty_levels, true) ?? [],
                'downloadCount'  => $detail->download_count,
                'playedCount'    => $detail->play_count,
                'upVotes'        => $detail->upVotes,
                'upVotesTotal'   => 0, //@todo get votes for song id instead of detailId
                'downVotes'      => $detail->downVotes,
                'downVotesTotal' => 0, //@todo get votes for song id instead of detailId
                'createdAt'      => $detail->created_at,
                'linkUrl'        => route('browse.detail', ['key' => $song->id . '-' . $detail->id]),
                'downloadUrl'    => route('download', ['key' => $song->id . '-' . $detail->id]),
                'coverUrl'       => asset("storage/songs/{$song->id}/{$song->id}-{$detail->id}.$detail->cover"),
                'hashMd5'        => $detail->hash_md5,
                'hashSha1'       => $detail->hash_sha1,
            ];
        }

        $this->updateCache($songData);

        return $songData;
    }

    /**
     * @param string $key
     *
     * @return array
     */
    protected function parseKey(string $key): array
    {
        $split = explode('-', $key, 2);

        $songId = $split[0];
        $detailId = $split[1] ?? null;

        return compact('songId', 'detailId');
    }

    /**
     * @param int $code
     *
     * @return string
     */
    public function getErrorText(int $code): string
    {
        $translation = [
            static::SONG_CREATED         => 'Song created successfully.',
            static::ERROR_ALREADY_EXISTS => 'The same song already exists.',
            static::ERROR_INVALID_FORMAT => 'The song format is invalid.',
            static::ERROR_INVALID_USER   => 'The user is invalid',
            static::SONG_UPDATED         => 'Song updated successfully',
        ];
        return $translation[$code] ?? 'Code ' . $code . ' Unknown';
    }

    /**
     * @param array $song
     */
    protected function updateCache(array $song)
    {
        $split = $this->parseKey($song['key']);

        Cache::tags(['song-' . $split['songId']])->put('default', $song['key'], config('beatsaver.songCacheDuration'));
        Cache::tags(['song-' . $split['songId']])->put('info', $song, config('beatsaver.songCacheDuration'));
        foreach ($song['version'] as $version) {
            Cache::tags(['song-' . $split['songId']])->put("votes-{$split['detailId']}-1", $version['upVotes'], config('beatsaver.songCacheDuration'));
            Cache::tags(['song-' . $split['songId']])->put("votes-{$split['detailId']}-0", $version['downVotes'], config('beatsaver.songCacheDuration'));
            Cache::tags(['song-' . $split['songId']])->put("downloads-{$split['detailId']}", $version['downloadCount'], config('beatsaver.songCacheDuration'));
        }
    }

}