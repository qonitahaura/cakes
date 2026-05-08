<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $q = Order::with('user')->latest();

        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }

        return $q->get();
    }

    public function show(string $id)
    {
        return Order::with([
            'user',
            'payment',
            'items.customizations',
            'items.designs',
        ])->findOrFail($id);
    }

    public function updateStatus(Request $r, $id)
    {
        $order = Order::findOrFail($id);
        $r->validate([
            'status' => 'required|in:pending,waiting_payment,paid,processing,shipped,completed,cancelled,refunded',
        ]);
        $order->update(['status' => $r->status]);

        return $order->fresh()->load('user');
    }
}
