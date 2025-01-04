<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;

    // Tên bảng tương ứng trong cơ sở dữ liệu
    protected $table = 'CDSyncs_album';

    // Các thuộc tính có thể được gán theo cách mass-assignable
    protected $fillable = [
        'title',
        'image',
        'artist_id',
    ];

    // Định nghĩa mối quan hệ với bảng `CDSyncs_artists`
    public function artist()
    {
        return $this->belongsTo(Artist::class, 'artist_id');
    }
}


