<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BeatSaverController extends Controller
{
    public function welcome(Request $request)
    {
        return view('welcome');
    }

    public function topDownloads(Request $request, $start = 1)
    {
        return view('browse.downloads');
    }

    public function topPlayed(Request $request, $start = 1)
    {
        return view('browse.played');
    }

    public function newest(Request $request, $start = 1)
    {
        return view('browse.newest');
    }

    public function search()
    {
        return view('search');
    }

    public function dmca()
    {
        return view('dmca');
    }

}
