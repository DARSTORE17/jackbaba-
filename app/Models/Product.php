<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

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
