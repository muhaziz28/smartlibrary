<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PeriodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index(Request $request)
    {
        $perPage = 10;
        $currentPage = request('page', 1);

        $perPage = 10;
        $currentPage = request('page', 1);

        $data = Periode::when($request->aktif !== null, function ($query) use ($request) {
            $query->where('aktif', $request->aktif);
        })
            ->paginate($perPage);

        return view('periode.index', compact('data', 'request'));
    }

    public function store(Request $request)
    {
        $periode = new Periode();
        $periode->mulai = $request->mulai;
        $periode->selesai = $request->selesai;
        if ($request->aktif == 'on') {
            $periode->aktif = 1;
        } else {
            $periode->aktif = 0;
        }
        $periode->keterangan = $request->keterangan;
        $periode->tahun_ajaran = $request->tahun_ajaran;
        $periode->save();

        return response()->json([
            'success' => true,
            'message' => 'Periode created successfully!'
        ]);
    }

    public function show($id)
    {
        $periode = Periode::find($id);

        return response()->json([
            'success' => true,
            'data' => $periode
        ]);
    }

    public function update(Request $request)
    {
        $periode = Periode::find($request->id);
        $periode->mulai = $request->mulai;
        $periode->selesai = $request->selesai;
        if ($request->aktif == 'on') {
            $periode->aktif = 1;
        } else {
            $periode->aktif = 0;
        }
        $periode->keterangan = $request->keterangan;
        $periode->tahun_ajaran = $request->tahun_ajaran;
        $periode->save();

        return response()->json([
            'success' => true,
            'message' => 'Periode updated successfully!'
        ]);
    }

    public function destroy($id)
    {
        $periode = Periode::find($id);
        try {
            $periode->delete();

            return response()->json([
                'success' => true,
                'message' => 'Periode deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Periode cannot be deleted!'
            ]);
        }
    }
}
