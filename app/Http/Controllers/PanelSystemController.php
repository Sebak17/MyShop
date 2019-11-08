<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Product;
use App\Rules\ValidAddress;
use App\Rules\ValidCity;
use App\Rules\ValidDistrict;
use App\Rules\ValidFirstName;
use App\Rules\ValidID;
use App\Rules\ValidPassword;
use App\Rules\ValidPhoneNumber;
use App\Rules\ValidSurName;
use App\Rules\ValidZipCode;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Session;

class PanelSystemController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    //
    //      PRODUCTS SYSTEM
    //

    public function addProductToShoppingCart(Request $request)
    {
        $results = array();

        if (!$request->session()->exists('CURRECT_PRODUCT_PAGE')) {
            $results['success'] = false;
            return response()->json($results);
        }

        $product_id = $request->session()->get('CURRECT_PRODUCT_PAGE');

        $product = Product::where('id', $product_id)->first();

        if ($product == null) {
            $results['success'] = false;
            return response()->json($results);
        }

        $shoppingCartData = $request->session()->get('SHOPPINGCART_DATA');

        if ($shoppingCartData == null) {
            $shoppingCartData = array();
        }

        if (isset($shoppingCartData[$product->id])) {
            $shoppingCartData[$product->id] = $shoppingCartData[$product->id] + 1;
        } else {
            $shoppingCartData[$product->id] = 1;
        }

        $request->session()->put('SHOPPINGCART_DATA', $shoppingCartData);
        $request->session()->save();

        $results['success'] = true;
        return response()->json($results);
    }

    public function loadShoppingCartProducts(Request $request)
    {
        $results = array();

        $results['products'] = array();

        foreach ($request->session()->get('SHOPPINGCART_DATA', []) as $key => $value) {

            $product = Product::where('id', $key)->first();

            if ($product == null) {
                continue;
            }

            $data = array();

            $data['id']     = $key;
            $data['name']   = $product->title;
            $data['price']  = $product->price;
            $data['amount'] = $value;

            array_push($results['products'], $data);
        }

        $results['success'] = true;
        return response()->json($results);
    }

    public function updateShoppingCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'products'          => 'required|array',
            'products.*.id'     => new ValidID,
            'products.*.amount' => 'required|integer|between:1,100',
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();
            return response()->json($results);
        }

        $shoppingCartData = $request->session()->get('SHOPPINGCART_DATA');

        if ($shoppingCartData == null) {
            $shoppingCartData = array();
        }

        foreach ($request->products as $value) {
            $product = Product::where('id', $value['id'])->first();

            if ($product == null) {
                continue;
            }

            $shoppingCartData[$value['id']] = $value['amount'];
        }

        $request->session()->put('SHOPPINGCART_DATA', $shoppingCartData);
        $request->session()->save();

        $results['success'] = true;
        return response()->json($results);
    }

    public function confirmShoppingCart(Request $request)
    {
        $results = array();

        if (!$request->session()->exists('SHOPPINGCART_DATA')) {
            $results['success'] = false;
            return response()->json($results);
        }

        $shoppingCartData = $request->session()->get('SHOPPINGCART_DATA');

        if (empty($shoppingCartData)) {
            $results['success'] = false;
            return response()->json($results);
        }

        $newShoppingCartData = array();

        foreach ($shoppingCartData as $key => $value) {

            $product = Product::where('id', $key)->first();

            if($product == null)
                continue;

            if(!is_int($value) || $value <= 0 || $value > 100 )
                continue;

            $newShoppingCartData[$key] = $value;
        }

        $request->session()->put('SHOPPINGCART_STATUS', "INFORMATION");
        $request->session()->put('SHOPPINGCART_DATA', $newShoppingCartData);
        $request->session()->save();


        $results['url']     = route('shoppingCartInformation');
        $results['success'] = true;
        return response()->json($results);
    }

    //
    //      USER DATA SETTINGS
    //

    public function changeDataPersonal(Request $request)
    {
        $results = array();

        $validator = Validator::make($request->all(), [
            'fname' => new ValidFirstName,
            'sname' => new ValidSurName,
            'phone' => new ValidPhoneNumber,
        ]);

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();

            return response()->json($results);
        }

        $user = Auth::user();

        $user->personal->firstname   = $request->fname;
        $user->personal->surname     = $request->sname;
        $user->personal->phoneNumber = $request->phone;

        $user->push();

        $results['success'] = true;
        return response()->json($results);
    }

    public function changeDataLocation(Request $request)
    {
        $results = array();

        $validator = Validator::make($request->all(), [
            'district' => new ValidDistrict,
            'city'     => new ValidCity,
            'zipcode'  => new ValidZipCode,
            'address'  => new ValidAddress,
        ]);

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();

            return response()->json($results);
        }

        $user = Auth::user();

        $user->location->district = $request->district;
        $user->location->city     = $request->city;
        $user->location->zipcode  = $request->zipcode;
        $user->location->address  = $request->address;

        $user->push();

        $results['success'] = true;
        return response()->json($results);
    }

    public function changePassword(Request $request)
    {
        $results = array();

        $validator = Validator::make($request->all(), [
            'password_old' => new ValidPassword,
            'password_new' => new ValidPassword,
        ]);

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();

            return response()->json($results);
        }

        if ($request->password_old == $request->password_new) {
            $results['success'] = false;

            $results['msg'] = "Hasło są identyczne!";

            return response()->json($results);
        }

        $user = Auth::user();

        if (!Hash::check($request->password_old, $user->password)) {
            $results['success'] = false;

            $results['msg'] = "Hasło jest niepoprawne!";

            return response()->json($results);
        }

        $user->password = bcrypt($request->password_new);
        $user->push();

        Auth::logout();

        $results['success'] = true;
        return response()->json($results);
    }

}
