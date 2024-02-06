<?php namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Prk extends Model
{
    protected $table = "prk";

    protected $fillable = ['kode_distrik', 'lokasi_id','tahun','identity_parent','identity_inti','identity_kegiatan','ket_identity_inti','ket_identity_kegiatan'];

    public function lokasi()
    {
    	return 
    	$this->belongsTo('App\Entities\Lokasi','lokasi_id');
    }
}
