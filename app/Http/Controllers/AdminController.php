<?php

namespace App\Http\Controllers;

class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin.auth');
    }

    public function loginPage()
    {
        return view('admin.login');
    }

    public function dashboardPage()
    {
        return view('admin.dashboard');
    }

    public function categoriesPage()
    {
        return view('admin.categories');
    }

    public function productsListPage()
    {
        return view('admin.products_list');
    }

    public function productsAddPage()
    {
        return view('admin.products_add');
    }

}
