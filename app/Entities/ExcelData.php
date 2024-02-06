<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class ExcelData extends Model
{
  public function file_import_ketetapan()
  {
      return $this->belongsTo('App\Entities\FileImportKetetapan');
  }

  public function file_import()
  {
      return $this->belongsTo('App\Entities\FileImport', 'file_import_id');
  }
}
