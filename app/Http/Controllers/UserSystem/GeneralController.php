<?php

namespace App\Http\Controllers\UserSystem;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Rules\ValidID;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GeneralController extends Controller
{

    public function changeFavoriteStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => new ValidID,
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();
            return response()->json($results);
        }

        $user = Auth::user();

        $favs = json_decode($user->getFavorites()->products, true);

        if (in_array($request->id, $favs)) {

            if (($key = array_search($request->id, $favs)) !== false) {
                unset($favs[$key]);
            }

            $favs = array_values($favs);

            $user->getFavorites()->products = json_encode($favs);
            $user->push();

            $results['status'] = false;

            $results['success'] = true;
            return response()->json($results);
        }

        $product = Product::where('id', $request->id)->first();

        if ($product == null) {
            $results['success'] = false;
            return response()->json($results);
        }

        array_push($favs, $request->id);
        $user->getFavorites()->products = json_encode($favs);
        $user->push();

        $results['status'] = true;

        $results['success'] = true;
        return response()->json($results);
    }

}
