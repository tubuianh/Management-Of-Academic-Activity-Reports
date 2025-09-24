@extends('layouts.user')
@section('page-title', "Danh Sách Biên Bản")

@section('content')
<div class="fixed-container">
    <div class="form-container">
        <div class="form-header d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><i class="fas fa-file-signature me-2"></i>Danh Sách Biên Bản</h4>
            <a href="{{ route('bienban.create') }}" class="btn btn-outline-light" style="border-radius: 5px;border: 3px solid #fff;">Gửi Biên Bản</a>
        </div>

        <div class="form-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="row mb-4 justify-content-center">
                <form action="{{ route('bienban.index') }}" method="GET" 
                      class="d-flex flex-wrap align-items-center gap-2 justify-content-center">
                    <input type="text" name="keyword"
                           class="form-control form-control-sm"
                           placeholder="Nhập từ khóa (chủ đề, ngày gửi, trạng thái)..."
                           value="{{ request('keyword') }}"
                           style="width: 350px; height: 45px;">
                    <button class="btn btn-primary" type="submit" style="height: 45px;">Tìm kiếm</button>
                    <a href="{{ route('bienban.index') }}" class="btn btn-success" style="height: 45px;min-width: 100px; align-content: center;">Làm mới</a>
                </form>
            </div>

            <div class="row">
                @forelse($bienbans as $bb)
                    @php
                        $modalId = 'modal-bb-' . $bb->maBienBan;
                        $statusColor = match($bb->trangThai) {
                            'Chờ Xác Nhận' => 'warning',
                            'Đã Xác Nhận' => 'success',
                            'Từ Chối' => 'danger',
                            default => 'secondary',
                        };
                    @endphp

                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm h-100 border-{{ $statusColor }} border-top border-4">
                            <div class="card-body d-flex flex-column">
                                <div class="text-center mb-2">
                                    <i class="fas fa-file-signature fa-2x text-{{ $statusColor }}"></i>
                                </div>
                                <h6 class="card-title text-center">
                                    Chủ đề: {{ $bb->lichBaoCao->chuDe ?? 'Không rõ chủ đề' }}
                                </h6>
                                <p class="text-center text-muted mb-1">Ngày gửi: <strong>{{ \Carbon\Carbon::parse($bb->ngayNop)->format('d/m/Y H:i') }}</strong></p>
                                <p class="text-center text-muted">Trạng thái: <strong>{{ $bb->trangThai }}</strong></p>

                                <div class="d-flex justify-content-center mt-auto">
                                    <button class="btn btn-outline-{{ $statusColor }}" data-bs-toggle="modal" data-bs-target="#{{ $modalId }}">
                                        <i class="fas fa-eye me-1"></i> Xem chi tiết
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header bg-{{ $statusColor }} text-white">
                                    <h5 class="modal-title" id="{{ $modalId }}Label">Chi tiết biên bản sinh hoạt học thuật</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                </div>
                                <div class="modal-body">
                                    <p class="mb-0 p-1"><strong>Mã biên bản:</strong> {{ $bb->maBienBan ?? 'Không rõ' }}</p>
                                    <p class="mb-0 p-1"><strong>Chủ đề:</strong> {{ $bb->lichBaoCao->chuDe ?? 'Không rõ' }}</p>
                                    <p class="mb-0 p-1"><strong>Ngày gửi:</strong> {{ \Carbon\Carbon::parse($bb->ngayNop)->format('d/m/Y H:i') }}</p>
                                    <p class="mb-0 p-1"><strong>Trạng thái:</strong> {{ $bb->trangThai }}</p>
                                    @if ($bb->trangThai === 'Đã Xác Nhận')
                                    <p class="mb-0 p-1"><strong>Ngày xác nhận:</strong> {{ \Carbon\Carbon::parse($bb->update_at)->format('d/m/Y') }}</p>
                                    @endif
                                    <p class="mb-0 p-1"><strong>Ngày/giờ tổ chức:</strong> {{ \Carbon\Carbon::parse($bb->lichBaoCao->ngayBaoCao)->format('d/m/Y') }} - {{ $bb->lichBaoCao->gioBaoCao }}</p>
                                    {{-- <p class="mb-0 p-1"><strong>Bộ môn:</strong> {{ $bb->lichBaoCao->boMon->tenBoMon }}</p>
                                    <p class="mb-0 p-1"><strong>Khoa:</strong> {{ $bb->lichBaoCao->boMon->khoa->tenKhoa }}</p> --}}
                                    @if ($bb->lichBaoCao->boMon)
                                        <p class="mb-0 p-1"><strong>Bộ môn:</strong> {{ $bb->lichBaoCao->boMon->tenBoMon }}</p> 
                                        <p class="mb-0 p-1"><strong>Khoa:</strong> {{ $bb->lichBaoCao->boMon->khoa->tenKhoa }}</p>                       
                                    @else
                                        <p class="mb-0 p-1"><strong>Khoa:</strong> {{ $bb->giangVien->boMon->khoa->tenKhoa ?? 'Chưa xác định' }}</p>
                                    @endif
                                </div>
                                <div class="modal-footer d-flex justify-content-center gap-2">
                                    <form action="{{ route('bienban.destroy', $bb->maBienBan) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm btn-delete">
                                            <i class="fas fa-trash-alt me-1"></i> Xoá
                                        </button>
                                    </form>
                                    <a href="{{ $bb->fileBienBan }}" target="_blank" class="btn btn-success btn-sm">
                                        <i class="fas fa-download me-1"></i> Tải Biên Bản
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="d-flex justify-content-center">
                        <h4>Không Có Biên Bản Nào</h4>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $bienbans->links() }}
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
</script>
@endsection

