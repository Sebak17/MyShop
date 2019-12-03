<?php

namespace App\Http\Controllers;

use App\Product;
use App\Order;
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
        return view('admin.products.list');
    }

    public function productsAddPage()
    {
        return view('admin.products.add');
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

        return view('admin.products.edit');
    }

    public function ordersListPage()
    {
        $orders = Order::all();

        return view('admin.orders.list')->with('orders', $orders);
    }

    public function orderPage(Request $request, $id)
    {
        $order = Order::where('id', $id)->first();

        if($order == null) {
            return view('admin.orders.not_exist')->with('id', $id);
        }

        $productsData = array();

        foreach ($order->products as $product) {
            $data              = array();
            $data['id']        = $product->product_id;
            $data['name']      = $product->name;
            $data['amount']    = $product->amount;
            $data['fullPrice'] = number_format((float) ($product->price * $product->amount), 2, '.', '');
            array_push($productsData, $data);
        }

        $deliverInfo = json_decode($order->deliver_info, true);
        $deliverInfo['type'] = $order->deliver_name;

        return view('admin.orders.item')->with('productsData', $productsData)->with('order', $order)->with('deliverInfo', $deliverInfo);
    }

}
