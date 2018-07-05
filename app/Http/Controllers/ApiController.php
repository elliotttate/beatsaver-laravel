<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\SongComposer;
use App\SongListComposer;
use Illuminate\Http\Request;
use Response;

class ApiController extends Controller
{

    /**
     * @param int              $start
     * @param SongListComposer $composer
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function topDownloads(int $start = 0, SongListComposer $composer)
    {
        $songs = $composer->getTopDownloadedSongs($start);
        $total = $composer->getSongCount();

        return Response::json(['songs' => $songs, 'total' => $total]);
    }

    /**
     * @param int              $start
     * @param SongListComposer $composer
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function topPlayed(int $start = 0, SongListComposer $composer)
    {
        $songs = $composer->getTopPlayedSongs($start);
        $total = $composer->getSongCount();

        return Response::json(['songs' => $songs, 'total' => $total]);
    }

    /**
     * @param int              $start
     * @param SongListComposer $composer
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function newest(int $start = 0, SongListComposer $composer)
    {
        $songs = $composer->getNewestSongs($start);
        $total = $composer->getSongCount();

        return Response::json(['songs' => $songs, 'total' => $total]);
    }

    /**
     * @param int              $id
     * @param int              $start
     * @param SongListComposer $composer
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function byUser(int $id, int $start = 0, SongListComposer $composer)
    {
        $user = User::find($id);
        if ($user) {
            $name = $user->name;
        } else {
            $name = '';
        }

        $songs = $composer->getSongsByUser($id, $start);
        $total = $composer->getUserSongCount($id);

        return Response::json(['songs' => $songs, 'total' => $total]);
    }

    /**
     * @param string       $key
     * @param SongComposer $composer
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail(string $key, SongComposer $composer)
    {
        $song = $composer->get($key);
        return Response::json(['song' => $song]);
    }

    /**
     * @param string       $key
     * @param int          $type
     * @param string       $votekey
     * @param SongComposer $composer
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function vote(string $key, int $type, string $votekey, SongComposer $composer)
    {
        $user = User::where('votekey', $votekey)->first();

        if (!$user) {
            return Response::json([], 403);
        }

        if ($composer->vote($key, $user, $type)) {
            return Response::json([]);
        }

        return Response::json([], 400);
    }

    /**
     * @param string           $type
     * @param string           $key
     * @param SongListComposer $composer
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(string $type, string $key, SongListComposer $composer)
    {
        if (strlen($key) < 3) {
            return Response::json(['songs' => [], 'total' => 0]);
        }

        $parameter = [strtolower($type) => $key];
        $songs = $composer->search($parameter);
        $total = count($songs);
        return Response::json(['songs' => $songs, 'total' => $total]);
    }
}
