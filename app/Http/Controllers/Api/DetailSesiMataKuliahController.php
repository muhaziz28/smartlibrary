<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MataKuliahDiajukan;
use App\Models\MataKuliahDiambil;
use App\Models\Pertemuan;
use App\Models\SesiMataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

class DetailSesiMataKuliahController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function peserta($id)
    {
        try {
            $peserta = MataKuliahDiambil::with('mahasiswa', 'sesiMataKuliah.periode_mata_kuliah.periode')
                ->whereHas('sesiMataKuliah.periode_mata_kuliah.periode', function ($query) {
                    $query->where('aktif', 1);
                })
                ->where('sesi_mata_kuliah_id', $id)->count();

            return response()->json([
                'success' => true,
                'message' => 'Success fetch data peserta',
                'data' => $peserta
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function konfirmasiPeserta($id)
    {
        try {
            $konfirmasiPeserta = MataKuliahDiajukan::with('sesi_mata_kuliah.periode_mata_kuliah.periode', 'sesi_mata_kuliah.periode_mata_kuliah.mata_kuliah', 'sesi_mata_kuliah.dosen', 'mahasiswa.prodi', 'mahasiswa.fakultas')
                ->whereHas('sesi_mata_kuliah.periode_mata_kuliah.periode', function ($query) {
                    $query->where('aktif', 1);
                })
                ->whereHas('sesi_mata_kuliah', function ($query) {
                    $query->where('kode_dosen', auth()->user()->username);
                })
                ->where('sesi_mata_kuliah_id', $id)
                // ambil yang status persetujuannya tidak sama dengan disetujui
                ->where('disetujui', '!=', 'disetujui')->where('disetujui', '!=', 'ditolak')
                ->count();

            return response()->json([
                'success' => true,
                'message' => 'Success fetch data konfirmasi peserta',
                'data' => $konfirmasiPeserta
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function pertemuan($id)
    {
        try {
            $pertemuan = Pertemuan::where('sesi_mata_kuliah_id', $id)
                ->orderBy('tanggal', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Success fetch data pertemuan',
                'data' => $pertemuan
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function pertemuanHariIni($id)
    {
        try {
            $today = Date::now()->toDateString();
            $pertemuan = Pertemuan::where('sesi_mata_kuliah_id', $id)
                ->whereDate('tanggal', $today)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Success fetch data pertemuan',
                'data' => $pertemuan
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function index($id)
    {
        $data = Pertemuan::with('sesiMataKuliah')->where('sesi_mata_kuliah_id', $id)->get();

        return response()->json([
            'success' => true,
            'message' => 'Success fetch data pertemuan',
            'data' => $data
        ], 200);
    }

    public function indexDosen(Request $request)
    {
        $sesi = SesiMataKuliah::with('periode_mata_kuliah.mata_kuliah', 'dosen', 'mata_kuliah_diajukan')
            ->where('kode_dosen', Auth::user()->username)
            ->when($request->periode, function ($query) use ($request) {
                $query->whereHas('periode_mata_kuliah', function ($query) use ($request) {
                    $query->where('periode_id', $request->periode);
                });
            })->get();

        return response()->json([
            'success' => true,
            'message' => 'Success fetch data sesi dosen',
            'data' => $sesi
        ], 200);
    }
}
