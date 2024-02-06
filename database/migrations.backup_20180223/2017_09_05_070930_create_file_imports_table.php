<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileImportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_imports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('template_id');
            $table->integer('version_id');
            $table->integer('fase_id');
            $table->integer('tahun');
            $table->string('file', 191)->nullable();
            $table->integer('status_upload_id')->default(1);
            $table->text('error')->nullable();
            $table->timestamp('draft_versi');
            $table->integer('form6_rutin_file_import_id')->nullable();
            $table->integer('form6_reimburse_file_import_id')->nullable();
            $table->integer('form10_pln_file_import_id')->nullable();
            $table->integer('form10_pu_file_import_id')->nullable();
            $table->integer('form10_penguatankit_file_import_id')->nullable();
            $table->integer('form_bahan_bakar_file_import_id')->nullable();
            $table->integer('form_penyusutan_file_import_id')->nullable();
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
        Schema::dropIfExists('file_imports');
    }
}
