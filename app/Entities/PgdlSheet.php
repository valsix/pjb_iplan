<?php namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class PgdlSheet extends Model
{
    protected $table = "pgdl_sheets";

    protected $fillable = ['id', 'pgdl_version_id', 'name'];

	public function pgdl_version()
    {
    	return $this->belongsTo('App\Entities\PgdlVersion', 'pgdl_version_id');
    }

	public function pgdl_sheet_setting()
    {
    	return $this->hasMany('App\Entities\PgdlSheetSetting');
    }

    public function pgdl_excel_datas_revisi()
    {
        return $this->hasMany('App\Entities\PgdlExcelDataRevisi');
    }
}
