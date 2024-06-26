<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNilaiIjazahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai_ijazah', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_pelajaran');
            $table->string('nilai');
            $table->boolean('status')->default(0);
            $table->foreignId('siswa_aktif_id')->constrained('siswa_aktif')->onDelete('cascade');
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nilai_ijazah');
    }
}
