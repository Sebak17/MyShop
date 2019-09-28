<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    

    protected $fillable = [

        'login', 'password', 'level'

    ];

    protected $hidden = [

        'password',

    ];
}
