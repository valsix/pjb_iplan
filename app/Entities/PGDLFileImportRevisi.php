<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class PGDLFileImportRevisi extends Model
{
    protected $table = "pgdl_file_imports_revisi";

    protected $dates = [
        'draft_versi',
    ];

    public function fase()
    {
        return $this->belongsTo('App\Entities\Fase', 'fase_id', 'id');
    }

    public function version()
    {
        return $this->belongsTo('App\Entities\Version');
    }

    public function status_upload()
    {
        return $this->belongsTo('App\Entities\StatusUpload');
    }

    public function distrik()
    {
        return $this->belongsTo('App\Entities\Distrik');
    }

    public function lokasi()
    {
        return $this->belongsTo('App\Entities\Lokasi');
    }

    public function file_import_ketetapan()
    {
        return $this->belongsTo('App\Entities\FileImportKetetapan', 'file_import_id' , 'id');
    }

    public function pgdl_excel_datas_revisi()
    {
        return $this->belongsTo('App\Entities\PGDLExcelDataRevisi', 'pgdl_file_import_revisi_id', 'id');
    }
}
