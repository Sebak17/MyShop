<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

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

         return view('order.information');
    }

    public function shoppingCartConfirm(Request $request)
    {
        if (!$request->session()->exists('SHOPPINGCART_STATUS') || $request->session()->get('SHOPPINGCART_STATUS') != "CONFIRM") {
            return redirect()->route('home');
        }

        if (!$request->session()->exists('SHOPPINGCART_DATA')) {
            return redirect()->route('home');
        }

        $shoppingCartData = $request->session()->get('SHOPPINGCART_DATA');

        if(empty($shoppingCartData)) {
            return redirect()->route('home');
        }

        $productsData = array();
        $summaryPrice = 0;

        foreach ($shoppingCartData as $key => $value) {
            
            $product = Product::where('id', $key)->first();

            if($product == null)
                continue;

            $data = array();

            $data['id'] = $product->id;
            $data['name'] = $product->title;
            $data['amount'] = $value;
            $data['image'] = (count($product->images) > 0 ? $product->images[0]->name : null);
            $data['fullPrice'] = number_format((float) ($product->price * $value), 2, '.', '');
            $summaryPrice += $product->price * $value;

            array_push($productsData, $data);
        }

        $summaryPrice = number_format((float) $summaryPrice, 2, '.', '');

        return view('order.confirmation')->with('productsData', $productsData)->with('summaryPrice', $summaryPrice);
    }

    public function favoritesPage() {
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
