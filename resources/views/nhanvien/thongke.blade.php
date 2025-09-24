@extends('layouts.user')
@php use Illuminate\Support\Str; @endphp
@section('content')
<style>
    #table-giangvien td,
    #table-giangvien th,
    #table-tggiangvien th,
    #table-tggiangvien td,
    #table-tgbomon th,
    #table-tgbomon td,
    #table-tgkhoa th,
    #table-tgkhoa td,
    .table-bomon th,
    .table-bomon td {
        text-align: center !important;; 
        vertical-align: middle !important;;
    }
    #table-giangvien th,
    #table-giangvien td,
    #table-tggiangvien th,
    #table-tggiangvien td,
    #table-tgbomon th,
    #table-tgbomon td,
    #table-tgkhoa th,
    #table-tgkhoa td,
    .table-bomon th,
    .table-bomon td{
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center !important;;
        vertical-align: middle !important;;
    }
</style>


{{-- Thống kế báo cáo --}}
<div  class="fixed-container">
    
    <div class="form-container">
        <div class="form-header align-items-center">
            <h3 class="mb-1">📊 Thống Kê Báo Cáo</h3>
            <p><strong>Tổng số báo cáo: </strong> {{ $tongBaoCaoKhoa }}</p>
        </div>
        <div class="form-body">
             <div class="accordion" id="thongkeAccordion">

        {{-- === Giảng viên === --}}
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGV">
                    <i class="fa-solid fa-person-chalkboard me-1"></i> Thống kê theo Giảng viên - Giờ nghiên cứu
                </button>
            </h2>
            <div id="collapseGV" class="accordion-collapse collapse">
                <div class="accordion-body">
                    {{-- Form lọc --}}
                    <form id="form-giangvien" method="GET" class="row g-2 align-items-end mb-3">
                        <div class="col-md-3">
                            <label>Năm học</label>
                            <select name="nam_hoc" class="form-control">
                                <option value="" disabled {{ request('nam_hoc') ? '' : 'selected' }}>-- Năm học --</option>
                                @for ($i = 2024; $i <= 2034; $i++)
                                    @php $value = $i . '-' . ($i + 1); @endphp
                                    <option value="{{ $value }}" {{ request('nam_hoc') == $value ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Học kỳ</label>
                            <select name="hoc_ky" class="form-control">
                                <option value="" disabled {{ request('hoc_ky') ? '' : 'selected' }}>-- Học kỳ --</option>
                                <option value="1" {{ request('hoc_ky') == '1' ? 'selected' : '' }}>Học kỳ 1</option>
                                <option value="2" {{ request('hoc_ky') == '2' ? 'selected' : '' }}>Học kỳ 2</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <button class="btn btn-primary w-100">Lọc</button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('nhanvien.thongke') }}" class="btn btn-primary w-100">Làm mới</a>                        </div>
                    </form> 

                    {{-- Bảng dữ liệu --}}
                     <table class="table table-bordered table-striped text-center" id="table-giangvien" width="100%">                  
                        <thead class="table-light">
                            <tr>
                                <th>Mã giảng viên</th>
                                <th>Họ tên</th>
                                <th>Bộ môn</th>
                                <th>Số báo cáo</th>
                                 <th>Giờ nghiên cứu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($baoCaoByGiangVien as $gv)
                                <tr>
                                    <td>{{ $gv->maGiangVien }}</td>
                                    <td>{{ $gv->ho }} {{ $gv->ten }}</td>
                                    <td>{{ $gv->boMon->tenBoMon ?? 'N/A' }}</td>
                                    <td>{{ $gv->bao_cao_count }}</td>
                                    <td>{{ $gv->gio_nghien_cuu }} giờ</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        {{-- === Bộ môn === --}}
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBM">
                    <i class="fa-solid fa-book-open-reader me-1"></i> Thống kê theo Bộ môn
                </button>
            </h2>
            <div id="collapseBM" class="accordion-collapse collapse">
                <div class="accordion-body">
                    {{-- Form lọc --}}

                    <form id="form-bomon" method="GET" class="row g-2 align-items-end mb-3">
                        <div class="col-md-3">
                            <label>Năm học</label>
                            <select name="nam_hoc" class="form-control">
                                <option value="" disabled {{ request('nam_hoc') ? '' : 'selected' }}>-- Năm học --</option>
                                @for ($i = 2024; $i <= 2034; $i++)
                                    @php $value = $i . '-' . ($i + 1); @endphp
                                    <option value="{{ $value }}" {{ request('nam_hoc') == $value ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Học kỳ</label>
                            <select name="hoc_ky" class="form-control">
                                <option value="" disabled {{ request('hoc_ky') ? '' : 'selected' }}>-- Học kỳ --</option>
                                <option value="1" {{ request('hoc_ky') == '1' ? 'selected' : '' }}>Học kỳ 1</option>
                                <option value="2" {{ request('hoc_ky') == '2' ? 'selected' : '' }}>Học kỳ 2</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <button class="btn btn-primary w-100">Lọc</button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('nhanvien.thongke') }}" class="btn btn-primary w-100">Làm mới</a>
                        </div>
                    </form> 

                    {{-- Dữ liệu từng bộ môn --}}
                     <table class="table table-bordered table-striped text-center">
                        <thead>
                            <tr>
                                <th>Mã bộ môn</th>
                                <th>Tên bộ môn</th>
                                <th>Số lượng báo cáo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($baoCaoByBoMon as $index => $item)
                                <tr>
                                    <td>{{ $item->maBoMon }}</td>
                                    <td>{{ $item->tenBoMon }}</td>
                                    <td>{{ $item->tong_bao_cao }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Không có dữ liệu</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- === Khoa === --}}
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseKhoa">
                    <i class="fa-solid fa-building-columns me-1"></i> Thống kê theo Khoa
                </button>
            </h2>
            <div id="collapseKhoa" class="accordion-collapse collapse">
                <div class="accordion-body">
                    {{-- Form lọc --}}
                    <form id="form-khoa" method="GET" class="row g-2 align-items-end mb-3">
                        <div class="col-md-3">
                            <label>Năm học</label>
                            <select name="nam_hoc" class="form-control">
                                <option value="" disabled {{ request('nam_hoc') ? '' : 'selected' }}>-- Năm học --</option>
                                @for ($i = 2024; $i <= 2034; $i++)
                                    @php $value = $i . '-' . ($i + 1); @endphp
                                    <option value="{{ $value }}" {{ request('nam_hoc') == $value ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Học kỳ</label>
                            <select name="hoc_ky" class="form-control">
                                <option value="" disabled {{ request('hoc_ky') ? '' : 'selected' }}>-- Học kỳ --</option>
                                <option value="1" {{ request('hoc_ky') == '1' ? 'selected' : '' }}>Học kỳ 1</option>
                                <option value="2" {{ request('hoc_ky') == '2' ? 'selected' : '' }}>Học kỳ 2</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <button class="btn btn-primary w-100">Lọc</button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('nhanvien.thongke') }}" class="btn btn-primary w-100">Làm mới</a>
                        </div>
                    </form>
                    {{-- Dữ liệu theo khoa --}}
                    
                     <table class="table table-bordered table-striped text-center">
                        <thead>
                            <tr>
                                <th>Mã khoa</th>
                                <th>Tên khoa</th>
                                <th>Số lượng báo cáo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($baoCaoByKhoa as $index => $item)
                                <tr>
                                    <td>{{ $item->maKhoa }}</td>
                                    <td>{{ $item->tenKhoa }}</td>
                                    <td>{{ $item->tong_bao_cao }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Không có dữ liệu</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>
</div>

{{-- Thống kế số lần tham gia  SHHT --}}
<div class="fixed-container mt-3">
    <div class="form-container">
        {{-- HEADER --}}
        <div class="form-header">
            <h3 class="mb-1">📊 Thống Kê Số Lần Tham Gia SHHT</h3>
            @if($namHoc && $hocKy)
                <p class="mb-0"><em>Năm học {{ $namHoc }}, học kỳ {{ $hocKy }}</em></p>
            @endif
        </div>

        <div class="form-body">
        <div class="accordion" id="thongkeAccordion">

            {{-- ================= GIẢNG VIÊN ================= --}}
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseThamGiaGV">
                        <i class="fa-solid fa-person-chalkboard me-1"></i>
                        Thống kê theo Giảng viên
                    </button>
                </h2>
                <div id="collapseThamGiaGV" class="accordion-collapse collapse">
                    <div class="accordion-body">
                        <form id="form-tggiangvien" method="GET" class="row g-2 align-items-end mb-3">
                        <div class="col-md-3">
                            <label>Năm học</label>
                            <select name="nam_hoc" class="form-control">
                                <option value="" disabled {{ request('nam_hoc') ? '' : 'selected' }}>-- Năm học --</option>
                                @for ($i = 2024; $i <= 2034; $i++)
                                    @php $value = $i . '-' . ($i + 1); @endphp
                                    <option value="{{ $value }}" {{ request('nam_hoc') == $value ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Học kỳ</label>
                            <select name="hoc_ky" class="form-control">
                                <option value="" disabled {{ request('hoc_ky') ? '' : 'selected' }}>-- Học kỳ --</option>
                                <option value="1" {{ request('hoc_ky') == '1' ? 'selected' : '' }}>Học kỳ 1</option>
                                <option value="2" {{ request('hoc_ky') == '2' ? 'selected' : '' }}>Học kỳ 2</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <button class="btn btn-primary w-100">Lọc</button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('nhanvien.thongke') }}" class="btn btn-primary w-100">Làm mới</a>
                        </div>
                    </form>
                    <table class="table table-bordered table-striped text-center" id="table-tggiangvien" width="100%">                  
                        <thead>
                            <tr>
                                <th>Mã giảng viên</th>
                                <th>Tên giảng viên</th>
                                <th>Số lần tham gia</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($thamGiaByGiangVien as $index => $item)
                                <tr>
                                    <td>{{ $item->maGiangVien }}</td>
                                    <td>{{ $item->ho }} {{ $item->ten }}</td>
                                    <td>{{ $item->tham_gia_count  }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Không có dữ liệu</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>

            {{-- ================= BỘ MÔN ================= --}}
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseThamGiaBM">
                        <i class="fa-solid fa-book-open-reader me-1"></i>
                        Thống kê theo Bộ môn
                    </button>
                </h2>
                <div id="collapseThamGiaBM" class="accordion-collapse collapse">
                    <div class="accordion-body">
                        <form id="form-tgbomon" method="GET" class="row g-2 align-items-end mb-3">
                        <div class="col-md-3">
                            <label>Năm học</label>
                            <select name="nam_hoc" class="form-control">
                                <option value="" disabled {{ request('nam_hoc') ? '' : 'selected' }}>-- Năm học --</option>
                                @for ($i = 2024; $i <= 2034; $i++)
                                    @php $value = $i . '-' . ($i + 1); @endphp
                                    <option value="{{ $value }}" {{ request('nam_hoc') == $value ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Học kỳ</label>
                            <select name="hoc_ky" class="form-control">
                                <option value="" disabled {{ request('hoc_ky') ? '' : 'selected' }}>-- Học kỳ --</option>
                                <option value="1" {{ request('hoc_ky') == '1' ? 'selected' : '' }}>Học kỳ 1</option>
                                <option value="2" {{ request('hoc_ky') == '2' ? 'selected' : '' }}>Học kỳ 2</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <button class="btn btn-primary w-100">Lọc</button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('nhanvien.thongke') }}" class="btn btn-primary w-100">Làm mới</a>
                        </div>
                    </form>
                        <table id="table-tgbomon" class="table table-bordered table-striped text-center" width="100%">
                            <thead class="table-light">
                                <tr>
                                    <th>Mã bộ môn</th>
                                    <th>Tên bộ môn</th>
                                    <th>Số lần tham gia</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($thamGiaByBoMon as $bm)
                                    <tr>
                                        <td>{{ $bm->maBoMon }}</td>
                                        <td>{{ $bm->tenBoMon }}</td>
                                        <td>{{ $bm->tham_gia_count }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ================= KHOA ================= --}}
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseThamGiaKhoa">
                        <i class="fa-solid fa-building-columns me-1"></i>
                        Thống kê theo Khoa
                    </button>
                </h2>
                <div id="collapseThamGiaKhoa" class="accordion-collapse collapse">
                    <div class="accordion-body">
                        <form id="form-tgkhoa" method="GET" class="row g-2 align-items-end mb-3">
                        <div class="col-md-3">
                            <label>Năm học</label>
                            <select name="nam_hoc" class="form-control">
                                <option value="" disabled {{ request('nam_hoc') ? '' : 'selected' }}>-- Năm học --</option>
                                @for ($i = 2024; $i <= 2034; $i++)
                                    @php $value = $i . '-' . ($i + 1); @endphp
                                    <option value="{{ $value }}" {{ request('nam_hoc') == $value ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Học kỳ</label>
                            <select name="hoc_ky" class="form-control">
                                <option value="" disabled {{ request('hoc_ky') ? '' : 'selected' }}>-- Học kỳ --</option>
                                <option value="1" {{ request('hoc_ky') == '1' ? 'selected' : '' }}>Học kỳ 1</option>
                                <option value="2" {{ request('hoc_ky') == '2' ? 'selected' : '' }}>Học kỳ 2</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <button class="btn btn-primary w-100">Lọc</button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('nhanvien.thongke') }}" class="btn btn-primary w-100">Làm mới</a>
                        </div>
                    </form>
                        <table id="table-tgkhoa" class="table table-bordered table-striped text-center" width="100%">
                            <thead class="table-light">
                                <tr>
                                    <th>Mã khoa</th>
                                    <th>Tên khoa</th>
                                    <th>Số lần tham gia</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($thamGiaByKhoa as $khoa)
                                    <tr>
                                        <td>{{ $khoa->maKhoa }}</td>
                                        <td>{{ $khoa->tenKhoa }}</td>
                                        <td>{{ $khoa->tham_gia_count }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div><!-- /.accordion -->
        </div><!-- /.form-body -->
    </div>
   
</div>


{{-- /////////// --}}
<style>
    .small-chart {
        max-width: 300px;
        margin: auto;
    }
</style>
<div class="fixed-container mt-3">
    <div class="form-container">
        {{-- HEADER --}}
        <div class="form-header">
            <h3 class="mb-1">📊 Biểu đồ thống kê</h3>
            @if($namHoc && $hocKy)
                <p class="mb-0"><em>Năm học {{ $namHoc }}, học kỳ {{ $hocKy }}</em></p>
            @endif
        </div>

        <div class="form-body">
            <div class="accordion" id="thongkeAccordion">
                <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseBieuDo">
                        <i class="fa-solid fa-person-chalkboard me-1"></i>
                        Biểu đồ tròn: Số báo cáo & Số lần tham gia SHHT theo Bộ môn, Khoa
                    </button>
                </h2>
                <div id="collapseBieuDo" class="accordion-collapse collapse">
                    <div class="accordion-body">
                        <form id="form-bieudo" method="GET" class="row g-2 align-items-end mb-3">
                            <div class="col-md-3">
                                <label>Năm học</label>
                                <select name="nam_hoc" class="form-control">
                                    <option value="" disabled {{ request('nam_hoc') ? '' : 'selected' }}>-- Năm học --</option>
                                    @for ($i = 2024; $i <= 2034; $i++)
                                        @php $value = $i . '-' . ($i + 1); @endphp
                                        <option value="{{ $value }}" {{ request('nam_hoc') == $value ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label>Học kỳ</label>
                                <select name="hoc_ky" class="form-control">
                                    <option value="" disabled {{ request('hoc_ky') ? '' : 'selected' }}>-- Học kỳ --</option>
                                    <option value="1" {{ request('hoc_ky') == '1' ? 'selected' : '' }}>Học kỳ 1</option>
                                    <option value="2" {{ request('hoc_ky') == '2' ? 'selected' : '' }}>Học kỳ 2</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <button class="btn btn-primary w-100">Lọc</button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('nhanvien.thongke') }}" class="btn btn-primary w-100">Làm mới</a>
                            </div>
                        </form>
           

    <div class="row row-cols-1 row-cols-md-2 g-4">

        {{-- Biểu đồ tròn: Số báo cáo theo bộ môn --}}
        <div class="col text-center">
            <h6>Số báo cáo theo Bộ môn</h6>
            <div class="small-chart">
                <canvas id="pieChartBaoCaoBoMon"></canvas>
            </div>
        </div>

        {{-- Biểu đồ tròn: Số báo cáo theo khoa --}}
        <div class="col text-center">
            <h6>Số báo cáo theo Khoa</h6>
            <div class="small-chart">
                <canvas id="pieChartBaoCaoKhoa"></canvas>
            </div>
        </div>

        {{-- Biểu đồ tròn: Tham gia SHHT theo bộ môn --}}
        <div class="col text-center">
            <h6>Số lần tham gia SHHT theo Bộ môn</h6>
            <div class="small-chart">
                <canvas id="pieChartThamGiaBoMon"></canvas>
            </div>
        </div>

        {{-- Biểu đồ tròn: Tham gia SHHT theo khoa --}}
        <div class="col text-center">
            <h6>Số lần tham gia SHHT theo Khoa</h6>
            <div class="small-chart">
                <canvas id="pieChartThamGiaKhoa"></canvas>
            </div>
        </div>

    </div>

                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
     <a href="{{ route('thongke.export', request()->all()) }}" style="margin-left: 50%; transform: translateX(-50%);"  class="btn btn-success mb-3 mt-3">
        <i class="fas fa-file-excel"></i> Xuất Excel
    </a>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    $(document).ready(function () {
        // Khởi tạo tất cả bảng có class "datatable"
        $('.datatable').DataTable({
            responsive: true,
            pageLength: 10,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/vi.json'
            }
        });
    });
    
    //đóng mở Accordion
    document.addEventListener('DOMContentLoaded', function () {
    // Khi submit form => lưu accordion đang mở
    const forms = ['giangvien', 'bomon', 'khoa', 'tggiangvien','tgbomon','tgkhoa','bieudo'];

    forms.forEach((key) => {
        const form = document.getElementById(`form-${key}`);
        if (form) {
            form.addEventListener('submit', function () {
                const openAccordion = document.querySelector('.accordion-collapse.show');
                if (openAccordion) {
                    const id = openAccordion.id;
                    localStorage.setItem('openAccordion', id);
                }
            });
        }
    });


    // Khi load lại trang => mở lại accordion đã lưu
    const savedId = localStorage.getItem('openAccordion');
    if (savedId) {
        const accordionContent = document.getElementById(savedId);
        const toggleButton = document.querySelector(`button[data-bs-target="#${savedId}"]`);

        if (accordionContent && toggleButton) {
            // Mở accordion
            accordionContent.classList.add('show');
            // Cập nhật nút toggle đúng trạng thái mở
            toggleButton.classList.remove('collapsed');
            toggleButton.setAttribute('aria-expanded', 'true');
        }
    }

    // Khi accordion bị đóng => xóa key
    document.querySelectorAll('.accordion-collapse').forEach(item => {
        item.addEventListener('hidden.bs.collapse', () => {
            const saved = localStorage.getItem('openAccordion');
            if (saved === item.id) {
                localStorage.removeItem('openAccordion');
            }
        });
    });
});

    $(document).ready(function () {
        $('#table-giangvien').DataTable({
            paging: true,
            ordering: true,
            searching: true,
            language: {
                 url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/vi.json'
            }
        });

        $('#table-bomon').DataTable({
            paging: false,
            ordering: true,
            searching: true,
            language: {
                 url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/vi.json'
            }
        });

        $('#table-khoa').DataTable({
            paging: false,
            ordering: true,
            searching: true,
            language: {
                 url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/vi.json'
            }
        });

        $('#table-tggiangvien').DataTable({
            paging: true,
            ordering: true,
            searching: true,
            language: {
                 url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/vi.json'
            }
        });

        $('#table-tgbomon').DataTable({
            paging: false,
            ordering: true,
            searching: true,
            language: {
                 url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/vi.json'
            }
        });

        $('#table-tgkhoa').DataTable({
            paging: false,
            ordering: true,
            searching: true,
            language: {
                 url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/vi.json'
            }
        });

       
    });

    // Dữ liệu từ server Blade => JS
   const baoCaoBoMonLabels = {!! json_encode($baoCaoByBoMon->pluck('tenBoMon')) !!};
    const baoCaoBoMonData = {!! json_encode($baoCaoByBoMon->pluck('tong_bao_cao')) !!};

    const baoCaoKhoaLabels = {!! json_encode($baoCaoByKhoa->pluck('tenKhoa')) !!};
    const baoCaoKhoaData = {!! json_encode($baoCaoByKhoa->pluck('tong_bao_cao')) !!};

    const thamGiaBoMonLabels = {!! json_encode($thamGiaByBoMon->pluck('tenBoMon')) !!};
    const thamGiaBoMonData = {!! json_encode($thamGiaByBoMon->pluck('tham_gia_count')) !!};

    const thamGiaKhoaLabels = {!! json_encode($thamGiaByKhoa->pluck('tenKhoa')) !!};
    const thamGiaKhoaData = {!! json_encode($thamGiaByKhoa->pluck('tham_gia_count')) !!};

    function generateColors(length) {
        const colors = [];
        for (let i = 0; i < length; i++) {
            colors.push(`hsl(${Math.floor(360 * Math.random())}, 70%, 60%)`);
        }
        return colors;
    }

    function createPieChart(ctxId, labels, data) {
        new Chart(document.getElementById(ctxId), {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: generateColors(labels.length)
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }

    createPieChart('pieChartBaoCaoBoMon', baoCaoBoMonLabels, baoCaoBoMonData);
    createPieChart('pieChartBaoCaoKhoa', baoCaoKhoaLabels, baoCaoKhoaData);
    createPieChart('pieChartThamGiaBoMon', thamGiaBoMonLabels, thamGiaBoMonData);
    createPieChart('pieChartThamGiaKhoa', thamGiaKhoaLabels, thamGiaKhoaData);


</script>

@endsection

