
@extends('layouts.user')

@section('page-title', "Cập Nhật Lịch Báo Cáo")

@section('content')

<div class="fixed-container">
    <div class="form-container">
        <div class="form-header">
            <h4 class="mb-0"><i class="fas fa-edit me-1"></i>Cập Nhật Lịch Sinh Hoạt Học Thuật</h4>
        </div>
        <div class="form-body">
            <form action="{{ route('lichbaocao.update', $lich->maLich) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    {{-- Chủ Đề --}}
                    <div class="form-section">
                        <div class="section-header">
                            <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Chủ đề</h5>
                        </div>
                        <div class="section-body">
                            <input type="text" class="form-control @error('chuDe') is-invalid @enderror" id="chuDe" name="chuDe" value="{{ old('chuDe', $lich->chuDe) }}">
                            @error('chuDe')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror   
                        </div>
                    </div>
                    
                    {{-- Ngày Báo Cáo / giờ báo cáo --}}

                    <div class="form-section">
                        <div class="section-header">
                            <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Thời gian Báo Cáo</h5>
                        </div>
                        <div class="section-body row g-3">
                            <div class="col-md-6">
                                <label for="ngayBaoCao" class="form-label">Ngày báo cáo</label>
                                <input type="date" class="form-control @error('ngayBaoCao') is-invalid @enderror" id="ngayBaoCao" name="ngayBaoCao" value="{{ old('ngayBaoCao', $lich->ngayBaoCao) }}">
                                @error('ngayBaoCao')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="gioBaoCao" class="form-label">Giờ báo cáo</label>
                                <input type="time" class="form-control @error('gioBaoCao') is-invalid @enderror" id="gioBaoCao" name="gioBaoCao" value="{{ old('gioBaoCao', $lich->gioBaoCao) }}">
                                @error('gioBaoCao')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    

                     {{-- Hạn ngày nộp --}}  {{-- Hạn giờ nộp --}}

                    <div class="form-section">
                        <div class="section-header">
                            <h5 class="mb-0"><i class="fas fa-hourglass-end me-2"></i>Hạn Nộp Báo Cáo</h5>
                        </div>
                        <div class="section-body row g-3">
                            <div class="col-md-6">
                                <label for="hanNgayNop" class="form-label">Hạn ngày nộp</label>
                                <input type="date" class="form-control @error('hanNgayNop') is-invalid @enderror" id="hanNgayNop" name="hanNgayNop" value="{{ old('hanNgayNop', $lich->hanNgayNop) }}">
                                @error('hanNgayNop')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="hanGioNop" class="form-label">Hạn giờ nộp</label>
                                <input type="time" class="form-control @error('hanGioNop') is-invalid @enderror" id="hanGioNop" name="hanGioNop" value="{{ old('hanGioNop', $lich->hanGioNop) }}">
                                @error('hanGioNop')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    

                   {{-- Bộ môn (chỉ hiện nếu không phải cấp khoa) --}}
                    @if(!$isCapKhoa)
                    <div class="form-section">
                        <div class="section-header">
                            <h5><i class="fas fa-book-open-reader me-2"></i>Bộ Môn</h5>
                        </div>  
                        <div class="section-body row g-3">
                            <select name="boMon_id" class="form-control @error('boMon_id') is-invalid @enderror">
                            @foreach($boMons as $boMon)
                                <option value="{{ $boMon->maBoMon }}" {{ old('boMon_id', $lich->boMon_id) == $boMon->maBoMon ? 'selected' : '' }}>
                                    {{ $boMon->tenBoMon }}
                                </option>
                            @endforeach
                        </select>
                        @if($errors->has('boMon_id'))
                            <div class="invalid-feedback">
                                {{ $errors->first('boMon_id') }}
                            </div>
                        @endif
                        </div>      
                        
                    </div>
                    
                    @endif

                    @if($isCapKhoa)
                        <div class="form-section">
                            <div class="section-header">
                                <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Khoa</h5>
                            </div>
                            <div class="section-body">
                                <input type="text" class="form-control" id="khoa" name="khoa" value="{{ $tenKhoa }}" readonly>                  
                            </div>
                        </div>        
                    @endif
                    {{-- Giảng viên phụ trách --}}
                    <div class="form-section">
                        <div class="section-header">
                            <h5>
                                <i class="fas fa-users me-2"></i>
                                {{ $isCapKhoa ? 'Giảng Viên Phụ Trách (Cấp Khoa)' : 'Giảng Viên Phụ Trách' }}
                            </h5>   
                        </div>
                        <div class="section-body">
                            <div class="row">
                            @foreach($giangViens as $gv)
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" name="giangVienPhuTrach[]" class="form-check-input"
                                        value="{{ $gv->maGiangVien }}"
                                        {{ in_array($gv->maGiangVien, $lich->giangVienPhuTrach->pluck('maGiangVien')->toArray()) ? 'checked' : '' }}>
                                    <label class="form-check-label">
                                        {{ $gv->ho }} {{ $gv->ten }}
                                        @if($isCapKhoa)
                                            <small class="text-muted">({{ $gv->boMon->tenBoMon ?? '' }})</small>
                                        @endif
                                    </label>
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
                                                <input class="form-check-input" type="checkbox" name="giangVienPhuTrach[]" value="{{ $gv->maGiangVien }}" id="gv_{{ $gv->maGiangVien }}">
                                                <label class="form-check-label" for="gv_{{ $gv->maGiangVien }}">
                                                    {{ $gv->ho }} {{ $gv->ten }} <small class="text-muted">({{ $boMon->tenBoMon }})</small>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Nút bấm --}}
                <div class="d-flex justify-content-center gap-2 mt-4">
                    <a href="{{ route('lichbaocao.index') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-2"></i>Cập Nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
   $(document).ready(function() {
    let selectedGiangVien = new Set($('input[name="giangVienPhuTrach[]"]:checked').map(function() {
        return this.value;
    }).get());

    $('#boMon_id').change(function() {
        var boMonId = $(this).val();
        $('#giangVienContainer').html('<p>Đang tải danh sách giảng viên...</p>');

        $.ajax({
            url: `/lichbaocao/giangviens/${boMonId}`,
            type: "GET",
            dataType: "json",
            success: function(data) {
                var giangVienHtml = "";
                if (data.length === 0) {
                    giangVienHtml = "<p>Không có giảng viên nào.</p>";
                } else {
                    data.forEach(function(giangVien, index) {
                        if (index % 3 === 0) giangVienHtml += '<div class="row">';

                        let checked = selectedGiangVien.has(giangVien.maGiangVien) ? 'checked' : '';

                        giangVienHtml += `
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" name="giangVienPhuTrach[]" value="${giangVien.maGiangVien}" class="form-check-input" ${checked}>
                                    <label class="form-check-label">${giangVien.ho} ${giangVien.ten}</label>
                                </div>
                            </div>`;

                        if ((index + 1) % 3 === 0) giangVienHtml += '</div>';
                    });
                }
                $('#giangVienContainer').html(giangVienHtml);

                // Cập nhật lại danh sách giảng viên được chọn sau khi load lại
                $('input[name="giangVienPhuTrach[]"]').change(function() {
                    if (this.checked) {
                        selectedGiangVien.add(this.value);
                    } else {
                        selectedGiangVien.delete(this.value);
                    }
                });
            }
        });
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

    

</script>

    
@endsection
