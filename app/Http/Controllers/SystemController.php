<?php

namespace App\Http\Controllers;

use App\Helpers\Payments\PayUHelper;

class SystemController extends Controller
{

    public function handlePayU()
    {
        $payu = new PayUHelper();
        $payu->handlePayment();
    }

}
