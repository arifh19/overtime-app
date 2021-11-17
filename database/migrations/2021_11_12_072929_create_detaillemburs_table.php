<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetaillembursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detaillemburs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nik');
            $table->string('nama');
            $table->string('waktu');
            $table->integer('lama_lembur');
            $table->unsignedInteger('departemen_id');
            $table->unsignedInteger('status_id');
            $table->unsignedInteger('lembur_id');
            $table->string('tanggal');
            $table->timestamps();
            $table->foreign('departemen_id')->references('id')->on('departemens')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('statuses')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('lembur_id')->references('id')->on('lemburs')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detaillemburs');
    }
}
