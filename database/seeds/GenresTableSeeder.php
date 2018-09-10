<?php

use Illuminate\Database\Seeder;

class GenresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('genres')->insert([
        ['name' => 'Blues'],
        ['name' => 'Classical music'],
        ['name' => 'Comedy'],
        ['name' => 'Country'],
        ['name' => 'Easy listening'],
        ['name' => 'Electronic'],
        ['name' => 'Folk'],
        ['name' => 'Hip hop'],
        ['name' => 'Jazz'],
        ['name' => 'Latin'],
        ['name' => 'Metal'],
        ['name' => 'Pop'],
        ['name' => 'R&B and soul'],
        ['name' => 'Rock']
    ]);


    }
}
