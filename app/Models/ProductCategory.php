<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;
    protected $table = 'CDSyncs_product_categories';

    protected $fillable = [
        'name',
        'description',
    ];
    public $timestamps = true;

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
}
