<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomizationOption extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customization_id',
        'option_name',
        'additional_price'
    ];

    public function customization()
    {
        return $this->belongsTo(Customization::class);
    }
}
