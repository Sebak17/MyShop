<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    
	public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function dashboardPage()
    {
    	return view('admin.dashboard');
    }

    public function categoriesPage()
    {
    	return view('admin.categories');
    }

}
