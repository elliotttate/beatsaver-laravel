<?php

namespace App;


use App\Models\Song;

class SongListComposer
{
    /**
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public function getTopPlayedSongs(int $offset, int $limit = 20): array
    {
        $topDownloaded = Song::with([
            'details' => function ($query) {
                $query->orderByDesc('play_count')->first();
            },
        ]);

        $songIds = $topDownloaded->offset($offset)->limit($limit)->pluck('id');

        return $this->convertSongIds($songIds->toArray());
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public function getTopDownloadedSongs(int $offset, int $limit = 20): array
    {
        $topDownloaded = Song::with([
            'details' => function ($query) {
                $query->orderByDesc('download_count')->first();
            },
        ]);

        $songIds = $topDownloaded->offset($offset)->limit($limit)->pluck('id');

        return $this->convertSongIds($songIds->toArray());
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public function getNewestSongs(int $offset, int $limit = 20): array
    {
        $topDownloaded = Song::with([
            'details' => function ($query) {
                $query->orderByDesc('created_at')->first();
            },
        ]);

        $songIds = $topDownloaded->offset($offset)->limit($limit)->pluck('id');

        return $this->convertSongIds($songIds->toArray());
    }

    /**
     * @param array $ids
     *
     * @return array
     */
    protected function convertSongIds(array $ids): array
    {
        $composer = new SongComposer();
        $songs = [];
        foreach ($ids as $songId) {
            $songs[] = $composer->get($songId);

        }
        return $songs;
    }

}