<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Str;

class OrderService
{
    public function createOrder($user, $data)
    {
        return Order::create([
            'user_id' => $user->id,
            'code' => 'ORD-' . Str::upper(Str::random(8)),
            'fulfillment_type' => $data['fulfillment_type'],
            'pickup_date' => $data['pickup_date'] ?? null,
            'pickup_time' => $data['pickup_time'] ?? null,
            'delivery_date' => $data['delivery_date'] ?? null,
            'delivery_time' => $data['delivery_time'] ?? null,
            'delivery_address' => $data['delivery_address'] ?? null,
            'delivery_fee' => $data['delivery_fee'] ?? 0,
            'status' => 'pending',
        ]);
    }
}
