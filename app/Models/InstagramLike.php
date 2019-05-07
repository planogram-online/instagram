<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstagramLike extends Model
{
    protected $table = "instagram_likes";

    protected $fillable = [
        'liker_pk',
        'media_pk'
    ];
}
