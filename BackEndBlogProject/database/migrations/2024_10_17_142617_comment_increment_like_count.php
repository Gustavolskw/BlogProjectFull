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
    public function up(): void
    {
        DB::unprepared('
            CREATE TRIGGER comment_increment_like_count
            AFTER INSERT ON comment_likes
            FOR EACH ROW
            BEGIN
                UPDATE comments
                SET like_count = like_count + 1
                WHERE id = NEW.comment_id;
            END;
        ');
    }

    public function down()
    {
        DB::unprepared('
            DROP TRIGGER comment_increment_like_count;
        ');
    }
};