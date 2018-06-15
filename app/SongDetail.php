<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SongDetail extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $touches = ['song'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function song()
    {
        return $this->belongsTo(\App\Song::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function votes()
    {
        return $this->hasMany(\App\Vote::class, 'detail_id');
    }
}
