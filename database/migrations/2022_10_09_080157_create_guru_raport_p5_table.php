<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuruRaportP5Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guru_raport_p5', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_pelajaran');
            $table->string('semester');
            $table->string('kelas');
            $table->foreignId('guru_id')->constrained('guru')->onDelete('cascade');
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
        Schema::dropIfExists('guru_raport_p5');
    }
}
