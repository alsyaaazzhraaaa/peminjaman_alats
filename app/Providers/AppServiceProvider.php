<?php

namespace App\Providers;

use App\Models\Alat;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use App\Observers\PeminjamanObserver;
use App\Observers\PengembalianObserver;
use App\Policies\AlatPolicy;
use App\Policies\KategoriPolicy;
use App\Policies\LogAktivitasPolicy;
use App\Policies\PeminjamanPolicy;
use App\Policies\PengembalianPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    protected $policies = [
        User::class => UserPolicy::class,
        Kategori::class => KategoriPolicy::class,
        Alat::class => AlatPolicy::class,
        Peminjaman::class => PeminjamanPolicy::class,
        Pengembalian::class => PengembalianPolicy::class,
        LogAktivitas::class => LogAktivitasPolicy::class,
    ];


    public function register(): void
    {

    }


    public function boot(): void
    {

        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }


        Peminjaman::observe(PeminjamanObserver::class);
        Pengembalian::observe(PengembalianObserver::class);
    }
}
