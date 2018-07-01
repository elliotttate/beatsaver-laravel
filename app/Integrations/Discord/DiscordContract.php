<?php

namespace App\Integrations\Discord;


interface DiscordContract
{
    public function postMessage(DiscordMessage $content);
}