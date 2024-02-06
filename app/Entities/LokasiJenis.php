<?php namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class LokasiJenis extends Model
{
    protected $table = "lokasi_jenis";

    protected $fillable = ['lokasi_id', 'jenis_id'];

    protected $primaryKey = 'lokasi_id';

    public function lokasi()
	{
    	return
		$this->belongsTo('App\Entities\Lokasi','lokasi_id','id');
	}


	public function jenis()
	{
    	return
		$this->belongsTo('App\Entities\Jenis','jenis_id','id');
	}
}
