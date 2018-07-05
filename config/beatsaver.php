<?php

return [
    'githubUrl'         => env('BS_GITHUB_URL', 'https://github.com/Byorun/beatsaver-laravel'),
    'legalEmail'        => env('BS_LEAGAL_EMAIL'),
    'songCacheDuration' => env('BS_SONG_CACHE_DURATION', \App\SongComposer::CACHE_DURATION),
    'discord'           => [
        'bot'      => [
            'enabled'     => env('BS_DISCORD_BOT_ENABLED', false),
            'url'         => env('BS_DISCORD_BOT_URL', 'http:/localhost/'),
            'bearerToken' => env('BS_DISCORD_BOT_TOKEN', 'secret'),
        ],
        'webhooks' => [
            'enabled'   => env('BS_DISCORD_WEBHOOKS_ENABLED', false),
            'username'  => env('BS_DISCORD_WEBHOOKS_USERNAME', null),
            'avatarUrl' => env('BS_DISCORD_WEBHOOKS_AVATAR', null),
            'channel'   => env('BS_DISCORD_WEBHOOKS_CHANNEL'),
        ]
    ],

];