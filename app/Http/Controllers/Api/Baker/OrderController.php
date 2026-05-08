<?php

namespace App\Http\Controllers\Api\Baker;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $q = Order::with(['user', 'items.customizations', 'items.designs', 'payment'])
            ->latest();

        if ($status === 'completed') {
            $q->where('status', 'completed');
        } elseif ($status) {
            $q->where('status', $status);
        } else {
            $q->whereIn('status', ['paid', 'processing', 'shipped']);
        }

        return $q->get();
    }

    /**
     * Orders sorted by nearest fulfillment deadline (pickup or delivery).
     */
    public function schedule()
    {
        $orders = Order::with(['user', 'items.customizations', 'items.designs', 'payment'])
            ->whereIn('status', ['paid', 'processing', 'shipped'])
            ->get();

        return $orders->sortBy(function (Order $o) {
            if ($o->fulfillment_type === 'pickup' && $o->pickup_date) {
                return Carbon::parse($o->pickup_date.' '.($o->pickup_time ?? '23:59:59'))->timestamp;
            }
            if ($o->fulfillment_type === 'delivery' && $o->delivery_date) {
                return Carbon::parse($o->delivery_date.' '.($o->delivery_time ?? '23:59:59'))->timestamp;
            }

            return PHP_INT_MAX;
        })->values();
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

    public function updateProductionStatus(Request $request, string $id)
    {
        $order = Order::findOrFail($id);
        $request->validate([
            'status' => 'required|in:processing,completed',
        ]);
        $next = $request->status;
        $current = $order->status;

        $allowed = match ($next) {
            'processing' => $current === 'paid',
            'completed' => in_array($current, ['paid', 'processing', 'shipped'], true),
            default => false,
        };

        if (! $allowed) {
            return response()->json(['message' => 'Invalid status transition'], 422);
        }

        $order->update(['status' => $next]);

        return $order->fresh()->load(['user', 'items.customizations', 'items.designs', 'payment']);
    }
}
