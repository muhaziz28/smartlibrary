<?php

namespace App\Http\Controllers;

use App\Models\SesiMataKuliah;
use App\Models\Session;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class SessionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id)
    {
        $sesi = SesiMataKuliah::with('mata_kuliah_diambil', 'pertemuan')->find($id);

        $sessionData = Session::where('pertemuan_id', $sesi->pertemuan[0]->id)->get();
        if (count($sessionData) != 0) {
            $allSession = [];
            foreach ($sesi->pertemuan as $pertemuan) {
                $allSession[] = Session::with('pertemuan', 'sessionData')->where('pertemuan_id', $pertemuan->id)->first();
            }
            // return response()->json($allSession);
            return view('absensi.index', compact('sesi', 'id', 'allSession'));
        }

        return view('absensi.create-session', compact('sesi', 'id'));
    }

    public function store(Request $request)
    {
        try {
            // Ambil sesi berdasarkan ID yang diberikan dalam permintaan
            $sesi = SesiMataKuliah::with('pertemuan')->find($request->id);

            if (!$sesi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sesi tidak ditemukan',
                ]);
            }

            // Inisialisasi transaksi database
            DB::beginTransaction();

            // Ambil tanggal dari permintaan
            $date = new \DateTime($request->date);

            // Loop melalui setiap pertemuan dan simpan sesi
            foreach ($sesi->pertemuan as $index => $pertemuan) {
                // Tambahkan 7 hari (1 minggu) sesuai dengan urutan pertemuan
                $nextDate = clone $date;
                $nextDate->modify("+{$index} week");

                $sessionData = new Session();
                $sessionData->time_start = $request->time_start;
                $sessionData->time_end = $request->time_end;
                $sessionData->description = $request->description;
                $sessionData->date = $nextDate->format('Y-m-d'); // Set tanggal sesi
                $sessionData->pertemuan_id = $pertemuan->id;
                $sessionData->save();
            }

            // Commit transaksi database
            DB::commit();

            return view('absensi.index', compact('sesi', 'id'));
        } catch (Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
