<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeaturedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('featured_products')) {
            Schema::create('featured_products', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id');
                $table->integer('views')->default(0);
                $table->integer('purchases')->default(0);
                $table->timestamps();
    
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('featured_products');
    }
}
