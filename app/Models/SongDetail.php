<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SongDetail extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $touches = ['song'];

    protected $fillable = ['song_id', 'song_name', 'song_sub_name', 'author_name', 'cover', 'play_count', 'download_count', 'bpm', 'difficulty_levels', 'hash_md5', 'hash_sha1'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function song()
    {
        return $this->belongsTo(Song::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function votes()
    {
        return $this->hasMany(Vote::class, 'detail_id');
    }

    /**
     * Return up and down votes count.
     *
     * @return array
     */
    public function voteCount()
    {
        $votes = $this->load('votes')->select(DB::raw('direction,count(*) as vote_count'))->groupBy('direction')
            ->orderBy('direction')->get()->keyBy('direction')->toArray();

        return ['up' => $votes[1]['votes_count'], 'down' => $votes[0]['votes_count']];
    }

    /**
     * @param User $user
     */
    public function voteUp(User $user)
    {
        $vote = new Vote(['direction' => 1, 'user_id' => $user->id]);
        $this->votes()->save($vote);
    }

    /**
     * @param User $user
     */
    public function voteDown(User $user)
    {
        $vote = new Vote(['direction' => 0, 'user_id' => $user->id]);
        $this->votes()->save($vote);
    }
}
