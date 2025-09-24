<?php

namespace App\Mail;

use App\Models\BienBanBaoCao;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\DangKyBaoCao;
class ThongBaoXacNhanBienBan extends Mailable
{
    use Queueable, SerializesModels;
    public $bienban;
    /**
     * Create a new message instance.
     */
    public function __construct(BienBanBaoCao $bienban)
    {
        $this->bienban = $bienban;
    }

    public function build()
    {
        return $this->subject('Thông báo: Kết quả xác nhận biên bản!')
                    ->view('emails.thongbao-xacnhanbienban');
    }

    /**
     * Get the message envelope.
     */
    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         subject: 'Thong Bao Dang Ky Bao Cao',
    //     );
    // }

    // /**
    //  * Get the message content definition.
    //  */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }

    // /**
    //  * Get the attachments for the message.
    //  *
    //  * @return array<int, \Illuminate\Mail\Mailables\Attachment>
    //  */
    // public function attachments(): array
    // {
    //     return [];
    // }
}
