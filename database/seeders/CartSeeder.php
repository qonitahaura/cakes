<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\CartItemCustomization;
use App\Models\CartItemDesign;
use App\Models\CustomizationOption;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        $customer = User::where('email', 'customer@cakes.com')->first();
        if (! $customer) {
            return;
        }

        $cart = Cart::firstOrCreate(
            ['user_id' => $customer->id, 'status' => 'active'],
            ['user_id' => $customer->id, 'status' => 'active']
        );

        // Pick one customizable + one fixed product.
        $customProduct = Product::where('is_custom', true)->first();
        $fixedProduct = Product::where('is_custom', false)->first();

        if (! $customProduct || ! $fixedProduct) {
            return;
        }

        // Create non-customizable item
        $fixedItem = CartItem::firstOrCreate(
            ['cart_id' => $cart->id, 'product_id' => $fixedProduct->id],
            [
                'quantity' => 1,
                'price_snapshot' => (float) $fixedProduct->base_price,
            ]
        );

        // Create customizable item
        $customItem = CartItem::firstOrCreate(
            ['cart_id' => $cart->id, 'product_id' => $customProduct->id],
            [
                'quantity' => 1,
                'price_snapshot' => (float) $customProduct->base_price,
            ]
        );

        // Add a design simulation (uploaded design simulation)
        CartItemDesign::firstOrCreate(
            ['cart_item_id' => $customItem->id],
            ['image_url' => 'https://picsum.photos/seed/design-' . $customItem->id . '/700/500']
        );

        // Add customization selections for the customizable cart item
        // We store cart_item_customizations rows referencing customization_id and option_id.
        $customizations = $customProduct->customizations()->withPivot(['is_required', 'max_select', 'sort_order'])->get();
        foreach ($customizations as $c) {
            // choose first available option
            $opt = $c->options()->first();
            if (! $opt) {
                continue;
            }

            CartItemCustomization::updateOrCreate(
                [
                    'cart_item_id' => $customItem->id,
                    'customization_id' => $c->id,
                    'customization_option_id' => $opt->id,
                ],
                [
                    'custom_values' => $opt->option_name,
                    'additional_price' => (float) $opt->additional_price,
                ]
            );
        }

        $this->command?->info('Cart demo seeded for customer');
    }
}
