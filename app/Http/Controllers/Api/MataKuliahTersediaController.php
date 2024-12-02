<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\MataKuliahDiajukan;
use App\Models\SesiMataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MataKuliahTersediaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function data()
    {
        try {
            if (Auth::user()->role_id !=  2) {
                return response()->json([
                    'success' => false,
                    "message" => "anda tidak memiliki akses",
                    'data' => null,
                ], 400);
            }

            $mahasiswa = Mahasiswa::where('nim', auth()->user()->username)->first();

            $sesiMataKuliah = SesiMataKuliah::with('periode_mata_kuliah.mata_kuliah', 'mata_kuliah_diambil', 'dosen')->whereHas('periode_mata_kuliah.periode', function ($query) {
                $query->where('aktif', 1);
            })->whereDoesntHave('mata_kuliah_diambil', function ($query) {
                $query->where('nim', auth()->user()->username);
            })->whereHas('periode_mata_kuliah.mata_kuliah', function ($query) use ($mahasiswa) {
                $query->where('prodi_id', $mahasiswa->prodi_id);
            })->whereDoesntHave('mata_kuliah_diajukan', function ($query) {
                $query->where('nim', auth()->user()->username);
            })->get();

            $mataKuliah = SesiMataKuliah::with('periode_mata_kuliah.mata_kuliah', 'mata_kuliah_diajukan', 'dosen')->whereHas('periode_mata_kuliah.periode', function ($query) {
                $query->where('aktif', 1);
            })->whereHas('mata_kuliah_diajukan', function ($query) {
                $query->where('nim', auth()->user()->username)->where('disetujui', 'ditolak');
            })->whereDoesntHave('mata_kuliah_diajukan', function ($query) {
                $query->where('nim', auth()->user()->username)->where('disetujui', 'disetujui');
            })->whereDoesntHave('mata_kuliah_diajukan', function ($query) {
                $query->where('nim', auth()->user()->username)->where('disetujui', 'pending');
            })->get();

            $sesiMataKuliah = $sesiMataKuliah->merge($mataKuliah);
            $matakuliahTersedia = $sesiMataKuliah;

            return response()->json([
                'success'   => true,
                'message'   => "Berhasil mengambil data mata kuliah tersedia",
                'data'      => $matakuliahTersedia
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}
