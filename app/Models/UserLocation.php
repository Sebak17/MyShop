<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLocation extends Model
{

    protected $table = 'users_location';

    protected $fillable = [
        'user_id', 'district', 'city', 'zipcode', 'address',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

}
