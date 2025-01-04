<?php

namespace App\Models;

use App\Models\Scopes\NotDeletedScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'name',
        'user_id',
        'email',
        'address',
        'phone',
        'city',
        'district',
        'commune',
        'note',
        'total_price',
        'shipping_fee',
        'final_price',
        'status',
        'order_code',
        'payment_method',
        'payment_status',
        "is_delete"
    ];
    public function orderItems()
    {
        return $this->hasMany(OrderItems::class, 'order_id');
    }
    public static function generateOrderCode()
    {
        return 'DONHANG' . strtoupper(uniqid()) . '-' . now()->format('Ymd');
    }
    protected static function booted()
    {
        static::addGlobalScope(new NotDeletedScope);
        static::creating(function ($orderItem) {
            if (empty($orderItem->order_code)) {
                $orderItem->order_code = self::generateOrderCode();
            }
        });

        static::updating(function ($orderItem) {
            if (empty($orderItem->order_code)) {
                $orderItem->order_code = self::generateOrderCode();
            }
        });
    } 
}
