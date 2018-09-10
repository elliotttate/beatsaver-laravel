<?php

namespace App\Contracts\Genre;

use Illuminate\Support\Collection;

interface GenreComposerContract
{

    /**
     * Default cache duration in minutes
     */
    const CACHE_DURATION = 60;
   
    /**
     * Get all genres.
     *
     * @return Collection
     */
    public function getGenres(): Collection;
}
