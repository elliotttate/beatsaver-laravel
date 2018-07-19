<?php

namespace App\Contracts\Song;

use App\Models\Song;
use App\Models\User;

interface ComposerContract
{

    /**
     * Default cache duration in minutes
     */
    const CACHE_DURATION = 60;

    const VOTE_UP = 1;
    const VOTE_DOWN = 0;

    const SONG_CREATED = 0;
    const ERROR_INVALID_FORMAT = 1;
    const ERROR_ALREADY_EXISTS = 2;
    const ERROR_INVALID_USER = 3;
    const SONG_UPDATED = 4;
    const SONG_UPDATE_FAILED = 5;

    /**
     * Get the song info from $key, return cached data (if exists).
     * If $noCache is true the cache will be updated from the database
     * and a fresh result is returned.
     *
     * @param string $key
     * @param bool   $noCache
     *
     * @return array
     */
    public function get(string $key, $noCache = false): array;

    /**
     * @param array  $metadata
     * @param string $file
     *
     * @return array
     */
    public function create(array $metadata, string $file): array;

    /**
     * @param Song  $song
     * @param array $metadata
     *
     * @return array
     */
    public function update(Song $song, array $metadata): array;

    /**
     * @param string $key
     *
     * @return bool
     */
    public function delete(string $key): bool;

    /**
     * @param string $key
     * @param User   $user
     * @param int    $direction
     *
     * @return bool
     */
    public function vote(string $key, User $user, int $direction): bool;

    /**
     * @param $key
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function serveFileDownload($key);

    /**
     * @param int $code
     *
     * @return string
     */
    public function getErrorText(int $code): string;

}