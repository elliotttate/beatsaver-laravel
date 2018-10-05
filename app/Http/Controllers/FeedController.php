<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\SongListComposer;

class FeedController extends Controller
{

    const FEED_LIMIT = 30;

    /**
     * @param SongListComposer $composer
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function newest(SongListComposer $composer)
    {
        return response()->view('master.atom-feed', [
            'title' => 'Newest',
            'songs' => $composer->getNewestSongs(0, FeedController::FEED_LIMIT),
        ], 200)->header('Content-Type', 'application/atom+xml; charset=utf-8');
    }

    /**
     * @param int              $id
     * @param SongListComposer $composer
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function byUser($id, SongListComposer $composer)
    {
        $user = User::find($id);
        if (!$user) {
            abort(404);
        }

        return response()->view('master.atom-feed', [
            'title' => 'Songs by ' . $user->name,
            'songs' => $composer->getSongsByUser($id, 0, FeedController::FEED_LIMIT),
        ], 200)->header('Content-Type', 'application/atom+xml; charset=utf-8');
    }
}
