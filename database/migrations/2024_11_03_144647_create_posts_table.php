

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
        Schema::create('CDSyncs_album_artists', function (Blueprint $table) {
            $table->id(); // Trường id tự động tăng
            $table->unsignedBigInteger('album_id'); // Trường album_id
            $table->unsignedBigInteger('artist_id'); // Trường artist_id
            $table->string('image')->nullable()->comment('Đường dẫn ảnh'); // Thêm trường image
            $table->timestamps(); // Tạo các trường created_at và updated_at

            // Định nghĩa khóa ngoại
            $table->foreign('album_id')->references('id')->on('CDSyncs_album')->onDelete('cascade');
            $table->foreign('artist_id')->references('id')->on('CDSyncs_artists')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('CDSyncs_album_artists');
    }
};
