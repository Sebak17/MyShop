<?php

namespace App\Http\Controllers\AdminSystem;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\WarehouseItem;
use App\Models\WarehouseItemHistory;
use App\Rules\ValidID;
use App\Rules\ValidProductName_NR;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\WarehouseHelper;

class WarehouseController extends Controller
{

    public function list(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'      => new ValidProductName_NR,
            'minAmount' => 'numeric|gte:0',
            'maxAmount' => 'numeric',
        ]);

        $results = array();

        $useParams = false;

        if (!$validator->fails()) {
            $useParams = true;
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

            $list[$i]['url'] = route('admin_warehouseProductPage', $prod->id);

            $i++;
        }

        $results['success'] = true;
        $results['list']    = $list;

        return response()->json($results);
    }

    public function itemsList(Request $request) {
        $validator = Validator::make($request->all(), [
            'id'      => new ValidID,
        ]);

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();
            return response()->json($results);
        }

        $product = Product::where('id', $request->id)->first();

        if($product == null) {
            $results['success'] = false;

            $results['msg'] = "Nie odnaleziono produktu!";
            return response()->json($results);
        }

        $results['items'] = array();

        foreach ($product->items as $item) {
            $d = array();

            $d['id'] = $item->id;

            $d['code'] = $item->code;
            $d['status'] = $item->status;

            $d['created_at'] = $item->created_at->format("Y-m-d H:i:s");

            array_push($results['items'], $d);
        }


        $results['success'] = true;
        return response()->json($results);
    }

    public function addItem(Request $request) {
        $validator = Validator::make($request->all(), [
            'id'      => new ValidID,
            'code'      => 'required|string|min:6|max:100',
            'status' => 'required|in:AVAILABLE,UNAVAILABLE,RESERVED,BOUGHT,INVISIBLE',
        ]);

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();
            return response()->json($results);
        }

        $code = str_replace(' ', '', $request->code);

        $item = WarehouseItem::where('code', $code)->first();

        if($item != null) {
            $results['success'] = false;

            $results['msg'] = "Towar z takim kodem już istnieje!";
            return response()->json($results);
        }

        $product = Product::where('id', $request->id)->first();

        if($product == null) {
            $results['success'] = false;

            $results['msg'] = "Nie odnaleziono produktu!";
            return response()->json($results);
        }

        $item = WarehouseItem::create([
            'product_id' => $request->id,
            'code' => $code,
            'status' => $request->status,
        ]);

        WarehouseItemHistory::create([
            'item_id' => $item->id,
            'data' => "Dodano towar do magazynu",
        ]);


        $results['success'] = true;
        return response()->json($results);
    }

    public function updateItem(Request $request) {
        $validator = Validator::make($request->all(), [
            'product_id'      => new ValidID,
            'item_id'      => new ValidID,
            'status' => 'required|in:AVAILABLE,UNAVAILABLE,RESERVED,SENT,INVISIBLE',
        ]);

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();
            return response()->json($results);
        }

        $item = WarehouseItem::where('id', $request->item_id)->where('product_id', $request->product_id)->first();

        if($item == null) {
            $results['success'] = false;

            $results['msg'] = "Nie odnaleziono towaru!";
            return response()->json($results);
        }

        WarehouseHelper::changeStatus($item, $request->status);

        $results['item'] = array();
        $results['item']['id'] = $item->id;
        $results['item']['status'] = $item->status;

        $results['success'] = true;
        return response()->json($results);
    }


    public function historyItem(Request $request) {
        $validator = Validator::make($request->all(), [
            'product_id'      => new ValidID,
            'item_id'      => new ValidID,
        ]);

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();
            return response()->json($results);
        }

        $item = WarehouseItem::where('id', $request->item_id)->where('product_id', $request->product_id)->first();

        if($item == null) {
            $results['success'] = false;

            $results['msg'] = "Nie odnaleziono towaru!";
            return response()->json($results);
        }

        $results['history'] = array();

        foreach($item->history as $his) {
            $o = array();
            $o['created_at'] = $his->created_at->format("Y-m-d H:i:s");
            $o['data'] = $his->data;

            array_push($results['history'], $o);
        }


        $results['success'] = true;
        return response()->json($results);
    }

}
