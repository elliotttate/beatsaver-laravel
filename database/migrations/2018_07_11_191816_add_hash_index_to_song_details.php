<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHashIndexToSongDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('song_details', function (Blueprint $table) {
            $table->index(['hash_md5', 'hash_sha1']);
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
            $table->dropIndex(['hash_md5', 'hash_sha1']);
        });
    }
}
