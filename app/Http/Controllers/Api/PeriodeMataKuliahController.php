<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Periode;
use App\Models\PeriodeMataKuliah;
use App\Models\SesiMataKuliah;
use Illuminate\Http\Request;

class PeriodeMataKuliahController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function data(Request $request)
    {
        try {
            $periodeMataKuliah = PeriodeMataKuliah::with('periode', 'mata_kuliah', 'sesi_mata_kuliah')
                ->when($request->keyword, function ($query) use ($request) {
                    $query->where(function ($subquery) use ($request) {
                        $subquery->whereHas('mata_kuliah', function ($q) use ($request) {
                            $q->where('nama_mk', 'like', "%{$request->keyword}%")->orWhere('kode_mk', 'like', "%{$request->keyword}%");
                        })->orWhereHas('sesi_mata_kuliah', function ($q) use ($request) {
                            $q->where('kode_sesi', 'like', "%{$request->keyword}%");
                        });
                    });
                })
                ->when($request->periode_id, function ($q) use ($request) {
                    $q->where('periode_id', $request->periode_id);
                }, function ($q) {
                    $q->whereHas('periode', function ($q) {
                        $q->where('aktif', 1);
                    });
                })
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Success fetch data periode mata kuliah',
                'data' => $periodeMataKuliah
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $periodeAktif = Periode::where('aktif', 1)->first();

            $mataKuliahId = $request->mata_kuliah_id;

            $count = 0;

            foreach ($mataKuliahId as $key => $value) {
                $count++;
                PeriodeMataKuliah::create([
                    'periode_id' => $periodeAktif->id,
                    'mata_kuliah_id' => $value,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Periode Mata Kuliah created successfully!',
                'data' => $count
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $periodeMataKuliah = PeriodeMataKuliah::findOrFail($id);

            $sesiMataKuliah = SesiMataKuliah::where('periode_mata_kuliah_id', $periodeMataKuliah->id)->get();

            foreach ($sesiMataKuliah as $key => $value) {
                $value->delete();
            }

            $periodeMataKuliah->delete();

            return response()->json([
                'success' => true,
                'message' => 'Periode Mata Kuliah deleted successfully!',
                'data' => $periodeMataKuliah
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function getMatakuliahPeriode(Request $request)
    {
        try {
            $periodeAktif = Periode::where('aktif', 1)->first();

            $periodeMataKuliah = PeriodeMataKuliah::with('periode', 'mata_kuliah', 'mata_kuliah.prodi', 'mata_kuliah.prodi.fakultas', 'sesi_mata_kuliah', 'sesi_mata_kuliah.periode_mata_kuliah', 'sesi_mata_kuliah.dosen')
                ->where('periode_id', $periodeAktif->id)
                ->get();

            $mataKuliah = [];
            // ambil mata kuliah yang belum ada di periode mata kuliah
            foreach ($periodeMataKuliah as $key => $value) {
                array_push($mataKuliah, $value->mata_kuliah_id);
            }

            $mataKuliah = \App\Models\MataKuliah::with('prodi', 'prodi.fakultas')
                ->whereNotIn('id', $mataKuliah)
                ->get();


            return response()->json([
                'success' => true,
                'message' => 'Success fetch data periode mata kuliah',
                'data' => $mataKuliah
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
