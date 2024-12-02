<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\MataKuliah;
use App\Models\PeriodeMataKuliah;
use App\Models\Pertemuan;
use App\Models\SesiMataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SesiMataKuliahController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getDosenByProdiSesi(Request $request, $id)
    {
        try {
            $periodeMataKuliah = PeriodeMataKuliah::findOrFail($id);
            $mataKuliahId = $periodeMataKuliah->mata_kuliah_id;

            $mataKuliah = MataKuliah::find($mataKuliahId);
            $prodiMataKuliah = $mataKuliah->prodi_id;

            $dosen = Dosen::with('fakultas')->whereHas('fakultas.prodi', function ($prodi) use ($prodiMataKuliah) {
                $prodi->where('id', $prodiMataKuliah);
            })->when($request->search, function ($dosen, $search) {
                return $dosen->where('nama_dosen', 'like', '%' . $search . '%')->orWhere('kode_dosen', 'like', '%' . $search . '%');
            })->get();

            return response()->json([
                'success' => 'success',
                'message' => 'Data Dosen berhasil diambil',
                'data' => $dosen,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => 'false',
                'message' => $e->getMessage(),
                'data' => null,
            ]);
        }
    }

    public function data(Request $request, $id)
    {
        $sesiMataKuliah = SesiMataKuliah::with('periode_mata_kuliah', 'dosen', 'dosen.fakultas')
            ->when($request->dosen, function ($q) use ($request) {
                $q->whereHas('dosen', function ($q) use ($request) {
                    $q->where('nama_dosen', 'LIKE', "%{$request->dosen}%")->orWhere('kode_dosen', 'LIKE', "%{$request->dosen}%");
                });
            })
            ->when($request->kode_sesi, function ($q) use ($request) {
                $q->where('kode_sesi', 'LIKE', "%{$request->kode_sesi}%");
            })
            ->where('periode_mata_kuliah_id', $id)
            ->get();

        return response()->json([
            'succes' => true,
            'message' => 'Data Sesi Mata Kuliah berhasil diambil',
            'data' => $sesiMataKuliah,
        ], 200);
    }

    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode_sesi' => 'required',
            'kode_dosen' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'data' => null,
            ], 400);
        }
        try {
            $sesiMataKuliah = new SesiMataKuliah();
            $sesiMataKuliah->periode_mata_kuliah_id = $id;
            $sesiMataKuliah->kode_sesi = $request->kode_sesi;
            $sesiMataKuliah->kode_dosen = $request->kode_dosen;
            $sesiMataKuliah->save();

            for ($i = 1; $i <= 16; $i++) {
                $pertemuan = new Pertemuan();
                $pertemuan->sesi_mata_kuliah_id = $sesiMataKuliah->id;
                $pertemuan->pertemuan_ke = $i;
                $pertemuan->tanggal = null;
                $pertemuan->save();
            }

            $getSesimatakuliah = SesiMataKuliah::with('periode_mata_kuliah', 'dosen', 'dosen.fakultas')->find($sesiMataKuliah->id);

            return response()->json([
                'success' => true,
                'message' => 'Sesi Mata Kuliah berhasil ditambahkan',
                'data' => $getSesimatakuliah,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }


    public function update(Request $request, $sesiId)
    {
        $validator = Validator::make($request->all(), [
            'kode_sesi' => 'required',
            'kode_dosen' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'data' => null,
            ], 400);
        }
        try {
            $sesiMataKuliah = SesiMataKuliah::with('periode_mata_kuliah', 'dosen', 'dosen.fakultas')->find($sesiId);
            $sesiMataKuliah->kode_sesi = $request->kode_sesi;
            $sesiMataKuliah->kode_dosen = $request->kode_dosen;
            $sesiMataKuliah->save();

            return response()->json([
                'success' => true,
                'message' => 'Sesi Mata Kuliah berhasil diupdate',
                'data' => $sesiMataKuliah,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $sesiMataKuliah = SesiMataKuliah::find($id);
            $sesiMataKuliah->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sesi Mata Kuliah berhasil dihapus',
                'data' => $sesiMataKuliah,
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
