<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductRating;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'short_description',
        'shipping_returns',
        'related_products',
        'price',
        'compare_price',
        'category_id',
        'sub_category_id',
        'brand_id',
        'is_featured',
        'sku',
        'barcode',
        'track_qty',
        'qty',
        'status',
    ];

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function product_ratings()
    {
        return $this->hasMany(ProductRating::class)->where('status', 1);
    }
}
