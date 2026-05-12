<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $q = Payment::query()->with(['order.user', 'order'])
            ->select('payments.*');

        // Search: order id / customer name
        if ($request->filled('search')) {
            $s = $request->string('search')->trim();
            $q->where(function ($qq) use ($s) {
                $qq->where('id', $s)
                    ->orWhereHas('order', function ($oo) use ($s) {
                        $oo->where('code', 'like', '%' . $s . '%')
                            ->orWhere('id', $s)
                            ->orWhereHas('user', function ($uu) use ($s) {
                                $uu->where('name', 'like', '%' . $s . '%');
                            });
                    });
            });
        }

        // Filter: DP/full (based on payload.confirmed_as)
        if ($request->filled('payment_kind')) {
            $kind = $request->string('payment_kind')->value(); // dp|full
            $q->where(function ($qq) use ($kind) {
                $qq->where('confirmed_as', $kind)
                    ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(payload, '$.confirmed_as')) = ?", [$kind]);
            });
        }

        // Filter: paid/unpaid
        if ($request->filled('paid_status')) {
            $ps = $request->string('paid_status')->value(); // paid|unpaid
            $q->where('payment_status', $ps === 'unpaid' ? 'unpaid' : 'paid');
        }

        // Optional date range (keeps existing compatibility)
        if ($request->filled('from')) {
            $q->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $q->whereDate('created_at', '<=', $request->to);
        }

        $sortUi = $request->string('sort')->value(); // newest|oldest
        $dir = match ($sortUi) {
            'oldest' => 'asc',
            default => 'desc',
        };

        // Sort by payment date
        $q->orderBy('paid_at', $dir)
            ->orderBy('created_at', $dir);

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
        return Payment::with('order.user', 'order.items')->findOrFail($id);
    }
}
