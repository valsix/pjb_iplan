<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PrkParent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prk_parent', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('desc_prk_parent')->nullable();
            $table->string('identity_prk_parent');
            $table->string('identity_prk_parent_ppa')->nullable();
            $table->string('identity_prk_parent_jom')->nullable();
            $table->string('identity_prk_parent_usaha_lain')->nullable();
            $table->string('name_prk_parent');
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
        Schema::drop('prk_parent');
    }
}
