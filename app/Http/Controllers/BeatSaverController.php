<?php

namespace App\Http\Controllers;

use App\Events\SongUploaded;
use App\Exceptions\UploadParserException;
use App\Http\Requests\DeleteSongRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\UpdateSongRequest;
use App\Http\Requests\UploadRequest;
use App\Http\Requests\VoteRequest;
use App\Models\User;
use App\SongComposer;
use App\SongListComposer;
use App\UploadParser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BeatSaverController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function welcome()
    {
        return view('welcome');
    }

    /**
     * @param int              $start
     * @param SongListComposer $composer
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function topDownloads($start = 0, SongListComposer $composer)
    {
        return view('browse.songlist')->with([
            'title' => 'Top Downloads',
            'songs' => $composer->getTopDownloadedSongs($start),
            'start' => $start,
            'steps' => $composer::DEFAULT_LIMIT,
        ]);

    }

    /**
     * @param int              $start
     * @param SongListComposer $composer
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function topPlayed($start = 0, SongListComposer $composer)
    {
        return view('browse.songlist')->with([
            'title' => 'Top Played',
            'songs' => $composer->getTopPlayedSongs($start),
            'start' => $start,
            'steps' => $composer::DEFAULT_LIMIT
        ]);
    }

    /**
     * @param int              $start
     * @param SongListComposer $composer
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function newest($start = 0, SongListComposer $composer)
    {
        return view('browse.songlist')->with([
            'title' => 'Newest',
            'songs' => $composer->getNewestSongs($start),
            'start' => $start,
            'steps' => $composer::DEFAULT_LIMIT
        ]);
    }

    /**
     * @param                  $id
     * @param int              $start
     * @param SongListComposer $composer
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function byUser($id, $start = 0, SongListComposer $composer)
    {
        $user = User::find($id);
        if (!$user) {
            return view('browse.user')->with(['songs' => [], 'username' => 'Unknown User', 'userId' => 0, 'start' => 0, 'steps' => 1]);
        }

        $name = $user->name;
        $userId = $user->id;

        return view('browse.user')->with([
            'songs'    => $composer->getSongsByUser($id, $start),
            'username' => $name,
            'userId'   => $userId,
            'start'    => $start,
            'steps'    => $composer::DEFAULT_LIMIT
        ]);
    }

    /**
     * @param              $key
     * @param SongComposer $composer
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detail($key, SongComposer $composer)
    {
        return view('browse.detail')->with(['song' => $composer->get($key)]);
    }

    /**
     * @param              $key
     * @param VoteRequest  $request
     * @param SongComposer $composer
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function vote($key, VoteRequest $request, SongComposer $composer)
    {
        if ($composer->vote($key, auth()->user(), ($request->input('type') == 'up' ? SongComposer::VOTE_UP : SongComposer::VOTE_DOWN))) {
            return redirect()->back()->with('status-success', 'Vote successful!');
        }

        return redirect()->back()->with('status-error', 'Invalid vote parameter!');

    }

    /**
     * @param              $key
     * @param SongComposer $composer
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download($key, SongComposer $composer)
    {
        return $composer->serveFileDownload($key);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function upload()
    {
        return view('upload');
    }

    /**
     * @param UploadRequest $request
     * @param SongComposer  $composer
     *
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search()
    {
        return view('search');
    }

    /**
     * @param SearchRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function searchSubmit(SearchRequest $request)
    {
        $params = $request->only(['key']);
        $params['type'] = 'all';

        return redirect()->route('search', $params);
    }

    /**
     * @param string           $type
     * @param string           $key
     * @param SongListComposer $composer
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function searchResult($type = 'all', $key = null, SongListComposer $composer)
    {
        $songs = [];

        if (!is_null($key) && strlen($key) >= 3) {
            $parameter = [strtolower($type) => $key];
            $songs = $composer->search($parameter);
        }

        return view('browse.search')->with([
            'title' => 'Song Search',
            'songs' => $songs,
            'key'   => $key,
            'start' => 0,
            'steps' => $composer::DEFAULT_LIMIT + 1 // +1 for disabling paging since paging is not supported while searching
        ]);
    }


    public function songEdit($id, SongComposer $composer)
    {
        $song = $composer->get($id);

        if ($song && auth()->id() == $song['uploaderId']) {
            return view('edit.edit')->with(['song' => $song]);
        }

        return redirect()->route('browse.user', ['id' => auth()->id()]);
    }

    public function songEditSubmit($id, UpdateSongRequest $request, SongComposer $composer)
    {
        $composer->delete();

        dd(request()->all());
    }

    public function songDelete($id, SongComposer $composer)
    {
        $song = $composer->get($id);

        if ($song && auth()->id() == $song['uploaderId']) {
            return view('edit.delete')->with(['song' => $song]);
        }

        return redirect()->route('browse.user', ['id' => auth()->id()]);
    }

    public function songDeleteSubmit($id, DeleteSongRequest $request, SongComposer $composer)
    {
        dd(request()->all());

        $composer->delete($id);
    }
}
