<?php

namespace App;

use Illuminate\Support\Collection;

class SongListComposerApi extends SongListComposer
{
    /**
     * Converts a list of song keys into song info data.
     *
     * @param Collection $songs
     *
     * @return Collection
     */
    protected function prepareSongInfo(Collection $songs): Collection
    {
        $composer = new SongComposerApi();

        $songs->transform(function ($item, $key) use ($composer) {
            return $composer->get($item->songKey);
        });

        return $songs;
    }

}
