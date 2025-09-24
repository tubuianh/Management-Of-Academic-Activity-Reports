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
        Schema::create('dong_tac_gias', function (Blueprint $table) {
            $table->unsignedInteger('maBaoCao');
            $table->string('giangVienNop_id');
            $table->string('giangVienDongTacGia_id');

            // Foreign keys
            $table->foreign('maBaoCao')->references('maBaoCao')->on('bao_caos')->onDelete('cascade');
            $table->foreign('giangVienNop_id')->references('maGiangVien')->on('giang_viens')->onDelete('cascade');
            $table->foreign('giangVienDongTacGia_id')->references('maGiangVien')->on('giang_viens')->onDelete('cascade');

            // Composite primary key (không để trùng đồng tác giả với cùng báo cáo)
            $table->primary(['maBaoCao', 'giangVienDongTacGia_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dong_tac_gias');
    }
};
