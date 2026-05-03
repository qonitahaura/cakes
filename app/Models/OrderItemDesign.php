<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItemDesign extends Model
{
    protected $fillable = ['order_item_id', 'image_url'];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}
