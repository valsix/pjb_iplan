<?php

namespace App\Http\Controllers\Pengendalian;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Entities\StrategiBisnis;
use App\Entities\Fase;
use App\Entities\Distrik;
use App\Entities\Lokasi;
use App\Entities\User;
use App\Entities\Role;
use App\Entities\Template;
use App\Entities\PgdlRealisasiProduksiPenjualan;
use App\Entities\PgdlRencanaProduksiPenjualan;
use DB;
use Input;
use Excel;
use PDF;

class LRInputProduksiPenjualanController extends Controller
{
    public function index(Request $rq)
    {
        $data['bulan'] = $this->getBulan();
        $data['tahun'] = Template::orderBy('tahun','ASC')->distinct()->get(['tahun']);
        $data['strategi_bisnis'] = StrategiBisnis::all();
        return view('pengendalian_input.lr_realisasi_produksi_penjualan.index',$data);
    }

    public function getBulan()
    {
        $bulan = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        return $bulan;
    }

    public function DataAjax(Request $request)
    {
        $tahun = $request->tahun;
        $bulan = $request->bulan;
        $lokasi = $request->lokasi;

        $produksi =  PgdlRealisasiProduksiPenjualan::where('produksi_penjualan_id',5)->where('tahun_realisasi',$tahun)
                                                            ->where('bulan_realisasi',$bulan)->where('lokasi_id',$lokasi)
                                                            ->select("id","realisasi")->first();
        $penjualan = PgdlRealisasiProduksiPenjualan::where('produksi_penjualan_id',6)->where('tahun_realisasi',$tahun)
                                                            ->where('bulan_realisasi',$bulan)->where('lokasi_id',$lokasi)
                                                            ->select("id","realisasi")->first();
        $harga = PgdlRealisasiProduksiPenjualan::where('produksi_penjualan_id',7)->where('tahun_realisasi',$tahun)
                                                            ->where('bulan_realisasi',$bulan)->where('lokasi_id',$lokasi)
                                                            ->select("id","realisasi")->first();
        $bpp = PgdlRealisasiProduksiPenjualan::where('produksi_penjualan_id',8)->where('tahun_realisasi',$tahun)
                                                            ->where('bulan_realisasi',$bulan)->where('lokasi_id',$lokasi)
                                                            ->select("id","realisasi")->first();


        $data['produksi_id'] = ($produksi) == null ? null:$produksi->id;
        $data['produksi'] = ($produksi) == null ? null:$produksi->realisasi;
        $data['penjualan_id'] = ($penjualan) == null ? null:$penjualan->id;
        $data['penjualan'] = ($penjualan) == null ? null:$penjualan->realisasi;
        $data['harga_id'] = ($harga) == null ? null:$harga->id;
        $data['harga'] = ($harga) == null ? null:$harga->realisasi;
        $data['bpp_id'] = ($bpp) == null ? null:$bpp->id;
        $data['bpp'] = ($bpp) == null ? null:$bpp->realisasi;

        $produksi_rencana =  PgdlRencanaProduksiPenjualan::where('produksi_penjualan_id',5)->where('tahun_rencana',$tahun)
                                                            ->where('bulan_rencana',$bulan)->where('lokasi_id',$lokasi)
                                                            ->select("id","rencana")->first();
        $penjualan_rencana = PgdlRencanaProduksiPenjualan::where('produksi_penjualan_id',6)->where('tahun_rencana',$tahun)
                                                            ->where('bulan_rencana',$bulan)->where('lokasi_id',$lokasi)
                                                            ->select("id","rencana")->first();
        $harga_rencana = PgdlRencanaProduksiPenjualan::where('produksi_penjualan_id',7)->where('tahun_rencana',$tahun)
                                                            ->where('bulan_rencana',$bulan)->where('lokasi_id',$lokasi)
                                                            ->select("id","rencana")->first();
        $bpp_rencana = PgdlRencanaProduksiPenjualan::where('produksi_penjualan_id',8)->where('tahun_rencana',$tahun)
                                                            ->where('bulan_rencana',$bulan)->where('lokasi_id',$lokasi)
                                                            ->select("id","rencana")->first();

        $data['rencana_produksi_id'] = ($produksi_rencana) == null ? null:$produksi_rencana->id;
        $data['rencana_produksi'] = ($produksi_rencana) == null ? null:$produksi_rencana->rencana;
        $data['rencana_penjualan_id'] = ($penjualan_rencana) == null ? null:$penjualan_rencana->id;
        $data['rencana_penjualan'] = ($penjualan_rencana) == null ? null:$penjualan_rencana->rencana;
        $data['rencana_harga_id'] = ($harga_rencana) == null ? null:$harga_rencana->id;
        $data['rencana_harga'] = ($harga_rencana) == null ? null:$harga_rencana->rencana;
        $data['rencana_bpp_id'] = ($bpp_rencana) == null ? null:$bpp_rencana->id;
        $data['rencana_bpp'] = ($bpp_rencana) == null ? null:$bpp_rencana->rencana;

        return $data;
    }

