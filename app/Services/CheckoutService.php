<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Models\OrderItemCustomization;
use App\Models\OrderItemDesign;

class CheckoutService
{
    protected $cartService;
    protected $orderService;
    protected $paymentService;

    public function __construct(
        CartService $cartService,
        OrderService $orderService,
        PaymentService $paymentService
    ) {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
        $this->paymentService = $paymentService;
    }

    public function process($user, $data)
    {
        $cart = $this->cartService->getOrCreateCart($user);

        if ($cart->items->isEmpty()) {
            throw new \Exception('Cart kosong');
        }

        // 🔹 Create Order
        $order = $this->orderService->createOrder($user, $data);

        $total = 0;

        foreach ($cart->items as $item) {

            $finalPrice = $item->price_snapshot * $item->quantity;

            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'quantity' => $item->quantity,
                'base_price' => $item->price_snapshot,
                'custom_total_price' => 0,
                'final_price' => $finalPrice,
            ]);

            // 🔹 Copy Customization
            foreach ($item->customizations as $cust) {
                OrderItemCustomization::create([
                    'order_item_id' => $orderItem->id,
                    'customization_id' => $cust->customization_id,
                    'customization_option_id' => $cust->customization_option_id,
                    'custom_values' => $cust->custom_values,
                    'additional_price' => $cust->additional_price,
                ]);
            }

            // 🔹 Copy Design
            foreach ($item->designs as $design) {
                OrderItemDesign::create([
                    'order_item_id' => $orderItem->id,
                    'image_url' => $design->image_url,
                ]);
            }

            $total += $finalPrice;
        }

        // 🔹 Update total
        $order->update([
            'total_price' => $total + $order->delivery_fee
        ]);

        // 🔹 Payment
        $this->paymentService->createPayment($order);

        // 🔹 Clear cart
        $this->cartService->clearCart($cart);

        return $order;
    }
}
