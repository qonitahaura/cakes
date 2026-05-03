<?php

namespace App\Services;

use App\Models\CartItemCustomization;

class CustomizationService
{
    public function storeCartCustomization($cartItem, $customizations)
    {
        foreach ($customizations as $cust) {
            CartItemCustomization::create([
                'cart_item_id' => $cartItem->id,
                'customization_id' => $cust['customization_id'] ?? null,
                'customization_option_id' => $cust['option_id'] ?? null,
                'custom_values' => $cust['value'] ?? null,
                'additional_price' => $cust['price'] ?? 0,
            ]);
        }
    }
}
