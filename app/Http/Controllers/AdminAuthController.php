<?php

namespace App\Http\Controllers;

use App\Rules\ValidLogin;
use App\Rules\ValidPassword;
use App\Rules\ValidReCaptcha;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminAuthController extends Controller
{
    use ThrottlesLogins;
    use AuthenticatesUsers;

    protected $guard = 'admin';

    protected $maxAttempts  = 2;
    protected $decayMinutes = 1;

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

        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            $results['msg'] = "Za dużo prób logowania! Poczekaj " . $this->limiter()->availableIn($this->throttleKey($request)) . " sekund!";
            return response()->json($results);
        }

        if (Auth::guard('admin')->attempt(['login' => $request->login, 'password' => $request->password])) {
            $results['success'] = true;
        } else {
            $this->incrementLoginAttempts($request);

            $results['success'] = false;
            $results['msg']     = "Dane logowania są niepoprawne!";
        }

        return response()->json($results);
    }

}
