<?php

namespace App\Models;

use App\Models\Scopes\NotDeletedScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Artist extends Model
{
    use HasFactory;

    protected $table = 'artists';

    protected $fillable = [
        'name',
        'bio',
        'status',
        'image_path',
        'is_delete'
    ];

    // Định nghĩa mối quan hệ với bảng `CDSyncs_album`
    public function albums()
    {
        return $this->hasMany(Album::class, 'artist_id');
    }
     public function products()
    {
        return $this->hasMany(Product::class);
    }
      public function images()
    {
        return $this->hasMany(ArtistImage::class);
    }
     protected $casts = [ 'status' => 'boolean'];
    public function getStatusAttribute($value) { return $value ? 'active' : 'no active'; }
    protected static function booted()
    {
        static::addGlobalScope(new NotDeletedScope);
    }
}

