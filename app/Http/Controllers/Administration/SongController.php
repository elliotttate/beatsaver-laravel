<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\UpdateSongRequest;
use App\Models\Song;
use Carbon\Carbon;

class SongController extends Controller
{
    /**
     * SongController constructor.
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $songs = Song::withTrashed()
            ->with(['uploader' => function ($query) {
                $query->withTrashed();
            }])
            ->with(['details' => function ($query) {
                $query->withTrashed();
            }])
            ->with('details.votes')
            ->get();

        return view('admin.song.index', ['songs' => $songs]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Song  $song
     * @return \Illuminate\Http\Response
     */
    public function show(Song $song)
    {
        $song->details->first()->difficulty_levels = json_decode($song->details->first()->difficulty_levels);

        return view('admin.song.show', ['song' => $song]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Song  $song
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSongRequest $request, Song $song)
    {
        $song->name = $request->name;
        $song->description = $request->description ? $request->description : '';
        $song->deleted_at = $request->has('hidden') ? Carbon::now() : null;

        if ($song->save()) {
            return redirect()->back()->withSuccess("$song->name has been updated!");
        }

        return redirect()->back()->withDanger("Something went wrong while trying to update $song->name!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Song  $song
     * @return \Illuminate\Http\Response
     */
    public function destroy(Song $song)
    {
        if (Song::destroyWithRelated($song)) {
            return redirect()->route('admin.songs.index')->withInfo("Permanently deleted $song->name");
        }

        return redirect()->route('admin.songs.index')->withDanger("Something went wrong while deleting $song->name");
    }
}
