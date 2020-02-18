<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserHistory extends Model
{
    protected $table = 'users_histories';

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'type',
        'data',
        'ip',
    ];
}
