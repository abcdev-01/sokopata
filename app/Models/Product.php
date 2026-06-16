<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id', 'name', 'category', 'description', 'price', 'unit',
        'quantity', 'location', 'image', 'is_available', 'min_order_quantity', 'views_count'
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'price' => 'decimal:2',
        'quantity' => 'decimal:2'
    ];

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getAverageRatingAttribute()
    {
        try {
            return $this->reviews()->avg('rating') ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getReviewCountAttribute()
    {
        try {
            return $this->reviews()->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('is_available', true)->where('quantity', '>', 0);
    }

    public function scopeByCategory(Builder $query, ?string $category): Builder
    {
        return $category ? $query->where('category', $category) : $query;
    }
}