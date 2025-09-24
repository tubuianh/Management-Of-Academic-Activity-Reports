{{-- <!-- resources/views/auth/reset-password.blade.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header text-center">
                        <h4>Đặt lại mật khẩu</h4>
                    </div>
                    <div class="card-body">

                        @if (session('status'))
                            <div class="alert alert-success">{{ session('status') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('password.update') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="code" class="form-label">Mã xác thực</label>
                                <input type="text" name="code" id="code" class="form-control" value="{{ old('code') }}" required autofocus>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu mới</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Xác nhận mật khẩu mới</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <button type="submit" class="btn btn-primary">Đặt lại mật khẩu</button>

                               
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> --}}

<!-- resources/views/auth/reset-password.blade.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu - Trường Đại học Nha Trang</title>
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
        
        .password-toggle {
            cursor: pointer;
            padding: 0.75rem 1rem;
            background-color: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-left: none;
            border-radius: 0 8px 8px 0;
            transition: all 0.3s;
        }
        
        .password-toggle:hover {
            color: var(--primary-color);
        }
        
        .input-group:focus-within .password-toggle {
            background-color: #fff;
            border-color: var(--primary-color);
        }
        
        .password-strength {
            height: 4px;
            background-color: #e9ecef;
            margin-top: 8px;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .password-strength-bar {
            height: 100%;
            width: 0;
            transition: width 0.3s, background-color 0.3s;
        }
        
        .strength-weak {
            background-color: #dc3545;
            width: 25%;
        }
        
        .strength-medium {
            background-color: #ffc107;
            width: 50%;
        }
        
        .strength-good {
            background-color: #28a745;
            width: 75%;
        }
        
        .strength-strong {
            background-color: #20c997;
            width: 100%;
        }
        
        .password-feedback {
            font-size: 0.8rem;
            margin-top: 5px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow page-transition" id="resetPasswordCard">
                    <div class="card-header text-center">
                        <div class="logo-container">
                            <img src="{{ asset('anhDaiDiens/ntu2.png') }}" alt="Logo NTU" class="logo">
                        </div>
                        <h4>Đặt lại mật khẩu</h4>
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <p class="text-center mb-4">Vui lòng nhập mã xác thực và mật khẩu mới của bạn</p>

                        <form action="{{ route('password.update') }}" method="POST" id="resetPasswordForm">
                            @csrf
                            <input type="hidden" name="ma" value="{{ $ma }}">

                            <div class="mb-3">
                                <label for="code" class="form-label">Mã xác thực</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-key text-muted"></i>
                                    </span>
                                    <input type="text" name="code" id="code" class="form-control border-start-0" 
                                           placeholder="Nhập mã xác thực" value="{{ old('code') }}" required autofocus>
                                </div>
                                @error('code')
                                    <span class="text-danger"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu mới</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-lock text-muted"></i>
                                    </span>
                                    <input type="password" name="password" id="password" class="form-control border-start-0" 
                                           placeholder="Nhập mật khẩu mới" required>
                                    <span class="password-toggle" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                                <div class="password-strength">
                                    <div class="password-strength-bar" id="passwordStrengthBar"></div>
                                </div>
                                <div class="password-feedback" id="passwordFeedback"></div>
                                @error('password')
                                    <span class="text-danger"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-lock text-muted"></i>
                                    </span>
                                    <input type="password" name="password_confirmation" id="password_confirmation" 
                                           class="form-control border-start-0" placeholder="Xác nhận mật khẩu mới" required>
                                    <span class="password-toggle" id="toggleConfirmPassword">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-check-circle me-2"></i>Đặt lại mật khẩu
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
            const card = document.getElementById('resetPasswordCard');
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
            const card = document.getElementById('resetPasswordCard');
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(function() {
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
            
            // Toggle password visibility
            const togglePassword = document.getElementById('togglePassword');
            const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('password_confirmation');
            
            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
            
            toggleConfirmPassword.addEventListener('click', function() {
                const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                confirmPassword.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
            
            // Password strength meter
            const passwordInput = document.getElementById('password');
            const strengthBar = document.getElementById('passwordStrengthBar');
            const feedback = document.getElementById('passwordFeedback');
            
            passwordInput.addEventListener('input', function() {
                const value = this.value;
                let strength = 0;
                let feedbackText = '';
                
                // Kiểm tra độ dài
                if (value.length >= 8) {
                    strength += 1;
                } else {
                    feedbackText = 'Mật khẩu phải có ít nhất 8 ký tự';
                }
                
                // Kiểm tra chữ hoa
                if (/[A-Z]/.test(value)) {
                    strength += 1;
                }
                
                // Kiểm tra số
                if (/[0-9]/.test(value)) {
                    strength += 1;
                }
                
                // Kiểm tra ký tự đặc biệt
                if (/[^A-Za-z0-9]/.test(value)) {
                    strength += 1;
                }
                
                // Hiển thị độ mạnh
                strengthBar.className = 'password-strength-bar';
                
                if (value.length === 0) {
                    strengthBar.style.width = '0';
                    feedback.textContent = '';
                } else if (strength === 1) {
                    strengthBar.classList.add('strength-weak');
                    feedbackText = feedbackText || 'Mật khẩu yếu';
                } else if (strength === 2) {
                    strengthBar.classList.add('strength-medium');
                    feedbackText = feedbackText || 'Mật khẩu trung bình';
                } else if (strength === 3) {
                    strengthBar.classList.add('strength-good');
                    feedbackText = feedbackText || 'Mật khẩu khá mạnh';
                } else if (strength === 4) {
                    strengthBar.classList.add('strength-strong');
                    feedbackText = feedbackText || 'Mật khẩu rất mạnh';
                }
                
                feedback.textContent = feedbackText;
            });
        });
    </script>
</body>
</html>

