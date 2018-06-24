<?php

namespace App;


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
        return $this->dummySongs($offset, $limit);
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public function getTopDownloadedSongs(int $offset, int $limit = 20): array
    {
        return $this->dummySongs($offset, $limit);
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
            $dummySongs[] = $composer->get($i);
        }

        return $dummySongs;
    }
}