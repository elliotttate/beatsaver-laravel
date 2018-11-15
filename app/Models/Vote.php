<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    public $timestamps = false;

    protected $fillable = ['direction', 'user_id', 'detail_id', 'song_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function voter()
    {
        return $this->belongsTo(User::class);
    }
}
