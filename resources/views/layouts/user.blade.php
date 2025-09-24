<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý báo cáo</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{--  --}}
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/2.0.0/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
   
     <style>
        :root {
            --primary-blue: #005BAA;
            --light-blue: #E3F2FD;
            --hover-blue: #2196F3;
            --text-dark: #2C3E50;
            --bg-light: #F8FAFC;
            --bg-color: #ffffff;
            --text-color: #000000;
            --bg-gray: #f0f2f5;
        }


        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
        }

        /* Navbar Styling */
        .navbar {
            background-color: var(--bg-light);
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            padding: 0.8rem 0;
        }

        .navbar-brand {
            color: var(--primary-blue) !important;
            font-weight: 600;
            font-size: 1.25rem;
            margin-right: 0px !important;
        }


        .nav-link {
            color: var(--text-dark) !important;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
            border-radius: 6px;
            margin: 0;
        }

        .nav-link:hover {
            background-color: var(--light-blue);
            color: var(--primary-blue) !important;
        }

        .nav-link.active {
            background-color: var(--light-blue);
            color: var(--primary-blue) !important;
            font-weight: 500;
        }

        /* Main Content */
        .container.py-4 {
            background-color: var(--bg-light);
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
            /* padding: 1rem !important; */
            margin-top: 2rem;
            margin-bottom: 2rem;
            max-width: 1250px !important;
            padding: 0 !important;
        }
         @media (min-width: 1200px) {
            .container, .container-lg, .container-md, .container-sm, .container-xl {
                max-width: 1275px;
            }
        }


        /* Dropdown Styling */
        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-radius: 8px;
        }

        .dropdown-item {
            padding: 0.7rem 1.5rem;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: var(--light-blue);
            color: var(--primary-blue);
        }

        .main-content-section {
            background-color: var(--bg-color);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }

        

        /* Footer Styling */
        footer {
            background-color: var(--bg-light) !important;
            color: var(--text-dark) !important;
            border-top: 1px solid #E0E7FF;
            padding: 3rem 0 !important;
        }

        footer h5 {
            color: var(--primary-blue);
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        footer .list-unstyled a {
            color: var(--text-dark) !important;
            text-decoration: none;
            transition: all 0.3s ease;
            opacity: 0.8;
        }

        footer .list-unstyled a:hover {
            opacity: 1;
            color: var(--primary-blue) !important;
        }

        /* Social Media Buttons */
        footer .social-buttons .btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            background-color: var(--light-blue);
            color: var(--primary-blue);
            border: none;
        }

        footer .social-buttons .btn:hover {
            transform: translateY(-2px);
            background-color: var(--primary-blue);
            color: white;
        }

        /* Back to Top Button */
        .back-to-top {
            background-color: var(--primary-blue);
            color: white;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            opacity: 0.9;
        }

        .back-to-top:hover {
            opacity: 1;
            transform: translateY(-3px);
            color: white;
        }

        /* Custom Card Styling */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        /* style content */
        .fixed-container {
        max-width: 1250px !important;
        margin: 0;
        padding: 0px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }
    .form-container {
        background-color: var(--bg-light);
        border: 1px solid #ddd;
        border-radius: 4px;
        max-width: 1250px !important;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }
    .form-header {
        background: #005BAA;
        color: white;
        padding: 15px;
        border-radius: 4px 4px 0 0;
        width: 100%;
    }
    .form-body {
        padding: 20px;
        width: 100%;
    }
    .form-section {
        background: #fff;
        border: 1px solid #ddd;
        margin-bottom: 20px;
        padding: 0;
        width: 100%;
    }
    .section-header {
        background: #f8f9fa;
        padding: 10px 15px;
        border-bottom: 1px solid #ddd;
    }
    .section-body {
        padding: 15px;
    }
    .input-group {
        margin-bottom: 10px;
    }
    .list-group-item {
        border-left: none;
        border-right: none;
        border-radius: 0;
    }
    .list-group-item:first-child {
        border-top: none;
    }
    .list-group-item:last-child {
        border-bottom: none;
    }
    .btn-fixed {
        min-width: 120px;
    }
    .btn-hover-danger:hover {
        background-color: #f8d7da; /* đỏ nhạt */
        border-radius: 4px;
    }
    /* Style cho các phần xen kẽ */
        .alternate-section {
            background-color: var(--bg-gray);
            padding: 2rem 0;
        }

        /* Style cho container trong các phần xen kẽ */
        .alternate-section .container {
            background-color: var(--bg-color);
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        /* Style cho các card trong trang */
        .feature-card {
            background-color: var(--bg-color);
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            height: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        /* Style cho phần footer */
        footer {
            background-color: var(--bg-gray) !important;
        }

    </style>

    @yield('styles')
</head>
<body class="d-flex flex-column min-vh-100">

    <div class="top-bar bg-primary text-white py-1">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="contact-info d-flex align-items-center">
                    <span class="me-3">
                        <i class="fas fa-phone-alt me-1"></i>
                        Call us : 0258 2461303
                    </span>
                    <span>
                        <i class="fas fa-envelope me-1"></i>
                        E-mail : cntt@ntu.edu.vn
                    </span>
                </div>
            </div>
        </div>
    </div>

    <style>
        .top-bar {
            font-size: 0.9rem;
            background-color: #005BAA !important;
        }
        
        .top-bar .contact-info {
            font-size: 0.85rem;
        }
        
        .form-check-input {
            cursor: pointer;
            width: 35px;
            height: 18px;
        }
        
        .language-selector {
            cursor: pointer;
        }
        
        .language-selector img {
            vertical-align: middle;
        }
    </style>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('user.dashboard') }}">
                <img src="/anhdaidiens/ntu2.png" alt="NTU Logo" height="40" class="me-2">
                <span>NTU Portal</span>
            </a>
            <div class="d-flex">
                <div class="d-lg-none">
                    {{-- <a class="nav-link dropdown-toggle position-relative notificationDropdown" href="#"  role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i style="font-size: 24px" class="fas fa-bell"></i>
                        <span id="notification-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"></span>
                    </a> --}}
                    <a class="nav-link dropdown-toggle position-relative notificationDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i style="font-size: 20px" class="fas fa-bell"></i>
                        <span id="notification-dot" class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle d-none"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end p-3 notification-list" aria-labelledby="notificationDropdown" style="width: 300px; max-height: 400px; overflow-y: auto;">
                        <li>Đang tải...</li>
                    </ul>            
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <div style="" class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">

                        @php
                        $guard = session('current_guard');
                        $user = Auth::guard($guard)->user(); 
                        $gv = \App\Models\GiangVien::with('chucVuObj.quyen')->find($user->maGiangVien);

                             $quyen = $current_quyen ?? auth()->user()->quyen ?? auth()->user()->chucVu->quyen ?? null;
                             $dsQuyen =  $user->quyen?->nhomRoute ?? $gv->chucVuObj?->quyen?->nhomRoute ?? [];
                            $menuItems = [
                                'lichbaocao'     => ['<i class="fa-solid fa-calendar-days me-1"></i> Lịch sinh hoạt học thuật', 'lichbaocao.index'],
                                'baocao'         => ['<i class="fa-solid fa-file-lines me-1"></i> Báo cáo', 'baocao.index'],
                                'dangkybaocao'   => ['<i class="fa-solid fa-file-pen me-1"></i> Đăng ký tổ chức SHHT', 'dangkybaocao.index'],
                                'bienban'        => ['<i class="fa-solid fa-file-signature me-1"></i> Biên bản', 'bienban.index'],
                                'duyet'          => ['<i class="fa-solid fa-circle-check me-1"></i> Xác nhận phiếu', 'duyet.index'],
                                'xacnhan'        => ['<i class="fa-solid fa-clipboard-check me-1"></i> Xác nhận biên bản', 'xacnhan.index'],
                                'lichbaocaodangky' => ['<i class="fa-solid fa-square-plus"></i> Đăng ký tham gia SHHT', 'lichbaocaodangky.dangky'],
                                // 'giangvien' => ['<i class="fa-solid fa-person-chalkboard me-1"></i> Giảng viên', 'giangvien.dsgv'],
                            ];
                        @endphp

                        @foreach ($menuItems as $key => [$label, $route])
                            @if(in_array($key, $dsQuyen))

                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs($route) ? 'active' : '' }}" href="{{ route($route) }}">
                                        {!! $label !!}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                        
                        @if(auth()->guard('giang_viens')->check())
                            @if($gv->chucVuObj->maChucVu === 'TBM')                      
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('giangVien.boMon') ? 'active' : '' }}" href="{{ route('giangVien.boMon') }}">
                                        <i class="fa-solid fa-person-chalkboard me-1"></i> Giảng viên
                                    </a>
                                </li>
                            @endif
                        @endif
                        
                        @if((auth()->guard('giang_viens')->check() && $gv->chucVuObj->maChucVu === 'TK') || auth()->guard('nhan_vien_p_d_b_c_ls')->check())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('giangvien.dsgv') ? 'active' : '' }}" href="{{ route('giangvien.dsgv') }}">
                                    <i class="fa-solid fa-person-chalkboard me-1"></i> Giảng viên
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('nhanvien.thongke') ? 'active' : '' }}" href="{{ route('nhanvien.thongke') }}">
                                <i class="fa-solid fa-square-poll-vertical"></i> Thống Kê
                            </a>
                        </li>
                
                </ul>
                <ul class="navbar-nav">

                    @if ($user)
                       <li class="nav-item dropdown d-flex align-items-center">
                           
                             <div class="d-none d-lg-block">
                                <!-- Icon thông báo -->
                                 <a class="nav-link dropdown-toggle position-relative notificationDropdown" href="#"  role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i style="font-size: 20px" class="fas fa-bell"></i>
                                <span id="notification-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end p-3 notification-list" aria-labelledby="notificationDropdown" style="width: 300px; max-height: 400px; overflow-y: auto;" >
                                <li>Đang tải...</li>
                            </ul>
                                
                            </div>
                        </li>


                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <img src="{{ asset('storage/' . ($user->anhDaiDien ?? 'anhDaiDiens/anhmacdinh.jpg')) }}" 
                                            alt="Ảnh đại diện" 
                                            class="rounded-circle me-2"
                                            style="width: 32px; height: 32px; object-fit: cover;"
                                            onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{  urlencode($user->ho . ' ' . $user->ten) }}&background=0D8ABC&color=fff';">
                                <span>{{ $user->ho }} {{ $user->ten }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                                        <i class="fas fa-user me-2"></i> Trang cá nhân
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>

                
            </div>
        </div>
    </nav>

    <main class="flex-grow-1">
        <div class="container py-4">
           
            @yield('content')
        </div>
    </main>

    <footer class="mt-auto">
        <div class="container">
            <div class="d-flex row justify-content-between">
                <div class="col-md-4">
                    <div class="d-flex align-items-center mb-1">
                        <img src="/anhdaidiens/ntu2.png" alt="NTU Logo" class="img-fluid" style="max-height: 50px;">
                        <div class="ms-2">
                            <h5 class="university-name mb-0" style="color: var(--primary-blue); font-size: 1.1rem; font-weight: 700;">TRƯỜNG ĐẠI HỌC NHA TRANG</h5>
                            <div class="system-name" style="color: var(--text-dark); font-size: 0.85rem; opacity: 0.8;">HỆ THỐNG QUẢN LÝ SINH HOẠT HỌC THUẬT</div>
                        </div>
                    </div>
                    <p class="text-muted mb-0" style="font-size: 0.85rem; line-height: 1.4; text-align: justify;">
                        Hệ thống quản lý sinh hoạt học thuật giúp giảng viên và nhân viên dễ dàng đăng ký, theo dõi và quản lý các báo cáo học thuật tại Trường Đại học Nha Trang.
                    </p>
                </div>

                <div class="col-md-2">
                    <h5>Liên kết</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="https://ntu.edu.vn" target="_blank">Trang chủ NTU</a></li>
                        <li class="mb-2"><a href="https://thuvien.ntu.edu.vn/" target="_blank">Thư viện</a></li>
                        <li class="mb-2"><a href="https://daotao.ntu.edu.vn/" target="_blank">Đào tạo</a></li>
                        <li class="mb-2"><a href="https://tuyensinh.ntu.edu.vn/" target="_blank">Tuyển sinh</a></li>
                    </ul>
                </div>

                <div class="col-md-2">
                    <h5>Hỗ trợ</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" data-bs-toggle="modal" data-bs-target="#huongDanModal">Hướng dẫn sử dụng</a></li>
                        <li class="mb-2"><a href="#" data-bs-toggle="modal" data-bs-target="#faqModal">Câu hỏi thường gặp</a></li>
                        <li class="mb-2">
                            <a href="{{ asset('pdfs/Quy_Dinh.pdf') }}" target="_blank">
                                Quy định báo cáo
                            </a>
                        </li>
                        <li class="mb-2"><a href="mailto:cntt@ntu.edu.vn">Liên hệ hỗ trợ</a></li>
                    </ul>
                </div>
                <!-- Modal Hướng dẫn sử dụng -->
                <div class="modal fade" id="huongDanModal" tabindex="-1" aria-labelledby="huongDanModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="huongDanModalLabel">Hướng dẫn sử dụng</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div  class="modal-body">
                                <!-- Nội dung hướng dẫn sử dụng -->
                                <h4>1. Đăng nhập hệ thống</h4>
                                <p>Sử dụng tài khoản được cấp để đăng nhập vào hệ thống. Nếu quên mật khẩu, bạn có thể sử dụng chức năng "Quên mật khẩu" để đặt lại mật khẩu qua email.</p>
                                
                                <h4>2. Quản lý báo cáo</h4>
                                <p>Để tạo báo cáo mới, truy cập menu "<i class="fa-solid fa-file-lines me-1"></i> Báo cáo" và nhấn vào nút "Tạo báo cáo mới". Điền đầy đủ thông tin và đính kèm tệp báo cáo (nếu có).</p>
                                
                                <h4>3. Đăng ký tổ chức SHHT</h4>
                                <p>Để đăng ký tổ chức SHHT, truy cập menu "<i class="fa-solid fa-file-pen me-1"></i> Đăng ký tổ chức SHHT", chọn lịch báo cáo phù hợp và điền đầy đủ thông tin. Sau khi gửi, phiếu sẽ được chuyển đến người có thẩm quyền xét duyệt.</p>
                                
                                <h4>4. Nộp biên bản SHHT</h4>
                                <p>Để nộp biên bản SHHT, truy cập menu "<i class="fa-solid fa-file-signature me-1"></i> Biên bản", chọn "Tạo biên bản mới" và điền đầy đủ thông tin. Biên bản sẽ được gửi đến người có thẩm quyền xác nhận.</p>
                                
                                <h4>5. Xem lịch SHHT</h4>
                                <p>Để xem lịch SHHT, truy cập menu "<i class="fa-solid fa-calendar-days me-1"></i> Lịch sinh hoạt học thuật". Tại đây, bạn có thể xem tất cả các lịch SHHT đã được lên kế hoạch.</p>
                                
                                <h4>6. Đăng ký tham gia SHHT</h4>
                                <p>Để đăng ký tham gia SHHT, truy cập menu "<i class="fa-solid fa-square-plus"></i> Đăng ký tham gia SHHT", chọn lịch báo cáo mà bạn muốn tham gia và xác nhận đăng ký.</p>
                                
                                <h4>7. Xác nhận phiếu đăng ký SHHT</h4>
                                <p>Nếu bạn có quyền xác nhận phiếu đăng ký SHHT, truy cập menu "<i class="fa-solid fa-circle-check me-1"></i> Xác nhận phiếu" để xem danh sách các phiếu đăng ký chờ xác nhận.</p>
                                
                                <h4>8. Xác nhận biên bản SHHT</h4>
                                <p>Nếu bạn có quyền xác nhận biên bản SHHT, truy cập menu "<i class="fa-solid fa-clipboard-check me-1"></i> Xác nhận biên bản" để xem danh sách các biên bản chờ xác nhận.</p>
                                
                                <h4>9. Xem thống kê SHHT</h4>
                                <p>Để xem thống kê SHHT, truy cập menu "<i class="fa-solid fa-square-poll-vertical"></i> Thống Kê" để xem các báo cáo thống kê về hoạt động SHHT.</p>
                                
                                <h4>10. Quản lý thông báo</h4>
                                <p>Hệ thống sẽ gửi thông báo khi có sự kiện mới (như phiếu đăng ký được duyệt, biên bản được xác nhận, v.v.). Bạn có thể xem thông báo bằng cách nhấp vào biểu tượng chuông <i class="fas fa-bell"></i> trên thanh điều hướng.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Câu hỏi thường gặp -->
                <div class="modal fade" id="faqModal" tabindex="-1" aria-labelledby="faqModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="faqModalLabel">Câu hỏi thường gặp</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Nội dung FAQ -->
                                <div class="accordion" id="faqAccordion">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="faq1">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse1" aria-expanded="true" aria-controls="faqCollapse1">
                                                Làm thế nào để đăng ký tổ chức SHHT?
                                            </button>
                                        </h2>
                                        <div id="faqCollapse1" class="accordion-collapse collapse show" aria-labelledby="faq1" data-bs-parent="#faqAccordion">
                                            <div class="accordion-body">
                                                Để đăng ký tổ chức SHHT, bạn cần truy cập vào menu "<i class="fa-solid fa-file-pen me-1"></i> Đăng ký tổ chức SHHT", chọn lịch báo cáo phù hợp, điền đầy đủ thông tin và gửi phiếu đăng ký. Sau khi gửi, phiếu sẽ được chuyển đến người có thẩm quyền xét duyệt.
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="faq2">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse2" aria-expanded="false" aria-controls="faqCollapse2">
                                                Làm thế nào để nộp biên bản SHHT?
                                            </button>
                                        </h2>
                                        <div id="faqCollapse2" class="accordion-collapse collapse" aria-labelledby="faq2" data-bs-parent="#faqAccordion">
                                            <div class="accordion-body">
                                                Để nộp biên bản SHHT, bạn cần truy cập vào menu "<i class="fa-solid fa-file-signature me-1"></i> Biên bản", chọn "Tạo biên bản mới", điền đầy đủ thông tin và đính kèm tệp biên bản (nếu có). Biên bản sẽ được gửi đến người có thẩm quyền xác nhận.
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="faq3">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse3" aria-expanded="false" aria-controls="faqCollapse3">
                                                Làm thế nào để đăng ký tham gia SHHT?
                                            </button>
                                        </h2>
                                        <div id="faqCollapse3" class="accordion-collapse collapse" aria-labelledby="faq3" data-bs-parent="#faqAccordion">
                                            <div class="accordion-body">
                                                Để đăng ký tham gia SHHT, bạn cần truy cập vào menu "<i class="fa-solid fa-square-plus"></i> Đăng ký tham gia SHHT", chọn lịch báo cáo mà bạn muốn tham gia và xác nhận đăng ký. Bạn sẽ nhận được thông báo khi đăng ký thành công.
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="faq4">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse4" aria-expanded="false" aria-controls="faqCollapse4">
                                                Làm thế nào để xem lịch SHHT?
                                            </button>
                                        </h2>
                                        <div id="faqCollapse4" class="accordion-collapse collapse" aria-labelledby="faq4" data-bs-parent="#faqAccordion">
                                            <div class="accordion-body">
                                                Để xem lịch SHHT, bạn cần truy cập vào menu "<i class="fa-solid fa-calendar-days me-1"></i> Lịch sinh hoạt học thuật". Tại đây, bạn có thể xem tất cả các lịch SHHT đã được lên kế hoạch, bao gồm thông tin về thời gian, địa điểm và chủ đề.
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="faq5">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse5" aria-expanded="false" aria-controls="faqCollapse5">
                                                Làm thế nào để nộp báo cáo SHHT?
                                            </button>
                                        </h2>
                                        <div id="faqCollapse5" class="accordion-collapse collapse" aria-labelledby="faq5" data-bs-parent="#faqAccordion">
                                            <div class="accordion-body">
                                                Để nộp báo cáo SHHT, bạn cần truy cập vào menu "<i class="fa-solid fa-file-lines me-1"></i> Báo cáo", chọn "Tạo báo cáo mới", điền đầy đủ thông tin và đính kèm tệp báo cáo. Lưu ý rằng báo cáo phải được nộp trước hạn nộp được quy định trong lịch SHHT.
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="faq6">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse6" aria-expanded="false" aria-controls="faqCollapse6">
                                                Làm thế nào để xác nhận phiếu đăng ký SHHT?
                                            </button>
                                        </h2>
                                        <div id="faqCollapse6" class="accordion-collapse collapse" aria-labelledby="faq6" data-bs-parent="#faqAccordion">
                                            <div class="accordion-body">
                                                Nếu bạn có quyền xác nhận phiếu đăng ký SHHT, bạn cần truy cập vào menu "<i class="fa-solid fa-circle-check me-1"></i> Xác nhận phiếu". Tại đây, bạn có thể xem danh sách các phiếu đăng ký chờ xác nhận, xem chi tiết và thực hiện xác nhận hoặc từ chối.
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="faq7">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse7" aria-expanded="false" aria-controls="faqCollapse7">
                                                Làm thế nào để xác nhận biên bản SHHT?
                                            </button>
                                        </h2>
                                        <div id="faqCollapse7" class="accordion-collapse collapse" aria-labelledby="faq7" data-bs-parent="#faqAccordion">
                                            <div class="accordion-body">
                                                Nếu bạn có quyền xác nhận biên bản SHHT, bạn cần truy cập vào menu "<i class="fa-solid fa-clipboard-check me-1"></i> Xác nhận biên bản". Tại đây, bạn có thể xem danh sách các biên bản chờ xác nhận, xem chi tiết và thực hiện xác nhận hoặc yêu cầu chỉnh sửa.
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="faq8">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse8" aria-expanded="false" aria-controls="faqCollapse8">
                                                Làm thế nào để xem thống kê SHHT?
                                            </button>
                                        </h2>
                                        <div id="faqCollapse8" class="accordion-collapse collapse" aria-labelledby="faq8" data-bs-parent="#faqAccordion">
                                            <div class="accordion-body">
                                                Để xem thống kê SHHT, bạn cần truy cập vào menu "<i class="fa-solid fa-square-poll-vertical"></i> Thống Kê". Tại đây, bạn có thể xem các báo cáo thống kê về số lượng SHHT đã tổ chức, tỷ lệ tham gia, và các chỉ số khác liên quan đến hoạt động SHHT.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <h5>Thông tin liên hệ</h5>
                    <p class="mb-1" style="font-size: 0.9rem;"><i class="fas fa-map-marker-alt me-2"></i>02 Nguyễn Đình Chiểu, Nha Trang, Khánh Hòa</p>
                    <p class="mb-1" style="font-size: 0.9rem;"><i class="fas fa-phone me-2"></i>0258 2461303</p>
                    <p class="mb-1" style="font-size: 0.9rem;"><i class="fas fa-envelope me-2"></i>cntt@ntu.edu.vn</p>
                    <div class="social-buttons d-flex gap-2 mt-3">
                        <a href="https://www.facebook.com/daihocnhatrang" target="_blank" class="btn" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.youtube.com/@truongaihocnhatrang8073" target="_blank" class="btn" title="YouTube"><i class="fab fa-youtube"></i></a>
                        <a href="mailto:cntt@ntu.edu.vn" class="btn" title="Email"><i class="fas fa-envelope"></i></a>
                        <a href="https://ntu.edu.vn" target="_blank" class="btn" title="Website"><i class="fas fa-globe"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center py-3 mt-4" style="background-color: rgba(0,0,0,0.03);">
            <p class="mb-0" style="font-size: 0.85rem; color: #6c757d;">
                © 2024 Trường Đại học Nha Trang. Phát triển bởi Kiều Thái Tuấn và Bùi Anh Tú.
            </p>
        </div>
    </footer>


    <a href="#" class="back-to-top position-fixed bottom-0 end-0 m-3">
        <i class="fas fa-arrow-up"></i>
    </a>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.0/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.0/js/dataTables.bootstrap5.min.js"></script>
    
    @yield('script')

<script>
   
    $(document).ready(function () {
    const loadNotifications = () => {
        $.get("{{ route('notifications.index') }}", function (data) {
            let html = '';
            if (data.length > 0) {
                data.forEach(function (item) {
                    const textClass = item.daDoc ? 'text-muted' : 'fw-bold';
                    html += `<li style="border-bottom: 1px solid #dee2e6;" data-id="${item.id}">
                        <div class="d-flex justify-content-between align-items-start align-items-center">
                            <a style="padding: 5px 0px" href="#" class="dropdown-item notification-item ${textClass} flex-grow-1 text-wrap" data-id="${item.id}" data-link="${item.link}" style="white-space: normal;">
                                ${item.noiDung}
                                <p style="margin:0; font-size:12px" class="text-muted small">${item.created_at}</p>
                            </a>
                            <button style="font-size:25px;" class="btn btn-sm btn-link text-danger btn-delete-notification px-1 btn-hover-danger" data-id="${item.id}" title="Xóa">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </li>`;
                });
            } else {
                html = '<li class="text-muted">Không có thông báo</li>';
            }
            $('.notification-list').html(html);

            // Gửi request đánh dấu tất cả đã đọc (vì đã mở dropdown)
            $.post("{{ route('notifications.markAllAsRead') }}", {
                _token: "{{ csrf_token() }}"
            });

            // Ẩn số lượng thông báo
            $('#notification-count').text('');
        });
    };

    // Khi ấn chuông => load danh sách
    $('.notificationDropdown').on('click', function () {
        loadNotifications();
    });

    // Khi trang load => hiện số lượng chưa đọc
    $.get("{{ route('notifications.index') }}", function (data) {
        const unreadCount = data.filter(item => item.daDoc == false).length;
        if (unreadCount > 0) {
            $('#notification-count').text(unreadCount);
        }
    });

    // Khi click vào nội dung thông báo → markAsRead + chuyển trang
    $(document).on('click', '.notification-item', function (e) {
        e.preventDefault();
        const id = $(this).data('id');
        const link = $(this).data('link');
        const $item = $(this);

        $.ajax({
            url: "{{ url('notifications/mark-as-read') }}/" + id,
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function () {
                // Gỡ in đậm ngay lập tức
                $item.removeClass('fw-bold').addClass('text-muted');

                // Chuyển trang sau khi markAsRead
                window.location.href = link;
            }
        });
    });

    // Khi click vào nút xóa thông báo (chỉ xóa của mình thôi)
    $(document).on('click', '.btn-delete-notification', function (e) {
        e.preventDefault();
        const id = $(this).data('id');
        const $item = $(this).closest('li');

        $.ajax({
            url: "{{ route('notifications.delete') }}",
            method: 'DELETE',
            data: {
                _token: "{{ csrf_token() }}",
                id: id
            },
            success: function () {
                $item.remove();
            }
        });
    });
});




</script>




</body>
</html>
