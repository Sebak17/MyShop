<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $guard = 'admin';

    protected $table = 'admins';

    public $timestamps = false;

    protected $fillable = [

        'login', 'password', 'level',

    ];

    protected $hidden = [

        'password',

    ];
}
