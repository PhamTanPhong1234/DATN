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
        Schema::create('CDSyncs_news_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('CDSyncs_users');
            $table->foreignId('news_id')->constrained('CDSyncs_news');
            $table->text('comment');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('CDSyncs_news_comments');
    }
};
