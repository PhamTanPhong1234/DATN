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
        Schema::create('CDSyncs_cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('CDSyncs_users');
            $table->foreignId('product_id')->constrained('CDSyncs_products');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('CDSyncs_cart_items');
    }
};
