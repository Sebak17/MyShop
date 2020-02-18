<?php

namespace App\Helpers\Payments;

use App\Helpers\OrderHelper;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\Payment;
use Log;

class PayUHelper
{

    public function __construct()
    {
        require_once "../vendor/openpayu/openpayu/lib/openpayu.php";

        //set Sandbox Environment
        \OpenPayU_Configuration::setEnvironment('sandbox');

        //set POS ID and Second MD5 Key (from merchant admin panel)
        \OpenPayU_Configuration::setMerchantPosId('371888');
        \OpenPayU_Configuration::setSignatureKey('f08387f3cbdedae37eefac91583e5f41');

        //set Oauth Client Id and Oauth Client Secret (from merchant admin panel)
        \OpenPayU_Configuration::setOauthClientId('371888');
        \OpenPayU_Configuration::setOauthClientSecret('04fd182b86b7142fc785ede2d4043e57');

    }

    public function createPayment(Order $order, Payment $order_payment)
    {

        $results = array();

        $payu_order                  = array();
        $payu_order['notifyUrl']     = route('systemSite_handlePayU');
        $payu_order['continueUrl']   = route('paymentStatusPage-success');
        $payu_order['customerIp']    = $_SERVER['REMOTE_ADDR'];
        $payu_order['merchantPosId'] = \OpenPayU_Configuration::getOauthClientId() ? \OpenPayU_Configuration::getOauthClientId() : \OpenPayU_Configuration::getMerchantPosId();
        $payu_order['description']   = 'Zamówienie nr. ' . $order->id;
        $payu_order['currencyCode']  = 'PLN';
        $payu_order['totalAmount']   = $order_payment->amount * 100;
        $payu_order['extOrderId']    = uniqid('', true);
        $payu_order['products']      = array();

        foreach ($order->products as $product) {
            $item = array();

            $item['name']      = $product->name;
            $item['unitPrice'] = $product->price * 100;
            $item['quantity']  = $product->amount;

            array_push($payu_order['products'], $item);

        }

        $buyerInfo = json_decode($order->buyer_info, true);

        $payu_order['buyer']['email']     = $order->user->email;
        $payu_order['buyer']['phone']     = $order->user->personal->phoneNumber;
        $payu_order['buyer']['firstName'] = $order->user->personal->firstname;
        $payu_order['buyer']['lastName']  = $order->user->personal->surname;
        $payu_order['buyer']['language']  = 'pl';

        $response = \OpenPayU_Order::create($payu_order);

        if ($response->getStatus() == 'SUCCESS') {
            OrderHelper::changeOrderStatus($order, 'PROCESSING');

            $order_payment->externalID = $response->getResponse()->orderId;
            $order_payment->status     = "GENERATED";
            $order_payment->save();

            $results['success'] = true;
            $results['url']     = $response->getResponse()->redirectUri;
            return $results;
        } else {
            $results['success'] = false;
            $results['msg']     = "Wystąpił nieznany błąd!";
        }

        return $results;
    }

    public function handlePayment()
    {
        $body = file_get_contents('php://input');
        $data = trim($body);
        try {

            if (!empty($data)) {
                $result = \OpenPayU_Order::consumeNotification($data);
            }

            if ($result->getResponse()->order->orderId) {
                $payu_order = \OpenPayU_Order::retrieve($result->getResponse()->order->orderId);

                $payment = Payment::where('externalID', $order->Id)->first();

                if ($payment == null) {
                    header("HTTP/1.1 200 OK");
                    exit();
                }

                $order = $payment->order;

                if ($order == null) {
                    header("HTTP/1.1 200 OK");
                    exit();
                }

                $payment->status = $payu_order->getStatus();
                $payment->save();

                if ($order->status != 'PROCESSING') {
                    header("HTTP/1.1 200 OK");
                    exit();
                }

                $localAmount = intval($payment->amount * 100);
                $externalTotalAmount = intval($response->getResponse()->orders[0]->totalAmount);

                if ( ($localAmount != $externalTotalAmount) &&
                    (($localAmount - 1) != $externalTotalAmount)) {
                    header("HTTP/1.1 200 OK");
                    exit();
                }

                if ($response->getResponse()->orders[0]->status != 'SUCCESS' && $response->getResponse()->orders[0]->status != 'COMPLETED') {
                    OrderHelper::changeOrderStatus($order, 'PAID');

                    header("HTTP/1.1 200 OK");
                    exit();
                }

            }

        } catch (\OpenPayU_Exception $e) {
            echo $e->getMessage();
        }

    }

