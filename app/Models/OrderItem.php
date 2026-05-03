<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class OrderItem extends Model
{
    use Auditable;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'quantity',
        'base_price',
        'custom_total_price',
        'final_price'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function customizations()
    {
        return $this->hasMany(OrderItemCustomization::class);
    }

    public function designs()
    {
        return $this->hasMany(OrderItemDesign::class);
    }
}
