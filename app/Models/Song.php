<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Song extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['id', 'name', 'description', 'user_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details()
    {
        return $this->hasMany(SongDetail::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function votes()
    {
        return $this->hasManyThrough(Vote::class, SongDetail::class, null, 'detail_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($song) {
            if ($song->isForceDeleting()) {
                $song->details()->withTrashed()->get()->each->forceDelete();
            } else {
                $song->details()->withTrashed()->get()->each->delete();
            }
        });
    }

    /**
     * Destroys a Song including all related entities
     *
     * @param Song $song
     * @return bool
     */
    public static function destroyWithRelated(Song $song)
    {
        if ($song->votes()->exists()) {
            $song->votes()->forceDelete();
        }

        if ($song->details()->exists()) {
            $song->details()->forceDelete();
        }

        if (!Storage::disk()->deleteDirectory("public/songs/$song->id")) {
            return false;
        }

        if (!$song->forceDelete()) {
            return false;
        }

        return true;
    }
}
