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
        Schema::create('CDSyncs_promotions', function (Blueprint $table) {
            $table->id(); // Trường id tự động tăng
            $table->string('title'); // Tiêu đề khuyến mãi
            $table->text('description')->nullable(); // Mô tả khuyến mãi
            $table->decimal('discount', 10, 2); // Giá trị giảm giá
            $table->date('start_date'); // Ngày bắt đầu
            $table->date('end_date'); // Ngày kết thúc
            $table->timestamps(); // Các trường created_at và updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('CDSyncs_promotions');
    }
};
