<?php

namespace App\Http\Controllers;

class OffersController extends Controller
{

    public function offersPage()
    {
        return view('offers.list');
    }

}
