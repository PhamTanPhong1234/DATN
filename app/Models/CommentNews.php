<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentNews extends Model
{
    use HasFactory;
    protected $table = 'comment_news';

    protected $fillable = [
        'user_id',
        'news_id',
        'content',
        'parent_id'
    ];

     public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // user_id là khóa ngoại trong bảng comment_news
    }
     public function parent()
    {
        return $this->belongsTo(CommentNews::class, 'parent_id'); // parent_id là khóa ngoại
    }

    public function replies()
    {
        return $this->hasMany(CommentNews::class, 'parent_id'); // Trả về các bình luận trả lời
    }
}
