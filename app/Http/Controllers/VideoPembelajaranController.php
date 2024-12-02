<?php

namespace App\Http\Controllers;

use App\Models\Pertemuan;
use App\Models\SesiMataKuliah;
use App\Models\VideoPembelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VideoPembelajaranController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'link' => 'required|url',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Link harus berupa url'
            ]);
        }

        try {
            $video_pembelajaran = VideoPembelajaran::create([
                'pertemuan_id' => $request->pertemuan_id,
                'link' => $request->link,
                'keterangan' => $request->keterangan,
            ]);

            $pertemuan = Pertemuan::where('id', $request->pertemuan_id)->first();

            $sesiMataKuliah = SesiMataKuliah::find($pertemuan->sesi_mata_kuliah_id);
            $chatid = $sesiMataKuliah->chat_id;

            if ($chatid != null) {
                $telegramController = new TelegramController;

                $keterangan = $request->keterangan == null ? '-' : $request->keterangan;

                $message = "Video pembelajaran telah ditambahkan,
Keterangan: $keterangan";

                $button = [];

                if ($request->link != null) $button[] = ['text' => 'Buka video', 'url' => $request->link];

                if (count($button) == 1) $telegramController->sendMessageWithLinkButton($message, $button[0]['text'], $button[0]['url'], $chatid);
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
                        VideoPembelajaran::create([
                            'pertemuan_id' => $pertemuanAnother->id,
                            'link' => $request->link,
                            'keterangan' => $request->keterangan,
                        ]);
                    }

                    $chatid = $sesi->chat_id;
                    if ($chatid != null) {
                        $telegramController = new TelegramController;


                        $message = "Video pembelajaran telah ditambahkan";
                        if ($video_pembelajaran->link == null) {
                            $telegramController->sendMessage($message, $chatid);
                        } else {
                            $button = [];

                            if ($video_pembelajaran->link != null) $button[] = ['text' => 'Buka link', 'url' => $video_pembelajaran->link];

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
                'message' => 'Berhasil menambahkan video pembelajaran'
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
        $video_pembelajaran = VideoPembelajaran::find($id);

        return response()->json([
            'success' => true,
            'data' => $video_pembelajaran
        ]);
    }

    public function update(Request $request)
    {
        try {
            $video_pembelajaran = VideoPembelajaran::find($request->id);

            $video_pembelajaran->link = $request->link;
            $video_pembelajaran->keterangan = $request->keterangan;
            $video_pembelajaran->save();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diubah.'
            ]);
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
            $video_pembelajaran = VideoPembelajaran::find($id);
            $video_pembelajaran->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
