<?php

namespace App\Helpers;

use App\Models\Order;
use App\Models\OrderHistory;

class OrderHelper
{

    public static function getDeliverCost($deliver_type)
    {

        switch ($deliver_type) {
            case 'INPOST_LOCKER':
                return config('site.deliver_cost.locker');
            case 'COURIER':
                return config('site.deliver_cost.courier');
        }

        return null;
    }

    public static function changeOrderStatus($order, $status)
    {
        
        if($order == null || $status == null)
            return;

        if($order->status == $status)
            return;

        $msg = "Zamiana statusu z " . $order->status . " na " . $status;

        OrderHistory::create([
            'order_id' => $order->id,
            'data' => $msg,
        ]);

        $order->status = $status;
        $order->save();

    }

}