    public function getPaymentStatus($order_id)
    {
        $results = array();

        $response = \OpenPayU_Order::retrieve($order_id);

        if ($response->getResponse()->orders[0]->status == 'CANCELED') {
            $results['msg']     = "Płatność została odrzucona!";
            $results['success'] = false;
            return $results;
        }

        if ($response->getResponse()->orders[0]->status != 'SUCCESS' && $response->getResponse()->orders[0]->status != 'COMPLETED') {
            $results['msg']     = "Płatność nie została zakończona sukcesem!";
            $results['success'] = false;
            return $results;
        }

        $payment = Payment::where('externalID', $order_id)->first();

        if ($payment == null) {
            $results['success'] = false;
            $results['msg']     = "Nie znaleziono płatności!";
            return $results;
        }

        $order = $payment->order;

        if ($order == null) {
            $results['success'] = false;
            $results['msg']     = "Nie znaleziono zamówienia!";
            return $results;
        }

        $localAmount = intval($payment->amount * 100);
        $externalTotalAmount = intval($response->getResponse()->orders[0]->totalAmount);

        if ( ($localAmount != $externalTotalAmount) &&
            (($localAmount - 1) != $externalTotalAmount)) {
            $results['success'] = false;
            $results['msg']     = "Wystąpił problem przy pobieraniu danych!";
            return $results;
        }

        $this->isNeedToUpdate($order, $payment, $response);

        $results['orderID'] = $order->id;
        
        $results['success'] = true;
        return $results;
    }

    public function checkPayment($order_id) {
    	$results = array();
    	$results['success'] = false;
    	
        $response = \OpenPayU_Order::retrieve($order_id);

        $status = $response->getResponse()->orders[0]->status;

        $payment = Payment::where('externalID', $order_id)->first();

        if ($payment == null) {
            return $results;
        }

        if ($status != 'SUCCESS' && $status != 'COMPLETED') {
            if($status == 'CANCELED') {
                $payment->status = $status;
                $payment->cancelled = true;
                $payment->save();

                $order = $payment->order;
                if($order != null) {
                    OrderHelper::changeOrderStatus($order, 'UNPAID');
                }

                OrderHistory::create([
                    'order_id' => $payment->order->id,
                    'data'     => 'Transakcja została odrzucona',
                ]);
            }

            return $results;
        }

        $order = $payment->order;

        if ($order == null) {
            return $results;
        }

        $localAmount = intval($payment->amount * 100);
        $externalTotalAmount = intval($response->getResponse()->orders[0]->totalAmount);

        if ( ($localAmount != $externalTotalAmount) &&
            (($localAmount - 1) != $externalTotalAmount)) {
            return $results;
        }

        $this->isNeedToUpdate($order, $payment, $response);

        $results['success'] = true;
        return $results;
    }

    private function isNeedToUpdate(Order $order, Payment $payment, $response)
    {
        if($response->getResponse()->orders[0]->status == 'CANCELED') {
                $payment->status = $response->getResponse()->orders[0]->status;
                $payment->cancelled = true;
                $payment->save();

                if($order != null) {
                    OrderHelper::changeOrderStatus($order, 'UNPAID');
                }

                OrderHistory::create([
                    'order_id' => $payment->order->id,
                    'data'     => 'Transakcja została odrzucona',
                ]);
            }

        if ($response->getResponse()->orders[0]->status != 'SUCCESS'  && $response->getResponse()->orders[0]->status != 'COMPLETED') {
            return;
        }

        if ($order->status != 'PROCESSING') {
            return;
        }

        $localAmount = intval($payment->amount * 100);
        $externalTotalAmount = intval($response->getResponse()->orders[0]->totalAmount);

        if ( ($localAmount != $externalTotalAmount) &&
           (($localAmount - 1) != $externalTotalAmount)) {
            return;
        }

        $payment->status = $response->getResponse()->orders[0]->status;
        $payment->save();

        OrderHelper::changeOrderStatus($order, 'PAID');

    }

}
