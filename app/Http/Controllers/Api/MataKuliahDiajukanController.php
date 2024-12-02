<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\MataKuliahDiajukan;
use App\Models\MataKuliahDiambil;
use App\Models\SesiMataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MataKuliahDiajukanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function data()
    {
        try {
            if (Auth::user()->role_id != 2) {
                return response()->json([
                    'success' => false,
                    "message" => "anda tidak memiliki akses",
                    'data' => null,
                ], 400);
            }

            $mahasiswa = Mahasiswa::where('nim', auth()->user()->username)->first();

            $diajukan = MataKuliahDiajukan::with('sesi_mata_kuliah.periode_mata_kuliah.periode', 'sesi_mata_kuliah.periode_mata_kuliah.mata_kuliah', 'sesi_mata_kuliah.dosen', 'mahasiswa')
                ->whereHas('sesi_mata_kuliah.periode_mata_kuliah.periode', function ($query) {
                    $query->where('aktif', 1);
                })->where('nim', auth()->user()->username)->get();

            $matakuliahDiajukan = $diajukan;

            return response()->json([
                'success'   => true,
                'message'   => "Berhasil mengambil data mata kuliah diajukan",
                'data'      => $matakuliahDiajukan
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                "message" => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function ajukan($sesiMataKuliahId)
    {
        try {
            $mahasiswa = Mahasiswa::where('nim', auth()->user()->username)->first();

            $mataKuliahDiajukan = MataKuliahDiajukan::create([
                'nim' => $mahasiswa->nim,
                'sesi_mata_kuliah_id' => (int) $sesiMataKuliahId,
                'disetujui' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Mata kuliah berhasil diajukan',
                'data' => $mataKuliahDiajukan,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                "message" => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function listPengajuan($sesiMataKuliahId)
    {
        if (Auth::user()->role_id == 2) {
            return response()->json([
                'success' => false,
                "message" => "anda tidak memiliki akses",
                'data' => null,
            ], 400);
        }

        try {
            $pengajuan = MataKuliahDiajukan::with('sesi_mata_kuliah.periode_mata_kuliah.periode', 'sesi_mata_kuliah.periode_mata_kuliah.mata_kuliah', 'sesi_mata_kuliah.dosen', 'mahasiswa.prodi', 'mahasiswa.fakultas')
                ->whereHas('sesi_mata_kuliah.periode_mata_kuliah.periode', function ($query) {
                    $query->where('aktif', 1);
                })
                ->whereHas('sesi_mata_kuliah', function ($query) {
                    $query->where('kode_dosen', auth()->user()->username);
                })
                ->where('sesi_mata_kuliah_id', $sesiMataKuliahId)
                ->where('disetujui', '!=', 'disetujui')->get();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil data pengajuan',
                'data' => $pengajuan,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                "message" => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function setuju($id)
    {
        try {
            $mataKuliahDiajukan = MataKuliahDiajukan::with('sesi_mata_kuliah')->find($id);
            if (!$mataKuliahDiajukan) {
                return response()->json([
                    'success' => false,
                    'message' => 'data tidak ditemukan',
                    'data' => null,
                ], 404);
            }

            if ($mataKuliahDiajukan->disetujui == 'disetujui') {
                return response()->json([
                    'success' => false,
                    'message' => 'data sudah disetujui sebelumnya',
                    'data' => null,
                ], 400);
            }

            $mataKuliahDiajukan->disetujui = 'disetujui';
            $mataKuliahDiajukan->save();

            $mataKuliahDiambil = new MataKuliahDiambil();
            $mataKuliahDiambil->nim = $mataKuliahDiajukan->nim;
            $mataKuliahDiambil->sesi_mata_kuliah_id = $mataKuliahDiajukan->sesi_mata_kuliah_id;
            $mataKuliahDiambil->save();

            return response()->json([
                'success' => true,
                'message' => 'Mata kuliah berhasil disetujui',
                'data' => $mataKuliahDiajukan,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                "message" => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function tolak($id)
    {
        try {
            $mataKuliahDiajukan = MataKuliahDiajukan::find($id);
            if (!$mataKuliahDiajukan) {
                return response()->json([
                    'success' => false,
                    'message' => 'data tidak ditemukan',
                    'data' => null,
                ], 404);
            }
            $mataKuliahDiajukan->disetujui = 'ditolak';
            $mataKuliahDiajukan->save();

            return response()->json([
                'success' => true,
                'message' => 'Mata kuliah berhasil ditolak',
                'data' => $mataKuliahDiajukan,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }
}
