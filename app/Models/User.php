<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'user_type', 'business_name',
        'business_registration_number', 'national_id', 'address', 'city',
        'profile_photo', 'is_verified', 'phone_verified_at', 'rating', 'total_transactions'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'supplier_id');
    }

    public function ordersAsBuyer()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function ordersAsSupplier()
    {
        return $this->hasMany(Order::class, 'supplier_id');
    }

    // Comment out until Review model is created
    // public function reviewsGiven()
    // {
    //     return $this->hasMany(Review::class, 'reviewer_id');
    // }

    // public function reviewsReceived()
    // {
    //     return $this->hasMany(Review::class, 'reviewee_id');
    // }

    // Comment out until Subscription model is created
    // public function subscription()
    // {
    //     return $this->hasOne(Subscription::class)->where('status', 'active');
    // }

    public function isPremiumBuyer()
    {
        return false; // Temporarily return false until subscription system is implemented
    }

    public function isPremiumSupplier()
    {
        return false; // Temporarily return false until subscription system is implemented
    }
}