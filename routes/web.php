<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AngketController;
use App\Http\Controllers\AssesmentController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\DaftarTugasController;
use App\Http\Controllers\DetailAbsensiController;
use App\Http\Controllers\DetailSesiMataKuliahController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\EbookController;
use App\Http\Controllers\EvaluasiController;
use App\Http\Controllers\FakultasController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\InstrumentController;
use App\Http\Controllers\JamPerkuliahanController;
use App\Http\Controllers\KonfirmasiPesertaController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\MataKuliahDiajukanController;
use App\Http\Controllers\MataKuliahMahasiswaController;
use App\Http\Controllers\MataKuliahTersediaController;
use App\Http\Controllers\ModulPengantarController;
use App\Http\Controllers\ModulsController;
use App\Http\Controllers\PengantarController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\PeriodeMataKuliahController;
use App\Http\Controllers\PertemuanController;
use App\Http\Controllers\PesertaMataKuliahController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RpsController;
use App\Http\Controllers\SesiMataKuliahController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\SyncController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\VideoConfController;
use App\Http\Controllers\VideoPembelajaranController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

Route::get('/link', function () {
    Artisan::call('storage:link');
    return "storage linked";
});

Route::get('/', function () {
    // login
    if (Auth::guard('web')->check()) {
        return redirect()->route('home');
    } else {
        return view('auth.login');
    }
});

Route::post('checkNim', [RegisterController::class, 'checkNim'])->name('checkNim');

Auth::routes(['web' => true]);
if (Auth::guard('web')->check()) {
    return redirect()->route('home');
}


Route::post('register', [RegisterController::class, 'registerm'])->name('registerm');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::get('getAllFakultas', 'getAllFakultas')->name('fakultas.getAllFakultas');
Route::get('getFakultas', [RegisterController::class, 'getFakultas'])->name('getFakultas');
Route::get('getProdi', [RegisterController::class, 'getProdi'])->name('getProdi');

