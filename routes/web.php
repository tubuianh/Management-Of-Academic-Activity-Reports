<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KhoaController;
use App\Http\Controllers\BoMonController;
use App\Http\Controllers\ChucVuController;
use App\Http\Controllers\GiangVienController;
use App\Http\Controllers\NhanVienPDBCLController;
use App\Http\Controllers\LichBaoCaoController;
use App\Http\Controllers\BaoCaoController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BienBanController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\DangKyBaoCaoController;
use App\Http\Controllers\DuyetDangKyController;
use App\Http\Controllers\QuenMatKhauController;
use App\Http\Controllers\XacNhanBienBanController;
use App\Http\Controllers\QuyenController;
use App\Models\BienBanBaoCao;
use App\Http\Controllers\EmailSettingController;
use App\Http\Controllers\NotificationController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/forgot-password', [QuenMatKhauController::class, 'showForgotForm'])->name('password.request');


Route::post('/forgot-password', [QuenMatKhauController::class, 'sendVerificationCode'])->name('password.email');


Route::get('/reset-password', [QuenMatKhauController::class, 'showResetForm'])->name('password.reset');


Route::post('/reset-password', [QuenMatKhauController::class, 'resetPassword'])->name('password.update');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', function () {
    if (Auth::guard('admins')->check()) {
        Auth::guard('admins')->logout();
    } elseif (Auth::guard('giang_viens')->check()) {
        Auth::guard('giang_viens')->logout();
    } elseif (Auth::guard('nhan_vien_p_d_b_c_ls')->check()) {
        Auth::guard('nhan_vien_p_d_b_c_ls')->logout();
    }

    Session::flush();
    return redirect()->route('login');
})->name('logout');





