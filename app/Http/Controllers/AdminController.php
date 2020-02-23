<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\Product;
use App\Models\User;
use App\Models\WarehouseItem;
use App\Rules\ValidEmail;
use App\Rules\ValidID;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

    public function productsItemPage(Request $request, $id)
    {
        if (!is_numeric($id)) {
            return redirect()->route('admin_dashboardPage');
        }

        $product = Product::where('id', $id)->first();

        if ($product == null) {
            return redirect()->route('admin_dashboardPage');
        }
        
        return view('admin.products.item')->with('product', $product);
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

        $realizeOrders = Order::where('status', 'REALIZE')->orWhere('status', 'PAID')->count();

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

        $buyerInfo = json_decode($order->buyer_info, true);

        $orderHistory = OrderHistory::where('order_id', $order->id)->get();

        return view('admin.orders.item')->with('productsData', $productsData)->with('order', $order)->with('deliverInfo', $deliverInfo)->with('buyerInfo', $buyerInfo)->with('orderHistory', $orderHistory);
    }

    public function ordersRealisingListPage()
    {

        $orders = Order::where('status', 'REALIZE')->orWhere('status', 'PAID')->get();

        return view('admin.orders.realising_list')->with('orders', $orders);
    }

    public function warehousePage()
    {
        return view('admin.warehouse');
    }

    public function warehouseListPage()
    {
        return view('admin.warehouse.list');
    }

    public function warehouseProductPage($id)
    {

        $product = Product::where('id', $id)->first();

        if ($product == null) {
            return redirect()->route('admin_warehousePage');
        }

        $items = $product->items;

        return view('admin.warehouse.product')->with('product', $product)->with('items', $items);
    }

    public function warehouseItemSearchPage(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'code'      => 'required|string|min:6|max:100',
        ]);

        if ($validator->fails()) {
            return view('admin.warehouse.not_exist');
        }

        $item = WarehouseItem::where('code', $request->code)->first();

        if($item == null) {
            return view('admin.warehouse.not_exist');
        }

        return view('admin.warehouse.item')->with('item', $item);
    }

    public function usersListPage()
    {
        return view('admin.users.list');
    }

    public function userPage(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id'    => new ValidID,
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

        if ($user == null) {
            return view('admin.users.not_exist');
        }

        $historyData = [];

        foreach ($user->history as $his) {

            $obj = array();

            $obj['type']     = $his->type;
            $obj['typeName'] = config('site.user_history.' . $his->type);
            $obj['data']     = $his->data;
            $obj['ip']       = $his->ip;
            $obj['time']     = $his->created_at->format('Y-m-d H:i:s');

            array_push($historyData, $obj);
        }

        $historyData = json_encode($historyData);

        return view('admin.users.item')->with('user', $user)->with('historyData', $historyData);
    }

    public function settingsPage()
    {
        return view('admin.settings');
    }

    public function settingsBannersPage()
    {
        $images = array();

        if (Storage::exists('banners.json')) {
            $images = json_decode(Storage::get('banners.json'), true);
        }

        return view('admin.settings.banners')->with('images', $images);
    }

    public function settingsMaintenancePage()
    {
        $data = array();

        if (file_exists(storage_path('framework/down'))) {
            $data['enabled'] = true;

            $mainInfo = json_decode(file_get_contents(storage_path('framework/down')), true);

            $data['msg'] = $mainInfo['message'];

            $data['allowed'] = array();

            foreach ($mainInfo['allowed'] as $v) {
                array_push($data['allowed'], $v);
            }

        } else {
            $data['enabled'] = false;
        }

        $ips = array();

        if (Storage::exists('allowed_ips.json')) {
            $ips = json_decode(Storage::get('allowed_ips.json'), true);
        }

        return view('admin.settings.maintenance')->with('data', $data)->with('ips', $ips);
    }

}
