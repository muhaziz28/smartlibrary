<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Pertemuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class PertemuanController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function data(Request $request, $id)
    {
        $pertemuan = Pertemuan::with('sesiMataKuliah.dosen', 'sesiMataKuliah.periode_mata_kuliah.mata_kuliah', 'sesiMataKuliah.jadwalTeori', 'sesiMataKuliah.jadwalPraktikum', 'absensi')
            ->where('id', $id)->first();

        try {
            return response()->json([
                'success' => true,
                'message' => 'Data pertemuan berhasil diambil',
                'data'    => $pertemuan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data'    => null
            ]);
        }
    }

    public function pertemuanHariIni()
    {
        $today = now()->toDateString();

        $pertemuan = Pertemuan::with('sesiMataKuliah.dosen', 'sesiMataKuliah.periode_mata_kuliah.mata_kuliah', 'sesiMataKuliah.jadwalTeori', 'sesiMataKuliah.jadwalPraktikum', 'absensi')
            // ->whereDate('tanggal', '=', $today)
            ->whereHas('sesiMataKuliah', function (Builder $q) {
                $q->whereHas('mata_kuliah_diambil', function (Builder $query) {
                    $user = Auth::user();
                    $mahasiswa = Mahasiswa::where('nim', $user->username)->first();

                    if ($mahasiswa) {
                        $query->where('nim', $mahasiswa->nim);
                    }
                });
            })
            ->get();

        return response()->json($pertemuan);
    }
}
