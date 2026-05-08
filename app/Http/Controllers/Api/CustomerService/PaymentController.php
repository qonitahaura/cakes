<?php

namespace App\Http\Controllers\Api\CustomerService;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $q = Payment::with('order.user')->latest();

        if ($request->filled('payment_status')) {
            $q->where('payment_status', $request->payment_status);
        }

        return $q->limit(200)->get();
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
