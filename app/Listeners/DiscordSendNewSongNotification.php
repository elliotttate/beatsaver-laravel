<?php

namespace App\Listeners;

use App\DiscordBot;
use App\Events\SongUploaded;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class DiscordSendNewSongNotification
{
    protected $bot;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->bot = new DiscordBot(config('beatsaver.discord.botUrl'),config('beatsaver.discord.botToken'));
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
        try {
            if(config('beatsaver.discord.botEnabled')) {
                $this->bot->eventNewSong($event->getSongData());
            }
        } catch (GuzzleException $e) {
            Log::error($e->getMessage());
        }
    }
}
