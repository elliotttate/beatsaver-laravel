<?php

namespace App\Console\Commands;

use App\Events\UserRegistered;
use App\Models\User;
use Hash;
use Illuminate\Console\Command;

class UserEdit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'beatsaver:user-edit {username : Name of the user you want to edit} {--p|password : set a new password for user} {--e|email : set a new email for the user}';

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

        $user = User::where('name', $username)->first();

        if (!$user) {
            $this->error("User {$username} not found");
        }

        if ($this->options()['email']) {
            $email = $this->ask('New Email:');
            $user->email = $email;
            if ($email && $user->save()) {
                event(new UserRegistered($user));
                $this->line("Email for user {$user->name} changed to {$user->email}, confirmation sent.");
            }
        }

        if ($this->options()['password']) {
            $password1 = $this->secret('New User Password:');
            $password2 = $this->secret('New User Password Confirm:');
            if ($password1 == $password2) {
                $user->password = Hash::make($password1);
                $user->save();
            }
        }
    }
}
