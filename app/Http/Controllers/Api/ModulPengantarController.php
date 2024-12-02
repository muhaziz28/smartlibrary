<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ModulPengantar;
use Illuminate\Http\Request;

class ModulPengantarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function data(Request $request, $id)
    {
        $modulPengantar = ModulPengantar::where('sesi_mata_kuliah_id', $id)->get();

        try {
            foreach ($modulPengantar as $key => $value) {
                if ($value->file != null) $value->file = url("/media/{$value->file}");
            }

            return response()->json([
                'success' => true,
                'message' => 'Data modul pengantar berhasil diambil',
                'data' => $modulPengantar
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
                'data'    => null
            ], 400);
        }

        try {
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

                $modulPengantar = ModulPengantar::create([
                    'sesi_mata_kuliah_id'   => $id,
                    'deskripsi'             => $request->deskripsi,
                    'file'                  => $filename,
                    'link'                  => $request->link,
                ]);

                $getModulPengantar = ModulPengantar::findOrFail($modulPengantar->id);

                return response()->json([
                    'success'   => true,
                    'message'   => 'Berhasil menambahkan modul pengantar',
                    'data'      => $getModulPengantar
                ], 201);
            } else {
                $modulPengantar = ModulPengantar::create([
                    'sesi_mata_kuliah_id'   => $id,
                    'deskripsi'             => $request->deskripsi,
                    'file'                  => null,
                    'link'                  => $request->link,
                ]);

                $getModulPengantar = ModulPengantar::findOrFail($modulPengantar->id);

                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil menambahkan modul pengantar',
                    'data' => $getModulPengantar
                ], 201);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function update(Request $request, $modulPengantarId)
    {
        $modulPengantar = ModulPengantar::findOrFail($modulPengantarId);
        try {
            if ($request->hasFile('file')) {
                if ($request->file('file')->getClientOriginalExtension() != 'pdf') {
                    return response()->json([
                        'success' => false,
                        'message' => 'File harus berupa pdf',
                        'data'    => null
                    ], 400);
                }
            }

            if ($modulPengantar->file != null  && $request->hasFile('file')) {
                if (file_exists(public_path("/media/" . $modulPengantar->file))) {
                    unlink(public_path("/media/" . $modulPengantar->file));
                }
            }

            $file = $request->file('file');
            if ($file != null) {
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path("/media"), $filename);
            }

            $modulPengantar->update([
                'deskripsi' => $request->deskripsi,
                'file' => $filename ?? $modulPengantar->file,
                'link' => $request->link,
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah modul pengantar',
                'data' => $modulPengantar
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data'    => null
            ], 500);
        }
    }

    public function destroy(Request $request, $modulPengantarId)
    {
        try {
            $modulPengantar = ModulPengantar::findOrFail($modulPengantarId);
            if ($modulPengantar->file != null) {
                // if file exists
                if (file_exists(public_path("/media/" . $modulPengantar->file))) {
                    // delete file
                    unlink(public_path("/media/" . $modulPengantar->file));
                }
            }
            $modulPengantar->delete();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus modul pengantar',
                'data' => $modulPengantar
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
