<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('CDSyncs_vouchers', function (Blueprint $table) {
            $table->id(); // Trường id tự động tăng
            $table->string('code')->unique(); // Mã voucher
            $table->decimal('discount', 10, 2); // Giá trị giảm giá
            $table->date('expiration_date'); // Ngày hết hạn
            $table->timestamps(); // Các trường created_at và updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('CDSyncs_vouchers');
    }
};
