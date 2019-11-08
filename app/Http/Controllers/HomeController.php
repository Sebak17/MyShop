<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    public function index()
    {
        return view('home/main');
    }

    public function basketPage()
    {
        return view('home/basket');
    }

    public function favoritesPage()
    {
    	
    }

}
