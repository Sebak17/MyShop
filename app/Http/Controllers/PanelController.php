<?php

namespace App\Http\Controllers;

class PanelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function shoppingCartPage()
    {
        return view('home.shoppingcart');
    }

    public function favoritesPage() {
        return view('home.favorites');
    }

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
