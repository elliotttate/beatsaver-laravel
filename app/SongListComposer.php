<?php

namespace App;

use App\Models\Song;
use DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

class SongListComposer
{
    const DEFAULT_LIMIT = 15;

    /**
     * Get song count of all songs.
     *
     * @return int
     */
    public function getSongCount(): int
    {
        return Song::count();
    }

    /**
     * Get song count for user.
     *
     * @param int $userId
     *
     * @return int
     */
    public function getUserSongCount(int $userId): int
    {
        return Song::where('user_id', $userId)->whereNull('deleted_at')->count();
    }

    /**
     * @param array $parameter
     * @param int   $offset
     * @param int   $limit
     * @param bool  $apiFormat
     *
     * @return Collection
     */
    public function search(array $parameter, int $offset = 0, int $limit = SongListComposer::DEFAULT_LIMIT, $apiFormat = false): Collection
    {
        if (!$parameter) {
            return collect();
        }

        $doSearch = false;
        $orderBy = 'created_at';

        $songs = $this->prepareQuery($orderBy, $offset, $limit)
            ->leftJoin('users as u', 's.user_id', '=', 'u.id');

        foreach ($parameter as $key => $search) {
            $searchableKeys = $this->searchableKeys();
            if (array_key_exists($key, $searchableKeys)) {
                $doSearch = true;

                $fields = $searchableKeys[$key]['fields'];

                switch ($searchableKeys[$key]['type']) {
                    case "fulltext" :
                        $this->addFullTextWhere($songs, $fields, $search);
                        break;
                    case "like":
                        $this->addLikeWhere($songs, $fields, $search);
                        break;
                    case "equal":
                        $this->addEqualWhere($songs, $fields, $search);
                        break;
                    case "mixed":

                        $songs->where(function ($query) use ($searchableKeys, $fields, $search) {
                            foreach ($fields as $group) {
                                switch ($searchableKeys[$group]['type']) {
                                    case "fulltext" :
                                        $this->addFullTextWhere($query, $searchableKeys[$group]['fields'], $search, 'or');
                                        break;
                                    case "like":
                                        $this->addLikeWhere($query, $searchableKeys[$group]['fields'], $search, 'or');
                                        break;
                                    case "equal":
                                        $this->addEqualWhere($query, $searchableKeys[$group]['fields'], $search, 'or');
                                        break;
                                    default:

                                }
                            }
                        });
                        break;
                    default:
                        $doSearch = false;

                }
            }
        }

        // only execute search if we have at least one valid input
        if (!$doSearch) {
            return collect();
        }

        return $this->prepareSongInfo($songs->get(), $apiFormat);
    }

    /**
     * searchable key to column mapper
     *
     * @return array
     */
    protected function searchableKeys()
    {
        return [
            'author' => [
                'type'   => 'like',
                'fields' => ['sd.author_name'],
            ],
            'name'   => [
                'type'   => 'fulltext',
                'fields' => ['s.name'],
            ],
            'user'   => [
                'type'   => 'like',
                'fields' => ['u.name'],
            ],
            'hash'   => [
                'type'   => 'equal',
                'fields' => ['sd.hash_md5'],
            ],
            'song'   => [
                'type'   => 'fulltext',
                'fields' => ['sd.song_name', 'sd.song_sub_name', 'sd.author_name'],
            ],
            'all'    => [
                'type'   => 'mixed',
                'fields' => ['name', 'user', 'song'],
            ],
        ];
    }

    /**
     * @param Builder $builder
     * @param array   $matches
     * @param string  $search
     * @param string  $type
     */
    protected function addFullTextWhere(Builder $builder, array $matches, string $search, $type = 'and')
    {
        \Log::debug('FullTextWhere: ' . $search);
        $builder->whereRaw('(MATCH(' . implode(',', $matches) . ') AGAINST(? IN BOOLEAN MODE) > 0)', ["*" . $search . "*"], $type);
    }

    /**
     * @param Builder $builder
     * @param array   $fields
     * @param string  $search
     * @param string  $type
     */
    protected function addLikeWhere(Builder $builder, array $fields, string $search, $type = 'and')
    {
        \Log::debug('LikeWhere:');
        $builder->where(function ($query) use ($fields, $search) {
            foreach ($fields as $field) {
                \Log::debug(' : ' . $search);
                $query->orWhere($field, 'LIKE', "%$search%");
            }
        }, null, null, $type);

    }

