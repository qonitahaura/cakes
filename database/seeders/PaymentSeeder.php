<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $dpOrder = Order::where('code', 'CAKE-PENDING-1')->first();
            $fullOrder = Order::where('code', 'CAKE-COMPLETED-1')->first();
            if (! $dpOrder || ! $fullOrder) {
                return;
            }

            // DP payment
            Payment::firstOrCreate(
                ['order_id' => $dpOrder->id],
                [
                    'payment_method' => 'card',
                    'payment_gateway' => 'stripe',
                    'payment_status' => 'paid',
                    'payment_date' => now()->subDays(4),
                    'paid_at' => now()->subDays(4),
                    'amount' => (float) $dpOrder->total_price * 0.5,
                    'payload' => [
                        'confirmed_as' => 'dp',
                        'notes' => 'Demo DP confirmed',
                    ],
                    'transaction_id' => 'txn-dp-demo-' . $dpOrder->id,
                ]
            );

            // Full payment
            Payment::updateOrCreate(
                ['order_id' => $fullOrder->id],
                [
                    'payment_method' => 'card',
                    'payment_gateway' => 'stripe',
                    'payment_status' => 'paid',
                    'payment_date' => now()->subDays(2),
                    'paid_at' => now()->subDays(2),
                    'amount' => (float) $fullOrder->total_price,
                    'payload' => [
                        'confirmed_as' => 'full',
                        'notes' => 'Demo full confirmed',
                    ],
                    'transaction_id' => 'txn-full-demo-' . $fullOrder->id,
                ]
            );

            // Unpaid example for a different order
            $pendingOrder = Order::where('code', 'CAKE-PROCESSING-1')->first();
            if ($pendingOrder) {
                Payment::updateOrCreate(
                    ['order_id' => $pendingOrder->id],
                    [
                        'payment_method' => 'card',
                        'payment_gateway' => 'stripe',
                        'payment_status' => 'unpaid',
                        'payment_date' => now()->subDays(3),
                        'paid_at' => null,
                        'amount' => (float) $pendingOrder->total_price,
                        'payload' => [
                            'confirmed_as' => null,
                        ],
                        'transaction_id' => null,
                    ]
                );
            }
        });
    }
}
