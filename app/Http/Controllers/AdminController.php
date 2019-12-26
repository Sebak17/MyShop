<?php

namespace App\Http\Controllers;

use App\User;
use App\Order;
use App\OrderHistory;
use App\UserHistory;
use App\Product;
use App\Rules\ValidID;
use App\Rules\ValidEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

        return view('admin.products.edit')->with('product', $product);
    }

    public function ordersListPage()
    {
        $orders = Order::all();

        $realizeOrders = Order::where('status', 'REALIZE')->count();

        return view('admin.orders.list')->with('orders', $orders)->with('realizeOrders', $realizeOrders);
    }

    public function orderPage(Request $request, $id)
    {
        $order = Order::where('id', $id)->first();

        if ($order == null) {
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

        $deliverInfo         = json_decode($order->deliver_info, true);
        $deliverInfo['type'] = $order->deliver_name;

        $buyerInfo         = json_decode($order->buyer_info, true);

        $orderHistory = OrderHistory::where('order_id', $order->id)->get();

        return view('admin.orders.item')->with('productsData', $productsData)->with('order', $order)->with('deliverInfo', $deliverInfo)->with('buyerInfo', $buyerInfo)->with('orderHistory', $orderHistory);
    }

    public function ordersRealisingListPage()
    {

        $orders = Order::where('status', 'REALIZE')->get();

        return view('admin.orders.realising_list')->with('orders', $orders);
    }

    public function usersListPage()
    {
        return view('admin.users.list');
    }

    public function userPage(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => new ValidID,
            'email' => new ValidEmail,
        ]);

        $results = array();

        $user = null;

        if ($validator->errors()->first('id') == '') {
            $user = User::where('id', $request->id)->first();
        }

        if ($user == null && $validator->errors()->first('email') == '') {
            $user = User::where('email', $request->email)->first();
        }

        if($user == null) {
            return view('admin.users.not_exist');
        }

        $historyData = [];

        foreach ($user->history as $his) {

            $obj           = array();

            $obj['type'] = $his->type;
            $obj['typeName'] = config('site.user_history.' . $his->type);
            $obj['data'] = $his->data;
            $obj['ip'] = $his->ip;
            $obj['time'] = $his->created_at->format('Y-m-d H:i:s');

            array_push($historyData, $obj);
        }

        $historyData = json_encode($historyData);

        return view('admin.users.item')->with('user', $user)->with('historyData', $historyData);
    }

}
