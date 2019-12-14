<?php

namespace App\Helpers\Payments;

use App\Helpers\OrderHelper;
use App\Order;
use Session;

class PayUHelper
{

    public function __construct()
    {


    }

    public function createPayment(Order $order, \App\Payment $order_payment)
    {

        $results = array();


        $results['success'] = false;
        $results['msg']     = "Wystąpił nieznany błąd!";
        return $results;
    }

    public function getPaymentStatus()
    {
        $results = array();

        return $results;
    }

}
