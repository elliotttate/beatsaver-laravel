<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(CreateAdminUserSeed::class);
         $this->call(CreateUsersSeed::class);
         $this->call([GenresTableSeeder::class]);
    }
}
