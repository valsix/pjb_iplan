<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Dmr extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dmr', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('no_dokumen');
            $table->string('no_prk')->nullable();
            $table->string('nama_prk')->nullable();
            $table->integer('tahun_anggaran');
            $table->integer('lokasi_id');
            $table->text('dmr_filepath');
            $table->integer('is_submitted')->default(0);
            $table->integer('dmr_review_phase_id')->default(0);
            $table->integer('dmr_review_status_id')->default(0);
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->integer('submitted_by')->nullable();
            $table->datetime('rejected_at')->nullable();
            $table->integer('rejected_by')->nullable();
            $table->datetime('revised_at')->nullable();
            $table->integer('revised_by')->nullable();
            $table->datetime('approved_at')->nullable();
            $table->integer('approved_by')->nullable();
            $table->text('alasan')->nullable();
            $table->text('latar_belakang');
            $table->text('sasaran_tujuan');
            $table->text('permasalahan');
            $table->text('alternatif_pencapaian');
            $table->text('benefit_operasional');
            $table->text('benefit_finansial');

            $table->text('alasan_latar_belakang')->nullable();
            $table->text('alasan_sasaran_tujuan')->nullable();
            $table->text('alasan_permasalahan')->nullable();
            $table->text('alasan_alternatif_pencapaian')->nullable();
            $table->text('alasan_benefit_operasional')->nullable();
            $table->text('alasan_benefit_finansial')->nullable();

            $table->bigInteger('jumlah_anggaran');
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
    Schema::drop('dmr');
    }
}
