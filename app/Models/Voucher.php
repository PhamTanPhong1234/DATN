<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    // Tên bảng tương ứng trong cơ sở dữ liệu
    protected $table = 'vouchers';

    // Các thuộc tính có thể được gán theo cách mass-assignable
    protected $fillable = [
        'code',
        'discount',
        'expiration_date',
    ];

    // Kiểm tra voucher còn hạn hay không
    public function isValid()
    {
        return $this->expiration_date >= now();
    }
}
