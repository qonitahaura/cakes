<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\CartItem;

class CartItemCustomization extends Model
{
    protected $fillable = [
        'cart_item_id',
        'customization_id',
        'customization_option_id',
        'custom_values',
        'additional_price'
    ];

    protected $casts = [
        'custom_values' => 'array'
    ];

    public function cartItem()
    {
        return $this->belongsTo(CartItem::class);
    }
}
