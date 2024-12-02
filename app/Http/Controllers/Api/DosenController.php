<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DosenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function data(Request $request)
    {
        $dosen = Dosen::with('fakultas')->get();

        foreach ($dosen as $key => $value) {
            $user = User::where('username', $value->kode_dosen)->first();
            if ($user) {
                $dosen[$key]['is_user'] = true;
            } else {
                $dosen[$key]['is_user'] = false;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Success fetch data dosen',
            'data' => $dosen
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_dosen' => 'required',
            'nama_dosen' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success'   => false,
                'message'   => $validator->errors()->all(),
                'data'      => null
            ], 400);
        }
        try {
            $cekDosen = Dosen::with('fakultas')->where('kode_dosen', $request->kode_dosen)->first();
            if ($cekDosen) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode dosen sudah ada.',
                    'data' => null
                ], 400);
            }

            Dosen::create($request->all());

            $dosen = Dosen::with('fakultas')->where('kode_dosen', $request->kode_dosen)->first();
            $dosen['is_user'] = false;

            return response()->json([
                'success'   => true,
                'message'   => 'Data berhasil ditambahkan.',
                'data'      => $dosen
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function update(Request $request, $kodeDosen)
    {
        try {
            $dosen = Dosen::with('fakultas')->where('kode_dosen', $kodeDosen)->first();

            $dosen->nama_dosen = $request->nama_dosen;

            $dosen->fakultas_id = $request->fakultas_id;
            $dosen->save();

            $user = User::where('username', $kodeDosen)->first();
            if ($user) {
                $dosen['is_user'] = true;
            } else {
                $dosen['is_user'] = false;
            }

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diubah.',
                'data'    => $dosen
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data'    => null
            ], 500);
        }
    }

    public function destroy($kodeDosen)
    {
        $dosen = Dosen::where('kode_dosen', $kodeDosen)->first();
        $user = User::where('username', $dosen->kode_dosen)->first();
        if ($user) {
            $user->delete();
        }
        $dosen->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus.',
            'data'    => $dosen
        ], 200);
    }

    public function makeUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_dosen'    => 'required',
            'fakultas_id'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success'   => false,
                'message'   => $validator->errors()->all(),
                'data'      => null
            ], 400);
        }
        try {

            $dosen = Dosen::with('fakultas')->where('kode_dosen', $request->kode_dosen)->first();

            $user = new User();
            $user->username = $request->kode_dosen;
            $user->password = Hash::make($request->kode_dosen);
            $user->role_id = 3;

            $user->save();

            $dosen->fakultas_id = $request->fakultas_id;
            $dosen->save();


            $getDosen = Dosen::with('fakultas')->where('kode_dosen', $request->kode_dosen)->first();
            $getDosen['is_user'] = true;

            return response()->json([
                'success'   => true,
                'message'   => 'Data berhasil ditambahkan.',
                'data'      => $getDosen
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}
