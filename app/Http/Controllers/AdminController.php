<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

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

    public function productsEditPage(Request $request, $id)
    {

        if (!is_numeric($id)) {
            return redirect()->route('admin_dashboardPage');
        }

        $product = Product::where('id', $id)->first();

        if ($product == null) {
            return redirect()->route('admin_dashboardPage');
        }

        $request->session()->put('ADMIN_PRODUCT_EDIT_ID', $id);

        return view('admin.products_edit');
    }

}
