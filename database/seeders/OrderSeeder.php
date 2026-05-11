<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemCustomization;
use App\Models\OrderItemDesign;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customer = User::where('email', 'customer@cakes.com')->first();
        if (! $customer) {
            return;
        }

        $customProduct = Product::where('is_custom', true)->first();
        $fixedProduct = Product::where('is_custom', false)->first();
        if (! $customProduct || ! $fixedProduct) {
            return;
        }

        DB::transaction(function () use ($customer, $customProduct, $fixedProduct) {
            $codes = [
                ['code' => 'CAKE-PENDING-1', 'status' => 'pending'],
                ['code' => 'CAKE-PROCESSING-1', 'status' => 'processing'],
                ['code' => 'CAKE-COMPLETED-1', 'status' => 'completed'],
            ];

            foreach ($codes as $i => $cfg) {
                $order = Order::firstOrCreate(
                    ['code' => $cfg['code']],
                    [
                        'user_id' => $customer->id,
                        'order_date' => now()->subDays(5 + $i),
                        'total_price' => 0,
                        'status' => $cfg['status'],
                        'fulfillment_type' => 'pickup',
                        'pickup_date' => now()->addDays(1 + $i),
                        'pickup_time' => now()->addHours(2 + $i)->format('H:i:s'),
                        'delivery_date' => null,
                        'delivery_time' => null,
                        'delivery_address' => null,
                        'delivery_fee' => 0,
                        'note' => $i === 0 ? 'Please call on arrival' : null,
                    ]
                );

                // Ensure at least 2 items per order.
                $this->seedOrderItem($order, $fixedProduct, 1, false);
                $this->seedOrderItem($order, $customProduct, 1, true);

                // Update total_price
                $total = $order->items()->sum(DB::raw('final_price'));
                $order->update(['total_price' => (float) $total]);
            }
        });
    }

    private function seedOrderItem(Order $order, Product $product, int $qty, bool $isCustomizable): void
    {
        $item = OrderItem::firstOrCreate(
            ['order_id' => $order->id, 'product_id' => $product->id],
            [
                'product_name' => $product->name,
                'quantity' => $qty,
                'base_price' => (float) $product->base_price,
                'custom_total_price' => 0,
                'final_price' => (float) $product->base_price * $qty,
            ]
        );

        if (! $isCustomizable) {
            return;
        }

        $customizations = $product->customizations()->with('options')->get();
        $customTotal = 0;

        foreach ($customizations as $c) {
            $opt = $c->options()->first();
            if (! $opt) {
                continue;
            }

            $additional = (float) $opt->additional_price;
            $customTotal += $additional;

            OrderItemCustomization::updateOrCreate(
                [
                    'order_item_id' => $item->id,
                    'customization_id' => $c->id,
                    'customization_option_id' => $opt->id,
                ],
                [
                    'custom_values' => $opt->option_name,
                    'additional_price' => $additional,
                ]
            );
        }

        // Add design image simulation
        OrderItemDesign::firstOrCreate(
            ['order_item_id' => $item->id],
            ['image_url' => 'https://picsum.photos/seed/order-design-' . $item->id . '/800/450']
        );

        $final = ((float) $product->base_price * $qty) + $customTotal;
        $item->update([
            'custom_total_price' => (float) $customTotal,
            'final_price' => (float) $final,
        ]);
    }
}