Route::middleware(['auth:web'])->group(function () {
    Route::controller(RoleController::class)->prefix('role')->group(function () {
        Route::get('', 'index')->name('role.index');
        Route::get('data', 'data')->name('role.data');
        Route::post('store', 'store')->name('role.store');
        Route::get('show/{id}', 'show')->name('role.show');
        Route::put('update', 'update')->name('role.update');
        Route::delete('destroy/{id}', 'destroy')->name('role.destroy');
    });

    Route::controller(PeriodeController::class)->prefix('periode')->group(function () {
        Route::get('', 'index')->name('periode.index');
        Route::post('store', 'store')->name('periode.store');
        Route::get('show/{id}', 'show')->name('periode.show');
        Route::put('update', 'update')->name('periode.update');
        Route::delete('destroy/{id}', 'destroy')->name('periode.destroy');
    });

    Route::controller(FakultasController::class)->prefix('fakultas')->group(function () {
        Route::get('', 'index')->name('fakultas.index');
        Route::post('store', 'store')->name('fakultas.store');
        Route::get('show/{id}', 'show')->name('fakultas.show');
        Route::put('update', 'update')->name('fakultas.update');
        Route::delete('destroy/{id}', 'destroy')->name('fakultas.destroy');
        Route::get('getAllFakultas', 'getAllFakultas')->name('fakultas.getAllFakultas');
    });

    Route::controller(ProdiController::class)->prefix('prodi')->group(function () {
        Route::get('prodi/{id}', 'index')->name('prodi.index');
        Route::post('store', 'store')->name('prodi.store');
        Route::get('show/{prodiId}', 'show')->name('prodi.show');
        Route::put('update', 'update')->name('prodi.update');
        Route::delete('destroy/{prodiId}', 'destroy')->name('prodi.destroy');
        Route::get('getAllProdi', 'getAllProdi')->name('prodi.getAllProdi');
        Route::post('import', 'import')->name('prodi.import');
    });

    Route::controller(DosenController::class)->prefix('dosen')->group(function () {
        Route::get('', 'index')->name('dosen.index');
        Route::get('data', 'data')->name('dosen.data');
        Route::post('store', 'store')->name('dosen.store');
        Route::get('show/{nim}', 'show')->name('dosen.show');
        Route::put('update', 'update')->name('dosen.update');
        Route::delete('destroy/{kode_dosen}', 'destroy')->name('dosen.destroy');
        Route::post('make_user/{kode_dosen}', 'make_user')->name('dosen.make_user');
        Route::delete('truncate', 'truncate')->name('dosen.truncate');
        Route::post('import', 'import')->name('dosen.import');
    });

    Route::controller(MahasiswaController::class)->prefix('mahasiswa')->group(function () {
        Route::get('', 'index')->name('mahasiswa.index');
        Route::get('data', 'data')->name('mahasiswa.data');
        Route::post('store', 'store')->name('mahasiswa.store');
        Route::get('show/{nim}', 'show')->name('mahasiswa.show');
        Route::put('update', 'update')->name('mahasiswa.update');
        Route::delete('truncate', 'truncate')->name('mahasiswa.truncate');
        Route::delete('destroy/{nim}', 'destroy')->name('mahasiswa.destroy');

        Route::post('import', 'import')->name('mahasiswa.import');
    });

    Route::controller(MataKuliahController::class)->prefix('mata_kuliah')->group(function () {
        Route::get('', 'index')->name('mata_kuliah.index');
        Route::get('data', 'data')->name('mata_kuliah.data');
        Route::post('store', 'store')->name('mata_kuliah.store');
        Route::get('show/{id}', 'show')->name('mata_kuliah.show');
        Route::put('update', 'update')->name('mata_kuliah.update');
        Route::delete('destroy/{id}', 'destroy')->name('mata_kuliah.destroy');

        Route::get('getMataKuliahList', 'getMataKuliahList')->name('mata_kuliah.getMataKuliahList');
    });

    Route::controller(PeriodeMataKuliahController::class)->prefix('periode_mata_kuliah')->group(function () {
        Route::get('', 'index')->name('periode_mata_kuliah.index');
        Route::get('data', 'data')->name('periode_mata_kuliah.data');
        Route::post('store', 'store')->name('periode_mata_kuliah.store');
        Route::delete('destroy/{id}', 'destroy')->name('periode_mata_kuliah.destroy');
    });

    Route::controller(SesiMataKuliahController::class)->prefix('sesi_mata_kuliah')->group(function () {
        Route::get('sesi_mata_kuliah/{id}', 'index')->name('sesi_mata_kuliah.index');
        Route::get('data/{id}', 'data')->name('sesi_mata_kuliah.data');
        Route::get('getDosenByProdiSesi/{id}', 'getDosenByProdiSesi')->name('sesi_mata_kuliah.getDosenByProdiSesi');
        Route::post('store', 'store')->name('sesi_mata_kuliah.store');
        Route::get('show/{id}', 'show')->name('sesi_mata_kuliah.show');
        Route::put('update', 'update')->name('sesi_mata_kuliah.update');
        Route::delete('destroy/{id}', 'destroy')->name('sesi_mata_kuliah.destroy');
    });

    Route::controller(DetailSesiMataKuliahController::class)->prefix('detail_sesi_mata_kuliah')->group(function () {
        Route::get('detail_sesi_mata_kuliah/{id}', 'index')->name('detail_sesi_mata_kuliah.index');
        Route::get('detail_sesi_dosen', 'indexDosen')->name('detail_sesi_mata_kuliah.dosen');

        Route::post('update_chat_id', 'updateChatID')->name('detail_sesi_mata_kuliah.updateChatID');
        Route::post('radius', 'updateRadius')->name('detail_sesi_mata_kuliah.radius');
    });

    Route::controller(PengantarController::class)->prefix('pengantar')->group(function () {
        Route::get('pengantar/{id}', 'index')->name('pengantar.index');
        Route::post('store', 'store')->name('pengantar.store');
        Route::put('update', 'update')->name('pengantar.update');
    });

    Route::controller(RpsController::class)->prefix('rps')->group(function () {
        Route::post('store', 'store')->name('rps.store');
        Route::get('show/{id}', 'show')->name('rps.show');
        Route::put('update', 'update')->name('rps.update');
        Route::delete('destroy/{id}', 'destroy')->name('rps.destroy');
    });

    Route::controller(ModulPengantarController::class)->prefix('modul_pengantar')->group(function () {
        Route::get('/{id}', 'index')->name('modul_pengantar.index');
        Route::post('store', 'store')->name('modul_pengantar.store');
        Route::get('show/{id}', 'show')->name('modul_pengantar.show');
        Route::put('update', 'update')->name('modul_pengantar.update');
        Route::delete('destroy/{id}', 'destroy')->name('modul_pengantar.destroy');
    });

    Route::controller(PertemuanController::class)->prefix('pertemuan')->group(function () {
        Route::get('data/{id}', 'data')->name('pertemuan.data');
        Route::get('pertemuan/{id}', 'index')->name('pertemuan.index');
    });

    Route::controller(EbookController::class)->prefix('ebook')->group(function () {
        Route::post('store', 'store')->name('ebook.store');
        Route::get('show/{id}', 'show')->name('ebook.show');
        Route::put('update', 'update')->name('ebook.update');
        Route::delete('destroy/{id}', 'destroy')->name('ebook.destroy');
    });

    Route::controller(ModulsController::class)->prefix('modul')->group(function () {
        Route::post('store', 'store')->name('modul.store');
        Route::get('show/{id}', 'show')->name('modul.show');
        Route::put('update', 'update')->name('modul.update');
        Route::delete('destroy/{id}', 'destroy')->name('modul.destroy');
    });

    Route::controller(TugasController::class)->prefix('tugas')->group(function () {
        Route::get('/{id}', 'index')->name('tugas.index');
        Route::post('store', 'store')->name('tugas.store');
        Route::get('show/{id}', 'show')->name('tugas.show');
        Route::put('update', 'update')->name('tugas.update');
        Route::delete('destroy/{id}', 'destroy')->name('tugas.destroy');
    });

    Route::controller(MataKuliahTersediaController::class)->prefix('mata_kuliah_tersedia')->group(function () {
        Route::get('data', 'data')->name('mata_kuliah_tersedia.data');
    });

    Route::controller(MataKuliahDiajukanController::class)->prefix('mata_kuliah_diajukan')->group(function () {
        Route::get('data', 'data')->name('mata_kuliah_diajukan.data');
        Route::get('pengajuanData/{id}', 'pengajuanData')->name('mata_kuliah_diajukan.pengajuanData');
        Route::post('store', 'store')->name('mata_kuliah_diajukan.store');
        Route::get('show/{id}', 'show')->name('mata_kuliah_diajukan.show');
        Route::put('setujui', 'setujui')->name('mata_kuliah_diajukan.setujui');
        Route::put('tolak', 'tolak')->name('mata_kuliah_diajukan.tolak');
    });

    Route::controller(KonfirmasiPesertaController::class)->prefix('konfirmasi')->group(function () {
        Route::get('konfirmasi/{id}', 'index')->name('konfirmasi');
    });

    Route::controller(PesertaMataKuliahController::class)->prefix('peserta')->group(function () {
        Route::get('peserta/{id}', 'index')->name('peserta');
        Route::get('data/{id}', 'data')->name('peserta.data');
        Route::delete('destroy', 'destroy')->name('peserta.destroy');
    });

    Route::controller(MataKuliahMahasiswaController::class)->prefix('mata_kuliah_mahasiswa')->group(function () {
        Route::get('mata_kuliah_mahasiswa', 'index')->name('mata_kuliah_mahasiswa.index');
        Route::get('data/{id}', 'data')->name('mata_kuliah_mahasiswa.data');
    });

    Route::post('image-upload', [ImageUploadController::class, 'storeImage'])->name('image.upload');

    Route::controller(ProfileController::class)->prefix('profile')->group(function () {
        Route::get('', 'index')->name('profile.index');
        Route::post('uploadProfilePicture', 'uploadProfilePicture')->name('profile.uploadProfilePicture');
        Route::delete('deleteProfilePicture/{id}', 'deleteProfilePicture')->name('profile.deleteProfilePicture');
        Route::put('addEmail', 'addEmail')->name('profile.addEmail');
        Route::put('changePassword', 'changePassword')->name('profile.changePassword');
    });

    Route::controller(DaftarTugasController::class)->prefix('daftar-tugas')->group(function () {
        Route::get('', 'index')->name('daftar-tugas.index');
    });
    Route::controller(AssesmentController::class)->prefix('assesment')->group(function () {
        Route::get('/{tugasId}', 'index')->name('assesment.index');
        Route::get('detail/{tugasId}', 'detail')->name('assesment.detail');
        // tolak
        Route::put('tolak', 'tolak')->name('assesment.tolak');
        // terima
        Route::put('terima', 'terima')->name('assesment.terima');
        Route::post('store', 'store')->name('assesment.store');
    });

    Route::controller(VideoConfController::class)->prefix('video_conf')->group(function () {
        Route::post('store', 'store')->name('video_conf.store');
        Route::get('show/{id}', 'show')->name('video_conf.show');
        Route::put('update', 'update')->name('video_conf.update');
        Route::delete('destroy/{id}', 'destroy')->name('video_conf.destroy');
    });

    Route::controller(VideoPembelajaranController::class)->prefix('video_pembelajaran')->group(function () {
        Route::post('store', 'store')->name('video_pembelajaran.store');
        Route::get('show/{id}', 'show')->name('video_pembelajaran.show');
        Route::put('update', 'update')->name('video_pembelajaran.update');
        Route::delete('destroy/{id}', 'destroy')->name('video_pembelajaran.destroy');
    });

    Route::controller(EvaluasiController::class)->prefix('evaluasi')->group(function () {
        Route::get('/{pertemuan_id}', 'index')->name('evaluasi.index');
        Route::get('create-soal/{pertemuan_id}', 'createSoal')->name('evaluasi.create-soal');
        Route::post('store', 'store')->name('evaluasi.store');
        Route::put('update', 'update')->name('evaluasi.update');
        Route::get('show/{id}', 'show')->name('evaluasi.show');
        Route::delete('destroy/{id}', 'destroy')->name('evaluasi.destroy');
    });

    Route::controller(BackupController::class)->prefix('backup')->group(function () {
        Route::get('matakuliah', 'matakuliah')->name('backup.matakuliah');
        Route::get('preview', 'preview')->name('backup.preview');
        Route::post('store', 'store')->name('backup.store');
    });

    Route::controller(SyncController::class)->prefix('sync')->group(function () {
        Route::get('preview', 'preview')->name('sync.preview');
        Route::post('store', 'store')->name('sync.store');
    });

    Route::controller(AbsensiController::class)->prefix('absensi')->group(function () {
        Route::get('/{id}', 'index')->name('absensi.index');
        Route::get('detail/{pertemuanId}', 'create')->name('absensi.create');
        Route::post('addType', 'addType')->name('absensi.addType');
        Route::post('addAbsensi', 'addAbsensi')->name('absensi.addAbsensi');
    });

    Route::controller(SessionController::class)->prefix('session')->group(function () {
        Route::get('/{id}', 'index')->name('session.index');
        Route::post('store', 'store')->name('session.store');
    });

    Route::controller(InstrumentController::class)->prefix('instrument')->group(function () {
        Route::get('', 'index')->name('instrument.index');
        Route::get('data', 'data')->name('instrument.data');
        Route::post('store', 'store')->name('instrument.store');
        Route::get('show/{id}', 'show')->name('instrument.show');
        Route::put('update', 'update')->name('instrument.update');
        Route::delete('destroy/{id}', 'destroy')->name('instrument.destroy');
    });

    Route::controller(AngketController::class)->prefix('angket')->group(function () {
        Route::get('/{sesiMataKuliahId}', 'index')->name('angket.index');
        Route::post('store', 'store')->name('angket.store');
    });

    Route::controller(JamPerkuliahanController::class)->prefix('jam-perkuliahan')->group(function () {
        Route::get('', 'index')->name('jam-perkuliahan.index');
        Route::post('', 'store')->name('jam-perkuliahan.store');
        Route::put('', 'update')->name('jam-perkuliahan.update');
        Route::get('/{id}', 'show')->name('jam-perkuliahan.show');
        Route::delete('/{id}', 'destroy')->name('jam-perkuliahan.destroy');
    });

    Route::controller(DetailAbsensiController::class)->prefix('detail-absensi')->group(function () {
        Route::post('/insert', 'insertAbsensi')->name('detail-absensi.insert');
        Route::get('/{pertemuanID}', 'index')->name('detail-absensi.index');
    });
});
