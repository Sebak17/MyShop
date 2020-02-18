<?php

namespace App\Http\Controllers\UserSystem;

use App\Helpers\OrderHelper;
use App\Http\Controllers\Controller;
use App\Mail\OrderCreateMail;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\OrderProduct;
use App\Models\Payment;
use App\Models\Product;
use App\Rules\ValidAddress;
use App\Rules\ValidCity;
use App\Rules\ValidDistrict;
use App\Rules\ValidFirstName;
use App\Rules\ValidID;
use App\Rules\ValidLockerName;
use App\Rules\ValidOrderNote;
use App\Rules\ValidPhoneNumber;
use App\Rules\ValidSurName;
use App\Rules\ValidZipCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Session;

class OrderController extends Controller
{

    public function addProductToShoppingCart(Request $request)
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

        $product = Product::where('id', $request->id)->where('status', 'IN_STOCK')->first();

        if ($product == null) {
            $results['success'] = false;
            return response()->json($results);
        }

        $shoppingCartData = $request->session()->get('SHOPPINGCART_DATA');

        if ($shoppingCartData == null) {
            $shoppingCartData = array();
        }

        if (isset($shoppingCartData[$product->id])) {

            if ($shoppingCartData[$product->id] < 100) {
                $shoppingCartData[$product->id] = $shoppingCartData[$product->id] + 1;
            }

        } else {
            $shoppingCartData[$product->id] = 1;
        }

        $request->session()->put('SHOPPINGCART_DATA', $shoppingCartData);
        $request->session()->save();

