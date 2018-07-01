<?php

namespace App;

use DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

class SongListComposer
{
    const DEFAULT_LIMIT = 15;

    /**
     * Get songs ordered by play count descending.
     * If a song has multiple versions only get the latest one.
     *
     * @param int $offset
     * @param int $limit
     *
     * @return Collection
     */
    public function getTopPlayedSongs(int $offset, int $limit = SongListComposer::DEFAULT_LIMIT): Collection
    {
        $orderBy = 'play_count';
        $songs = $this->prepareQuery($orderBy, $offset, $limit);

        return $this->prepareSongInfo($songs->get());
    }

    /**
     * Get songs ordered by download count descending.
     * If a song has multiple versions only get the latest one.
     *
     * @param int $offset
     * @param int $limit
     *
     * @return Collection
     */
    public function getTopDownloadedSongs(int $offset, int $limit = SongListComposer::DEFAULT_LIMIT): Collection
    {
        $orderBy = 'download_count';

        $songs = $this->prepareQuery($orderBy, $offset, $limit);


        return $this->prepareSongInfo($songs->get());
    }

    /**
     * Get songs ordered by creation date descending.
     * If a song has multiple versions only get the latest one.
     *
     * @param int $offset
     * @param int $limit
     *
     * @return Collection
     */
    public function getNewestSongs(int $offset, int $limit = SongListComposer::DEFAULT_LIMIT): Collection
    {
        $orderBy = 'created_at';
        $songs = $this->prepareQuery($orderBy, $offset, $limit);

        return $this->prepareSongInfo($songs->get());
    }

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
    public function getSongsByUser(int $userId, int $offset, int $limit = SongListComposer::DEFAULT_LIMIT): Collection
    {
        $orderBy = 'created_at';
        $songs = $this->prepareQuery($orderBy, $offset, $limit)
            ->leftJoin('songs as s', 'sd.song_id', '=', 's.id')
            ->where('s.user_id', $userId);

        return $this->prepareSongInfo($songs->get());
    }

    /**
     * Converts a list of song keys into song info data.
     *
     * @param Collection $songs
     *
     * @return Collection
     */
    protected function prepareSongInfo(Collection $songs): Collection
    {
        $composer = new SongComposer();

        $songs->transform(function ($item, $key) use ($composer) {
            return $composer->get($item->songKey);
        });

        return $songs;
    }

    /**
     * Prepare a base query every song list uses.
     *
     * WARNING: never pass unchecked, user defined data into {$orderBy} since it opens
     * the query for injection attacks!
     *
     * @param string $orderBy
     * @param int    $offset
     * @param int    $limit
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function prepareQuery(string $orderBy, int $offset, int $limit): Builder
    {
        return DB::table('song_details as sd')->select(DB::raw("concat(sd.song_id,'-',max(sd.id))as songKey"))
            ->groupBy(['sd.song_id'])->orderByRaw("(select {$orderBy} from song_details where id = max(sd.id)) desc")
            ->offset($offset)->limit($limit);

    }

}