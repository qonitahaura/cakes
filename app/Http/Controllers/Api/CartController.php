<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\CartService;
use App\Services\CustomizationService;

class CartController extends Controller
{
    public function index(CartService $cartService)
    {
        return $this->get($cartService);
    }

    public function add(Request $request, CartService $cartService, CustomizationService $custService)
    {
        $cartItem = $cartService->addItem(Auth::user(), $request->all());

        if ($request->has('customizations')) {
            $custService->storeCartCustomization($cartItem, $request->customizations);
        }

        return response()->json([
            'message' => 'Item ditambahkan ke cart',
            'data' => $cartItem
        ]);
    }

    public function get(CartService $cartService)
    {
        $cart = $cartService->getOrCreateCart(Auth::user());

        return response()->json($cart->load('items.product', 'items.customizations', 'items.designs'));
    }
}
