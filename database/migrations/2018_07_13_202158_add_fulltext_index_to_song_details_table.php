<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFulltextIndexToSongDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('song_details', function (Blueprint $table) {
            DB::statement('CREATE FULLTEXT INDEX idx_fulltext_song_details ON song_details (song_name,song_sub_name,author_name)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('song_details', function (Blueprint $table) {
            DB::statement('DROP INDEX idx_fulltext_song_details ON song_details');
        });
    }
}
