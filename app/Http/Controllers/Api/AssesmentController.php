<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        $this->middleware('auth:api');
    }

    public function data($tugasId)
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

        return response()->json([
            'success' => true,
            'message' => 'Data tugas berhasil diambil',
            'data' => $data
        ], 200);
    }

    public function detail(Request $request, $tugasId)
    {
        try {

            $validate = $request->validate([
                'nim' => 'required'
            ]);

            if (!$validate) {
                return response()->json([
                    'success' => false,
                    'message' => 'NIM harus diisi',
                    'data' => null
                ], 400);
            }

            $tugas = Tugas::find($tugasId);
            $pertemuan = Pertemuan::find($tugas->pertemuan_id);
            $sesi = SesiMataKuliah::with('mata_kuliah_diambil')->find($pertemuan->sesi_mata_kuliah_id);

            $tugasMahasiswa = TugasMahasiswa::where('tugas_id', $tugasId)->where('nim', $request->nim)->get();
            $user = User::where('username', $request->nim)->first();

            return response()->json([
                'success' => true,
                'message' => 'Data tugas berhasil diambil',
                'data' => [
                    'tugas' => $tugas,
                    'tugas_mahasiswa' => $tugasMahasiswa,
                    'user' => $user
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function tolak(Request $request, $tugasMahasiswaId)
    {
        try {
            $tugasMahasiswa = TugasMahasiswa::with('tugas')->find($tugasMahasiswaId);
            if ($tugasMahasiswa->status != 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tugas sudah disetujui atau ditolak',
                    'data' => null
                ], 400);
            }
            $tugasMahasiswa->status = 'ditolak';
            $tugasMahasiswa->komentar = $request->komentar;
            $tugasMahasiswa->save();

            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil ditolak',
                'data' => $tugasMahasiswa
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function terima(Request $request, $tugasMahasiswaId)
    {
        try {

            $tugasMahasiswa = TugasMahasiswa::with('tugas')->find($tugasMahasiswaId);
            if ($tugasMahasiswa->status != 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tugas sudah disetujui atau ditolak',
                    'data' => null
                ], 400);
            }
            $validate = $request->validate([
                'nilai' => 'required|numeric|min:0',
            ]);

            if (!$validate) {
                return response()->json(['message' => 'Nilai harus diisi', 'success' => false, 'data' => null], 400);
            }

            $tugasMahasiswa->status = 'disetujui';
            $tugasMahasiswa->nilai = $request->nilai;
            $tugasMahasiswa->komentar = $request->komentar;
            $tugasMahasiswa->save();

            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil disetujui',
                'data' => $tugasMahasiswa
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function kumpulkan(Request $request, $tugasId)
    {
        if (Auth::user()->role_id != 2) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses',
                'data' => null
            ], 403);
        }
        if ($request->file == null && $request->link == null) {
            return response()->json([
                'success' => false,
                'message' => 'File atau link tidak boleh kosong',
                'data' => null
            ]);
        }

        try {
            if ($request->hasFile('file')) {
                if ($request->file('file')->getClientOriginalExtension() != 'pdf') {
                    return response()->json([
                        'success' => false,
                        'message' => 'File harus berupa pdf',
                        'data' => null
                    ], 400);
                }

                $file = $request->file('file');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path("/media"), $filename);

                $tugasMahasiswa = TugasMahasiswa::create([
                    'tugas_id' => $tugasId,
                    'nim' => Auth::user()->username,
                    'file' => $filename,
                    'link' => $request->link,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil menambahkan tugas',
                    'data' => $tugasMahasiswa
                ], 201);
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
                ], 201);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan tugas',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}
