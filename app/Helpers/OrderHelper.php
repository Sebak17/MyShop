<?php

namespace App\Helpers;

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

}
