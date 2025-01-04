<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    protected $table = 'CDSyncs_news';

    protected $fillable = [
        'title',
        'content',
        'category_id',
    ];

    public $timestamps = true;
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function singlenews(){
        return $this->hasOne(SingleNews::class, 'news_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(NewsComment::class, 'news_id', 'id');
    }
}
