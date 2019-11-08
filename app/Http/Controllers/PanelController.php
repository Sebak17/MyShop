<?php

namespace App\Http\Controllers;

class PanelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function basketPage()
    {
        return view('home/basket');
    }

    public function favoritesPage() {}

    public function dashboardPage()
    {
        return view('panel.dashboard');
    }

    public function ordersPage()
    {
        return view('panel.orders');
    }

    public function settingsPage()
    {
        return view('panel.settings');
    }

}
