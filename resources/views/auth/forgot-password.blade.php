{{-- 
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header text-center">
                        <h4>Quên mật khẩu</h4>
                    </div>
                    <div class="card-body">

                        @if (session('status'))
                            <div class="alert alert-success">{{ session('status') }}</div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="ma" class="form-label">Mã số đăng nhập:</label>
                                <input type="text" name="ma" id="ma" class="form-control" required>
                                @error('ma')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Gửi mã xác thực</button>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> --}}
<!-- resources/views/auth/forgot-password.blade.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu - Trường Đại học Nha Trang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --primary-color: #005BAA;  /* Màu xanh đặc trưng của NTU */
            --secondary-color: #0056b3;
            --text-color: #2b2d42;
            --background-color: #f8f9fa;
            --error-color: #ef233c;
        }

        body {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.5s ease, box-shadow 0.5s ease;
        }
        
        .card:hover {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
        }

        .card-header h4 {
            margin-bottom: 0;
            color: var(--primary-color);
            font-weight: 600;
        }

        .card-body {
            padding: 2rem;
        }

        .form-label {
            font-weight: 500;
            color: var(--text-color);
            margin-bottom: 0.5rem;
        }

        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            background-color: #f9f9f9;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(0, 91, 170, 0.25);
            background-color: #fff;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .btn-primary::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }
        
        .btn-primary:focus:not(:active)::after {
            animation: ripple 1s ease-out;
        }
        
        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 0.5;
            }
            100% {
                transform: scale(20, 20);
                opacity: 0;
            }
        }

        .alert {
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            animation: fadeIn 0.5s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .text-danger {
            color: var(--error-color) !important;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: block;
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }

        .back-to-login {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            position: relative;
            padding: 8px 16px;
            border-radius: 6px;
        }

        .back-to-login:hover {
            color: var(--secondary-color);
            background-color: rgba(0, 91, 170, 0.05);
            transform: translateY(-2px);
        }
        
        .back-to-login i {
            transition: transform 0.3s ease;
        }
        
        .back-to-login:hover i {
            transform: translateX(-4px);
        }

        .logo-container {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .logo {
            max-width: 120px;
            height: auto;
            transition: transform 0.5s ease;
        }
        
        .logo:hover {
            transform: scale(1.05);
        }
        
        .page-transition {
            transition: transform 0.5s ease, opacity 0.5s ease;
        }
        
        .page-transition.slide-out {
            transform: translateX(-100%);
            opacity: 0;
        }
        
        .input-group {
            transition: all 0.3s ease;
        }
        
        .input-group:focus-within {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow page-transition" id="forgotPasswordCard">
                    <div class="card-header text-center">
                        <div class="logo-container">
                            <img src="{{ asset('anhDaiDiens/ntu2.png') }}" alt="Logo NTU" class="logo">
                        </div>
                        <h4>Quên mật khẩu</h4>
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                            </div>
                        @endif

                        <p class="text-center mb-4">Vui lòng nhập mã số đăng nhập của bạn để nhận mã xác thực</p>

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="ma" class="form-label">Mã số đăng nhập</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-user-circle text-muted"></i>
                                    </span>
                                    <input type="text" name="ma" id="ma" class="form-control border-start-0" 
                                           placeholder="Nhập mã số đăng nhập" required>
                                    
                                </div>
                                
                                <span>Mã xác thực sẽ được gửi email của bạn!</span>
                                @error('ma')
                                    <span class="text-danger"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>Gửi mã xác thực
                                </button>
                            </div>
                        </form>

                        <a href="{{ route('login') }}" class="back-to-login" id="backToLoginBtn">
                            <i class="fas fa-arrow-left me-1"></i>Quay lại đăng nhập
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation khi nhấn nút quay lại đăng nhập
        document.getElementById('backToLoginBtn').addEventListener('click', function(e) {
            e.preventDefault();
            const card = document.getElementById('forgotPasswordCard');
            const loginUrl = this.getAttribute('href');
            
            // Thêm hiệu ứng slide out
            card.classList.add('slide-out');
            
            // Chuyển hướng sau khi animation hoàn tất
            setTimeout(function() {
                window.location.href = loginUrl;
            }, 500);
        });
        
        // Animation khi trang load
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.getElementById('forgotPasswordCard');
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(function() {
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>

