<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengantar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PengantarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function data($id)
    {
        try {
            $pengantar = Pengantar::where('sesi_mata_kuliah_id', $id)->first();

            if (!$pengantar) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengantar tidak ditemukan',
                    'data'    => null
                ], 404);
            }

            if ($pengantar->file != null) $pengantar->file = url("/media/{$pengantar->file}");

            return response()->json([
                'success' => true,
                'message' => 'Data pengantar berhasil diambil',
                'data' => $pengantar
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
        if ($request->pengantar == null && $request->file == null && $request->link == null && $request->video == null) {
            return response()->json([
                'success' => false,
                'message' => 'Pengantar tidak boleh kosong',
                'data'    => null
            ], 400);
        }

        $checkPengantar = Pengantar::where('sesi_mata_kuliah_id', $id)->first();
        if ($checkPengantar != null) {
            return response()->json([
                'success' => false,
                'message' => 'Pengantar sudah ada',
                'data'    => null
            ], 400);
        }

        try {
            $pengantar = $request->pengantar;

            if ($request->hasFile('file')) {
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
            }

            $data = Pengantar::create([
                'sesi_mata_kuliah_id'   => $id,
                'pengantar'             => $pengantar,
                'file'                  => $request->hasFile('file') ? $filename : null,
                'link'                  => $request->link,
                'video'                 => $request->video,
            ]);

            $pengantar = Pengantar::where('sesi_mata_kuliah_id', $id)->first();

            return response()->json([
                'success' => true,
                'message' => 'Pengantar berhasil ditambahkan',
                'data'    => $pengantar
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data'    => null
            ], 500);
        }
    }

    public function update(Request $request, $pengantarId)
    {
        $validate = Validator::make($request->all(), [
            'pengantar' => 'required',
            'file'      => 'nullable|mimes:pdf',
            'link'      => 'nullable',
            'video'     => 'nullable'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors()->first(),
                'data'    => null
            ], 400);
        }

        try {
            $pengantar = Pengantar::findOrFail($pengantarId);
            if ($pengantar == null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengantar tidak ditemukan',
                    'data'    => null
                ], 404);
            }

            $filename = null;

            if ($request->hasFile('file')) {
                $fileLama = $pengantar->file;

                if ($fileLama) {
                    $pathFileLama = public_path("/media/{$fileLama}");
                    if (file_exists($pathFileLama)) {
                        unlink($pathFileLama);
                    }
                }

                $file = $request->file('file');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path("/media"), $filename);

                $pengantar->file = $filename;
            }

            // update pengantar
            $pengantar->pengantar = $request->pengantar;
            $pengantar->link = $request->link;
            $pengantar->video = $request->video;
            $pengantar->file = $filename ? $filename : $pengantar->file;
            $pengantar->save();

            $pengantar->sesi_mata_kuliah_id = (int) $pengantar->sesi_mata_kuliah_id;

            return response()->json([
                'success' => true,
                'message' => 'Pengantar berhasil diubah',
                'data'    => $pengantar
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data'    => null
            ], 500);
        }
    }

    public function destroy(Request $request, $pengantarId)
    {
        $pengantar = Pengantar::findOrFail($pengantarId);
        try {
            $file = $pengantar->file;
            if ($file) {
                $pathFile = public_path("/media/{$file}");
                if (file_exists($pathFile)) unlink($pathFile);
            }

            $pengantar->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pengantar berhasil dihapus',
                'data'    => null
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
