<?php

namespace App\Models;

use App\Models\Scopes\IsActiveScope;
use App\Models\Scopes\NotDeletedScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category_id',
        'active',
        'artist_id',
        'is_delete',
        "mp3_path"
    ];

    public $timestamps = true;

    protected $casts = [
        'active' => 'boolean',
    ];

    // Relationships
 
      public function categories()
    {
          return $this->belongsTo(Category::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    // Accessors & Mutators
    public function getStatusAttribute()
    {
        return $this->active ? 'active' : 'no active';
    }

    // Global Scopes
    protected static function booted()
    {
        static::addGlobalScope(new IsActiveScope);
        static::addGlobalScope(new NotDeletedScope);
    }
}
