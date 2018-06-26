<?php

namespace App\Http\Controllers;

use App\Exceptions\UploadParserException;
use App\Http\Requests\UploadRequest;
use App\SongComposer;
use App\SongListComposer;
use App\UploadParser;
use Illuminate\Http\Request;

class BeatSaverController extends Controller
{
    public function welcome()
    {
        return view('welcome');
    }

    public function topDownloads($start = 1, SongListComposer $composer)
    {
        return view('browse.downloads')->with('songs', $composer->getTopDownloadedSongs($start));
    }

    public function topPlayed($start = 1, SongListComposer $composer)
    {
        return view('browse.played')->with('songs', $composer->getTopDownloadedSongs($start));
    }

    public function newest($start = 1, SongListComposer $composer)
    {
        return view('browse.newest')->with('songs', $composer->getNewestSongs($start));
    }

    public function detail($key, SongComposer $composer)
    {
        $split = explode('-', $key, 2);

        $songId = $split[0];
        $detailId = $split[1];

        return view('browse.detail')->with('song', $composer->get($songId, $detailId));
    }

    public function voteUp($key)
    {

        return redirect()->back();
    }

    public function voteDown($key)
    {
        return redirect()->back();
    }

    public function download($key)
    {
        
    }

    public function upload()
    {
        return view('upload');
    }

    public function uploadSubmit(UploadRequest $request, SongComposer $composer)
    {
        $process = $request->file('fileupload')->store('process');

        $metadata = $request->only(['name','description']);
        $metadata['tempFile'] = $process;
        $metadata['userId'] = auth()->id();

        try {
            $parser = new UploadParser($process);
            $composer->create($metadata,$parser->getSongData());

        } catch (UploadParserException $e) {
            //@todo real error message!
            return redirect()->back()->withErrors('Invalid song format.');
        }

        return redirect()->route('browse.top.newest'); //@todo redirect to "my songs"
    }

    public function search()
    {
        return view('search');
    }
}
