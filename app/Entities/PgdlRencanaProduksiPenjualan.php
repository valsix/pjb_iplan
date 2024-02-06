<?php namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class PgdlRencanaProduksiPenjualan extends Model
{
    protected $table = "pgdl_rencana_produksi_penjualan";

    protected $fillable = ['id', 'lokasi_id', 'produksi_penjualan_id', 'tahun_rencana', 'bulan_rencana', 'rencana'];

    public function pgdl_produksi_penjualan()
    {
    	return $this->belongsTo('App\Entities\PgdlProduksiPenjualan', 'pgdl_produksi_penjualan_id');
    }

    public function lokasi()
    {
    	return $this->belongsTo('App\Entities\Lokasi', 'lokasi_id');
    }
}
