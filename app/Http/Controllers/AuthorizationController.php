<?php

namespace App\Http\Controllers;

use App\Helpers\Security;
use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Mail\AuthActiveAccountMail;
use App\Mail\AuthResetPasswordMail;
use App\Mail\AuthCreateAccountMail;
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
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class AuthorizationController extends Controller
{
    use ThrottlesLogins;
    use AuthenticatesUsers;

    protected $maxAttempts  = 5;
    protected $decayMinutes = 1;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:admin')->except('logout');
    }

    public function loginPage()
    {
        return view('auth.login');
    }

    public function registerPage()
    {
        return view('auth.register');
    }

    public function activeAccountPage()
    {
        return view('auth.active_account');
    }

    public function activeAccountCheckPage($hash)
    {

        if (!Security::checkHash($hash)) {
            return redirect()->route('activeAccountPage');
        }

        $user_info = UserInfo::where('activationHash', '=', $hash)->first();

        if ($user_info == null || !$user_info->exists()) {
            return view('auth.active_account_check')->with('error', 'Kod aktywacyjny jest nieprawidłowy!');
        }

        $user = $user_info->user;

        if ($user->active != 0) {
            return view('auth.active_account_check')->with('error', 'Konto jest już aktywne!');
        }

        $user->active                   = 1;
        $user->info->activationHash     = null;
        $user->info->activationMailTime = null;
        $user->push();

        UserHelper::addToHistory(
            $user,
            "AUTH",
            "Konto aktywowane przez użytkownika",
        );

        return view('auth.active_account_check')->with('success', true);
    }

    public function resetPasswordPage()
    {
        return view('auth.password_reset');
    }

    public function resetPasswordCheckPage(Request $request, $hash)
    {

        if (!Security::checkHash($hash)) {
            return redirect()->route('resetPasswordPage');
        }

        $user_info = UserInfo::where('passwordResetHash', '=', $hash)->first();

        if ($user_info == null || !$user_info->exists()) {
            return view('auth.active_account_check');
        }

        $user = $user_info->user;

        $request->session()->put('passResetHash', $hash);

        return view('auth.password_reset_check')->with('success', true);
    }

    public function signIn(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'grecaptcha' => ['required', new ValidReCaptcha],
            'email'      => new ValidEMail,
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

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            if (auth()->user()->active == 0) {
                UserHelper::addToHistory(
                    auth()->user(),
                    "AUTH",
                    "Logowanie do konta... KONTO NIEAKTYWNE",
                );

                auth()->logout();

                $results['success'] = false;
                $results['msg']     = "Konto nie jest aktywowane!";
            } else

            if (auth()->user()->ban != null) {
                UserHelper::addToHistory(
                    auth()->user(),
                    "AUTH",
                    "Logowanie do konta... KONTO ZABLOKOWANE",
                );

                auth()->logout();

                $results['success'] = false;
                $results['msg']     = "Konto jest zablokowane!";
            } else {
                $results['success'] = true;

                UserHelper::addToHistory(
                    auth()->user(),
                    "AUTH",
                    "Logowanie do konta... SUKCES",
                );
            }

        } else {
            $this->incrementLoginAttempts($request);

            $results['success'] = false;
            $results['msg']     = "Dane logowania są niepoprawne!";
        }

        return response()->json($results);
    }

    public function signUp(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'grecaptcha' => ['required', new ValidReCaptcha],
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
            'password' => bcrypt($request->pass),
            'hash'     => $hash_1,
        ]);
        $user->save();

        UserLocation::create([
            'user_id'  => $user->id,
            'district' => $request->district,
            'city'     => $request->city,
            'zipcode'  => $request->zipcode,
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

        UserHelper::addToHistory(
            $user,
            "AUTH",
            "Stworzono konto",
        );

        $subject = 'Dziękujemy za rejestracę w naszym serwisie';

        Mail::to($user->email)->send(new AuthCreateAccountMail($user, $subject));

        $results['success'] = true;
        $results['msg']     = "Potwierdź swoje konta na email'u!";
        return response()->json($results);
    }

    public function activateAccountMail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'grecaptcha' => ['required', new ValidReCaptcha],
            'email'      => new ValidEMail,
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();

            return response()->json($results);
        }

        $user = User::where('email', '=', $request->email)->first();

        if ($user == null || !$user->exists()) {
            // ERROR - EMAIL NOT FOUND
            $results['success'] = true;
            return response()->json($results);
        }

        if ($user->active != 0) {
            // ERROR - IS ACTIVE
            $results['success'] = true;
            return response()->json($results);
        }

        if ($user->info->activationMailTime > strtotime("now")) {
            // ERROR - TIME
            $results['success'] = true;
            return response()->json($results);
        }

        $hash = Security::generateChecksum(rand(0, 99999), time(), $request->email, $user->district, $user->city, $user->zipcode, $user->address, $user->firstname, $user->surname, $user->phone);

        $user->info->activationHash     = $hash;
        $user->info->activationMailTime = strtotime("+5 minutes");
        $user->push();


        UserHelper::addToHistory(
            $user,
            "AUTH",
            "Wysłano maila aktywującego",
        );

        $subject = 'Aktywacja konta';

        Mail::to($user->email)->send(new AuthActiveAccountMail($user, $subject));

        $results['success'] = true;
        return response()->json($results);
    }

    public function resetPasswordMail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'grecaptcha' => ['required', new ValidReCaptcha],
            'email'      => new ValidEMail,
            'phone'      => new ValidPhoneNumber,
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();

            return response()->json($results);
        }

        $user = User::where('email', '=', $request->email)->first();

        if ($user == null || !$user->exists()) {
            $results['success'] = true;
            // $results['debug']   = "EMAIL NOT FOUND";
            return response()->json($results);
        }

        if ($user->personal->phoneNumber != $request->phone) {
            $results['success'] = true;
            // $results['debug']   = "PHONE INCORRECT";
            return response()->json($results);
        }

        if ($user->info->passwordResetMailTime > strtotime("now")) {
            $results['success'] = true;
            // $results['debug']   = "TIME NOT REMAIN";
            return response()->json($results);
        }

        $hash = Security::generateChecksum(rand(0, 99999), time(), "QWEE", $user->surname, $request->email, $user->district, $user->city, $user->zipcode, $user->phone, $user->address, $user->firstname);

        $user->info->passwordResetHash     = $hash;
        $user->info->passwordResetMailTime = strtotime("+5 minutes");
        $user->push();

        UserHelper::addToHistory(
            $user,
            "AUTH",
            "Wysłano maila resetującego hasło",
        );

        $subject = 'Resetowanie hasła';

        Mail::to($user->email)->send(new AuthResetPasswordMail($user, $subject));

        $results['success'] = true;
        return response()->json($results);
    }

    public function resetPasswordChange(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'grecaptcha' => ['required', new ValidReCaptcha],
            'password'   => new ValidPassword,
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();

            return response()->json($results);
        }

        if (!$request->session()->exists('passResetHash')) {
            $results['success'] = false;
            $results['msg']     = "Kod autoryzacji jest nieprawidłowy!";
            return response()->json($results);
        }

        $hash = $request->session()->get('passResetHash');

        $user_info = UserInfo::where('passwordResetHash', '=', $hash)->first();

        if ($user_info == null || !$user_info->exists()) {
            $results['success'] = false;
            $results['msg']     = "Kod autoryzacji jest nieprawidłowy!";
            return response()->json($results);
        }

        $user = $user_info->user;

        $user->password = bcrypt($request->password);
        $user->info->passwordResetHash = null;
        $user->info->passwordResetMailTime = null;
        $user->push();

        UserHelper::addToHistory(
            $user,
            "AUTH",
            "Zmiana hasła przez odzysk konta",
        );

        $results['success'] = true;
        return response()->json($results);
    }

}
