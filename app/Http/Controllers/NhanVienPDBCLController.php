<?php

namespace App\Http\Controllers;
use App\Models\NhanVienPDBCL;
use Illuminate\Http\Request;
use App\Http\Requests\StoreNhanVienRequest;
use App\Http\Requests\UpdateNhanVienRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\Quyen;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\Models\GiangVien;
use App\Models\BoMon;
use Illuminate\Support\Facades\DB;
use App\Models\Khoa;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Chart\{Chart, DataSeries, DataSeriesValues, Layout, Legend, PlotArea, Title};
class NhanVienPDBCLController extends Controller
{
    public function index(Request $request)
    {

          if ($request->ajax()) {
            return $this->getDataTable();
        }
        
        return view('nhanvien.index');
    }

    private function getDataTable()
    {
        $data = NhanVienPDBCL::query(); 

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('quyen', function ($row) {
                return $row->quyen ? $row->quyen->tenQuyen : 'Không có quyền';
            })
            ->addColumn('ho_ten', function ($row) {
                return $row->ho . ' ' . $row->ten; // Ghép họ và tên
            })
            ->addColumn('anhDaiDien', function ($row) {
                $src = $row->anhDaiDien 
                    ? asset('storage/' . $row->anhDaiDien) 
                    : 'https://ui-avatars.com/api/?name=' . urlencode($row->ho . ' ' . $row->ten) . '&background=0D8ABC&color=fff';
            
                return '<img src="' . $src . '" 
                            alt="Ảnh đại diện" 
                            class="img-fluid rounded-circle shadow" 
                            style="width: 50px; height: 50px; object-fit: cover;">';
            })            
            ->addColumn('hanhdong', function ($row) {
                return view('components.action-buttons', [
                    'row' => $row,
                    'editRoute' => 'nhanvien.edit',
                    'deleteRoute' => 'nhanvien.destroy',
                    'id' => $row->maNV
                ])->render();
            })
            ->rawColumns(['hanhdong', 'anhDaiDien'])
            ->make(true);
    }

    public function create()
    {
        $quyens = Quyen::all();
        return view('nhanvien.create',compact('quyens'));
    }

    public function store(StoreNhanVienRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('anhDaiDien')) {
            $path = $request->file('anhDaiDien')->store('anhDaiDiens', 'public');
            $data['anhDaiDien'] = $path;
        } else {
            $data['anhDaiDien'] = null;
        }

        $data['matKhau'] = bcrypt($data['matKhau']);

        NhanVienPDBCL::create($data);

        return redirect()->route('nhanvien.index')->with('success', 'Thêm nhân viên thành công!');
    }

    public function edit($maNV)
    {
        $quyens = Quyen::all();
        $nhanvien = NhanVienPDBCL::findOrFail($maNV);
        return view('nhanvien.edit', compact('nhanvien','quyens'));
    }


    public function update(UpdateNhanVienRequest $request, $maNV)
    {
        $nhanvien = NhanVienPDBCL::findOrFail($maNV);
        $data = $request->validated();
    
        if ($request->hasFile('anhDaiDien')) {
            // Xóa ảnh cũ nếu không phải ảnh mặc định
            if ($nhanvien->anhDaiDien && $nhanvien->anhDaiDien !== 'anhDaiDiens/anhmacdinh.jpg') {
                Storage::disk('public')->delete($nhanvien->anhDaiDien);
            }
    
            $path = $request->file('anhDaiDien')->store('anhDaiDiens', 'public');
            $data['anhDaiDien'] = $path;
        }
    
        $nhanvien->update($data);
    
        return redirect()->route('nhanvien.index')->with('success', 'Cập nhật nhân viên thành công!');
    }
    
    public function destroy($maNV)
    {
        $nhanvien = NhanVienPDBCL::findOrFail($maNV);

        if ($nhanvien->anhDaiDien && $nhanvien->anhDaiDien !== 'anhDaiDiens/anhmacdinh.jpg') {
            Storage::disk('public')->delete($nhanvien->anhDaiDien);
        }

        $nhanvien->delete();

        return redirect()->route('nhanvien.index')->with('success', 'Xóa nhân viên thành công!');
    }

