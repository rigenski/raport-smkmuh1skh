<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNilaiP5Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai_p5', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_pelajaran');
            $table->string('semester');
            $table->string('nilai');
            $table->foreignId('siswa_aktif_id')->constrained('siswa_aktif')->onDelete('cascade');
            $table->foreignId('raport_p5_elemen_id')->constrained('raport_p5_elemen')->onDelete('cascade');
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
        Schema::dropIfExists('nilai');
    }
}
