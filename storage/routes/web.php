<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KhoaController;
use App\Http\Controllers\BoMonController;
use App\Http\Controllers\ChucVuController;
use App\Http\Controllers\GiangVienController;
use App\Http\Controllers\NhanVienPDBCLController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('login', [AuthController::class, 'showLoginForm'])->name('auth.login');


Route::get('/', function () {
    return redirect()->route('admin.dashboard'); 
});

// Hoặc nếu có trang dashboard admin riêng
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

//Routes CRUD Admin
Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index'); // Danh sách Admin
    Route::get('/create', [AdminController::class, 'create'])->name('admin.create'); // Form thêm
    Route::post('/store', [AdminController::class, 'store'])->name('admin.store'); // Lưu Admin mới
    Route::get('/{admin}/edit', [AdminController::class, 'edit'])->name('admin.edit'); // Form sửa
    Route::put('/{admin}', [AdminController::class, 'update'])->name('admin.update'); // Cập nhật Admin
    Route::delete('/{admin}', [AdminController::class, 'destroy'])->name('admin.destroy'); // Xóa Admin
});


Route::prefix('giangvien')->group(function () {
    Route::get('/', [GiangVienController::class, 'index'])->name('giangvien.index');
    Route::get('/create', [GiangVienController::class, 'create'])->name('giangvien.create');
    Route::post('/store', [GiangVienController::class, 'store'])->name('giangvien.store');
    Route::get('/{giangvien}/edit', [GiangVienController::class, 'edit'])->name('giangvien.edit');
    Route::put('/{giangvien}', [GiangVienController::class, 'update'])->name('giangvien.update');
    Route::delete('/{giangvien}', [GiangVienController::class, 'destroy'])->name('giangvien.destroy');
});

// Routes CRUD Khoa
Route::prefix('khoa')->group(function () {
    Route::get('/', [KhoaController::class, 'index'])->name('khoa.index'); 
    Route::get('/create', [KhoaController::class, 'create'])->name('khoa.create'); 
    Route::post('/store', [KhoaController::class, 'store'])->name('khoa.store'); 
    Route::get('/{khoa}/edit', [KhoaController::class, 'edit'])->name('khoa.edit'); 
    Route::put('/{khoa}', [KhoaController::class, 'update'])->name('khoa.update'); 
    Route::delete('/{khoa}', [KhoaController::class, 'destroy'])->name('khoa.destroy'); 
});

// Routes CRUD Chuc Vu
Route::prefix('chucvu')->group(function () {
    Route::get('/', [ChucVuController::class, 'index'])->name('chucvu.index');
    Route::get('/create', [ChucVuController::class, 'create'])->name('chucvu.create');
    Route::post('/store', [ChucVuController::class, 'store'])->name('chucvu.store');
    Route::get('/{chucvu}/edit', [ChucVuController::class, 'edit'])->name('chucvu.edit');
    Route::put('/{chucvu}', [ChucVuController::class, 'update'])->name('chucvu.update');
    Route::delete('/{chucvu}', [ChucVuController::class, 'destroy'])->name('chucvu.destroy');
});

// Routes CRUD Bo Mon
Route::prefix('bomon')->group(function () {
    Route::get('/', [BoMonController::class, 'index'])->name('bomon.index'); // Danh sách bộ môn
    Route::get('/create', [BoMonController::class, 'create'])->name('bomon.create'); // Form thêm bộ môn
    Route::post('/store', [BoMonController::class, 'store'])->name('bomon.store'); // Lưu bộ môn mới
    Route::get('/{bomon}/edit', [BoMonController::class, 'edit'])->name('bomon.edit'); // Form sửa bộ môn
    Route::put('/{bomon}', [BoMonController::class, 'update'])->name('bomon.update'); // Cập nhật bộ môn
    Route::delete('/{bomon}', [BoMonController::class, 'destroy'])->name('bomon.destroy'); // Xóa bộ môn
});



// Route::resource('giangvien', GiangVienController::class);



// // Trang chủ chuyển hướng tùy theo loại người dùng
// Route::get('/', function () {
//     if (auth()->check()) {
//         $user = auth()->user();
//         if ($user instanceof \App\Models\Admin) {
//             return redirect()->route('admin.dashboard');
//         }
//         return redirect()->route('user.dashboard');
//     }
//     return redirect()->route('login');
// });

// // Authentication routes
// Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
// Route::post('/login', [AuthController::class, 'login']);
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// // Admin dashboard
// Route::middleware(['auth', 'admin'])->group(function () {
//     Route::get('/admin/dashboard', function () {
//         return view('admin.dashboard');
//     })->name('admin.dashboard');

//     Route::prefix('admin')->group(function () {
//         Route::get('/', [AdminController::class, 'index'])->name('admin.index');
//         Route::get('/create', [AdminController::class, 'create'])->name('admin.create');
//         Route::post('/store', [AdminController::class, 'store'])->name('admin.store');
//         Route::get('/{admin}/edit', [AdminController::class, 'edit'])->name('admin.edit');
//         Route::put('/{admin}', [AdminController::class, 'update'])->name('admin.update');
//         Route::delete('/{admin}', [AdminController::class, 'destroy'])->name('admin.destroy');
//     });
// });

// // User dashboard (giảng viên + nhân viên phòng đảm bảo chất lượng)
// Route::middleware(['auth', 'user'])->group(function () {
//     Route::get('/user/dashboard', function () {
//         return view('user.dashboard');
//     })->name('user.dashboard');

//     // Giảng viên routes
//     Route::prefix('giangvien')->group(function () {
//         Route::get('/', [GiangVienController::class, 'index'])->name('giangvien.index');
//         Route::get('/create', [GiangVienController::class, 'create'])->name('giangvien.create');
//         Route::post('/store', [GiangVienController::class, 'store'])->name('giangvien.store');
//         Route::get('/{maGiangVien}/edit', [GiangVienController::class, 'edit'])->name('giangvien.edit');
//         Route::put('/{maGiangVien}', [GiangVienController::class, 'update'])->name('giangvien.update');
//         Route::delete('/{maGiangVien}', [GiangVienController::class, 'destroy'])->name('giangvien.destroy');
//     });

//     // Nhân viên phòng ĐBCL routes
//     // Route::prefix('nhanvienpdbcl')->group(function () {
//     //     Route::get('/', [NhanVienPDBCLController::class, 'index'])->name('nhanvienpdbcl.index');
//     //     Route::get('/create', [NhanVienPDBCLController::class, 'create'])->name('nhanvienpdbcl.create');
//     //     Route::post('/store', [NhanVienPDBCLController::class, 'store'])->name('nhanvienpdbcl.store');
//     //     Route::get('/{maNV}/edit', [NhanVienPDBCLController::class, 'edit'])->name('nhanvienpdbcl.edit');
//     //     Route::put('/{maNV}', [NhanVienPDBCLController::class, 'update'])->name('nhanvienpdbcl.update');
//     //     Route::delete('/{maNV}', [NhanVienPDBCLController::class, 'destroy'])->name('nhanvienpdbcl.destroy');
//     // });
// });