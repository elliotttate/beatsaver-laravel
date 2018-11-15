<?php

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateAdminUserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'admin',
            'email' => "admin@admin.com",
            'password' => Hash::make('password123'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'admin' => true,
        ]);
    }
}
