<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        return Order::with('user')->latest()->get();
    }

    public function updateStatus(Request $r, $id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => $r->status]);

        return $order;
    }
}
