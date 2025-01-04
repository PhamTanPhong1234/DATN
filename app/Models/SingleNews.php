<?php

namespace App\Models;

use App\Models\Scopes\NotDeletedScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SingleNews extends Model
{
    use HasFactory;
    protected $table = 'single_news_content';
    protected $fillable = [ 'news_id', 'content','image',"is_delete", 'created_at', 'updated_at'];

    public function news()
{
    return $this->belongsTo(News::class, 'news_id', 'id');
}
  protected static function booted()
    {
        static::addGlobalScope(new NotDeletedScope);
    }
}
