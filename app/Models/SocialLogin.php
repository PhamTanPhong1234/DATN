<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialLogin extends Model
{
    use HasFactory;
    protected $table = 'social_logins';

    protected $fillable = [
        'user_id',
        'recipient_name',
        'phone_number',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country',
    ];

    public $timestamps = true;
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
