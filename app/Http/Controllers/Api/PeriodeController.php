<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PeriodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function data(Request $request)
    {
        try {
            $periode = Periode::when($request->keyword, function ($q) use ($request) {
                $q->where('tahun_ajaran', '=', $request->keyword);
            })->get();

            return response()->json([
                'success' => true,
                'message' => 'Success fetch data periode',
                'data' => $periode
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
        try {
            $validate = Validator::make($request->all(), [
                'mulai' => 'required',
                'selesai' => 'required',
                'aktif' => 'required',
                'keterangan' => 'nullable',
                'tahun_ajaran' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validate->errors()->all(),
                    'data' => null
                ], 400);
            }

            $periode = new Periode();
            $periode->mulai = $request->mulai;
            $periode->selesai = $request->selesai;
            if ($request->aktif == true) {
                $periode->aktif = 1;
            } else {
                $periode->aktif = 0;
            }
            $periode->keterangan = $request->keterangan;
            $periode->tahun_ajaran = $request->tahun_ajaran;
            $periode->save();

            return response()->json([
                'success' => true,
                'message' => 'Periode created successfully!',
                'data' => $periode
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
        try {
            $periode = Periode::find($id);
            $periode->mulai = $request->mulai;
            $periode->selesai = $request->selesai;
            if ($request->aktif == true) {
                $periode->aktif = 1;
            } else {
                $periode->aktif = 0;
            }
            $periode->keterangan = $request->keterangan;
            $periode->tahun_ajaran = $request->tahun_ajaran;
            $periode->save();

            return response()->json([
                'success' => true,
                'message' => 'Periode updated successfully!',
                'data' => $periode
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
            $periode = Periode::find($id);
            $periode->delete();

            return response()->json([
                'success' => true,
                'message' => 'Periode deleted successfully!',
                'data' => $periode
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
