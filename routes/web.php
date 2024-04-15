<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\EkskulController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\GuruMataPelajaranController;
use App\Http\Controllers\GuruRaportP5Controller;
use App\Http\Controllers\KetidakhadiranController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\NilaiIjazahController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\RaportController;
use App\Http\Controllers\RaportP5Controller;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SiswaAktifController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TranskripController;
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

  Route::get('/admin/mata-pelajaran', [MataPelajaranController::class, 'index'])->name('admin.mata_pelajaran');
  Route::post('/admin/mata-pelajaran/import', [MataPelajaranController::class, 'import'])->name('admin.mata_pelajaran.import');
  Route::get('/admin/mata-pelajaran/reset', [MataPelajaranController::class, 'reset'])->name('admin.mata_pelajaran.reset');
  Route::get('/admin/mata-pelajaran/export-format', [MataPelajaranController::class, 'export_format'])->name('admin.mata_pelajaran.export_format');
  Route::post('/admin/mata-pelajaran/{id}/update', [MataPelajaranController::class, 'update'])->name('admin.mata_pelajaran.update');
  Route::get('/admin/mata-pelajaran/{id}/destroy', [MataPelajaranController::class, 'destroy'])->name('admin.mata_pelajaran.destroy');

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

  Route::get('/admin/guru-mata-pelajaran', [GuruMataPelajaranController::class, 'index'])->name('admin.guru_mata_pelajaran');
  Route::post('/admin/guru-mata-pelajaran/import', [GuruMataPelajaranController::class, 'import'])->name('admin.guru_mata_pelajaran.import');
  Route::get('/admin/guru-mata-pelajaran/export-format', [GuruMataPelajaranController::class, 'export_format'])->name('admin.guru_mata_pelajaran.export_format');
  Route::post('/admin/guru-mata-pelajaran/{id}/update', [GuruMataPelajaranController::class, 'update'])->name('admin.guru_mata_pelajaran.update');
  Route::get('/admin/guru-mata-pelajaran/{id}/destroy', [GuruMataPelajaranController::class, 'destroy'])->name('admin.guru_mata_pelajaran.destroy');

  Route::get('/admin/guru-raport-p5', [GuruRaportP5Controller::class, 'index'])->name('admin.guru_raport_p5');
  Route::post('/admin/guru-raport-p5/import', [GuruRaportP5Controller::class, 'import'])->name('admin.guru_raport_p5.import');
  Route::get('/admin/guru-raport-p5/export-format', [GuruRaportP5Controller::class, 'export_format'])->name('admin.guru_raport_p5.export_format');
  Route::post('/admin/guru-raport-p5/{id}/update', [GuruRaportP5Controller::class, 'update'])->name('admin.guru_raport_p5.update');
  Route::get('/admin/guru-raport-p5/{id}/destroy', [GuruRaportP5Controller::class, 'destroy'])->name('admin.guru_raport_p5.destroy');

  Route::get('/admin/siswa', [SiswaController::class, 'index'])->name('admin.siswa');
  Route::post('/admin/siswa/import', [SiswaController::class, 'import'])->name('admin.siswa.import');
  Route::get('/admin/siswa/export-format', [SiswaController::class, 'export_format'])->name('admin.siswa.export_format');
  Route::post('/admin/siswa/{id}/update', [SiswaController::class, 'update'])->name('admin.siswa.update');
  Route::get('/admin/siswa/{id}/destroy', [SiswaController::class, 'destroy'])->name('admin.siswa.destroy');

  Route::get('/admin/siswa-aktif', [SiswaAktifController::class, 'index'])->name('admin.siswa_aktif');
  Route::post('/admin/siswa-aktif/import', [SiswaAktifController::class, 'import'])->name('admin.siswa_aktif.import');
  Route::get('/admin/siswa-aktif/export-format', [SiswaAktifController::class, 'export_format'])->name('admin.siswa_aktif.export_format');
  Route::post('/admin/siswa-aktif/{id}/update', [SiswaAktifController::class, 'update'])->name('admin.siswa_aktif.update');
  Route::get('/admin/siswa-aktif/{id}/destroy', [SiswaAktifController::class, 'destroy'])->name('admin.siswa_aktif.destroy');

  Route::get('/admin/ranking', [RankingController::class, 'index'])->name('admin.ranking');
  Route::get('/admin/ranking/print', [RankingController::class, 'print'])->name('admin.ranking.print');
  Route::get('/admin/ranking/export-excel', [RankingController::class, 'export_excel'])->name('admin.ranking.export_excel');

  Route::get('/admin/riwayat', [RiwayatController::class, 'index'])->name('admin.riwayat');

  Route::post('/admin/dokumen/store', [DokumenController::class, 'store'])->name('admin.dokumen.store');
  Route::post('/admin/dokumen/{id}/update', [DokumenController::class, 'update'])->name('admin.dokumen.update');
  Route::get('/admin/dokumen/{id}/destroy', [DokumenController::class, 'destroy'])->name('admin.dokumen.destroy');
});

