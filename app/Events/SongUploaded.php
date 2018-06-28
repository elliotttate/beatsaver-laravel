<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SongUploaded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var array
     */
    protected $songData;

    /**
     * Create a new event instance.
     *
     * @param array $songData
     */
    public function __construct(array $songData)
    {
        //
        $this->songData = $songData;
    }

    public function getSongData()
    {
        return $this->songData;
    }
}
