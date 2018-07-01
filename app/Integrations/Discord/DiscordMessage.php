<?php

namespace App\Integrations\Discord;


use Illuminate\Contracts\Support\Jsonable;

class DiscordMessage implements Jsonable
{
    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $avatarUrl;

    /**
     * @var string
     */
    protected $content;

    public function __construct(string $content = null, string $username = null, string $avatarUrl = null)
    {
        $this->username = $username;
        $this->avatarUrl = $avatarUrl;
        $this->content = $content;
    }

    /**
     * A message is considered empty if the content is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->content);
    }

    /**
     * @param string $username
     *
     * @return DiscordMessage
     */
    public function setUsername(string $username): DiscordMessage
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @param string $avatarUrl
     *
     * @return DiscordMessage
     */
    public function setAvatarUrl(string $avatarUrl): DiscordMessage
    {
        $this->avatarUrl = $avatarUrl;
        return $this;
    }

    /**
     * @param string $content
     *
     * @return DiscordMessage
     */
    public function setContent(string $content): DiscordMessage
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     *
     * @return string
     */
    public function toJson($options = 0): string
    {
        if (!$this->isEmpty()) {
            return json_encode([
                    'username'   => $this->username,
                    'avatar_url' => $this->avatarUrl,
                    'content'    => $this->content,
                ], $options) ?? '{}';
        }

        return '{}';
    }
}