<?php

namespace App;


use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Log;

class DiscordBot
{
    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $authToken;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    public function __construct(string $url, string $authToken)
    {
        $this->client = new \GuzzleHttp\Client();
        $this->url = $url;
        $this->authToken = $authToken;
    }


    /**
     * @param array $songData
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function eventNewSong(array $songData)
    {
        $uploaderName = $songData['uploader'];
        $songKey = $songData['downloadKey'];
        try {
            Log::debug($this->url . 'SongUploaded');
            $response = $this->client->request('POST', $this->url . 'SongUploaded', [
                'timeout' => 5,
                'json' => [
                    'creatorName' => $uploaderName,
                    'songUrl'     => route('browse.detail', ['key' => $songKey])
                ]
            ]);
            Log::debug($response->getBody());
        } catch (RequestException $e) {
            Log::error($e->getMessage());
        }
    }
}