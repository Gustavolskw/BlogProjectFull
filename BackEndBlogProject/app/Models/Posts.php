<?php

namespace App\Models;

use Database\Factories\PostsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Posts extends Model
{
    /** @use HasFactory<PostsFactory> */
    use HasFactory;

    public $table = 'posts';
    public $timestamps = false;
    protected $fillable = [
        'title',
        'content',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function thread(): BelongsTo
    {
        return $this->belongsTo(Threads::class, 'thread_id', 'id');
    }
    public function postImages(): HasMany
    {
        return $this->hasMany(PostImages::class, 'post_id', 'id');
    }
}