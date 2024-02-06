<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class PgdlStatusAiPjb extends Model
{
    protected $table = "pgdl_status_ai_pjb";

    protected $fillable = ['distrik_id', 'tahun', 'prk', 'po_no', 'file_import_revisi_id', 'status_kontrak_id', 'status_disburse_id', 'date_kontrak', 'date_disburse'];
  	public function file_import_revisi()
  	{
      	return $this->belongsTo('App\Entities\FileImportRevisi', 'file_import_revisi_id');
  	}

  	public function distrik()
  	{
      	return $this->belongsTo('App\Entities\Distrik', 'distrik_id');
  	}
    public function status_kontrak()
  	{
      	return $this->belongsTo('App\Entities\PgdlMasterStatusAiPjb', 'status_kontrak_id');
  	}
    public function status_disburse()
  	{
      	return $this->belongsTo('App\Entities\PgdlMasterStatusAiPjb', 'status_disburse_id');
  	}  	
}
