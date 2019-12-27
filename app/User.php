<?php

namespace App;

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
        return $this->hasOne('App\UserLocation');
    }

    public function personal()
    {
        return $this->hasOne('App\UserPersonal');
    }

    public function info()
    {
        return $this->hasOne('App\UserInfo');
    }

    public function orders()
    {
        return $this->hasMany('App\Order');
    }

    public function ban() 
    {
        return $this->hasOne('App\Ban');
    }

    public function history() 
    {
        return $this->hasMany('App\UserHistory');
    }

    public function favorites() 
    {
        return $this->hasOne('App\UserFavorites');
    }

     public function getFavorites() {
        $fav = $this->favorites;

        if($fav == null) {
            $fav = \App\UserFavorites::create([
                'user_id' => $this->id,
                'products' => json_encode([]),
            ]);
        }

        return $fav;
    }
}
