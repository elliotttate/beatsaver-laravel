<?php

namespace App\Console\Commands;

use App\Models\Song;
use App\Models\User;
use Illuminate\Console\Command;
use Storage;

class UserDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'beatsaver:user-delete {username : Name of the user you want to edit} {--p|purge : Completely remove this user (including all files)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Edit an existing user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $username = $this->argument('username');

        $user = User::withTrashed()->where('name', $username)->first();

        if (!$user) {
            $this->error("User {$username} not found");
            return;
        }

        $delete = $this->ask('Do you really want to delete this user? (yes/no)');

        if ($delete) {
            if ($this->options()['purge']) {
                $purge = $this->ask('Please confirm purge request? This action cannot be undone! (yes/no)');
                if ($purge == 'yes') {

                    $songs = Song::withTrashed()->where('user_id', $user->id)->get();
                    foreach ($songs as $song) {
                        Storage::disk()->deleteDirectory("public/songs/$song->id");
                    }
                    if ($user->forceDelete()) {
                        $this->info("User {$user->name} purged!");
                    }

                } else {
                    $this->line('User has not been purged!');
                }
            } else {
                $user->delete();
                $this->line('User has been deleted!');
            }
        } else {
            $this->line('User was not deleted!');
        }
    }
}
