<?php namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class PgdlRealisasiProduksiPenjualan extends Model
{
    protected $table = "pgdl_realisasi_produksi_penjualan";

    protected $fillable = ['id', 'lokasi_id', 'produksi_penjualan_id', 'tahun_realisasi', 'bulan_realisasi', 'realisasi'];

    public function pgdl_produksi_penjualan()
    {
    	return $this->belongsTo('App\Entities\PgdlProduksiPenjualan', 'pgdl_produksi_penjualan_id');
    }

    public function lokasi()
    {
    	return $this->belongsTo('App\Entities\Lokasi', 'lokasi_id');
    }
}
