<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Entities\StrategiBisnis;
use App\Entities\Fase;  
use App\Entities\Distrik;
use App\Entities\Lokasi;
use App\Entities\Jenis;
use App\Entities\LokasiJenis;
use App\Entities\FileApproval;
use Illuminate\Support\Facades\DB;
use Excel;
use PDF;

class SummaryFormController extends Controller
{
    public function summary(Request $request)
    {
        if ($request->isMethod('get')) 
        {
            $Sbisnis       = StrategiBisnis::all();
            $fs            = Fase::all();
            $input_f       = 1;
            $input_t       = DB::table('file_approval')->orderBy('tahun_anggaran', 'desc')
                                                       ->pluck('tahun_anggaran')
                                                       ->first();
            $tahun         = DB::table('file_approval')->select('tahun_anggaran')
                                                       ->groupBy('tahun_anggaran')
                                                       ->pluck('tahun_anggaran');
            $lokasi        = Lokasi::all();
                                                       
            return view('/output/summary', compact('thnselected', 'Sbisnis', 'fs', 'lokasi', 'tahun', 'input_sb', 'input_d', 'input_l', 'input_t', 'input_f'));
        }
        else if ($request->isMethod('post')) 
        {
            $lokasi        = Lokasi::all();
            $Sbisnis       = StrategiBisnis::all();
            $fs            = Fase::all();
            $input_f       = 1;
            $tahun         = DB::table('file_approval')->select('tahun_anggaran')
                                                       ->groupBy('tahun_anggaran')
                                                       ->pluck('tahun_anggaran');
            $distrik       = Distrik::all();
            $lokasiall     = Lokasi::all();

            if ($request->input('strategi_bisnis') != NULL) {
                $input_sb = Input::get('strategi_bisnis');
                $dst      = DB::table('distrik')->where('strategi_bisnis_id', $input_sb)->pluck('id');
                $lokasi   = $lokasi->whereIn('distrik_id', $dst);
            }
            if ($request->input('distrik') != NULL) {
                $input_d  = Input::get('distrik');
                $lokasi   = $lokasi->where('distrik_id', $input_d);
            }
            if ($request->input('lokasi') != NULL) {
                $input_l  = Input::get('lokasi');
                $lokasi   = $lokasi->where('id', $input_l);
            }
            if ($request->input('tahun_anggaran') != NULL) {
                $input_t  = Input::get('tahun_anggaran');
            }
            if ($request->input('fase') != NULL) {
                $input_f  = Input::get('fase');
            }

            return view('/output/summary', compact('lokasiall', 'distrik', 'thnselected', 'Sbisnis', 'fs', 'lokasi', 'tahun', 'input_sb', 'input_d', 'input_l', 'input_t', 'input_f'));
        }
    }

    public function Ajax($id)
    {
        $ds = Distrik::where('strategi_bisnis_id', $id)->select("id","name")->get();

        return json_encode($ds);
    }

    public function Ajax2($id)
    {
        $lks = Lokasi::where('distrik_id', $id)->select("id","name")->get();

        return json_encode($lks);
    }
}
