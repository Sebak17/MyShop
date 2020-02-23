<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $table = 'products';

    public $timestamps = false;

    protected $fillable = [
        'price', 'title', 'description', 'status', 'category_id', 'params',
    ];

    public function images()
    {
        return $this->hasMany('App\Models\ProductImage');
    }

    public function getBuyedAmount()
    {
        $amount = \App\Models\Order::join('orders_products', 'orders_products.order_id', '=', 'orders.id')
            ->where(function ($query) {
                $query->where('orders.status', '=', 'PAID')->orWhere('orders.status', '=', 'REALIZE')->orWhere('orders.status', '=', 'SENT')->orWhere('orders.status', '=', 'RECEIVE');
            })->where(function ($query) {
                $query->where('orders_products.product_id', '=', $this->id);
            })
            ->distinct()
            ->count('orders.id');

        return $amount;
    }

    public function items()
    {
        return $this->hasMany('App\Models\WarehouseItem');
    }

    public function getCategory() 
    {
        return \App\Models\Category::where('id', $this->category_id)->first();
    }

}
