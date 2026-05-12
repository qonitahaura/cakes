<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\CheckoutService;

class CheckoutController extends Controller
{
    public function checkout(Request $request, CheckoutService $service)
    {
        try {
            $order = $service->process(Auth::user(), $request->all());

            return response()->json([
                'message' => 'Checkout berhasil',
                'order' => $order
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
