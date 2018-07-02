<?php

namespace App\Http\Controllers;

use App\Events\SongUploaded;
use App\Exceptions\UploadParserException;
use App\Http\Requests\UploadRequest;
use App\Http\Requests\VoteRequest;
use App\Models\SongDetail;
use App\Models\User;
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
        $songs = $composer->getTopDownloadedSongs($start);

        \Log::debug(count(\DB::getQueryLog()));
        return view('browse.downloads')->with(['songs' => $songs]);
    }

    public function topPlayed($start = 0, SongListComposer $composer)
    {
        return view('browse.played')->with(['songs' => $composer->getTopPlayedSongs($start)]);
    }

    public function newest($start = 0, SongListComposer $composer)
    {
        return view('browse.newest')->with(['songs' => $composer->getNewestSongs($start)]);
    }

    public function byUser($id, $start = 0, SongListComposer $composer)
    {
        $user = User::find($id);
        if ($user) {
            $name = $user->name;
        } else {
            $name = '';
        }
        return view('browse.user')->with(['songs' => $composer->getSongsByUser($id, $start), 'username' => $name]);
    }

    public function detail($key, SongComposer $composer)
    {
        return view('browse.detail')->with(['song' => $composer->get($key)]);
    }

    public function vote($key, VoteRequest $request, SongComposer $composer)
    {
        if ($composer->vote($key, auth()->user(), ($request->input('type') == 'up' ? SongComposer::VOTE_UP : SongComposer::VOTE_DOWN))) {
            return redirect()->back()->with('status-success', 'Vote successful!');
        }

        return redirect()->back()->with('status-error', 'Invalid vote parameter!');

    }

    public function download($key, SongComposer $composer)
    {
        return $composer->serveFileDownload($key);
    }

    public function upload()
    {
        return view('upload');
    }

    public function uploadSubmit(UploadRequest $request, SongComposer $composer)
    {
        if (!auth()->user()->isVerified()) {
            return redirect()->back()->withErrors('Your email needs to be verified in order to upload songs.');
        }
        $process = $request->file('fileupload')->store('process');

        $metadata = $request->only(['name', 'description']);
        $metadata['tempFile'] = $process;
        $metadata['userId'] = auth()->id();

        try {
            $parser = new UploadParser($process);
            if (!$songData = $parser->getSongData()) {
                return redirect()->back()->withErrors('Invalid song format.');
            }

            if ($song = $composer->create($metadata, $songData)) {
                event(new SongUploaded($song));
            }
        } catch (UploadParserException $e) {
            //@todo real error message!
            return redirect()->back()->withErrors('Invalid song format.');
        }

        return redirect()->route('browse.user', ['id' => auth()->id()]);
    }

    public function search()
    {
        return view('search');
    }

    public function searchResult($type,$key, SongListComposer $composer)
    {
        $parameter = [strtolower($type) => $key];
        return view('browse.search')->with(['songs' => $composer->search($parameter)]);
    }
}
