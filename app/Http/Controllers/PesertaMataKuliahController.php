<?php

namespace App\Http\Controllers;

use App\Models\MataKuliahDiajukan;
use App\Models\MataKuliahDiambil;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PesertaMataKuliahController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index($id)
    {
        $peserta = MataKuliahDiambil::with('mahasiswa', 'mahasiswa.fakultas', 'mahasiswa.prodi', 'sesiMataKuliah.periode_mata_kuliah.periode')
            ->whereHas('sesiMataKuliah.periode_mata_kuliah.periode', function ($query) {
                $query->where('aktif', 1);
            })
            ->where('sesi_mata_kuliah_id', $id);

        $perPage = 10;
        $currentPage = request('page', 1);

        $totalItems = $peserta->count();

        $totalPages = ceil($totalItems / $perPage);
        $data = $peserta->skip(($currentPage - 1) * $perPage)->take($perPage)->get();

        // return response()->json($data);
        return view('detail_sesi.peserta', compact('id', 'data', 'totalItems', 'totalPages', 'currentPage', 'perPage'));
    }


    public function destroy(Request $request)
    {
        $id = $request->id;
        $peserta = MataKuliahDiambil::find($id);

        $nim = $peserta->nim;

        $mataKuliahDiajukan = MataKuliahDiajukan::where('nim', $nim)->where('sesi_mata_kuliah_id', $peserta->sesi_mata_kuliah_id)->where('disetujui', 'disetujui')->first();
        $mataKuliahDiajukan->disetujui = 'ditolak';
        $mataKuliahDiajukan->save();

        $peserta->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menghapus peserta'
        ]);
    }
}
