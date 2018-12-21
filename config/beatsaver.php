<?php

return [
    'tracking'   => env('BS_TRACKING', false),
    'githubUrl'  => env('BS_GITHUB_URL', 'https://github.com/Byorun/beatsaver-laravel'),
    'legalEmail' => env('BS_LEAGAL_EMAIL'),
    'cache'      => [
        'duration' => env('BS_SONG_CACHE_DURATION', \App\SongComposer::CACHE_DURATION),
        'top100'   => [
            'newest'    => env('BS_SONG_CACHE_TOP100_NEWEST', true),
            'downloads' => env('BS_SONG_CACHE_TOP100_DOWNLOADS', true),
            'played'    => env('BS_SONG_CACHE_TOP100_PLAYED', true),
        ],
    ],
    'discord'    => [
        'bot'      => [
            'enabled'     => env('BS_DISCORD_BOT_ENABLED', false),
            'url'         => env('BS_DISCORD_BOT_URL', 'http:/localhost/'),
            'bearerToken' => env('BS_DISCORD_BOT_TOKEN', 'secret'),
        ],
        'webhooks' => [
            'enabled'   => env('BS_DISCORD_WEBHOOKS_ENABLED', false),
            'username'  => env('BS_DISCORD_WEBHOOKS_USERNAME', null),
            'avatarUrl' => env('BS_DISCORD_WEBHOOKS_AVATAR', null),
            'channel'   => env('BS_DISCORD_WEBHOOKS_CHANNEL', ''),
        ],
    ],
    'scoreSaber' => [
        'authKey'              => env('BS_SCORESABER_AUTH_KEY', null),
        'enabled'              => env('BS_SCORESABER_ENABLED', false),
        'syncMinDownloadCount' => env('BS_SCORESABER_SYNC_MIN_DOWNLOAD', 100),
    ],
    'legacy'     => [
        'enabled'  => env('BS_LEGACY_IMPORT_ENABLED', false),
        'songPath' => env('BS_LEGACY_SONG_PATH', storage_path()),
    ],
];