<?php

namespace App\Http\Controllers;

use App\Models\AccessToken;
use App\SongComposerApi;
use App\SongListComposerApi;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Response;

class ApiController extends Controller
{

    /**
     * @param int                 $start
     * @param SongListComposerApi $composer
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function topDownloads($start = 0, SongListComposerApi $composer)
    {
        $songs = $composer->getTopDownloadedSongs((int)$start, $composer::DEFAULT_LIMIT);
        $total = $composer->getSongCount();

        return Response::json(['songs' => $songs, 'total' => $total]);
    }

    /**
     * @param int                 $start
     * @param SongListComposerApi $composer
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function topPlayed($start = 0, SongListComposerApi $composer)
    {
        $songs = $composer->getTopPlayedSongs((int)$start, $composer::DEFAULT_LIMIT);
        $total = $composer->getSongCount();

        return Response::json(['songs' => $songs, 'total' => $total]);
    }

    /**
     * @param int                 $start
     * @param SongListComposerApi $composer
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function newest($start = 0, SongListComposerApi $composer)
    {
        $songs = $composer->getNewestSongs((int)$start, $composer::DEFAULT_LIMIT);
        $total = $composer->getSongCount();

        return Response::json(['songs' => $songs, 'total' => $total]);
    }

    /**
     * @param int                 $id
     * @param int                 $start
     * @param SongListComposerApi $composer
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function byUser(int $id, $start = 0, SongListComposerApi $composer)
    {
        $songs = $composer->getSongsByUser($id, (int)$start, $composer::DEFAULT_LIMIT);
        $total = $composer->getUserSongCount($id);

        return Response::json(['songs' => $songs, 'total' => $total]);
    }

    /**
     * @param string          $key
     * @param SongComposerApi $composer
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail(string $key, SongComposerApi $composer)
    {
        $song = $composer->get($key);
        if ($song) {
            return Response::json(['song' => $song]);
        }
        return Response::json(['message' => "{$key} not found"], 404);
    }

    /**
     * @param string          $key
     * @param int             $type
     * @param string          $accessToken
     * @param SongComposerApi $composer
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function vote(string $key, int $type, string $accessToken, SongComposerApi $composer)
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
     * @param string              $type
     * @param string              $key
     * @param SongListComposerApi $composer
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(string $type, string $key, SongListComposerApi $composer)
    {
        if (strlen($key) < 3) {
            return Response::json(['songs' => [], 'total' => 0]);
        }

        $parameter = [strtolower($type) => $key];
        $songs = $composer->search($parameter, 0, 30);
        $total = count($songs);
        return Response::json(['songs' => $songs, 'total' => $total]);
    }
}
