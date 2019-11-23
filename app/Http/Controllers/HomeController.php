<?php

namespace App\Http\Controllers;

use App\Category;
use App\Helpers\CategoriesHelper;
use App\Product;
use App\Rules\ValidID;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function __construct() {}

    public function index(Request $request)
    {

        $categories = Category::where('active', 1)->where('visible', 1)->where('overcategory', 0)->orderBy('orderID', 'ASC')->get();
        $categoriesData = array();

        foreach ($categories as $category) {
            $cat = array();

            $cat['id']   = $category->id;
            $cat['name'] = $category->name;
            $cat['icon'] = $category->icon;

            array_push($categoriesData, $cat);
        }




        $productsHistorySession = $request->session()->get('PRODUCTS_SEEN_HISTORY', []);
        $productsHistoryData = array();

        foreach ($productsHistorySession as $id) {
            $product = Product::where('id', $id)->first();
            if ($product == null || $product->status == 'INVISIBLE') {
                continue;
            }

            $pr           = array();
            $pr['url']     = route('productPage') . '?id=' . $product->id;
            $pr['name']   = $product->title;
            $pr['price']  = number_format((float) $product->price, 2, '.', '');
            $pr['image']  = (count($product->images) > 0 ? $product->images[0]->name : null);
            array_push($productsHistoryData, $pr);
        }

        $productsHistoryData = array_reverse($productsHistoryData);

        return view('home/main')->with('categories', $categoriesData)->with('productsHistory', $productsHistoryData);
    }

    public function productsPage(Request $request)
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

        $categories    = Category::where('active', 1)->where('visible', 1)->where('overcategory', $req_category)->orderBy('orderID', 'ASC')->get();
        $allCategories = Category::where('active', 1)->where('visible', 1)->get();

        $allSubCategories = CategoriesHelper::getAllSubCategories($allCategories, $req_category);

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

            if ($req_category != 0) {
                $isOkay = false;

                foreach ($allSubCategories as $cat) {

                    if ($cat->id == $value->category_id) {
                        $isOkay = true;
                    }

                }

            }

            if ($req_category == $value->category_id) {
                $isOkay = true;
            }

            if (isset($isOkay) && !$isOkay) {
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

    public function productPage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => new ValidID,
        ]);

        if ($validator->fails()) {
            return redirect()->route('home');
        }

        $product = Product::where('id', $request->id)->first();

        if ($product == null || $product->status == 'INVISIBLE') {
            // TODO
            return "Not found!";
        }

        $categoriesPath = "";
        $currCategoryID = $product->category_id;

        do {

            $category = Category::where('id', $currCategoryID)->first();

            $categoriesPath = '<li class="breadcrumb-item"><a href="' . route('productsPage') .'?category=' . $currCategoryID . '">' . $category->name . '</a></li>' . $categoriesPath;

            $currCategoryID = $category->overcategory;

        } while ($currCategoryID != 0);

        $productsHistory = $request->session()->get('PRODUCTS_SEEN_HISTORY', []);

        if (in_array($product->id, $productsHistory)) {

            if (($key = array_search($product->id, $productsHistory)) !== false) {
                unset($productsHistory[$key]);
            }

        }

        array_push($productsHistory, $product->id);

        if(count($productsHistory) > 10) {
            array_shift($productsHistory);
        }


        $request->session()->put('PRODUCTS_SEEN_HISTORY', $productsHistory);

        $categoriesPath = '<li class="breadcrumb-item"><a href="' . route('productsPage') .'?category=0"><i class="fas fa-home"></i></a></li>' . $categoriesPath;

        $status = "<strong class='text-muted'>BRAK DANYCH</strong>";

        switch ($product->status) {
            case "IN_STOCK":
                $status = "<strong class='text-success'>DOSTĘPNY</strong>";
                break;
            case "INACCESSIBLE":
                $status = "<strong class='text-danger'>BRAK NA MAGAZYNIE</strong>";
                break;
        }

        return view('offers.item')->with('categoriesPath', $categoriesPath)->with('product', $product)->with('status', $status);
    }

}
