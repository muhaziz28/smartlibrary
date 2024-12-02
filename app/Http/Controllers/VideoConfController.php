<?php

namespace App\Http\Controllers;

use App\Models\Pertemuan;
use App\Models\SesiMataKuliah;
use App\Models\VideoConf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VideoConfController extends Controller
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
            $video_conf = VideoConf::create([
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

                $message = "Video Conference baru telah ditambahkan pada pertemuan ke-$pertemuan->pertemuan_ke,
Keterangan: $keterangan";

                $button = [];

                if ($request->link != null) $button[] = ['text' => 'Bergabung', 'url' => $request->link];

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
                        VideoConf::create([
                            'pertemuan_id' => $pertemuanAnother->id,
                            'link' => $request->link,
                            'keterangan' => $request->keterangan,
                        ]);
                    }

                    $chatid = $sesi->chat_id;
                    if ($chatid != null) {
                        $telegramController = new TelegramController;


                        $message = "Video conf telah ditambahkan";
                        if ($video_conf->link == null) {
                            $telegramController->sendMessage($message, $chatid);
                        } else {
                            $button = [];

                            if ($video_conf->link != null) $button[] = ['text' => 'Buka link', 'url' => $video_conf->link];

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
                'message' => 'Berhasil menambahkan video conference'
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
        $video_conf = VideoConf::find($id);

        return response()->json([
            'success' => true,
            'data' => $video_conf
        ]);
    }

    public function update(Request $request)
    {
        try {
            $video_conf = VideoConf::find($request->id);

            $video_conf->link = $request->link;
            $video_conf->keterangan = $request->keterangan;
            $video_conf->save();

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
            $video_conf = VideoConf::find($id);
            $video_conf->delete();
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
