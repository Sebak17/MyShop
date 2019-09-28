<?php

namespace App\Http\Controllers;

use App\Helpers\Security;
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
use App\User;
use App\UserInfo;
use App\UserLocation;
use App\UserPersonal;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthorizationController extends Controller
{

    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function loginPage()
    {
        return view('auth.login');
    }

    public function registerPage()
    {
        return view('auth.register');
    }

    public function signIn(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'grecaptcha' => new ValidReCaptcha,
            'email'      => new ValidEMail,
            'password'   => new ValidPassword,
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();

            return response()->json($results);
        }

        $results = array();

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $results['success'] = true;
        } else {
            $results['success'] = false;
            $results['msg'] = "Dane logowania są niepoprawne!";
        }

        return response()->json($results);
    }

    public function signUp(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'grecaptcha' => new ValidReCaptcha,
            'email'      => new ValidEMail,
            'pass'       => new ValidPassword,
            'firstname'  => new ValidFirstName,
            'surname'    => new ValidSurName,
            'phone'      => new ValidPhoneNumber,
            'district'   => new ValidDistrict,
            'city'       => new ValidCity,
            'zipcode'    => new ValidZipCode,
            'address'    => new ValidAddress,
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();

            return response()->json($results);
        }

        if (User::where('email', '=', $request->email)->exists()) {
            $results['success'] = false;

            $results['msg'] = "Taki email już jest zarejestrowany!";
            return response()->json($results);
        }

        $hash_1 = Security::generateChecksum(rand(0, 99999), time(), $request->email, $request->password, $request->district, $request->city, $request->zipcode, $request->address, $request->firstname, $request->surname, $request->phone);

        $hash_2 = Security::generateChecksum(time(), rand(0, 99999), $request->password, $request->surname, $request->city, $request->zipcode, $request->phone, $request->email, $request->district, $request->address, $request->firstname);

        $user = User::create([
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'hash'     => $hash_1,
        ]);
        $user->save();

        UserLocation::create([
            'user_id'  => $user->id,
            'district' => $request->district,
            'city'     => $request->city,
            'zipcode' => $request->zipcode,
            'address'  => $request->address,
        ]);

        UserPersonal::create([
            'user_id'     => $user->id,
            'firstname'   => $request->firstname,
            'surname'     => $request->surname,
            'phoneNumber' => $request->phone,
        ]);

        UserInfo::create([
            'user_id'        => $user->id,
            'firstIP'        => $_SERVER['REMOTE_ADDR'],
            'activationHash' => $hash_2,
        ]);

        $results['success'] = true;
        $results['msg']     = "Potwierdź swoje konta na email'u!";
        return response()->json($results);
    }

}
