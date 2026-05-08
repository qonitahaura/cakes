<?php

namespace App\Http\Controllers\Api\CustomerService;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function incoming()
    {
        return Order::with(['user', 'items', 'payment'])
            ->where('status', 'pending')
            ->latest()
            ->get();
    }

    public function pickupSchedule()
    {
        $orders = Order::with(['user', 'payment'])
            ->where('fulfillment_type', 'pickup')
            ->whereNotNull('pickup_date')
            ->whereNotIn('status', ['cancelled', 'refunded'])
            ->orderBy('pickup_date')
            ->orderBy('pickup_time')
            ->get();

        return $orders->groupBy(fn ($o) => (string) $o->pickup_date)->map->values();
    }

    public function history(Request $request)
    {
        $q = Order::with(['user', 'payment'])->latest();

        if ($request->filled('status')) {
            $q->where('status', $request->status);
        } else {
            $q->whereIn('status', ['completed', 'cancelled', 'refunded']);
        }

        return $q->limit(200)->get();
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

    public function validateOrder(Request $request, string $id)
    {
        $order = Order::findOrFail($id);
        $data = $request->validate([
            'action' => 'required|in:approve,request_revisions',
            'message' => 'nullable|string|max:2000',
        ]);

        if ($data['action'] === 'approve') {
            if ($order->status !== 'pending') {
                return response()->json(['message' => 'Order is not pending'], 422);
            }
            $order->update(['status' => 'paid']);

            return $order->fresh()->load(['user', 'payment', 'items']);
        }

        // request_revisions
        $note = trim((string) ($order->note ?? ''));
        $msg = $data['message'] ?? 'Revision requested';
        $order->update([
            'note' => $note ? $note."\n\n[CS] ".$msg : '[CS] '.$msg,
            'status' => 'pending',
        ]);

        return $order->fresh()->load(['user', 'payment', 'items']);
    }
}
