<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BaoCao;
use App\Models\LichBaoCao;
use App\Models\GiangVien;
use App\Models\DongTacGia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
class BaoCaoController extends Controller
{

    public function index(Request $request)
    {
        $giangVien = Auth::guard('giang_viens')->user();
        $query = BaoCao::with('lichBaoCao')
        ->where('giangVien_id', $giangVien->maGiangVien);
    
        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where(function ($q) use ($keyword) {
                $q->where('tenBaoCao', 'like', '%' . $keyword . '%')
                  ->orWhere('dinhDang', 'like', '%' . $keyword . '%')
                  ->orWhere('ngayNop', 'like', '%' . $keyword . '%');
            });
        }
    
        $baoCaos = $query->orderByDesc('ngayNop')->paginate(6);
    
        return view('baocao.index', compact('baoCaos'));
    }
    


    public function create()
    {
        // $lichBaoCaos = LichBaoCao::all();
        $user = Auth::guard('giang_viens')->user();
        $today = now();
        $lichBaoCaos = $user->lichBaoCaos()
        ->where('hanNgayNop', '>=', $today)
        ->get();
        $tatCaGiangVien = GiangVien::all();
        return view('baocao.create',compact('lichBaoCaos','tatCaGiangVien'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tenBaoCao' => 'required|string|max:255',
            'ngayNop' => 'date',
            'dinhDang' => 'required|string',
            'tomTat' => 'required|string',
            'files.*' => 'required|file|mimes:pdf,docx,ppt,pptx|max:20480', // 20MB mỗi file
            'lich_bao_cao_id' => 'required|exists:lich_bao_caos,maLich',
            'dong_tac_gia_ids.*' => 'nullable|exists:giang_viens,maGiangVien',
        ]);

        $lich = LichBaoCao::find($request->lich_bao_cao_id);

        $hanNop = Carbon::createFromFormat('Y-m-d H:i:s', $lich->hanNgayNop . ' ' . $lich->hanGioNop);
        $ngayNop = Carbon::now();
    
        if ($ngayNop->gt($hanNop)) {
            return redirect()->back()
                ->withErrors(['ngayNop' => 'Đã quá hạn nộp báo cáo cho chủ đề này.'])
                ->withInput();
        }

        $user = Auth::guard('giang_viens')->user();
        $giangVienId = $user->maGiangVien;

        $giangVien = Auth::guard('giang_viens')->user();

    foreach ($request->file('files') as $file) {
        $fileName = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('public/baocaodanop', $fileName);
        $dinhDang = $file->getClientOriginalExtension();
        $tenBaoCao = $request->tenBaoCao.' ('.$file->getClientOriginalName().')';
        $baoCao = BaoCao::create([
            'tenBaoCao' => $tenBaoCao,
            'ngayNop' => $request->ngayNop,
            'dinhDang' => $dinhDang,
            'tomTat' => $request->tomTat,
            'duongDanFile' => 'storage/baocaodanop/' . $fileName,
            'giangVien_id' => $giangVien->maGiangVien,
            'lich_bao_cao_id' => $request->lich_bao_cao_id,
        ]);
        // ✅ Nếu có đồng tác giả thì lưu
        if ($request->has('dong_tac_gia_ids')) {
            foreach ($request->dong_tac_gia_ids as $dongTacGiaId) {
                DongTacGia::create([
                    'maBaoCao' => $baoCao->maBaoCao,
                    'giangVienNop_id' => $giangVien->maGiangVien,
                    'giangVienDongTacGia_id' => $dongTacGiaId,
                ]);
            }
        }   
    }

    return redirect()->route('baocao.index')->with('success', 'Đã nộp báo cáo thành công!');
    }

    public function destroy($maBaoCao)
    {
        $baoCao = BaoCao::findOrFail($maBaoCao); // dùng maBaoCao
        // Xóa file nếu có
        if ($baoCao->duongDanFile && file_exists(public_path($baoCao->duongDanFile))) {
            unlink(public_path($baoCao->duongDanFile));
        }
        $baoCao->delete();
        return redirect()->back()->with('success', 'Đã xóa báo cáo thành công!');
    }

}

