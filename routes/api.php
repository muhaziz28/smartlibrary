<?php

use App\Http\Controllers\Api\AbsenController;
use App\Http\Controllers\Api\AssesmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DetailSesiMataKuliahController;
use App\Http\Controllers\Api\DosenController;
use App\Http\Controllers\Api\EbookController;
use App\Http\Controllers\Api\EvaluasiController;
use App\Http\Controllers\Api\FakultasController;
use App\Http\Controllers\Api\MahasiswaController;
use App\Http\Controllers\Api\MataKuliahController;
use App\Http\Controllers\Api\MataKuliahDiajukanController;
use App\Http\Controllers\Api\MataKuliahMahasiswaController;
use App\Http\Controllers\Api\MataKuliahTersediaController;
use App\Http\Controllers\Api\MataKulliahDosenController;
use App\Http\Controllers\Api\ModulController;
use App\Http\Controllers\Api\ModulPengantarController;
use App\Http\Controllers\Api\PengantarController;
use App\Http\Controllers\Api\PeriodeController;
use App\Http\Controllers\Api\PeriodeMataKuliahController;
use App\Http\Controllers\Api\PertemuanController;
use App\Http\Controllers\Api\ProdiController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RpsController;
use App\Http\Controllers\Api\SesiMataKuliahController;
use App\Http\Controllers\Api\TugasController;
use App\Http\Controllers\Api\VideoConfController;
use App\Http\Controllers\Api\VideoPembelajaranController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sail\Console\PublishCommand;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('jwt.verify')->get('test', function (Request $request) {
//     return 'ok';
// });

Route::post('login', [AuthController::class, 'login']);

// step 1
Route::post('check-nim', [AuthController::class, 'checkNim']);
// step 2
Route::get('get-fakultas', [AuthController::class, 'getFakultas']);
Route::get('get-prodi', [AuthController::class, 'getProdi']);
// step 3
Route::post('register', [AuthController::class, 'register']);

Route::post('forgot-password', [AuthController::class, 'forgotPassword']);

Route::middleware('jwt.verify')->group(function () {
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('logout', [AuthController::class, 'logout']);

    Route::controller(FakultasController::class)->prefix('fakultas')->group(function () {
        Route::get('', 'data');
        Route::post('store', 'store');
        Route::put('update/{id}', 'update');
        Route::delete('destroy/{id}', 'destroy');
    });

    Route::controller(ProdiController::class)->prefix('prodi')->group(function () {
        Route::get('data/{id}', 'data');
        Route::post('store/{id}', 'store');
        Route::put('update/{id}', 'update');
        Route::delete('destroy/{id}', 'destroy');
    });

    Route::controller(MahasiswaController::class)->prefix('mahasiswa')->group(function () {
        Route::get('data', 'data');
        Route::post('store', 'store');
        Route::put('update/{nim}', 'update');
        Route::delete('destroy/{nim}', 'destroy');
    });

    Route::controller(DosenController::class)->prefix('dosen')->group(function () {
        Route::get('data', 'data');
        Route::post('store', 'store');
        Route::post('makeUser', 'makeUser');
        Route::put('update/{kodeDosen}', 'update');
        Route::delete('destroy/{kodeDosen}', 'destroy');
    });

    Route::controller(PeriodeController::class)->prefix('periode')->group(function () {
        Route::get('data', 'data');
        Route::post('store', 'store');
        Route::put('update/{id}', 'update');
        Route::delete('destroy/{id}', 'destroy');
    });

    Route::controller(MataKuliahController::class)->prefix('mata-kuliah')->group(function () {
        Route::get('data', 'data');
        Route::post('store', 'store');
        Route::put('update/{id}', 'update');
        Route::delete('destroy/{id}', 'destroy');
    });

    Route::controller(PeriodeMataKuliahController::class)->prefix('periode-mata-kuliah')->group(function () {
        Route::get('data', 'data');
        Route::get('getMatakuliahPeriode', 'getMatakuliahPeriode');
        Route::post('store', 'store');
        Route::delete('destroy/{id}', 'destroy');
    });

    Route::controller(SesiMataKuliahController::class)->prefix('sesi-mata-kuliah')->group(function () {
        Route::get('data/{id}', 'data');
        Route::get('getDosenByProdiSesi/{id}', 'getDosenByProdiSesi');
        Route::post('store/{id}', 'store');
        Route::put('update/{sesiId}', 'update');
        Route::delete('destroy/{id}', 'destroy');
    });

    Route::controller(DetailSesiMataKuliahController::class)->prefix('detail-sesi-mata-kuliah')->group(function () {
        Route::get('peserta/{id}', 'peserta');
        Route::get('konfirmasi-peserta/{id}', 'konfirmasiPeserta');
        Route::get('index/{id}', 'index');
        Route::get('indexDosen/{id}', 'indexDosen');
        Route::get('pertemuan/{id}', 'pertemuan');
        Route::get('pertemuan/active/{id}', 'pertemuanHariIni');
    });

    Route::controller(PengantarController::class)->prefix('pengantar')->group(function () {
        Route::get('data/{id}', 'data');
        Route::post('store/{id}', 'store');
        Route::post('update/{pengantarId}', 'update');
        Route::delete('destroy/{pengantarId}', 'destroy');
    });

    Route::controller(RpsController::class)->prefix('rps')->group(function () {
        Route::get('data/{id}', 'data');
        Route::post('store/{id}', 'store');
        Route::post('update/{rpsId}', 'update');
        Route::delete('destroy/{rpsId}', 'destroy');
    });

    Route::controller(ModulPengantarController::class)->prefix('modul-pengantar')->group(function () {
        Route::get('data/{id}', 'data');
        Route::post('store/{id}', 'store');
        Route::post('update/{modulPengantarId}', 'update');
        Route::delete('destroy/{modulPengantarId}', 'destroy');
    });

    Route::controller(PertemuanController::class)->prefix('pertemuan')->group(function () {
        Route::get('today', 'pertemuanHariIni');
        Route::get('data/{id}', 'data');
    });

    Route::controller(EbookController::class)->prefix('ebook')->group(function () {
        Route::get('data/{id}', 'data');
        Route::post('store/{id}', 'store');
        Route::post('update/{ebookId}', 'update');
        Route::delete('destroy/{ebookId}', 'destroy');
    });

    Route::controller(ModulController::class)->prefix('modul')->group(function () {
        Route::get('data/{id}', 'data');
        Route::post('store/{id}', 'store');
        Route::post('update/{modulId}', 'update');
        Route::delete('destroy/{modulId}', 'destroy');
    });

    Route::controller(VideoConfController::class)->prefix('video-conf')->group(function () {
        Route::get('data/{id}', 'data');
        Route::post('store/{id}', 'store');
        Route::put('update/{id}', 'update');
        Route::delete('destroy/{id}', 'destroy');
    });

    Route::controller(VideoPembelajaranController::class)->prefix('video-pembelajaran')->group(function () {
        Route::get('data/{id}', 'data');
        Route::post('store/{id}', 'store');
        Route::put('update/{id}', 'update');
        Route::delete('destroy/{id}', 'destroy');
    });

    Route::controller(TugasController::class)->prefix('tugas')->group(function () {
        Route::get('data/{id}', 'data');
        Route::post('store/{id}', 'store');
        Route::post('update/{tugasId}', 'update');
        Route::delete('destroy/{tugasId}', 'destroy');
    });

    Route::controller(EvaluasiController::class)->prefix('evaluasi')->group(function () {
        Route::get('data/{id}', 'data');
        Route::post('store/{id}', 'store');
        Route::put('update/{tugasId}', 'update');
        Route::delete('destroy/{tugasId}', 'destroy');
    });

    Route::controller(MataKuliahTersediaController::class)->prefix('mata-kuliah-tersedia')->group(function () {
        // mata kuliah tersedia mahasiswa
        Route::get('data', 'data');
    });

    Route::controller(MataKuliahDiajukanController::class)->prefix('mata-kuliah-diajukan')->group(function () {
        // mata kuliah diajukan mahasiswa
        Route::get('data', 'data');
        // ajukan matakuliah mahasiswa
        Route::post('ajukan/{sesiMataKuliahId}', 'ajukan');
        // list pengajuan mata kuliah oleh mahasiswa untuk dosen
        Route::get('listPengajuan/{sesiMataKuliahId}', 'listPengajuan');
        // untuk setuju & tolak pengajuan mata kuliah oleh dosen
        Route::post('setuju/{id}', 'setuju');
        Route::post('tolak/{id}', 'tolak');
    });

    Route::controller(MataKuliahMahasiswaController::class)->prefix('mata-kuliah-mahasiswa')->group(function () {
        // mata kuliah mahasiswa
        Route::get('data', 'data');
    });

    Route::controller(AssesmentController::class)->prefix('assesment')->group(function () {
        // list assesment mahasiswa untuk dosen
        Route::get('data/{tugasId}', 'data');

        // detail assesment mahasiswa untuk dosen dan mahasiswa
        Route::get('detail/{tugasId}', 'detail');

        // tolak assesment mahasiswa untuk dosen
        Route::post('tolak/{tugasMahasiswaId}', 'tolak');

        // setuju assesment mahasiswa untuk dosen
        Route::post('terima/{tugasMahasiswaId}', 'terima');

        // kumpulkan assesment mahasiswa untuk mahasiswa
        Route::post('kumpulkan/{tugasId}', 'kumpulkan');
    });

    Route::controller(ProfileController::class)->prefix('profile')->group(function () {
        Route::get('data', 'data');
        Route::post('upload-profile-picture', 'uploadProfilePicture');
        Route::delete('delete-profile-picture', 'deleteProfilePicture');
        Route::post('add-email', 'addEmail');
        Route::post('change-password', 'changePassword');
    });

    Route::controller(AbsenController::class)->prefix('absen')->group(function () {
        Route::post('/{pertemuanID}', 'checkIn');
        Route::get('/{pertemuanID}', 'getStatus');
    });

    Route::controller(MataKulliahDosenController::class)->prefix("mata-kuliah-dosen")->group(function () {
        Route::get('', 'index');
    });
});
