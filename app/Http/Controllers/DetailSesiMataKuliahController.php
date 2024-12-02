<?php

namespace App\Http\Controllers;

use App\Models\Angket;
use App\Models\Instrument;
use App\Models\MataKuliahDiajukan;
use App\Models\MataKuliahDiambil;
use App\Models\ModulPengantar;
use App\Models\Pengantar;
use App\Models\Periode;
use App\Models\PeriodeMataKuliah;
use App\Models\Pertemuan;
use App\Models\Rps;
use App\Models\SesiMataKuliah;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DetailSesiMataKuliahController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index($id)
    {
        $mahasiswa = MataKuliahDiambil::with('mahasiswa', 'sesiMataKuliah.periode_mata_kuliah.periode')
            ->whereHas('sesiMataKuliah.periode_mata_kuliah.periode', function ($query) {
                $query->where('aktif', 1);
            })
            ->where('sesi_mata_kuliah_id', $id);
        $peserta = $mahasiswa->count();

        $konfirmasiPeserta = MataKuliahDiajukan::with('sesi_mata_kuliah.periode_mata_kuliah.periode', 'sesi_mata_kuliah.periode_mata_kuliah.mata_kuliah', 'sesi_mata_kuliah.dosen', 'mahasiswa.prodi', 'mahasiswa.fakultas')
            ->whereHas('sesi_mata_kuliah.periode_mata_kuliah.periode', function ($query) {
                $query->where('aktif', 1);
            })
            ->whereHas('sesi_mata_kuliah', function ($query) {
                $query->where('kode_dosen', auth()->user()->username);
            })
            ->where('sesi_mata_kuliah_id', $id)
            ->where('disetujui', '!=', 'disetujui')->where('disetujui', '!=', 'ditolak')
            ->count();

        $sesiMataKuliah = SesiMataKuliah::with('periode_mata_kuliah.periode', 'dosen', 'jadwalTeori', 'jadwalPraktikum')->find($id);

        $pengantar = Pengantar::with('sesi_mata_kuliah')->where('sesi_mata_kuliah_id', $id)->get();

        $pertemuanTeori = Pertemuan::with('sesiMataKuliah')->where('sesi_mata_kuliah_id', $id)->where('is_praktikum', false)->get();
        $pertemuanPraktikum = Pertemuan::with('sesiMataKuliah')->where('sesi_mata_kuliah_id', $id)->where('is_praktikum', true)->get();

        $periode = Periode::where('aktif', 0)->get();

        $periodeMataKuliah = PeriodeMataKuliah::with('periode', 'mata_kuliah')->where('id', $sesiMataKuliah->periode_mata_kuliah_id)->first();

        $sesi = SesiMataKuliah::where('kode_dosen', Auth::user()->username)
            ->whereHas('periode_mata_kuliah.periode', function ($query) {
                $query->where('aktif', 1);
            })
            ->where('kode_sesi', '!=', $sesiMataKuliah->kode_sesi)->get();

        $checkAngket = null;
        if (Auth::user()->role_id === 2) {
            $checkAngket = Angket::where('nim', Auth::user()->username)->where('sesi_mata_kuliah_id', $id)->first();
        }

        $angket = Angket::with('nilais')->where('sesi_mata_kuliah_id', $id)->get();
        $arrayAngket = $angket->toArray();
        $nilai = [];
        foreach ($arrayAngket as $a) {
            $nilai[] = $a['nilais'];
        }
        $mahasiswaAngket = [];
        foreach ($angket as $a) {
            $mahasiswaAngket[] = $a->nim;
        }

        $mahasiswaIsFilledQuest = MataKuliahDiambil::with('mahasiswa')
            ->whereHas('mahasiswa', function ($query) use ($mahasiswaAngket) {
                $query->whereIn('nim', $mahasiswaAngket);
            })
            ->where('sesi_mata_kuliah_id', $id)
            ->get();

        $mahasiswaIsNotFilledQuest = MataKuliahDiambil::with('mahasiswa')
            ->whereHas('mahasiswa', function ($query) use ($mahasiswaAngket) {
                $query->whereNotIn('nim', $mahasiswaAngket);
            })
            ->where('sesi_mata_kuliah_id', $id)
            ->get();

        $instrument = Instrument::all();
        $dataSet = [];

        foreach ($instrument as $i) {
            foreach ($nilai as $n) {
                foreach ($n as $nn) {
                    if ($nn['instrument_id'] == $i->id) {
                        $dataSet[$i->id][] = $nn['nilai'];
                        $dataSet[$i->id]['result'] = array_sum($dataSet[$i->id]) / count($dataSet[$i->id]);
                        $dataSet[$i->id]['result'] = round($dataSet[$i->id]['result'], 2);
                    }
                }
            }
        }
        $instrumentLabel = [];
        foreach ($instrument as $i) {
            $instrumentLabel[] = $i->item;
        }

        $dataSet2 = [];
        foreach ($dataSet as $d) {
            $dataSet2[] = $d;
        }

        $pengantar = Pengantar::with('sesi_mata_kuliah')->where('sesi_mata_kuliah_id', $id)->first();
        $rps = Rps::where('sesi_mata_kuliah_id', $id)->get();
        $modulPengantar = ModulPengantar::where('sesi_mata_kuliah_id', $id)->get();

        return view('detail_sesi.index', compact(
            'instrumentLabel',
            'dataSet2',
            'instrument',
            'sesi',
            'periode',
            'periodeMataKuliah',
            'pertemuanTeori',
            'pertemuanPraktikum',
            'sesiMataKuliah',
            'pengantar',
            'peserta',
            'konfirmasiPeserta',
            'id',
            'checkAngket',
            'mahasiswaIsFilledQuest',
            'mahasiswaIsNotFilledQuest',
            'pengantar',
            'rps',
            'modulPengantar',
        ));
    }

    public function indexDosen(Request $request)
    {
        $perPage = 10;
        $currentPage = request('page', 1);

        $sesi = null;
        if ($request->periode == null) {
            $sesi =
                SesiMataKuliah::with('periode_mata_kuliah.mata_kuliah', 'dosen', 'mata_kuliah_diajukan')
                ->where('kode_dosen', Auth::user()->username)
                ->whereHas('periode_mata_kuliah.periode', function ($query) {
                    $query->where('aktif', 1);
                });
        } else {
            $sesi = SesiMataKuliah::with('periode_mata_kuliah.mata_kuliah', 'dosen', 'mata_kuliah_diajukan')
                ->where('kode_dosen', Auth::user()->username)
                ->when($request->periode, function ($query) use ($request) {
                    $query->whereHas('periode_mata_kuliah', function ($query) use ($request) {
                        $query->where('periode_id', $request->periode);
                    });
                });
        }

        $totalItems = $sesi->count();
        $totalPages = ceil($totalItems / $perPage);
        $sesi = $sesi->skip(($currentPage - 1) * $perPage)->take($perPage)->get();

        $periode = Periode::all();

        return view('sesi_dosen.index', compact('sesi', 'totalItems', 'totalPages', 'currentPage', 'perPage', 'periode', 'request'));
    }

    public function updateChatID(Request $request)
    {
        try {
            $sesiMataKuliah = SesiMataKuliah::find($request->id);
            $sesiMataKuliah->chat_id = $request->chat_id;
            $sesiMataKuliah->save();

            return response()->json([
                'success' => true,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateRadius(Request $request)
    {
        try {
            if ($request->rad < 100) {
                return response()->json([
                    'success' => false,
                    'message' => 'Radius minimal 100 meter'
                ]);
            }

            $sesiMataKuliah = SesiMataKuliah::find($request->id);

            $sesiMataKuliah->radius = $request->rad;
            $sesiMataKuliah->save();

            return response()->json([
                'success' => true,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
