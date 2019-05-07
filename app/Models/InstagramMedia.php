<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstagramMedia extends Model
{
    protected $table = "instagram_media";

    protected $fillable = [
        'pk',
        'author_pk',
        'media_type',
        'like_count',
        'view_count',
    ];
}
