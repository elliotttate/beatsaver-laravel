<?php

namespace App\Integrations\Discord;


use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Log;

class DiscordWebhook implements DiscordContract
{
    /**
     * @var string
     */
    protected $url;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    public function __construct(string $webHookUrl)
    {
        $this->client = new \GuzzleHttp\Client([
            'timeout' => 5,
            'base_uri' =>'https://discordapp.com/api/webhooks/',
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);
        $this->url = $webHookUrl;
    }

    /**
     * Prepare a discord message with default username and avatar (if provided by config).
     *
     * @return DiscordMessage
     */
    public function prepareMessage()
    {
        return new DiscordMessage(null,config('beatsaver.discord.webhooks.username'),config('beatsaver.discord.webhooks.avatarUrl'));
    }

    /**
     * @param DiscordMessage $content
     *
     * @throws GuzzleException
     */
    public function postMessage(DiscordMessage $content)
    {
        try {
            $response = $this->client->request('POST', $this->url,[
                'body' => $content->toJson()
            ]);
            Log::debug('Body: ' . $response->getBody());
        } catch (RequestException $e) {
            Log::error($e->getMessage());
            Log::error($e->getRequest()->getHeaders());
            Log::error($e->getRequest()->getBody());
        }
    }
}