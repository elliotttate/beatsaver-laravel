<?php

namespace App\Console\Commands;

use App\Models\Song;
use App\Models\User;
use App\Models\Vote;
use App\SongComposer;
use Carbon\Carbon;
use DB;
use File;
use Illuminate\Console\Command;
use Illuminate\Database\MySqlConnection;

class BeatsaverImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'beatsaver:import-legacy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import user from legacy beatsaver db';

    protected $legacyConnection;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->legacyConnection = DB::connection('beatsaver-legacy');

    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (config('beatsaver.legacy.enabled') && $this->confirm('This import is for empty databases only! Do you want to continue?')) {
            $this->importUser();
            $this->importSongs();
            $this->importVotes();
        }
    }

    protected function importUser()
    {
        $this->info('Start User Import');
        $this->legacyConnection->table('users')->orderBy('id')->chunk(1000, function ($users) {
            foreach ($users as $user) {
                $newUser = new User();
                $newUser->id = $user->id;
                $newUser->name = $user->username;
                $newUser->password = $user->password;
                $newUser->email = $user->email;
                $newUser->created_at = Carbon::createFromTimestamp($user->registered);
                $newUser->save();
            }
        });
    }

    protected function importSongs()
    {
        $this->info('Start Song Import');
        $composer = new SongComposer();
        $this->legacyConnection->table('beats')->orderBy('id')->chunk(1000, function ($songs) use ($composer) {
            foreach ($songs as $song) {
                $file = realpath(config('beatsaver.legacy.songPath') . '/' . $song->id . '.zip');
                if ($file) {
//                    $this->line('Importing: ID ' . $song->id . ' PATH ' . $file);
                    $status = $composer->create([
                        'userId'      => $song->ownerid,
                        'songId'      => $song->id,
                        'name'        => html_entity_decode($song->beatname, ENT_QUOTES | ENT_HTML5),
                        'description' => html_entity_decode($song->beattext, ENT_QUOTES | ENT_HTML5),
                        'created_at'  => Carbon::createFromTimestamp($song->uploadtime),
                    ], $file);
                    if ($status['status'] == $composer::SONG_CREATED || $status['status'] == $composer::SONG_UPDATED) {
                        $detail = Song::find($song->id)->details->first();
                        $detail->download_count = $song->downloads;
                        $detail->save();
                    } else {
                        $this->line('SongID: ' . $song->id . ' failed to import, parsing failed');
                    }
                } else {
                    $this->line('SongID: ' . $song->id . ' failed to import, file not found');
                }
            }
        });
    }

    protected function importVotes()
    {
        $this->info('Start Votes Import');
        $this->legacyConnection->table('votes')->orderBy('id')->chunk(1000, function ($votes) {
            foreach ($votes as $vote) {
                if (User::find($vote->userid)) {
                    if ($song = Song::find($vote->beatid)) {
                        $detail = $song->details->first();
                        $newVote = new Vote;
                        $newVote->user_id = $vote->userid;
                        $newVote->song_id = $vote->beatid;
                        $newVote->detail_id = $detail->id;
                        $newVote->direction = 1;
                        $newVote->save();
                    }
                }
            }
        });
    }
}
