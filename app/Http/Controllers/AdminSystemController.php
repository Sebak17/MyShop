<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminSystemController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function categoryList()
    {

        $results = array();

        $list1 = array();

        $categories = Category::where('active', 1)->get();

        $i = 0;

        foreach ($categories as $cat) {

            $list1[$i] = array();

            $list1[$i]['id']    = $cat['id'];
            $list1[$i]['name']  = $cat['name'];
            $list1[$i]['order'] = $cat['orderID'];
            $list1[$i]['icon']  = $cat['icon'];

            if ($cat['overcategory'] != 0) {
                $list1[$i]['overcategory'] = $cat['overcategory'];
            }

            $i++;
        }

        $results['success'] = true;
        $results['list1']   = $list1;

        return response()->json($results);

    }

    public function productAddImage(Request $request)
    {

        $results = array();

        if (!$request->hasFile('images')) {
            $results['success'] = false;
            return response()->json($results);
        }

        $validator = Validator::make($request->all(), [
            'images'   => 'required',
            'images.*' => 'mimes:png,jpeg',
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();
            return response()->json($results);
        }

        $files = $request->file('images');

        $results['images'] = array();

        foreach ($files as $file) {
            $ar   = explode("/", $file->store('public/tmp_images'));
            $hash = end($ar);
            array_push($results['images'], $hash);

            $request->session()->push('tmp.images', $hash);
        }

        $results['success'] = true;

        return response()->json($results);
    }

    public function productLoadOldImages(Request $request)
    {
        $results = array();

        if (!is_array($request->session()->get('tmp.images'))) {
            $results['success'] = false;
            return response()->json($results);
        }

        $results['images'] = array();

        foreach ($request->session()->get('tmp.images') as $hash) {

            if (!Storage::exists("public/tmp_images/" . $hash)) {
            	echo "? " . $hash . "\n";
            	$request->session()->put('user.teams', array_diff($request->session()->get('user.teams'), ['marketing']));
                $request->session()->pull('tmp.images' . $hash);
                continue;
            }

            array_push($results['images'], $hash);
        }

        $results['success'] = true;
        return response()->json($results);
    }

}
