<?php

namespace App\Http\Controllers\AdminSystem;

use App\Ban;
use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Rules\ValidAddress;
use App\Rules\ValidBanDescription;
use App\Rules\ValidCity;
use App\Rules\ValidDistrict;
use App\Rules\ValidFirstName;
use App\Rules\ValidID;
use App\Rules\ValidPhoneNumber;
use App\Rules\ValidSurName;
use App\Rules\ValidZipCode;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function ban(Request $request)
    {
        $results = array();

        $validator = Validator::make($request->all(), [
            'id'     => new ValidID,
            'reason' => new ValidBanDescription,
        ]);

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();
            return response()->json($results);
        }

        $user = User::where('id', $request->id)->first();

        if ($user == null) {
            $results['success'] = false;

            $results['msg'] = "Nie znaleziono takiego użytkownika!";
            return response()->json($results);
        }

        if ($user->ban != null) {
            $results['success'] = false;

            $results['msg'] = "Użytkownik jest już zablokowany!";
            return response()->json($results);
        }

        Ban::create([
            'user_id' => $user->id,
            'reason'  => $request->reason,
        ]);

        UserHelper::addToHistory($user, 'BAN', "Konto zablokowane z powodu: " . $request->reason);

        $results['success'] = true;

        return response()->json($results);
    }

    public function unban(Request $request)
    {
        $results = array();

        $validator = Validator::make($request->all(), [
            'id' => new ValidID,
        ]);

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();
            return response()->json($results);
        }

        $user = User::where('id', $request->id)->first();

        if ($user == null) {
            $results['success'] = false;

            $results['msg'] = "Nie znaleziono takiego użytkownika!";
            return response()->json($results);
        }

        if ($user->ban == null) {
            $results['success'] = false;

            $results['msg'] = "Użytkownik nie jest zablokowany!";
            return response()->json($results);
        }

        $user->ban->delete();

        UserHelper::addToHistory($user, 'BAN', "Konto odblokowane");

        $results['success'] = true;

        return response()->json($results);
    }

    public function changePersonal(Request $request)
    {
        $results = array();

        $validator = Validator::make($request->all(), [
            'id'    => new ValidID,
            'fname' => new ValidFirstName,
            'sname' => new ValidSurName,
            'phone' => new ValidPhoneNumber,
        ]);

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();

            return response()->json($results);
        }

        $user = User::where('id', $request->id)->first();

        if ($user == null) {
            $results['success'] = false;

            $results['msg'] = "Nie znaleziono takiego użytkownika!";
            return response()->json($results);
        }

        $user->personal->firstname   = $request->fname;
        $user->personal->surname     = $request->sname;
        $user->personal->phoneNumber = $request->phone;

        $user->push();

        UserHelper::addToHistory(
            $user,
            "AC_CHANGE",
            "Zmiana danych osobowych przez administratora",
        );

        $results['success'] = true;
        return response()->json($results);
    }

    public function changeLocation(Request $request)
    {
        $results = array();

        $validator = Validator::make($request->all(), [
            'id'       => new ValidID,
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

        $user = User::where('id', $request->id)->first();

        if ($user == null) {
            $results['success'] = false;

            $results['msg'] = "Nie znaleziono takiego użytkownika!";
            return response()->json($results);
        }

        $user->location->district = $request->district;
        $user->location->city     = $request->city;
        $user->location->zipcode  = $request->zipcode;
        $user->location->address  = $request->address;

        $user->push();

        UserHelper::addToHistory(
            $user,
            "AC_CHANGE",
            "Zmiana lokalizacji przez administratora",
        );

        $results['success'] = true;
        return response()->json($results);
    }

}
