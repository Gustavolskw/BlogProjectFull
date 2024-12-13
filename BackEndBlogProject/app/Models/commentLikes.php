<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class commentLikes extends Model
{
    /** @use HasFactory<\Database\Factories\CommentLikesFactory> */
    use HasFactory;
    public $table = 'comment_likes';
    public $timestamps = false;
    public function comment(): BelongsTo
    {
        return $this->belongsTo(comments::class, 'comment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}