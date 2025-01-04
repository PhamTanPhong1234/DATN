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
        Schema::create('CDSyncs_admin_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('CDSyncs_users');
            $table->string('action');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('CDSyncs_admin_logs');
    }
};
