<?php

namespace App\Models;

use Database\Factories\CommentsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class comments extends Model
{
    /** @use HasFactory<CommentsFactory> */
    use HasFactory;
    public $table = 'comments';
    public $timestamps = false;
    protected $fillable = [
        'comment',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function thread(): BelongsTo
    {
        return $this->belongsTo(Threads::class, 'thread_id', 'id');
    }
}
