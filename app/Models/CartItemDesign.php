<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItemDesign extends Model
{
    protected $fillable = ['cart_item_id', 'image_url'];

    public function cartItem()
    {
        return $this->belongsTo(CartItem::class);
    }
}
