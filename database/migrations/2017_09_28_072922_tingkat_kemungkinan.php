<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TingkatKemungkinan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('tingkat_kemungkinan', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('nama_tingkat_kemungkinan');
            $table->string('no_tingkat_kemungkinan');
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
        Schema::drop('tingkat_kemungkinan');
    }
}
