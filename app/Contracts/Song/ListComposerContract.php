<?php

namespace App\Contracts\Song;

use Illuminate\Support\Collection;

interface ListComposerContract
{
    const DEFAULT_LIMIT = 15;

    /**
     * Get song count of all songs.
     *
     * @return int
     */
    public function getSongCount(): int;

    /**
     * Get song count for user.
     *
     * @param int $userId
     *
     * @return int
     */
    public function getUserSongCount(int $userId): int;

    /**
     * @param array $parameter
     * @param int   $offset
     * @param int   $limit
     *
     * @return Collection
     */
    public function search(array $parameter, int $offset = 0, int $limit = ListComposerContract::DEFAULT_LIMIT): Collection;

    /**
     * Get songs ordered by play count descending.
     * If a song has multiple versions only get the latest one.
     *
     * @param int $offset
     * @param int $limit
     *
     * @return Collection
     */
    public function getTopPlayedSongs(int $offset = 0, int $limit = ListComposerContract::DEFAULT_LIMIT): Collection;

    /**
     * Get song keys ordered by play count descending.
     * If a song has multiple versions only get the latest one.
     *
     * @param int $offset
     * @param int $limit
     *
     * @return Collection
     */
    public function getTopPlayedKeys(int $offset = 0, int $limit = ListComposerContract::DEFAULT_LIMIT): Collection;

    /**
     * Get songs ordered by download count descending.
     * If a song has multiple versions only get the latest one.
     *
     * @param int $offset
     * @param int $limit
     *
     * @return Collection
     */
    public function getTopDownloadedSongs(int $offset = 0, int $limit = ListComposerContract::DEFAULT_LIMIT): Collection;

    /**
     * Get song keys ordered by download count descending.
     * If a song has multiple versions only get the latest one.
     *
     * @param int $offset
     * @param int $limit
     *
     * @return Collection
     */
    public function getTopDownloadedKeys(int $offset = 0, int $limit = ListComposerContract::DEFAULT_LIMIT): Collection;

    /**
     * Get songs ordered by creation date descending.
     * If a song has multiple versions only get the latest one.
     *
     * @param int $offset
     * @param int $limit
     *
     * @return Collection
     */
    public function getNewestSongs(int $offset = 0, int $limit = ListComposerContract::DEFAULT_LIMIT): Collection;

    /**
     * Get song keys ordered by creation date descending.
     * If a song has multiple versions only get the latest one.
     *
     * @param int $offset
     * @param int $limit
     *
     * @return Collection
     */
    public function getNewestKeys(int $offset = 0, int $limit = ListComposerContract::DEFAULT_LIMIT): Collection;

    /**
     * Get songs uploaded by user {$id] ordered by creation date.
     * If a song has multiple versions only get the latest one.
     *
     * @param int $userId
     * @param int $offset
     * @param int $limit
     *
     * @return Collection
     */
    public function getSongsByUser(int $userId, int $offset = 0, int $limit = ListComposerContract::DEFAULT_LIMIT): Collection;

    /**
     * Get song keys uploaded by user {$id] ordered by creation date.
     * If a song has multiple versions only get the latest one.
     *
     * @param int $userId
     * @param int $offset
     * @param int $limit
     *
     * @return Collection
     */
    public function getKeysByUser(int $userId, int $offset = 0, int $limit = ListComposerContract::DEFAULT_LIMIT): Collection;
}
