<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('super_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->references('id')->on('stores')->cascadeOnDelete();
            $table->string('name');
            $table->string('code_number');
            $table->string('phone_number');
            $table->string('password');
            $table->string('profile_photo_path');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('super_users');
    }
};
