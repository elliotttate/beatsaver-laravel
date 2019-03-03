<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
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
     * @param int                 $start
     * @param SongListComposerApi $composer
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function topRated($start = 0, SongListComposerApi $composer)
    {
        $songs = $composer->getTopRatedSongs((int)$start, $composer::DEFAULT_LIMIT);
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
     * @param string          $key
     * @param int             $type
     * @param SongComposerApi $composer
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function voteById(Request $request, string $key, int $type, SongComposerApi $composer)
    {
        $apiKey = env('STEAM_WEB_API_KEY', null);
        if ($apiKey == null) {
            return Response::json(['message' => 'steam voting not set up'], 501);
        }

        $id = $request->id;
        $ticket = $request->ticket;

        if (!$id || !$ticket) {
            return Response::json(['message' => 'bad request'], 400);
        }

        try {
            $client = new Client(['base_uri' => 'https://api.steampowered.com']);
            $res = $client->get("/ISteamUserAuth/AuthenticateUserTicket/v1?key={$apiKey}&appid=620980&ticket={$ticket}");
    
            if ($res->getStatusCode() != 200) {
                return Response::json(['message' => 'steam api error'], 500);
            }
    
            $body = json_decode($res->getBody())->response;
            if (array_key_exists('error', $body)) {
                return Response::json(['message' => 'invalid auth ticket'], 401);
            } else if (!array_key_exists('params', $body)) {
                return Response::json(['message' => 'steam api error'], 500);
            }

            $params = $body->params;
            if ($params->result != 'OK') {
                return Response::json(['message' => 'steam api error'], 500);
            } else if ($id != $params->steamid) {
                return Response::json(['message' => 'steam id mismatch'], 403);
            }

            $song = $composer->get($key, true);
            if ($composer->voteRaw($key, $id, $type)) {
                $song = $composer->get($key, true);
    
                return Response::json(Arr::only($song, ['upVotes', 'upVotesTotal', 'downVotes', 'downVotesTotal']));
            }
        } catch (Exception $e) {
            return Response::json(['message' => 'steam api error'], 500);
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
    public function search(string $type, $key, SongListComposerApi $composer)
    {
        if (strlen($key) < 3) {
            return Response::json(['songs' => [], 'total' => 0]);
        }

        $parameter = [strtolower($type) => $key];
        $songs = $composer->search($parameter, 0, 40);
        $total = count($songs);
        return Response::json(['songs' => $songs, 'total' => $total]);
    }
}
