@extends('layouts.app')

@section('page-title', "Thêm Giảng Viên")

@section('content')
<div class="fixed-container">
    <div class="form-container">
        <div class="form-header">
            <h4 class="mb-0"><i class="fas fa-chalkboard-teacher me-2"></i>Thêm Giảng Viên</h4>
        </div>
        <div class="form-body">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('giangvien.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Thông tin chung -->
                <div class="form-section">
                    <div class="section-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin giảng viên</h5>
                    </div>
                    <div class="section-body row">
                        <div class="col-md-6">
                            <label for="maGiangVien" class="form-label">Mã Giảng Viên</label>
                            <input type="text" class="form-control @error('maGiangVien') is-invalid @enderror" name="maGiangVien" id="maGiangVien" value="{{ old('maGiangVien') }}">
                            @error('maGiangVien')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="anhDaiDien" class="form-label">Ảnh đại diện</label>
                            <input type="file" class="form-control @error('anhDaiDien') is-invalid @enderror" name="anhDaiDien" id="anhDaiDien">
                            @error('anhDaiDien')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="ho" class="form-label">Họ</label>
                            <input type="text" class="form-control @error('ho') is-invalid @enderror" name="ho" id="ho" value="{{ old('ho') }}">
                            @error('ho')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="ten" class="form-label">Tên</label>
                            <input type="text" class="form-control @error('ten') is-invalid @enderror" name="ten" id="ten" value="{{ old('ten') }}">
                            @error('ten')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Thông tin liên hệ -->
                <div class="form-section">
                    <div class="section-header">
                        <h5 class="mb-0"><i class="fas fa-phone-alt me-2"></i>Thông tin liên hệ</h5>
                    </div>
                    <div class="section-body row">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="sdt" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control @error('sdt') is-invalid @enderror" name="sdt" id="sdt" value="{{ old('sdt') }}">
                            @error('sdt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Mật khẩu -->
                <div class="form-section">
                    <div class="section-header">
                        <h5 class="mb-0"><i class="fas fa-key me-2"></i>Mật khẩu</h5>
                    </div>
                    <div class="section-body row">
                        <div class="col-md-6">
                            <label for="matKhau" class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control @error('matKhau') is-invalid @enderror" name="matKhau" id="matKhau">
                            @error('matKhau')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="matKhau_confirmation" class="form-label">Xác nhận mật khẩu</label>
                            <input type="password" class="form-control @error('matKhau_confirmation') is-invalid @enderror" name="matKhau_confirmation" id="matKhau_confirmation">
                            @error('matKhau_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Thông tin chuyên môn -->
                <div class="form-section">
                    <div class="section-header">
                        <h5 class="mb-0"><i class="fas fa-briefcase me-2"></i>Thông tin chuyên môn</h5>
                    </div>
                    <div class="section-body row">
                        <div class="col-md-6">
                            <label for="chucVu" class="form-label">Chức vụ</label>
                            <select class="form-select @error('chucVu') is-invalid @enderror" name="chucVu" id="chucVu">
                                <option value="">-- Chọn chức vụ --</option>
                                @foreach($chucVus as $chucVu)
                                    <option value="{{ $chucVu->maChucVu }}" {{ old('chucVu') == $chucVu->maChucVu ? 'selected' : '' }}>{{ $chucVu->tenChucVu }}</option>
                                @endforeach
                            </select>
                            @error('chucVu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="boMon_id" class="form-label">Bộ môn</label>
                            <select class="form-select @error('boMon_id') is-invalid @enderror" name="boMon_id" id="boMon_id">
                                <option value="">-- Chọn bộ môn --</option>
                                @foreach($boMons as $boMon)
                                    <option value="{{ $boMon->maBoMon }}" {{ old('boMon_id') == $boMon->maBoMon ? 'selected' : '' }}>{{ $boMon->tenBoMon }}</option>
                                @endforeach
                            </select>
                            @error('boMon_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-center gap-2 mt-4">
                    <a href="{{ route('giangvien.index') }}" class="btn btn-secondary btn-fixed">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </a>
                    <button type="submit" class="btn btn-success btn-fixed">
                        <i class="fas fa-user-plus me-2"></i>Thêm mới
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
