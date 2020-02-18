<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFavorites extends Model
{
    protected $table = 'users_favorites';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'products',
    ];
}
