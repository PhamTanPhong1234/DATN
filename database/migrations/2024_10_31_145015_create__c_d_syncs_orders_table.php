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
        Schema::create('CDSyncs_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('CDSyncs_users');
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['pending', 'processed', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('CDSyncs_orders');
    }
};
