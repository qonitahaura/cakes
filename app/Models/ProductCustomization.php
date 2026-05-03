<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCustomization extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'customization_id',
        'is_required',
        'max_select',
        'sort_order'
    ];
}
