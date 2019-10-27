<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\ProductImage;
use App\Rules\ValidProductCategory;
use App\Rules\ValidProductDescription;
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

    public function productAddImage(Request $request)
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

        $files = $request->file('images');

        foreach ($files as $file) {
            $ar   = explode("/", $file->store('public/tmp_images'));
            $hash = end($ar);

            $request->session()->push('tmp_images', $hash);
        }

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

    public function productLoadList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => new ValidProductName
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

            if($useParams) {

                if($request->name != "" && !preg_match("/(" . $request->name . ")/i", $prod['title']))
                    continue;
            }

            $list[$i] = array();

            $list[$i]['id']    = $prod['id'];
            $list[$i]['name']  = $prod['title'];

            $list[$i]['image1']  = $prod->images[0]->name;

            $i++;
        }

        $results['success'] = true;
        $results['list']   = $list;

        return response()->json($results);
    }

}
