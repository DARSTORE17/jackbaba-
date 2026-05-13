<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'seller_id',
        'name',
        'slug',
        'old_price',
        'new_price',
        'discount',
        'rate',
        'stock',
        'initial_stock',
        'thumbnail',
        'is_advertised',
        'vat_enabled',
        'vat_rate',
        'delivery_payment',
        'delivery_fee',
    ];

    protected $casts = [
        'is_advertised' => 'boolean',
        'vat_enabled' => 'boolean',
        'old_price' => 'decimal:2',
        'new_price' => 'decimal:2',
        'vat_rate' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'initial_stock' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        // Clear category caches when product is created, updated, or deleted
        // (since product counts in categories will change)
        static::saved(function () {
            Cache::forget('shop_categories_with_stock');
            Cache::forget('shop_all_categories');
            Cache::forget('footer_categories');
        });

        static::deleted(function () {
            Cache::forget('shop_categories_with_stock');
            Cache::forget('shop_all_categories');
            Cache::forget('footer_categories');
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id')->where('role', 'seller');
    }

    public function media()
    {
        return $this->hasMany(ProductMedia::class);
    }

    public function description()
    {
        return $this->hasOne(ProductDescription::class);
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->old_price && $this->old_price > 0) {
            return round((($this->old_price - $this->new_price) / $this->old_price) * 100);
        }
        return 0;
    }
}
