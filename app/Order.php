<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    public $timestamps = false;

    protected $fillable = [
        'user_id', 'status', 'cost', 'time_create',
    ];

    public function products()
    {
        return $this->hasMany('App\OrderProduct');
    }

    public function payment()
    {
        return $this->hasMany('App\Payment');
    }

}
