<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LichBaoCao;
use Illuminate\Support\Facades\Mail;
use App\Mail\ThongBaoHanNopBaoCao;
use Carbon\Carbon;

class ThongBaoTruocHanNop extends Command
{

    protected $signature = 'thongbao:truochan';
    protected $description = 'Gửi thông báo trước hạn nộp 3 ngày cho giảng viên phụ trách';
    public function handle()
    {
          $now = Carbon::now()->startOfDay();
        $targetDate = $now->copy()->addDays(3); // ngày cần thông báo

        $lichs = LichBaoCao::whereDate('hanNgayNop', $targetDate)->get();

        foreach ($lichs as $lich) {
            foreach ($lich->giangVienPhuTrach as $gv) {
                if ($gv->email) {
                    Mail::to($gv->email)->queue(new ThongBaoHanNopBaoCao($lich));
                    $this->info("Đã gửi mail đến: " . $gv->email);
                }
            }
        }

       
        
       

        return Command::SUCCESS;
    }
}