Route::group(['middleware' => ['auth', 'checkRole:admin,guru,wali kelas']], function () {
  Route::get('/admin/nilai', [NilaiController::class, 'index'])->name('admin.nilai');
  Route::post('/admin/nilai/import', [NilaiController::class, 'import'])->name('admin.nilai.import');
  Route::post('/admin/nilai/{siswa_aktif_id}/{mata_pelajaran_id}/store', [NilaiController::class, 'store'])->name('admin.nilai.store');
  Route::get('/admin/nilai/export-format', [NilaiController::class, 'export_format'])->name('admin.nilai.export_format');
  Route::post('/admin/nilai/{id}/update', [NilaiController::class, 'update'])->name('admin.nilai.update');

  Route::get('/admin/nilai-ijazah', [NilaiIjazahController::class, 'index'])->name('admin.nilai_ijazah');
  Route::post('/admin/nilai-ijazah/import', [NilaiIjazahController::class, 'import'])->name('admin.nilai_ijazah.import');
  Route::post('/admin/nilai-ijazah/{siswa_aktif_id}/{mata_pelajaran_id}/store', [NilaiIjazahController::class, 'store'])->name('admin.nilai_ijazah.store');
  Route::get('/admin/nilai-ijazah/export-format', [NilaiIjazahController::class, 'export_format'])->name('admin.nilai_ijazah.export_format');
  Route::post('/admin/nilai-ijazah/{id}/update', [NilaiIjazahController::class, 'update'])->name('admin.nilai_ijazah.update');
  Route::get('/admin/nilai-ijazah/reset', [NilaiIjazahController::class, 'reset'])->name('admin.nilai_ijazah.reset');

  Route::get('/admin/raport_p5', [RaportP5Controller::class, 'index'])->name('admin.raport_p5');
  Route::get('/admin/raport_p5/print', [RaportP5Controller::class, 'print'])->name('admin.raport_p5.print');
  Route::get('/admin/raport_p5/setting', [RaportP5Controller::class, 'setting'])->name('admin.raport_p5.setting');
  Route::post('/admin/raport_p5/setting/edit', [RaportP5Controller::class, 'editSetting'])->name('admin.raport_p5.setting.edit');
  Route::get('/admin/raport_p5/projek', [RaportP5Controller::class, 'projek'])->name('admin.raport_p5.projek');
  Route::post('/admin/raport_p5/projek/edit', [RaportP5Controller::class, 'editProjek'])->name('admin.raport_p5.projek.edit');
  Route::get('/admin/raport_p5/dimensi', [RaportP5Controller::class, 'dimensi'])->name('admin.raport_p5.dimensi');
  Route::post('/admin/raport_p5/dimensi/edit', [RaportP5Controller::class, 'editdimensi'])->name('admin.raport_p5.dimensi.edit');
  Route::get('/admin/raport_p5/elemen', [RaportP5Controller::class, 'elemen'])->name('admin.raport_p5.elemen');
  Route::post('/admin/raport_p5/elemen/edit', [RaportP5Controller::class, 'editElemen'])->name('admin.raport_p5.elemen.edit');
  Route::post('/admin/raport_p5/import', [RaportP5Controller::class, 'import'])->name('admin.raport_p5.import');
  Route::get('/admin/raport_p5/export-format', [RaportP5Controller::class, 'export_format'])->name('admin.raport_p5.export_format');

  Route::get('/admin/dokumen', [DokumenController::class, 'index'])->name('admin.dokumen');
});

Route::group(['middleware' => ['auth', 'checkRole:admin,wali kelas']], function () {
  Route::get('/admin/raport', [RaportController::class, 'index'])->name('admin.raport');
  Route::get('/admin/raport/print', [RaportController::class, 'print'])->name('admin.raport.print');

  Route::get('/admin/transkrip', [TranskripController::class, 'index'])->name('admin.transkrip');
  Route::get('/admin/transkrip/print', [TranskripController::class, 'print'])->name('admin.transkrip.print');

  Route::get('/admin/ekskul', [EkskulController::class, 'index'])->name('admin.ekskul');
  Route::post('/admin/ekskul/import', [EkskulController::class, 'import'])->name('admin.ekskul.import');
  Route::post('/admin/ekskul/{siswa_aktif_id}/store', [EkskulController::class, 'store'])->name('admin.ekskul.store');
  Route::get('/admin/ekskul/export-format', [EkskulController::class, 'export_format'])->name('admin.ekskul.export_format');
  Route::post('/admin/ekskul/{id}/update', [EkskulController::class, 'update'])->name('admin.ekskul.update');

  Route::get('/admin/ketidakhadiran', [KetidakhadiranController::class, 'index'])->name('admin.ketidakhadiran');
  Route::post('/admin/ketidakhadiran/import', [KetidakhadiranController::class, 'import'])->name('admin.ketidakhadiran.import');
  Route::post('/admin/ketidakhadiran/{siswa_aktif_id}/store', [KetidakhadiranController::class, 'store'])->name('admin.ketidakhadiran.store');
  Route::get('/admin/ketidakhadiran/export-format', [KetidakhadiranController::class, 'export_format'])->name('admin.ketidakhadiran.export_format');
  Route::post('/admin/ketidakhadiran/{id}/update', [KetidakhadiranController::class, 'update'])->name('admin.ketidakhadiran.update');
});
