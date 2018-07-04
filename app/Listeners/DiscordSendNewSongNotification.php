<?php

namespace App\Listeners;

use App\Events\SongUploaded;
use App\Integrations\Discord\DiscordBot;
use App\Integrations\Discord\DiscordWebhook;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class DiscordSendNewSongNotification
{
    protected $bot;
    protected $webhook;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //@todo unify interface so that we don't have to worry about different discord bot types

        $this->bot = new DiscordBot(config('beatsaver.discord.bot.url'), config('beatsaver.discord.bot.bearerToken'));
        $this->webhook = new DiscordWebhook(config('beatsaver.discord.webhooks.channel'));
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

            $songData = $event->getSongData();

            if (config('beatsaver.discord.bot.enabled')) {
                $this->bot->eventNewSong($songData);
            }

            if (config('beatsaver.discord.webhooks.enabled')) {
                $message = $this->webhook->prepareMessage();
                $message->setContent("New song uploaded by **{$songData['uploader']}** : " . route('browse.detail', ['key' => $songData['key']]));
                $this->webhook->postMessage($message);
            }
        } catch (GuzzleException $e) {
            Log::error($e->getMessage());
        }
    }
}
