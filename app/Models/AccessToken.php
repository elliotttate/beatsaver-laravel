<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessToken extends Model
{
    const TYPE_READ_ONLY = 0;
    const TYPE_READ_WRITE = 1;

    protected $fillable = ['token', 'type'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeCanWrite($query)
    {
        return $query->where('type', static::TYPE_READ_WRITE);
    }

    public function isReadOnly()
    {
        return $this->type == static::TYPE_READ_ONLY;
    }
}
