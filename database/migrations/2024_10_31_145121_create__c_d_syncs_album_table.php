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
        Schema::create('CDSyncs_album', function (Blueprint $table) {
            $table->id(); // Trường id tự động tăng
            $table->string('title'); // Tên album
            $table->string('image')->nullable()->comment('Đường dẫn ảnh'); // Thêm trường image
            $table->unsignedBigInteger('artist_id'); // Trường artist_id
            $table->timestamps(); // Các trường created_at và updated_at

            // Định nghĩa khóa ngoại
            $table->foreign('artist_id')->references('id')->on('CDSyncs_artists')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('CDSyncs_album');
    }
};
