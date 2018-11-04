<?php

namespace App\Models;

use DB;
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
     * @param User $user
     *
     * @return Model
     */
    public function voteUp(User $user)
    {
        return $this->votes()->updateOrCreate([
            'song_id'   => $this->song_id,
            'detail_id' => $this->id,
            'user_id'   => $user->id,
        ], ['direction' => 1]);
    }

    /**
     * @param User $user
     *
     * @return Model
     */
    public function voteDown(User $user)
    {
        return $this->votes()->updateOrCreate([
            'song_id'   => $this->song_id,
            'detail_id' => $this->id,
            'user_id'   => $user->id,
        ], ['direction' => 0]);
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($details) {
            if ($details->isForceDeleting()) {
                $details->votes()->get()->each->forceDelete();
            } else {
                $details->votes()->get()->each->delete();
            }
        });
    }
}
