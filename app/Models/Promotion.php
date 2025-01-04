<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    // Tên bảng tương ứng trong cơ sở dữ liệu
    protected $table = 'CDSyncs_promotions';

    // Các thuộc tính có thể được gán theo cách mass-assignable
    protected $fillable = [
        'title',
        'description',
        'discount',
        'start_date',
        'end_date',
    ];

    // Phương thức để kiểm tra khuyến mãi còn hiệu lực
    public function isActive()
    {
        $currentDate = now()->toDateString();
        return $this->start_date <= $currentDate && $this->end_date >= $currentDate;
    }
}

