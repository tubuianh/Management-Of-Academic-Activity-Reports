
@extends('layouts.app')
@section('page-title', "Danh Sách Phiếu Đăng Ký Sinh Hoạt Học Thuật")
@section('content')
<div class="card shadow mb-4">
     @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="m-0 font-weight-bold text-primary">Danh Sách Phiếu Đăng Ký Sinh Hoạt Học Thuật </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="phieuDKTable" width="100%">
                <thead class="thead-light">
                <tr>
                    {{-- <th>#</th> --}}
                    <th>Chủ đề</th>
                    <th>Ngày đăng ký</th>
                    <th>Trạng thái</th>
                    <th>Ngày/giờ báo cáo</th>
                    <th>Địa điểm</th>
                    <th>Cấp tổ chức</th>
                    {{-- <th>Khoa</th> --}}
                    <th>Báo cáo viên</th>
                    <th>File báo cáo</th>
                    <th>Hành động</th>
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
    $('#phieuDKTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.ds_phieu_dk") }}',
        columns: [
            // { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'chuDe', name: 'lichBaoCao.chuDe' },
            { data: 'ngayDangKy', name: 'ngayDangKy' },
            { data: 'trangThai', name: 'trangThai'},
            { data: 'ngayGioBaoCao', name: 'lichBaoCao.ngayBaoCao' },
            { data: 'diaDiem',name:'diaDiem'},
            { data: 'capToChuc', name: 'capToChuc' },
            // { data: 'khoa', name: 'lichBaoCao.boMon.khoa.tenKhoa' },
            { data: 'baoCaoVien', name: 'baoCaos.giangVien.ten' },
            { data: 'fileBaoCao', name: 'baoCaos.tenBaoCao' },
            { data: 'hanhDong', name: 'hanhDong', orderable: false, searchable: false,width:'120px' }
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
                title: "Bạn có chắc chắn muốn xác nhận phiếu đăng ký SHHT?",
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
                title: "Bạn có chắc chắn muốn từ chối phiếu đăng ký SHHT?",
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
