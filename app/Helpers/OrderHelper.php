<?php

namespace App\Helpers;

use App\Models\Product;
use App\Models\OrderHistory;
use Session;

class OrderHelper
{

    public static function getDeliverCost($deliver_type)
    {

        switch ($deliver_type) {
            case 'INPOST_LOCKER':
                return config('site.deliver_cost.locker');
            case 'COURIER':
                return config('site.deliver_cost.courier');
        }

        return null;
    }

    public static function changeOrderStatus($order, $status)
    {

        if ($order == null || $status == null) {
            return;
        }

        if ($order->status == $status) {
            return;
        }

        $msg = "Zamiana statusu z " . $order->status . " na " . $status;

        OrderHistory::create([
            'order_id' => $order->id,
            'data'     => $msg,
        ]);

        $order->status = $status;
        $order->save();
    }

    public static function getProductsAvailable() {
        $shoppingCartData = Session::get('SHOPPINGCART_DATA');

        if ($shoppingCartData == null) {
            return null;
        }

        $results = array();

        foreach ($shoppingCartData as $key => $value) {
            $product = Product::where('id', $key)->where('status', 'ACTIVE')->first();

            if ($product == null) {
                continue;
            }

            if (!$product->isAvailableToBuy()) {
                array_push($results, array("type" => "NOT_AVAILABLE", "product_id" => $product->id));
                continue;
            }

            $amount = intval($value);

            if (!is_int($amount) || $amount <= 0) {
                continue;
            }

            if ($product->sizeAvailableItems() < $amount) {
                array_push($results, array("type" => "TOO_FEW", "product_id" => $product->id, "amount" => $amount));
                continue;
            }
        }

        return $results;
    }

    public static function getProductsAvailableStatus() {
        $productsFail = self::getProductsAvailable();

        $fails = array();

        if( $productsFail != null && gettype($productsFail) == 'array'  && count($productsFail) > 0 ) {

            foreach($productsFail as $pr) {

                $d = array();

                $product = Product::where('id', $pr['product_id'])->first();

                if($product == null)
                    continue;

                switch($pr['type']) {
                    case "NOT_AVAILABLE":
                        $d['msg'] = "Produkt <strong>" . $product->title . "</strong> nie jest już dostępny!";
                        break;
                    case "TOO_FEW":
                        $d['msg'] = "Produktu <strong>" . $product->title . "</strong> nie ma w ilości " . $pr['amount'] . " na magazynie! Ilość została zmieniona do " . $product->sizeAvailableItems() . ".";
                        break;
                }

                array_push($fails, $d);

            }
        }

        return $fails;
    }

    public static function refreshShoppingCart()
    {

        $shoppingCartData = Session::get('SHOPPINGCART_DATA');

        if ($shoppingCartData == null) {
            $shoppingCartData = array();
        }

        $newShoppingCartData = array();

        foreach ($shoppingCartData as $key => $value) {
            $product = Product::where('id', $key)->where('status', 'ACTIVE')->first();

            if ($product == null) {
                continue;
            }

            if (!$product->isAvailableToBuy()) {
                continue;
            }

            $amount = intval($value);

            if (!is_int($amount) || $amount <= 0) {
                continue;
            }

            if($amount > 10)
                $amount = 10;

            if ($product->sizeAvailableItems() < $amount) {
                $amount = $product->sizeAvailableItems();
            }

            $newShoppingCartData[$key] = $amount;
        }

        Session::put('SHOPPINGCART_DATA', $newShoppingCartData);
        Session::save();
    }

}
