<?php

namespace App\Models;

use App\Models\Scopes\NotDeletedScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';

    protected $fillable = [
        'name',
        'description',
        'image_path',
         'is_delete'
    ];
    public $timestamps = true;
    public function news()
    {
        return $this->hasMany(News::class, 'category_id', 'id');
    }
      public function products()
    {
        return $this->hasMany(Product::class);
    }
    
    protected static function booted()
    {
        static::addGlobalScope(new NotDeletedScope);
    }
    
}
