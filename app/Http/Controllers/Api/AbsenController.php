<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckInRequest;
use App\Models\Absensi;
use App\Models\Pertemuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AbsenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function checkIn(CheckInRequest $checkInRequest, $pertemuanID)
    {
        $pertemuan = Pertemuan::with('sesiMataKuliah.dosen', 'sesiMataKuliah.periode_mata_kuliah.mata_kuliah', 'sesiMataKuliah.jadwalTeori', 'sesiMataKuliah.jadwalPraktikum')->find($pertemuanID);
        if (!$pertemuan) {
            return response()->json([
                'success' => false,
                'message' => 'Pertemuan tidak ditemukan',
                'data' => null
            ], 404);
        }

        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);

        $alreadyTake = Absensi::where('pertemuan_id', $pertemuanID)->where('username', $user->username)->first();
        if ($alreadyTake) {
            return response()->json([
                'success' => false,
                'message' => 'Absensi sudah diambil',
                'data'    => null
            ]);
        }

        $now = now();
        $timeNow = $now->toTimeString();
        $dateNow = $now->toDateString();
        if ($pertemuan->tanggal > $dateNow) {
            return response()->json([
                'success' => false,
                'message' => 'Bukan waktu absensi',
                'data'    => null
            ]);
        } else if ($pertemuan->tanggal <  $dateNow) {
            return response()->json([
                'success' => false,
                'message' => 'Waktu absensi sudah habis',
                'data'    => null
            ]);
        }

        if ($pertemuan->is_praktikum) {
            $jadwalPraktikum = $pertemuan->sesiMataKuliah->jadwalPraktikum;
            $pass = $dateNow > $jadwalPraktikum->start && $timeNow < $jadwalPraktikum->end;

            if ($pass) {
                $data = new Absensi();
                $data->username = $user->username;
                $data->latitude = $checkInRequest->latitude;
                $data->longitude = $checkInRequest->longitude;
                if ($checkInRequest->attachment) {
                    $file = $checkInRequest->file('attachment');
                    if ($file != null && $file != '') {
                        $filename = time() . '.' . $file->getClientOriginalExtension();
                        $file->move(public_path("/absen"), $filename);
                    }
                }
                $data->attachment = $filename ?? null;
                $data->date_in = $now;
                $data->hadir = true;
                $data->pertemuan_id = $pertemuanID;
                $data->save();
                return response()->json([
                    'success' => true,
                    'message' => "berhasil",
                    'data'    => null,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "Tidak dapat melakukan absen, waktu perkuliahan sudah habis",
                    'data'    => null,
                ]);
            }
        }

        // if ($now > $) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Waktu absensi sudah habis',
        //         'data'    => null
        //     ]);
        // }

        return response()->json($pertemuan);
    }

    public function getStatus($pertemuanID)
    {
        $user = Auth::user();

        $statusAbsensi = Absensi::where('pertemuan_id', $pertemuanID)->where('username', $user->username)->first();
        if ($statusAbsensi) {
            if ($statusAbsensi->attachment != null) {
                $statusAbsensi->attachment = url('absen/' . $statusAbsensi->attachment);
            }
            return response()->json([
                'success' => false,
                'message' => 'Absensi sudah diambil',
                'data'    => $statusAbsensi
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Belum ada data absensi',
                'data'    => null
            ]);
        }
    }
}
