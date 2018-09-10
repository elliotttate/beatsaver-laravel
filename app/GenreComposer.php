<?php

namespace App;

use Illuminate\Support\Collection;
use App\Contracts\Genre\GenreComposerContract;
use DB;

class GenreComposer implements GenreComposerContract
{

    /**
     * Get all genres
     *
     * @return Collection
     */
    public function getGenres(): Collection
    {
        $genres = DB::table('genres as g');

        return $genres->get();
    }
}