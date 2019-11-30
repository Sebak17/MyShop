<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

	protected $table = 'payments';

    public $timestamps = true;
   
	protected $fillable = [
        'order_id', 'externalID', 'type', 'amount', 'status', 'cancelled'
    ];

    public function order()
    {
        return $this->belongsTo('App\Order');
    }

}
