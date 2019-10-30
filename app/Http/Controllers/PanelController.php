<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PanelController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
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
