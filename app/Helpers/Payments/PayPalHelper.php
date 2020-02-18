<?php

namespace App\Helpers\Payments;

use App\Helpers\OrderHelper;
use App\Models\Order;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Session;

class PayPalHelper
{

    private $_api_context;

    public function __construct()
    {
        $paypal_conf        = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
            $paypal_conf['client_id'],
            $paypal_conf['secret'])
        );
        $this->_api_context->setConfig($paypal_conf['settings']);

    }

    public function createPayment(Order $order, \App\Payment $order_payment)
    {

        $results = array();

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $productsList = array();

        $item = new Item();

        $item->setName("Dostawa")
            ->setCurrency('PLN')
            ->setQuantity(1)
            ->setPrice(OrderHelper::getDeliverCost($order->deliver_name));
        array_push($productsList, $item);

        foreach ($order->products as $product) {
            $item = new Item();

            $item->setName($product->name)
                ->setCurrency('PLN')
                ->setQuantity($product->amount)
                ->setPrice($product->price);

            array_push($productsList, $item);
        }

        $item_list = new ItemList();
        $item_list->setItems($productsList);

        $amount = new Amount();
        $amount->setCurrency('PLN')
               ->setTotal($order_payment->amount);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Zamówienie nr. ' . $order->id);

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(route('paymentStatusPage'))
                      ->setCancelUrl(route('paymentStatusPage'));

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));

        try {
            $payment->create($this->_api_context);

        } catch (\PayPal\Exception\PayPalConnectionException $ex) {

            $results['success'] = false;
            $results['msg']     = "Wystąpił błąd podczas tworzenia płatności!";
            return $results;

        }

        foreach ($payment->getLinks() as $link) {

            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }

        }

        Session::put('paypal_payment_id', $payment->getId());

        if (isset($redirect_url)) {
            OrderHelper::changeOrderStatus($order, 'PROCESSING');

            $order_payment->externalID = $payment->getId();
            $order_payment->status     = "GENERATED";
            $order_payment->save();

            $results['success'] = true;
            $results['url']     = $redirect_url;
            return $results;
        }

        $results['success'] = false;
        $results['msg']     = "Wystąpił nieznany błąd!";
        return $results;
    }

    public function getPaymentStatus($payerID, $token)
    {
        $results = array();

        $payment_id = Session::get('paypal_payment_id');

        if ($payment_id == null) {
            $results['success'] = false;
            $results['msg']     = "Nie znaleziono płatności!";
            return $results;
        }

        if (empty($payerID) || empty($token)) {
            $results['success'] = false;
            $results['msg']     = "Nie znaleziono płatności!";
            return $results;
        }

        $payment   = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($payerID);
        $result = null;
        try {
            $result = $payment->execute($execution, $this->_api_context);

        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            $results['success'] = false;
            $results['msg']     = "Błąd podczas sprawdzania zamówienia!";
            return $results;

        }

        if ($result == null) {
            $results['success'] = false;
            $results['msg']     = "Błąd podczas sprawdzania zamówienia!";
            return $results;
        }

        if ($result->getState() == 'approved') {

            $order_payment = \App\Payment::where('externalID', $result->getId())->where('amount', $result->getTransactions()[0]->getAmount()->getTotal())->first();

            if ($order_payment == null) {
                $results['success'] = false;
                $results['msg']     = "Nie znaleziono płatności!";
                return $results;
            }

            if ($order_payment->status == 'approved') {
                $results['success'] = false;
                $results['msg']     = "Płatność jest już zrealizowana!";
                return $results;
            }

            $order = $order_payment->order;

            if($order == null) {
                $results['success'] = false;
                $results['msg'] = "Nie znaleziono zamówienia!";
                return $results;
            }

            $results['orderID']     = $order->id;

            if($order->status == 'PAID') {
                $results['success'] = false;
                $results['msg'] = "Zamówienie jest już opłacone!";
                return $results;
            }

            if($order->status != 'PROCESSING') {
                $results['success'] = false;
                $results['msg'] = "Zamówienie nie jest w trakcie opłacania!";
                return $results;
            }

            $order_payment->status = $result->getState();
            $order_payment->save();

            OrderHelper::changeOrderStatus($order, 'PAID');
            
            $results['success'] = true;
            $results['msg']     = "Zamówienie zostało opłacone pomyślnie!";

            return $results;
        }

        $results['success'] = false;
        $results['msg']     = "Zamówienie nie zostało opłacone!";
        return $results;
    }

}
