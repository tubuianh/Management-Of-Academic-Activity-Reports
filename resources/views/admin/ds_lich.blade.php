@extends('layouts.app')
@section('page-title', "Lịch Sinh Hoạt Học Thuật")

@section('content')
<div class="card shadow mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="m-0 font-weight-bold text-primary">Danh Sách Lịch</h5>
        
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="lichBaoCaoTable" width="100%">
                <thead class="thead-light">
                    <tr>
                        {{-- <th>#</th> --}}
                        <th>Chủ Đề</th>
                        <th>Ngày Báo Cáo</th>
                        <th>Giờ Báo Cáo</th>
                        <th>Hạn Ngày Nộp</th>
                        <th>Hạn Giờ Nộp</th>
                        <th>Cấp Tổ Chức</th>
                        <th>Giảng Viên Phụ Trách</th>
                        <th style="display: none">Hành động</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        let table = $('#lichBaoCaoTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.ds_lich') }}",
            columns: [
                // { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'chuDe', name: 'chuDe' },
                { data: 'ngayBaoCao', name: 'ngayBaoCao' },
                { data: 'gioBaoCao', name: 'gioBaoCao' },
                { data: 'hanNgayNop', name: 'hanNgayNop' },
                { data: 'hanGioNop', name: 'hanGioNop' },
                { data: 'capToChuc', name: 'capToChuc' },
                { data: 'giangVienPhuTrach', name: 'giangVienPhuTrach' },
                { data: 'hanhdong', name: 'hanhdong', orderable: false, searchable: false, visible: false }
            ],
            language: {
                searchPlaceholder: "Tìm kiếm...",
                lengthMenu: "Hiển thị _MENU_ dòng",
                processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>'
            }
        });
    });

    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        let formId = $(this).data('form');
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
                document.getElementById(formId).submit();
            }
        });
    });
</script>
@endsection
