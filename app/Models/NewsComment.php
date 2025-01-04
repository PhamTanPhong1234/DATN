<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsComment extends Model
{
    use HasFactory;
    protected $table = 'CDSyncs_news_comments';

    protected $fillable = [
        'user_id',
        'news_id',
        'comment',
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function news()
    {
        return $this->belongsTo(News::class, 'news_id', 'id');
    }
}
