<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\JamPerkuliahan;
use App\Models\MataKuliah;
use App\Models\PeriodeMataKuliah;
use App\Models\Pertemuan;
use App\Models\SesiMataKuliah;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SesiMataKuliahController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index($id)
    {
        $periode = PeriodeMataKuliah::find($id);

        $perPage = 10;
        $currentPage = request('page', 1);

        $data = SesiMataKuliah::with('periode_mata_kuliah.mata_kuliah.prodi', 'dosen')->where('periode_mata_kuliah_id', $id)->get();

        $totalItems = $data->count();

        $totalPages = ceil($totalItems / $perPage);
        $sesiMataKuliah = $data->skip(($currentPage - 1) * $perPage)->take($perPage);

        $jam = JamPerkuliahan::all();

        return view('sesi_mata_kuliah.index', compact('id', 'jam', 'sesiMataKuliah', 'periode', 'totalItems', 'totalPages', 'currentPage', 'perPage'));
    }

    public function getDosenByProdiSesi(Request $request, $id)
    {
        $periodeMataKuliah = PeriodeMataKuliah::find($id);
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
            'data' => $dosen,
        ]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_sesi'         => 'required',
            'kode_dosen'        => 'required',
            'jadwal_teori'      => 'required',
            'jadwal_praktikum'  => 'nullable',
            'jam_teori'         => 'required',
            'jam_praktikum'     => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }
        try {
            $sesiMataKuliah = new SesiMataKuliah();
            $sesiMataKuliah->periode_mata_kuliah_id = $request->periode_mata_kuliah_id;
            $sesiMataKuliah->kode_sesi = $request->kode_sesi;
            $sesiMataKuliah->kode_dosen = $request->kode_dosen;
            $sesiMataKuliah->jadwal_teori = $request->jam_teori;
            $sesiMataKuliah->jadwal_praktikum = $request->jam_praktikum;

            $sesiMataKuliah->save();

            $tanggalPertemuanI = Carbon::parse($request->jadwal_teori);

            for ($i = 1; $i <= 16; $i++) {
                $pertemuan = new Pertemuan();
                $pertemuan->sesi_mata_kuliah_id = $sesiMataKuliah->id;
                $pertemuan->pertemuan_ke = $i;
                $pertemuan->tanggal = $tanggalPertemuanI->copy()->addWeeks($i - 1);
                $pertemuan->save();
            }

            if ($request->jadwal_praktikum) {
                $praktikumPertemuanI = Carbon::parse($request->jadwal_praktikum);

                for ($i = 1; $i <= 16; $i++) {
                    $pertemuan = new Pertemuan();
                    $pertemuan->sesi_mata_kuliah_id = $sesiMataKuliah->id;
                    $pertemuan->pertemuan_ke = $i;
                    $pertemuan->tanggal = $praktikumPertemuanI->copy()->addWeeks($i - 1);
                    $pertemuan->is_praktikum = true;
                    $pertemuan->save();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Sesi Mata Kuliah berhasil ditambahkan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function show($id)
    {
        try {
            $sesiMataKuliah = SesiMataKuliah::with('periode_mata_kuliah', 'dosen')->find($id);

            return response()->json([
                'success' => true,
                'data' => $sesiMataKuliah
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_sesi' => 'required',
            'kode_dosen' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }
        try {
            $sesiMataKuliah = SesiMataKuliah::find($request->id);
            $sesiMataKuliah->periode_mata_kuliah_id = $request->periode_mata_kuliah_id;
            $sesiMataKuliah->kode_sesi = $request->kode_sesi;
            $sesiMataKuliah->kode_dosen = $request->kode_dosen;
            $sesiMataKuliah->save();

            return response()->json([
                'success' => true,
                'message' => 'Sesi Mata Kuliah berhasil diupdate',
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
        $sesiMataKuliah = SesiMataKuliah::find($id);
        $sesiMataKuliah->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sesi Mata Kuliah berhasil dihapus',
        ]);
    }
}
