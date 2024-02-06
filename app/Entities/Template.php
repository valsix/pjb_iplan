<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
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

    public function version()
    {
        return $this->hasMany('App\Entities\Version');
    }

    public function jenis()
    {
        return $this->belongsTo('App\Entities\Jenis');
    }
}
