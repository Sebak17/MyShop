<?php

namespace App\Http\Controllers;

use App\Helpers\CategoriesHelper;
use App\Models\Category;
use App\Models\Product;
use App\Rules\ValidID;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function __construct() {}

    public function index(Request $request)
    {

        $banners = array();

        if (Storage::exists('cfg/banners.json')) {
            $banners = json_decode(Storage::get('cfg/banners.json'), true);
        }

        $categories     = Category::where('active', 1)->where('visible', 1)->where('overcategory', 0)->orderBy('orderID', 'ASC')->get();
        $categoriesData = array();

        foreach ($categories as $category) {
            $cat = array();

            $cat['id']   = $category->id;
            $cat['name'] = $category->name;
            $cat['icon'] = $category->icon;

            array_push($categoriesData, $cat);
        }

        $productsHistorySession = $request->session()->get('PRODUCTS_SEEN_HISTORY', []);
        $productsHistoryData    = array();

        foreach ($productsHistorySession as $id) {
            $product = Product::where('id', $id)->first();

            if ($product == null || $product->status == 'INVISIBLE') {
                continue;
            }

            $pr          = array();
            $pr['url']   = route('productPage') . '?id=' . $product->id;
            $pr['name']  = $product->title;
            $pr['price'] = number_format((float) $product->priceCurrent, 2, '.', '');
            $pr['image'] = (count($product->images) > 0 ? $product->images[0]->name : null);
            array_push($productsHistoryData, $pr);
        }

        $productsHistoryData = array_reverse($productsHistoryData);

        $productsProposed = array();

        if (Product::all()->count() > 0) {

            if (empty($productsHistorySession)) {

                while (count($productsProposed) != 4) {

                    $product = Product::all()->random();

                    if ($product == null || $product->status == 'INVISIBLE') {
                        continue;
                    }

                    if (isset($productsProposed[$product->id])) {
                        continue;
                    }

                    $pr                             = array();
                    $pr['url']                      = route('productPage') . '?id=' . $product->id;
                    $pr['name']                     = $product->title;
                    $pr['price']                    = number_format((float) $product->priceCurrent, 2, '.', '');
                    $pr['image']                    = (count($product->images) > 0 ? $product->images[0]->name : null);
                    $productsProposed[$product->id] = $pr;

                }

            } else {

                foreach ($productsHistorySession as $id) {

                    $op = Product::where('id', $id)->first();

                    for ($i = 0; $i < 4; $i++) {

                        $product = Product::where('category_id', $op->category_id)->get()->random();

                        if ($product == null || $product->status == 'INVISIBLE') {
                            continue;
                        }

                        if (isset($productsProposed[$product->id])) {
                            continue;
                        }

                        $pr                             = array();
                        $pr['url']                      = route('productPage') . '?id=' . $product->id;
                        $pr['name']                     = $product->title;
                        $pr['price']                    = number_format((float) $product->priceCurrent, 2, '.', '');
                        $pr['image']                    = (count($product->images) > 0 ? $product->images[0]->name : null);
                        $productsProposed[$product->id] = $pr;

                    }

                }

                shuffle($productsProposed);
                $productsProposed = array_slice($productsProposed, 0, 4, true);

                while (count($productsProposed) < 4) {

                    $product = Product::all()->random();

                    if ($product == null || $product->status == 'INVISIBLE') {
                        continue;
                    }

                    if (isset($productsProposed[$product->id])) {
                        continue;
                    }

                    $pr                             = array();
                    $pr['url']                      = route('productPage') . '?id=' . $product->id;
                    $pr['name']                     = $product->title;
                    $pr['price']                    = number_format((float) $product->priceCurrent, 2, '.', '');
                    $pr['image']                    = (count($product->images) > 0 ? $product->images[0]->name : null);
                    $productsProposed[$product->id] = $pr;

                }

            }

        }

        return view('home/main')->with('banners', $banners)->with('categories', $categoriesData)->with('productsHistory', $productsHistoryData)->with('productsProposed', $productsProposed);
    }

    public function productsPage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category'  => 'required|integer',
            'sort'      => 'required|numeric',
            'price-min' => 'required|numeric',
            'price-max' => 'required|numeric',
            'string' => 'required|string',
        ]);

        $req_category = $request->input('category');
        $req_sort     = $request->input('sort');
        $req_priceMin = $request->input('price-min');
        $req_priceMax = $request->input('price-max');
        $req_string = $request->input('string');

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

            if ($validator->errors()->first('string') != '') {
                $req_string = "";
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

        $productsData = array();

        foreach (Product::all() as $product) {

            if ($product->status == 'INVISIBLE') {
                continue;
            }

            if ($req_category != 0) {
                $isOkay = false;

                foreach ($allSubCategories as $cat) {

                    if ($cat->id == $product->category_id) {
                        $isOkay = true;
                    }

                }

            }

            if ($req_category == $product->category_id) {
                $isOkay = true;
            }

            if (isset($isOkay) && !$isOkay) {
                continue;
            }

            if ($req_string != "" && !preg_match("/(" . $req_string . ")/i", $product->title)) {
                continue;
            }

            if ($req_priceMin != "" && $product->priceCurrent < $req_priceMin) {
                continue;
            }

            if ($req_priceMax != "" && $product->priceCurrent > $req_priceMax) {
                continue;
            }

            $item           = array();
            $item['id']     = $product->id;
            $item['name']   = $product->title;
            $item['price']  = number_format((float) $product->priceCurrent, 2, '.', '');
            $item['buyers'] = $product->getBuyedAmount();
            $item['image']  = (count($product->images) > 0 ? $product->images[0]->name : null);

            $productsData[$product->id] = $item;
        }

        //   SORT
        //      1 nazwa produktu od A do Z
        //      2 nazwa produktu od Z do A
        //      3 popularności
        //      4 cena rosnąco
        //      5 cena malejąco

        switch ($req_sort) {
            case 1:
                usort($productsData, function ($a, $b) {
                    return strcmp($a['name'], $b['name']);
                });
                break;
            case 2:
                usort($productsData, function ($a, $b) {
                    return strcmp($b['name'], $a['name']);
                });
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

        return view('products.list')->with('categoriesList', $categoriesList)->with('currentCategory', $currentCategory)->with('overCategory', $overCategoryInfo)->with('productsList', $productsData);
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
            return view('products.not_exist');
        }

        $categoriesPath = "";
        $currCategoryID = $product->category_id;

        do {

            $category = Category::where('id', $currCategoryID)->first();

            $categoriesPath = '<li class="breadcrumb-item"><a href="' . route('productsPage') . '?category=' . $currCategoryID . '">' . $category->name . '</a></li>' . $categoriesPath;

            $currCategoryID = $category->overcategory;

        } while ($currCategoryID != 0);

        $productsHistory = $request->session()->get('PRODUCTS_SEEN_HISTORY', []);

        if (in_array($product->id, $productsHistory)) {

            if (($key = array_search($product->id, $productsHistory)) !== false) {
                unset($productsHistory[$key]);
            }

        }

        array_push($productsHistory, $product->id);

        if (count($productsHistory) > 10) {
            array_shift($productsHistory);
        }

        $request->session()->put('PRODUCTS_SEEN_HISTORY', $productsHistory);

        $categoriesPath = '<li class="breadcrumb-item"><a href="' . route('productsPage') . '?category=0"><i class="fas fa-home"></i></a></li>' . $categoriesPath;

        $status = "<strong class='text-muted'>BRAK DANYCH</strong>";

        if($product->isAvailableToBuy())
            $status = "<strong class='text-success'>DOSTĘPNY</strong>";
        else
            $status = "<strong class='text-danger'>BRAK NA MAGAZYNIE</strong>";

        $params = json_decode($product->params);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $params = array();
        }

        $isFavorite = false;

        if (Auth::guard('web')->check()) {
            $favs = json_decode(Auth::user()->getFavorites()->products, true);

            if (in_array($request->id, $favs)) {
                $isFavorite = true;
            }

        }

        return view('products.item')->with('categoriesPath', $categoriesPath)->with('product', $product)->with('status', $status)->with('params', $params)->with('isFavorite', $isFavorite);
    }

}
