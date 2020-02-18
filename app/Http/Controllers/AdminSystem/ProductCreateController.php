<?php

namespace App\Http\Controllers\AdminSystem;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Rules\ValidProductCategory;
use App\Rules\ValidProductDescription;
use App\Rules\ValidProductImage;
use App\Rules\ValidProductName;
use App\Rules\ValidProductParams;
use App\Rules\ValidProductPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductCreateController extends Controller
{

    public function imageUpload(Request $request)
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
            $results['msg']     = $validator->errors()->first();
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

    public function imageRemove(Request $request)
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
            $results['msg']     = "Plik nie istnieje!";
            return response()->json($results);
        }

        Storage::delete("public/tmp_images/" . $request->name);
        $request->session()->put('tmp_images', array_diff($request->session()->get('tmp_images'), [$request->name]));

        $results['success'] = true;
        return response()->json($results);
    }

    public function loadOldImages(Request $request)
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

    public function create(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name'        => new ValidProductName,
            'price'       => new ValidProductPrice,
            'description' => new ValidProductDescription,
            'category'    => new ValidProductCategory,
            'params'      => new ValidProductParams,
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();

            return response()->json($results);
        }

        $category = Category::where('id', $request->category)->first();

        if ($category == null) {
            $results['success'] = false;
            $results['msg']     = "Podana kategoria nie istnieje!";
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
            'category_id' => $category->id,
            'params'      => $request->params,
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

}
