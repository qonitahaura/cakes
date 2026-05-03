<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class ReportController extends Controller
{
    public function index(Request $r)
    {
        return Order::whereBetween('created_at', [
            $r->start,
            $r->end
        ])->get();
    }
}
