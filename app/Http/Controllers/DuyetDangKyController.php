<?php
namespace App\Http\Controllers;
use App\Models\DangKyBaoCao;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\NotificationUser;
use App\Mail\ThongBaoDuyetDangKy;
use Illuminate\Support\Facades\Mail;

class DuyetDangKyController extends Controller
{
    public function index()
    {
        
        $dangKyBaoCaos = DangKyBaoCao::where('trangThai', 'Chờ Xác Nhận')
            ->with(['baoCaos.giangVien', 'lichBaoCao.boMon.khoa'])
            ->paginate(6);
        
        return view('nhanvien.duyet.index', compact('dangKyBaoCaos'));
    }

    public function duyet($maDangKy)
    {
        $dangKy = DangKyBaoCao::findOrFail($maDangKy);
        $dangKy->trangThai = 'Đã Xác Nhận';
        $dangKy->save();
        $notification = Notification::create([
            'loai' => 'xac_nhan_phieu',
            'noiDung' => 'Có phiếu đăng ký sinh hoạt học thuật đã được xác nhận!',
            'link' => route('dangkybaocao.index'),
            'doiTuong' => 'truong_bo_mon'
        ]);

        NotificationUser::create([
                'notification_id' => $notification->id,
                'user_id' => (int) $dangKy->giangVien_id,
                'guard_name' => 'giang_viens',
                'daDoc' => false,
        ]);

         // Gửi email 
         $lich = $dangKy->lichBaoCao;
         $gv = $lich->giangVien;
         if ($gv->email) {
            Mail::to($gv->email)->queue(new ThongBaoDuyetDangKy($dangKy));
        }
        return redirect()->back()->with('success', 'Đã xác nhận phiếu đăng ký sinh hoạt học thuật!');
    }

    public function tuChoi($maDangKy)
    {
        $dangKy = DangKyBaoCao::findOrFail($maDangKy);
        $dangKy->trangThai = 'Từ Chối';
        $dangKy->save();
        $notification = Notification::create([
            'loai' => 'xac_nhan_phieu',
            'noiDung' => 'Có phiếu đăng ký sinh hoạt học thuật bị từ chối!',
            'link' => route('dangkybaocao.index'),
            'doiTuong' => 'truong_bo_mon'
        ]);
        NotificationUser::create([
                'notification_id' => $notification->id,
                'user_id' => (int) $dangKy->giangVien_id,
                'guard_name' => 'giang_viens',
                'daDoc' => false,
        ]);
         // Gửi email cho tất cả giảng viên liên quan
         foreach ($dangKy->baoCaos as $bc) {
            $gv = $bc->giangVien;
            if ($gv->email) {
                Mail::to($gv->email)->queue(new ThongBaoDuyetDangKy($dangKy));
            }
        }

        return redirect()->back()->with('success', 'Đã từ chối phiếu đăng ký sinh hoạt học thuật!');
    }

    public function daDuyet()
    {
        $dangKyBaoCaos = DangKyBaoCao::where('trangThai', 'Đã Xác Nhận')
            ->with(['baoCaos.giangVien', 'lichBaoCao.boMon.khoa'])
            ->paginate(6);

        return view('nhanvien.duyet.daduyet', compact('dangKyBaoCaos'));
    }

}
