<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\MataKuliahDiajukan;
use App\Models\MataKuliahDiambil;
use App\Models\Periode;
use App\Models\Pertemuan;
use App\Models\SesiMataKuliah;
use App\Services\InstrumentService;
use App\Services\KompetensiService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private InstrumentService $instrumentService)
    {
        $this->middleware('auth:web');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $events = [];

        if (Auth::user()->role_id == 2) {
            $mahasiswa = Mahasiswa::where('nim', auth()->user()->username)->first();

            $sesiMataKuliah = SesiMataKuliah::with('periode_mata_kuliah.mata_kuliah', 'mata_kuliah_diambil', 'dosen')->whereHas('periode_mata_kuliah.periode', function ($query) {
                $query->where('aktif', 1);
            })->whereDoesntHave('mata_kuliah_diambil', function ($query) {
                $query->where('nim', auth()->user()->username);
            })->whereHas('periode_mata_kuliah.mata_kuliah', function ($query) use ($mahasiswa) {
                $query->where('prodi_id', $mahasiswa->prodi_id);
            })->whereDoesntHave('mata_kuliah_diajukan', function ($query) {
                $query->where('nim', auth()->user()->username);
            })->get();

            $mataKuliah = SesiMataKuliah::with('periode_mata_kuliah.mata_kuliah', 'mata_kuliah_diajukan', 'dosen')->whereHas('periode_mata_kuliah.periode', function ($query) {
                $query->where('aktif', 1);
            })->whereHas('mata_kuliah_diajukan', function ($query) {
                $query->where('nim', auth()->user()->username)->where('disetujui', 'ditolak');
            })->whereDoesntHave('mata_kuliah_diajukan', function ($query) {
                $query->where('nim', auth()->user()->username)->where('disetujui', 'disetujui');
            })->whereDoesntHave('mata_kuliah_diajukan', function ($query) {
                $query->where('nim', auth()->user()->username)->where('disetujui', 'pending');
            })->get();

            $diajukan = MataKuliahDiajukan::with('sesi_mata_kuliah.periode_mata_kuliah.periode', 'sesi_mata_kuliah.periode_mata_kuliah.mata_kuliah', 'sesi_mata_kuliah.dosen', 'mahasiswa')->whereHas('sesi_mata_kuliah.periode_mata_kuliah.periode', function ($query) {
                $query->where('aktif', 1);
            })->where('nim', auth()->user()->username)->get();
            // merge collection
            $perPage = 10;
            $currentPage = request('page', 1);

            $sesiMataKuliah = $sesiMataKuliah->merge($mataKuliah);
            $totalItems = $sesiMataKuliah->count();
            $totalPages = ceil($totalItems / $perPage);
            $matakuliahTersedia = $sesiMataKuliah->skip(($currentPage - 1) * $perPage)->take($perPage);

            $totalItemsMataKuliahDiajukan = $diajukan->count();
            $totalPagesMataKuliahDiajukan = ceil($totalItemsMataKuliahDiajukan / $perPage);
            $matakuliahDiajukan = $diajukan->skip(($currentPage - 1) * $perPage)->take($perPage);
            // return response()->json($diajukan);
            // return response()->json($matakuliahTersedia);
            $periodeAktif = Periode::where('aktif', 1)->first();

            $mataKuliahDiambil = MataKuliahDiambil::where('nim', Auth::user()->nim)->whereHas('sesiMataKuliah', function ($query) use ($periodeAktif) {
                $query->where('sesi_mata_kuliah.periode_mata_kuliah_id', $periodeAktif->id);
            })->with('sesiMataKuliah')->get();

            // return view('home', compact('mataKuliahDiambil'));
            return view('home', compact('events', 'matakuliahTersedia', 'mataKuliahDiambil', 'sesiMataKuliah', 'totalItems', 'totalItemsMataKuliahDiajukan', 'totalPagesMataKuliahDiajukan', 'matakuliahDiajukan', 'totalPages', 'currentPage', 'perPage'));
        }

        if (Auth::user()->role_id == 3) {
            $sesiMataKuliah = SesiMataKuliah::with('periode_mata_kuliah.mata_kuliah', 'pertemuan')->where('kode_dosen', Auth::user()->username)->get();
            foreach ($sesiMataKuliah as $sesi) {
                $sesiTeori = $sesi->pertemuan->where('is_praktikum', false);
                foreach ($sesiTeori as $pertemuanItem) {
                    $scheduleDate = Carbon::parse($pertemuanItem->tanggal)->format('Y-m-d');
                    $start_time = Carbon::parse($sesi->jadwal_teori);

                    $sks = $sesi->periode_mata_kuliah->mata_kuliah->sks;

                    $duration = $sks * 50;

                    $finish_time = $start_time->copy()->addMinutes($duration);

                    $events[] = [
                        'title' => 'Teori ' . $sesi->periode_mata_kuliah->mata_kuliah->nama . ' - Pertemuan ' . $pertemuanItem->pertemuan_ke .  '(Kode sesi: ' . $sesi->kode_sesi . ')',
                        'start' => $scheduleDate . 'T' . $start_time->format('H:i:s'),
                        'end' => $scheduleDate . 'T' . $finish_time->format('H:i:s'),
                    ];
                }
            }

            foreach ($sesiMataKuliah as $sesi) {
                $sesiTeori = $sesi->pertemuan->where('is_praktikum', true);
                foreach ($sesiTeori as $pertemuanItem) {
                    $scheduleDate = Carbon::parse($pertemuanItem->tanggal)->format('Y-m-d');
                    $start_time = Carbon::parse($sesi->jadwal_teori);

                    $sks = $sesi->periode_mata_kuliah->mata_kuliah->sks;

                    $duration = $sks * 50;

                    $finish_time = $start_time->copy()->addMinutes($duration);

                    $events[] = [
                        'title' => 'Praktikum ' . $sesi->periode_mata_kuliah->mata_kuliah->nama . ' - Pertemuan ' . $pertemuanItem->pertemuan_ke .  '(Kode sesi: ' . $sesi->kode_sesi . ')',
                        'start' => $scheduleDate . 'T' . $start_time->format('H:i:s'),
                        'end' => $scheduleDate . 'T' . $finish_time->format('H:i:s'),
                    ];
                }
            }
        }

        if (Auth::user()->role_id == 1) {
            $totalMahasiswa = Mahasiswa::count();
            $totalDosen = Dosen::count();

            $activePeriode = Periode::where('aktif', '1')->first();
            return view('home', compact('events', 'totalMahasiswa', 'totalDosen', 'activePeriode'));
        }

        // // dd($events);
        // $result = json_encode($events);
        // // dd($result);
        return view('home', compact('events'));
    }
}