public function thongKeBaoCao(Request $request)
    {
        // Lọc theo giảng viên
        $fromGV = $request->input('from_gv') ?? '2025-01-01';
        $toGV = $request->input('to_gv') ?? now()->format('Y-m-d');
        $searchGV = $request->input('search_gv');

        $namHoc = $request->input('nam_hoc');
        $hocKy = $request->input('hoc_ky');

        if ($namHoc && $hocKy) {
            [$startYear, $endYear] = explode('-', $namHoc);
            if ($hocKy == '1') {
                $from = Carbon::create($startYear, 8, 1);
                $to = Carbon::create($endYear, 1, 31)->endOfDay();
            } elseif ($hocKy == '2') {
                $from = Carbon::create($endYear, 2, 1);
                $to = Carbon::create($endYear, 6, 30)->endOfDay();
            }
        } else {
            $from = Carbon::create(2025, 1, 1);
            // $to = now()->endOfDay();
            $to = Carbon::create(2040, 1, 1);
        }

        // Bộ môn và khoa dùng chung from/to
        $searchBM = $request->input('search_bm');
        $searchKhoa = $request->input('search_khoa');

        // Thống kê theo giảng viên
        $baoCaoByGiangVien = GiangVien::with('boMon')
            ->withCount(['baoCao' => function ($q) use ($from, $to) {
                $q->whereBetween('ngayNop', [$from, $to]);
            }])
            ->when($searchGV, function ($q) use ($searchGV) {
                $q->where(function ($q2) use ($searchGV) {
                    $q2->where('maGiangVien', 'like', "%$searchGV%")
                        ->orWhere('ho', 'like', "%$searchGV%")
                        ->orWhere('ten', 'like', "%$searchGV%");
                });
            })->get();

        $tongBaoCaoGV = $baoCaoByGiangVien->sum('bao_cao_count');
        $baoCaoByGiangVien = $baoCaoByGiangVien->map(function ($gv) {
            $gv->gio_nghien_cuu = $gv->bao_cao_count * 60;
            return $gv;
        });

        // Thống kê theo bộ môn
        $baoCaoByBoMon = BoMon::with(['giangViens' => function ($q) use ($from, $to, $searchBM) {
            $q->withCount(['baoCao' => function ($q2) use ($from, $to) {
                $q2->whereBetween('ngayNop', [$from, $to]);
            }])
            ->when($searchBM, function ($q3) use ($searchBM) {
                $q3->where(function ($q4) use ($searchBM) {
                    $q4->where('maGiangVien', 'like', "%$searchBM%")
                        ->orWhereRaw("CONCAT(ho, ' ', ten) LIKE ?", ["%$searchBM%"]);
                });
            });
        }])
        ->when($searchBM, function ($q) use ($searchBM) {
            $q->where('tenBoMon', 'like', "%$searchBM%");
        })->get();

        $baoCaoByBoMon = $baoCaoByBoMon->map(function ($bm) {
            $bm->tong_bao_cao = $bm->giangViens->sum('bao_cao_count');
            return $bm;
        });

        $tongBaoCaoBM = $baoCaoByBoMon->sum('tong_bao_cao');

        // Thống kê theo khoa
        $baoCaoByKhoa = Khoa::with(['boMons.giangViens' => function ($q) use ($from, $to, $searchKhoa) {
            $q->withCount(['baoCao' => function ($q2) use ($from, $to) {
                $q2->whereBetween('ngayNop', [$from, $to]);
            }])
            ->when($searchKhoa, function ($q3) use ($searchKhoa) {
                $q3->where(function ($q4) use ($searchKhoa) {
                    $q4->where('maGiangVien', 'like', "%$searchKhoa%")
                        ->orWhere('ho', 'like', "%$searchKhoa%")
                        ->orWhere('ten', 'like', "%$searchKhoa%");
                });
            });
        }])->get();

        $baoCaoByKhoa = $baoCaoByKhoa->map(function ($khoa) {
            $khoa->boMons = $khoa->boMons->map(function ($boMon) {
                $boMon->tong_bao_cao = $boMon->giangViens->sum('bao_cao_count');
                return $boMon;
            });
            $khoa->tong_bao_cao = $khoa->boMons->sum('tong_bao_cao');
            return $khoa;
        });

        $tongBaoCaoKhoa = $baoCaoByKhoa->sum('tong_bao_cao');

        // Số lần tham gia giảng viên
        $subGV = DB::table('lich_bao_cao_giang_vien as lgv')
            ->join('lich_bao_caos as lich', 'lich.maLich', '=', 'lgv.lich_bao_cao_id')
            ->join('dang_ky_bao_caos as dk', 'dk.lichBaoCao_id', '=', 'lich.maLich')
            ->where('dk.trangThai', 'Đã Xác Nhận')
            ->whereBetween('lich.ngayBaoCao', [$from, $to])
            ->select('lgv.giang_vien_id', DB::raw('COUNT(DISTINCT lgv.lich_bao_cao_id) as so_lan'))
            ->groupBy('lgv.giang_vien_id');

        $thamGiaByGiangVien = GiangVien::leftJoinSub($subGV, 'tg', 'tg.giang_vien_id', '=', 'giang_viens.maGiangVien')
            ->with('boMon')
            ->select('giang_viens.*', DB::raw('COALESCE(tg.so_lan, 0) as tham_gia_count'))
            ->orderByDesc('tham_gia_count')
            ->get();

        $tongThamGiaGV = $thamGiaByGiangVien->sum('tham_gia_count');

        // Số lần tham gia bộ môn
        $subBM = DB::table('lich_bao_caos as lich')
            ->join('dang_ky_bao_caos as dk', 'dk.lichBaoCao_id', '=', 'lich.maLich')
            ->where('dk.trangThai', 'Đã Xác Nhận')
            ->whereBetween('lich.ngayBaoCao', [$from, $to])
            ->select('lich.boMon_id', DB::raw('COUNT(DISTINCT lich.maLich) as so_lan'))
            ->groupBy('lich.boMon_id');

        $thamGiaByBoMon = BoMon::leftJoinSub($subBM, 'tg', 'tg.boMon_id', '=', 'bo_mons.maBoMon')
            ->select('bo_mons.*', DB::raw('COALESCE(tg.so_lan, 0) as tham_gia_count'))
            ->orderByDesc('tham_gia_count')
            ->get();

        $tongThamGiaBM = $thamGiaByBoMon->sum('tham_gia_count');

        // Số lần tham gia khoa
        $subKhoa = DB::table('lich_bao_caos as lich')
            ->join('dang_ky_bao_caos as dk', 'dk.lichBaoCao_id', '=', 'lich.maLich')
            ->join('bo_mons as bm', 'bm.maBoMon', '=', 'lich.boMon_id')
            ->where('dk.trangThai', 'Đã Xác Nhận')
            ->whereBetween('lich.ngayBaoCao', [$from, $to])
            ->select('bm.maKhoa', DB::raw('COUNT(DISTINCT lich.maLich) as so_lan'))
            ->groupBy('bm.maKhoa');

        $thamGiaByKhoa = Khoa::leftJoinSub($subKhoa, 'tg', 'tg.maKhoa', '=', 'khoas.maKhoa')
            ->select('khoas.*', DB::raw('COALESCE(tg.so_lan, 0) as tham_gia_count'))
            ->orderByDesc('tham_gia_count')
            ->get();

        $tongThamGiaKhoa = $thamGiaByKhoa->sum('tham_gia_count');

        return view('nhanvien.thongke', compact(
            'baoCaoByGiangVien', 'baoCaoByBoMon', 'baoCaoByKhoa',
            'fromGV', 'toGV', 'searchGV', 'searchBM', 'searchKhoa',
            'tongBaoCaoGV', 'tongBaoCaoBM', 'tongBaoCaoKhoa',
            'namHoc', 'hocKy', 'from', 'to',
            'thamGiaByGiangVien', 'tongThamGiaGV',
            'thamGiaByBoMon', 'tongThamGiaBM',
            'thamGiaByKhoa', 'tongThamGiaKhoa'
        ));
    }


