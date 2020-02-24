<?php

namespace App\Helpers;

use App\Models\WarehouseItem;
use App\Models\WarehouseItemHistory;

class WarehouseHelper
{

    public static function changeStatus(WarehouseItem $item, $status)
    {
        
        if($item == null || $status == null)
            return;

        if($item->status == $status)
            return;

        $msg = "Zamiana statusu z " . $item->status . " na " . $status;

        WarehouseItemHistory::create([
            'item_id' => $item->id,
            'data' => $msg,
        ]);

        $item->status = $status;
        $item->save();

    }

    public static function addHistory(WarehouseItem $item, $msg)
    {
        
        if($item == null || $msg == null)
            return;

        WarehouseItemHistory::create([
            'item_id' => $item->id,
            'data' => $msg,
        ]);

    }

}
