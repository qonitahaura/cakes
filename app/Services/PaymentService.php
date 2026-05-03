<?php

namespace App\Services;

use App\Models\Payment;

class PaymentService
{
    public function createPayment($order)
    {
        return Payment::create([
            'order_id' => $order->id,
            'amount' => $order->total_price,
            'payment_status' => 'unpaid',
        ]);
    }
}
