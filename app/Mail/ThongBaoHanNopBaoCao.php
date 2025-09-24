<?php 
namespace App\Mail;

use App\Models\LichBaoCao;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ThongBaoHanNopBaoCao extends Mailable
{
    use Queueable, SerializesModels;

    public $lichBaoCao;

    public function __construct(LichBaoCao $lichBaoCao)
    {
        $this->lichBaoCao = $lichBaoCao;
    }

    public function build()
    {
        return $this->subject('🔔 Thông báo lịch sinh hoạt học thuật')
                    ->view('emails.thongbao-hannopbaocao');
    }
}
