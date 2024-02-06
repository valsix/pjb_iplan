<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LevelResiko extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('level_resiko', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('tingkat_kemungkinan_id');
            $table->string('tingkat_dampak_id');
            $table->string('nama_level_resiko');
            $table->string('warna_level_resiko');
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
        Schema::drop('level_resiko');
    }
}
