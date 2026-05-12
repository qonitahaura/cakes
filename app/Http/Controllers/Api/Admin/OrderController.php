<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $q = Order::with(['user', 'payment'])->select('orders.*');

        // Search
        if ($request->filled('search')) {
            $s = $request->string('search')->trim();
            $q->where(function ($qq) use ($s) {
                $qq->where('code', 'like', '%' . $s . '%')
                    ->orWhere('id', $s)
                    ->orWhereHas('user', function ($u) use ($s) {
                        $u->where('name', 'like', '%' . $s . '%');
                    });
            });
        }

        // Filters
        if ($request->filled('status')) {
            $q->where('status', $request->string('status'));
        }
        if ($request->filled('payment_status')) {
            $q->whereHas('payment', function ($pp) use ($request) {
                $pp->where('payment_status', $request->string('payment_status'));
            });
        }
        if ($request->filled('pickup_date')) {
            $q->whereDate('pickup_date', $request->string('pickup_date'));
        }

        $sortUi = $request->string('sort')->value(); // newest|oldest
        $dir = match ($sortUi) {
            'oldest' => 'asc',
            default => 'desc',
        };

        $q->orderBy('created_at', $dir);

        $perPage = (int) $request->input('per_page', 10);
        $perPage = max(1, min(100, $perPage));
        $page = (int) $request->input('page', 1);
        $page = max(1, $page);

        $paginator = $q->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'last_page' => $paginator->lastPage(),
                'total' => $paginator->total(),
            ],
        ]);
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
