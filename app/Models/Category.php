<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'parent_id',
        'seller_id'
    ];

    protected $casts = [
        // No casts needed for now
    ];

    protected static function boot()
    {
        parent::boot();

        // Clear caches when category is created, updated, or deleted
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

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
