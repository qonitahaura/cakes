<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customization extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'type'];

    public function options()
    {
        return $this->hasMany(CustomizationOption::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_customizations');
    }
}
