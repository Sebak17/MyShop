<?php

namespace App\Http\Controllers\AdminSystem;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Rules\ValidID;
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

class ProductEditController extends Controller
{

    public function loadCurrentData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => new ValidID,
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;
            $results['msg']     = $validator->errors()->first();
            return response()->json($results);
        }

        $product = Product::where('id', $request->id)->first();

        if ($product == null) {
            $results['success'] = false;
            return response()->json($results);
        }

        $results['product'] = array();

        $results['product']['category_id'] = $product['category_id'];
        $results['product']['status']      = $product['status'];
        $results['product']['params']      = $product['params'];

        $results['product']['images'] = array();

        foreach ($product->images as $image) {

            array_push($results['product']['images'], $image->name);
        }

        $results['success'] = true;
        return response()->json($results);
    }

    public function edit(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id'          => new ValidID,
            'name'        => new ValidProductName,
            'price'       => new ValidProductPrice,
            'description' => new ValidProductDescription,
            'category'    => new ValidProductCategory,
            'status'      => "required|in:INVISIBLE,ACTIVE,INACTIVE",
            'params'      => new ValidProductParams,
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();

            return response()->json($results);
        }

        $product = Product::where('id', $request->id)->first();

        if ($product == null) {
            $results['success'] = false;
            return response()->json($results);
        }

        $product->title        = $request->name;
        $product->priceCurrent = $request->price;
        $product->priceNormal  = $request->price;
        $product->description  = $request->description;
        $product->category_id  = $request->category;
        $product->status       = $request->status;
        $product->params       = $request->params;

        $product->save();

        $results['success'] = true;

        return response()->json($results);
    }

    public function imageList(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => new ValidID,
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;
            $results['msg']     = $validator->errors()->first();
            return response()->json($results);
        }

        $product = Product::where('id', $request->id)->first();

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

    public function imageAdd(Request $request)
    {
        $results = array();

        if (!$request->hasFile('images')) {
            $results['success'] = false;
            return response()->json($results);
        }

        $validator = Validator::make($request->all(), [
            'id'       => new ValidID,
            'images'   => 'required',
            'images.*' => 'mimes:png,jpeg',
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();
            return response()->json($results);
        }

        $product = Product::where('id', $request->id)->first();

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

    public function imageRemove(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'   => new ValidID,
            'name' => new ValidProductImage,
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();

            return response()->json($results);
        }

        $product = Product::where('id', $request->id)->first();

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

}
