<?php

namespace App\Models;

use Database\Factories\ThreadLikesFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThreadLikes extends Model
{
    /** @use HasFactory<ThreadLikesFactory> */
    use HasFactory;

    public $table = 'thread_likes';

    public $primaryKey= 'id';

    protected $fillable = [
        'data_like'
    ];

    public $timestamps = false;

    public function threads(): BelongsTo
    {
        return $this->belongsTo(Threads::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
