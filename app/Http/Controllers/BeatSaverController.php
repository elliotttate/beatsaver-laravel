<?php

namespace App\Http\Controllers;

use App\SongComposer;
use App\SongListComposer;
use Illuminate\Http\Request;

class BeatSaverController extends Controller
{
    public function welcome(Request $request)
    {
        return view('welcome');
    }

    public function topDownloads(Request $request, $start = 1, SongListComposer $composer)
    {
        return view('browse.downloads')->with('songs', $composer->getTopDownloadedSongs($start));
    }

    public function topPlayed(Request $request, $start = 1, SongListComposer $composer)
    {
        return view('browse.played')->with('songs', $composer->getTopDownloadedSongs($start));
    }

    public function newest(Request $request, $start = 1, SongListComposer $composer)
    {
        return view('browse.newest')->with('songs', $composer->getNewestSongs($start));
    }

    public function detail($id, SongComposer $composer)
    {
        $split = explode('-', $id, 2);

        $songId = $split[0];
        $detailId = $split[1];

        return view('browse.detail')->with('song', $composer->get($songId, $detailId));
    }

    public function voteUp($id)
    {
        return redirect()->back();
    }

    public function voteDown($id)
    {
        return redirect()->back();
    }

    public function search()
    {
        return view('search');
    }
}
