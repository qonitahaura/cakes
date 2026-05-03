<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Order extends Model
{
    use Auditable;

    protected $fillable = [
        'user_id',
        'code',
        'order_date',
        'total_price',
        'status',
        'fulfillment_type',
        'pickup_date',
        'pickup_time',
        'delivery_date',
        'delivery_time',
        'delivery_address',
        'delivery_fee',
        'note'
    ];

    protected $casts = [
        'delivery_address' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
