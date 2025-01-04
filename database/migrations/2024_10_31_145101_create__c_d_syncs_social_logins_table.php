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
        Schema::create('CDSyncs_social_logins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('CDSyncs_users');
            $table->string('provider');
            $table->string('social_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('CDSyncs_social_logins');
    }
};
