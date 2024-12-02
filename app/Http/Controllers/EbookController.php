<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\Pertemuan;
use App\Models\SesiMataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class EbookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function store(Request $request)
    {
        if ($request->file == null && $request->link == null) {
            return response()->json([
                'success' => false,
                'message' => 'File atau link tidak boleh kosong'
            ]);
        }

        $validate = Validator::make($request->all(), [
            'file' => 'mimes:pdf,ppt,pptx,doc,docx,xls,xlsx|nullable',
            'link' => 'url|nullable',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors()->first()
            ]);
        }

        try {
            $filename = null;
            if ($request->hasFile('file')) {
                if ($request->file('file')->getClientOriginalExtension() != 'pdf') {
                    return response()->json([
                        'success' => false,
                        'message' => 'File harus berupa pdf'
                    ]);
                }

                $file = $request->file('file');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path("/media"), $filename);
            }

            $ebook = Ebook::create([
                'pertemuan_id' => $request->pertemuan_id,
                'judul' => $request->judul,
                'file' => $filename,
                'link' => $request->link,
                'keterangan' => $request->keterangan,
            ]);

            $pertemuan = Pertemuan::where('id', $request->pertemuan_id)->first();

            $sesiMataKuliah = SesiMataKuliah::find($pertemuan->sesi_mata_kuliah_id);
            $chatid = $sesiMataKuliah->chat_id;

            if ($chatid != null) {
                $telegramController = new TelegramController;
                $file = url('/media/' . $ebook->file);

                $judul = $ebook->judul == null ? '-' : $ebook->judul;
                $keterangan = $ebook->keterangan == null ? '-' : $ebook->keterangan;

                $message = "Ebook telah ditambahkan,\nJudul: $judul,\nKeterangan: $keterangan";
                if ($ebook->link == null && $ebook->file == null) {
                    $telegramController->sendMessage($message, $chatid);
                } else {
                    $button = [];

                    if ($ebook->link != null) $button[] = ['text' => 'Buka link', 'url' => $ebook->link];
                    if ($ebook->file != null) $button[] = ['text' => 'Buka file', 'url' => $file];

                    if (count($button) > 0) {
                        if (count($button) == 1) {
                            $telegramController->sendMessageWithLinkButton($message, $button[0]['text'], $button[0]['url'], $chatid);
                        } else {
                            $telegramController->sendMessageWithTwoButtons($message, $button[0]['text'], $button[0]['url'], $button[1]['text'], $button[1]['url'], $chatid);
                        }
                    } else {
                        $telegramController->sendMessage($message, $chatid);
                    }
                }
            }

            if ($request->insert == true) {
                $pertemuanKe = $pertemuan->pertemuan_ke; //ex: 10
                $sesiMataKuliahId = $pertemuan->sesi_mata_kuliah_id;
                $periodeId = $sesiMataKuliah->periode_mata_kuliah_id;

                // Ambil semua sesi kecuali yang sedang diproses
                $listAnotherSesi = SesiMataKuliah::where('periode_mata_kuliah_id', $periodeId)->where('id', '!=', $sesiMataKuliahId)->get();

                foreach ($listAnotherSesi as $sesi) {
                    // Ambil pertemuan dengan nomor yang sama pada sesi lain
                    $pertemuanAnother = Pertemuan::where('sesi_mata_kuliah_id', $sesi->id)
                        ->where('pertemuan_ke', $pertemuanKe)
                        ->where('id', '!=', $request->pertemuan_id)
                        ->first();

                    if ($pertemuanAnother) {
                        Ebook::create([
                            'pertemuan_id' => $pertemuanAnother->id,
                            'judul' => $request->judul,
                            'file' => $filename,
                            'link' => $request->link,
                            'keterangan' => $request->keterangan,
                        ]);
                    }

                    $chatid = $sesi->chat_id;
                    if ($chatid != null) {
                        $telegramController = new TelegramController;
                        $file = url('/media/' . $filename);

                        $message = "Ebook telah ditambahkan,\nJudul: $judul,\nKeterangan: $keterangan";
                        if ($ebook->link == null && $ebook->file == null) {
                            $telegramController->sendMessage($message, $chatid);
                        } else {
                            $button = [];

                            if ($ebook->link != null) $button[] = ['text' => 'Buka link', 'url' => $ebook->link];
                            if ($ebook->file != null) $button[] = ['text' => 'Buka file', 'url' => $file];

                            if (count($button) > 0) {
                                if (count($button) == 1) {
                                    $telegramController->sendMessageWithLinkButton($message, $button[0]['text'], $button[0]['url'], $chatid);
                                } else {
                                    $telegramController->sendMessageWithTwoButtons($message, $button[0]['text'], $button[0]['url'], $button[1]['text'], $button[1]['url'], $chatid);
                                }
                            } else {
                                $telegramController->sendMessage($message, $chatid);
                            }
                        }
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan ebook',
                'data' => $pertemuanAnother ?? null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    public function show($id)
    {
        $ebook = Ebook::find($id);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendapatkan data ebook',
            'data' => $ebook
        ]);
    }

    public function update(Request $request)
    {
        $ebook = Ebook::where('id', $request->id)->first();
        $validate = Validator::make($request->all(), [
            'file' => 'mimes:pdf',
            'link' => 'url',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors()->first()
            ]);
        }
        try {
            if ($request->hasFile('file')) {
                if ($request->file('file')->getClientOriginalExtension() != 'pdf') {
                    return response()->json([
                        'success' => false,
                        'message' => 'File harus berupa pdf'
                    ]);
                }

                $file = $request->file('file');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path("/media"), $filename);

                $ebook->update([
                    'judul' => $request->judul,
                    'file' => $filename,
                    'link' => $request->link,
                    'keterangan' => $request->keterangan,
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil mengubah ebook',
                    'data' => $ebook
                ]);
            } else {
                $ebook->update([
                    'deskripsi' => $request->deskripsi,
                    'link' => $request->link,
                    'keterangan' => $request->keterangan,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil mengubah ebook',
                    'data' => $ebook
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $ebook = Ebook::find($id);

            // if ($ebook->file != null) {
            //     // if file exists
            //     if (file_exists(public_path("/media/" . $ebook->file))) {
            //         // delete file
            //         unlink(public_path("/media/" . $ebook->file));
            //     }
            // }
            $ebook->delete();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus ebook',
                'data' => $ebook
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
