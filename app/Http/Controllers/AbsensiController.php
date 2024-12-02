<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\AbsensiType;
use App\Models\Pertemuan;
use App\Models\SesiMataKuliah;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id)
    {
        $sesi = SesiMataKuliah::with('mata_kuliah_diambil', 'pertemuan', 'jadwalTeori')->find($id);
        $pertemuanTeori = $sesi->pertemuan->where('is_praktikum', false);
        $pertemuanPraktikum = $sesi->pertemuan->where('is_praktikum', true);

        $absensiType = AbsensiType::all();

        foreach ($sesi->pertemuan as $pertemuan) {
            $pertemuan->absensi_type = $absensiType->where('pertemuan_id', $pertemuan->id)->first();
        }

        return view('absensi.index', compact('sesi', 'pertemuanTeori', 'pertemuanPraktikum', 'id'));
    }

    public function addType(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required',
            ]);

            AbsensiType::create([
                'type' => $request->type,
                'pertemuan_id' => $request->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Absensi type berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create($pertemuanId)
    {
        $pertemuan = Pertemuan::with('sesiMataKuliah.mata_kuliah_diambil.mahasiswa')->find($pertemuanId);

        $absensi = Absensi::where('pertemuan_id', $pertemuanId)->get();

        $mahasiswa = collect();
        if ($absensi->count() > 0) {
            $mahasiswa = $pertemuan->sesiMataKuliah->mata_kuliah_diambil->map(function ($item) use ($absensi) {
                $absen = $absensi->where('nim', $item->mahasiswa->nim)->first();
                $item->absen = $absen;
                return $item;
            });
        }

        return view('absensi.create', compact('pertemuan', 'mahasiswa', 'pertemuanId'));
    }

    public function addAbsensi(Request $request)
    {
        try {
            $request->validate([
                'nim' => 'required',
                'type' => 'required'
            ]);

            $pertemuan = Pertemuan::find($request->pertemuan_id);

            $checkAbsensi = Absensi::where('pertemuan_id', $pertemuan->id)->get();
            if ($checkAbsensi->count() > 0) {
                // update absensi
                foreach ($request->nim as $key => $nim) {
                    $absensi = Absensi::where('pertemuan_id', $pertemuan->id)->where('nim', $nim)->first();
                    if ($absensi) {
                        $absensi->update([
                            'status' => $request->type[$key],
                            'is_approved' => true,
                        ]);
                    }

                    // jika absensi tidak ada, maka tambahkan absensi
                    if (!$absensi) {
                        Absensi::create([
                            'pertemuan_id' => $pertemuan->id,
                            'nim' => $nim,
                            'status' => $request->type[$key],
                            'is_approved' => true,
                        ]);
                    }
                }
            } else {

                foreach ($request->nim as $key => $nim) {
                    Absensi::create([
                        'pertemuan_id' => $pertemuan->id,
                        'nim' => $nim,
                        'status' => $request->type[$key],
                        'is_approved' => true,
                    ]);
                }

                // return response()->json([
                //     'success' => true,
                //     'message' => 'Absensi berhasil ditambahkan'
                // ]);
            }
            return redirect()->back();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
