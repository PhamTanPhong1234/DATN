<?php 

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use App\Models\Scopes\NotDeletedScope;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;

class User extends Authenticatable implements MustVerifyEmailContract
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'password',
        'email',
        'role',
        'social_id',
        'avatar',
        'is_ban',
        'email_verified_at',
        'remember_token',
        'number',
        'address',
        'is_active',
        'is_delete',
    ];

    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_ban' => 'boolean', // Cast as boolean for easy checks
        'is_active' => 'boolean',
        'is_delete' => 'boolean',
    ];

    // Relationships
    public function shippingInfos()
    {
        return $this->hasMany(ShippingInfo::class, 'user_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(CommentNews::class, 'user_id');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'user_id', 'id');
    }

    public function logLogin(){
        return $this->hasMany(LogLogin::class, 'user_id', 'id');
    }

    public function productReviews()
    {
        return $this->hasMany(ProductReview::class, 'user_id', 'id');
    }

    public function newsComments()
    {
        return $this->hasMany(NewsComment::class, 'user_id', 'id');
    }

    public function socialLogins()
    {
        return $this->hasMany(SocialLogin::class, 'user_id', 'id');
    }

    public function adminLogs()
    {
        return $this->hasMany(AdminLog::class, 'admin_id', 'id');
    }

    public function news()
    {
        return $this->hasMany(News::class, 'user_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class, 'user_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    protected static function booted()
    {
        static::addGlobalScope(new NotDeletedScope);
    }
}
