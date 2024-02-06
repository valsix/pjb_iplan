<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TingkatDampak extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('tingkat_dampak', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('nama_tingkat_dampak');
            $table->string('no_tingkat_dampak');
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
        Schema::drop('tingkat_dampak');
    }
}
