@extends('layouts.dashboard')

@section('dashboard-content')
    <div id="order-root" data-order-id="{{ $orderId }}" class="space-y-6">
        <div>
            <a href="/admin/orders" class="text-sm font-medium text-primary-600 hover:underline">&larr; Back to orders</a>
            <h2 id="order-title" class="page-title mt-2">Order</h2>
            <div id="order-meta" class="mt-3 space-y-1 text-sm text-accent-800"></div>
        </div>

        <div class="card">
            <h3 class="mb-3 font-semibold text-accent-900">Update status</h3>
            <form id="status-form" class="flex flex-wrap items-end gap-3">
                <div>
                    <label class="label" for="order-new-status">New status</label>
                    <select class="input" id="order-new-status">
                        @foreach (['pending','waiting_payment','paid','processing','shipped','completed','cancelled','refunded'] as $s)
                            <option value="{{ $s }}">{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-primary">Save</button>
            </form>
        </div>

        <div>
            <h3 class="mb-3 text-lg font-semibold text-accent-900">Items &amp; designs</h3>
            <div id="order-items" class="grid gap-4 md:grid-cols-2"></div>
        </div>
    </div>
@endsection
