<?php namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class PgdlSheetSetting extends Model
{
    protected $table = "pgdl_sheet_settings";

    protected $fillable = ['id', 'pgdl_sheet_id', 'kolom', 'row', 'validation_type', 'color', 'validation', 'query_value', 'sequence', 'editable'];

	public function pgdl_sheet()
    {
    	return $this->belongsTo('App\Entities\PgdlSheet', 'pgdl_sheet_id');
    }

}
