<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use App\Models\PeriodeMataKuliah;
use Illuminate\Http\Request;

class PeriodeMataKuliahController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index(Request $request)
    {
        $perPage = 10;
        $currentPage = request('page', 1);

        $periodeAktif = Periode::where('aktif', 1)->first();

        if (!$periodeAktif) {
            return redirect()->route('periode.index')->with('message', 'Tidak ada periode aktif, tambah/update periode terlebih dahulu');
        }

        $data = PeriodeMataKuliah::with('periode', 'mata_kuliah.prodi', 'sesi_mata_kuliah')
            ->when($request->keyword, function ($query) use ($request) {
                $query->where(function ($subquery) use ($request) {
                    $subquery->whereHas('mata_kuliah', function ($q) use ($request) {
                        $q->where('nama_mk', 'like', "%{$request->keyword}%")->orWhere('kode_mk', 'like', "%{$request->keyword}%");
                    })->orWhereHas('sesi_mata_kuliah', function ($q) use ($request) {
                        $q->where('kode_sesi', 'like', "%{$request->keyword}%");
                    });
                });
            })
            ->when($request->periode, function ($query) use ($request) {
                $query->where('periode_id', $request->periode);
            })
            ->when($request->periode == null, function ($query) {
                $periodeAktif = Periode::where('aktif', 1)->first();
                $query->where('periode_id', $periodeAktif->id);
            })
            ->paginate($perPage);

        // dd($request->periode);

        $periode = Periode::all();

        return view('periode_mata_kuliah.index', compact('data', 'request', 'periode'));
    }

    public function store(Request $request)
    {
        $periodeAktif = Periode::where('aktif', 1)->first();

        // select mata_kuliah_id memiliki data mata_kuliah_id[], ubah menjadi array
        $mataKuliahId = $request->mata_kuliah_id;

        foreach ($mataKuliahId as $key => $value) {
            PeriodeMataKuliah::create([
                'periode_id' => $periodeAktif->id,
                'mata_kuliah_id' => $value,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Periode Mata Kuliah created successfully!',
        ]);
    }

    public function destroy($id)
    {
        try {
            $periodeMataKuliah = PeriodeMataKuliah::findOrFail($id);

            $periodeMataKuliah->delete();

            return response()->json([
                'success' => true,
                'message' => 'Periode Mata Kuliah deleted successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
