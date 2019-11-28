<?php 
namespace App\Http\Controllers;

use App\Order;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PanelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function shoppingCartPage(Request $request)
    {
        $request->session()->forget('SHOPPINGCART_STATUS');
        return view('home.shoppingcart');
    }

    public function shoppingCartInformation(Request $request)
    {

        if (!$request->session()->exists('SHOPPINGCART_STATUS') || $request->session()->get('SHOPPINGCART_STATUS') != "INFORMATION") {
            return redirect()->route('home');
        }

        if (!$request->session()->exists('SHOPPINGCART_DATA')) {
            return redirect()->route('home');
        }

        $shoppingCartData = $request->session()->get('SHOPPINGCART_DATA');

        if (empty($shoppingCartData)) {
            return redirect()->route('home');
        }

        $productsData = array();
        $summaryPrice = 0;

        foreach ($shoppingCartData as $key => $value) {
            $product = Product::where('id', $key)->first();

            if ($product == null) {
                continue;
            }

            $data              = array();
            $data['id']        = $product->id;
            $data['name']      = $product->title;
            $data['amount']    = $value;
            $data['fullPrice'] = number_format((float) ($product->price * $value), 2, '.', '');
            $summaryPrice += $product->price * $value;
            array_push($productsData, $data);
        }

        $summaryPrice = number_format((float) $summaryPrice, 2, '.', '');
        return view('order.information')->with('productsData', $productsData)->with('summaryPrice', $summaryPrice);
    }

    public function orderPage(Request $request, $id)
    {

        $order = Order::where('id', $id)->first();

        if($order == null) {
            return redirect()->route('home');
        }

        $user = Auth::user();

        if($order->user_id != $user->id) {
            return redirect()->route('home');
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

        return view('order.item')->with('productsData', $productsData)->with('order', $order)->with('deliverInfo', $deliverInfo);
    }

    public function favoritesPage()
    {
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
