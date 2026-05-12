<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItemCustomization extends Model
{
    protected $fillable = [
        'order_item_id',
        'customization_id',
        'customization_option_id',
        'custom_values',
        'additional_price'
    ];

    protected $casts = [
        'custom_values' => 'array'
    ];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}
