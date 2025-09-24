@extends('layouts.user')

@section('content')
<style>
    #giangvien-table td,
    #giangvien-table th{
        text-align: center !important;; 
        vertical-align: middle !important;;
    }
    #giangvien-table th,
    #giangvien-table td{
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center !important;;
        vertical-align: middle !important;;
    }
</style>

<div class="fixed-container">
    <div class="form-container">
        <div class="form-header d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><i class="fas fa-users me-2"></i>Danh Sách Giảng Viên {{ $tenBoMon }}</h4>
        </div>

        <div class="form-body">
             <table id="giangvien-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Ảnh đại diện</th>
                        <th>Mã giảng viên</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Chức vụ</th>
                        <th>Bộ môn</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dsGiangVien as $gv)
                        <tr>
                            <td>
                                <img src="{{ asset('storage/' . ($gv->anhDaiDien ?? 'anhDaiDiens/anhmacdinh.jpg')) }}" 
                                    alt="Ảnh đại diện" 
                                    class="rounded-circle me-2"
                                    style="width: 32px; height: 32px; object-fit: cover;"
                                    onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($gv->ho . ' ' . $gv->ten) }}&background=0D8ABC&color=fff';">
                            </td>
                            <td>{{ $gv->maGiangVien }}</td>
                            <td>{{ $gv->ho }} {{ $gv->ten }}</td>
                            <td>{{ $gv->email }}</td>
                            <td>{{ $gv->chucVuObj->tenChucVu ?? 'Không rõ' }}</td>
                            <td>{{ $gv->boMon->tenBoMon ?? 'Không rõ' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function () {
        $('#giangvien-table').DataTable({
            language: {
                "search": "Tìm kiếm:",
                "lengthMenu": "Hiển thị _MENU_ dòng",
                "info": "Hiển thị _START_ đến _END_ trong tổng số _TOTAL_ mục",
                "paginate": {
                    "first": "Đầu",
                    "last": "Cuối",
                    "next": "Tiếp",
                    "previous": "Trước"
                },
                "zeroRecords": "Không tìm thấy kết quả phù hợp",
                "infoEmpty": "Không có dữ liệu",
                "infoFiltered": "(lọc từ _MAX_ mục)"
            }
        });
    });
</script>
@endsection
