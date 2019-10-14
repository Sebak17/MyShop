<?php

namespace App\Http\Controllers;

class AdminAuthController extends Controller
{

    public function loginPage()
    {
        return view('admin.login');
    }

    public function signIn() {}

}
