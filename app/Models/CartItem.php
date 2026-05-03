<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class CartItem extends Model
{
    use Auditable;

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'price_snapshot'
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function customizations()
    {
        return $this->hasMany(CartItemCustomization::class);
    }

    public function designs()
    {
        return $this->hasMany(CartItemDesign::class);
    }
}
