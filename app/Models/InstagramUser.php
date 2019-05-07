<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstagramUser extends Model
{
    protected $table = "instagram_users";

    protected $fillable = [
        'pk',
        'username',
        'full_name',
        'is_private',
        'profile_pic_url',
        'profile_pic_id',
        'is_verified',
        'has_anonymous_profile_picture',
    ];
}
