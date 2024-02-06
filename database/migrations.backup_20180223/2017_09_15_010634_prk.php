<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Prk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prk', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('lokasi_id');
            $table->string('kode_distrik');
            $table->string('tahun');
            $table->string('identity_parent');
            $table->string('identity_inti');
            $table->string('identity_kegiatan');
            $table->string('ket_identity_inti');
            $table->string('ket_identity_kegiatan');
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
        Schema::drop('prk');
    }
}