//Thông báo
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::post('/notifications/mark-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.read');
Route::delete('/notifications/delete', [NotificationController::class, 'delete'])->name('notifications.delete');
Route::post('notifications/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
Route::post('/notifications/markAllAsRead', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
Route::delete('/notifications', [NotificationController::class, 'delete'])->name('notifications.delete');

Route::middleware(['custom.session','auth:admins', 'kiemtraquyen'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/ds_lich', [AdminController::class, 'indexLichAdmin'])->name('admin.ds_lich');
    Route::get('/ds_bao_cao', [AdminController::class, 'indexBaoCaoAdmin'])->name('admin.ds_bao_cao');
    Route::get('/ds_phieu_dk', [AdminController::class, 'indexPhieuDKAdmin'])->name('admin.ds_phieu_dk');
    Route::get('/ds_bien_ban', [AdminController::class, 'indexBienBanAdmin'])->name('admin.ds_bien_ban');

    Route::prefix('nhanvien')->group(function () {
        Route::get('/', [NhanVienPDBCLController::class, 'index'])->name('nhanvien.index');
        Route::get('/create', [NhanVienPDBCLController::class, 'create'])->name('nhanvien.create');
        Route::post('/store', [NhanVienPDBCLController::class, 'store'])->name('nhanvien.store');
        Route::get('/{maNV}/edit', [NhanVienPDBCLController::class, 'edit'])->name('nhanvien.edit');
        Route::put('/{maNV}', [NhanVienPDBCLController::class, 'update'])->name('nhanvien.update');
        Route::delete('/{maNV}', [NhanVienPDBCLController::class, 'destroy'])->name('nhanvien.destroy');
    });
    Route::prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.index'); // Danh sách Admin
        Route::get('/create', [AdminController::class, 'create'])->name('admin.create'); // Form thêm
        Route::post('/store', [AdminController::class, 'store'])->name('admin.store'); // Lưu Admin mới
        Route::get('/{admin}/edit', [AdminController::class, 'edit'])->name('admin.edit'); // Form sửa
        Route::put('/{maAdmin}', [AdminController::class, 'update'])->name('admin.update'); // Cập nhật Admin
        Route::delete('/{admin}', [AdminController::class, 'destroy'])->name('admin.destroy'); // Xóa Admin
        Route::get('/email-settings', [EmailSettingController::class, 'index'])->name('email-settings.index');
        Route::get('/email-settings/form', [EmailSettingController::class, 'form'])->name('email-settings.form');
        Route::post('/email-settings/save', [EmailSettingController::class, 'save'])->name('email-settings.save');
        Route::post('/email-settings/test', [EmailSettingController::class, 'sendTestEmail'])->name('email-settings.test');
        
    });

    

    Route::prefix('giangvien')->group(function () {
        Route::get('/', [GiangVienController::class, 'index'])->name('giangvien.index');
        Route::get('/create', [GiangVienController::class, 'create'])->name('giangvien.create');
        Route::post('/store', [GiangVienController::class, 'store'])->name('giangvien.store');
        Route::get('/{maGiangVien}/edit', [GiangVienController::class, 'edit'])->name('giangvien.edit');
        Route::put('/{maGiangVien}', [GiangVienController::class, 'update'])->name('giangvien.update');
        Route::delete('/{maGiangVien}', [GiangVienController::class, 'destroy'])->name('giangvien.destroy');
        Route::post('/import', [GiangVienController::class, 'import'])->name('giangvien.import');
        

    });
    
    Route::prefix('khoa')->group(function () {
        Route::get('/', [KhoaController::class, 'index'])->name('khoa.index'); 
        Route::get('/create', [KhoaController::class, 'create'])->name('khoa.create'); 
        Route::post('/store', [KhoaController::class, 'store'])->name('khoa.store'); 
        Route::get('/{khoa}/edit', [KhoaController::class, 'edit'])->name('khoa.edit'); 
        Route::put('/{khoa}', [KhoaController::class, 'update'])->name('khoa.update'); 
        Route::delete('/{khoa}', [KhoaController::class, 'destroy'])->name('khoa.destroy'); 
    });
    
    Route::prefix('chucvu')->group(function () {
        Route::get('/', [ChucVuController::class, 'index'])->name('chucvu.index');
        Route::get('/create', [ChucVuController::class, 'create'])->name('chucvu.create');
        Route::post('/store', [ChucVuController::class, 'store'])->name('chucvu.store');
        Route::get('/{chucvu}/edit', [ChucVuController::class, 'edit'])->name('chucvu.edit');
        Route::put('/{chucvu}', [ChucVuController::class, 'update'])->name('chucvu.update');
        Route::delete('/{chucvu}', [ChucVuController::class, 'destroy'])->name('chucvu.destroy');
    });
    
    Route::prefix('bomon')->group(function () {
        Route::get('/', [BoMonController::class, 'index'])->name('bomon.index'); // Danh sách bộ môn
        Route::get('/create', [BoMonController::class, 'create'])->name('bomon.create'); // Form thêm bộ môn
        Route::post('/store', [BoMonController::class, 'store'])->name('bomon.store'); // Lưu bộ môn mới
        Route::get('/{bomon}/edit', [BoMonController::class, 'edit'])->name('bomon.edit'); // Form sửa bộ môn
        Route::put('/{bomon}', [BoMonController::class, 'update'])->name('bomon.update'); // Cập nhật bộ môn
        Route::delete('/{bomon}', [BoMonController::class, 'destroy'])->name('bomon.destroy'); // Xóa bộ môn
    });

    Route::prefix('quyen')->group(function () {
        Route::get('/', [QuyenController::class, 'index'])->name('quyen.index'); // Danh sách bộ môn
        Route::get('/create', [QuyenController::class, 'create'])->name('quyen.create'); // Form thêm bộ môn
        Route::post('/store', [QuyenController::class, 'store'])->name('quyen.store'); // Lưu bộ môn mới
        Route::get('/{quyen}/edit', [QuyenController::class, 'edit'])->name('quyen.edit'); // Form sửa bộ môn
        Route::put('/{quyen}', [QuyenController::class, 'update'])->name('quyen.update'); // Cập nhật bộ môn
        Route::delete('/{quyen}', [QuyenController::class, 'destroy'])->name('quyen.destroy'); // Xóa bộ môn
    });
   
});
Route::prefix('xacnhanBB')->group(function () {
        Route::post('/{maBienBan}/xac-nhanBB', [AdminController::class, 'xacNhanBB'])->name('xacnhanBB.xacNhanBienBan');
        Route::post('/{maBienBan}/tu-choi', [AdminController::class, 'tuChoiBB'])->name('xacnhanBB.tuChoiBienBan');
    });
