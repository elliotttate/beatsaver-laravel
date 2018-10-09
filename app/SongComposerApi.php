<?php

namespace App;

class SongComposerApi extends SongComposer
{
    /**
     * @param string $key
     * @param bool   $noCache
     *
     * @return array
     */
    public function get(string $key, $noCache = false): array
    {
        if ($song = parent::get($key, $noCache)) {
            return $this->convertSongToApiFormat($song);
        }
        return $song;
    }

    /**
     * @param array $song
     *
     * @return array
     */
    protected function convertSongToApiFormat(array $song): array
    {
        return [
            'id'             => $song['id'],
            'key'            => $song['key'],
            'name'           => $song['name'],
            'description'    => $song['description'],
            'uploader'       => $song['uploader'],
            'uploaderId'     => $song['uploaderId'],
            'genre'          => array_key_exists ('genre', $song) ? $song['genre'] : 'No genre',
            'genreId'        => array_key_exists ('genreId', $song) ? $song['genreId'] : -1,
            'songName'       => $song['version'][$song['key']]['songName'],
            'songSubName'    => $song['version'][$song['key']]['songSubName'],
            'authorName'     => $song['version'][$song['key']]['authorName'],
            'bpm'            => $song['version'][$song['key']]['bpm'],
            'difficulties'   => $song['version'][$song['key']]['difficulties'],
            'downloadCount'  => $song['version'][$song['key']]['downloadCount'],
            'playedCount'    => $song['version'][$song['key']]['playedCount'],
            'upVotes'        => $song['version'][$song['key']]['upVotes'],
            'upVotesTotal'   => 0,
            'downVotes'      => $song['version'][$song['key']]['downVotes'],
            'downVotesTotal' => 0,
            'version'        => $song['key'],
            'createdAt'      => $song['version'][$song['key']]['createdAt'],
            'linkUrl'        => $song['version'][$song['key']]['linkUrl'],
            'downloadUrl'    => $song['version'][$song['key']]['downloadUrl'],
            'coverUrl'       => $song['version'][$song['key']]['coverUrl'],
            'hashMd5'        => $song['version'][$song['key']]['hashMd5'],
            'hashSha1'       => $song['version'][$song['key']]['hashSha1'],
        ];
    }

}