<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDistrikLokasiToFileImports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('file_imports', function (Blueprint $table) {
            $table->integer('distrik_id')->nullable();
            $table->integer('lokasi_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('file_imports', function (Blueprint $table) {
            $table->dropColumn('distrik_id');
            $table->dropColumn('lokasi_id');
            //
        });
    }
}
