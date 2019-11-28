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

    public function payments()
    {
        return $this->hasMany('App\Payment');
    }

    public function getCurrentPayment() {
        foreach ($this->payments as $key => $paym) {
            if(!$paym->cancelled)
                return $this->payments[$key];
        }

        return null;
    }

}
