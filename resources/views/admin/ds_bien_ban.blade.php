
@extends('layouts.app')
@section('page-title', "Danh Sách Biên Bản")
@section('content')
<div class="card shadow mb-4">
     @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="m-0 font-weight-bold text-primary">Danh Sách Biên Bản </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="bienBanTable" width="100%">
                <thead class="thead-light">
                    <tr>
                        {{-- <th>#</th> --}}
                        <th>Mã Biên Bản</th>
                        <th>Ngày Gửi</th>
                        <th>Chủ Đề</th>
                        <th>Cấp Tổ Chức</th>
                        <th>Trạng Thái</th>
                        <th>Hành Động</th>
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
    $('#bienBanTable').DataTable({
         processing: true,
            serverSide: true,
             ajax: "{{ route('admin.ds_bien_ban') }}",
            columns: [
                // { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'maBienBan', name: 'maBienBan' ,width:'150px'},
                { data: 'ngayNop', name: 'ngayNop',width:'150px' },
                { data: 'chuDe', name: 'chuDe' ,width:'150px'},
                // { data: 'boMon', name: 'boMon',width:'150px' },
                // { data: 'khoa', name: 'khoa' ,width:'150px'},
                { data: 'capToChuc', name: 'capToChuc' },
                { data: 'trangThai', name: 'trangThai' },
                { data: 'hanhdong', name: 'hanhdong', orderable: false, searchable: false }
            ],
            language: {
                searchPlaceholder: "Tìm kiếm...",
                lengthMenu: "Hiển thị _MENU_ dòng",
                processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>'
            }
    });
});

 $(document).ready(function() {
        $(document).on('click', '.btn-xacnhan', function(e) {
            e.preventDefault(); // Ngăn chặn hành động mặc định của nút
            let formId = $(this).data('form');
            Swal.fire({
                title: "Bạn có chắc chắn muốn xác nhận phiếu biên bản SHHT?",
                text: "Bạn sẽ không thể khôi phục lại dữ liệu này!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Xác nhận!"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit(); // Submit form bằng JavaScript
                }
            });
        });
    });
    $(document).ready(function() {
        $(document).on('click', '.btn-tuchoi', function(e) {
            e.preventDefault(); // Ngăn chặn hành động mặc định của nút
            let formId = $(this).data('form');
            Swal.fire({
                title: "Bạn có chắc chắn muốn từ chối biên bản SHHT?",
                text: "Bạn sẽ không thể khôi phục lại dữ liệu này!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Từ chối!"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit(); // Submit form bằng JavaScript
                }
            });
        });
    });

   
</script>
@endsection
