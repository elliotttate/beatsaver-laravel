<?php

namespace App;

use App\Models\Song;
use App\Models\SongDetail;
use App\Models\User;
use DB;
use Storage;

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
        //@todo check cache before querying the database

        $song = Song::with([
            'uploader',
            'details'       => function ($query) use ($detailId) {
                if ($detailId) {
                    $query->where('id', $detailId);
                } else {
                    $query->orderByDesc('id');
                }
            },
            'details.votes' => function ($query) {
                $query;//->select(DB::raw('direction,count(*) as vote_count'))->groupBy('direction')->orderBy('direction');
            }
        ])->findOrFail($id);

        /**
         * @var $details SongDetail
         */
        $details = $song->details->first();
        $detailVotes = $details->voteCount();
        $difficulties = array_keys(json_decode($details->difficulty_levels, true));

        return [
            'id'             => $song->id,
            'name'           => $song->name,
            'uploader'       => $song->uploader->name,
            'songName'       => $details->song_name,
            'songSubName'    => $details->song_sub_name,
            'authorName'     => $details->author_name,
            'cover'          => $song->id . '-' . $details->id,
            'coverMime'      => $details->cover,
            'description'    => $song->description,
            'difficulties'   => $difficulties, // @todo we may need the complete stats here in the future
            'downloadCount'  => $details->download_count,
            'playedCount'    => $details->play_count,
            'upvotes'        => $detailVotes['up'],
            'upvotesTotal'   => 0, //@todo get votes for song id instead of detailId
            'downvotes'      => $detailVotes['down'],
            'downvotesTotal' => 0, //@todo get votes for song id instead of detailId
            'downloadKey'    => $song->id . '-' . $details->id,
            'version'        => $song->details->count(), //@todo fix version if $detailId is specified
        ];
    }

    /**
     * @param array $metadata
     * @param array $songData
     *
     * @return array
     */
    public function create(array $metadata, array $songData)
    {
        //check if song hash already exists
        if (SongDetail::where('hash_md5', $songData['hashMD5'])->where('hash_sha1', $songData['hashSHA1'])->first()) {
            return null;
        }

        $user = User::findOrFail($metadata['userId']);
        $song = new Song([
            'name'        => $metadata['name'],
            'description' => $metadata['description'],
        ]);
        $songDetails = new SongDetail([
            'song_name'         => $songData['songName'],
            'song_sub_name'     => $songData['songSubName'],
            'author_name'       => $songData['authorName'],
            'cover'             => $songData['coverType'],
            'bpm'               => $songData['beatsPerMinute'],
            'difficulty_levels' => json_encode($songData['difficultyLevels']),
            'hash_md5'          => $songData['hashMD5'],
            'hash_sha1'         => $songData['hashSHA1'],
        ]);

        $user->songs()->save($song);
        $song->details()->save($songDetails);
        if (!Storage::disk()->exists('public/songs')) {
            Storage::disk()->makeDirectory('public/songs');
        }
        Storage::disk()->move($metadata['tempFile'], "public/songs/{$song->id}-{$songDetails->id}.zip");
        Storage::disk()->put("public/songs/{$song->id}-{$songDetails->id}.{$songData['coverType']}", base64_decode($songData['coverData']));


        return [
            'id'             => $song->id,
            'name'           => $song->name,
            'uploader'       => $song->uploader->name,
            'songName'       => $songDetails->song_name,
            'songSubName'    => $songDetails->song_sub_name,
            'authorName'     => $songDetails->author_name,
            'cover'          => $song->id . '-' . $songDetails->id,
            'coverMime'      => $songDetails->cover,
            'description'    => $song->description,
            'difficulties'   => array_keys($songData['difficultyLevels']), // @todo we may need the complete stats here in the future
            'downloadCount'  => $songDetails->download_count,
            'playedCount'    => $songDetails->play_count,
            'upvotes'        => 0,
            'upvotesTotal'   => 0,
            'downvotes'      => 0,
            'downvotesTotal' => 0,
            'downloadKey'    => $song->id . '-' . $songDetails->id,
            'version'        => $song->details->count(), //@todo fix version if $detailId is specified
        ];

    }
}