<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeSystemController extends Controller
{

    public function loadCategories(Request $request)
    {

        $results = array();

        $overcategory_id = 0;

        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
        ]);

        $results = array();

        if (!$validator->fails()) {
            $overcategory_id = $request->id;
        }

        $categories = Category::where('active', 1)->where('visible', 1)->where('overcategory', $overcategory_id)->orderBy('orderID', 'ASC')->get();

        $list = array();

        foreach ($categories as $category) {
            $ar = array();

            $ar['id']   = $category->id;
            $ar['name'] = $category->name;
            $ar['icon'] = $category->icon;

            array_push($list, $ar);
        }

        $overcategory = Category::where('active', 1)->where('visible', 1)->where('id', $overcategory_id)->first();

        if ($overcategory != null) {

            $results['currentCategory'] = $overcategory->name;

            $overcategory = Category::where('active', 1)->where('visible', 1)->where('id', $overcategory->overcategory)->first();

            if ($overcategory != null) {
                $results['overcategory'] = [
                    'id'   => $overcategory->id,
                    'name' => $overcategory->name,
                ];
            } else {
                $results['overcategory'] = [
                    'id'   => 0,
                    'name' => 'Wszystkich kategorii',
                ];
            }

        } else {
            $results['currentCategory'] = "Wszystkie kategorie";

            $results['overcategory'] = [
                'id'   => 0,
                'name' => 'Wszystkich kategorii',
            ];
        }

        $results['categories'] = $list;
        $results['success']    = true;

        return response()->json($results);

    }

}
