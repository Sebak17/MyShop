<?php

namespace App\Http\Controllers;

use App\Rules\ValidLogin;
use App\Rules\ValidPassword;
use App\Rules\ValidReCaptcha;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminAuthController extends Controller
{

    use AuthenticatesUsers;

    protected $guard = 'admin';

    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('guest:admin');
    }

    public function loginPage()
    {
        return view('admin.login');
    }

    public function signIn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'grecaptcha' => new ValidReCaptcha,
            'login'      => new ValidLogin,
            'password'   => new ValidPassword,
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();

            return response()->json($results);
        }

        if (Auth::guard('admin')->attempt(['login' => $request->login, 'password' => $request->password])) {
            $results['success'] = true;
        } else {
            $results['success'] = false;
            $results['msg']     = "Dane logowania są niepoprawne!";
        }

        return response()->json($results);
    }

}
