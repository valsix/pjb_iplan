<?php namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class PGDLLRParentCode extends Model
{
    protected $table = "pgdl_lr_parent_codes";

    protected $fillable = ['id', 'struktur_bisnis_id', 'keterangan', 'kode_parent'];
  	
	public function struktur_bisnis()
    {
    	return $this->belongsTo('App\Entities\StrukturBisnis', 'struktur_bisnis_id');
    }

}
