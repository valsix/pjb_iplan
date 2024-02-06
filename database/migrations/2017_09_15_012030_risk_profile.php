<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RiskProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riskprofile', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('lokasi_id');
            $table->string('risk_tag');
            $table->string('risk_event');
            $table->string('risk_corporate');
            $table->string('possibility_level');
            $table->string('impact_level');
            $table->string('risk_level');
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
        Schema::drop('riskprofile');
    }
}
