<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class PGDLExcelDataRevisi extends Model
{
	protected $table = "pgdl_excel_datas_revisi";

  public function pgdl_file_import_revisi()
  {
      return $this->belongsTo('App\Entities\PGDLFileImportRevisi', 'id', 'pgdl_file_import_revisi_id');
  }
}
