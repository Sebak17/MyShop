<?php

namespace App\Http\Controllers;

use Hash;
use App\Http\Controllers\Controller;
use App\Rules\ValidAddress;
use App\Rules\ValidCity;
use App\Rules\ValidDistrict;
use App\Rules\ValidEMail;
use App\Rules\ValidFirstName;
use App\Rules\ValidPassword;
use App\Rules\ValidPhoneNumber;
use App\Rules\ValidReCaptcha;
use App\Rules\ValidSurName;
use App\Rules\ValidZipCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PanelSystemController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

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
        $user->location->city = $request->city;
        $user->location->zipcode = $request->zipcode;
        $user->location->address = $request->address;

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

        if($request->password_old == $request->password_new) {
        	$results['success'] = false;

            $results['msg'] = "Hasło są identyczne!";

            return response()->json($results);
        }

        $user = Auth::user();

        if(!Hash::check($request->password_old, $user->password)) {
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
