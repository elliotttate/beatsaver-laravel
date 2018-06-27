<?php

namespace App;


use App\Models\Song;
use App\Models\SongDetail;

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
        return [];
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

        $composer = new SongComposer();
        $songs = [];
        foreach ($songIds as $songId) {
            $songs[] = $composer->get($songId);
        }

        return $songs;
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public function getNewestSongs(int $offset, int $limit = 20): array
    {
        return $this->dummySongs($offset, $limit);
    }

    /**
     * produces a dummy song array for testing
     *
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    protected function dummySongs(int $offset, int $limit = 20): array
    {
        $composer = new SongComposer();
        $dummySongs = [];
        for ($i = 0; $i < $limit; $i++) {
            $dummySongs[] = $composer->get($i,2);
        }

        return $dummySongs;
    }
}