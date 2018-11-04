<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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


}
