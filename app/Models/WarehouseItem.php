<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseItem extends Model
{
    protected $table = 'warehouse_items';

    protected $fillable = [
        'product_id', 'code',
    ];

    public $timestamps = true;
}
