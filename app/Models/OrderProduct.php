<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
  
	protected $table = 'orders_products';

    public $timestamps = false;
   
	protected $fillable = [
        'order_id', 'product_id', 'warehouse_item_id', 'price', 'name',
    ];

    public function order()
    {
    	return $this->belongsTo('App\Models\Order');
    }

}
