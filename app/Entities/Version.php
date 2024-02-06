<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    public function sheets()
    {
        return $this->hasMany('App\Entities\Sheet');
    }

    public function file_imports()
    {
        return $this->hasMany('App\Entities\FileImport');
    }

    public function file_imports_ketetapan()
    {
        return $this->hasMany('App\Entities\FileImportKetetapan');
    }

    public function pgdl_file_imports_revisi()
    {
        return $this->hasMany('App\Entities\PGDLFileImportRevisi');
    }

    public function template()
    {
        return $this->belongsTo('App\Entities\Template');
    }
}
