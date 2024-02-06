<?php namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class PgdlTemplate extends Model
{
    protected $table = "pgdl_templates";

    protected $fillable = ['id', 'jenis_id', 'tahun', 'file', 'active', 'setting_filepath'];

    public function pgdl_version()
    {
    	return $this->hasMany('App\Entities\PgdlVersion');
    }

    public function file_imports_ketetapan()
    {
        return $this->hasMany('App\Entities\FileImportKetetapan');
    }

    public function pgdl_file_imports_revisi()
    {
        return $this->hasMany('App\Entities\PGDLFileImportRevisi');
    }

    public function jenis()
    {
        return $this->belongsTo('App\Entities\Jenis');
    }

}
