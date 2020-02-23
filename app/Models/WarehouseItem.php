<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseItem extends Model
{
    protected $table = 'warehouse_items';

    protected $fillable = [
        'product_id', 'code', 'status',
    ];

    public $timestamps = true;

    public function product() 
    {
    	return $this->belongsTo('App\Models\Product');
    }

    public function history() 
    {
        return $this->hasMany('App\Models\WarehouseItemHistory', 'item_id');
    }
}
