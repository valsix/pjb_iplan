<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class FileImportKetetapan extends Model
{

    protected $table = "file_imports_ketetapan";

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

    public function pgdl_file_import_revisi()
    {
        return $this->belongsTo('App\Entities\PGDLFileImportRevisi', 'file_import_ketetapan_id' , 'id');
    }


}
