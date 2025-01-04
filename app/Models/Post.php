<?php

namespace App\Models;

use App\Models\Scopes\NotDeletedScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
    protected $table = 'news'; 


    protected $fillable = [
        'title',
        'content',
        'user_id',
        'status',
        'image_path',
        "is_delete"
    ]; 
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
public function allNews(){
    return $this->hasMany(SingleNews::class, 'news_id', 'id');
}
    protected $casts = [ 'status' => 'boolean'];
    public function getStatusAttribute($value) { return $value ? 'active' : 'no active'; }
    protected static function booted()
    {
        static::addGlobalScope(new NotDeletedScope);
    }
}
