<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstagramComment extends Model
{
    protected $table = "instagram_comments";

    protected $fillable = [
        'pk',
        'author_pk',
        'media_pk',
        'text',
        'comment_like_count'
    ];
}
