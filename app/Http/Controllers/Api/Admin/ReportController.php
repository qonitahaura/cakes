<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Legacy: orders between start and end.
     */
    public function index(Request $r)
    {
        $r->validate([
            'start' => 'required|date',
            'end' => 'required|date',
        ]);

        return Order::whereBetween('created_at', [
            $r->start,
            $r->end,
        ])->get();
    }

    public function summary()
    {
        $revenueStatuses = ['paid', 'processing', 'shipped', 'completed'];

        return response()->json([
            'total_users' => User::count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_revenue' => (float) Order::whereIn('status', $revenueStatuses)->sum('total_price'),
            'pending_orders' => Order::whereIn('status', ['pending', 'waiting_payment'])->count(),
            'orders_by_status' => Order::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status'),
        ]);
    }

    public function revenueByDay(Request $request)
    {
        $days = (int) $request->input('days', 30);
        $days = max(1, min(365, $days));
        $start = Carbon::now()->subDays($days)->startOfDay();

        $revenueStatuses = ['paid', 'processing', 'shipped', 'completed'];

        $rows = Order::query()
            ->whereIn('status', $revenueStatuses)
            ->where('created_at', '>=', $start)
            ->select(DB::raw('DATE(created_at) as d'), DB::raw('SUM(total_price) as revenue'))
            ->groupBy('d')
            ->orderBy('d')
            ->get();

        return response()->json($rows);
    }

    /**
     * CSV export (no extra composer deps).
     */
    public function export(Request $request, string $ext)
    {
        $request->validate([
            'start' => 'nullable|date',
            'end' => 'nullable|date',
        ]);

        $start = $request->input('start', Carbon::now()->subDays(30)->toDateString());
        $end = $request->input('end', Carbon::now()->toDateString());

        $orders = Order::with('user:id,name,email')
            ->whereBetween('created_at', [$start.' 00:00:00', $end.' 23:59:59'])
            ->orderBy('created_at')
            ->get();

        if ($ext !== 'csv') {
            return response()->json(['message' => 'Only csv export is supported'], 422);
        }

        $filename = 'orders-'.$start.'-to-'.$end.'.csv';
        $callback = function () use ($orders) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['id', 'code', 'customer', 'email', 'total_price', 'status', 'created_at']);
            foreach ($orders as $o) {
                fputcsv($out, [
                    $o->id,
                    $o->code,
                    $o->user?->name,
                    $o->user?->email,
                    $o->total_price,
                    $o->status,
                    $o->created_at,
                ]);
            }
            fclose($out);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