Route::prefix('xacnhanPhieu')->group(function () {
        Route::post('/{maDangKy}/xac-nhan', [AdminController::class, 'duyetPhieu'])->name('xacnhanPhieu.xacNhanPhieu');
        Route::post('/{maDangKy}/tu-choi', [AdminController::class, 'tuChoiPhieu'])->name('xacnhanPhieu.tuChoiPhieu');
    });

Route::middleware(['giangvien_or_nhanvien', 'session.timeout'])->group(function () {
    Route::get('/user/dashboard', function () {
        return view('user.dashboard');
    })->name('user.dashboard');
   Route::get('/thong-ke/export', [NhanVienPDBCLController::class, 'exportThongKeExcel'])->name('thongke.export');


    Route::get('/user/thongke', [NhanVienPDBCLController::class, 'thongKeBaoCao'])->name('nhanvien.thongke');
    Route::get('user/thongke/giangvien', [NhanVienPDBCLController::class, 'giangVienTK'])->name('nhanvien.thongke.giangvien');
    Route::get('/user/thongke/bomon', [NhanVienPDBCLController::class, 'boMonTK'])->name('nhanvien.thongke.bomon');
    Route::get('/user/thongke/khoa', [NhanVienPDBCLController::class, 'khoaTK'])->name('nhanvien.thongke.khoa');
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/dsgv', [GiangVienController::class, 'dsGiangVien'])->name('giangvien.dsgv');
    Route::get('/giang-vien/bo-mon', [GiangVienController::class, 'giangVienBoMon'])->name('giangVien.boMon');

    Route::prefix('lichbaocao')->group(function () {
        Route::get('/', [LichBaoCaoController::class, 'index'])->name('lichbaocao.index');
        Route::get('/create', [LichBaoCaoController::class, 'create'])->name('lichbaocao.create');
        Route::post('/store', [LichBaoCaoController::class, 'store'])->name('lichbaocao.store');
        Route::get('/{lichbaocao}/edit', [LichBaoCaoController::class, 'edit'])->name('lichbaocao.edit');
        Route::put('/{lichbaocao}', [LichBaoCaoController::class, 'update'])->name('lichbaocao.update');
        Route::delete('/{lichbaocao}', [LichBaoCaoController::class, 'destroy'])->name('lichbaocao.destroy');
        Route::get('/giangviens/{boMon_id}', [LichBaoCaoController::class, 'getGiangVien'])->name('lichbaocao.getGiangVien');// api lấy giảng viên từ bộ môn
        Route::get('/{lichbaocao}/baocao', [LichBaoCaoController::class, 'getBaoCaoTheoLich'])->name('lichbaocao.baocao');
       
    });

});

