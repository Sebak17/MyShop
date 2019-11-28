<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

	protected $table = 'payments';

    public $timestamps = true;
   
	protected $fillable = [
        'order_id', 'type', 'amount', 'status', 'cancelled'
    ];

}
