<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRatingFunc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            DROP FUNCTION IF EXISTS beatsaver.calc_rating;
            CREATE FUNCTION beatsaver.calc_rating (s_id INT(10), d_id INT(10))
            RETURNS FLOAT
            BEGIN
                DECLARE total_reviews INT DEFAULT 0;
                DECLARE up_votes INT DEFAULT 0;
                DECLARE down_votes INT DEFAULT 0;
                DECLARE review_score FLOAT DEFAULT 0;
                
                SELECT COUNT(*) INTO total_reviews FROM votes WHERE song_id = s_id AND detail_id = d_id;
                
                IF total_reviews = 0 THEN
                    RETURN 0;
                ELSE
                    SELECT COUNT(*) INTO up_votes FROM votes WHERE song_id = s_id AND detail_id = d_id AND direction = 1;
                    SELECT COUNT(*) INTO down_votes FROM votes WHERE song_id = s_id AND detail_id = d_id AND direction = 0;
                
                    SET review_score = up_votes / total_reviews;
                    RETURN review_score - ((review_score - 0.5) * POW(2, -1 * LOG10(total_reviews + 1)));
                END IF;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP FUNCTION IF EXISTS beatsaver.calc_rating;');
    }
}