public function exportThongKeExcel(Request $request)
{
     // Lấy dữ liệu thống kê dưới dạng object
    $data = $this->thongKeBaoCao($request);

    $spreadsheet = new Spreadsheet();
    $spreadsheet->removeSheetByIndex(0);

    // Sheet thông tin
    $sheets = [
        ['title' => 'Báo Cáo Giảng Viên', 'data' => $data['baoCaoByGiangVien'], 'headers' => ['Mã GV', 'Họ Tên', 'Bộ Môn', 'Số Báo Cáo', 'Giờ Nghiên Cứu']],
        ['title' => 'Báo Cáo Bộ Môn', 'data' => $data['baoCaoByBoMon'], 'headers' => ['Tên Bộ Môn', 'Số Báo Cáo']],
        ['title' => 'Báo Cáo Khoa', 'data' => $data['baoCaoByKhoa'], 'headers' => ['Tên Khoa', 'Số Báo Cáo']],
        ['title' => 'Số Lần Tham Gia SHHT Bộ Môn', 'data' => $data['thamGiaByBoMon'], 'headers' => ['Tên Bộ Môn', 'Số Lần Tham Gia']],
        ['title' => 'Số Lần Tham Gia SHHT Khoa', 'data' => $data['thamGiaByKhoa'], 'headers' => ['Tên Khoa', 'Số Lần Tham Gia']],
    ];

    foreach ($sheets as $sheetInfo) {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle($sheetInfo['title']);
        $sheet->fromArray($sheetInfo['headers'], NULL, 'A1');

        $row = 2;
        foreach ($sheetInfo['data'] as $item) {
            $values = match ($sheetInfo['title']) {
                'Báo Cáo Giảng Viên' => [
                    $item->maGiangVien,
                    $item->ho . ' ' . $item->ten,
                    optional($item->boMon)->tenBoMon,
                    $item->bao_cao_count,
                    $item->gio_nghien_cuu,
                ],
                'Báo Cáo Bộ Môn' => [$item->tenBoMon, $item->tong_bao_cao],
                'Báo Cáo Khoa' => [$item->tenKhoa, $item->tong_bao_cao],
                'Số Lần Tham Gia SHHT Bộ Môn' => [$item->tenBoMon, $item->tham_gia_count],
                'Số Lần Tham Gia SHHT Khoa' => [$item->tenKhoa, $item->tham_gia_count],
                default => [],
            };
            $sheet->fromArray($values, NULL, "A$row");
            $row++;
        }
        // 👉 Tự động giãn cột theo nội dung
        $colCount = count($sheetInfo['headers']);
        for ($col = 0; $col < $colCount; $col++) {
            $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1);
            $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
        }
    }

    // --- Sheet biểu đồ ---
    $chartSheet = $spreadsheet->createSheet();
    $chartSheet->setTitle('Biểu Đồ');

    // ==== Hàm phụ: tạo dữ liệu và biểu đồ ====
    $createChart = function($startRow, $title, $labels, $values, $secondHeader) use ($chartSheet) {
    $rowCount = count($labels);

    // Ghi tiêu đề bảng (VD: Tên Khoa | Số Báo Cáo)
    $chartSheet->fromArray([[$title, $secondHeader]], NULL, "A$startRow");

    // Ghi nhãn và giá trị
    $chartSheet->fromArray(array_map(fn($l) => [$l], $labels), NULL, "A" . ($startRow + 1));
    $chartSheet->fromArray(array_map(fn($v) => [$v], $values), NULL, "B" . ($startRow + 1));

    // Tự động giãn cột
    $chartSheet->getColumnDimension('A')->setAutoSize(true);
    $chartSheet->getColumnDimension('B')->setAutoSize(true);

    // Tạo DataSeries
    $dataSeriesLabels = [new DataSeriesValues('String', "'Biểu Đồ'!B$startRow", null, 1)];
    $xAxisTickValues = [new DataSeriesValues('String', "'Biểu Đồ'!A" . ($startRow + 1) . ":A" . ($startRow + $rowCount), null, $rowCount)];
    $dataSeriesValues = [new DataSeriesValues('Number', "'Biểu Đồ'!B" . ($startRow + 1) . ":B" . ($startRow + $rowCount), null, $rowCount)];

    $series = new DataSeries(
        DataSeries::TYPE_PIECHART,
        null,
        range(0, count($dataSeriesValues) - 1),
        $dataSeriesLabels,
        $xAxisTickValues,
        $dataSeriesValues
    );

    $plotArea = new PlotArea(null, [$series]);
    $legend = new Legend(Legend::POSITION_RIGHT, null, false);
    $chartTitle = new Title($title);

    $chart = new Chart(
        $title,
        $chartTitle,
        $legend,
        $plotArea,
        true,
        0,
        null,
        null
    );

    // Đặt vị trí biểu đồ
    $chart->setTopLeftPosition("D$startRow");
    $chart->setBottomRightPosition("L" . ($startRow + 18));
    $chartSheet->addChart($chart);
};

    // ==== Gọi tạo 4 biểu đồ ====
    $createChart(
        1,
        'Biểu Đồ Báo Cáo Theo Bộ Môn',
        array_column($data['baoCaoByBoMon']->toArray(), 'tenBoMon'),
        array_column($data['baoCaoByBoMon']->toArray(), 'tong_bao_cao'),
        'Số Báo Cáo'
    );

    $createChart(
        22,
        'Biểu Đồ Báo Cáo Theo Khoa',
        array_column($data['baoCaoByKhoa']->toArray(), 'tenKhoa'),
        array_column($data['baoCaoByKhoa']->toArray(), 'tong_bao_cao'),
        'Số Báo Cáo'
    );

    $createChart(
        43,
        'Biểu Đồ Tham Gia SHHT Theo Bộ Môn',
        array_column($data['thamGiaByBoMon']->toArray(), 'tenBoMon'),
        array_column($data['thamGiaByBoMon']->toArray(), 'tham_gia_count'),
        'Số Lần Tham Gia'
    );

    $createChart(
        64,
        'Biểu Đồ Tham Gia SHHT Theo Khoa',
        array_column($data['thamGiaByKhoa']->toArray(), 'tenKhoa'),
        array_column($data['thamGiaByKhoa']->toArray(), 'tham_gia_count'),
        'Số Lần Tham Gia'
    );

    // Ghi file và trả về
    $writer = new Xlsx($spreadsheet);
    $writer->setIncludeCharts(true);

    $fileName = 'thong_ke_' . now()->format('Ymd_His') . '.xlsx';
    $filePath = storage_path("app/public/$fileName");
    $writer->save($filePath);

    return response()->download($filePath)->deleteFileAfterSend(true);
}



  

}
