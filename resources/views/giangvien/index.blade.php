

@extends('layouts.app')
@section('page-title', "Giảng Viên")
@section('content')
<div class="card shadow mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="m-0 font-weight-bold text-primary">Danh Sách Giảng Viên</h5>
        <form action="{{ route('giangvien.import') }}" method="POST" enctype="multipart/form-data" class="d-inline ms-2">
            @csrf
            <input type="file" name="file" accept=".xlsx,.csv" required class="form-control d-inline-block w-auto">
            <button type="submit" class="btn btn-success">Nhập từ Excel</button>
        </form>
        <a href="{{ route('giangvien.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm Giảng Viên
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('warning') }}</div>
            @endif

            <table class="table table-bordered" id="giangVienTable" width="100%">
                <thead class="thead-light">
                    <tr>
                        <th>Mã Giảng Viên</th>
                        <th>Họ Tên</th>
                        <th>Số điện thoại</th>
                        <th>Email</th>
                        <th>Chức vụ</th>
                        <th >Bộ môn</th>
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
        let table = $('#giangVienTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('giangvien.index') }}",
            columns: [
                { data: 'maGiangVien', name: 'maGiangVien' },
                { 
                    data: null, 
                    name: 'ho_ten', 
                    render: function(data, type, row) {
                        return row.ho + ' ' + row.ten; //Ghép họ và tên trực tiếp trong datatable
                    }
                },
                { data: 'sdt', name: 'sdt' },
                { data: 'email', name: 'email' },
                { data: 'chucVuObj', name: 'chucVuObj' },
                { data: 'bomon', name: 'bomon' },
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
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault(); // Ngăn chặn hành động mặc định của nút
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
                    document.getElementById(formId).submit(); // Submit form bằng JavaScript
                }
            });
        });
    });
</script>
@endsection
