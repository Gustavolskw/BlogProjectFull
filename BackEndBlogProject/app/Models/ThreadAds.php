<?php

namespace App\Models;

use Database\Factories\ThreadAdsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThreadAds extends Model
{
    /** @use HasFactory<ThreadAdsFactory> */
    use HasFactory;

    public $table = 'thread_ads';
    public $primaryKey ='id';
    protected $fillable = [
        'ad_link',
        'ad_img'
    ];
    public $timestamps = false;

    public function threads()
    {
        return $this->belongsTo(Threads::class);
    }
}
