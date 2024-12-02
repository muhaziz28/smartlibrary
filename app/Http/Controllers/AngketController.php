<?php

namespace App\Http\Controllers;

use App\Models\Angket;
use App\Models\Instrument;
use App\Models\Mahasiswa;
use App\Models\Nilai;
use App\Models\Periode;
use App\Models\SesiMataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AngketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index($sesiMataKuliahId)
    {
        $checkAngket = Angket::where('nim', Auth::user()->username)->where('sesi_mata_kuliah_id', $sesiMataKuliahId)->first();
        if ($checkAngket != null) return redirect()->back();

        $user = Auth::user()->role_id;
        if ($user != 2) return redirect()->back();
        $mahasiswa = Mahasiswa::where('nim', Auth::user()->username)->first();

        $sesi = SesiMataKuliah::with('periode_mata_kuliah.mata_kuliah')->find($sesiMataKuliahId);

        $instruments = Instrument::all();

        return view('angket.index', compact('sesiMataKuliahId', 'mahasiswa', 'sesi', 'instruments'));
    }

    public function store(Request $request)
    {
        $checkAngket = Angket::where('nim', Auth::user()->username)->where('sesi_mata_kuliah_id', $request->sesi_mata_kuliah_id)->first();
        if ($checkAngket != null) return redirect()->back();

        $periodeActive = Periode::where('aktif', 1)->first();

        $data = $request->all();
        $instrumentRequst = [];
        $idInstrument = [];
        foreach ($data as $key => $value) {
            if (strpos($key, 'instrument') !== false) {
                $instrumentRequst[] = $value;
                $idInstrument[] = explode('_', $key)[1];
            }
        }

        // compare instrument id with instrument nilai
        $instrument = Instrument::all();
        $instrumentId = [];
        $instrumentNilai = [];
        foreach ($instrument as $key => $value) {
            $instrumentId[] = $value->id;
            $instrumentNilai[] = $value->nilai;
        }

        $nilaiInstrument = [];
        for ($i = 0; $i < count($instrumentId); $i++) {
            $nilaiInstrument[$instrumentId[$i]] = 0;
        }

        for ($i = 0; $i < count($idInstrument); $i++) {
            $nilaiInstrument[$idInstrument[$i]] = $instrumentRequst[$i];
        }

        $data['nilai_instrument'] = $nilaiInstrument;


        $data['nim'] = Auth::user()->username;

        try {
            DB::beginTransaction();
            $angket = new Angket();
            $angket->nim = $data['nim'];
            $angket->periode_id = $periodeActive->id;
            $angket->sesi_mata_kuliah_id = $request->sesi_mata_kuliah_id;
            $angket->save();

            foreach ($data['nilai_instrument'] as $key => $value) {
                $nilai = new Nilai();
                $nilai->angket_id = $angket->id;
                $nilai->instrument_id = $key;
                $nilai->nilai = $value;
                $nilai->save();
            }

            DB::commit();

            return redirect()->route('home');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal mengisi angket');
        }
    }
}
