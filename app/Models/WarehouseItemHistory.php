<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseItemHistory extends Model
{
    protected $table = 'warehouse_items_histories';

    public $timestamps = true;

    protected $fillable = [
        'item_id',
        'data',
    ];
}
