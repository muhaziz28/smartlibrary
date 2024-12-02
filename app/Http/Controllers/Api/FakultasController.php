<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FakultasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function data(Request $request)
    {
        $fakultas = Fakultas::when($request->keyword, function ($q) use ($request) {
            $q->where('nama_fakultas', 'LIKE', "%{$request->keyword}%")->orWhere('kode_fakultas', 'LIKE', "%{$request->keyword}%");
        })->get();

        return response()->json([
            'success'   => true,
            'message'   => 'Success fetch data fakultas',
            'data'      => $fakultas
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_fakultas' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(),
                'data' => null
            ], 400);
        }
        $checkFakultas = Fakultas::where('nama_fakultas', '=', $request->nama_fakultas)->first();
        if ($checkFakultas) {
            return response()->json([
                'success' => false,
                'message' => 'Fakultas already exists!',
                'data' => null
            ], 400);
        }
        try {
            $fakultas = new Fakultas();
            $fakultas->nama_fakultas = $request->nama_fakultas;
            $fakultas->kode_fakultas = $request->kode_fakultas;
            $fakultas->save();

            return response()->json([
                'success'   => true,
                'message'   => 'Fakultas created successfully!',
                'data'      => $fakultas
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function update(Request $request)
    {
        $fakultas = Fakultas::find($request->id);
        $fakultas->nama_fakultas = $request->nama_fakultas;
        $fakultas->kode_fakultas = $request->kode_fakultas;

        if ($fakultas->nama_fakultas != $request->nama_fakultas) {
            $checkFakultas = Fakultas::where('nama_fakultas', '=', $request->nama_fakultas)->first();
            if ($checkFakultas) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fakultas already exists!',
                    'data' => null
                ], 400);
            }
        }

        $fakultas->save();

        return response()->json([
            'success' => true,
            'message' => 'Fakultas updated successfully!',
            'data' => $fakultas
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        $fakultas = Fakultas::find($id);

        $prodi = $fakultas->prodi;
        if ($prodi != null) {
            foreach ($prodi as $p) {
                $p->delete();
            }

            $fakultas->delete();
        }

        return response()->json([
            'success'   => true,
            'message'   => 'Fakultas deleted successfully!',
            'data'      => null
        ], 200);
    }
}
