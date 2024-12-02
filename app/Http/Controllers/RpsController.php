<?php

namespace App\Http\Controllers;

use App\Models\Rps;
use App\Models\SesiMataKuliah;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RpsController extends Controller
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
                if ($file != null) {
                    $filename = time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path("/media"), $filename);
                }
            }

            $rps = Rps::create([
                'sesi_mata_kuliah_id' => $request->sesi_mata_kuliah_id,
                'deskripsi' => $request->deskripsi,
                'file' => $filename ?? null,
                'link' => $request->link,
            ]);

            $sesiMataKuliha = SesiMataKuliah::find($request->sesi_mata_kuliah_id);
            $chatid = $sesiMataKuliha->chat_id;

            if ($chatid != null) {
                $telegramController = new TelegramController;
                $file = url('/media/' . $filename);

                $message = "RPS baru telah ditambahkan,
            Deskripsi: $rps->deskripsi";
                if ($rps->link == null && $rps->file == null) {
                    $telegramController->sendMessage($message, $chatid);
                } else {
                    $button = [];

                    if ($rps->link != null) $button[] = ['text' => 'Buka link', 'url' => $rps->link];

                    if ($rps->file != null) $button[] = ['text' => 'Buka file', 'url' => $file];

                    if (count($button) == 1) $telegramController->sendMessageWithLinkButton($message, $button[0]['text'], $button[0]['url'], $chatid);
                    if (count($button) == 2) $telegramController->sendMessageWithTwoButtons($message, $button[0]['text'], $button[0]['url'], $button[1]['text'], $button[1]['url'], $chatid);
                }
            }

            if ($request->insert == true) {
                $listSesi = SesiMataKuliah::find($request->sesi_mata_kuliah_id);
                $periodeId = $listSesi->periode_mata_kuliah_id;

                $targetSesi = SesiMataKuliah::where('periode_mata_kuliah_id', $periodeId)->where('id', '!=', $request->sesi_mata_kuliah_id)->get();
                foreach ($targetSesi as $sesi) {
                    Rps::create([
                        'sesi_mata_kuliah_id' => $sesi->id,
                        'deskripsi' => $request->deskripsi,
                        'file' => $filename ?? null,
                        'link' => $request->link,
                    ]);

                    $chatid = $sesi->chat_id;
                    if ($chatid != null) {
                        $telegramController = new TelegramController;
                        $file = url('/media/' . $filename);

                        $message = "RPS baru telah ditambahkan,
                    Deskripsi: $rps->deskripsi";
                        if ($rps->link == null && $rps->file == null) {
                            $telegramController->sendMessage($message, $chatid);
                        } else {
                            $button = [];

                            if ($rps->link != null) $button[] = ['text' => 'Buka link', 'url' => $rps->link];

                            if ($rps->file != null) $button[] = ['text' => 'Buka file', 'url' => $file];

                            if (count($button) == 1) $telegramController->sendMessageWithLinkButton($message, $button[0]['text'], $button[0]['url'], $chatid);
                            if (count($button) == 2) $telegramController->sendMessageWithTwoButtons($message, $button[0]['text'], $button[0]['url'], $button[1]['text'], $button[1]['url'], $chatid);
                        }
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan rps',
                'data' => $rps
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan rps',
                'data' => $e->getMessage()
            ]);
        }
    }

    public function show($id)
    {
        $rps = Rps::find($id);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendapatkan data RPS',
            'data' => $rps
        ]);
    }

    public function update(Request $request)
    {
        $rps = Rps::where('id', $request->id)->first();
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

                $rps->update([
                    'sesi_mata_kuliah_id' => $request->sesi_mata_kuliah_id,
                    'deskripsi' => $request->deskripsi,
                    'file' => $filename,
                    'link' => $request->link,
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil mengubah RPS',
                    'data' => $rps
                ]);
            } else {
                $rps->update([
                    'sesi_mata_kuliah_id' => $request->sesi_mata_kuliah_id,
                    'deskripsi' => $request->deskripsi,
                    'link' => $request->link,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil mengubah RPS',
                    'data' => $rps
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah RPS',
                'data' => $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $rps = Rps::find($id);
            // if ($rps->file != null) {                
            //     if (file_exists(public_path("/media/" . $rps->file))) {
            //         unlink(public_path("/media/" . $rps->file));
            //     }
            // }
            $rps->delete();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus RPS',
                'data' => $rps
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus RPS',
                'data' => $e->getMessage()
            ]);
        }
    }
}
