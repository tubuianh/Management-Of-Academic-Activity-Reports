<?php

namespace App\Http\Controllers;
use App\Http\Requests\AdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Models\Admin;
use App\Models\Quyen;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\GiangVien;
use App\Models\NhanVienPDBCL;
use App\Models\LichBaoCao;
use App\Models\DangKyBaoCao;
use App\Models\BaoCao;
use App\Models\BoMon;
use App\Models\BienBanBaoCao;
use Carbon\Carbon;
use App\Models\Notification;
use App\Mail\ThongBaoDuyetDangKy;
use App\Mail\ThongBaoXacNhanBienBan;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
public function dashboard(Request $request)
{
    $now = Carbon::now();

    // Ngày lọc từ form
    $fromDate = $request->input('from_date') ? Carbon::parse($request->input('from_date'))->startOfDay() : null;
    $toDate = $request->input('to_date') ? Carbon::parse($request->input('to_date'))->endOfDay() : null;

    // Mặc định: tháng và kỳ
    $startOfMonth = $now->copy()->startOfMonth();
    $endOfMonth = $now->copy()->endOfMonth();
    $startOfSemester = $now->month <= 6
        ? Carbon::create($now->year, 1, 1)
        : Carbon::create($now->year, 7, 1);

    // Áp dụng điều kiện ngày lọc nếu có
    $baoCaoQuery = BaoCao::query();
    $bienBanQuery = BienBanBaoCao::query();
    $lichBaoCaoQuery = LichBaoCao::query();
    $phieuDangKyQuery = DangKyBaoCao::query();
    if ($fromDate && $toDate) {
        $baoCaoQuery->whereBetween('created_at', [$fromDate, $toDate]);
        $bienBanQuery->whereBetween('created_at', [$fromDate, $toDate]);
        $phieuDangKyQuery->whereBetween('created_at', [$fromDate, $toDate]);
        $lichBaoCaoQuery->whereBetween('created_at', [$fromDate, $toDate]);
    }

    $notifications = collect();

    // Phiếu đăng ký được duyệt gần nhất
    $phieuDangKy = DangKyBaoCao::where('trangThai', 'Đã Xác Nhận')->latest()->first();
    if ($phieuDangKy) {
        $notifications->push([
            'icon' => 'fas fa-check',
            'bg' => 'success',
            'text' => 'Phiếu đăng ký SHHT được xác nhận',
            'time' => $phieuDangKy->created_at->diffForHumans(),
        ]);
    }
    
    // Giảng viên mới đăng ký gần nhất
    $giangVien = GiangVien::latest()->first();
    if ($giangVien) {
        $notifications->push([
            'icon' => 'fas fa-user',
            'bg' => 'info',
            'text' => 'Giảng viên mới được thêm',
            'time' => $giangVien->created_at->diffForHumans(),
        ]);
    }

    $bienBan = BienBanBaoCao::where('trangThai', 'Đã Xác Nhận')->latest()->first();
    if ($bienBan) {
        $notifications->push([
            'icon' => 'fas fa-check',
            'bg' => 'info',
            'text' => 'Biên bản mới được xác nhận',
            'time' => $bienBan->created_at->diffForHumans(),
        ]);
    }
    
    // Lịch báo cáo mới được tạo gần nhất
    $lich = LichBaoCao::latest()->first();
    if ($lich) {
        $notifications->push([
            'icon' => 'fas fa-calendar',
            'bg' => 'warning',
            'text' => 'Lịch báo cáo mới được tạo',
            'time' => $lich->created_at->diffForHumans(),
        ]);
    }
    
    // Sắp xếp lại theo thời gian (mới nhất trên cùng)
    $notifications = $notifications->sortByDesc('time')->values();


    return view('admin.dashboard', [
        'tongGiangVien' => GiangVien::count(),
        'tongNhanVien' => NhanVienPDBCL::count(),
        'tongAdmin' => Admin::count(),
        'baoCaoTheoThang' => BaoCao::selectRaw('MONTH(created_at) as thang, COUNT(*) as soLuong')
        ->groupBy('thang')
        ->orderBy('thang')
        ->pluck('soLuong', 'thang'),
        'baoCaoTheoBoMon' => BoMon::withCount(['giangViens as soLuongBaoCao' => function ($query) use ($fromDate, $toDate) {
            $query->join('bao_caos', 'giang_viens.maGiangVien', '=', 'bao_caos.giangVien_id');
            if ($fromDate && $toDate) {
                $query->whereBetween('bao_caos.created_at', [$fromDate, $toDate]);
            }
        }])->get()->pluck('soLuongBaoCao', 'tenBoMon'),
        'notifications' => $notifications,


        'tongBaoCao' => $baoCaoQuery->count(),
        'tongBienBan' => $bienBanQuery->count(),
        'tongLichBaoCao' => $lichBaoCaoQuery->count(),
        'tongPhieuDangKy' => $phieuDangKyQuery->count(),
        'bienBanDuocXacNhan' => $bienBanQuery->where('trangThai', 'Đã Xác Nhận')->count(),
        'phieuDuocXacNhan' => $phieuDangKyQuery->where('trangThai', 'Đã Xác Nhận')->count(),

        'baoCaoNgay' => BaoCao::selectRaw('DATE(created_at) as ngay, COUNT(*) as soLuong')
            ->when($fromDate && $toDate, fn($q) => $q->whereBetween('created_at', [$fromDate, $toDate]))
            ->groupBy('ngay')
            ->orderBy('ngay')
            ->pluck('soLuong', 'ngay')
    ]);
}

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getDataTable();
        }
        
        return view('admin.index');
    }

    private function getDataTable()
    {
        $data = Admin::query(); 

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('quyen', function ($row) {
                return $row->quyen ? $row->quyen->tenQuyen : 'Không có quyền';
            })
            ->addColumn('ho_ten', function ($row) {
                return $row->ho . ' ' . $row->ten; // Ghép họ và tên
            })
            ->addColumn('hanhdong', function ($row) {
                return view('components.action-buttons', [
                    'row' => $row,
                    'editRoute' => 'admin.edit',
                    'deleteRoute' => 'admin.destroy',
                    'id' => $row->maAdmin
                ])->render();
            })
            ->rawColumns(['hanhdong'])
            ->make(true);
    }

    public function create()
    {
        $quyens = Quyen::all();
        return view('admin.create', compact('quyens'));
    }

    public function store(AdminRequest $request)
    {
        $data = $request->validated();
        $data['matKhau'] = bcrypt($request->matKhau);
        Admin::create($data);
        return redirect()->route('admin.index')->with('success', 'Thêm admin thành công');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Admin $admin)
    {
        $quyens = Quyen::all();
        return view('admin.edit', compact('admin', 'quyens'));
    }

    public function update(UpdateAdminRequest $request, $maAdmin)
    {
        $admin = Admin::findOrFail($maAdmin);
        $data = $request->validated();
        if ($request->filled('matKhau')) {
            $data['matKhau'] = bcrypt($request->matKhau);
        }else {
            unset($data['matKhau']);
        }
        $admin->update($data);   
        
        return redirect()->route('admin.index')->with('success', 'Cập nhật admin thành công');
    }


    public function destroy(Admin $admin)
    {
        $admin->delete();
        return redirect()->route('admin.index')->with('success', 'Xóa admin thành công');
    }

    public function indexLichAdmin(Request $request)
{
    if ($request->ajax()) {
        return $this->getDataTableLich();
    }

    return view('admin.ds_lich');
}

