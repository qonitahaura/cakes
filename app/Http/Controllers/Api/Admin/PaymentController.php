<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $q = Payment::query()->with('order.user');

        if ($request->filled('payment_status')) {
            $q->where('payment_status', $request->payment_status);
        }
        if ($request->filled('from')) {
            $q->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $q->whereDate('created_at', '<=', $request->to);
        }

        return $q->latest()->get();
    }

    public function show(string $id)
    {
        return Payment::with('order.user', 'order.items')->findOrFail($id);
    }
}
