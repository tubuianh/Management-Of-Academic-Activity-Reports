<?php

namespace App\Http\Controllers;

use App\Models\LichBaoCao;
use App\Models\BaoCao;
use App\Models\Khoa;
use App\Models\GiangVien;
use App\Models\NhanVienPDBCL;
use App\Models\Notification;
use App\Models\NotificationUser;
use App\Models\BoMon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\LichBaoCaoRequest;
use App\Mail\ThongBaoLichBaoCao;
use App\Mail\DangKySHHTMail;
use App\Mail\HuyDangKySHHTMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class LichBaoCaoController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
    
        $query = LichBaoCao::with(['boMon', 'giangVienPhuTrach','baoCaos.giangVien','giangVien']);
        

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('chuDe', 'like', '%' . $keyword . '%')
                  ->orWhere('ngayBaoCao', 'like', '%' . $keyword . '%')
                  ->orWhere('gioBaoCao', 'like', '%' . $keyword . '%')
                  ->orWhere('hanNgayNop', 'like', '%' . $keyword . '%')
                  ->orWhere('hanGioNop', 'like', '%' . $keyword . '%')
                  ->orWhereHas('boMon', function ($subQ) use ($keyword) {
                      $subQ->where('tenBoMon', 'like', '%' . $keyword . '%');
                  });
            });
             // Tìm giảng viên phụ trách (separate condition)
             $query->orWhereHas('giangVienPhuTrach', function ($subQ) use ($keyword) {
                $subQ->whereRaw("CONCAT(ho, ' ', ten) LIKE ?", ['%' . $keyword . '%']);
            });
            
            
        }
    
        $dsLichBaoCao = $query->orderByDesc('ngayBaoCao')->paginate(6);
    
        return view('lichbaocao.index', compact('dsLichBaoCao', 'keyword'));
    }
    

