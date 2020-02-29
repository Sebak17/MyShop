<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $table = 'products';

    public $timestamps = false;

    protected $fillable = [
        'priceCurrent', 'priceNormal', 'title', 'description', 'status', 'category_id', 'params',
    ];

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

    public function getBoughtItemsTotal()
    {
        $amount = 0;

        foreach ($this->items as $item) {

            if ($item->status == 'BOUGHT') {
                $amount++;
            }

        }

        return $amount;
    }

    public function getOrders()
    {
        $orders = \App\Models\Order::join('orders_products', 'orders_products.order_id', '=', 'orders.id')
            ->where(function ($query) {
                $query->where('orders.status', '=', 'PAID')->orWhere('orders.status', '=', 'REALIZE')->orWhere('orders.status', '=', 'SENT')->orWhere('orders.status', '=', 'RECEIVE');
            })->where(function ($query) {
            $query->where('orders_products.product_id', '=', $this->id);
        })
            ->distinct()
            ->get();

        return $orders;
    }

    public function isAvailableToBuy() 
    {
        if($this->status != 'ACTIVE')
            return false;

        if( count($this->items->where('status', 'AVAILABLE')) > 0 )
            return true;
        return false;
    }

    public function sizeAvailableItems() {
        return $this->items->where('status', 'AVAILABLE')->count();
    }

    public function areItemsAvailable($amount) {

        if(count($this->items->where('status', 'AVAILABLE')->take($amount)) != $amount)
            return null;

        return true;
    }

    public function getFirstAvailableItem() {
        return $this->items->where('status', 'AVAILABLE')->first();
    }

    public function images()
    {
        return $this->hasMany('App\Models\ProductImage');
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
