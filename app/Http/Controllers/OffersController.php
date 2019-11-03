<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OffersController extends Controller
{

    public function offersPage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category'  => 'integer',
            'sort'      => 'numeric',
            'price-min' => 'numeric',
            'price-max' => 'numeric',
        ]);

        $req_category = $request->input('category');
        $req_sort     = $request->input('sort');
        $req_priceMin = $request->input('price-min');
        $req_priceMax = $request->input('price-max');

        if ($validator->fails()) {

            if ($validator->errors()->first('category') != '') {
                $req_category = 0;
            }

            if ($validator->errors()->first('sort') != '') {
                $req_sort = 0;
            }

            if ($validator->errors()->first('price-min') != '') {
                $req_priceMin = 0;
            }

            if ($validator->errors()->first('price-max') != '') {
                $req_priceMax = 0;
            }

        }

        $categories = Category::where('active', 1)->where('visible', 1)->where('overcategory', $req_category)->orderBy('orderID', 'ASC')->get();

        $categoriesList = array();

        foreach ($categories as $category) {
            $ar = array();

            $ar['id']   = $category->id;
            $ar['name'] = $category->name;
            $ar['icon'] = $category->icon;

            array_push($categoriesList, $ar);
        }

        $currentCategory = [
            'id'   => 0,
            'name' => "Wszystkie kategorie",
        ];

        $overcategory = Category::where('active', 1)->where('visible', 1)->where('id', $req_category)->first();

        $overCategoryInfo = [
            'id'   => 0,
            'name' => 'Wszystkich kategorii',
        ];

        if ($overcategory != null) {

            $currentCategory = [
                'id'   => $overcategory->id,
                'name' => $overcategory->name,
            ];

            $overcategory = Category::where('active', 1)->where('visible', 1)->where('id', $overcategory->overcategory)->first();

            if ($overcategory != null) {
                $overCategoryInfo = [
                    'id'   => $overcategory->id,
                    'name' => $overcategory->name,
                ];
            }

        }

        $products     = Product::all();
        $productsData = array();

        foreach ($products as $value) {

            if ($value->status == 'INVISIBLE') {
                continue;
            }

            if ($req_category != 0 && $value->category_id != $req_category) {
                continue;
            }

            if ($req_priceMin != "" && $value->price < $req_priceMin) {
                continue;
            }

            if ($req_priceMax != "" && $value->price > $req_priceMax) {
                continue;
            }

            $product           = array();
            $product['id']     = $value->id;
            $product['name']   = $value->title;
            $product['price']  = number_format((float) $value->price, 2, '.', '');
            $product['buyers'] = rand(0, 100);
            $product['image']  = (count($value->images) > 0 ? $value->images[0]->name : null);

            $productsData[$value->id] = $product;
        }

        //   SORT
        //      1 od najnowszych
        //      2 od najstarszych
        //      3 popularności
        //      4 cena rosnąco
        //      5 cena malejąco

        switch ($req_sort) {
            case 1:
                break;
            case 2:
                break;
            case 3:
                usort($productsData, function ($a, $b) {

                    if ($a['buyers'] == $b['buyers']) {
                        return 0;
                    }

                    if ($a['buyers'] < $b['buyers']) {
                        return 1;
                    }

                    return -1;
                });
                break;
            case 4:
                usort($productsData, function ($a, $b) {

                    if ($a['price'] == $b['price']) {
                        return 0;
                    }

                    if ($a['price'] > $b['price']) {
                        return 1;
                    }

                    return -1;
                });
                break;
            case 5:
                usort($productsData, function ($a, $b) {

                    if ($a['price'] == $b['price']) {
                        return 0;
                    }

                    if ($a['price'] < $b['price']) {
                        return 1;
                    }

                    return -1;
                });
                break;
        }

        return view('offers.list')->with('categoriesList', $categoriesList)->with('currentCategory', $currentCategory)->with('overCategory', $overCategoryInfo)->with('productsList', $productsData);
    }

}
