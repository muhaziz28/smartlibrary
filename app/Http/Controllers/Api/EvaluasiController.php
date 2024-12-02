<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Evaluasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EvaluasiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function data(Request $request, $pertemuan_id)
    {
        try {

            $data = Evaluasi::where('pertemuan_id', $pertemuan_id)->get();
            return response()->json([
                'success' => true,
                'message' => 'Data evaluasi berhasil diambil',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data'    => null
            ], 500);
        }
    }

    public function store(Request $request, $pertemuan_id)
    {
        $validator = Validator::make($request->all(), [
            'link' => 'required|url',
            'keterangan' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(),
                'data' => null
            ], 400);
        }

        try {
            $evaluasi = new Evaluasi();
            $evaluasi->link = $request->link;
            $evaluasi->pertemuan_id = $pertemuan_id;
            $evaluasi->keterangan = $request->keterangan;
            $evaluasi->save();

            return response()->json([
                'success' => true,
                'message' => 'Evaluasi created successfully',
                'data' => $evaluasi
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
            $evaluasi = Evaluasi::find($id);
            if (!$evaluasi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Evaluasi not found',
                    'data' => null
                ], 404);
            }
            $evaluasi->link = $request->link;
            $evaluasi->keterangan = $request->keterangan;
            $evaluasi->save();

            return response()->json([
                'success' => true,
                'message' => 'Evaluasi updated successfully!',
                'data' => $evaluasi
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $evaluasi = Evaluasi::find($id);
            $evaluasi->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus.',
                'data' => $evaluasi
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
