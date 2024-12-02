<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\MataKuliahDiambil;
use App\Models\Pertemuan;
use App\Models\SesiMataKuliah;
use App\Models\Tugas;
use App\Models\TugasMahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssesmentController extends Controller
{
    public function  __construct()
    {
        $this->middleware('auth');
    }

    public function index($tugasId)
    {
        $tugas = Tugas::find($tugasId);
        $pertemuan = Pertemuan::find($tugas->pertemuan_id);
        $sesi = SesiMataKuliah::with('mata_kuliah_diambil')->find($pertemuan->sesi_mata_kuliah_id);

        $mahasiswa = MataKuliahDiambil::with('mahasiswa')->where('sesi_mata_kuliah_id', $sesi->id)->get();

        $tugasMahasiswa = TugasMahasiswa::where('tugas_id', $tugasId)->orderBy('status', 'asc')->get();

        $data = [];

        foreach ($mahasiswa as $key => $value) {
            $mahasiswaData = $value->mahasiswa;

            $tugasMahasiswaData = [];

            foreach ($tugasMahasiswa as $key2 => $value2) {
                if ($value->nim == $value2->nim) {
                    $tugasMahasiswaData[] = $value2;
                }
            }

            $mahasiswaData['tugas'] = $tugasMahasiswaData;
            $data[] = ['mahasiswa' => $mahasiswaData];
        }

        $perPage = 10;
        $currentPage = request('page', 1);

        $totalItems = count($data);
        $totalPages = ceil($totalItems / $perPage);
        $data = array_slice($data, ($currentPage - 1) * $perPage, $perPage);

        // return response()->json($data);
        return view('tugas.assesment', compact('tugas', 'data', 'totalItems', 'totalPages', 'currentPage', 'perPage'));
    }

    public function detail(Request $request, $tugasId)
    {
        $tugas = Tugas::find($tugasId);
        $pertemuan = Pertemuan::find($tugas->pertemuan_id);
        $sesi = SesiMataKuliah::with('mata_kuliah_diambil')->find($pertemuan->sesi_mata_kuliah_id);

        $mahasiswa = MataKuliahDiambil::with('mahasiswa')->where('sesi_mata_kuliah_id', $sesi->id)->where('nim', $request->nim)->first();
        if ($mahasiswa == null) {
            return redirect()->back();
        }
        $tugasMahasiswa = TugasMahasiswa::where('tugas_id', $tugasId)->where('nim', $request->nim)->get();
        $user = User::where('username', $request->nim)->first();
        $perPage = 10;
        $currentPage = request('page', 1);

        $totalItems = count($tugasMahasiswa);
        $totalPages = ceil($totalItems / $perPage);
        $data = array_slice($tugasMahasiswa->toArray(), ($currentPage - 1) * $perPage, $perPage);
        // return response()->json($data);
        return view('tugas.detail', compact('user', 'tugas', 'data', 'totalItems', 'totalPages', 'currentPage', 'perPage', 'mahasiswa', 'tugasId'));
    }

    public function tolak(Request $request)
    {
        $tugasMahasiswa = TugasMahasiswa::with('tugas')->find($request->id);
        $tugasMahasiswa->status = 'ditolak';
        $tugasMahasiswa->komentar = $request->komentar;
        $tugasMahasiswa->save();

        $pertemuanId = $tugasMahasiswa->tugas->pertemuan_id;

        $sesiMataKuliah = SesiMataKuliah::where('id', Pertemuan::where('id', $pertemuanId)->first()->sesi_mata_kuliah_id)->first();
        $chatid = $sesiMataKuliah->chat_id;

        $mahasiswa = Mahasiswa::where('nim', $tugasMahasiswa->nim)->first();

        if ($chatid != null) {
            $telegramController = new TelegramController;

            $message = "Tugas $mahasiswa->nim-$mahasiswa->nama_mahasiswa ditolak,
Komentar: $request->komentar";

            $telegramController->sendMessage($message, $chatid);
        }

        return redirect()->back();
    }

    public function terima(Request $request)
    {
        $validate = $request->validate([
            'nilai' => 'required|numeric|min:0',
        ]);

        if (!$validate) {
            return response()->json(['message' => 'Nilai harus diisi', 'success' => false]);
        }

        $tugasMahasiswa = TugasMahasiswa::with('tugas')->find($request->id);
        $tugasMahasiswa->status = 'disetujui';
        $tugasMahasiswa->nilai = $request->nilai;
        $tugasMahasiswa->komentar = $request->komentar;
        $tugasMahasiswa->save();

        $pertemuanId = $tugasMahasiswa->tugas->pertemuan_id;

        $sesiMataKuliah = SesiMataKuliah::where('id', Pertemuan::where('id', $pertemuanId)->first()->sesi_mata_kuliah_id)->first();
        $chatid = $sesiMataKuliah->chat_id;

        $mahasiswa = Mahasiswa::where('nim', $tugasMahasiswa->nim)->first();

        if ($chatid != null) {
            $telegramController = new TelegramController;

            $message = "Tugas $mahasiswa->nim-$mahasiswa->nama_mahasiswa diterima";

            $telegramController->sendMessage($message, $chatid);
        }

        return redirect()->back();
    }

    public function store(Request $request)
    {
        if ($request->file == null && $request->link == null) {
            return response()->json([
                'success' => false,
                'message' => 'File atau link tidak boleh kosong'
            ]);
        }

        try {
            if ($request->hasFile('file')) {
                if ($request->file('file')->getClientOriginalExtension() != 'pdf') {
                    return response()->json([
                        'success' => false,
                        'message' => 'File harus berupa pdf'
                    ]);
                }

                $file = $request->file('file');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path("/media"), $filename);

                $tugasMahasiswa = TugasMahasiswa::create([
                    'tugas_id' => $request->tugas_id,
                    'nim' => Auth::user()->username,
                    'file' => $filename,
                    'link' => $request->link,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil menambahkan tugas',
                    'data' => $tugasMahasiswa
                ]);
            } else {
                $tugasMahasiswa = TugasMahasiswa::create([
                    'tugas_id' => $request->tugas_id,
                    'nim' => Auth::user()->username,
                    'link' => $request->link,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil menambahkan tugas',
                    'data' => $tugasMahasiswa
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan tugas',
                'data' => $e->getMessage()
            ]);
        }
    }
}
