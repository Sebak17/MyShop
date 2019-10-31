<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{

    public function index()
    {
        return view('home/main');
    }

    public function basketPage()
    {
        return view('home/basket');
    }

}
