<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;

class Product extends Model
{
    use Auditable;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'base_price',
        'category_id',
        'image_url',
        'is_available',
        'is_custom'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function customizations()
    {
        return $this->belongsToMany(Customization::class, 'product_customizations')
            ->withPivot(['is_required', 'max_select', 'sort_order']);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
