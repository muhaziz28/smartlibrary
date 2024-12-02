<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProdiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function data(Request $request, $id)
    {
        $prodi = Prodi::with('fakultas')->when($request->keyword, function ($q) use ($request) {
            $q->where('nama_prodi', 'LIKE', "%{$request->keyword}%")->orWhere('kode_prodi', 'LIKE', "%{$request->keyword}%");
        })
            ->where('fakultas_id', $id)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Success fetch data prodi',
            'data' => $prodi
        ], 200);
    }

    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_prodi' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success'   => false,
                'message'   => $validator->errors()->all(),
                'data'      => null
            ], 400);
        }
        $checkProdi = Prodi::where('nama_prodi', '=', $request->nama_prodi)->where('fakultas_id', $id)->first();
        if ($checkProdi) {
            return response()->json([
                'success' => false,
                'message' => 'Prodi already exists!',
                'data' => null
            ], 400);
        }
        try {
            $prodi = new Prodi();
            $prodi->nama_prodi = $request->nama_prodi;
            $prodi->kode_prodi = $request->kode_prodi;
            $prodi->fakultas_id = $id;
            $prodi->save();

            $getProdi = Prodi::with('fakultas')->find($prodi->id);

            return response()->json([
                'success' => true,
                'message' => 'Prodi created successfully!',
                'data' => $getProdi
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $prodi = Prodi::with('fakultas')->find($id);
            $prodi->nama_prodi = $request->nama_prodi;
            $prodi->kode_prodi = $request->kode_prodi;

            if ($prodi->nama_prodi != $request->nama_prodi) {
                $checkProdi = Prodi::where('nama_prodi', '=', $request->nama_prodi)->where('fakultas_id', $prodi->fakultas_id)->first();
                if ($checkProdi) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Prodi already exists!',
                        'data' => null
                    ], 400);
                }
            }

            $prodi->save();

            return response()->json([
                'success' => true,
                'message' => 'Prodi updated successfully!',
                'data' => $prodi
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        $prodi = Prodi::find($id);
        $prodi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Prodi deleted successfully!',
            'data' => null
        ]);
    }
}
