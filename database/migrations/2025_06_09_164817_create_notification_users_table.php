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
        Schema::create('notification_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notification_id')->constrained('notifications')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->string('guard_name'); // 'giang_viens', 'nhan_vien_p_d_b_c_ls'
            $table->boolean('daDoc')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_users');
    }
};
