<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
  
	protected $table = 'orders_products';

    public $timestamps = false;
   
	protected $fillable = [
        'order_id', 'product_id', 'price', 'amount', 'name',
    ];

    public function order()
    {
    	return $this->belongsTo('App\Order');
    }

}
