<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Product;
use App\Rules\ValidAddress;
use App\Rules\ValidCity;
use App\Rules\ValidDistrict;
use App\Rules\ValidFirstName;
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

    public function addProductToBasket(Request $request)
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

        $basketData = $request->session()->get('BASKET_DATA');

        if ($basketData == null) {
            $basketData = array();
        }

        if (isset($basketData[$product->id])) {
            $basketData[$product->id] = $basketData[$product->id] + 1;
        } else {
            $basketData[$product->id] = 1;
        }

        $request->session()->put('BASKET_DATA', $basketData);
        $request->session()->save();

        $results['success'] = true;
        return response()->json($results);
    }

    public function loadBasketProducts(Request $request)
    {
        $results = array();

        $results['products'] = array();

        foreach ($request->session()->get('BASKET_DATA', []) as $key => $value) {

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

    public function zzz(Request $request)
    {
        dd($request->session()->all());
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
