<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MahasiswaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function data(Request $request)
    {
        try {
            $mahasiswa = Mahasiswa::with('fakultas', 'prodi')
                ->when($request->keyword, function ($q) use ($request) {
                    $q->where('nama_mahasiswa', 'LIKE', "%{$request->keyword}%")->orWhere('nim', 'LIKE', "%{$request->keyword}%");
                })->get();

            foreach ($mahasiswa as $key => $value) {
                $user = User::where('username', $value->nim)->first();
                if ($user) {
                    $mahasiswa[$key]['is_user'] = true;
                } else {
                    $mahasiswa[$key]['is_user'] = false;
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Success fetch data mahasiswa',
                'data' => $mahasiswa
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data'    => null
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nim'            => 'required',
            'nama_mahasiswa' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(),
                'data'    => null
            ], 400);
        }
        try {
            $cekMahasiswa = Mahasiswa::where('nim', $request->nim)->first();
            if ($cekMahasiswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'NIM sudah ada.',
                    'data'    => null
                ], 400);
            }

            Mahasiswa::create([
                'nim'            => $request->nim,
                'nama_mahasiswa' => $request->nama_mahasiswa,
            ]);

            $mahasiswa = Mahasiswa::with('fakultas', 'prodi')->where('nim', $request->nim)->first();
            $mahasiswa['is_user'] = false;

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditambahkan.',
                'data'    => $mahasiswa
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data'    => null
            ], 500);
        }
    }

    public function update(Request $request, $nim)
    {
        try {
            $mahasiswa = Mahasiswa::with('fakultas', 'prodi')->where('nim', $nim)->first();

            $mahasiswa->nama_mahasiswa = $request->nama_mahasiswa;
            $mahasiswa->save();

            $user = User::where('username', $mahasiswa->nim)->first();
            if ($user) {
                $mahasiswa['is_user'] = true;
            } else {
                $mahasiswa['is_user'] = false;
            }

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diubah.',
                'data'    => $mahasiswa
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data'    => null
            ], 500);
        }
    }

    public function destroy(Request $request, $nim)
    {
        try {
            $mahasiswa = Mahasiswa::where('nim', $nim)->first();
            $user = User::where('username', $mahasiswa->nim)->first();
            if ($user) {
                $user->delete();
            }
            $mahasiswa->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus.',
                'data'    => $mahasiswa
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data'    => null
            ], 500);
        }
    }
}
