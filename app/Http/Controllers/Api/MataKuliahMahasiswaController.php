<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MataKuliahDiambil;
use App\Models\PeriodeMataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MataKuliahMahasiswaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function data(Request $request)
    {
        try {
            $periodeMataKuliah = PeriodeMataKuliah::with('periode')->whereHas('periode', function ($periode) {
                $periode->where('aktif', 1);
            })->first();

            $periodeMataKuliahId = $periodeMataKuliah->periode->id;

            if ($request->periode == null) {
                $mataKuliah = MataKuliahDiambil::with('sesiMataKuliah.periode_mata_kuliah.mata_kuliah', 'sesiMataKuliah.dosen')
                    ->whereHas('sesiMataKuliah.periode_mata_kuliah', function ($periodeMataKuliah) use ($periodeMataKuliahId) {
                        $periodeMataKuliah->where('periode_id', $periodeMataKuliahId);
                    })
                    ->where('nim', Auth::user()->username)->get();
            } else {
                $mataKuliah = MataKuliahDiambil::with('sesiMataKuliah.periode_mata_kuliah.mata_kuliah', 'sesiMataKuliah.dosen')
                    // ->whereHas('sesiMataKuliah.periode_mata_kuliah', function ($periodeMataKuliah) use ($periodeMataKuliahId) {
                    //     $periodeMataKuliah->where('periode_id', $periodeMataKuliahId);
                    // })
                    ->where('nim', Auth::user()->username)
                    ->whereHas('sesiMataKuliah.periode_mata_kuliah', function ($periodeMataKuliah) use ($request) {
                        $periodeMataKuliah->where('periode_id', $request->periode);
                    })->get();
            }

            return response()->json([
                'success'   => true,
                'message'   => "Berhasil mengambil data mata kuliah mahasiswa",
                'data'      => $mataKuliah
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                "message" => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }
}
