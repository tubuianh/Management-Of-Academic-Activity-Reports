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
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('user_type'); // 'admins', 'giang_viens', 'nhan_vien_p_d_b_c_ls'
            $table->string('user_ma');   // maAdmin / maGiangVien / maNV
            $table->string('session_id')->unique();
            $table->timestamp('last_activity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_sessions');
    }
};
