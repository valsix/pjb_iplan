<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class FileImport extends Model
{
    protected $dates = [
        'draft_versi',
    ];

    public function fase()
    {
        return $this->belongsTo('App\Entities\Fase', 'fase_id', 'id');
    }

    public function template()
    {
        return $this->belongsTo('App\Entities\Template', 'template_id', 'id');
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
}
