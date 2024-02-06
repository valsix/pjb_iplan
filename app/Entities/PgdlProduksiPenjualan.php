<?php namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class PgdlProduksiPenjualan extends Model
{
    protected $table = "pgdl_produksi_penjualan";

    protected $fillable = ['id', 'strategi_bisnis_id', 'name'];

    public function pgdl_realisasi_produksi_penjualan()
    {
    	return $this->hasMany('App\Entities\PgdlRealisasiProduksiPenjualan');
    }

    public function pgdl_rencana_produksi_penjualan()
    {
    	return $this->hasMany('App\Entities\PgdlRencanaProduksiPenjualan');
    }

    public function strategi_bisnis()
    {
    	return $this->belongsTo('App\Entities\StrategiBisnis', 'strategi_bisnis_id');
    }
}
