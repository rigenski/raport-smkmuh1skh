<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(
            ['layouts.admin'],
            function ($view) {
                $view->with('setting', Setting::all());
            }
        );

        view()->composer(
            ['admin.setting.index', 'admin.guru.index', 'admin.guru-mata-pelajaran.index', 'admin.mata-pelajaran.index', 'admin.nilai.index', 'admin.ranking.index', 'admin.raport.index', 'admin.riwayat.index', 'admin.setting.index', 'admin.siswa.index', 'admin.siswa-aktif.index', 'admin.wali-kelas.index', 'admin.ekskul.index', 'admin.ketidakhadiran.index', 'admin.dokumen.index'],
            function ($view) {
                $view->with('data_tahun_pelajaran', ['2019/2020', '2020/2021', '2021/2022', '2022/2023', '2023/2024', '2024/2025', '2025/2026']);
            }
        );
    }
}
