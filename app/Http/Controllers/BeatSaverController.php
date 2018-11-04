<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteSongRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\UpdateSongRequest;
use App\Http\Requests\UploadRequest;
use App\Http\Requests\VoteRequest;
use App\Models\Song;
use App\Models\User;
use App\SongComposer;
use App\SongListComposer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\UnauthorizedException;
use Log;

class BeatSaverController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function welcome()
    {
        return redirect()->route('browse.top.newest');
    }

    /**
     * @param int              $start
     * @param SongListComposer $composer
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function topDownloads($start = 0, SongListComposer $composer)
    {
        return view('master.page-songs-by-top-downloads')->with([
            'title' => 'Top Downloads',
            'songs' => $composer->getTopDownloadedSongs((int)$start),
            'start' => (int)$start,
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
        return view('master.page-songs-by-played')->with([
            'title' => 'Top Played',
            'songs' => $composer->getTopPlayedSongs((int)$start),
            'start' => (int)$start,
            'steps' => $composer::DEFAULT_LIMIT,
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
        return view('master.page-songs-by-created')->with([
            'title' => 'Newest',
            'songs' => $composer->getNewestSongs((int)$start),
            'start' => (int)$start,
            'steps' => $composer::DEFAULT_LIMIT,
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
            return view('master.page-songs-by-user')->with(['songs' => [], 'username' => 'Unknown User', 'userId' => 0, 'start' => 0, 'steps' => 1]);
        }

        $name = $user->name;
        $userId = $user->id;

        return view('master.page-songs-by-user')->with([
            'songs'    => $composer->getSongsByUser($id, (int)$start),
            'username' => $name,
            'userId'   => $userId,
            'start'    => (int)$start,
            'steps'    => $composer::DEFAULT_LIMIT,
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
        $song = $composer->get($key);
        if ($song) {
            return view('master.page-song-detail')->with(['song' => $song]);
        }
        abort(404);
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
     * @return \Illuminate\Http\RedirectResponse
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
        return view('master.page-song-upload');
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
        $process = $request->file('fileupload');

        $metadata = [
            'userId'      => auth()->id(),
            'songId'      => null,
            'name'        => $request->input('name'),
            'description' => $request->input('description'),
        ];

        $song = $composer->create($metadata, $process);

        if ($song['status'] != $composer::SONG_CREATED) {
            Log::debug($song['status']);
            return redirect()->back()->withErrors($composer->getErrorText($song['status']));
        }

        return redirect()->route('browse.user', ['id' => auth()->id()]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search()
    {
        return view('master.page-search');
    }

    /**
     * @param SearchRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function searchSubmit(SearchRequest $request)
    {
        $params = [];
        $params['key'] = $request->input('key');
        $params['type'] = 'all';

        return redirect()->route('search', $params);
    }

    /**
     * @param string           $type
     * @param SongListComposer $composer
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function searchResult($type = 'all', SongListComposer $composer)
    {
        $songs = [];
        $key = request()->input('key');

        if (!is_null($key) && strlen($key) >= 3) {
            $parameter = [strtolower($type) => $key];
            $songs = $composer->search($parameter, 0, 40);
        }

        return view('master.page-search')->with([
            'title' => 'Song Search',
            'songs' => $songs,
            'key'   => request()->input('key'),
            'start' => 0,
            'steps' => $composer::DEFAULT_LIMIT + 1 // +1 for disabling paging since paging is not supported while searching
        ]);
    }


    public function songEdit($id, SongComposer $composer)
    {
        $song = $composer->get($id);

        if ($song && auth()->id() == $song['uploaderId']) {
            return view('master.page-song-edit')->with(['song' => $song]);
        }

        return redirect()->route('browse.user', ['id' => auth()->id()]);
    }

    public function songEditSubmit($id, UpdateSongRequest $request, SongComposer $composer)
    {
        $song = Song::find($id);

        if (!$song) {
            return redirect()->back()->withErrors('Invalid Song.');
        }

        if ($song && auth()->id() != $song->user_id) {
            throw new UnauthorizedException('Access Denied!');
        }

        if ($request->hasFile('fileupload')) {
            if (!auth()->user()->isVerified()) {
                return redirect()->back()->withErrors('Your email needs to be verified in order to upload songs.');
            }
            $process = $request->file('fileupload');
        } else {
            $process = false;
        }

        $metadata = $request->only(['name', 'description']);
        $metadata['userId'] = auth()->id();
        $metadata['updateFile'] = $process;
        $metadata['songId'] = $song->id;

        $info = $composer->update($song, $metadata);

        if ($info['status'] == $composer::SONG_CREATED || $info['status'] == $composer::SONG_UPDATED) {
            return redirect()->route('browse.detail', ['key' => $info['song']['key']]);
        }

        return redirect()->back()->withErrors($composer->getErrorText($info['status']));
    }

    public function songDelete($id, SongComposer $composer)
    {
        $song = $composer->get($id);

        if ($song && auth()->id() == $song['uploaderId']) {
            return view('master.page-song-delete')->with(['song' => $song]);
        }

        return redirect()->route('browse.user', ['id' => auth()->id()]);
    }

    public function songDeleteSubmit($id, DeleteSongRequest $request, SongComposer $composer)
    {

        if ($request->input('confirm', 0)) {
            if ($composer->delete($id)) {
                return redirect()->route('browse.user', ['id' => auth()->id()])->with('status-success', 'Delete successful.');
            }
        }
        return redirect()->route('browse.detail', ['key' => $id]);


    }
}
