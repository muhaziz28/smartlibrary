<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use App\Models\Periode;
use App\Models\PeriodeMataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class MataKuliahController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index(Request $request)
    {
        $perPage = 10;
        $currentPage = request('page', 1);

        $data = MataKuliah::with('prodi')
            ->when($request->keyword, function ($query) use ($request) {
                $query->where(function ($subquery) use ($request) {
                    $subquery->where('kode_mk', 'like', "%{$request->keyword}%")->orWhere('nama_mk', 'like', "%{$request->keyword}%");
                });
            })
            ->paginate($perPage);

        return view('mata_kuliah.index', compact('data', 'request'));
    }

    public function getMataKuliahList(Request $request)
    {
        $search = $request->search;
        $periodeAktif = Periode::where('aktif', 1)->first();

        $mataKuliah = MataKuliah::whereDoesntHave('periode_mata_kuliah', function ($query) use ($periodeAktif) {
            $query->where('periode_id', $periodeAktif->id);
        })->when($search, function ($query, $search) {
            $query->where('nama_mk', 'like', '%' . $search . '%')->orWhere('kode_mk', 'like', '%' . $search . '%');
        })->get();

        return response()->json([
            'success' => true,
            'message' => 'Mata Kuliah retrieved successfully!',
            'data' => $mataKuliah
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_mk' => 'required',
            'nama_mk' => 'required',
            'sks' => 'required',
            'prodi_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all()
            ]);
        }

        try {
            $check = MataKuliah::where('kode_mk', $request->kode_mk)->first();
            if ($check) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode Mata Kuliah sudah ada!'
                ]);
            }

            $mataKuliah = new MataKuliah();
            $mataKuliah->kode_mk = $request->kode_mk;
            $mataKuliah->nama_mk = $request->nama_mk;
            $mataKuliah->sks = $request->sks;
            $mataKuliah->prodi_id = $request->prodi_id;
            $mataKuliah->save();

            return response()->json([
                'success' => true,
                'message' => 'Mata Kuliah created successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function show($id)
    {
        $mataKuliah = MataKuliah::with('prodi', 'prodi.fakultas')->find($id);

        return response()->json([
            'success' => true,
            'message' => 'Mata Kuliah retrieved successfully!',
            'data' => $mataKuliah
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_mk' => 'required',
            'nama_mk' => 'required',
            'sks' => 'required',
            'prodi_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all()
            ]);
        }

        try {
            $mataKuliah = MataKuliah::find($request->id);
            $mataKuliah->kode_mk = $request->kode_mk;
            $mataKuliah->nama_mk = $request->nama_mk;
            $mataKuliah->sks = $request->sks;
            $mataKuliah->prodi_id = $request->prodi_id;
            $mataKuliah->save();

            return response()->json([
                'success' => true,
                'message' => 'Mata Kuliah updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        $mataKuliah = MataKuliah::find($id);
        $mataKuliah->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mata kuliah deleted successfully!'
        ]);
    }
}
