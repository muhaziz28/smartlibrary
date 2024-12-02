<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\Modul;
use App\Models\ModulPengantar;
use App\Models\Pertemuan;
use App\Models\VideoConf;
use App\Models\VideoPembelajaran;

class PertemuanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index($id)
    {
        $pertemuan = Pertemuan::with('sesiMataKuliah', 'sesiMataKuliah.periode_mata_kuliah.mata_kuliah')
            ->where('id', $id)->first();

        $ebook = Ebook::where('pertemuan_id', $id)->get();

        $modul = Modul::where('pertemuan_id', $id)->get();

        $video_conf = VideoConf::where('pertemuan_id', $id)->get();

        $video_pembelajaran = VideoPembelajaran::where('pertemuan_id', $id)->get();

        return view('pertemuan.index', compact('id', 'pertemuan', 'ebook', 'modul', 'video_conf', 'video_pembelajaran'));
    }
}
