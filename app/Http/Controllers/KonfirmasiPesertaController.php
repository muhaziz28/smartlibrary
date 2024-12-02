<?php

namespace App\Http\Controllers;

use App\Models\MataKuliahDiajukan;
use Illuminate\Http\Request;

class KonfirmasiPesertaController extends Controller
{
    public function index($id)
    {
        $perPage = 10;
        $currentPage = request('page', 1);

        $pengajuan = MataKuliahDiajukan::with('sesi_mata_kuliah.periode_mata_kuliah.periode', 'sesi_mata_kuliah.periode_mata_kuliah.mata_kuliah', 'sesi_mata_kuliah.dosen', 'mahasiswa.prodi', 'mahasiswa.fakultas')
            ->whereHas('sesi_mata_kuliah.periode_mata_kuliah.periode', function ($query) {
                $query->where('aktif', 1);
            })
            ->whereHas('sesi_mata_kuliah', function ($query) {
                $query->where('kode_dosen', auth()->user()->username);
            })
            ->where('sesi_mata_kuliah_id', $id)
            // ambil yang status persetujuannya tidak sama dengan disetujui
            ->where('disetujui', '!=', 'disetujui');

        $totalItems = $pengajuan->count();

        $totalPages = ceil($totalItems / $perPage);
        $konfirmasi = $pengajuan->skip(($currentPage - 1) * $perPage)->take($perPage)->get();
        // return response()->json($konfirmasi);
        return view('detail_sesi.konfirmasi_peserta', compact('id', 'konfirmasi', 'totalItems', 'totalPages', 'currentPage', 'perPage'));
    }
}
