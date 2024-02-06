<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RencanaKerja extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('rencanakerja', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('lokasi_id');
            $table->string('tahun_anggaran');
            $table->string('name_unit');
            $table->string('satuan_unit');
            $table->string('rkap_n_1');
            $table->string('prak_real_n_1');
            $table->string('rkap_n');
            $table->string('create_by');
            $table->string('update_by')->nullable();
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
        //
        Schema::drop('rencanakerja');
    }
}
