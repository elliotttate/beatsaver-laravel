<?php

namespace App\Http\Controllers;

use App\Models\AccessToken;
use App\Models\User;
use App\SongComposer;
use App\SongListComposer;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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
        $songs = $composer->getTopDownloadedSongs($start, SongListComposer::DEFAULT_LIMIT, true);
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
        $songs = $composer->getTopPlayedSongs($start, SongListComposer::DEFAULT_LIMIT, true);
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
        $songs = $composer->getNewestSongs($start, SongListComposer::DEFAULT_LIMIT, true);
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

        $songs = $composer->getSongsByUser($id, $start, SongListComposer::DEFAULT_LIMIT, true);
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
        $song = $composer->get($key, true);
        if ($song) {
            return Response::json(['song' => $song]);
        }
        return Response::json(['message' => "{$key} not found"], 404);
    }

    /**
     * @param string       $key
     * @param int          $type
     * @param string       $accessToken
     * @param SongComposer $composer
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function vote(string $key, int $type, string $accessToken, SongComposer $composer)
    {
        $user = AccessToken::where('token', $accessToken)->first();

        if (!$user) {
            return Response::json(['message' => 'invalid API token'], 401);
        }

        if ($user->isReadOnly()) {
            return Response::json(['message' => 'this token has no permission to vote'], 403);
        }

        if ($composer->vote($key, $user->user, $type)) {
            $song = $composer->get($key, true);

            return Response::json(Arr::only($song, ['upVotes', 'upVotesTotal', 'downVotes', 'downVotesTotal']));
        }

        return Response::json(['message' => 'invalid song key'], 400);
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
        $songs = $composer->search($parameter, 0, SongListComposer::DEFAULT_LIMIT, true);
        $total = count($songs);
        return Response::json(['songs' => $songs, 'total' => $total]);
    }
}
