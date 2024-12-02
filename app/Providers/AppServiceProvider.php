<?php

namespace App\Providers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Repositories\InstrumentRepository;
use App\Repositories\InstrumentRepositoryInterface;
use App\Repositories\KompetensiRepository;
use App\Repositories\KompetensiRepositoryInterface;
use App\Services\InstrumentService;
use App\Services\KompetensiService;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(InstrumentRepositoryInterface::class, InstrumentRepository::class);
        $this->app->bind(InstrumentService::class, function ($app) {
            return new InstrumentService($app->make(InstrumentRepositoryInterface::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $user = Auth::user();
            $userMhs = null;
            $userDosn = null;

            if ($user) {
                if ($user->role_id == 2) {
                    $userMhs = Mahasiswa::where('nim', $user->username)->first();
                }

                if ($user->role_id == 3) {
                    $userDosn = Dosen::where('kode_dosen', $user->username)->first();
                }
            }

            $view->with('userMhs', $userMhs);
            $view->with('userDosn', $userDosn);
        });
    }

    private function tokenValidate($request)
    {
        // 
    }
}
