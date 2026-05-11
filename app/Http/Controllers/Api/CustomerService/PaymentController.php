<?php

namespace App\Http\Controllers\Api\CustomerService;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $perPage = max(1, min(50, $perPage));
        $page = (int) $request->input('page', 1);
        $page = max(1, $page);

        $q = Payment::query()
            ->select('payments.*')
            ->with([
                'order:id,code,user_id',
                'order.user:id,name',
            ]);

        if ($request->filled('payment_status')) {
            $q->where('payment_status', $request->string('payment_status'));
        }

        if ($request->filled('search')) {
            $s = $request->string('search')->trim();
            $q->where(function ($qq) use ($s) {
                $qq->where('id', $s)
                    ->orWhereHas('order', function ($o) use ($s) {
                        $o->where('code', 'like', '%' . $s . '%');
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


    public function confirmDp(Request $request, string $id)
    {
        return $this->confirm($request, $id, 'dp');
    }

    public function confirmFull(Request $request, string $id)
    {
        return $this->confirm($request, $id, 'full');
    }

    protected function confirm(Request $request, string $id, string $kind)
    {
        $payment = Payment::with('order')->findOrFail($id);

        $payload = array_merge($payment->payload ?? [], [
            'confirmed_as' => $kind,
            'confirmed_by' => $request->user()?->id,
            'confirmed_at' => now()->toIso8601String(),
        ]);

        $payment->update([
            'payment_status' => 'paid',
            'paid_at' => now(),
            'payload' => $payload,
        ]);

        return $payment->fresh()->load('order');
    }
}
