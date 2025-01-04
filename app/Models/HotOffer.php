<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotOffer extends Model
{
    use HasFactory;

    protected $table = 'hot_offers';
    protected $fillable = [
        'name', 
        'description', 
        'discount_percentage', 
        'start_date', 
        'end_date', 
        'active', 
        'image', 
    ];
}
