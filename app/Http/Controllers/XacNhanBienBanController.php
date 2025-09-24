<?php
namespace App\Http\Controllers;
use App\Models\BienBanBaoCao;
use App\Models\Notification;
use App\Models\NotificationUser;
use Illuminate\Http\Request;
use App\Mail\ThongBaoXacNhanBienBan;
use Illuminate\Support\Facades\Mail;

class XacNhanBienBanController extends Controller
{
    public function index()
    {
        $bienBanBaoCaos = BienBanBaoCao::where('trangThai', 'Chờ Xác Nhận')->paginate(6);
        return view('nhanvien.xacnhanbienban.index', compact('bienBanBaoCaos'));
    }

    public function xacNhan($maBienBan)
    {
        $bienban = BienBanBaoCao::findOrFail($maBienBan);
        $bienban->trangThai = 'Đã Xác Nhận';
        $bienban->save();
        $notification = Notification::create([
            'loai' => 'xac_nhan_bien_ban',
            'noiDung' => 'Có biên bản sinh hoạt học thuật đã được xác nhận!',
            'link' => route('bienban.index'),
            'doiTuong' => 'truong_bo_mon'
        ]);
        NotificationUser::create([
                'notification_id' => $notification->id,
                'user_id' => (int) $bienban->giangVien_id,
                'guard_name' => 'giang_viens',
                'daDoc' => false,
            ]);
         // Gửi email cho tất cả giảng viên liên quan
         $gv = $bienban->giangVien;
         if ($gv->email) {
             Mail::to($gv->email)->queue(new ThongBaoXacNhanBienBan($bienban));
         }

        return redirect()->back()->with('success', 'Đã xác nhận biên bản thành công!');
    }

    public function tuChoi($maBienBan)
    {
        $bienban = BienBanBaoCao::findOrFail($maBienBan);
        $bienban->trangThai = 'Từ Chối';
        $bienban->save();
        $notification = Notification::create([
            'loai' => 'xac_nhan_bien_ban',
            'noiDung' => 'Có biên bản sinh hoạt học thuật bị từ chối!',
            'link' => route('bienban.index'),
            'doiTuong' => 'truong_bo_mon'
        ]);
         NotificationUser::create([
                'notification_id' => $notification->id,
                'user_id' => (int) $bienban->giangVien_id,
                'guard_name' => 'giang_viens',
                'daDoc' => false,
            ]);
         // Gửi email cho tất cả giảng viên liên quan
         $gv = $bienban->giangVien();
         if ($gv->email) {
             Mail::to($gv->email)->queue(new ThongBaoXacNhanBienBan($bienban));
         }

        return redirect()->back()->with('success', 'Đã từ chối biên bản thành công!.');
    }

    public function daXacNhan()
    {
        $bienBanBaoCaos = BienBanBaoCao::where('trangThai', 'Đã Xác Nhận')->paginate(6);
        return view('nhanvien.xacnhanbienban.daxacnhan', compact('bienBanBaoCaos'));
    }

}
