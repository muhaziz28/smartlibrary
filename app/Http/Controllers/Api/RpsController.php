<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rps;
use Illuminate\Http\Request;

class RpsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function data($id)
    {
        $rps = Rps::where('sesi_mata_kuliah_id', $id)->get();

        try {
            foreach ($rps as $key => $value) {
                if ($value->file != null) {
                    $value->file = url("/media/{$value->file}");
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Data RPS berhasil diambil',
                'data' => $rps
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data'    => null
            ], 500);
        }
    }

    public function store(Request $request, $id)
    {
        if ($request->file == null && $request->link == null) {
            return response()->json([
                'success' => false,
                'message' => 'File atau link tidak boleh kosong',
                'data' => null
            ], 400);
        }

        try {

            if ($request->file('file')->getClientOriginalExtension() != 'pdf') {
                return response()->json([
                    'success' => false,
                    'message' => 'File harus berupa pdf',
                    'data'    => null
                ], 400);
            }

            $file = $request->file('file');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path("/media"), $filename);

            $rps = Rps::create([
                'sesi_mata_kuliah_id' => $id,
                'deskripsi'           => $request->deskripsi,
                'file'                => $filename ?? null,
                'link'                => $request->link,
            ]);

            $rps->sesi_mata_kuliah_id = (int)$rps->sesi_mata_kuliah_id;

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan RPS',
                'data' => $rps
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function update(Request $request, $rpsId)
    {

        try {
            $rps = Rps::findOrfail($rpsId);

            if ($request->file('file')->getClientOriginalExtension() != 'pdf') {
                return response()->json([
                    'success' => false,
                    'message' => 'File harus berupa pdf',
                    'data'    => null
                ], 400);
            }

            if ($rps->file != null) {
                if (file_exists(public_path("/media/" . $rps->file))) {
                    unlink(public_path("/media/" . $rps->file));
                }
            }

            $old = Rps::find($rpsId);
            if ($old->file != null && $request->file != null) {
                if (file_exists(public_path("/media/" . $old->file))) {
                    unlink(public_path("/media/" . $old->file));
                }
            }

            $file = $request->file('file');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path("/media"), $filename);

            $rps->update([
                'deskripsi' => $request->deskripsi,
                'file' => $filename ?? null,
                'link' => $request->link,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah RPS',
                'data' => $rps
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message'   => $e->getMessage(),
                'data'      => null
            ], 500);
        }
    }

    public function destroy(Request $request, $rpsId)
    {
        $rps = Rps::findOrFail($rpsId);

        try {
            if ($rps->file != null) {
                if (file_exists(public_path("/media/" . $rps->file))) {
                    unlink(public_path("/media/" . $rps->file));
                }
            }
            $rps->delete();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus RPS',
                'data' => $rps
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
