<?php

namespace App\Http\Controllers\AdminSystem;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\WarehouseItem;
use App\Rules\ValidProductName_NR;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WarehouseController extends Controller
{

    function list(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'      => new ValidProductName_NR,
            'minAmount' => 'numeric|gte:0',
            'maxAmount' => 'numeric',
        ]);

        $results = array();

        $useParams = false;

        if (!$validator->fails()) {
            $useParams = true;
        } else {
            echo "FAIL :( ";
        }

        $list = array();

        $items = Product::get();

        $i = 0;

        foreach ($items as $prod) {

            $amount = WarehouseItem::where('product_id', $prod->id)->count();

            if ($useParams) {

                if ($request->name != "" && !preg_match("/(" . $request->name . ")/i", $prod['title'])) {
                    continue;
                }

                if ($request->minAmount != "" && $amount < $request->minAmount) {
                    continue;
                }

                if ($request->maxAmount != "" && $amount > $request->maxAmount) {
                    continue;
                }

            }

            $list[$i] = array();

            $list[$i]['id']     = $prod['id'];
            $list[$i]['name']   = $prod['title'];
            $list[$i]['amount'] = WarehouseItem::where('product_id', $prod->id)->count();

            $list[$i]['url'] = route('admin_warehouseItemPage', $prod->id);

            $i++;
        }

        $results['success'] = true;
        $results['list']    = $list;

        return response()->json($results);
    }

}
