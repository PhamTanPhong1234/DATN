<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    use HasFactory;
    protected $fillable = ['order_id', 'user_id','product_id', 'quantity', 'price'];
    protected $table = 'orders_items';

  public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }



    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
