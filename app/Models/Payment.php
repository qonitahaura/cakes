<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Payment extends Model
{
    use Auditable;

    protected $fillable = [
        'order_id',
        'payment_method',
        'payment_gateway',
        'payment_status',
        'payment_date',
        'payment_url',
        'expired_at',
        'paid_at',
        'payload',
        'transaction_id',
        'amount'
    ];

    protected $casts = [
        'payload' => 'array'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
