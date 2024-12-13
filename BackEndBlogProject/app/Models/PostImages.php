<?php

namespace App\Models;

use Database\Factories\PostImagesFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostImages extends Model
{
    /** @use HasFactory<PostImagesFactory> */
    use HasFactory;
    protected $table = 'post_images';
    protected $fillable = [
        'image_url'
    ];
    public $timestamps = false;
    public function post(): BelongsTo
    {
        return $this->belongsTo(Posts::class, 'post_id', 'id');
    }
}
