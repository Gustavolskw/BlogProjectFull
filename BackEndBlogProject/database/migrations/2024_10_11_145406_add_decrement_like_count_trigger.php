<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        DB::unprepared('
            CREATE TRIGGER decrement_like_count
            AFTER DELETE ON thread_likes
            FOR EACH ROW
            BEGIN
                UPDATE threads
                SET like_count = like_count - 1
                WHERE id = OLD.thread_id;
            END;
        ');
    }

    public function down()
    {
        DB::unprepared('
            DROP TRIGGER decrement_like_count;
        ');
    }
};