    /**
     * @param Builder $builder
     * @param array   $fields
     * @param string  $search
     * @param string  $type
     */
    protected function addEqualWhere(Builder $builder, array $fields, string $search, $type = 'and')
    {
        \Log::debug('FullTextWhere:');
        $builder->where(function ($query) use ($fields, $search) {
            foreach ($fields as $field) {
                \Log::debug(' : ' . $search);
                $query->orWhere($field, $search);
            }
        }, null, null, $type);

    }

    /**
     * Get songs ordered by play count descending.
     * If a song has multiple versions only get the latest one.
     *
     * @param int  $offset
     * @param int  $limit
     * @param bool $apiFormat
     *
     * @return Collection
     */
    public function getTopPlayedSongs(int $offset = 0, int $limit = SongListComposer::DEFAULT_LIMIT, $apiFormat = false): Collection
    {
        $orderBy = 'play_count';
        $songs = $this->prepareQuery($orderBy, $offset, $limit);

        return $this->prepareSongInfo($songs->get(), $apiFormat);
    }

    /**
     * Get songs ordered by download count descending.
     * If a song has multiple versions only get the latest one.
     *
     * @param int  $offset
     * @param int  $limit
     * @param bool $apiFormat
     *
     * @return Collection
     */
    public function getTopDownloadedSongs(int $offset = 0, int $limit = SongListComposer::DEFAULT_LIMIT, $apiFormat = false): Collection
    {
        $orderBy = 'download_count';
        $songs = $this->prepareQuery($orderBy, $offset, $limit);

        return $this->prepareSongInfo($songs->get(), $apiFormat);
    }

    /**
     * Get songs ordered by creation date descending.
     * If a song has multiple versions only get the latest one.
     *
     * @param int  $offset
     * @param int  $limit
     * @param bool $apiFormat
     *
     * @return Collection
     */
    public function getNewestSongs(int $offset = 0, int $limit = SongListComposer::DEFAULT_LIMIT, $apiFormat = false): Collection
    {
        $orderBy = 'created_at';
        $songs = $this->prepareQuery($orderBy, $offset, $limit);

        return $this->prepareSongInfo($songs->get(), $apiFormat);
    }

    /**
     * Get songs uploaded by user {$id] ordered by creation date.
     * If a song has multiple versions only get the latest one.
     *
     * @param int  $userId
     * @param int  $offset
     * @param int  $limit
     * @param bool $apiFormat
     *
     * @return Collection
     */
    public function getSongsByUser(int $userId, int $offset = 0, int $limit = SongListComposer::DEFAULT_LIMIT, $apiFormat = false): Collection
    {
        $orderBy = 'created_at';
        $songs = $this->prepareQuery($orderBy, $offset, $limit)->where('s.user_id', $userId);

        return $this->prepareSongInfo($songs->get(), $apiFormat);
    }

    /**
     * Converts a list of song keys into song info data.
     *
     * @param Collection $songs
     * @param bool       $apiFormat
     *
     * @return Collection
     */
    protected function prepareSongInfo(Collection $songs, $apiFormat = false): Collection
    {
        $composer = new SongComposer();

        $songs->transform(function ($item, $key) use ($composer, $apiFormat) {
            return $composer->get($item->songKey, $apiFormat);
        });

        return $songs;
    }

    /**
     * Prepare a base query every song list uses.
     * Song not delete, latest version
     *
     * WARNING: never pass unchecked, user defined data into {$orderBy} since it opens
     * the query for injection attacks!
     *
     * @param string $orderBy
     * @param int    $offset
     * @param int    $limit
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function prepareQuery(string $orderBy, int $offset, int $limit): Builder
    {
        return DB::table('song_details as sd')->select(DB::raw("concat(sd.song_id,'-',max(sd.id))as songKey"))
            ->leftJoin('songs as s', 'sd.song_id', '=', 's.id')
            ->leftJoin('users as u', 's.user_id', '=', 'u.id')
            ->whereNull('s.deleted_at')->whereNull('sd.deleted_at')->whereNull('u.deleted_at')
            ->groupBy(['sd.song_id'])->orderByRaw("(select {$orderBy} from song_details where id = max(sd.id) and deleted_at is null) desc")
            ->offset($offset)->limit($limit);

    }

}