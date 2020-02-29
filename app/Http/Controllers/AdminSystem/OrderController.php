<?php

namespace App\Http\Controllers\AdminSystem;

use App\Helpers\OrderHelper;
use App\Helpers\WarehouseHelper;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\WarehouseItem;
use App\Models\OrderHistory;
use App\Rules\ValidAddress;
use App\Rules\ValidCity;
use App\Rules\ValidDistrict;
use App\Rules\ValidID;
use App\Rules\ValidLockerName;
use App\Rules\ValidOrderStatus;
use App\Rules\ValidZipCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    public function changeStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'     => new ValidID,
            'status' => new ValidOrderStatus,
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();
            return response()->json($results);
        }

        $order = Order::where('id', $request->id)->first();

        if ($order == null) {
            $results['success'] = false;

            $results['msg'] = "Nie znaleziono zamówienia!";
            return response()->json($results);
        }

        if($order->status == "CANCELED") {
            $results['success'] = false;

            $results['msg'] = "Nie możesz zmienić statusu gdy zamówienie jest anulowane!";
            return response()->json($results);
        }

        OrderHelper::changeOrderStatus($order, $request->status);

        $results['success'] = true;
        return response()->json($results);
    }

    public function changeDeliverLoc(Request $request)
    {
        $results = array();

        $validator = Validator::make($request->all(), [
            'id'           => new ValidID,
            'deliver.type' => "required|in:INPOST_LOCKER,COURIER",
        ]);

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();
            return response()->json($results);
        }

        $deliver_info = array();

        switch ($request->input('deliver.type')) {
            case 'INPOST_LOCKER':
                $validator = Validator::make($request->all(), [
                    'deliver.lockerName' => new ValidLockerName,
                ]);

                if ($validator->fails()) {
                    $results['success'] = false;

                    $results['msg'] = $validator->errors()->first();
                    return response()->json($results);
                }

                $deliver_info['lockerName'] = $request->input('deliver.lockerName');

                break;
            case 'COURIER':
                $validator = Validator::make($request->all(), [
                    'deliver.district' => new ValidDistrict,
                    'deliver.city'     => new ValidCity,
                    'deliver.zipcode'  => new ValidZipCode,
                    'deliver.address'  => new ValidAddress,
                ]);

                if ($validator->fails()) {
                    $results['success'] = false;

                    $results['msg'] = $validator->errors()->first();
                    return response()->json($results);
                }

                $deliver_info['district'] = $request->input('deliver.district');
                $deliver_info['city']     = $request->input('deliver.city');
                $deliver_info['zipcode']  = $request->input('deliver.zipcode');
                $deliver_info['address']  = $request->input('deliver.address');

                break;
        }

        if (empty($deliver_info)) {
            $results['success'] = false;
            $results['msg']     = "Wystąpił błąd z danymi do wysyłki!";
            return response()->json($results);
        }

        $order = Order::where('id', $request->id)->first();

        if ($order == null) {
            $results['success'] = false;
            $results['msg']     = "Wystapił bład z zamówieniem!";
            return response()->json($results);
        }

        if($order->status == "CANCELED") {
            $results['success'] = false;

            $results['msg'] = "Nie możesz zmienić adresu gdy zamówienie jest anulowane!";
            return response()->json($results);
        }

        $order->deliver_name = $request->input('deliver.type');
        $order->deliver_info = json_encode($deliver_info);
        $order->save();

        OrderHistory::create([
            'order_id' => $order->id,
            'data'     => 'Zmiana dostawy zamówienia',
        ]);

        $results['success'] = true;
        return response()->json($results);
    }

    public function changePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'            => new ValidID,
            'paymentMethod' => "required|in:PAYU,PAYPAL,PAYMENTCARD",
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();
            return response()->json($results);
        }

        $order = Order::where('id', $request->id)->first();

        if ($order == null) {
            $results['success'] = false;

            $results['msg'] = "Nie znaleziono zamówienia!";
            return response()->json($results);
        }

        if($order->status == "CANCELED") {
            $results['success'] = false;

            $results['msg'] = "Nie możesz zmienić płatności gdy zamówienie jest anulowane!";
            return response()->json($results);
        }

        OrderHistory::create([
            'order_id' => $order->id,
            'data'     => 'Zmiana płatności z ' . $order->payment . ' na ' . $request->paymentMethod,
        ]);

        $order->payment = $request->paymentMethod;
        $order->save();

        $results['success'] = true;
        return response()->json($results);
    }

    public function changeCost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'   => new ValidID,
            'cost' => "required|numeric|min:0|not_in:0",
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();
            return response()->json($results);
        }

        $order = Order::where('id', $request->id)->first();

        if ($order == null) {
            $results['success'] = false;

            $results['msg'] = "Nie znaleziono zamówienia!";
            return response()->json($results);
        }

        if($order->status == "CANCELED") {
            $results['success'] = false;

            $results['msg'] = "Nie możesz zmienić kosztu gdy zamówienie jest anulowane!";
            return response()->json($results);
        }

        OrderHistory::create([
            'order_id' => $order->id,
            'data'     => 'Zmiana kosztu zamówienia z ' . $order->cost . ' na ' . $request->cost,
        ]);

        $order->cost = $request->cost;
        $order->save();

        $results['success'] = true;
        return response()->json($results);
    }

    public function changeParcelID(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'       => new ValidID,
            'parcelID' => "required|string|min:10|max:120",
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();
            return response()->json($results);
        }

        $order = Order::where('id', $request->id)->first();

        if ($order == null) {
            $results['success'] = false;

            $results['msg'] = "Nie znaleziono zamówienia!";
            return response()->json($results);
        }

        if($order->status == "CANCELED") {
            $results['success'] = false;

            $results['msg'] = "Nie możesz zmienić numeru przesyłki gdy zamówienie jest anulowane!";
            return response()->json($results);
        }

        OrderHistory::create([
            'order_id' => $order->id,
            'data'     => 'Zmiana numeru przesyłki na ' . $request->parcelID,
        ]);

        $order->deliver_parcelID = $request->parcelID;
        $order->save();

        $results['success'] = true;
        return response()->json($results);
    }

    public function orderCancel(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'id'       => new ValidID,
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();
            return response()->json($results);
        }

        $order = Order::where('id', $request->id)->first();

        if ($order == null) {
            $results['success'] = false;

            $results['msg'] = "Nie znaleziono zamówienia!";
            return response()->json($results);
        }

        if($order->status == "CANCELED") {
            $results['success'] = false;

            $results['msg'] = "Zamówienie jest już anulowane!";
            return response()->json($results);
        }

        OrderHistory::create([
            'order_id' => $order->id,
            'data'     => 'Anulowanie zamówienia przez administratora',
        ]);

        $order->status = "CANCELED";
        $order->save();

        foreach ($order->products as $product) {
            $item = WarehouseItem::where('id', $product->warehouse_item_id)->first();
            
            if($item == null) {
                continue;
            }

            WarehouseHelper::addHistory($item, 'Anulowanie zamówienia #' . $order->id . ' oraz zwolnienie produktów!');
            WarehouseHelper::changeStatus($item, 'AVAILABLE');
        }

        $results['success'] = true;
        return response()->json($results);
    }

}
