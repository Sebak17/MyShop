<?php

namespace App;

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
        return $this->hasMany('App\ProductImage');
    }

    public function getBuyedAmount()
    {
        $amount = \App\Order::join('orders_products', 'orders_products.order_id', '=', 'orders.id')
            ->where(function ($query) {
                $query->where('orders.status', '=', 'PAID')->orWhere('orders.status', '=', 'REALIZE')->orWhere('orders.status', '=', 'SENT')->orWhere('orders.status', '=', 'RECEIVE');
            })->where(function ($query) {
                $query->where('orders_products.product_id', '=', $this->id);
            })
            ->distinct()
            ->count('orders.id');

        return $amount;
    }

}