private function getDataTableLich()
{
    $lichBaoCaos = LichBaoCao::with('giangVienPhuTrach', 'boMon');

    return DataTables::of($lichBaoCaos)
        ->addIndexColumn()
        ->editColumn('gioBaoCao', function ($row) {
            return Carbon::parse($row->gioBaoCao)->format('h:i') . 
                   ( Carbon::parse($row->gioBaoCao)->format('A') == 'AM' ? ' SA' : ' CH');
        })
        ->editColumn('hanGioNop', function ($row) {
            return Carbon::parse($row->hanGioNop)->format('h:i') . 
                   ( Carbon::parse($row->hanGioNop)->format('A') == 'AM' ? ' SA' : ' CH');
        })
        ->addColumn('capToChuc', function($row) {

            if (optional($row->boMon)->tenBoMon) {
                return 'Cấp Bộ môn - ' . $row->boMon->tenBoMon;
            }

            return 'Cấp Khoa - ' . (optional($row->giangVien->boMon->khoa)->tenKhoa ?? '[Không rõ]');
        })
        ->addColumn('giangVienPhuTrach', function($lich) {
            return $lich->giangVienPhuTrach->map(function($gv) {
                return $gv->ho . ' ' . $gv->ten;
            })->implode(', ');
        })
        ->addColumn('hanhdong', function($lich) {
            return view('components.action-buttons', [
                'row' => $lich,
                'editRoute' => 'lichbaocao.edit',
                'deleteRoute' => 'lichbaocao.destroy',
                'id' => $lich->maLich
            ])->render();
        })
        ->rawColumns(['hanhdong'])
        ->make(true);
}