public function getBaoCaoTheoLich($maLich)
{
    $baoCaos = BaoCao::where('lich_bao_cao_id', $maLich)
        ->with('giangVien')
        ->get()
        ->map(function ($bc) {
            return [
                'tenBaoCao' => $bc->tenBaoCao,
                'giangVien' => $bc->giangVien->ho . ' ' . $bc->giangVien->ten,
                'duongDanFile' => asset($bc->duongDanFile), // nếu lưu trong storage
            ];
        });

    return response()->json($baoCaos);
}


    public function create()
    {
        $giangViens = GiangVien::all();
        $guard = session('current_guard');
        $user = Auth::guard($guard)->user();
        $maGv = $user->maGiangVien;
        $chucVu = $user->chucVu;
        // $boMons = BoMon::all();
        $boMons = collect(); // khởi tạo rỗng
         if ($chucVu === 'TBM') { // Trưởng bộ môn
        $boMon = BoMon::where('truongBoMon', $maGv)->first();
        if ($boMon) {
            $boMons->push($boMon); // chỉ thêm bộ môn của TBM
        }
        } elseif ($chucVu === 'TK') { // Trưởng khoa
            $khoa = Khoa::where('truongKhoa', $maGv)->first();
            if ($khoa) {
                $boMons = $khoa->boMons; // lấy toàn bộ bộ môn trong khoa đó
            }
        } else {
            // Nếu muốn cho các vai trò khác xem tất cả bộ môn:
            $boMons = BoMon::all();
        }

        $giangViens = GiangVien::all(); // Có thể lọc lại nếu cần
        $cap = null;
        $khoa = null;

        if ($chucVu === 'TK') {
            $cap = 'bo_mon'; // mặc định là cấp bộ môn
            $khoa = Khoa::where('truongKhoa', $maGv)->first();
        }
        return view('lichbaocao.create', compact('giangViens', 'boMons','maGv','cap', 'khoa'));
        
    }

    public function store(LichBaoCaoRequest $request)
    {

        // dd(request()->all());

        $guard = session('current_guard');
        $user = Auth::guard($guard)->user();
        $maGv = $user->maGiangVien;
        $data = $request->validated();
        $lichBaoCao = LichBaoCao::create([
            'ngayBaoCao' => $data['ngayBaoCao'],
            'gioBaoCao' => $data['gioBaoCao'],
            'chuDe' => $data['chuDe'],
            'giangVienPhuTrach_id' => $maGv,
            'hanNgayNop' => $data['hanNgayNop'],
            'hanGioNop' => $data['hanGioNop'],
            'boMon_id' => $data['boMon_id'],        
        ]);
        // Gán nhiều giảng viên vào lịch báo cáo
        $lichBaoCao->giangVienPhuTrach()->sync($request->giangVienPhuTrach);

       
        if ($request->filled('boMon_id')) {
            // Cấp bộ môn: key là uid = boMon_maGiangVien
            foreach ($request->phanBien as $uid => $phanBienId) {
                if (!empty($phanBienId)) {
                    // Tách UID để lấy lại mã giảng viên
                    [$boMonId, $maGV] = explode('_', $uid);

                    $lichBaoCao->giangVienPhuTrach()->updateExistingPivot($maGV, [
                        'giang_vien_phan_bien_id' => $phanBienId
                    ]);
                }
            }
        } else {
            // Cấp khoa: key là maGiangVien bình thường
            foreach ($request->giangVienPhuTrach as $maGV) {
                $phanBienId = $request->phanBien[(string) $maGV] ?? null;
                if ($phanBienId) {
                    $lichBaoCao->giangVienPhuTrach()->updateExistingPivot($maGV, [
                        'giang_vien_phan_bien_id' => $phanBienId
                    ]);
                }
            }
        }

        
        // $notificationGv = Notification::create([
        //     'loai' => 'lich',
        //     'noiDung' => 'Có lịch sinh hoạt học thuật mới!',
        //     'link' => route('lichbaocao.index'),
        //     'doiTuong' => 'giang_vien',
            
        // ]);
        // $giangViens = GiangVien::all();

        // foreach ($giangViens as $gv) {
        //     NotificationUser::create([
        //         'notification_id' => $notificationGv->id,
        //         'user_id' => (int) $gv->maGiangVien,
        //         'guard_name' => 'giang_viens',
        //         'daDoc' => false,
        //     ]);
        // }

        // $notificationNv = Notification::create([
        //     'loai' => 'lich',
        //     'noiDung' => 'Có lịch sinh hoạt học thuật mới!',
        //     'link' => route('lichbaocao.index'),
        //     'doiTuong' => 'nhan_vien'
        // ]);

        // $pdbcls = NhanVienPDBCL::all();
        // foreach ($pdbcls as $nv) {
        //     NotificationUser::create([
        //         'notification_id' => $notificationNv->id,
        //         'user_id' => (int) $nv->maNV,
        //         'guard_name' => 'nhan_vien_p_d_b_c_ls',
        //         'daDoc' => false,
        //     ]);
        // }

        // foreach ($lichBaoCao->giangVienPhuTrach as $gv) {
        //     Mail::to($gv->email)->queue(new ThongBaoLichBaoCao($lichBaoCao));
        // }
        
        return redirect()->route('lichbaocao.index')->with('success', 'Lịch báo cáo được tạo thành công.');
    }


    public function show(LichBaoCao $lichBaoCao)
    {
        //
    }


    public function edit($maLich)
    {
        $guard = session('current_guard');
        $user = Auth::guard($guard)->user();
        $maGv = $user->maGiangVien;
        $lich = LichBaoCao::with('giangVienPhuTrach')->where('maLich', $maLich)->firstOrFail();
        $giangViens = GiangVien::where('boMon_id', $lich->boMon_id)->get();
        $boMons = BoMon::all();
        $tenKhoa = $user->boMon?->khoa?->tenKhoa ?? 'Chưa xác định';

        $lichdk = $lich;
        $lichdk = LichBaoCao::withCount('dangKyBaoCaos')->findOrFail($maLich);
        if ($lichdk->dang_ky_bao_caos_count > 0) {
            return redirect()->route('lichbaocao.index')
                ->with('error', 'Không thể sửa lịch vì đã đăng ký tổ chức sinh hoạt học thuật cho lịch này rồi!');
        }
         // RÀNG BUỘC: Chỉ giảng viên phụ trách mới được sửa
        if ($lich->giangVienPhuTrach_id != $maGv) {
            return redirect()->route('lichbaocao.index')
                ->with('error', 'Không thể sửa lịch này vì bạn không phải là người tạo lịch này!');
        }

         // Xác định cấp khoa
        $isCapKhoa = $lich->boMon_id == null;

        // Nếu cấp khoa → lấy toàn bộ giảng viên trong khoa
        if ($isCapKhoa) {
            // Lấy tất cả giảng viên thuộc các bộ môn
            $giangViens = $boMons->flatMap->giangViens;
        } else {
            // Chỉ lấy giảng viên trong bộ môn tương ứng
            $giangViens = GiangVien::where('boMon_id', $lich->boMon_id)->get();
        }
        $giangVienPhuTrach = [];
$giangVienPhanBien = [];

foreach ($lich->giangVienPhuTrach as $gv) {
    $maGV = $gv->maGiangVien;
    $giangVienPhuTrach[] = $maGV;

    // Tạo UID theo định dạng bạn dùng trong view, ví dụ: "boMon_maGV" hoặc chỉ "maGV"
    $boMon = $gv->boMon?->maBoMon ?? ''; // nếu cần phân biệt bộ môn
    $uid = $boMon ? $boMon . '_' . $maGV : $maGV;

    if ($gv->pivot->giang_vien_phan_bien_id) {
        $giangVienPhanBien[$uid] = $gv->pivot->giang_vien_phan_bien_id;
    }
}
        return view('lichbaocao.edit', compact('lich', 'giangViens', 'boMons','isCapKhoa','tenKhoa','giangVienPhanBien','giangVienPhuTrach'));
    }
    

    public function update(LichBaoCaoRequest $request, $maLich)
    {
      
    
        $lich = LichBaoCao::where('maLich', $maLich)->firstOrFail();

        $lich->update($request->validated());
        $lich->giangVienPhuTrach()->sync($request->giangVienPhuTrach ?? []);
        return redirect()->route('lichbaocao.index')->with('success', 'Lịch báo cáo đã được cập nhật.');
    }
    
    // API lấy danh sách giảng viên theo bộ môn (Dùng AJAX)
    public function getGiangVien($boMon_id)
{
    $giangViens = GiangVien::where('boMon_id', $boMon_id)->get();

    if ($giangViens->isEmpty()) {
        return response()->json(['message' => 'Không có giảng viên nào'], 404);
    }

    return response()->json($giangViens);
}
    
    public function destroy($maLich)
    {
        $guard = session('current_guard');
        $user = Auth::guard($guard)->user();
        $maGv = $user->maGiangVien;
        $lich = LichBaoCao::withCount('dangKyBaoCaos')->findOrFail($maLich);
        
         // Chỉ cho xóa nếu người đăng nhập là giảng viên lên lịch đó
        if ($lich->giangVienPhuTrach_id != $maGv) {
            return redirect()->route('lichbaocao.index')
                ->with('error', 'Không thể xóa lịch này vì bạn không phải là người tạo lịch này!');
        }
        // Nếu có ít nhất 1 đăng ký báo cáo thì không cho xóa
        if ($lich->dang_ky_bao_caos_count > 0) {
            return redirect()->route('lichbaocao.index')
                ->with('error', 'Không thể xóa lịch vì đã đăng ký tổ chức sinh hoạt học thuật cho lịch này rồi!');
        }
    
        $lich->delete();
    
        return redirect()->route('lichbaocao.index')
            ->with('success', 'Lịch báo cáo đã được xóa.');
    }
    
    