    public function StoreDataAjax(Request $request)
    {
        if($request->produksi_id)
        {
            $produksi =  PgdlRealisasiProduksiPenjualan::find($request->produksi_id);
            $produksi->realisasi = $request->produksi;

            $produksi->save();
        }

        if($request->rencana_produksi_id)
        {
            $produksi_rencana =  PgdlRencanaProduksiPenjualan::find($request->rencana_produksi_id);
            $produksi_rencana->rencana = $request->rencana_produksi;

            $produksi_rencana->save();
        }

        if($request->produksi_id==null)
        {
            $produksi = new PgdlRealisasiProduksiPenjualan;
            $produksi->lokasi_id = $request->lokasi;
            $produksi->produksi_penjualan_id = 5;
            $produksi->bulan_realisasi = $request->bulan;
            $produksi->tahun_realisasi = $request->tahun;
            $produksi->realisasi = $request->produksi;

            $produksi->save();

        }

        if($request->rencana_produksi_id==null)
        {
            $produksi_rencana = new PgdlRencanaProduksiPenjualan;
            $produksi_rencana->lokasi_id = $request->lokasi;
            $produksi_rencana->produksi_penjualan_id = 5;
            $produksi_rencana->bulan_rencana = $request->bulan;
            $produksi_rencana->tahun_rencana = $request->tahun;
            $produksi_rencana->rencana = $request->rencana_produksi;

            $produksi_rencana->save();
        }

        if($request->penjualan_id)
        {
            $penjualan =  PgdlRealisasiProduksiPenjualan::find($request->penjualan_id);
            $penjualan->realisasi = $request->penjualan;

            $penjualan->save();
        }

        if($request->rencana_penjualan_id)
        {
            $penjualan_rencana =  PgdlRencanaProduksiPenjualan::find($request->rencana_penjualan_id);
            $penjualan_rencana->rencana = $request->rencana_penjualan;

            $penjualan_rencana->save();
        }

        if($request->penjualan_id==null)
        {
            $penjualan = new PgdlRealisasiProduksiPenjualan;
            $penjualan->lokasi_id = $request->lokasi;
            $penjualan->produksi_penjualan_id = 6;
            $penjualan->bulan_realisasi = $request->bulan;
            $penjualan->tahun_realisasi = $request->tahun;
            $penjualan->realisasi = $request->penjualan;

            $penjualan->save();

        }

        if($request->rencana_penjualan_id==null)
        {
            $penjualan_rencana = new PgdlRencanaProduksiPenjualan;
            $penjualan_rencana->lokasi_id = $request->lokasi;
            $penjualan_rencana->produksi_penjualan_id = 6;
            $penjualan_rencana->bulan_rencana = $request->bulan;
            $penjualan_rencana->tahun_rencana = $request->tahun;
            $penjualan_rencana->rencana = $request->rencana_penjualan;

            $penjualan_rencana->save();

        }

        if($request->harga_id)
        {
            $harga =  PgdlRealisasiProduksiPenjualan::find($request->harga_id);
            $harga->realisasi = $request->harga;

            $harga->save();
        }

        if($request->rencana_harga_id)
        {
            $harga_rencana =  PgdlRencanaProduksiPenjualan::find($request->rencana_harga_id);
            $harga_rencana->rencana = $request->rencana_harga;

            $harga_rencana->save();
        }


        if($request->harga_id==null)
        {
            $harga = new PgdlRealisasiProduksiPenjualan;
            $harga->lokasi_id = $request->lokasi;
            $harga->produksi_penjualan_id = 7;
            $harga->bulan_realisasi = $request->bulan;
            $harga->tahun_realisasi = $request->tahun;
            $harga->realisasi = $request->harga;

            $harga->save();
        }

        if($request->rencana_harga_id==null)
        {
            $harga_rencana = new PgdlRencanaProduksiPenjualan;
            $harga_rencana->lokasi_id = $request->lokasi;
            $harga_rencana->produksi_penjualan_id = 7;
            $harga_rencana->bulan_rencana = $request->bulan;
            $harga_rencana->tahun_rencana = $request->tahun;
            $harga_rencana->rencana = $request->rencana_harga;

            $harga_rencana->save();
        }

        if($request->bpp_id)
        {
            $bpp =  PgdlRealisasiProduksiPenjualan::find($request->bpp_id);
            $bpp->realisasi = $request->bpp;

            $bpp->save();
        }

        if($request->rencana_bpp_id)
        {
            $bpp_rencana =  PgdlRencanaProduksiPenjualan::find($request->rencana_bpp_id);
            $bpp_rencana->rencana = $request->rencana_bpp;

            $bpp_rencana->save();
        }

        if($request->bpp_id==null)
        {
            $bpp = new PgdlRealisasiProduksiPenjualan;
            $bpp->lokasi_id = $request->lokasi;
            $bpp->produksi_penjualan_id = 8;
            $bpp->bulan_realisasi = $request->bulan;
            $bpp->tahun_realisasi = $request->tahun;
            $bpp->realisasi = $request->bpp;

            $bpp->save();
        }

        if($request->rencana_bpp_id==null)
        {
            $bpp_rencana = new PgdlRencanaProduksiPenjualan;
            $bpp_rencana->lokasi_id = $request->lokasi;
            $bpp_rencana->produksi_penjualan_id = 8;
            $bpp_rencana->bulan_rencana = $request->bulan;
            $bpp_rencana->tahun_rencana = $request->tahun;
            $bpp_rencana->rencana = $request->rencana_bpp;

            $bpp_rencana->save();
        }

        return response()->json(['response' => 'sukses']);
    }

}
