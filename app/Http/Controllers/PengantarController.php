<?php

namespace App\Http\Controllers;

use App\Models\Pengantar;
use App\Models\SesiMataKuliah;
use Illuminate\Http\Request;

class PengantarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index($id)
    {
        $pengantar = Pengantar::with('sesi_mata_kuliah')->where('sesi_mata_kuliah_id', $id)->first();
        // dd($pengantar);
        return view('pengantar.index', compact('pengantar', 'id'));
    }

    public function store(Request $request)
    {
        if ($request->pengantar == null && $request->file == null && $request->link == null && $request->video == null) {
            return response()->json([
                'success' => false,
                'message' => 'Pengantar tidak boleh kosong'
            ]);
        }

        if ($request->link != null) {
            if (!filter_var($request->link, FILTER_VALIDATE_URL)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Link tidak valid'
                ]);
            }
        }
        if ($request->video != null) {
            if (strpos($request->video, 'youtube.com') == false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Video harus berupa link youtube'
                ]);
            }
        }

        try {
            $pengantar = $request->pengantar;

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

            $store = Pengantar::create([
                'sesi_mata_kuliah_id' => $request->sesi_mata_kuliah_id,
                'pengantar' => $pengantar,
                'file' => $request->hasFile('file') ? $filename : null,
                'link' => $request->link,
                'video' => $request->video,
            ]);

            $sesiMataKuliha = SesiMataKuliah::find($request->sesi_mata_kuliah_id);
            $chatid = $sesiMataKuliha->chat_id;

            if ($chatid != null) {
                $telegramController = new TelegramController;
                $url = url('/pengantar/pengantar/' . $store->id);

                $message = "Pengantar telah ditambahkan";
                if ($store->link == null && $store->video == null && $store->file == null) {
                    $telegramController->sendMessage($message, $chatid);
                } else {
                    $button = [];

                    if ($store->link != null) $button[] = ['text' => 'Buka link', 'url' => $store->link];
                    if ($store->video != null) $button[] = ['text' => 'Buka video', 'url' => $store->video];
                    if ($store->file != null) $button[] = ['text' => 'Buka file', 'url' => $file];

                    if (count($button) == 1) $telegramController->sendMessageWithLinkButton($message, $button[0]['text'], $button[0]['url'], $chatid);
                    if (count($button) == 2) $telegramController->sendMessageWithTwoButtons($message, $button[0]['text'], $button[0]['url'], $button[1]['text'], $button[1]['url'], $chatid);
                    if (count($button) == 3) $telegramController->sendMessageWithThreeButtons($message, $button[0]['text'], $button[0]['url'], $button[1]['text'], $button[1]['url'], $button[2]['text'], $button[2]['url'], $chatid);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Pengantar berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request)
    {
        if ($request->pengantar == null && $request->file == null && $request->link == null && $request->video == null) {
            return response()->json([
                'success' => false,
                'message' => 'Pengantar tidak boleh kosong'
            ]);
        }

        try {
            $pengantar = $request->pengantar;

            if ($request->hasFile('file')) {
                if ($request->file('file')->getClientOriginalExtension() != 'pdf') {
                    return response()->json([
                        'success' => false,
                        'message' => 'File harus berupa pdf'
                    ]);
                }

                $oldFile = Pengantar::where('sesi_mata_kuliah_id', $request->sesi_mata_kuliah_id)->first();
                if ($oldFile->file != null) {
                    if (file_exists(public_path("/media/$oldFile->file"))) {
                        unlink(public_path("/media/$oldFile->file"));
                    }
                }

                $file = $request->file('file');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path("/media"), $filename);
            }

            $errors = [];

            if ($request->link !== null && !filter_var($request->link, FILTER_VALIDATE_URL)) {
                $errors[] = 'Link tidak valid';
            }

            if ($request->video !== null && strpos($request->video, 'youtube.com') === false) {
                $errors[] = 'Video harus berupa link YouTube';
            }

            if (!empty($errors)) {
                return response()->json([
                    'success' => false,
                    'message' => $errors
                ]);
            }

            $store = Pengantar::where('sesi_mata_kuliah_id', $request->sesi_mata_kuliah_id)->update([
                'pengantar' => $pengantar,
                'file' => $request->hasFile('file') ? $filename : null,
                'link' => $request->link,
                'video' => $request->video,
            ]);

            $sesiMataKuliha = SesiMataKuliah::find($request->sesi_mata_kuliah_id);
            $chatid = $sesiMataKuliha->chat_id;

            if ($chatid != null) {
                $telegramController = new TelegramController;
                $url = url('/pengantar/pengantar/' . $request->sesi_mata_kuliah_id);
                $data = Pengantar::where('sesi_mata_kuliah_id', $request->sesi_mata_kuliah_id)->first();

                $link = $data->link == null ? 'Tidak ada' : $data->link;
                $video = $data->video == null ? 'Tidak ada' : $data->video;
                $file = $data->file == null ? 'Tidak ada' : url('/media/' . $data->file);

                $message = "Pengantar telah diubah";
                if ($data->link == null && $data->video == null && $data->file == null) {
                    $telegramController->sendMessage($message, $chatid);
                } else {
                    $button = [];

                    if ($data->link != null) $button[] = ['text' => 'Buka link', 'url' => $data->link];
                    if ($data->video != null) $button[] = ['text' => 'Buka video', 'url' => $data->video];
                    if ($data->file != null) $button[] = ['text' => 'Buka file', 'url' => $file];

                    if (count($button) == 1) $telegramController->sendMessageWithLinkButton($message, $button[0]['text'], $button[0]['url'], $chatid);
                    if (count($button) == 2) $telegramController->sendMessageWithTwoButtons($message, $button[0]['text'], $button[0]['url'], $button[1]['text'], $button[1]['url'], $chatid);
                    if (count($button) == 3) $telegramController->sendMessageWithThreeButtons($message, $button[0]['text'], $button[0]['url'], $button[1]['text'], $button[1]['url'], $button[2]['text'], $button[2]['url'], $chatid);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Pengantar berhasil diubah'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
