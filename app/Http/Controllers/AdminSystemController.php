<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\ProductImage;
use App\Rules\ValidProductCategory;
use App\Rules\ValidProductDescription;
use App\Rules\ValidProductImage;
use App\Rules\ValidProductName;
use App\Rules\ValidProductPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminSystemController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }



    //
    //      GENERAL
    //

    public function categoryList()
    {

        $results = array();

        $list1 = array();

        $categories = Category::where('active', 1)->get();

        $i = 0;

        foreach ($categories as $cat) {

            $list1[$i] = array();

            $list1[$i]['id']    = $cat['id'];
            $list1[$i]['name']  = $cat['name'];
            $list1[$i]['order'] = $cat['orderID'];
            $list1[$i]['icon']  = $cat['icon'];

            if ($cat['overcategory'] != 0) {
                $list1[$i]['overcategory'] = $cat['overcategory'];
            }

            $i++;
        }

        $results['success'] = true;
        $results['list1']   = $list1;

        return response()->json($results);

    }

    public function productLoadList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => new ValidProductName,
            'minPrice' => new ValidProductPrice,
            'maxPrice' => new ValidProductPrice,
        ]);

        $results = array();

        $useParams = false;

        if (!$validator->fails()) {
            $useParams = true;
        }

        $list = array();

        $products = Product::get();

        $i = 0;

        foreach ($products as $prod) {

            if ($useParams) {

                if ($request->name != "" && !preg_match("/(" . $request->name . ")/i", $prod['title'])) {
                    continue;
                }

                if ($request->minPrice != "" && $prod['price'] < $request->minPrice) {
                    continue;
                }

                if ($request->maxPrice != "" && $prod['price'] > $request->maxPrice) {
                    continue;
                }

            }

            $list[$i] = array();

            $list[$i]['id']    = $prod['id'];
            $list[$i]['name']  = $prod['title'];
            $list[$i]['price'] = $prod['price'];

            $list[$i]['image1'] = (count($prod->images) > 0 ? $prod->images[0]->name : null);

            $i++;
        }

        $results['success'] = true;
        $results['list']    = $list;

        return response()->json($results);
    }



    //
    //      PRODUCT CREATE SITES
    //

    public function productAddImageUpload(Request $request)
    {
        $results = array();

        if (!$request->hasFile('images')) {
            $results['success'] = false;
            return response()->json($results);
        }

        $validator = Validator::make($request->all(), [
            'images'   => 'required',
            'images.*' => 'mimes:png,jpeg',
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;
            $results['error'] = "validator";
            $results['msg'] = $validator->errors()->first();
            return response()->json($results);
        }

        $files = $request->file('images');

        foreach ($files as $file) {
            $ar   = explode("/", $file->store('public/tmp_images'));
            $hash = end($ar);

            $request->session()->push('tmp_images', $hash);
        }

        $results['success'] = true;

        return response()->json($results);
    }

    public function productAddImageRemove(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => new ValidProductImage,
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();

            return response()->json($results);
        }

        if (!is_array($request->session()->get('tmp_images'))) {
            $results['success'] = false;
            return response()->json($results);
        }

        if (!Storage::exists("public/tmp_images/" . $request->name)) {
            $results['success'] = false;
            $results['msg'] = "Plik nie istnieje!";
            return response()->json($results);
        }

        Storage::delete("public/tmp_images/" . $request->name);
        $request->session()->put('tmp_images', array_diff($request->session()->get('tmp_images'), [$request->name]));

        $results['success'] = true;
        return response()->json($results);
    }

    public function productLoadOldImages(Request $request)
    {
        $results = array();

        if (!is_array($request->session()->get('tmp_images'))) {
            $results['success'] = false;
            return response()->json($results);
        }

        $results['images'] = array();

        foreach ($request->session()->get('tmp_images') as $hash) {

            if (!Storage::exists("public/tmp_images/" . $hash)) {
                $request->session()->put('tmp_images', array_diff($request->session()->get('tmp_images'), [$hash]));
                continue;
            }

            array_push($results['images'], $hash);
        }

        $results['success'] = true;
        return response()->json($results);
    }

    public function productCreate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name'        => new ValidProductName,
            'price'       => new ValidProductPrice,
            'description' => new ValidProductDescription,
            'category'    => new ValidProductCategory,
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();

            return response()->json($results);
        }

        $images = array();

        if (is_array($request->session()->get('tmp_images'))) {

            foreach ($request->session()->get('tmp_images') as $hash) {

                if (!Storage::exists("public/tmp_images/" . $hash)) {
                    $request->session()->put('tmp_images', array_diff($request->session()->get('tmp_images'), [$hash]));
                    continue;
                }

                array_push($images, $hash);
            }

        }

        $product = Product::create([
            'title'       => $request->name,
            'price'       => $request->price,
            'description' => $request->description,
            'status'      => "INVISIBLE",
            'category_id' => $request->category,
        ]);

        foreach ($images as $value) {
            ProductImage::create([
                'product_id' => $product->id,
                'name'       => $value,
            ]);

            Storage::move("public/tmp_images/" . $value, "public/products_images/" . $value);
        }

        $request->session()->forget('tmp_images');

        $results['success'] = true;

        return response()->json($results);
    }



    //
    //      PRODUCT EDIT SITES
    //

    public function productLoadCurrent(Request $request)
    {
        $results = array();

        $current_id = $request->session()->get("ADMIN_PRODUCT_EDIT_ID");

        $product = Product::where('id', $current_id)->first();

        if ($product == null) {
            $results['success'] = false;
            return response()->json($results);
        }

        $results['product'] = array();

        $results['product']['name']        = $product['title'];
        $results['product']['description'] = $product['description'];
        $results['product']['price']       = $product['price'];
        $results['product']['category_id'] = $product['category_id'];

        $results['product']['images'] = array();

        foreach ($product->images as $image) {

            array_push($results['product']['images'], $image->name);
        }

        $results['success'] = true;
        return response()->json($results);
    }

    public function productEdit(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name'        => new ValidProductName,
            'price'       => new ValidProductPrice,
            'description' => new ValidProductDescription,
            'category'    => new ValidProductCategory,
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();

            return response()->json($results);
        }

        $current_id = $request->session()->get("ADMIN_PRODUCT_EDIT_ID");

        $product = Product::where('id', $current_id)->first();

        if ($product == null) {
            $results['success'] = false;
            return response()->json($results);
        }

        $product->title       = $request->name;
        $product->price       = $request->price;
        $product->description = $request->description;
        $product->category_id = $request->category;

        $product->save();

        $results['success'] = true;

        return response()->json($results);
    }

    public function productEditImageList(Request $request)
    {

        $results = array();

        $current_id = $request->session()->get("ADMIN_PRODUCT_EDIT_ID");

        $product = Product::where('id', $current_id)->first();

        if ($product == null) {
            $results['success'] = false;
            return response()->json($results);
        }

        $results['images'] = array();

        foreach ($product->images as $image) {

            array_push($results['images'], $image->name);
        }

        $results['success'] = true;
        return response()->json($results);
    }

    public function productEditImageAdd(Request $request)
    {
        $results = array();

        if (!$request->hasFile('images')) {
            $results['success'] = false;
            return response()->json($results);
        }

        $validator = Validator::make($request->all(), [
            'images'   => 'required',
            'images.*' => 'mimes:png,jpeg',
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();
            return response()->json($results);
        }

        $current_id = $request->session()->get("ADMIN_PRODUCT_EDIT_ID");

        $product = Product::where('id', $current_id)->first();

        if ($product == null) {
            $results['success'] = false;
            return response()->json($results);
        }

        $files = $request->file('images');

        foreach ($files as $file) {
            $ar   = explode("/", $file->store('public/products_images'));
            $hash = end($ar);

            ProductImage::create([
                'product_id' => $product->id,
                'name'       => $hash,
            ]);
        }

        $results['success'] = true;

        return response()->json($results);
    }

    public function productEditImageRemove(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => new ValidProductImage,
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();

            return response()->json($results);
        }

        $current_id = $request->session()->get("ADMIN_PRODUCT_EDIT_ID");

        $product = Product::where('id', $current_id)->first();

        if ($product == null) {
            $results['success'] = false;
            return response()->json($results);
        }

        $productImage = ProductImage::where('name', $request->name)->first();

        if ($productImage == null) {
            $results['success'] = false;
            return response()->json($results);
        }

        if ($product->id != $productImage->product_id) {
            $results['success'] = false;
            return response()->json($results);
        }

        $productImage->delete();

        if (Storage::exists("public/products_images/" . $request->name)) {
            Storage::delete("public/products_images/" . $request->name);
        }

        $results['success'] = true;
        return response()->json($results);
    }



    //
    //      CATEGORIES MANAGER SITES
    //

    public function categoryAdd(Request $request)
    {
        
    }

    public function categoryRemove(Request $request)
    {
        
    }

    public function categoryEdit(Request $request)
    {
        
    }

    public function categoryChangeOrder(Request $request)
    {
        
    }


}
