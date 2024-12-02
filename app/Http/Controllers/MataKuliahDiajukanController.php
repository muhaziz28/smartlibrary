<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\MataKuliahDiajukan;
use App\Models\MataKuliahDiambil;
use App\Models\SesiMataKuliah;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MataKuliahDiajukanController extends Controller
{
    public function data(Request $request)
    {
        $mahasiswa = Mahasiswa::where('nim', auth()->user()->username)->first();

        $mataKuliahDiajukan = MataKuliahDiajukan::with('sesi_mata_kuliah.periode_mata_kuliah.periode', 'sesi_mata_kuliah.periode_mata_kuliah.mata_kuliah', 'sesi_mata_kuliah.dosen', 'mahasiswa')
            ->whereHas('sesi_mata_kuliah.periode_mata_kuliah.periode', function ($query) {
                $query->where('aktif', 1);
            })
            ->where('nim', auth()->user()->username)
            ->get();

        return DataTables::of($mataKuliahDiajukan)
            ->addIndexColumn()
            ->make(true);
    }

    public function pengajuanData(Request $request, $id)
    {
        $mataKuliahDiajukan = MataKuliahDiajukan::with('sesi_mata_kuliah.periode_mata_kuliah.periode', 'sesi_mata_kuliah.periode_mata_kuliah.mata_kuliah', 'sesi_mata_kuliah.dosen', 'mahasiswa.prodi', 'mahasiswa.fakultas')
            ->whereHas('sesi_mata_kuliah.periode_mata_kuliah.periode', function ($query) {
                $query->where('aktif', 1);
            })
            ->whereHas('sesi_mata_kuliah', function ($query) {
                $query->where('kode_dosen', auth()->user()->username);
            })
            ->where('sesi_mata_kuliah_id', $id)
            // ambil yang status persetujuannya tidak sama dengan disetujui
            ->where('disetujui', '!=', 'disetujui')
            ->get();


        return DataTables::of($mataKuliahDiajukan)
            ->addIndexColumn()
            ->addColumn('action', function ($mataKuliahDiajukan) {
                if ($mataKuliahDiajukan->disetujui == 'pending') {
                    $btn = '<button class="btn btn-sm btn-success" id="setujui" data-id="' . $mataKuliahDiajukan->id . '">Setujui</button>';
                    $btn .= '<button class="btn btn-sm btn-danger" id="tolak" data-id="' . $mataKuliahDiajukan->id . '">Tolak</button>';
                    return $btn;
                } else if ($mataKuliahDiajukan->disetujui == 'ditolak') {
                    return 'Ditolak';
                } else if ($mataKuliahDiajukan->disetujui == 'disetujui') {
                    return 'Sudah disetujui';
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $mahasiswa = Mahasiswa::where('nim', auth()->user()->username)->first();

        // $checkMataKuliahDiajukan = MataKuliahDiajukan::where('nim', auth()->user()->username)
        //     ->where('sesi_mata_kuliah_id', $request->sesi_mata_kuliah_id)
        //     ->first();

        // if ($checkMataKuliahDiajukan) { // jika sudah pernah diajukan
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Mata kuliah sudah pernah diajukan',
        //     ], 422);
        // }

        $mataKuliahDiajukan = MataKuliahDiajukan::create([
            'nim' => $mahasiswa->nim,
            'sesi_mata_kuliah_id' => $request->sesi_mata_kuliah_id,
            'disetujui' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Mata kuliah berhasil diajukan',
            'data' => $mataKuliahDiajukan,
        ]);
    }

    public function show($id)
    {
        $sesiMataKuliah = SesiMataKuliah::with('periode_mata_kuliah.mata_kuliah', 'mata_kuliah_diambil', 'dosen')
            ->whereHas('periode_mata_kuliah.periode', function ($query) {
                $query->where('aktif', 1);
            })
            ->whereDoesntHave('mata_kuliah_diambil', function ($query) {
                $query->where('nim', auth()->user()->username);
            })
            ->where('id', $id)
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Mata kuliah berhasil diajukan',
            'data' => $sesiMataKuliah,
        ]);
    }

    public function setujui(Request $request)
    {
        $mataKuliahDiajukan = MataKuliahDiajukan::with('sesi_mata_kuliah')->find($request->id);
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
        ]);
    }

    public function tolak(Request $request)
    {
        $mataKuliahDiajukan = MataKuliahDiajukan::find($request->id);
        $mataKuliahDiajukan->disetujui = 'ditolak';
        $mataKuliahDiajukan->save();

        return response()->json([
            'success' => true,
            'message' => 'Mata kuliah berhasil ditolak',
            'data' => $mataKuliahDiajukan,
        ]);
    }
}
