<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 'buyer_id', 'supplier_id', 'subtotal', 'commission',
        'delivery_fee', 'total_amount', 'status', 'payment_method',
        'payment_transaction_id', 'payment_released', 'payment_released_at',
        'delivery_address', 'delivered_at', 'cancellation_reason'
    ];

    protected $casts = [
        'payment_released' => 'boolean',
        'payment_released_at' => 'datetime',
        'delivered_at' => 'datetime'
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function releasePayment()
    {
        $this->payment_released = true;
        $this->payment_released_at = Carbon::now();
        $this->status = 'completed';
        $this->save();

        // Update supplier rating and transaction count
        $supplier = $this->supplier;
        if ($supplier) {
            $supplier->total_transactions++;
            $supplier->save();
        }
    }
}