

@extends('layouts.user')

@section('content')

<div class="fixed-container">
    <div class="form-container">
        <div class="form-header">
            <h4 class="mb-0"><i class="fas fa-calendar-plus me-2"></i>Tạo Lịch Sinh Hoạt Học Thuật</h4>
        </div>
        <div class="form-body">
            <form action="{{ route('lichbaocao.store') }}" method="POST">
                @csrf

                {{-- cấp tổ chức --}}
                @if($cap !== null)
                    <div class="form-section">
                        <div class="section-header">
                            <h5 class="mb-0"><i class="fas fa-layer-group me-2"></i>Chọn cấp tổ chức</h5>
                        </div>
                        <div class="section-body">
                            <select name="capBaoCao" id="capBaoCao" class="form-control">
                                <option value="bo_mon">Cấp Bộ Môn</option>
                                <option value="khoa">Cấp Khoa</option>
                            </select>
                        </div>
                    </div>
                @endif
                {{-- Chủ đề --}}
                <div class="form-section">
                    <div class="section-header">
                        <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Chủ đề</h5>
                    </div>
                    <div class="section-body">
                        <input type="text" class="form-control @error('chuDe') is-invalid @enderror" name="chuDe" value="{{ old('chuDe') }}" placeholder="Nhập chủ đề">
                        @error('chuDe')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Thời gian báo cáo --}}
                <div class="form-section">
                    <div class="section-header">
                        <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Thời gian Báo Cáo</h5>
                    </div>
                    <div class="section-body row g-3">
                        <div class="col-md-6">
                            <label for="ngayBaoCao" class="form-label">Ngày báo cáo</label>
                            <input type="date" class="form-control @error('ngayBaoCao') is-invalid @enderror" id="ngayBaoCao" name="ngayBaoCao">
                            <small class="text-muted">Ngày báo cáo lớn hơn ngày hiện tại ít nhất 3 ngày!</small>
                            @error('ngayBaoCao')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="gioBaoCao" class="form-label">Giờ báo cáo</label>
                            <input type="time" class="form-control @error('gioBaoCao') is-invalid @enderror" id="gioBaoCao" name="gioBaoCao">
                            @error('gioBaoCao')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Hạn nộp báo cáo --}}
                <div class="form-section">
                    <div class="section-header">
                        <h5 class="mb-0"><i class="fas fa-hourglass-end me-2"></i>Hạn Nộp Báo Cáo</h5>
                    </div>
                    <div class="section-body row g-3">
                        <div class="col-md-6">
                            <label for="hanNgayNop" class="form-label">Hạn ngày nộp</label>
                            <input type="date" class="form-control @error('hanNgayNop') is-invalid @enderror" id="hanNgayNop" name="hanNgayNop">
                            @error('hanNgayNop')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="hanGioNop" class="form-label">Hạn giờ nộp</label>
                            <input type="time" class="form-control @error('hanGioNop') is-invalid @enderror" id="hanGioNop" name="hanGioNop">
                            @error('hanGioNop')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Bộ môn--}}
                <div id="sectionBoMon" class="form-section">
                    <div class="section-header">
                        <h5 class="mb-0"><i class="fa-solid fa-book-open-reader me-1"></i>Bộ Môn</h5>
                    </div>
                    <div class="section-body row g-3">
                        <div class="col-md-12">
                            <select name="boMon_id" id="boMonSelect" class="form-control @error('boMon_id') is-invalid @enderror">
                                <option value="">-- Chọn bộ môn --</option>
                                @foreach($boMons as $boMon)
                                    <option value="{{ $boMon->maBoMon }}">{{ $boMon->tenBoMon }}</option>
                                @endforeach
                            </select>
                            @error('boMon_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                      
                    </div>
                </div>

                 {{-- Giảng viên phụ trách --}}
                 {{-- Giảng viên phụ trách theo Bộ môn --}}
<div id="sectionGiangVienPhuTrach" class="form-section">
    <div class="section-header">
        <h5 class="mb-0"><i class="fas fa-users me-2"></i>Giảng Viên Phụ Trách (Theo Bộ Môn)</h5>
    </div>
    <div class="section-body row g-3">
        <div class="col-md-12">
            <div id="giangVienList">
                @foreach ($boMons as $boMon)
                    <div class="boMon-giangVien" data-bomon="{{ $boMon->maBoMon }}" style="display: none;">
                        <div class="row">
                            @foreach ($boMon->giangViens as $gv)
                                @php
                                    $uid = $boMon->maBoMon . '_' . $gv->maGiangVien;
                                @endphp
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input gv-checkbox"
                                               type="checkbox"
                                               name="giangVienPhuTrach[]"
                                               value="{{ $gv->maGiangVien }}"
                                               id="gv_{{ $uid }}"
                                               onchange="togglePhanBien('{{ $uid }}')">

                                        <label class="form-check-label" for="gv_{{ $uid }}">
                                            {{ $gv->ho }} {{ $gv->ten }}
                                        </label>

                                        {{-- Phản biện --}}
                                        <div class="mt-1 ms-4 d-none phan-bien-select"
                                             id="phanBien_for_{{ $uid }}">
                                            <label class="form-label mb-1">
                                                <i class="fas fa-user-check me-1"></i>Chọn giảng viên phản biện
                                            </label>
                                            <select class="form-select" name="phanBien[{{ $uid }}]">
                                                <option value="">-- Chọn giảng viên phản biện --</option>
                                                @foreach ($boMon->giangViens as $otherGV)
                                                    @if ($otherGV->maGiangVien !== $gv->maGiangVien)
                                                        <option value="{{ $otherGV->maGiangVien }}">
                                                            {{ $otherGV->ho }} {{ $otherGV->ten }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>


                {{-- GIẢNG VIÊN CẤP KHOA --}}
                <div id="sectionGiangVienKhoa" style="display:none">
                    <div class="form-section">
                        <div class="section-header">
                            <h5 class="mb-0"><i class="fas fa-users me-2"></i>Giảng Viên Phụ Trách (Toàn Khoa)</h5>
                        </div>
                        <div class="section-body">
                            <div class="row">
                                @foreach($khoa?->boMons ?? [] as $boMon)
                                    @foreach($boMon->giangViens as $gv)
                                        <div class="col-md-4 mb-1">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="giangVienPhuTrach[]" value="{{ $gv->maGiangVien }}" id="gv_{{ $gv->maGiangVien }}" onchange="togglePhanBien(this)">
                                                <label class="form-check-label" for="gv_{{ $gv->maGiangVien }}">
                                                    {{ $gv->ho }} {{ $gv->ten }} <small class="text-muted">({{ $boMon->tenBoMon }})</small>
                                                </label>
                                                {{--  --}}
                                                <div class="mt-1 ms-4 d-none" id="phanBien_for_{{ $gv->maGiangVien }}" >
                                                    <label class="form-label mb-1"><i class="fas fa-user-check me-1"></i>Chọn giảng viên phản biện</label>
                                                    <select class="form-select" name="phanBien[{{ $gv->maGiangVien }}]">
                                                        <option value="">-- Chọn giảng viên phản biện --</option>
                                                        @foreach($khoa->boMons as $bmPB)
                                                            @foreach($bmPB->giangViens as $gvPB)
                                                                @if($gvPB->maGiangVien !== $gv->maGiangVien)
                                                                    <option value="{{ $gvPB->maGiangVien }}">{{ $gvPB->ho }} {{ $gvPB->ten }} ({{ $bmPB->tenBoMon }})</option>
                                                                @endif
                                                            @endforeach
                                                        @endforeach
                                                    </select>
                                                </div>

                                                {{--  --}}
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="d-flex justify-content-center gap-2 mt-4">
                    <a href="{{ route('lichbaocao.index') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-2"></i>Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

    $(document).ready(function() {
        
       $('#boMonSelect').on('change', function () {
    const selectedBoMon = $(this).val();
    $('.boMon-giangVien').hide(); // Ẩn tất cả
    $(`.boMon-giangVien[data-bomon="${selectedBoMon}"]`).show(); // Hiện đúng bộ môn
});
        

        $(document).ready(function() {
            $('#ngayBaoCao').change(function () {
                let ngayBaoCao = new Date(this.value);
                let minDate = new Date(); // Hôm nay
                let maxDate = new Date(ngayBaoCao);
                maxDate.setDate(maxDate.getDate() - 3); // Ngày báo cáo - 3 ngày

                let minDateFormatted = minDate.toISOString().split("T")[0];
                let maxDateFormatted = maxDate.toISOString().split("T")[0];

                $('#hanNgayNop').attr('min', minDateFormatted);
                $('#hanNgayNop').attr('max', maxDateFormatted);
            });
        });
    });

     $(document).ready(function () {
        // Toggle cấp báo cáo
        $('#capBaoCao').change(function () {
            const selected = $(this).val();
            if (selected === 'khoa') {
                $('#sectionBoMon').hide();
                $('#sectionGiangVienPhuTrach').hide();
                $('#sectionGiangVienKhoa').show();
            } else {
                $('#sectionBoMon').show();
                $('#sectionGiangVienPhuTrach').show();
                $('#sectionGiangVienKhoa').hide();
            }
        });

        // Gọi lại logic hanNop khi chọn ngày báo cáo
        $('#ngayBaoCao').change(function () {
            let ngayBaoCao = new Date(this.value);
            let minDate = new Date(); 
            let maxDate = new Date(ngayBaoCao);
            maxDate.setDate(maxDate.getDate() - 3);

            let minDateFormatted = minDate.toISOString().split("T")[0];
            let maxDateFormatted = maxDate.toISOString().split("T")[0];

            $('#hanNgayNop').attr('min', minDateFormatted);
            $('#hanNgayNop').attr('max', maxDateFormatted);
        });
        
        $(document).on('change', 'input[name="giangVienPhuTrach[]"]', function () {
    // Tìm đúng checkbox vừa thay đổi
    const checkbox = $(this);
    const gvId = checkbox.val();
    
    // Với bộ môn, cần tìm đúng UID vì có thể bị trùng mã giảng viên ở các bộ môn khác nhau
    // UID được tạo bằng `boMon_maGiangVien`
    const uid = checkbox.attr('id').replace('gv_', '');
    const container = $(`#phanBien_for_${uid}`);

    if (checkbox.is(':checked')) {
        container.removeClass('d-none');
    } else {
        container.addClass('d-none');
        container.find('select').val('');
    }
});


 
function togglePhanBien(checkbox) {
    let maGV = checkbox.value;
    let div = document.getElementById('phanBien_for_' + maGV);
    if (checkbox.checked) {
        div.style.display = 'block';
    } else {
        div.style.display = 'none';

        // Optionally xóa chọn phản biện nếu bỏ chọn giảng viên
        let select = div.querySelector('select');
        if (select) select.value = '';
        
    }
}

function togglePhanBien(uid) {
    const checkbox = document.getElementById('gv_' + uid);
    const div = document.getElementById('phanBien_for_' + uid);

    if (checkbox.checked) {
        div.classList.remove('d-none');
    } else {
        div.classList.add('d-none');
        const select = div.querySelector('select');
        if (select) select.value = '';
    }
}

    });
</script>
@endsection