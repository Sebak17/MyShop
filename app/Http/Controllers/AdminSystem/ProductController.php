<?php

namespace App\Http\Controllers\AdminSystem;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Rules\ValidID;
use App\Rules\ValidProductDescription;
use App\Rules\ValidProductImage;
use App\Rules\ValidProductName;
use App\Rules\ValidProductParams;
use App\Rules\ValidProductPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function promotionAdd(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id'        => new ValidID,
            'price'       => new ValidProductPrice,
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
            $results['msg']     = "Nie znaleziono produktu!";
            return response()->json($results);
        }

        if($product->priceNormal <= $request->price) {
        	$results['success'] = false;
            $results['msg']     = "Cena promocyjna nie może być wyższa od podstawowej!";
            return response()->json($results);
        }

        if($product->priceCurrent != $product->priceNormal) {
        	$results['success'] = false;
            $results['msg']     = "Promocja już trwa!";
            return response()->json($results);
        }
        
        $product->priceCurrent = $request->price;
        $product->save();


        $results['success'] = true;
        return response()->json($results);
    }

    public function promotionRemove(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id'        => new ValidID,
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
            $results['msg']     = "Nie znaleziono produktu!";
            return response()->json($results);
        }

        if($product->priceCurrent == $product->priceNormal) {
        	$results['success'] = false;
            $results['msg']     = "Promocja już trwa!";
            return response()->json($results);
        }
        
        $product->priceCurrent = $product->priceNormal;
        $product->save();


        $results['success'] = true;
        return response()->json($results);
    }

}
