<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class ExcelDataKetetapan extends Model
{
  public function file_import()
  {
      return $this->belongsTo('App\Entities\FileImport', 'id', 'file_imports_id');
  }

  public function file_import_ketetapan()
  {
      return $this->belongsTo('App\Entities\FileImportKetetapan', 'id', 'file_import_ketetapan_id');
  }
}
