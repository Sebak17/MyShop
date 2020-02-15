<?php

namespace App\Http\Controllers\UserSystem;

use App\Helpers\OrderHelper;
use App\Helpers\Payments\PayPalHelper;
use App\Helpers\Payments\PayUHelper;
use App\Http\Controllers\Controller;
use App\Order;
use App\OrderHistory;
use App\Payment;
use App\Rules\ValidID;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
	
    public function paymentCancel(Request $request)
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

        $order = Order::where('id', $request->id)->first();

        if ($order == null) {
            $results['success'] = false;
            return response()->json($results);
        }

        if (!in_array($order->status, ['CREATED', 'UNPAID', 'PROCESSING', 'PAID', 'REALIZE', 'SENT', 'RECEIVE'])) {
            $results['success'] = false;
            return response()->json($results);
        }

        do {

            $payment = $order->getCurrentPayment();

            if ($payment == null) {
                break;
            }

            $payment->cancelled = true;
            $payment->save();

        } while ($payment != null);

        OrderHistory::create([
            'order_id' => $order->id,
            'data'     => 'Anulowanie płatności przez użytkownika.',
        ]);

        OrderHelper::changeOrderStatus($order, 'UNPAID');

        $results['success'] = true;
        return response()->json($results);
    }

    public function paymentPay(Request $request)
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

        $order = Order::where('id', $request->id)->first();

        if ($order == null) {
            $results['success'] = false;
            return response()->json($results);
        }

        if (!in_array($order->status, ['CREATED', 'UNPAID'])) {
            $results['success'] = false;
            return response()->json($results);
        }

        $payment = $order->getCurrentPayment();

        if ($payment == null) {
            $payment = Payment::create([
                'order_id'  => $order->id,
                'type'      => $order->payment,
                'amount'    => $order->cost,
                'status'    => "CREATED",
                'cancelled' => false,
            ]);
        }

        OrderHelper::changeOrderStatus($order, 'UNPAID');

        $res = array();

        switch ($order->payment) {
            case 'PAYPAL':
                $paypal = new PayPalHelper();
                $res    = $paypal->createPayment($order, $payment);
                break;
            case 'PAYU':
                $payu = new PayUHelper();
                $res  = $payu->createPayment($order, $payment);
                break;
            default:
                $res['success'] = false;
                $res['msg']     = "Ten sposób płatności nie jest obsługiwany!";
                break;
        }

        if ($res['success'] == true) {
            $results['success'] = true;
            $results['url']     = $res['url'];

            OrderHistory::create([
                'order_id' => $order->id,
                'data'     => 'Polecenie transakcji za pomocą: ' . $order->payment,
            ]);

        } else {
            $results['success'] = false;
            $results['msg']     = $res['msg'];
        }

        return response()->json($results);
    }

    public function paymentCheck(Request $request)
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

        $order = Order::where('id', $request->id)->first();

        if ($order == null) {
            $results['success'] = false;
            return response()->json($results);
        }

        if (!in_array($order->status, ['PROCESSING'])) {
            $results['success'] = false;
            return response()->json($results);
        }

        if ($order->payment != 'PAYU') {
            $results['success'] = false;
            return response()->json($results);
        }

        $payment = $order->getCurrentPayment();

        if ($payment == null) {
            $results['success'] = false;
            $results['msg']     = "Nie znaleziono płatności!";
            return response()->json($results);
        }

        if ($payment->type != 'PAYU') {
            $results['success'] = false;
            return response()->json($results);
        }

        $payu = new PayUHelper();
        $res  = $payu->checkPayment($payment->externalID);

        $results['success'] = $res['success'];
        return response()->json($results);
    }

}
