<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogLogin extends Model
{
    use HasFactory;
 
     
    protected $table = 'login_logs';
    protected $fillable = ['user_id', 'ip_address', 'device'];
}