public function indexBaoCaoAdmin(Request $request)
{
    if ($request->ajax()) {
        return $this->getDataTableBaoCao();
    }

    return view('admin.ds_bao_cao');
}
    
    private function getDataTableBaoCao()
    {

    $data = BaoCao::query();

    return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('ngayNop', function($row) {
            return Carbon::parse($row->ngayNop)->format('d/m/Y');
        })
        ->addColumn('file', function($row) {
            if ($row->duongDanFile) {
                return '<a href="'.asset($row->duongDanFile).'" target="_blank">Tải file</a>';
            }
            return 'Không có file';
        })
        ->addColumn('hanhdong', function($row) {
            $downloadLink = '';
            if ($row->duongDanFile) {
                $downloadLink = '<a href="'.asset($row->duongDanFile).'" class="btn btn-sm btn-info" target="_blank"><i class="fas fa-download"></i> Tải File</a>';
            }
        
            $deleteButton = view('components.action-buttons', [
                'row' => $row,
                'editRoute' => null, // Không cần route edit
                'deleteRoute' => 'baocao.destroy',
                'id' => $row->maBaoCao
            ])->render();
        
            return '<div class="d-flex gap-1">'.$downloadLink .'</div>';

        })
        ->rawColumns(['hanhdong'])
        ->make(true);
    }

    public function indexPhieuDKAdmin(Request $request)
    {
        if ($request->ajax()) {
            return $this->getDataTablePhieuDK();
        }

        return view('admin.ds_phieu_dk');
    }
    
    private function getDataTablePhieuDK()
{
    $data = DangKyBaoCao::with([
        'baoCaos.giangVien',
        'lichBaoCao.boMon.khoa',
        'lichBaoCao.giangVienPhuTrach.baoCao',
        'giangVien.boMon.khoa'
    ]);

    return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('chuDe', function($row) {
            return $row->lichBaoCao->chuDe ?? '[Không rõ]';
        })
        ->addColumn('ngayDangKy', function($row) {
            return Carbon::parse($row->ngayDangKy)->format('d/m/Y');
        })
         ->addColumn('trangThai', function ($row) {
            $color = match($row->trangThai) {
                'Chờ Xác Nhận' => 'warning',
                'Đã Xác Nhận' => 'success',
                'Từ Chối' => 'danger',
                default => 'secondary',
            };
            return '<span style="font-size:14px" class="badge bg-' . $color . '">' . $row->trangThai . '</span>';
        })
        ->addColumn('ngayGioBaoCao', function($row) {
            if ($row->lichBaoCao) {
                $ngay = Carbon::parse($row->lichBaoCao->ngayBaoCao)->format('d/m/Y');
                $gio = $row->lichBaoCao->gioBaoCao;
                return "$ngay - $gio";
            }
            return '[Không rõ]';
        })
       ->addColumn('diaDiem', function($row) {
            $lich = optional($row->lichBaoCao); // giúp tránh lỗi nếu null

            if ($lich->boMon) {
                return 'VP BM ' . ($lich->boMon->tenBoMon ?? '[Không rõ]');
            }

            return 'VP Khoa ' . ($lich->giangVien->boMon->khoa->tenKhoa ?? '[Không rõ]');
        })
       ->addColumn('capToChuc', function($row) {
            $lich = optional($row->lichBaoCao);

            if (optional($lich->boMon)->tenBoMon) {
                return 'Cấp Bộ môn - ' . $lich->boMon->tenBoMon;
            }

            return 'Cấp Khoa - ' . (optional($lich->giangVien->boMon->khoa)->tenKhoa ?? '[Không rõ]');
        })

        ->addColumn('baoCaoVien', function($row) {
            return $row->baoCaos->map(function($bc) {
                return $bc->giangVien->ho . ' ' . $bc->giangVien->ten;
            })->implode('<br>');
        })
        ->addColumn('fileBaoCao', function($row) {
            return $row->baoCaos->map(function($bc) {
                return '<li>'.'<a href="'.asset($bc->duongDanFile).'" target="_blank">'.$bc->tenBaoCao.'</a>'.'</li>';
            })->implode('<br>');
        })
        ->addColumn('hanhDong', function($row) {
            $deleteForm = view('components.action-buttons', [
                'row' => $row,
                'xacNhanRoute' => 'xacnhanPhieu.xacNhanPhieu',
                'tuChoiRoute' => 'xacnhanPhieu.tuChoiPhieu',
                'id' => $row->maDangKyBaoCao
            ])->render();

            return $deleteForm;
        })
        ->rawColumns(['baoCaoVien', 'fileBaoCao','trangThai', 'hanhDong'])
        ->make(true);
}

