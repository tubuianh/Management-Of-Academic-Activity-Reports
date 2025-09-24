<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DongTacGia extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'dong_tac_gias';

    protected $fillable = [
        'maBaoCao',
        'giangVienNop_id',
        'giangVienDongTacGia_id',
    ];

    public function baoCao()
    {
        return $this->belongsTo(BaoCao::class, 'maBaoCao', 'maBaoCao');
    }

    public function giangVienNop()
    {
        return $this->belongsTo(GiangVien::class, 'giangVienNop_id', 'maGiangVien');
    }

    public function giangVienDongTacGia()
    {
        return $this->belongsTo(GiangVien::class, 'giangVienDongTacGia_id', 'maGiangVien');
    }
}
