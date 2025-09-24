

@extends('layouts.user')

@section('content')
<div class="fixed-container">
    <div class="form-container">
        <div class="form-header d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><i class="fa-solid fa-calendar-days me-2"></i>Danh Sách Lịch</h4>
            @php 
            $guard = session('current_guard');
            $user = Auth::guard($guard)->user();
            @endphp
            @if($guard === 'giang_viens' && in_array($user->chucVuObj->tenChucVu, ['Trưởng Bộ Môn', 'Trưởng Khoa']))
            <a href="{{ route('lichbaocao.create') }}" class="btn btn-outline-light" style="border-radius: 5px;border: 3px solid #fff;">Thêm Lịch </a>
            @endif
        </div>
        <div class="form-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <div class="row mb-4 justify-content-center">
                    <form action="{{ route('lichbaocao.index') }}" method="GET" 
                        class="d-flex flex-wrap align-items-center gap-2 justify-content-center" style="max-width: 100%;">
                        
                        <input type="text" name="keyword" 
                            class="form-control form-control-sm" 
                            placeholder="Nhập từ khóa (chủ đề, thời gian, giảng viên, bộ môn...)"
                            value="{{ request('keyword') }}" 
                            style="width: 350px; height: 45px; min-width: 250px;">
                        
                        <button class="btn btn-primary" type="submit" style="height: 45px; min-width: 100px;">
                            Tìm kiếm
                        </button>
                        
                        <a href="{{ route('lichbaocao.index') }}" class="btn btn-success" style="height: 45px; min-width: 100px; align-content: center;">
                            Làm mới
                        </a>
                    </form>  
                </div>
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    @forelse ($dsLichBaoCao as $lich)
                        @php
                            $modalId = 'modal-' . $lich->maLich;
                        @endphp
                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm h-100 border-primary border-top border-4">
                                <div class="card-body d-flex flex-column">
                                    <div class="text-center mb-2">
                                        <i class="fa-solid fa-calendar-days fa-2x text-primary"></i>
                                    </div>
                                    <h6 class="card-title text-center">Chủ đề: {{ $lich->chuDe }}</h6>
                                    <h6 class="card-text text-center">Ngày/giờ tổ chức: {{ $lich->ngayBaoCao }}  {{ $lich->gioBaoCao }}</h6>
                                    <h6 class="card-text text-center">Hạn ngày/giờ nộp báo cáo:{{ $lich->hanNgayNop }}  {{ $lich->hanGioNop }}</h6>
                                    <h6 class="card-text text-center">
                                        Báo cáo viên:
                                        {{ $lich->giangVienPhuTrach->map(fn($gv) => $gv->ho . ' ' . $gv->ten)->join(', ') }}
                                    </h6>
                                    <h6 class="card-title text-center">Giảng viên tạo lịch: {{ $lich->giangVien->ho }} {{ $lich->giangVien->ten }}</h6>
                                    <div class="d-flex justify-content-center gap-2 mt-auto">
                                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#{{ $modalId }}">
                                            <i class="fas fa-eye me-1"></i> Xem chi tiết
                                        </button>
                                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal-bcn-{{ $lich->maLich }}">
                                            <i class="fas fa-file-alt me-1"></i> Báo cáo đã nộp
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal Báo cáo đã nộp -->
                        <div class="modal fade" id="modal-bcn-{{ $lich->maLich }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h5 class="modal-title text-white">Danh sách báo cáo đã nộp</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                    </div>
                                    <div class="modal-body">
                                        @if($lich->baoCaos->isEmpty())
                                            <div class="alert alert-info">Chưa có báo cáo nào được nộp cho lịch này.</div>
                                        @else
                                            <ul class="list-group">
                                                <h6>Giảng viên - tên báo cáo</h6>
                                                @foreach($lich->baoCaos as $bc)
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <strong>{{ $bc->giangVien->ho }} {{ $bc->giangVien->ten }}</strong>
                                                            - {{ $bc->tenBaoCao }}
                                                            @if($bc->dongTacGias->count() > 0)
                                                                <br>
                                                                <small class="text-muted">
                                                                    Đồng tác giả:
                                                                    @foreach($bc->dongTacGias as $dtg)
                                                                        {{ $dtg->giangVienDongTacGia?->ho }} {{ $dtg->giangVienDongTacGia?->ten }}{{ !$loop->last ? ',' : '' }}
                                                                    @endforeach
                                                                </small>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            @if($bc->duongDanFile)
                                                            <a style="min-width: 30px" href="{{ asset($bc->duongDanFile) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-download me-1"></i>
                                                            </a>
                                                            @else
                                                                <span class="text-muted">Không có file</span>
                                                            @endif
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        <nav>
                                            <ul id="pagination" class="pagination justify-content-center mt-2"></ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Modal Chi tiết --}}
                        <div class="modal fade" id="{{ $modalId }}" aria-labelledby="{{ $modalId }}Label" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h5 class="modal-title text-white">Chi tiết lịch sinh hoạt học thuật</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="mb-1"><strong>Chủ đề:</strong> {{ $lich->chuDe }}</p>
                                        <p class="mb-1"><strong>Ngày báo cáo:</strong> {{ $lich->ngayBaoCao }}</p>
                                        <p class="mb-1"><strong>Giờ báo cáo:</strong> {{ $lich->gioBaoCao }}</p>
                                        <p class="mb-1"><strong>Hạn nộp ngày:</strong> {{ $lich->hanNgayNop }}</p>
                                        <p class="mb-1"><strong>Hạn nộp giờ:</strong> {{ $lich->hanGioNop }}</p>
                                        {{-- <p class="mb-1"><strong>Bộ môn:</strong> {{ $lich->boMon->tenBoMon ?? 'Chưa có' }}</p> --}}
                                        <p class="mb-1">
                                            @if($lich->boMon)
                                                <strong>Bộ môn:</strong> {{ $lich->boMon->tenBoMon ?? 'Chưa có' }}
                                            @elseif($lich->giangVien && $lich->giangVien->boMon && $lich->giangVien->boMon->khoa)
                                                <strong>Khoa:</strong> {{ $lich->giangVien->boMon->khoa->tenKhoa }}
                                            @else
                                                Chưa có
                                            @endif
                                        </p>
                                        <p class="mb-1">
                                            @if($lich->boMon)
                                                <strong>Cấp tổ chức:</strong> Bộ Môn
                                            @elseif($lich->giangVien && $lich->giangVien->boMon && $lich->giangVien->boMon->khoa)
                                                <strong>Cấp tổ chức:</strong> Khoa
                                            @else
                                                Chưa có
                                            @endif
                                        </p>
                                        <p class="mb-1"><strong>Giảng viên phụ trách - Giảng viên phản biện:</strong><br>
                                            {{-- @foreach($lich->giangVienPhuTrach as $gv)
                                                - {{ $gv->ho }} {{ $gv->ten }}<br>
                                            @endforeach --}}
                                            @foreach($lich->giangVienPhuTrach as $gv)
                                                @php
                                                    $phanBienId = $gv->pivot->giang_vien_phan_bien_id ?? null;
                                                    $gvPhanBien = $phanBienId ? \App\Models\GiangVien::find($phanBienId) : null;
                                                @endphp
                                                - {{ $gv->ho }} {{ $gv->ten }}
                                                @if($gvPhanBien)
                                                    — <span class="text-muted">Phản biện: {{ $gvPhanBien->ho }} {{ $gvPhanBien->ten }}</span>
                                                @endif
                                                <br>
                                            @endforeach

                                        </p>                            
                                    </div>

                                    @if($guard === 'giang_viens' && in_array($user->chucVuObj->tenChucVu, ['Trưởng Bộ Môn', 'Trưởng Khoa']))
                                        <div class="modal-footer d-flex justify-content-center gap-2">
                                            <form action="{{ route('lichbaocao.edit', $lich->maLich) }}">

                                                <button type="submit" class="btn btn-warning btn-sm text-white">
                                                    <i class="fas fa-edit me-1 text-white"></i> Sửa
                                                </button>
                                            </form>
                                            <form action="{{ route('lichbaocao.destroy', $lich->maLich) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm btn-delete">
                                                    <i class="fas fa-trash-alt me-1"></i> Xoá
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                    
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center">
                            <div class="alert alert-info">Không tìm thấy lịch báo cáo phù hợp.</div>
                        </div>
                    @endforelse
                    
                    </div>
                    </div>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        {{ $dsLichBaoCao->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            let formId = $(this).data('form');
            const form = $(this).closest('form'); 
            Swal.fire({
                title: "Bạn có chắc chắn?",
                text: "Bạn sẽ không thể khôi phục lại dữ liệu này!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Vâng, xóa nó!"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Submit form trực tiếp
                }
            });
        });

    
    });
    setTimeout(() => {
        const success = document.getElementById('alert-success');
        const error = document.getElementById('alert-danger');
        if (success) success.style.display = 'none';
        if (error) error.style.display = 'none';
    }, 3000); // 3000ms = 3 giây
    

</script>
@endsection
