<?php

return [
    'githubUrl'         => env('BS_GITHUB_URL', 'https://github.com/Byorun/beatsaver-laravel'),
    'legalEmail'        => env('BS_LEAGAL_EMAIL'),
    'songCacheDuration' => env('BS_SONG_CACHE_DURATION', \App\SongComposer::CACHE_DURATION),
    'discord'           => [
        'botEnabled' => env('DISCORD_BOT_ENABLED', false),
        'botUrl'     => env('DISCORD_BOT_URL', 'http:/localhost'),
        'botToken'   => env('DISCORD_BOT_TOKEN', 'secret'),
    ],

];