public function dangKyView()
{
    // $lichBaoCaos = LichBaoCao::with('boMon', 'giangVienPhuTrach')->get();
    $giangVien = Auth::guard('giang_viens')->user();

    // Lấy các lịch báo cáo mà giảng viên đã đăng ký
    $lichDaDangKy = DB::table('lich_bao_cao_giang_vien')
                        ->where('giang_vien_id', $giangVien->maGiangVien)
                        ->pluck('lich_bao_cao_id')->toArray();
    
    // Lấy lịch báo cáo có ngày báo cáo > ngày hiện tại  3 ngày
    $lichBaoCaos = LichBaoCao::with('boMon', 'giangVienPhuTrach')
                            ->where('ngayBaoCao', '>', Carbon::now()->addDays(4)) // Ngày báo cáo phải lớn hơn 4 ngày sau hôm nay
                            ->paginate(6);

    return view('lichbaocaodangky.dangky', compact('lichBaoCaos', 'lichDaDangKy'));
}

public function dangKySubmit(Request $request)
{
    $request->validate([
        'lich_bao_cao_id' => 'required|exists:lich_bao_caos,maLich',
    ]);

    $giangVienDangKy = Auth::guard('giang_viens')->user();

    // Kiểm tra xem giảng viên đã đăng ký lịch này chưa
    $daDangKy = DB::table('lich_bao_cao_giang_vien')
        ->where('lich_bao_cao_id', $request->lich_bao_cao_id)
        ->where('giang_vien_id', $giangVienDangKy->maGiangVien)
        ->exists();

    if ($daDangKy) {
        return redirect()->back()->with('error', 'Bạn đã đăng ký lịch này rồi.');
    }

    // Thêm bản ghi vào bảng pivot
    DB::table('lich_bao_cao_giang_vien')->insert([
        'lich_bao_cao_id' => $request->lich_bao_cao_id,
        'giang_vien_id' => $giangVienDangKy->maGiangVien,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Gửi email cho giảng viên lên lịch
    $lichBaoCao = LichBaoCao::with(['giangVien', 'boMon.khoa'])->findOrFail($request->lich_bao_cao_id);
    $giangVienLenLich = $lichBaoCao->giangVien;

    if ($giangVienLenLich && $giangVienLenLich->email) {
        Mail::to($giangVienLenLich->email)->queue(new DangKySHHTMail($lichBaoCao, $giangVienDangKy));
    }


    return redirect()->back()->with('success', 'Đăng ký lịch thành công.');
}

public function huyDangKy(Request $request)
{
    $request->validate([
        'lich_bao_cao_id' => 'required|exists:lich_bao_caos,maLich',
    ]);

    $giangVienHuyDangKy = Auth::guard('giang_viens')->user();

    // Kiểm tra xem giảng viên đã đăng ký lịch này chưa
    $daDangKy = DB::table('lich_bao_cao_giang_vien')
        ->where('lich_bao_cao_id', $request->lich_bao_cao_id)
        ->where('giang_vien_id', $giangVienHuyDangKy->maGiangVien)
        ->exists();

    if (!$daDangKy) {
        return redirect()->back()->with('error', 'Hủy đăng ký thành công!');
    }

    // Hủy đăng ký
    DB::table('lich_bao_cao_giang_vien')
        ->where('lich_bao_cao_id', $request->lich_bao_cao_id)
        ->where('giang_vien_id', $giangVienHuyDangKy->maGiangVien)
        ->delete();

    // Gửi email cho giảng viên lên lịch
    $lichBaoCao = LichBaoCao::with(['giangVien', 'boMon.khoa'])->findOrFail($request->lich_bao_cao_id);
    $giangVienLenLich = $lichBaoCao->giangVien;

    if ($giangVienLenLich && $giangVienLenLich->email) {
        Mail::to($giangVienLenLich->email)->queue(new HuyDangKySHHTMail($lichBaoCao, $giangVienHuyDangKy));
    }


    return redirect()->back()->with('success', 'Hủy đăng ký thành công.');
}


 
    

}
