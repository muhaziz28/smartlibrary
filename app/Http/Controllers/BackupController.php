<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\Evaluasi;
use App\Models\Modul;
use App\Models\ModulPengantar;
use App\Models\Pengantar;
use App\Models\Periode;
use App\Models\PeriodeMataKuliah;
use App\Models\Rps;
use App\Models\SesiMataKuliah;
use App\Models\Tugas;
use App\Models\VideoConf;
use App\Models\VideoPembelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BackupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function matakuliah(Request $request)
    {
        $periode = Periode::where('id', $request->periode)->get();
        $sesi = SesiMataKuliah::whereHas('periode_mata_kuliah', function ($query) use ($request) {
            $query->where('periode_id', $request->periode);
        })
            ->where('kode_dosen', Auth::user()->username)->get();
        return response()->json([
            'sesi' => $sesi
        ]);
    }

    public function preview(Request $request)
    {
        $sesi = SesiMataKuliah::with('periode_mata_kuliah.periode')->find($request->sesi);
        $current = SesiMataKuliah::with('periode_mata_kuliah.periode')->find($request->current);

        return view('backup.perview', compact('sesi', 'current'));
    }

    public function store(Request $request)
    {
        try {
            $sesiId = $request->sesi;
            $currentId = $request->current;

            $sesi = SesiMataKuliah::with('periode_mata_kuliah.periode', 'pertemuan', 'pertemuan.ebook', 'pertemuan.modul', 'pertemuan.video_pembelajaran', 'pertemuan.tugas')->find($sesiId);
            $currentSesi = SesiMataKuliah::with('periode_mata_kuliah.periode', 'pertemuan', 'pertemuan.ebook', 'pertemuan.modul', 'pertemuan.video_pembelajaran', 'pertemuan.tugas')->find($currentId);

            if ($request->pengantar == true) {
                $oldPengantar = Pengantar::where('sesi_mata_kuliah_id', $sesi->id)->get();

                $currentPengantar = Pengantar::where('sesi_mata_kuliah_id', $currentSesi->id)->get();
                if ($currentPengantar->count() > 0) {
                    foreach ($currentPengantar as $pengantar) {
                        $pengantar->delete();
                    }

                    foreach ($oldPengantar as $pengantar) {
                        Pengantar::create([
                            'sesi_mata_kuliah_id' => $currentSesi->id,
                            'pengantar' => $pengantar->pengantar,
                            'file' => $pengantar->file,
                            'link' => $pengantar->link,
                            'video' => $pengantar->video
                        ]);
                    }
                } else {
                    foreach ($oldPengantar as $pengantar) {
                        Pengantar::create([
                            'sesi_mata_kuliah_id' => $currentSesi->id,
                            'pengantar' => $pengantar->pengantar,
                            'file' => $pengantar->file,
                            'link' => $pengantar->link,
                            'video' => $pengantar->video
                        ]);
                    }
                }
            }

            if ($request->rps == true) {
                $oldRps = Rps::where('sesi_mata_kuliah_id', $sesi->id)->get();
                $currentRps = Rps::where('sesi_mata_kuliah_id', $currentSesi->id)->get();

                if ($currentRps->count() > 0) {
                    foreach ($currentRps as $rps) {
                        if ($rps->file) {
                            $file = public_path('media/' . $rps->file);
                            if (file_exists($file)) unlink($file);
                        }
                        $rps->delete();
                    }

                    foreach ($oldRps as $rps) {
                        Rps::create([
                            'sesi_mata_kuliah_id' => $currentSesi->id,
                            'deskripsi' => $rps->deskripsi,
                            'file' => $rps->file,
                            'link' => $rps->link,
                        ]);
                    }
                } else {
                    foreach ($oldRps as $rps) {
                        Rps::create([
                            'sesi_mata_kuliah_id' => $currentSesi->id,
                            'deskripsi' => $rps->deskripsi,
                            'file' => $rps->file,
                            'link' => $rps->link,
                        ]);
                    }
                }
            }

            if ($request->modul_pengantar == true) {
                $oldModulPengantar = ModulPengantar::where('sesi_mata_kuliah_id', $sesi->id)->get();
                $currentModulPengantar = ModulPengantar::where('sesi_mata_kuliah_id', $currentSesi->id)->get();

                if ($currentModulPengantar->count() > 0) {
                    foreach ($currentModulPengantar as $modulPengantar) {
                        if ($modulPengantar->file) {
                            $file = public_path('media/' . $modulPengantar->file);
                            if (file_exists($file)) unlink($file);
                        }
                        $modulPengantar->delete();
                    }

                    foreach ($oldModulPengantar as $modulPengantar) {
                        ModulPengantar::create([
                            'sesi_mata_kuliah_id' => $currentSesi->id,
                            'deskripsi' => $modulPengantar->deskripsi,
                            'file' => $modulPengantar->file,
                            'link' => $modulPengantar->link,
                        ]);
                    }
                } else {
                    foreach ($oldModulPengantar as $modulPengantar) {
                        ModulPengantar::create([
                            'sesi_mata_kuliah_id' => $currentSesi->id,
                            'deskripsi' => $modulPengantar->deskripsi,
                            'file' => $modulPengantar->file,
                            'link' => $modulPengantar->link,
                        ]);
                    }
                }
            }

            if ($request->ebook == true) {
                // ambil data ebook di setiap pertemuan sesi
                $oldEbook = [];
                foreach ($sesi->pertemuan as $pertemuan) {
                    // ambil pertemuan ke dan ebooknya
                    $oldEbook[] = $pertemuan;
                }

                // ambil data ebook di setiap pertemuan current sesi
                $currentEbook = [];
                foreach ($currentSesi->pertemuan as $pertemuan) {
                    $currentEbook[] = $pertemuan;
                }

                // jika current ebook tidak kosong
                if (count($currentEbook) > 0) {
                    // hapus semua ebook di current sesi
                    foreach ($currentEbook as $pertemuan) {
                        if ($pertemuan->ebook->count() > 0) {
                            foreach ($pertemuan->ebook as $ebook) {
                                if ($ebook->file) {
                                    $file = public_path('media/' . $ebook->file);
                                    if (file_exists($file)) unlink($file);
                                }
                                $ebook->delete();
                            }
                        }
                    }

                    // tambahkan ebook dari sesi ke current sesi
                    foreach ($oldEbook as $pertemuan) {
                        foreach ($pertemuan->ebook as $ebook) {
                            Ebook::create([
                                'pertemuan_id' => $currentSesi->pertemuan->where('pertemuan_ke', $pertemuan->pertemuan_ke)->first()->id,
                                'judul' => $ebook->judul,
                                'file' => $ebook->file,
                                'link' => $ebook->link,
                                'keterangan' => $ebook->keterangan
                            ]);
                        }
                    }
                } else {
                    // tambahkan ebook dari sesi ke current sesi
                    foreach ($oldEbook as $pertemuan) {
                        foreach ($pertemuan->ebook as $ebook) {
                            Ebook::create([
                                'pertemuan_id' => $currentSesi->pertemuan->where('pertemuan_ke', $pertemuan->pertemuan_ke)->first()->id,
                                'judul' => $ebook->judul,
                                'file' => $ebook->file,
                                'link' => $ebook->link,
                                'keterangan' => $ebook->keterangan
                            ]);
                        }
                    }
                }
            }

            if ($request->modul == true) {
                $oldModul = [];
                foreach ($sesi->pertemuan as $pertemuan) {
                    $oldModul[] = $pertemuan;
                }

                $currentModul = [];
                foreach ($currentSesi->pertemuan as $pertemuan) {
                    $currentModul[] = $pertemuan;
                }

                if (count($currentModul) > 0) {
                    foreach ($currentModul as $pertemuan) {
                        if ($pertemuan->modul->count() > 0) {
                            foreach ($pertemuan->modul as $modul) {
                                if ($modul->file) {
                                    $file = public_path('media/' . $modul->file);
                                    if (file_exists($file)) unlink($file);
                                }
                                $modul->delete();
                            }
                        }
                    }

                    foreach ($oldModul as $pertemuan) {
                        foreach ($pertemuan->modul as $modul) {
                            Modul::create([
                                'pertemuan_id' => $currentSesi->pertemuan->where('pertemuan_ke', $pertemuan->pertemuan_ke)->first()->id,
                                'type' => $modul->type,
                                'file' => $modul->file,
                                'link' => $modul->link,
                                'keterangan' => $modul->keterangan
                            ]);
                        }
                    }
                } else {
                    foreach ($oldModul as $pertemuan) {
                        foreach ($pertemuan->modul as $modul) {
                            Modul::create([
                                'pertemuan_id' => $currentSesi->pertemuan->where('pertemuan_ke', $pertemuan->pertemuan_ke)->first()->id,
                                'type' => $modul->type,
                                'file' => $modul->file,
                                'link' => $modul->link,
                                'keterangan' => $modul->keterangan
                            ]);
                        }
                    }
                }
            }

            if ($request->video_conf == true) {
                $oldVideoConf = [];
                foreach ($sesi->pertemuan as $pertemuan) {
                    $oldVideoConf[] = $pertemuan;
                }

                $currentVideoConf = [];
                foreach ($currentSesi->pertemuan as $pertemuan) {
                    $currentVideoConf[] = $pertemuan;
                }

                if (count($currentVideoConf) > 0) {
                    foreach ($currentVideoConf as $pertemuan) {
                        if ($pertemuan->video_conf->count() > 0) {
                            foreach ($pertemuan->video_conf as $videoConf) {
                                $videoConf->delete();
                            }
                        }
                    }

                    foreach ($oldVideoConf as $pertemuan) {
                        foreach ($pertemuan->video_conf as $videoConf) {
                            VideoConf::create([
                                'pertemuan_id' => $currentSesi->pertemuan->where('pertemuan_ke', $pertemuan->pertemuan_ke)->first()->id,
                                'link' => $videoConf->link,
                                'keterangan' => $videoConf->keterangan
                            ]);
                        }
                    }
                } else {
                    foreach ($oldVideoConf as $pertemuan) {
                        foreach ($pertemuan->video_conf as $videoConf) {
                            VideoConf::create([
                                'pertemuan_id' => $currentSesi->pertemuan->where('pertemuan_ke', $pertemuan->pertemuan_ke)->first()->id,
                                'link' => $videoConf->link,
                                'keterangan' => $videoConf->keterangan
                            ]);
                        }
                    }
                }
            }

            if ($request->video_pembelajaran == true) {
                $oldVideoPembelajaran = [];
                foreach ($sesi->pertemuan as $pertemuan) {
                    $oldVideoPembelajaran[] = $pertemuan;
                }

                $currentVideoPembelajaran = [];
                foreach ($currentSesi->pertemuan as $pertemuan) {
                    $currentVideoPembelajaran[] = $pertemuan;
                }

                if (count($currentVideoPembelajaran) > 0) {
                    foreach ($currentVideoPembelajaran as $pertemuan) {
                        if ($pertemuan->video_pembelajaran->count() > 0) {
                            foreach ($pertemuan->video_pembelajaran as $videoPembelajaran) {
                                $videoPembelajaran->delete();
                            }
                        }
                    }

                    foreach ($oldVideoPembelajaran as $pertemuan) {
                        foreach ($pertemuan->video_pembelajaran as $videoPembelajaran) {
                            VideoPembelajaran::create([
                                'pertemuan_id' => $currentSesi->pertemuan->where('pertemuan_ke', $pertemuan->pertemuan_ke)->first()->id,
                                'link' => $videoPembelajaran->link,
                                'keterangan' => $videoPembelajaran->keterangan
                            ]);
                        }
                    }
                } else {
                    foreach ($oldVideoPembelajaran as $pertemuan) {
                        foreach ($pertemuan->video_pembelajaran as $videoPembelajaran) {
                            VideoPembelajaran::create([
                                'pertemuan_id' => $currentSesi->pertemuan->where('pertemuan_ke', $pertemuan->pertemuan_ke)->first()->id,
                                'link' => $videoPembelajaran->link,
                                'keterangan' => $videoPembelajaran->keterangan
                            ]);
                        }
                    }
                }
            }

            if ($request->tugas == true) {
                $oldTugas = [];
                foreach ($sesi->pertemuan as $pertemuan) {
                    $oldTugas[] = $pertemuan;
                }

                $currentTugas = [];
                foreach ($currentSesi->pertemuan as $pertemuan) {
                    $currentTugas[] = $pertemuan;
                }

                if (count($currentTugas) > 0) {
                    foreach ($currentTugas as $pertemuan) {
                        if ($pertemuan->tugas->count() > 0) {
                            foreach ($pertemuan->tugas as $tugas) {
                                if ($tugas->file) {
                                    $file = public_path('media/' . $tugas->file);
                                    if (file_exists($file)) unlink($file);
                                }
                                $tugas->delete();
                            }
                        }
                    }

                    foreach ($oldTugas as $pertemuan) {
                        foreach ($pertemuan->tugas as $tugas) {
                            Tugas::create([
                                'pertemuan_id' => $currentSesi->pertemuan->where('pertemuan_ke', $pertemuan->pertemuan_ke)->first()->id,
                                'file' => $tugas->file,
                                'link' => $tugas->link,
                                'keterangan' => $tugas->keterangan,
                                'deadline' => $tugas->deadline
                            ]);
                        }
                    }
                } else {
                    foreach ($oldTugas as $pertemuan) {
                        foreach ($pertemuan->tugas as $tugas) {
                            Tugas::create([
                                'pertemuan_id' => $currentSesi->pertemuan->where('pertemuan_ke', $pertemuan->pertemuan_ke)->first()->id,
                                'file' => $tugas->file,
                                'link' => $tugas->link,
                                'keterangan' => $tugas->keterangan,
                                'deadline' => $tugas->deadline
                            ]);
                        }
                    }
                }
            }

            if ($request->evaluasi == true) {
                $oldEvaluasi = [];
                foreach ($sesi->pertemuan as $pertemuan) {
                    $oldEvaluasi[] = $pertemuan;
                }

                $currentEvaluasi = [];
                foreach ($currentSesi->pertemuan as $pertemuan) {
                    $currentEvaluasi[] = $pertemuan;
                }

                if (count($currentEvaluasi) > 0) {
                    foreach ($currentEvaluasi as $pertemuan) {
                        if ($pertemuan->evaluasi->count() > 0) {
                            foreach ($pertemuan->evaluasi as $evaluasi) {
                                $evaluasi->delete();
                            }
                        }
                    }

                    foreach ($oldEvaluasi as $pertemuan) {
                        foreach ($pertemuan->evaluasi as $evaluasi) {
                            Evaluasi::create([
                                'pertemuan_id' => $currentSesi->pertemuan->where('pertemuan_ke', $pertemuan->pertemuan_ke)->first()->id,
                                'link' => $evaluasi->link,
                                'keterangan' => $evaluasi->keterangan,
                            ]);
                        }
                    }
                } else {
                    foreach ($oldEvaluasi as $pertemuan) {
                        foreach ($pertemuan->evaluasi as $evaluasi) {
                            Evaluasi::create([
                                'pertemuan_id' => $currentSesi->pertemuan->where('pertemuan_ke', $pertemuan->pertemuan_ke)->first()->id,
                                'link' => $evaluasi->link,
                                'keterangan' => $evaluasi->keterangan,
                            ]);
                        }
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengsikronkan data',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
