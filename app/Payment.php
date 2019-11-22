<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

	protected $table = 'payments';

    public $timestamps = false;
   
	protected $fillable = [
        'order_id', 'type', 'amount', 'status'
    ];

}
