<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'status',
        'cost',
        'buyer_info',
        'deliver_name',
        'deliver_info',
        'payment',
        'note',
    ];

    public function products()
    {
        return $this->hasMany('App\OrderProduct');
    }

    public function payment()
    {
        return $this->hasOne('App\Payment');
    }

}
