@extends('layouts.user')

@section('content')

<div class="fixed-container">
    <div class="form-container">
        <div class="form-header">
            <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Đăng ký sinh hoạt học thuật</h4>
        </div>
        <div class="form-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <form action="{{ route('dangkybaocao.store') }}" method="POST">
                @csrf
                @php
                    $lichChuaDangKy = $lichBaoCaos->reject(function ($lich) use ($daDangKyIds) {
                        return in_array($lich->maLich, $daDangKyIds);
                    });
                @endphp

                @if ($lichChuaDangKy->isEmpty())
                    <div class="alert alert-warning mt-3">
                        Tất cả các chủ đề đã được đăng ký. Vui lòng chờ chủ đề mới.
                    </div>
                @endif
                <!-- Chọn lịch báo cáo -->
                <div class="form-section">
                    <div class="section-header">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Chọn chủ đề báo cáo</h5>
                    </div>
                    <div class="section-body">
                        <select name="lichBaoCao_id" id="lichBaoCao_id" class="form-select" required>
                            <option value="">-- Chọn chủ đề báo cáo --</option>
                            @foreach($lichBaoCaos as $lich)
                                @if(!in_array($lich->maLich, $daDangKyIds))
                                    <option value="{{ $lich->maLich }}">
                                        {{ $lich->chuDe }} ({{ $lich->ngayBaoCao }})
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        
                    </div>
                </div>

                <!-- Thông tin chi tiết -->
                <div id="thongTinLichBaoCao" class="form-section" style="display:none;">
                    <div class="section-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin chi tiết</h5>
                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class="col-md-6" id="boMonGroup">
                                <div class="input-group">
                                    <span class="input-group-text" style="width: 40px;"><i class="fas fa-building"></i></span>
                                    <input type="text" id="boMon" class="form-control" readonly placeholder="Bộ môn">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text" style="width: 40px;"><i class="fas fa-university"></i></span>
                                    <input type="text" id="khoa" class="form-control" readonly placeholder="Khoa">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text" style="width: 40px;"><i class="fas fa-clock"></i></span>
                                    <input type="text" id="thoiGian" class="form-control" readonly placeholder="Thời gian">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text" style="width: 40px;"><i class="fas fa-map-marker-alt"></i></span>
                                    <input type="text" id="diaDiem" class="form-control" readonly placeholder="Địa điểm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chọn báo cáo -->
                <div class="form-section">
                    <div class="section-header">
                        <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Chọn báo cáo</h5>
                    </div>
                    <div class="section-body">
                        <div id="baoCaoList" class="list-group">
                        </div>
                        @if ($errors->has('baoCao_ids'))
                            <div class="text-danger mt-2">
                                {{ $errors->first('baoCao_ids') }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- File đính kèm -->
                <div id="fileBaoCaoList" class="form-section" style="display:none;">
                    <div class="section-header" style="display:none;">
                        <h5 class="mb-0"><i class="fas fa-paperclip me-2"></i>File đính kèm</h5>
                    </div>
                    <div class="section-body" style="display:none;">
                        <ul id="fileList" class="list-group">
                        </ul>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-center gap-2 mt-4">
                    <a href="{{ route('dangkybaocao.index') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-2"></i>Đăng ký
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

    const lichData = @json($lichBaoCaos);
    const baoCaos = @json($baoCaos);
    const giangViens = @json($giangViens);

    document.getElementById('lichBaoCao_id').addEventListener('change', function () {
        const selectedId = this.value;
        const lich = lichData.find(item => item.maLich == selectedId);
        const filtered = baoCaos.filter(b => b.lich_bao_cao_id == selectedId);

        const thongTinDiv = document.getElementById('thongTinLichBaoCao');
        const boMonGroup = document.getElementById('boMonGroup');

        if (lich) {
            thongTinDiv.style.display = 'block';

            const isCapKhoa = lich.bo_mon === null;

            if (isCapKhoa) {
                boMonGroup.style.display = 'none';
                document.getElementById('khoa').value = {!! json_encode($tenKhoa) !!};
                document.getElementById('diaDiem').value = 'VP Khoa ' + {!! json_encode($tenKhoa) !!};
            } else {
                boMonGroup.style.display = 'block';
                document.getElementById('boMon').value = lich.bo_mon?.tenBoMon || 'Không rõ';
                document.getElementById('khoa').value = lich.bo_mon?.khoa?.tenKhoa || 'Không rõ';
                document.getElementById('diaDiem').value = 'VP BM ' + (lich.bo_mon?.tenBoMon || '');
            }

            document.getElementById('thoiGian').value = lich.gioBaoCao + ', ' + lich.ngayBaoCao;
        } else {
            thongTinDiv.style.display = 'none';
        }

        // Hiển thị danh sách báo cáo
        let html = '';
        let fileHtml = '';

        if (filtered.length > 0) {
            filtered.forEach(b => {
                const gv = giangViens.find(gv => gv.maGiangVien == b.giangVien_id);
                const tenGV = gv ? (gv.ho + ' ' + gv.ten) : 'Không rõ';

                html += `
                    <div class="list-group-item">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="baoCao_ids[]"
                                   value="${b.maBaoCao}" id="bc${b.maBaoCao}">
                            <label class="form-check-label" for="bc${b.maBaoCao}">
                                <strong>${b.tenBaoCao}</strong><br>
                                <small class="text-muted">
                                    <i class="fas fa-user me-1"></i>${tenGV} |
                                    <i class="fas fa-calendar me-1"></i>${b.ngayNop} |
                                    <i class="fas fa-file me-1"></i>${b.dinhDang}
                                </small>
                            </label>
                        </div>
                    </div>
                `;

                if (b.duongDanFile) {
                    fileHtml += `
                        <li class="list-group-item">
                            <i class="fas fa-file-download me-2"></i>
                            <a href="${b.duongDanFile}" class="text-decoration-none">
                                ${b.tenBaoCao}
                            </a>
                        </li>
                    `;
                }
            });
        } else {
            html = '<div class="text-center text-muted py-3">Không có báo cáo nào phù hợp.</div>';
        }

        document.getElementById('baoCaoList').innerHTML = html;
        if (document.getElementById('fileList')) {
            document.getElementById('fileList').innerHTML = fileHtml;
        }
    });
</script>

@endsection

