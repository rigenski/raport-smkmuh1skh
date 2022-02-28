<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\WaliKelasController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/**
 * AUTHENTICATION
 */

Route::get('/', [AuthController::class, 'home'])->name('home');

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login/post', [AuthController::class, 'login'])->name('login.post');

Route::group(['middleware' => ['auth', 'checkRole:admin,guru,wali kelas']], function () {
  Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

  Route::get('/admin', [DashboardController::class, 'index'])->name('admin');
});

Route::group(['middleware' => ['auth', 'checkRole:admin']], function () {
  Route::get('/admin/setting', [SettingController::class, 'index'])->name('admin.setting');
  Route::post('/admin/setting/update', [SettingController::class, 'update'])->name('admin.setting.update');

  Route::get('/admin/guru', [GuruController::class, 'index'])->name('admin.guru');
  Route::post('/admin/guru/import', [GuruController::class, 'import'])->name('admin.guru.import');
  Route::get('/admin/guru/export-format', [GuruController::class, 'export_format'])->name('admin.guru.export_format');
  Route::post('/admin/guru/{id}/update', [GuruController::class, 'update'])->name('admin.guru.update');
  Route::get('/admin/guru/{id}/destroy', [GuruController::class, 'destroy'])->name('admin.guru.destroy');

  Route::get('/admin/wali-kelas', [WaliKelasController::class, 'index'])->name('admin.wali_kelas');
  Route::post('/admin/wali-kelas/import', [WaliKelasController::class, 'import'])->name('admin.wali_kelas.import');
  Route::get('/admin/wali-kelas/export-format', [WaliKelasController::class, 'export_format'])->name('admin.wali_kelas.export_format');
  Route::post('/admin/wali-kelas/{id}/update', [WaliKelasController::class, 'update'])->name('admin.wali_kelas.update');
  Route::get('/admin/wali-kelas/{id}/destroy', [WaliKelasController::class, 'destroy'])->name('admin.wali_kelas.destroy');

  Route::get('/admin/siswa', [SiswaController::class, 'index'])->name('admin.siswa');
  Route::post('/admin/siswa/import', [SiswaController::class, 'import'])->name('admin.siswa.import');
  Route::get('/admin/siswa/export-format', [SiswaController::class, 'export_format'])->name('admin.siswa.export_format');
  Route::post('/admin/siswa/{id}/update', [SiswaController::class, 'update'])->name('admin.siswa.update');
  Route::get('/admin/siswa/{id}/destroy', [SiswaController::class, 'destroy'])->name('admin.siswa.destroy');

  Route::get('/admin/mapel', [MapelController::class, 'index'])->name('admin.mapel');
  Route::post('/admin/mapel/import', [MapelController::class, 'import'])->name('admin.mapel.import');
  Route::get('/admin/mapel/export-format', [MapelController::class, 'export_format'])->name('admin.mapel.export_format');
  Route::post('/admin/mapel/{id}/update', [MapelController::class, 'update'])->name('admin.mapel.update');
  Route::get('/admin/mapel/{id}/destroy', [MapelController::class, 'destroy'])->name('admin.mapel.destroy');

  Route::get('/admin/ranking', [RankingController::class, 'index'])->name('admin.ranking');
});

Route::group(['middleware' => ['auth', 'checkRole:admin,guru,wali kelas']], function () {
  Route::get('/admin/nilai', [NilaiController::class, 'index'])->name('admin.nilai');
  Route::post('/admin/nilai/import', [NilaiController::class, 'import'])->name('admin.nilai.import');
  Route::get('/admin/nilai/export-format', [NilaiController::class, 'export_format'])->name('admin.nilai.export_format');
  Route::post('/admin/nilai/{id}/update', [NilaiController::class, 'update'])->name('admin.nilai.update');
  Route::get('/admin/nilai/{id}/destroy', [NilaiController::class, 'destroy'])->name('admin.nilai.destroy');
});

Route::group(['middleware' => ['auth', 'checkRole:admin,guru']], function () {
});
