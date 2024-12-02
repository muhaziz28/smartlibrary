<?php

namespace App\Http\Controllers;

use App\Models\MataKuliahDiambil;
use App\Models\Periode;
use App\Models\PeriodeMataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class MataKuliahMahasiswaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index(Request $request)
    {
        $periodeMataKuliah = PeriodeMataKuliah::with('periode')->whereHas('periode', function ($periode) {
            $periode->where('aktif', 1);
        })->first();

        $perPage = 10;
        $currentPage = request('page', 1);

        $periodeMataKuliahId = $periodeMataKuliah->periode->id;

        if ($request->periode == null) {
            $mataKuliah = MataKuliahDiambil::with('sesiMataKuliah.periode_mata_kuliah.mata_kuliah', 'sesiMataKuliah.dosen')
                ->whereHas('sesiMataKuliah.periode_mata_kuliah', function ($periodeMataKuliah) use ($periodeMataKuliahId) {
                    $periodeMataKuliah->where('periode_id', $periodeMataKuliahId);
                })
                ->where('nim', Auth::user()->username);
        } else {
            $mataKuliah = MataKuliahDiambil::with('sesiMataKuliah.periode_mata_kuliah.mata_kuliah', 'sesiMataKuliah.dosen')
                // ->whereHas('sesiMataKuliah.periode_mata_kuliah', function ($periodeMataKuliah) use ($periodeMataKuliahId) {
                //     $periodeMataKuliah->where('periode_id', $periodeMataKuliahId);
                // })
                ->where('nim', Auth::user()->username)
                ->whereHas('sesiMataKuliah.periode_mata_kuliah', function ($periodeMataKuliah) use ($request) {
                    $periodeMataKuliah->where('periode_id', $request->periode);
                });
        }

        $totalItems = $mataKuliah->count();
        $totalPages = ceil($totalItems / $perPage);
        $mataKuliahDiambil = $mataKuliah->skip(($currentPage - 1) * $perPage)->take($perPage)->get();

        $periode = Periode::all();

        return view('mahasiswa.mata-kuliah', compact('periodeMataKuliah', 'mataKuliahDiambil', 'totalItems', 'totalPages', 'currentPage', 'perPage', 'request', 'periode'));
    }

    public function data(Request $request, $id)
    {


        // return DataTables::of($mataKuliahDiambil)
        //     ->addIndexColumn()
        //     ->addColumn('action', function ($mataKuliahDiambil) {
        //         $btn = '<a href="' . route('detail_sesi_mata_kuliah.index', $mataKuliahDiambil->sesiMataKuliah->id) . '" class="btn btn-sm btn-info">Detail</a>';
        //         return $btn;
        //     })
        //     ->rawColumns(['action'])
        //     ->make(true);
    }
}
