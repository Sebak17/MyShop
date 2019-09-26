<?php

namespace App\Http\Controllers;

class AuthorizationController extends Controller
{
    public function loginPage() 
    {
    	return view('auth.login');
    }

    public function registerPage()
    {	
    	return view('auth.register');
    }
}
