<?php

namespace App\Http\Controllers;

use App\Models\Modul;
use App\Models\Pertemuan;
use App\Models\SesiMataKuliah;
use Illuminate\Auth\Events\Validated;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Yajra\DataTables\Facades\DataTables;

class ModulsController extends Controller
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

        $validate = FacadesValidator::make($request->all(), [
            'file' => 'mimes:pdf',
            'link' => 'url|nullable',
            'type' => 'required',
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

            $modul = Modul::create([
                'pertemuan_id' => $request->pertemuan_id,
                'file' => $filename,
                'link' => $request->link,
                'type' => $request->type,
                'keterangan' => $request->keterangan,
            ]);

            $pertemuan = Pertemuan::where('id', $request->pertemuan_id)->first();

            $sesiMataKuliah = SesiMataKuliah::find($pertemuan->sesi_mata_kuliah_id);
            $chatid = $sesiMataKuliah->chat_id;

            if ($chatid != null) {
                $telegramController = new TelegramController;
                $file = url('/media/' . $modul->file);

                $type = $modul->type;
                $keterangan = $modul->keterangan == null ? '-' : $modul->keterangan;

                $message = "Modul pembelajaran telah ditambahkan,
Type: $type,
Keterangan: $keterangan";
                if ($modul->link == null && $modul->file == null) {
                    $telegramController->sendMessage($message, $chatid);
                } else {
                    $button = [];

                    if ($modul->link != null) $button[] = ['text' => 'Buka link', 'url' => $modul->link];
                    if ($modul->file != null) $button[] = ['text' => 'Buka file', 'url' => $file];

                    if (count($button) == 1) $telegramController->sendMessageWithLinkButton($message, $button[0]['text'], $button[0]['url'], $chatid);
                    if (count($button) == 2) $telegramController->sendMessageWithTwoButtons($message, $button[0]['text'], $button[0]['url'], $button[1]['text'], $button[1]['url'], $chatid);
                }
            }

            if ($request->insert == true) {
                $pertemuanKe = $pertemuan->pertemuan_ke;
                $sesiMataKuliahId = $pertemuan->sesi_mata_kuliah_id;
                $periodeId = $sesiMataKuliah->periode_mata_kuliah_id;

                $listAnotherSesi = SesiMataKuliah::where('periode_mata_kuliah_id', $periodeId)->where('id', '!=', $sesiMataKuliahId)->get();

                foreach ($listAnotherSesi as $sesi) {
                    $pertemuanAnother = Pertemuan::where('sesi_mata_kuliah_id', $sesi->id)
                        ->where('pertemuan_ke', $pertemuanKe)
                        ->where('id', '!=', $request->pertemuan_id)
                        ->first();

                    if ($pertemuanAnother) {
                        Modul::create([
                            'pertemuan_id' => $pertemuanAnother->id,
                            'file' => $filename,
                            'link' => $request->link,
                            'type' => $request->type,
                            'keterangan' => $request->keterangan,
                        ]);
                    }

                    $chatid = $sesi->chat_id;
                    if ($chatid != null) {
                        $telegramController = new TelegramController;
                        $file = url('/media/' . $filename);

                        $message = "Modul telah ditambahkan,\Type: $request->type,\nKeterangan: $request->keterangan";
                        if ($modul->link == null && $modul->file == null) {
                            $telegramController->sendMessage($message, $chatid);
                        } else {
                            $button = [];

                            if ($modul->link != null) $button[] = ['text' => 'Buka link', 'url' => $modul->link];
                            if ($modul->file != null) $button[] = ['text' => 'Buka file', 'url' => $file];

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
                'message' => 'Berhasil menambahkan modul',
                'data' => $modul
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
        $modul = Modul::find($id);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendapatkan data modul',
            'data' => $modul
        ]);
    }

    public function update(Request $request)
    {
        $modul = Modul::where('id', $request->id)->first();
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

                $modul->update([
                    'file' => $filename,
                    'link' => $request->link,
                    'type' => $request->type,
                    'keterangan' => $request->keterangan,
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil mengubah modul',
                    'data' => $modul
                ]);
            } else {
                $modul->update([

                    'link' => $request->link,
                    'type' => $request->type,
                    'keterangan' => $request->keterangan,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil mengubah modul',
                    'data' => $modul
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
        $modul = Modul::find($id);

        try {
            // if has file
            if ($modul->file != null) {
                // if file exists
                if (file_exists(public_path("/media/" . $modul->file))) {
                    // delete file
                    unlink(public_path("/media/" . $modul->file));
                }
            }
            $modul->delete();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus modul',
                'data' => $modul
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
