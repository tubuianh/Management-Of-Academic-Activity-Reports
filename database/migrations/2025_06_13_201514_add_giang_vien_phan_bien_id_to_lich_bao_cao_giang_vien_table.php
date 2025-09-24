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
        Schema::table('lich_bao_cao_giang_vien', function (Blueprint $table) {
            $table->string('giang_vien_phan_bien_id')->nullable()->after('giang_vien_id');

            $table->foreign('giang_vien_phan_bien_id')
              ->references('maGiangVien')
              ->on('giang_viens')
              ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lich_bao_cao_giang_vien', function (Blueprint $table) {
             $table->dropForeign(['giang_vien_phan_bien_id']);
        $table->dropColumn('giang_vien_phan_bien_id');
        });
    }
};
