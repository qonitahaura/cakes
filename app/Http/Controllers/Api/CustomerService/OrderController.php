<?php

namespace App\Http\Controllers\Api\CustomerService;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function incoming(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $perPage = max(1, min(50, $perPage));
        $page = (int) $request->input('page', 1);
        $page = max(1, $page);

        $q = Order::query()
            ->where('status', 'pending')
            ->select('orders.*')
            ->with([
                'user:id,name',
                'payment:id,order_id,payment_status,amount',
                'items:id,order_id,product_id,quantity',
            ]);

        if ($request->filled('search')) {
            $s = $request->string('search')->trim();
            $q->where(function ($qq) use ($s) {
                $qq->where('code', 'like', '%' . $s . '%')
                    ->orWhereHas('user', function ($u) use ($s) {
                        $u->where('name', 'like', '%' . $s . '%');
                    });
            });
        }

        $sort = $request->string('sort')->value(); // newest|oldest
        if ($sort === 'oldest') {
            $q->orderBy('created_at', 'asc');
        } else {
            $q->orderBy('created_at', 'desc');
        }

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


    public function pickupSchedule(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $perPage = max(1, min(100, $perPage));
        $page = (int) $request->input('page', 1);
        $page = max(1, $page);

        $from = $request->input('from_date');
        $to = $request->input('to_date');

        $q = Order::query()
            ->where('fulfillment_type', 'pickup')
            ->whereNotNull('pickup_date')
            ->whereNotIn('status', ['cancelled', 'refunded'])
            ->select('orders.*')
            ->with([
                'user:id,name',
                'payment:id,order_id,payment_status,amount',
            ]);

        if ($from) {
            $q->whereDate('pickup_date', '>=', $from);
        }
        if ($to) {
            $q->whereDate('pickup_date', '<=', $to);
        }

        if ($request->filled('search')) {
            $s = $request->string('search')->trim();
            $q->where(function ($qq) use ($s) {
                $qq->where('code', 'like', '%' . $s . '%')
                    ->orWhereHas('user', function ($u) use ($s) {
                        $u->where('name', 'like', '%' . $s . '%');
                    });
            });
        }

        $sort = $request->string('sort')->value(); // earliest|latest
        if ($sort === 'latest') {
            $q->orderByDesc('pickup_date')->orderByDesc('pickup_time');
        } else {
            $q->orderBy('pickup_date')->orderBy('pickup_time');
        }

        // Paginate the actual orders (frontend can optionally group).
        $paginator = $q->paginate($perPage, ['*'], 'page', $page);

        // Maintain backward-compatible response shape if frontend expects groups by date.
        // We group only the current page results to avoid full-table loads.
        $grouped = collect($paginator->items())
            ->groupBy(fn($o) => (string) $o->pickup_date)
            ->map->values();

        return response()->json([
            'data' => $grouped,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'last_page' => $paginator->lastPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }


    public function history(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $perPage = max(1, min(50, $perPage));
        $page = (int) $request->input('page', 1);
        $page = max(1, $page);

        $q = Order::query()
            ->select('orders.*')
            ->with([
                'user:id,name',
                'payment:id,order_id,payment_status,amount',
            ]);

        if ($request->filled('status')) {
            $q->where('status', $request->string('status'));
        } else {
            $q->whereIn('status', ['completed', 'cancelled', 'refunded']);
        }

        if ($request->filled('search')) {
            $s = $request->string('search')->trim();
            $q->where(function ($qq) use ($s) {
                $qq->where('code', 'like', '%' . $s . '%')
                    ->orWhereHas('user', function ($u) use ($s) {
                        $u->where('name', 'like', '%' . $s . '%');
                    });
            });
        }

        $sort = $request->string('sort')->value(); // newest|oldest
        if ($sort === 'oldest') {
            $q->orderBy('updated_at', 'asc');
        } else {
            $q->orderBy('updated_at', 'desc');
        }

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
            $order->update(['status' => 'paid', 'validated_at' => now()]);

            return $order->fresh()->load(['user', 'payment', 'items']);
        }

        // request_revisions
        $note = trim((string) ($order->note ?? ''));
        $msg = $data['message'] ?? 'Revision requested';
        $order->update([
            'note' => $note ? $note . "\n\n[CS] " . $msg : '[CS] ' . $msg,
            'status' => 'pending',
        ]);

        return $order->fresh()->load(['user', 'payment', 'items']);
    }
}
