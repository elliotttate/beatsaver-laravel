<?php

namespace App;

class SongComposer
{
    /**
     * @param int  $id
     * @param null $detailId
     *
     * @return array
     */
    public function get(int $id, $detailId = null)
    {
        return $this->dummySong();
    }

    /**
     * produces a dummy song for testing
     *
     * @return array
     */
    protected function dummySong(): array
    {
        return [
            'id'            => 1,
            'name'          => 'My Awesome Song',
            'uploader'      => 'Dummy Uploader',
            'songName'      => 'Darude - Sandstorm',
            'songSubName'   => 'hardcore remix',
            'cover'         => 1,
            'coverMime'     => 'png',
            'description'   => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.',
            'difficulties'  => ['Normal', 'Hard'],
            'downloadCount' => 233,
            'playedCount'   => 79,
            'upvotes'       => 24,
            'downvotes'     => 3,
            'downloadId'    => '1-16',
            'version'       => 2
        ];
    }
}