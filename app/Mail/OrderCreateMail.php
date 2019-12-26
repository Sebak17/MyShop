<?php

namespace App\Mail;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderCreateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $deliverInfo         = json_decode($this->order->deliver_info, true);
        $deliverInfo['type'] = $this->order->deliver_name;

        $buyerInfo         = json_decode($this->order->buyer_info, true);

        return $this->markdown('emails.order_create')->with('order', $this->order)->with('deliverInfo', $deliverInfo)->with('buyerInfo', $buyerInfo);
    }
}
