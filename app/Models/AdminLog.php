<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    use HasFactory;
    protected $table = 'CDSyncs_admin_logs';

    protected $fillable = [
        'admin_id',
        'action',
    ];

    public $timestamps = false;
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id', 'id');
    }
}
