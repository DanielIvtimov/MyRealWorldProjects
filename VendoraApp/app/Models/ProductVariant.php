<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductImage;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Support\Str;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'sku',
        'name',
        'options',
        'price',
        'compare_price',
        'stock_quantity',
        'stock_status',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'array',
            'price' => 'decimal:2',
            'compare_price' => 'decimal:2',
            'stock_quantity' => 'integer',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
    #[Scope()]
    protected function active(Builder $query): void
    {
        $query->where('is_active', true);
    }
    #[Scope()]
    protected function inStock(Builder $query): void
    {
        $query->where('stock_status', 'in_stock')->where('stock_quantity', '>', 0);
    }
    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function orderItems()
    {
        $this->hasMany(OrderItem::class);
    }
    // Helper Methods
    public function getDiscountPercentageAttribute()
    {
        if($this->compare_price && $this->compare_price > $this->price){
            return round((($this->compare_price - $this->price) / $this->compare_price) * 100);
        }
        return 0;
    }
    // Event
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function($variant): void {
            if(empty($variant->sku)){
                $variant->sku = 'SKU-' . strtoupper(Str::random(8));
            }
        });
    }
}
