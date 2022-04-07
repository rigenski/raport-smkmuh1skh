<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNilaiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_pelajaran');
            $table->string('semester');
            $table->string('nilai');
            $table->string('keterangan');
            $table->string('kelas');
            $table->string('angkatan');
            $table->string('jurusan');
            $table->string('mata_pelajaran');
            $table->foreignId('siswa_id')->constrained('siswa');
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran');
            $table->boolean('status')->default(0);
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