public function indexBienBanAdmin(Request $request)
{
    if ($request->ajax()) {
        return $this->getDatatableBienBan();
    }
    return view('admin.ds_bien_ban');
}

public function getDatatableBienBan()
{
    $bienbans = BienBanBaoCao::with('lichBaoCao.boMon.khoa')->latest('ngayNop');

    return datatables()->of($bienbans)
        ->addIndexColumn()
        ->addColumn('chuDe', function ($row) {
            return $row->lichBaoCao->chuDe ?? 'Không rõ';
        })
        ->addColumn('ngayNop', function ($row) {
            return Carbon::parse($row->ngayNop)->format('d/m/Y H:i');
        })
       
        ->addColumn('capToChuc', function($row) {
            $lich = optional($row->lichBaoCao);

            if (optional($lich->boMon)->tenBoMon) {
                return 'Cấp Bộ môn - ' . $lich->boMon->tenBoMon;
            }

            return 'Cấp Khoa - ' . (optional($lich->giangVien->boMon->khoa)->tenKhoa ?? '[Không rõ]');
        })
        ->addColumn('trangThai', function ($row) {
            $color = match($row->trangThai) {
                'Chờ Xác Nhận' => 'warning',
                'Đã Xác Nhận' => 'success',
                'Từ Chối' => 'danger',
                default => 'secondary',
            };
            return '<span style="font-size:14px" class="badge bg-' . $color . '">' . $row->trangThai . '</span>';
        })
        ->addColumn('hanhdong', function ($row) {
            $viewBtn = '<a href="' . asset($row->fileBienBan) . '" target="_blank" style="margin-bottom:5px" class="btn btn-sm btn-primary"><i class="fas fa-download"></i> Tải</a>';
            $deleteForm = view('components.action-buttons', [
                'row' => $row,
                'xacNhanRoute' => 'xacnhanBB.xacNhanBienBan',
                'tuChoiRoute' => 'xacnhanBB.tuChoiBienBan',
                'id' => $row->maBienBan
            ])->render();
            return $viewBtn . $deleteForm;
        })
        ->rawColumns(['trangThai', 'hanhdong'])
        ->make(true);
}


    public function duyetPhieu($maDangKy)
    {
        $dangKy = DangKyBaoCao::findOrFail($maDangKy);
        $dangKy->trangThai = 'Đã Xác Nhận';
        $dangKy->save();
         Notification::create([
            'loai' => 'xac_nhan_phieu',
            'noiDung' => 'Có phiếu đăng ký sinh hoạt học thuật đã được xác nhận!',
            'link' => route('dangkybaocao.index'),
            'doiTuong' => 'truong_bo_mon'
        ]);

         // Gửi email 
         $lich = $dangKy->lichBaoCao;
         $gv = $lich->giangVien;
         if ($gv->email) {
            Mail::to($gv->email)->queue(new ThongBaoDuyetDangKy($dangKy));
        }
        return redirect()->route('admin.ds_phieu_dk')->with('success', 'Xác nhận phiếu thành công!');
    }

    public function tuChoiPhieu($maDangKy)
    {
        $dangKy = DangKyBaoCao::findOrFail($maDangKy);
        $dangKy->trangThai = 'Từ Chối';
        $dangKy->save();
         Notification::create([
            'loai' => 'xac_nhan_phieu',
            'noiDung' => 'Có phiếu đăng ký sinh hoạt học thuật bị từ chối!',
            'link' => route('dangkybaocao.index'),
            'doiTuong' => 'truong_bo_mon'
        ]);
         // Gửi email cho tất cả giảng viên liên quan
         foreach ($dangKy->baoCaos as $bc) {
            $gv = $bc->giangVien;
            if ($gv->email) {
                Mail::to($gv->email)->queue(new ThongBaoDuyetDangKy($dangKy));
            }
        }

         return redirect()->route('admin.ds_phieu_dk')->with('success', 'Từ chối phiếu thành công!');
    }

    public function xacNhanBB($maBienBan)
    {
        $bienban = BienBanBaoCao::findOrFail($maBienBan);
        $bienban->trangThai = 'Đã Xác Nhận';
        $bienban->save();
         Notification::create([
            'loai' => 'xac_nhan_bien_ban',
            'noiDung' => 'Có biên bản sinh hoạt học thuật đã được xác nhận!',
            'link' => route('bienban.index'),
            'doiTuong' => 'truong_bo_mon'
        ]);
         //Gửi email cho tất cả giảng viên liên quan
         $gv = $bienban->giangVien;
         if ($gv->email) {
             Mail::to($gv->email)->queue(new ThongBaoXacNhanBienBan($bienban));
         }

        return redirect()->route('admin.ds_bien_ban')->with('success', 'Xác nhận biên bản thành công!');
    }

    public function tuChoiBB($maBienBan)
    {
        $bienban = BienBanBaoCao::findOrFail($maBienBan);
        $bienban->trangThai = 'Từ Chối';
        $bienban->save();
         Notification::create([
            'loai' => 'xac_nhan_bien_ban',
            'noiDung' => 'Có biên bản sinh hoạt học thuật bị từ chối!',
            'link' => route('bienban.index'),
            'doiTuong' => 'truong_bo_mon'
        ]);
         // Gửi email cho tất cả giảng viên liên quan
         $gv = $bienban->giangVien();
         if ($gv->email) {
             Mail::to($gv->email)->queue(new ThongBaoXacNhanBienBan($bienban));
         }

        return redirect()->route('admin.ds_bien_ban')->with('success', 'Từ chối biên bản thành công!');
    }


}
