<?php

namespace App\Http\Controllers;

use App\Models\Pertemuan;
use App\Models\SesiMataKuliah;
use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class TugasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index($id)
    {
        $pertemuan = Pertemuan::with('sesiMataKuliah', 'sesiMataKuliah.periode_mata_kuliah.mata_kuliah')->find($id);
        $perPage = 10;
        $currentPage = request('page', 1);

        $totalItems = Tugas::where('pertemuan_id', $id)->count();

        $totalPages = ceil($totalItems / $perPage);
        $tugas = Tugas::where('pertemuan_id', $id)->skip(($currentPage - 1) * $perPage)->take($perPage)->get();
        return view('tugas.index', compact('id', 'tugas', 'totalItems', 'totalPages', 'currentPage', 'perPage', 'pertemuan'));
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
            'file' => 'mimes:pdf,ppt,pptx,doc,docx,xls,xlsx',
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
                $file = $request->file('file');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path("/media"), $filename);
            }

            $tugas = Tugas::create([
                'pertemuan_id'  => $request->pertemuan_id,
                'file'          => $filename,
                'link'          => $request->link,
                'keterangan'    => $request->keterangan,
                'deadline'      => $request->deadline,
            ]);

            $pertemuan = Pertemuan::where('id', $request->pertemuan_id)->first();

            $sesiMataKuliah = SesiMataKuliah::find($pertemuan->sesi_mata_kuliah_id);
            $chatid = $sesiMataKuliah->chat_id;

            if ($chatid != null) {
                $telegramController = new TelegramController;

                $urlTugas = url('/tugas/' . $request->pertemuan_id);
                $deadline = date('d-m-Y', strtotime($tugas->deadline));
                $keterangan = $request->keterangan == null ? '-' : $request->keterangan;
                $message = "Tugas baru telah ditambahkan,
Deadline: $deadline,
Keterangan: $keterangan,
Harap dikerjakan sebelum deadline";

                $telegramController->sendMessageWithLinkButton($message, "Lihat Tugas", $urlTugas, $chatid);
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
                        Tugas::create([
                            'pertemuan_id'  => $pertemuanAnother->id,
                            'file'          => $filename,
                            'link'          => $request->link,
                            'keterangan'    => $request->keterangan,
                            'deadline'      => $request->deadline,
                        ]);
                    }

                    $chatid = $sesi->chat_id;
                    if ($chatid != null) {
                        $telegramController = new TelegramController;
                        $file = url('/media/' . $filename);

                        $message = "Tugas baru telah ditambahkan,
Deadline: $deadline,
Keterangan: $keterangan,
Harap dikerjakan sebelum deadline";
                        if ($tugas->link == null && $tugas->file == null) {
                            $telegramController->sendMessage($message, $chatid);
                        } else {
                            $button = [];

                            if ($tugas->link != null) $button[] = ['text' => 'Buka link', 'url' => $tugas->link];
                            if ($tugas->file != null) $button[] = ['text' => 'Buka file', 'url' => $file];

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
                'message' => 'Berhasil menambahkan tugas',
                'data' => $tugas
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
        $tugas = Tugas::find($id);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendapatkan data tugas',
            'data' => $tugas
        ]);
    }

    public function update(Request $request)
    {
        $tugas = Tugas::where('id', $request->id)->first();
        try {
            if ($request->hasFile('file')) {
                $validate = Validator::make($request->all(), [
                    'file' => 'mimes:pdf,ppt,pptx,doc,docx,xls,xlsx',
                ]);

                if ($validate->fails()) {
                    return response()->json([
                        'success' => false,
                        'message' => $validate->errors()->first()
                    ]);
                }
                $file = $request->file('file');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path("/media"), $filename);

                $tugas->update([
                    'file'          => $filename,
                    'link'          => $request->link,
                    'keterangan'    => $request->keterangan,
                    'deadline'      => $request->deadline,
                ]);
                return response()->json([
                    'success'   => true,
                    'message'   => 'Berhasil mengubah tugas',
                    'data'      => $tugas
                ]);
            } else {
                $tugas->update([
                    'link'          => $request->link,
                    'keterangan'    => $request->keterangan,
                    'deadline'      => $request->deadline,
                ]);

                return response()->json([
                    'success'   => true,
                    'message'   => 'Berhasil mengubah tugas',
                    'data'      => $tugas
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
            $tugas = Tugas::find($id);
            // if has file
            // if ($tugas->file != null) {
            //     // if file exists
            //     if (file_exists(public_path("/media/" . $tugas->file))) {
            //         // delete file
            //         unlink(public_path("/media/" . $tugas->file));
            //     }
            // }
            $tugas->delete();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus tugas',
                'data' => $tugas
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
