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

    public function index()
    {
        return view('home/main');
    }

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

        if ($product == null) {
            return "Not found!";
        }

        $categoriesPath = "";
        $currCategoryID = $product->category_id;

        do {

            $category = Category::where('id', $currCategoryID)->first();

            $categoriesPath = '<li class="breadcrumb-item"><a href="oferty?category=' . $currCategoryID . '">' . $category->name . '</a></li>' . $categoriesPath;

            $currCategoryID = $category->overcategory;

        } while ($currCategoryID != 0);

        $categoriesPath = '<li class="breadcrumb-item"><a href="oferty?category=0"><i class="fas fa-home"></i></a></li>' . $categoriesPath;

        $status = "<strong class='text-muted'>BRAK DANYCH</strong>";

        switch ($product->status) {
            case "IN_STOCK":
                $status = "<strong class='text-success'>DOSTĘPNY</strong>";
                break;
            case "INACCESSIBLE":
                $status = "<strong class='text-danger'>BRAK NA MAGAZYNIE</strong>";
                break;
        }

        $request->session()->put('CURRECT_PRODUCT_PAGE', $product->id);

        return view('offers.item')->with('categoriesPath', $categoriesPath)->with('product', $product)->with('status', $status);
    }

}
