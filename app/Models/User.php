<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password', 'hash', 'active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        
    ];


    public function location()
    {
        return $this->hasOne('App\Models\UserLocation');
    }

    public function personal()
    {
        return $this->hasOne('App\Models\UserPersonal');
    }

    public function info()
    {
        return $this->hasOne('App\Models\UserInfo');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    public function ban() 
    {
        return $this->hasOne('App\Models\Ban');
    }

    public function history() 
    {
        return $this->hasMany('App\Models\UserHistory');
    }

    public function favorites() 
    {
        return $this->hasOne('App\Models\UserFavorites');
    }

     public function getFavorites() {
        $fav = $this->favorites;

        if($fav == null) {
            $fav = \App\Models\UserFavorites::create([
                'user_id' => $this->id,
                'products' => json_encode([]),
            ]);
        }

        return $fav;
    }
}
