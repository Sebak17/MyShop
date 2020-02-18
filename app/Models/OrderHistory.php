<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    protected $table = 'orders_histories';

    public $timestamps = true;

    protected $fillable = [
        'order_id',
        'data',
    ];
}
