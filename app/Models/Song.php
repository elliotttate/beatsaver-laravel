<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class Song extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['id', 'name', 'description', 'genre', 'user_id'];

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

    /**
     * Builds the datatable for Song.
     *
     * @return \Yajra\DataTables\EloquentDatatable
     */
    public static function dataTable()
    {
        $songs = Song::withTrashed()->with('details.votes');

        return DataTables::eloquent($songs)
            ->addColumn('author_name', function (Song $song) {
                return $song->uploader->name;
            })
            ->addColumn('play_count', function (Song $song) {
                return $song->details->first()->play_count;
            })
            ->addColumn('download_count', function (Song $song) {
                return $song->details->first()->download_count;
            })
            ->addColumn('upvotes', function (Song $song) {
                return $song->details->first()->votes->where('direction', true)->count();
            })
            ->addColumn('downvotes', function (Song $song) {
                return $song->details->first()->votes->where('direction', false)->count();
            })
            ->addColumn('states', function (Song $song) {
                $states = '';

                !$song->deleted_at ?: $states .= '.Hidden';
                !$song->created_at->diffInDays(Carbon::now()) < 30 ?: $states .= '.New';

                return $states;
            });
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
