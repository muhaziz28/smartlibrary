<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TugasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function data($id)
    {
        $tugas = Tugas::where('pertemuan_id', $id)->get();

        try {
            foreach ($tugas as $key => $value) {
                if ($value->file != null) {
                    $value->file = url("/media/{$value->file}");
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Data tugas berhasil diambil',
                'data' => $tugas
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
                'data'    => null,
            ], 400);
        }

        $validate = Validator::make($request->all(), [
            'file' => 'mimes:pdf,ppt,pptx,doc,docx,xls,xlsx',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors()->first(),
                'data'    => null,
            ], 400);
        }

        try {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                if ($file != null && $file != '') {
                    $filename = time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path("/media"), $filename);
                }
            }

            $tugas = Tugas::create([
                'pertemuan_id'  => $id,
                'file'          => $filename ?? null,
                'link'          => $request->link,
                'keterangan'    => $request->keterangan,
                'deadline'      => $request->deadline,
            ]);

            $tugas->pertemuan_id = (int)$id;

            return response()->json([
                'success'   => true,
                'message'   => 'Berhasil menambahkan tugas',
                'data'      => $tugas
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data'    => null,
            ], 500);
        }
    }

    public function update(Request $request, $tugasId)
    {
        $tugas = Tugas::where('id', $tugasId)->first();
        try {
            if ($request->hasFile('file')) {
                $validate = Validator::make($request->all(), [
                    'file' => 'mimes:pdf,ppt,pptx,doc,docx,xls,xlsx',
                ]);

                if ($validate->fails()) {
                    return response()->json([
                        'success' => false,
                        'message' => $validate->errors()->first(),
                        'data'    => null,
                    ], 400);
                }
                $file = $request->file('file');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path("/media"), $filename);

                $tugas->update([
                    'file'          => $filename,
                    'link'          => $request->link,
                    'keterangan'    => $request->keterangan,
                    'deadline'      => $request->deadline,
                ]);

                return response()->json([
                    'success'   => true,
                    'message'   => 'Berhasil mengubah tugas',
                    'data'      => $tugas
                ], 201);
            } else {
                $tugas->update([
                    'file'          => null,
                    'link'          => $request->link,
                    'keterangan'    => $request->keterangan,
                    'deadline'      => $request->deadline,
                ]);

                return response()->json([
                    'success'   => true,
                    'message'   => 'Berhasil mengubah tugas',
                    'data'      => $tugas
                ], 201);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data'    => null,
            ], 500);
        }
    }

    public function destroy(Request $request, $tugasId)
    {
        $tugas = Tugas::find($tugasId);

        try {
            // if has file
            if ($tugas->file != null) {
                // if file exists
                if (file_exists(public_path("/media/" . $tugas->file))) {
                    // delete file
                    unlink(public_path("/media/" . $tugas->file));
                }
            }
            $tugas->delete();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus tugas',
                'data' => $tugas
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data'    => null,
            ], 500);
        }
    }
}
