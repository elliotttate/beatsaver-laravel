<?php

namespace App\Listeners;

use App\Events\SongUploaded;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateSongCache
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param SongUploaded $event
     *
     * @return void
     */
    public function handle(SongUploaded $event)
    {
        //
    }
}
