<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FileApproval extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_approval', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('tahun_anggaran');
            $table->integer('distrik_id')->nullable();
            $table->integer('lokasi_id')->nullable();
            $table->integer('approval_id');
            $table->integer('file_import_id')->nullable();
            $table->integer('file_approval_status_id')->nullable();
            $table->text('keterangan')->nullable();
            $table->integer('created_by');
            $table->integer('approval_by')->nullable();
            $table->datetime('approval_at')->nullable();
            $table->integer('jenis_id');
            $table->integer('file_approval_parent_id')->nullable();
            $table->integer('latest_approval_id')->nullable();
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
        Schema::drop('file_approval');
    }
}
