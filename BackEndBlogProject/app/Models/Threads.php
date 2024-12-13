<?php

namespace App\Models;

use Database\Factories\ThreadsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Threads extends Model
{
    /** @use HasFactory<ThreadsFactory> */
    use HasFactory;
    protected $table = 'threads';
    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'description',
        'thread_img',
    ];

    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function posts()
    {
        return $this->hasMany(Posts::class, 'thread_id', 'id');
    }

    public function threadAds(): HasMany
    {
        return $this->hasMany(ThreadAds::class);
    }

    public function threadComments(): HasMany
    {
        return $this->hasMany(comments::class, 'thread_id', 'id');
    }
}