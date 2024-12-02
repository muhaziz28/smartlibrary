<?php

namespace App\Http\Controllers;

use App\Models\ModulPengantar;
use App\Models\SesiMataKuliah;
use Illuminate\Http\Request;

class ModulPengantarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index($id)
    {
        $perPage = 10;
        $currentPage = request('page', 1);

        $totalItems = ModulPengantar::count();

        $totalPages = ceil($totalItems / $perPage);
        $modulPengantar = ModulPengantar::where('sesi_mata_kuliah_id', $id)->skip(($currentPage - 1) * $perPage)->take($perPage)->get();
        return view('modul-pengantar.index', compact('id', 'modulPengantar', 'totalItems', 'totalPages', 'currentPage', 'perPage'));
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
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path("/media"), $filename);
            }

            $modulPengantar = ModulPengantar::create([
                'sesi_mata_kuliah_id' => $request->sesi_mata_kuliah_id,
                'deskripsi' => $request->deskripsi,
                'file' => $filename,
                'link' => $request->link,
            ]);

            $sesiMataKuliah = SesiMataKuliah::find($request->sesi_mata_kuliah_id);
            $chatid = $sesiMataKuliah->chat_id;

            if ($chatid != null) {
                $telegramController = new TelegramController;
                $file = url('/media/' . $filename);
                $deskripsi = $modulPengantar->deskripsi == null ? '' : $modulPengantar->deskripsi;

                $message = "Modul pengantar baru telah ditambahkan,
Deskripsi: $deskripsi";
                if ($modulPengantar->link == null && $modulPengantar->file == null) {
                    $telegramController->sendMessage($message, $chatid);
                } else {
                    $button = [];

                    if ($modulPengantar->link != null) $button[] = ['text' => 'Buka link', 'url' => $modulPengantar->link];

                    if ($modulPengantar->file != null) $button[] = ['text' => 'Buka file', 'url' => $file];

                    if (count($button) == 1) $telegramController->sendMessageWithLinkButton($message, $button[0]['text'], $button[0]['url'], $chatid);
                    if (count($button) == 2) $telegramController->sendMessageWithTwoButtons($message, $button[0]['text'], $button[0]['url'], $button[1]['text'], $button[1]['url'], $chatid);
                }
            }

            if ($request->insert == true) {
                $listSesi = SesiMataKuliah::find($request->sesi_mata_kuliah_id);
                $periodeId = $listSesi->periode_mata_kuliah_id;

                $targetSesi = SesiMataKuliah::where('periode_mata_kuliah_id', $periodeId)->where('id', '!=', $request->sesi_mata_kuliah_id)->get();
                foreach ($targetSesi as $sesi) {
                    ModulPengantar::create([
                        'sesi_mata_kuliah_id' => $sesi->id,
                        'deskripsi' => $request->deskripsi,
                        'file' => $filename ?? null,
                        'link' => $request->link,
                    ]);
                }

                $sesiMataKuliah = SesiMataKuliah::where('periode_mata_kuliah_id', $periodeId)->where('id', '!=', $request->sesi_mata_kuliah_id)->get();
                foreach ($sesiMataKuliah as $sesi) {
                    $chatid = $sesi->chat_id;
                    if ($chatid != null) {
                        $telegramController = new TelegramController;
                        $file = url('/media/' . $filename);

                        $message = "Modul pengantar baru telah ditambahkan,
                    Deskripsi: $modulPengantar->deskripsi";
                        if ($modulPengantar->link == null && $modulPengantar->file == null) {
                            $telegramController->sendMessage($message, $chatid);
                        } else {
                            $button = [];

                            if ($modulPengantar->link != null) $button[] = ['text' => 'Buka link', 'url' => $modulPengantar->link];

                            if ($modulPengantar->file != null) $button[] = ['text' => 'Buka file', 'url' => $file];

                            if (count($button) == 1) $telegramController->sendMessageWithLinkButton($message, $button[0]['text'], $button[0]['url'], $chatid);
                            if (count($button) == 2) $telegramController->sendMessageWithTwoButtons($message, $button[0]['text'], $button[0]['url'], $button[1]['text'], $button[1]['url'], $chatid);
                        }
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan modul pengantar',
                'data' => $modulPengantar
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan modul pengantar',
                'data' => $e->getMessage()
            ]);
        }
    }

    public function show($id)
    {
        $modulPengantar = ModulPengantar::find($id);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendapatkan data modul pengantar',
            'data' => $modulPengantar
        ]);
    }

    public function update(Request $request)
    {
        $modulPengantar = ModulPengantar::where('id', $request->id)->first();
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

                $modulPengantar->update([
                    'sesi_mata_kuliah_id' => $request->sesi_mata_kuliah_id,
                    'deskripsi' => $request->deskripsi,
                    'file' => $filename,
                    'link' => $request->link,
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil mengubah modul pengantar',
                    'data' => $modulPengantar
                ]);
            } else {
                $modulPengantar->update([
                    'sesi_mata_kuliah_id' => $request->sesi_mata_kuliah_id,
                    'deskripsi' => $request->deskripsi,
                    'link' => $request->link,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil mengubah modul pengantar',
                    'data' => $modulPengantar
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah modul pengantar',
                'data' => $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $modulPengantar = ModulPengantar::find($id);

            // if ($modulPengantar->file != null) {
            //     // if file exists
            //     if (file_exists(public_path("/media/" . $modulPengantar->file))) {
            //         // delete file
            //         unlink(public_path("/media/" . $modulPengantar->file));
            //     }
            // }
            $modulPengantar->delete();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus modul pengantar',
                'data' => $modulPengantar
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus modul pengantar',
                'data' => $e->getMessage()
            ]);
        }
    }
}
