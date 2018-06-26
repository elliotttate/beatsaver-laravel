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
            'details' => function ($query) use ($detailId) {
                if ($detailId) {
                    $query->where('id', $detailId);
                } else {
                    $query->orderByDesc('id');
                }
            },
            'details.votes' => function($query) {
                $query;//->select(DB::raw('direction,count(*) as vote_count'))->groupBy('direction')->orderBy('direction');
            }
        ])->findOrFail($id);

        $details = $song->details->first();
        $detailVotes = $details->votes()->select(DB::raw('direction,count(*) as vote_count'))->groupBy('direction')->orderBy('direction')->get()->keyBy('direction')->toArray();
        $difficulties = array_keys(json_decode($details->difficulty_levels, true));

        return [
            'id'             => $song->id,
            'name'           => $song->name,
            'uploader'       => $song->uploader->name,
            'songName'       => $details->song_name,
            'songSubName'    => $details->song_sub_name,
            'cover'          => $song->id . '-' . $details->id,
            'coverMime'      => $details->cover,
            'description'    => $song->description,
            'difficulties'   => $difficulties,
            'downloadCount'  => $details->download_count,
            'playedCount'    => $details->play_count,
            'upvotes'        => isset($detailVotes[1]) ? $detailVotes[1]['vote_count'] : 0,
            'upvotesTotal'   => 0, //@todo get votes for song id instead of detailId
            'downvotes'      => isset($detailVotes[0]) ? $detailVotes[0]['vote_count'] : 0,
            'downvotesTotal' => 0, //@todo get votes for song id instead of detailId
            'downloadKey'    => $song->id . '-' . $details->id,
            'version'        => $song->details->count(), //@todo fix version if $detailId is specified
        ];
    }

    /**
     * @param array $metadata
     * @param array $songData
     *
     * @return Song|null
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

        //@todo fire new song/version event

        return $song;
    }
}