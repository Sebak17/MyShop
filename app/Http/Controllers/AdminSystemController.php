<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

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

			$list1[$i]['id'] = $cat['id'];
			$list1[$i]['name'] = $cat['name'];
			$list1[$i]['order'] = $cat['orderID'];
			$list1[$i]['icon'] = $cat['icon'];
			if($cat['overcategory'] != 0)
				$list1[$i]['overcategory'] = $cat['overcategory'];

			$i++;
		}

		$results['success'] = true;
		$results['list1'] = $list1;

    	return response()->json($results);
    	
    }

}
