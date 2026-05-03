<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        return Order::with('items')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
    }

    public function show($id)
    {
        return Order::with('items.customizations', 'items.designs')
            ->where('user_id', Auth::id())
            ->findOrFail($id);
    }
}