Route::middleware(['auth:giang_viens','session.timeout','kiemtraquyen'])->group(function () {    
   
    Route::prefix('lichbaocaodangky')->group(function () {
        Route::get('/dangky', [LichBaoCaoController::class, 'dangKyView'])->name('lichbaocaodangky.dangky');
        Route::post('/dangky', [LichBaoCaoController::class, 'dangKySubmit'])->name('lichbaocaodangky.dangky.submit');
        Route::post('/huy-dang-ky', [LichBaoCaoController::class, 'huyDangKy'])->name('lichbaocaodangky.huy');
    });


    Route::prefix('quan-ly-bao-cao')->group(function () {
        Route::get('/', [BaoCaoController::class, 'index'])->name('baocao.index'); // Xem danh sách báo cáo
        Route::get('/create', [BaoCaoController::class, 'create'])->name('baocao.create'); // Trang tạo báo cáo mới
        Route::post('/', [BaoCaoController::class, 'store'])->name('baocao.store'); // Lưu báo cáo mới
        Route::get('/{maBaoCao}/edit', [BaoCaoController::class, 'edit'])->name('baocao.edit'); // Trang chỉnh sửa báo cáo
        Route::put('/{maBaoCao}', [BaoCaoController::class, 'update'])->name('baocao.update'); // Cập nhật báo cáo
        Route::delete('/{maBaoCao}', [BaoCaoController::class, 'destroy'])->name('baocao.destroy'); // Xóa báo cáo
    });

    Route::prefix('dangkybaocao')->group(function () {
        Route::get('/', [DangKyBaoCaoController::class, 'index'])->name('dangkybaocao.index');
        Route::get('/create', [DangKyBaoCaoController::class, 'create'])->name('dangkybaocao.create');
        Route::post('/store', [DangKyBaoCaoController::class, 'store'])->name('dangkybaocao.store');
        Route::delete('/{maDangKyBaoCao}', [DangKyBaoCaoController::class, 'destroy'])->name('dangkybaocao.destroy');
    
        // API lấy chi tiết lịch báo cáo
        Route::get('/get-lich/{id}', [DangKyBaoCaoController::class, 'getLichBaoCao'])->name('dangkybaocao.getLichBaoCao');
        Route::get('/export/{lich_id}', [DangKyBaoCaoController::class, 'exportPhieu'])
     ->name('dangkybaocao.export');
    });

    Route::prefix('bienban')->group(function () {
        Route::get('/', [BienBanController::class, 'index'])->name('bienban.index'); // Xem danh sách báo cáo
        Route::get('/create', [BienBanController::class, 'create'])->name('bienban.create'); // Trang tạo báo cáo mới
        Route::post('/', [BienBanController::class, 'store'])->name('bienban.store'); // Lưu báo cáo mới
        Route::get('/{maBienBan}/edit', [BienBanController::class, 'edit'])->name('bienban.edit'); // Trang chỉnh sửa báo cáo
        Route::put('/{maBienBan}', [BienBanController::class, 'update'])->name('bienban.update'); // Cập nhật báo cáo
        Route::delete('/{maBienBan}', [BienBanController::class, 'destroy'])->name('bienban.destroy'); // Xóa báo cáo

    });

});
// 
Route::middleware(['auth:nhan_vien_p_d_b_c_ls', 'session.timeout','kiemtraquyen'])->group(function () {  

    Route::prefix('duyet')->group(function () {
        Route::get('/', [DuyetDangKyController::class, 'index'])->name('duyet.index');
        Route::post('/{maDangKy}/duyet', [DuyetDangKyController::class, 'duyet'])->name('duyet.duyet');
        Route::post('/{maDangKy}/tu-choi', [DuyetDangKyController::class, 'tuChoi'])->name('duyet.tuchoi');
        Route::get('/da-duyet', [DuyetDangKyController::class, 'daDuyet'])->name('duyet.daduyet');

    });
    

    Route::prefix('xacnhan')->group(function () {
        Route::get('/', [XacNhanBienBanController::class, 'index'])->name('xacnhan.index');
        Route::post('/{maBienBan}/xac-nhan', [XacNhanBienBanController::class, 'xacNhan'])->name('xacnhan.xacnhan');
        Route::post('/{maBienBan}/tu-choi', [XacNhanBienBanController::class, 'tuChoi'])->name('xacnhan.tuchoi');
        Route::get('/da-xac-nhan', [XacNhanBienBanController::class, 'daXacNhan'])->name('xacnhan.daxacnhan');

    });
});



