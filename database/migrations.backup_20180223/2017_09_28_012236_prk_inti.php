<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PrkInti extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prk_inti', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('desc_prk_inti');
            $table->integer('prk_parent_id');
            $table->string('identity_prk_inti');
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
        Schema::drop('prk_inti');
    }
}
