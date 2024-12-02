<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Pertemuan;
use Illuminate\Http\Request;

class DetailAbsensiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($pertemuanID)
    {
        $pertemuan = Pertemuan::with('sesiMataKuliah.jadwalTeori', 'sesiMataKuliah.jadwalPraktikum', 'sesiMataKuliah.dosen', 'sesiMataKuliah.mata_kuliah_diambil.mahasiswa')->find($pertemuanID);
        $mahasiswa = $pertemuan->sesiMataKuliah->mata_kuliah_diambil;

        foreach ($mahasiswa as $m) {
            $absensi = Absensi::where('pertemuan_id', $pertemuanID)
                ->where('username', $m->nim)
                ->first();

            if ($absensi) {
                $m->absensi = $absensi;
            } else {
                $m->absensi = null;
            }
        }

        return view('detail-absensi.index', compact('pertemuanID', 'pertemuan', 'mahasiswa'));
    }

    public function insertAbsensi(Request $request)
    {
        $absensi = new Absensi();
        $absensi->username = $request->username;
        $absensi->date_in = now()->toDateString();
        $absensi->pertemuan_id = $request->pertemuan_id;
        $absensi->hadir = $request->hadir ? 1 : 0;
        $absensi->save();

        return response()->json('berhasil');
    }
}
