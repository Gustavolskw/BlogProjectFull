<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::unprepared('
            CREATE TRIGGER comment_decrement_like_count
            AFTER DELETE ON comment_likes
            FOR EACH ROW
            BEGIN
                UPDATE comments
                SET like_count = like_count - 1
                WHERE id = OLD.comment_id;
            END;
        ');
    }

    public function down()
    {
        DB::unprepared('
            DROP TRIGGER comment_decrement_like_count;
        ');
    }
};