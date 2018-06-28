<?php

namespace App\Http\Controllers;

use App\Exceptions\UploadParserException;
use App\Http\Requests\UploadRequest;
use App\Http\Requests\VoteRequest;
use App\Models\SongDetail;
use App\Models\Vote;
use App\SongComposer;
use App\SongListComposer;
use App\UploadParser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BeatSaverController extends Controller
{
    public function welcome()
    {
        return view('welcome');
    }

    public function topDownloads($start = 0, SongListComposer $composer)
    {
        return view('browse.downloads')->with(['songs' => $composer->getTopDownloadedSongs($start)]);
    }

    public function topPlayed($start = 0, SongListComposer $composer)
    {
        return view('browse.played')->with(['songs' => $composer->getTopDownloadedSongs($start)]);
    }

    public function newest($start = 0, SongListComposer $composer)
    {
        return view('browse.newest')->with(['songs' => $composer->getNewestSongs($start)]);
    }

    public function detail($key, SongComposer $composer)
    {
        $alreadyVoted = false;
        $split = explode('-', $key, 2);

        $songId = $split[0];
        $detailId = $split[1];

        return view('browse.detail')->with(['song' => $composer->get($songId, $detailId), 'alreadyVoted' => $alreadyVoted]);
    }

    public function vote($key, VoteRequest $request)
    {
        $split = explode('-', $key, 2);

        $songId = $split[0];
        $detailId = $split[1];


        Vote::updateOrCreate([
            'song_id'   => $songId,
            'detail_id' => $detailId,
            'user_id'   => auth()->id(),
        ], ['direction' => $request->input('type') == 'up' ? 1 : 0]);

        return redirect()->back();
    }

    public function download($key)
    {
        $split = explode('-', $key, 2);

        $songId = $split[0];
        $detailId = $split[1];

        // @todo stop/prevent download count faking
        if(SongDetail::where('id', $detailId)->where('song_id', $songId)->increment('download_count',1)) {
            return \response()->download(storage_path("app/public/songs")."/$key.zip");
        }

        return abort(404);

    }

    public function upload()
    {
        return view('upload');
    }

    public function uploadSubmit(UploadRequest $request, SongComposer $composer)
    {
        if(!auth()->user()->isVerified()) {
            return redirect()->back()->withErrors('Your email needs to be verified in order to upload songs.');
        }
        $process = $request->file('fileupload')->store('process');

        $metadata = $request->only(['name', 'description']);
        $metadata['tempFile'] = $process;
        $metadata['userId'] = auth()->id();

        try {
            $parser = new UploadParser($process);
            if(!$songData = $parser->getSongData()){
                return redirect()->back()->withErrors('Invalid song format.');
            }
            $composer->create($metadata, $songData);

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