        $results['success'] = true;
        return response()->json($results);
    }

    public function loadShoppingCartProducts(Request $request)
    {
        $results = array();

        $results['products'] = array();

        foreach ($request->session()->get('SHOPPINGCART_DATA', []) as $key => $value) {

            $product = Product::where('id', $key)->where('status', 'IN_STOCK')->first();

            if ($product == null) {
                continue;
            }

            $data = array();

            $data['id']     = $key;
            $data['name']   = $product->title;
            $data['price']  = $product->price;
            $data['amount'] = $value;

            array_push($results['products'], $data);
        }

        $results['success'] = true;
        return response()->json($results);
    }

    public function updateShoppingCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'products'          => 'array',
            'products.*.id'     => new ValidID,
            'products.*.amount' => 'required|integer|between:1,100',
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();
            return response()->json($results);
        }

        $shoppingCartData = $request->session()->get('SHOPPINGCART_DATA');

        if ($shoppingCartData == null) {
            $shoppingCartData = array();
        }

        $newShoppingCartData = array();

        if (is_array($request->products) && !empty($request->products)) {

            foreach ($request->products as $value) {
                $product = Product::where('id', $value['id'])->where('status', 'IN_STOCK')->first();

                if ($product == null) {
                    continue;
                }

                $newShoppingCartData[$value['id']] = $value['amount'];
            }

        }

        $request->session()->put('SHOPPINGCART_DATA', $newShoppingCartData);
        $request->session()->save();

        $results['success'] = true;
        return response()->json($results);
    }

    public function confirmShoppingCart(Request $request)
    {
        $results = array();

        if (!$request->session()->exists('SHOPPINGCART_DATA')) {
            $results['success'] = false;
            return response()->json($results);
        }

        $shoppingCartData = $request->session()->get('SHOPPINGCART_DATA');

        if (!is_array($shoppingCartData) || empty($shoppingCartData)) {
            $results['success'] = false;
            return response()->json($results);
        }

        $newShoppingCartData = array();

        foreach ($shoppingCartData as $key => $value) {

            $product = Product::where('id', $key)->where('status', 'IN_STOCK')->first();

            if ($product == null) {
                continue;
            }

            $value = intval($value);

            if (!is_int($value) || $value <= 0 || $value > 100) {
                continue;
            }

            $newShoppingCartData[$key] = $value;
        }

        $request->session()->put('SHOPPINGCART_STATUS', "INFORMATION");
        $request->session()->put('SHOPPINGCART_DATA', $newShoppingCartData);
        $request->session()->save();

        $results['url']     = route('shoppingCartInformation');
        $results['success'] = true;
        return response()->json($results);
    }

    public function createOrder(Request $request)
    {
        $results = array();

        if (!$request->session()->exists('SHOPPINGCART_STATUS') || $request->session()->get('SHOPPINGCART_STATUS') != "INFORMATION") {
            $results['success'] = false;
            return response()->json($results);
        }

        $validator = Validator::make($request->all(), [
            'paymentType'  => "required|in:PAYU,PAYPAL,PAYMENTCARD",
            'clientFName'  => new ValidFirstName,
            'clientSName'  => new ValidSurName,
            'clientPhone'  => new ValidPhoneNumber,
            'deliver.type' => "required|in:INPOST_LOCKER,COURIER",
            'note'         => new ValidOrderNote,
        ]);

        $addNoteToOrder = true;

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

        $shoppingCartData = $request->session()->get('SHOPPINGCART_DATA');

        if (empty($shoppingCartData)) {
            $results['success'] = false;
            $results['msg']     = "Koszyk jest pusty!";
            return response()->json($results);
        }

        if (empty($deliver_info)) {
            $results['success'] = false;
            $results['msg']     = "Wystąpił błąd z danymi do wysyłki!";
            return response()->json($results);
        }

        $productsData = array();
        $summaryPrice = 0;

        foreach ($shoppingCartData as $key => $value) {
            $product = Product::where('id', $key)->where('status', 'IN_STOCK')->first();

            if ($product == null) {
                continue;
            }

            $data           = array();
            $data['id']     = $product->id;
            $data['name']   = $product->title;
            $data['price']  = $product->price;
            $data['amount'] = $value;
            $summaryPrice += ($product->price * $value);
            array_push($productsData, $data);
        }

        $summaryPrice = number_format((float) $summaryPrice, 2, '.', '');

        $summaryPrice += OrderHelper::getDeliverCost($request->input('deliver.type'));

        $user = Auth::user();

        $buyer_info = [
            'firstname' => $request->input('clientFName'),
            'surname'   => $request->input('clientSName'),
            'phone'     => $request->input('clientPhone'),
        ];

        $order = Order::create([
            'user_id'      => $user->id,
            'status'       => 'CREATED',
            'cost'         => $summaryPrice,
            'buyer_info'   => json_encode($buyer_info),
            'deliver_name' => $request->input('deliver.type'),
            'deliver_info' => json_encode($deliver_info),
            'payment'      => $request->input('paymentType'),
        ]);

        if ($addNoteToOrder) {
            $note = $request->input('note');
            // TODO
            // NOTE FILTR
            $order->note = $note;
            $order->save();
        }

        Payment::create([
            'order_id'  => $order->id,
            'type'      => $order->payment,
            'amount'    => $order->cost,
            'status'    => "CREATED",
            'cancelled' => false,
        ]);

        foreach ($productsData as $product) {
            OrderProduct::create([
                'order_id'   => $order->id,
                'product_id' => $product['id'],
                'price'      => $product['price'],
                'amount'     => $product['amount'],
                'name'       => $product['name'],
            ]);
        }

        OrderHistory::create([
            'order_id' => $order->id,
            'data'     => 'Stworzono zamówienie',
        ]);

        $request->session()->forget('SHOPPINGCART_STATUS');
        $request->session()->forget('SHOPPINGCART_DATA');

        $subject = 'Dziękujemy za złożenie zamówienia nr. ' . $order->id;

        Mail::to($user->email)->send(new OrderCreateMail($order, $subject));

        $results['success'] = true;
        $results['url']     = route('orderIDPage', $order->id);
        return response()->json($results);
    }

}
