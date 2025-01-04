<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtistImage extends Model
{
    use HasFactory;
    protected $table = 'image_artists';
    protected $fillable = [
        'artist_id',
        'image_path',
        
    ];
      public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

}
