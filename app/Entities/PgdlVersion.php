<?php namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class PgdlVersion extends Model
{
    protected $table = "pgdl_versions";

    protected $fillable = ['id', 'pgdl_template_id', 'versi', 'file', 'active'];

    public function pgdl_template()
    {
        return $this->belongsTo('App\Entities\PgdlTemplate', 'pgdl_template_id');
    }

    public function file_imports_ketetapan()
    {
        return $this->hasMany('App\Entities\FileImportKetetapan');
    }

    public function pgdl_sheet()
    {
        return $this->hasMany('App\Entities\PgdlSheet');
    }

}
