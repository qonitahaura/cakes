<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;

class CartService
{
    public function getOrCreateCart($user)
    {
        return Cart::firstOrCreate(
            ['user_id' => $user->id, 'status' => 'active']
        );
    }

    public function addItem($user, $data)
    {
        $cart = $this->getOrCreateCart($user);

        return CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $data['product_id'],
            'quantity' => $data['quantity'],
            'price_snapshot' => $data['price'],
        ]);
    }

    public function clearCart($cart)
    {
        $cart->items()->delete();
        $cart->update(['status' => 'converted']);
    }
}
