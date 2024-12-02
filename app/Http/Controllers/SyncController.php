<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\Evaluasi;
use App\Models\Modul;
use App\Models\Pengantar;
use App\Models\Pertemuan;
use App\Models\Rps;
use App\Models\SesiMataKuliah;
use App\Models\Tugas;
use App\Models\VideoConf;
use App\Models\VideoPembelajaran;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseIsRedirected;

class SyncController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function preview(Request $request)
    {
        try {
            $sesi = SesiMataKuliah::with('pertemuan', 'pertemuan.tugas', 'pertemuan.ebook', 'pertemuan.modul', 'pertemuan.video_conf', 'pertemuan.video_pembelajaran', 'pertemuan.evaluasi', 'pengantar', 'rps', 'modul_pengantar')->find($request->sesi);
            $current = SesiMataKuliah::with('pertemuan', 'pengantar')->find($request->current);

            // return response()->json([
            //     'sesi' => $sesi,
            //     'current' => $current
            // ]);
            return view('sync.preview', compact('sesi', 'current'));
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $sesi = SesiMataKuliah::where('id', $request->sesi)->first(); // asal

            $current = SesiMataKuliah::where('kode_sesi', $request->current)->first(); // target

            $telegramController = new TelegramController;
            $chatid = $sesi->chat_id;

            // jika ada pengantar di sesi yang akan di sync
            if ($request->pengantar) {
                $pengantar = Pengantar::where('sesi_mata_kuliah_id', $sesi->id)->first();

                // update pengantar sesi yang akan di sync
                $sesiPengantar = Pengantar::where('sesi_mata_kuliah_id', $current->id)->first();
                if ($sesiPengantar) {
                    $sesiPengantar->update([
                        'pengantar' => $pengantar->pengantar,
                        'file' => $pengantar->file,
                        'link' => $pengantar->link,
                        'video' => $pengantar->video
                    ]);
                } else {
                    Pengantar::create([
                        'sesi_mata_kuliah_id' => $sesi->id,
                        'pengantar' => $pengantar->pengantar,
                        'file' => $pengantar->file,
                        'link' => $pengantar->link,
                        'video' => $pengantar->video
                    ]);
                }

                if ($chatid != null) {
                    $message = "Pengantar berhasil di sync";
                    if ($sesiPengantar->link == null && $sesiPengantar->file == null) {
                        $telegramController->sendMessage($message, $chatid);
                    } else {
                        $button = [];

                        if ($sesiPengantar->link != null) $button[] = ['text' => 'Buka link', 'url' => $sesiPengantar->link];

                        $file = url('/media/' . $sesiPengantar->file);

                        if ($sesiPengantar->file != null) $button[] = ['text' => 'Buka file', 'url' => $file];

                        if (count($button) == 1) $telegramController->sendMessageWithLinkButton($message, $button[0]['text'], $button[0]['url'], $chatid);
                        if (count($button) == 2) $telegramController->sendMessageWithTwoButtons($message, $button[0]['text'], $button[0]['url'], $button[1]['text'], $button[1]['url'], $chatid);
                    }
                }
            }

            if ($request->rps) {
                $rps = Rps::where('sesi_mata_kuliah_id', $sesi->id)->get();

                $storeRps = [];
                // store rps
                foreach ($rps as $item) {
                    $storeRps[] = Rps::create([
                        'sesi_mata_kuliah_id' => $current->id,
                        'deskripsi' => $item->deskripsi,
                        'link' => $item->link,
                        'file' => $item->file
                    ]);
                }

                if ($chatid != null) {
                    $message = "RPS berhasil di sync";

                    // loop for send message
                    foreach ($storeRps as $item) {
                        if ($item['link'] == null && $item['file'] == null) {
                            $telegramController->sendMessage($message, $chatid);
                        } else {
                            $button = [];

                            if ($item['link'] != null) $button[] = ['text' => 'Buka link', 'url' => $item['link']];

                            $file = url('/media/' . $item['file']);

                            if ($item['file'] != null) $button[] = ['text' => 'Buka file', 'url' => $file];

                            if (count($button) == 1) $telegramController->sendMessageWithLinkButton($message, $button[0]['text'], $button[0]['url'], $chatid);
                            if (count($button) == 2) $telegramController->sendMessageWithTwoButtons($message, $button[0]['text'], $button[0]['url'], $button[1]['text'], $button[1]['url'], $chatid);
                        }
                    }
                }
            }

            if ($request->pertemuan) {
                $listPertemuanSesi = []; // listPertemuan yang akan di sync
                foreach ($request->pertemuan as $item) {
                    $listPertemuanSesi[] = Pertemuan::with('tugas', 'ebook', 'modul', 'video_conf', 'video_pembelajaran', 'evaluasi')->where('id', $item)->first();
                }

                $pertemuanKe = [];
                foreach ($listPertemuanSesi as $item) {
                    $pertemuanKe[] = $item->pertemuan_ke;
                }

                $listPertemuanCurrent = Pertemuan::with('tugas', 'ebook', 'modul', 'video_conf', 'video_pembelajaran', 'evaluasi')->where('sesi_mata_kuliah_id', $current->id)->whereIn('pertemuan_ke', $pertemuanKe)->get();

                // input tugas, ebook, modul, video_conf, video_pembelajaran, evaluasi ke pertemuan yang akan di sync
                foreach ($listPertemuanSesi as $item) {
                    if ($item->tugas->count() > 0) {
                        $listTugas = [];
                        foreach ($item->tugas as $tugas) {
                            $listTugas[] = Tugas::create([
                                'pertemuan_id' => $listPertemuanCurrent->where('pertemuan_ke', $item->pertemuan_ke)->first()->id,
                                'deskripsi' => $tugas->deskripsi,
                                'link' => $tugas->link,
                                'file' => $tugas->file,
                                'deadline' => $tugas->deadline
                            ]);

                            if ($chatid != null) {
                                $message = "Tugas pertemuan ke " . $item->pertemuan_ke . " berhasil di sync";

                                if ($tugas->link == null && $tugas->file == null) {
                                    $telegramController->sendMessage($message, $chatid);
                                } else {
                                    $button = [];

                                    if ($tugas->link != null) $button[] = ['text' => 'Buka link', 'url' => $tugas->link];

                                    $file = url('/media/' . $tugas->file);

                                    if ($tugas->file != null) $button[] = ['text' => 'Buka file', 'url' => $file];

                                    if (count($button) == 1) $telegramController->sendMessageWithLinkButton($message, $button[0]['text'], $button[0]['url'], $chatid);
                                    if (count($button) == 2) $telegramController->sendMessageWithTwoButtons($message, $button[0]['text'], $button[0]['url'], $button[1]['text'], $button[1]['url'], $chatid);
                                }
                            }
                        }
                        $tugasMessage = "Total sebanyak " . count($listTugas) . " tugas berhasil di sync";
                        $telegramController->sendMessage($tugasMessage, $chatid);
                    }

                    if ($item->ebook->count() > 0) {
                        $listEbook = [];
                        foreach ($item->ebook as $ebook) {
                            $listEbook[] = Ebook::create([
                                'pertemuan_id' => $listPertemuanCurrent->where('pertemuan_ke', $item->pertemuan_ke)->first()->id,
                                'judul' => $ebook->judul,
                                'file' => $ebook->file,
                                'link' => $ebook->link,
                                'keterangan' => $ebook->keterangan
                            ]);

                            if ($chatid != null) {
                                $message = "Ebook pertemuan ke " . $item->pertemuan_ke . " berhasil di sync";

                                if ($ebook->link == null && $ebook->file == null) {
                                    $telegramController->sendMessage($message, $chatid);
                                } else {
                                    $button = [];

                                    if ($ebook->link != null) $button[] = ['text' => 'Buka link', 'url' => $ebook->link];

                                    $file = url('/media/' . $ebook->file);

                                    if ($ebook->file != null) $button[] = ['text' => 'Buka file', 'url' => $file];

                                    if (count($button) == 1) $telegramController->sendMessageWithLinkButton($message, $button[0]['text'], $button[0]['url'], $chatid);
                                    if (count($button) == 2) $telegramController->sendMessageWithTwoButtons($message, $button[0]['text'], $button[0]['url'], $button[1]['text'], $button[1]['url'], $chatid);
                                }
                            }
                        }
                        $ebookMessage = "Total sebanyak " . count($listEbook) . " ebook berhasil di sync";
                        $telegramController->sendMessage($ebookMessage, $chatid);
                    }

                    if ($item->modul->count() > 0) {
                        $listModul = [];
                        foreach ($item->modul as $modul) {
                            $listModul[] = Modul::create([
                                'pertemuan_id' => $listPertemuanCurrent->where('pertemuan_ke', $item->pertemuan_ke)->first()->id,
                                'file' => $modul->file,
                                'link' => $modul->link,
                                'type' => $modul->type,
                                'keterangan' => $modul->keterangan
                            ]);

                            if ($chatid != null) {
                                $message = "Modul pertemuan ke " . $item->pertemuan_ke . " berhasil di sync";

                                if ($modul->link == null && $modul->file == null) {
                                    $telegramController->sendMessage($message, $chatid);
                                } else {
                                    $button = [];

                                    if ($modul->link != null) $button[] = ['text' => 'Buka link', 'url' => $modul->link];

                                    $file = url('/media/' . $modul->file);

                                    if ($modul->file != null) $button[] = ['text' => 'Buka file', 'url' => $file];

                                    if (count($button) == 1) $telegramController->sendMessageWithLinkButton($message, $button[0]['text'], $button[0]['url'], $chatid);
                                    if (count($button) == 2) $telegramController->sendMessageWithTwoButtons($message, $button[0]['text'], $button[0]['url'], $button[1]['text'], $button[1]['url'], $chatid);
                                }
                            }
                        }
                        $modulMessage = "Total sebanyak " . count($listModul) . " modul berhasil di sync";
                        $telegramController->sendMessage($modulMessage, $chatid);
                    }

                    if ($item->video_conf->count() > 0) {
                        $listVideoConf = [];
                        foreach ($item->video_conf as $video_conf) {
                            $listVideoConf[] = VideoConf::create([
                                'pertemuan_id' => $listPertemuanCurrent->where('pertemuan_ke', $item->pertemuan_ke)->first()->id,
                                'link' => $video_conf->link,
                                'keterangan' => $video_conf->keterangan
                            ]);

                            if ($chatid != null) {
                                $message = "Video conf pertemuan ke " . $item->pertemuan_ke . " berhasil di sync";

                                if ($video_conf->link == null && $video_conf->file == null) {
                                    $telegramController->sendMessage($message, $chatid);
                                } else {
                                    $button = [];

                                    if ($video_conf->link != null) $button[] = ['text' => 'Buka link', 'url' => $video_conf->link];

                                    $file = url('/media/' . $video_conf->file);

                                    if ($video_conf->file != null) $button[] = ['text' => 'Buka file', 'url' => $file];

                                    if (count($button) == 1) $telegramController->sendMessageWithLinkButton($message, $button[0]['text'], $button[0]['url'], $chatid);
                                    if (count($button) == 2) $telegramController->sendMessageWithTwoButtons($message, $button[0]['text'], $button[0]['url'], $button[1]['text'], $button[1]['url'], $chatid);
                                }
                            }
                        }
                        $video_confMessage = "Total sebanyak " . count($listVideoConf) . " video conf berhasil di sync";
                        $telegramController->sendMessage($video_confMessage, $chatid);
                    }

                    if ($item->video_pembelajaran->count() > 0) {
                        $listVideoPembelajaran = [];
                        foreach ($item->video_pembelajaran as $video_pembelajaran) {
                            $listVideoPembelajaran[] = VideoPembelajaran::create([
                                'pertemuan_id' => $listPertemuanCurrent->where('pertemuan_ke', $item->pertemuan_ke)->first()->id,
                                'link' => $video_pembelajaran->link,
                                'keterangan' => $video_pembelajaran->keterangan
                            ]);

                            if ($chatid != null) {
                                $message = "Video pembelajaran pertemuan ke " . $item->pertemuan_ke . " berhasil di sync";

                                if ($video_pembelajaran->link == null && $video_pembelajaran->file == null) {
                                    $telegramController->sendMessage($message, $chatid);
                                } else {
                                    $button = [];

                                    if ($video_pembelajaran->link != null) $button[] = ['text' => 'Buka link', 'url' => $video_pembelajaran->link];

                                    $file = url('/media/' . $video_pembelajaran->file);

                                    if ($video_pembelajaran->file != null) $button[] = ['text' => 'Buka file', 'url' => $file];

                                    if (count($button) == 1) $telegramController->sendMessageWithLinkButton($message, $button[0]['text'], $button[0]['url'], $chatid);
                                    if (count($button) == 2) $telegramController->sendMessageWithTwoButtons($message, $button[0]['text'], $button[0]['url'], $button[1]['text'], $button[1]['url'], $chatid);
                                }
                            }
                        }
                        $video_pembelajaranMessage = "Total sebanyak " . count($listVideoPembelajaran) . " video pembelajaran berhasil di sync";
                        $telegramController->sendMessage($video_pembelajaranMessage, $chatid);
                    }

                    if ($item->evaluasi->count() > 0) {
                        $listEvaluasi = [];
                        foreach ($item->evaluasi as $evaluasi) {
                            $listEvaluasi[] = Evaluasi::create([
                                'pertemuan_id' => $listPertemuanCurrent->where('pertemuan_ke', $item->pertemuan_ke)->first()->id,
                                'link' => $evaluasi->link,
                                'keterangan' => $evaluasi->keterangan
                            ]);

                            if ($chatid != null) {
                                $message = "Evaluasi pertemuan ke " . $item->pertemuan_ke . " berhasil di sync";

                                if ($evaluasi->link == null && $evaluasi->file == null) {
                                    $telegramController->sendMessage($message, $chatid);
                                } else {
                                    $button = [];

                                    if ($evaluasi->link != null) $button[] = ['text' => 'Buka link', 'url' => $evaluasi->link];

                                    $file = url('/media/' . $evaluasi->file);

                                    if ($evaluasi->file != null) $button[] = ['text' => 'Buka file', 'url' => $file];

                                    if (count($button) == 1) $telegramController->sendMessageWithLinkButton($message, $button[0]['text'], $button[0]['url'], $chatid);
                                    if (count($button) == 2) $telegramController->sendMessageWithTwoButtons($message, $button[0]['text'], $button[0]['url'], $button[1]['text'], $button[1]['url'], $chatid);
                                }
                            }
                        }
                        $evaluasiMessager = "Total sebanyak " . count($listEvaluasi) . " evaluasi berhasil di sync";
                        $telegramController->sendMessage($evaluasiMessager, $chatid);
                    }
                }
            }
            return response()->json([
                'success' => true,
                'message' => 'Berhasil sync',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
