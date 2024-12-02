<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MataKuliahController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function data(Request $request)
    {
        try {
            $mataKuliah = MataKuliah::with('prodi', 'prodi.fakultas')
                ->when($request->keyword, function ($q) use ($request) {
                    $q->where('nama_mk', 'LIKE', "%{$request->keyword}%")->orWhere('kode_mk', 'LIKE', "%{$request->keyword}%");
                })->get();

            return response()->json([
                'success' => true,
                'message' => 'Success fetch data mata kuliah',
                'data' => $mataKuliah
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
            'kode_mk'   => 'required',
            'nama_mk'   => 'required',
            'sks'       => 'required',
            'prodi_id'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(),
                'data'    => null
            ], 400);
        }

        try {
            $check = MataKuliah::where('kode_mk', $request->kode_mk)->first();
            if ($check) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode Mata Kuliah sudah ada!',
                    'data'    => null
                ], 400);
            }

            $mataKuliah = new MataKuliah();
            $mataKuliah->kode_mk = $request->kode_mk;
            $mataKuliah->nama_mk = $request->nama_mk;
            $mataKuliah->sks = $request->sks;
            $mataKuliah->prodi_id = $request->prodi_id;
            $mataKuliah->save();

            $getMataKuliah = MataKuliah::with('prodi', 'prodi.fakultas')->find($mataKuliah->id);

            return response()->json([
                'success' => true,
                'message' => 'Mata Kuliah created successfully!',
                'data'    => $getMataKuliah
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data'    => null
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode_mk'   => 'required',
            'nama_mk'   => 'required',
            'sks'       => 'required',
            'prodi_id'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(),
                'data'    => null
            ], 400);
        }

        try {
            $mataKuliah = MataKuliah::find($id);
            $mataKuliah->kode_mk = $request->kode_mk;
            $mataKuliah->nama_mk = $request->nama_mk;
            $mataKuliah->sks = $request->sks;
            $mataKuliah->prodi_id = $request->prodi_id;
            $mataKuliah->save();

            $getMataKuliah = MataKuliah::with('prodi', 'prodi.fakultas')->find($mataKuliah->id);

            return response()->json([
                'success' => true,
                'message' => 'Mata Kuliah updated successfully!',
                'data'    => $getMataKuliah
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data'    => null
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $mataKuliah = MataKuliah::find($id);
            $mataKuliah->delete();

            return response()->json([
                'success' => true,
                'message' => 'Mata kuliah deleted successfully!',
                'data'    => $mataKuliah
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
