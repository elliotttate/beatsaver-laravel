<?php

namespace App;

use App\Models\Song;
use App\Models\SongDetail;
use App\Models\User;
use App\Models\Vote;
use Storage;

class SongComposer
{

    /**
     * Default cache duration in minutes
     */
    const CACHE_DURATION = 60;
    const VOTE_UP = 1;
    const VOTE_DOWN = 0;

    /**
     * Get the song info from $key, return cached data (if exists).
     *
     * @param string $key
     *
     * @return array
     */
    public function get(string $key): array
    {
        try {
            $song = cache("song.{$key}.info");
            if ($song) {
                \Log::debug('load from cache ' . $key);
                $song['upVotes'] = cache("song.{$key}.votes-1", $song['upVotes']);
                $song['downVotes'] = cache("song.{$key}.votes-0", $song['downVotes']);
                return $song;
            }
            \Log::debug('cache empty ' . $key . ' try compose');
            if ($song = $this->compose($key)) {
                // update cache after compose
                cache()->put("song.{$song['key']}.info", $song, config('beatsaver.songCacheDuration'));
                cache()->put("song.{$song['key']}.votes-1", $song['upVotes'], config('beatsaver.songCacheDuration'));
                cache()->put("song.{$song['key']}.votes-0", $song['downVotes'], config('beatsaver.songCacheDuration'));
                return $song;
            }
            \Log::debug('compose failed');
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return [];
        }

        return [];
    }

    /**
     * Store a new song in the database.
     *
     * @param array $metadata
     * @param array $songData
     *
     * @return array
     */
    public function create(array $metadata, array $songData): array
    {
        //check if song hash already exists
        if (SongDetail::where('hash_md5', $songData['hashMD5'])->where('hash_sha1', $songData['hashSHA1'])->first()) {
            return [];
        }

        $user = User::findOrFail($metadata['userId']);
        $song = new Song([
            'name'        => $metadata['name'],
            'description' => $metadata['description'],
        ]);
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

        $user->songs()->save($song);
        $song->details()->save($songDetails);
        if (!Storage::disk()->exists('public/songs')) {
            Storage::disk()->makeDirectory('public/songs');
        }
        Storage::disk()->move($metadata['tempFile'], "public/songs/{$song->id}-{$songDetails->id}.zip");
        Storage::disk()->put("public/songs/{$song->id}-{$songDetails->id}.{$songData['coverType']}", base64_decode($songData['coverData']));

        return [
            'id'             => $song->id,
            'key'            => $song->id . '-' . $songDetails->id,
            'name'           => $song->name,
            'description'    => $song->description,
            'uploader'       => $song->uploader->name,
            'uploaderId'     => $song->uploader->id,
            'songName'       => $songDetails->song_name,
            'songSubName'    => $songDetails->song_sub_name,
            'authorName'     => $songDetails->author_name,
            'difficulties'   => array_keys($songData['difficultyLevels']), // @todo we may need the complete stats here in the future
            'downloadCount'  => 0,
            'playedCount'    => 0,
            'upVotes'        => 0,
            'upVotesTotal'   => 0,
            'downVotes'      => 0,
            'downVotesTotal' => 0,
            'version'        => $song->details->count(), //@todo fix version if $detailId is specified
            'createdAt'      => $songDetails->created_at,
            'linkUrl'        => route('browse.detail', ['key' => $song->id . '-' . $songDetails->id]),
            'downloadUrl'    => route('download', ['key' => $song->id . '-' . $songDetails->id]),
            'coverUrl'       => asset("storage/songs/{$song->id}-{$songDetails->id}.$songDetails->cover"),

        ];
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
                return Storage::delete($filesToDelete->toArray());
            }
            return false;
        } else {
            if ($destroyed = SongDetail::destroy($split['detailId'])) {
                $filesToDelete = collect(Storage::allFiles())->filter(function ($value, $key) use ($split) {
                    $pi = pathinfo($value);
                    return preg_match("/^{$split['songId']}-{$split['detailId']}\.(.*)/", strtolower($pi['basename']));
                });
                return Storage::delete($filesToDelete->toArray());
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

        $vote = Vote::updateOrCreate([
            'song_id'   => $split['songId'],
            'detail_id' => $split['detailId'],
            'user_id'   => $user->id,
        ], ['direction' => $direction]);

        try {
            if ($vote->wasRecentlyCreated && $direction == static::VOTE_UP) {
                cache()->increment("song.{$key}.votes-1", 1);
            } elseif ($vote->wasRecentlyCreated && $direction == static::VOTE_DOWN) {
                cache()->increment("song.{$key}.votes-0", 1);
            } else {
                cache()->increment("song.{$key}.votes-{$direction}", 1);
                $decrement = $direction == static::VOTE_UP ? static::VOTE_DOWN : static::VOTE_UP;
                cache()->decrement("song.{$key}.votes-{$decrement}", 1);
            }
        } catch (\Exception $e) {
            // ignore cache errors, the next song update will fix them automatically
        }

        return true;
    }

    /**
     * @param $key
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function serveFileDownload($key)
    {
        $split = $this->parseKey($key);

        // @todo stop/prevent download count faking
        if ($downloadCount = SongDetail::where('id', $split['detailId'])->where('song_id', $split['songId'])->increment('download_count', 1)) {
            //@todo use download count to cache
            return \response()->download(storage_path("app/public/songs") . "/$key.zip");
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

        /**
         * @var $details SongDetail
         */
        $details = $song->details->first();
        $difficulties = array_keys(json_decode($details->difficulty_levels, true));

        return [
            'id'             => $song->id,
            'key'            => $song->id . '-' . $details->id,
            'name'           => $song->name,
            'description'    => $song->description,
            'uploader'       => $song->uploader->name,
            'uploaderId'     => $song->uploader->id,
            'songName'       => $details->song_name,
            'songSubName'    => $details->song_sub_name,
            'authorName'     => $details->author_name,
            'difficulties'   => $difficulties, // @todo we may need the complete stats here in the future
            'downloadCount'  => $details->download_count,
            'playedCount'    => $details->play_count,
            'upVotes'        => $details->upVotes,
            'upVotesTotal'   => 0, //@todo get votes for song id instead of detailId
            'downVotes'      => $details->downVotes,
            'downVotesTotal' => 0, //@todo get votes for song id instead of detailId
            'version'        => $song->details->count(), //@todo fix version if $detailId is specified
            'createdAt'      => $details->created_at,
            'linkUrl'        => route('browse.detail', ['key' => $song->id . '-' . $details->id]),
            'downloadUrl'    => route('download', ['key' => $song->id . '-' . $details->id]),
            'coverUrl'       => asset("storage/songs/{$song->id}-{$details->id}.$details->cover"),
        ];

    }

    /**
     * @param string $key
     *
     * @return array
     */
    protected function parseKey(string $key)
    {
        $split = explode('-', $key, 2);

        $songId = $split[0];
        $detailId = $split[1] ?? null;

        return compact('songId', 'detailId');
    }
}