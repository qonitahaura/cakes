<?php

namespace App\Http\Controllers\Api\Baker;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;


class OrderController extends Controller
{
    public function index(Request $request)
    {
        $q = Order::with(['user:id,name', 'payment', 'items.customizations', 'items.designs'])
            ->select('orders.*');

        // Search by order code or customer name
        if ($request->filled('search')) {
            $s = $request->string('search')->trim();
            $q->where(function ($qq) use ($s) {
                $qq->where('code', 'like', '%' . $s . '%')
                    ->orWhereHas('user', function ($u) use ($s) {
                        $u->where('name', 'like', '%' . $s . '%');
                    });
            });
        }

        // Filters
        $status = $request->query('status');
        if ($status === 'completed') {
            $q->where('status', 'completed');
        } elseif ($status) {
            $q->where('status', $status);
        } else {
            // All => include completed too
            $q->whereIn('status', ['paid', 'processing', 'shipped', 'completed']);
        }


        $pickup_deadline = $request->query('pickup_deadline');
        if ($pickup_deadline) {
            $q->where(function ($qq) use ($pickup_deadline) {
                $qq->where(function ($x) use ($pickup_deadline) {
                    $x->where('fulfillment_type', 'pickup')
                        ->whereDate('pickup_date', $pickup_deadline);
                })->orWhere(function ($x) use ($pickup_deadline) {
                    $x->where('fulfillment_type', 'delivery')
                        ->whereDate('delivery_date', $pickup_deadline);
                });
            });
        }

        // Sort by nearest deadline
        $sort = $request->string('sort')->value(); // deadline|newest
        if ($sort === 'deadline') {
            $q->orderByRaw(
                "CASE WHEN fulfillment_type='pickup' AND pickup_date IS NOT NULL THEN pickup_date ELSE delivery_date END ASC"
            );
        } else {
            $q->orderBy('created_at', 'desc');
        }

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

    /**
     * Orders sorted by nearest fulfillment deadline (pickup or delivery).
     * Used by both:
     * - Baker completed page (completion_status=completed)
     * - Baker schedule page (completion_status=pending)
     */
    public function schedule(Request $request)
    {
        $q = Order::with(['user:id,name', 'payment', 'items.customizations', 'items.designs'])
            ->select('orders.*');



        // Search by order code / customer name
        if ($request->filled('search')) {
            $s = $request->string('search')->trim();
            $q->where(function ($qq) use ($s) {
                $qq->where('code', 'like', '%' . $s . '%')
                    ->orWhereHas('user', function ($u) use ($s) {
                        $u->where('name', 'like', '%' . $s . '%');
                    });
            });
        }

        $pickup_date = $request->query('pickup_date');
        $fulfillment_type = $request->query('fulfillment_type');


        if ($fulfillment_type === 'pickup') {
            $q->where('fulfillment_type', 'pickup');
        } elseif ($fulfillment_type === 'delivery') {
            $q->where('fulfillment_type', 'delivery');
        }

        if ($pickup_date) {
            $q->where(function ($qq) use ($pickup_date) {
                $qq->where(function ($x) use ($pickup_date) {
                    $x->where('fulfillment_type', 'pickup')->whereDate('pickup_date', $pickup_date);
                })->orWhere(function ($x) use ($pickup_date) {
                    $x->where('fulfillment_type', 'delivery')->whereDate('delivery_date', $pickup_date);
                });
            });
        }

        // Untuk halaman schedule: kita HAPUS order yang sudah complete.
        // Jadi schedule hanya menampilkan produksi yang belum selesai.
        $q->whereIn('status', ['paid', 'processing', 'shipped']);




        $q->orderByRaw(
            "CASE WHEN fulfillment_type='pickup' AND pickup_date IS NOT NULL THEN pickup_date ELSE delivery_date END ASC"
        );

        $perPage = (int) request()->input('per_page', 10);
        $perPage = max(1, min(50, $perPage));
        $page = (int) request()->input('page', 1);
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
