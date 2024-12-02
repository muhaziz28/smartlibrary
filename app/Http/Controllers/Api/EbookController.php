<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EbookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function data($id)
    {
        $ebook = Ebook::where('pertemuan_id', $id)->get();

        try {
            foreach ($ebook as $key => $value) {
                if ($value->file != null) {
                    $value->file = url("/media/{$value->file}");
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Data ebook berhasil diambil',
                'data' => $ebook
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
            $file = $request->file('file');
            if ($file != null || $file != '') {
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path("/media"), $filename);
            }

            $ebook = Ebook::create([
                'pertemuan_id' => $id,
                'judul' => $request->judul,
                'file' => $filename ?? null,
                'link' => $request->link,
                'keterangan' => $request->keterangan,
            ]);

            $ebook->pertemuan_id = (int)$ebook->pertemuan_id;

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan ebook',
                'data' => $ebook
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data'    => null,
            ]);
        }
    }

    public function update(Request $request, $ebookId)
    {
        $ebook = Ebook::where('id', $ebookId)->first();
        $validate = Validator::make($request->all(), [
            'file' => 'mimes:pdf',
            'link' => 'url|nullable',
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
                if ($request->file('file')->getClientOriginalExtension() != 'pdf') {
                    return response()->json([
                        'success' => false,
                        'message' => 'File harus berupa pdf',
                        'data'    => null,
                    ], 400);
                }
            }

            $old = Ebook::find($ebookId);
            if ($old->file != null && $request->file('file') != null) {
                if (file_exists(public_path("/media/" . $old->file))) {
                    unlink(public_path("/media/" . $old->file));
                }
            }

            $file = $request->file('file');
            if ($file != null || $file != '') {
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path("/media"), $filename);
            }

            $ebook->update([
                'judul' => $request->judul,
                'file' => $filename ?? null,
                'link' => $request->link,
                'keterangan' => $request->keterangan,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah ebook',
                'data' => $ebook
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data'    => null
            ], 500);
        }
    }

    public function destroy(Request $request, $ebookId)
    {
        $ebook = Ebook::find($ebookId);

        try {
            if ($ebook->file != null && $ebook->file != '') {
                if (file_exists(public_path("/media/" . $ebook->file))) {
                    unlink(public_path("/media/" . $ebook->file));
                }
            }
            $ebook->delete();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus ebook',
                'data' => $ebook
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
