<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Modul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ModulController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function data($id)
    {
        $modul = Modul::where('pertemuan_id', $id)->get();

        try {
            foreach ($modul as $key => $value) {
                if ($value->file != null) {
                    $value->file = url("/media/{$value->file}");
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Data modul berhasil diambil',
                'data' => $modul
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data'    => null
            ], 500);
        }
    }

    public function store(Request $request, $pertemuanId)
    {
        if ($request->file == null && $request->link == null) {
            return response()->json([
                'success' => false,
                'message' => 'File atau link tidak boleh kosong',
                'data'  => null,
            ], 400);
        }

        $validate = Validator::make($request->all(), [
            'file' => 'mimes:pdf',
            'type' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors()->first(),
                'data'  => null,
            ], 400);
        }

        try {
            $file = $request->file('file');
            if ($request->file('file') != null || $request->file('file') != '') {
                if ($request->file('file')->getClientOriginalExtension() != 'pdf') {
                    return response()->json([
                        'success' => false,
                        'message' => 'File harus berupa pdf',
                        'data'  => null,
                    ], 400);
                }

                if ($file != null || $file != '') {
                    $filename = time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path("/media"), $filename);
                }
            }

            $modul = Modul::create([
                'pertemuan_id' => $pertemuanId,
                'file' => $filename ?? null,
                'link' => $request->link,
                'type' => $request->type,
                'keterangan' => $request->keterangan,
            ]);
            $modul->pertemuan_id = (int)$modul->pertemuan_id;


            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan modul',
                'data' => $modul
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data'    => null,
            ], 500);
        }
    }

    public function update(Request $request, $modulId)
    {
        try {
            if ($request->file == null && $request->link == null) {
                return response()->json([
                    'success' => false,
                    'message' => 'File atau link tidak boleh kosong',
                    'data'  => null,
                ], 400);
            }
            $modul = Modul::where('id', $modulId)->first();
            if ($request->file('file') != null || $request->file('file') != '') {
                if ($request->file('file')->getClientOriginalExtension() != 'pdf') {
                    return response()->json([
                        'success' => false,
                        'message' => 'File harus berupa pdf',
                        'data'    => null,
                    ], 400);
                }
            }

            $old = Modul::find($modulId);
            if ($old->file != null) {
                if ($request->file('file') != null || $request->file('file') != '') {
                    if (file_exists(public_path("/media/" . $old->file))) {
                        unlink(public_path("/media/" . $old->file));
                    }
                }
            }

            $file = $request->file('file');
            if ($file != null || $file != '') {
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path("/media"), $filename);
            }

            $modul->update([
                'file' => $filename ?? null,
                'link' => $request->link,
                'type' => $request->type,
                'keterangan' => $request->keterangan,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah modul',
                'data' => $modul
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'null'    => null,
            ], 500);
        }
    }

    public function destroy(Request $request, $modulId)
    {
        try {
            $modul = Modul::find($modulId);

            // if ($modul->file != null) {
            //     // if file exists
            //     if (file_exists(public_path("/media/" . $modul->file))) {
            //         // delete file
            //         unlink(public_path("/media/" . $modul->file));
            //     }
            // }
            $modul->delete();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus modul',
                'data' => $modul
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data'    => null,
            ]);
        }
    }
}
