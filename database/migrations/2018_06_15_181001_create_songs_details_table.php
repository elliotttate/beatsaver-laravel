<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSongsDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('song_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('song_id');
            $table->string('song_name');
            $table->string('song_sub_name')->default('');
            $table->string('author_name');
            $table->unsignedInteger('play_count')->default(0);
            $table->unsignedInteger('download_count')->default(0);
            $table->unsignedInteger('bpm')->default(0);
            $table->json('difficulty_levels');
            $table->string('hash_md5');
            $table->string('hash_sha1');
            $table->softDeletes();
            $table->timestamps();


            $table->foreign('song_id')->references('id')->on('songs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('song_details');
    }
}
