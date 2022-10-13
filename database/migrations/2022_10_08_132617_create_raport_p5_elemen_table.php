<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRaportP5ElemenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raport_p5_elemen', function (Blueprint $table) {
            $table->id();
            $table->string('sub_elemen');
            $table->text('akhir_fase');
            $table->foreignId('raport_p5_dimensi_id')->constrained('raport_p5_dimensi')->onDelete('cascade');
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
        Schema::dropIfExists('raport_p5_elemen');
    }
}
