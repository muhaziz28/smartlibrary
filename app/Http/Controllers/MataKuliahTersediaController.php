<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\SesiMataKuliah;
use Illuminate\Http\Request;

class MataKuliahTersediaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function data(Request $request)
    {
        $mahasiswa = Mahasiswa::where('nim', auth()->user()->username)->first();

        // dd($mahasiswa->prodi_id);

        $sesiMataKuliah = SesiMataKuliah::with('periode_mata_kuliah.mata_kuliah', 'mata_kuliah_diambil', 'dosen')
            ->whereHas('periode_mata_kuliah.periode', function ($query) {
                $query->where('aktif', 1);
            })
            ->whereDoesntHave('mata_kuliah_diambil', function ($query) {
                $query->where('nim', auth()->user()->username);
            })
            ->whereHas('periode_mata_kuliah.mata_kuliah', function ($query) use ($mahasiswa) {
                $query->where('prodi_id', $mahasiswa->prodi_id);
            })
            ->whereDoesntHave('mata_kuliah_diajukan', function ($query) {
                $query->where('nim', auth()->user()->username);
            })
            ->get();

        $mataKuliahDiajukan = SesiMataKuliah::with('periode_mata_kuliah.mata_kuliah', 'mata_kuliah_diajukan', 'dosen')
            ->whereHas('periode_mata_kuliah.periode', function ($query) {
                $query->where('aktif', 1);
            })
            ->whereHas('mata_kuliah_diajukan', function ($query) {
                $query->where('nim', auth()->user()->username)->where('disetujui', 'ditolak');
            })
            ->whereDoesntHave('mata_kuliah_diajukan', function ($query) {
                $query->where('nim', auth()->user()->username)->where('disetujui', 'disetujui');
            })
            ->whereDoesntHave('mata_kuliah_diajukan', function ($query) {
                $query->where('nim', auth()->user()->username)->where('disetujui', 'pending');
            })

            ->get();

        // merge collection
        $perPage = 10;
        $currentPage = request('page', 1);

        $sesiMataKuliah = $sesiMataKuliah->merge($mataKuliahDiajukan);
        $totalItems = $sesiMataKuliah->count();
        $totalPages = ceil($totalItems / $perPage);
        $matakuliahTersedia = $sesiMataKuliah->skip(($currentPage - 1) * $perPage)->take($perPage);
        // return response()->json($periode);
        return response()->json($matakuliahTersedia);
        // return view('home', compact('matakuliahTersedia', 'sesiMataKuliah', 'totalItems', 'totalPages', 'currentPage', 'perPage'));
    }
}
