<?php namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Rencana_kerja extends Model
{
    protected $table = "rencanakerja";

    protected $fillable = ['lokasi_id', 'tahun_anggaran', 'name_unit', 'satuan_unit', 'rkap_n_1', 'prak_real_n_1', 'rkap_n'];

    public function lokasi()
    {
    	return 
    	$this->belongsTo('App\Entities\Lokasi','lokasi_id');
    }
}
