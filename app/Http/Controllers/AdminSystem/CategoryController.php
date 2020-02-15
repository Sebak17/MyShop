<?php

namespace App\Http\Controllers\AdminSystem;

use App\Category;
use App\Http\Controllers\Controller;
use App\Rules\ValidCategoryName;
use App\Rules\ValidIconFA;
use App\Rules\ValidID;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => new ValidCategoryName,
            'icon'  => new ValidIconFA,
            'ovcat' => new ValidID,
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;
            $results['msg']     = $validator->errors()->first();
            return response()->json($results);
        }

        $orderID = count(Category::where('overcategory', $request->ovcat)->get()) + 1;

        Category::create([
            'name'         => $request->name,
            'orderID'      => $orderID,
            'overcategory' => $request->ovcat,
            'active'       => 1,
            'visible'      => 1,
            'icon'         => $request->icon,
        ]);

        $results['success'] = true;
        return response()->json($results);
    }

    public function remove(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => new ValidID,
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;
            $results['msg']     = $validator->errors()->first();
            return response()->json($results);
        }

        $category = Category::where('id', $request->id)->first();

        if ($category == null) {
            $results['success'] = false;
            $request['msg']     = "Nie znaleziono produktu!";
            return response()->json($results);
        }

        $categories = Category::where('overcategory', $request->id)->get();

        foreach ($categories as $cat) {
            $cat->overcategory = -1;
            $cat->save();
        }

        $category->delete();

        $results['success'] = true;
        return response()->json($results);
    }

    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'   => new ValidID,
            'name' => new ValidCategoryName,
            'icon' => new ValidIconFA,
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;
            $results['msg']     = $validator->errors()->first();
            return response()->json($results);
        }

        $category = Category::where('id', $request->id)->first();

        if ($category == null) {
            $results['success'] = false;
            return response()->json($results);
        }

        $category->name = $request->name;
        $category->icon = $request->icon;
        $category->save();

        $results['success'] = true;
        return response()->json($results);
    }

    public function changeOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'newids'   => "required|array",
            'newids.*' => new ValidID,
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;
            $results['msg']     = $validator->errors()->first();
            return response()->json($results);
        }

        foreach ($request->newids as $id => $index) {

            $category = Category::where('id', $id)->first();

            if ($category == null) {
                continue;
            }

            $category->orderID = $index;
            $category->save();
        }

        $results['success'] = true;
        return response()->json($results);
    }

}
