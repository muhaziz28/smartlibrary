<?php

namespace App\Http\Controllers;

use App\Models\Evaluasi;
use App\Models\Pertemuan;
use App\Models\SesiMataKuliah;
use App\Models\SoalEvaluasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\FuncCall;

class EvaluasiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, $pertemuan_id)
    {
        $pertemuan = Pertemuan::with('sesiMataKuliah', 'sesiMataKuliah.periode_mata_kuliah.mata_kuliah')->find($pertemuan_id);
        $perPage = 10;
        $currentPage = request('page', 1);

        $data = Evaluasi::where('pertemuan_id', $pertemuan_id);

        $totalItems = $data->count();

        $totalPages = ceil($totalItems / $perPage);
        $evaluasi = $data->skip(($currentPage - 1) * $perPage)->take($perPage)->get();
        return view('evaluasi.index', compact('pertemuan_id', 'evaluasi', 'totalItems', 'totalPages', 'currentPage', 'perPage', 'pertemuan'));
    }

    public function createSoal(Request $request, $pertemuan_id)
    {
        return view('evaluasi.create-soal', compact('pertemuan_id'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'link' => 'required|url',
            'pertemuan_id' => 'required',
            'keterangan' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all()
            ]);
        }

        try {
            $evaluasi = new Evaluasi();
            $evaluasi->link = $request->link;
            $evaluasi->pertemuan_id = $request->pertemuan_id;
            $evaluasi->keterangan = $request->keterangan;
            $evaluasi->save();

            $pertemuan = Pertemuan::where('id', $request->pertemuan_id)->first();

            $sesiMataKuliah = SesiMataKuliah::find($pertemuan->sesi_mata_kuliah_id);
            $chatid = $sesiMataKuliah->chat_id;

            if ($chatid != null) {
                $telegramController = new TelegramController;

                $urlEvaluasi = url('/evaluasi/' . $request->pertemuan_id);
                $keterangan = $request->keterangan == null ? '-' : $request->keterangan;
                $message = "Evaluasi baru telah ditambahkan,
Keterangan: $keterangan";

                $telegramController->sendMessageWithLinkButton($message, "Lihat Tugas", $urlEvaluasi, $chatid);
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
                        Evaluasi::create([
                            'pertemuan_id' => $pertemuanAnother->id,
                            'link' => $request->link,
                            'keterangan' => $request->keterangan,
                        ]);
                    }

                    $chatid = $sesi->chat_id;
                    if ($chatid != null) {
                        $telegramController = new TelegramController;


                        $message = "Evaluasi baru telah ditambahkan,
Keterangan: $keterangan";
                        if ($evaluasi->link == null) {
                            $telegramController->sendMessage($message, $chatid);
                        } else {
                            $button = [];

                            if ($evaluasi->link != null) $button[] = ['text' => 'Buka link', 'url' => $evaluasi->link];

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
                'message' => 'Evaluasi created successfully'
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
        try {
            $evaluasi = Evaluasi::find($id);

            return response()->json([
                'success' => true,
                'data' => $evaluasi
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
        try {
            $evaluasi = Evaluasi::find($request->id);
            $evaluasi->link = $request->link;
            $evaluasi->keterangan = $request->keterangan;
            $evaluasi->save();

            return response()->json([
                'success' => true,
                'message' => 'Evaluasi updated successfully!'
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
            $evaluasi = Evaluasi::find($id);
            $evaluasi->delete();

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
