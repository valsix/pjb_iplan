<?php namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class PGDLHistoryLog extends Model
{
    protected $table = "pgdl_history_logs";

    protected $fillable = ['id', 'prk', 'keterangan', 'identity_prk', 'deskripsi_prk_awal', 'deskripsi_prk_akhir', 'beban_awal', 'beban_akhir', 'cashflow_awal', 'cashflow_akhir', 'ijin_proses_awal', 'ijin_proses_akhir', 'user_id', 'pgdl_file_import_revisi_id'];
 
	public function pgdl_file_import_revisi()
    {
    	return $this->belongsTo('App\Entities\PgdlFileImportRevisi', 'pgdl_file_import_revisi_id');
    }

}
