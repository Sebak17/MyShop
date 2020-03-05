<?php

namespace App\Helpers;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class ProductsHelper
{

    public static function getProposedProducts()
    {

        $proposedProducts = array();

        $products = Product::where('status', 'ACTIVE')->get();

        if ($products->count() <= 4) {

            foreach ($products as $product) {
                array_push($proposedProducts, $product);
            }

            return $proposedProducts;
        }

        $productsHistorySession = Session::get('PRODUCTS_SEEN_HISTORY', []);

        if (!empty($productsHistorySession)) {

            foreach ($productsHistorySession as $id) {

                $op = Product::where('id', $id)->first();

                if ($op == null || $op->status != 'ACTIVE') {
                    continue;
                }

                for ($i = 0; $i < 4; $i++) {

                    $product = $products->where('category_id', $op->category_id)->random();

                    if ($product == null || $product->status == 'INVISIBLE') {
                        continue;
                    }

                    if (in_array($product, $proposedProducts)) {
                        continue;
                    }

                    array_push($proposedProducts, $product);

                }

            }

            shuffle($proposedProducts);
            $proposedProducts = array_slice($proposedProducts, 0, 4, true);

        }

        $attempt = 0;

        while (count($proposedProducts) != 4 && $attempt < 10) {

            $product = $products->random();

            if (in_array($product, $proposedProducts)) {
                continue;
            }

            array_push($proposedProducts, $product);

            $attempt++;
        }

        return $proposedProducts;
    }

}
