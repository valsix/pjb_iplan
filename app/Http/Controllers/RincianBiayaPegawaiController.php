<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use Session;
use App\Entities\StrategiBisnis;
use App\Entities\Distrik;
use App\Entities\Lokasi;
use App\Entities\ExcelData;
use App\Entities\Sheet;
use App\Entities\Fase;
use App\Entities\Template;
use App\Entities\User;
use App\Entities\Role;
use Illuminate\Support\Facades\DB;
use PDF;
use Excel;

class RincianBiayaPegawaiController extends Controller
{
    public function Rincian_Biaya_Pegawai(Request $request)
    {
      $user_id = session('user_id');
      $user = User::find($user_id);
      $role_id = session('role_id');
      $role = Role::find($role_id);

      //kantor pusat
      if($role->is_kantor_pusat) {
        $sb = StrategiBisnis::all();
      }
      else {
        $sb = StrategiBisnis::where('id', $user->distrik->strategi_bisnis->id)->get();
      }

        $fase = Fase::all();
        $tahun = Template::select('tahun')->where('jenis_id', 7)->orWhere('jenis_id',1)->distinct()->get();

        $input_tahun = $request->input('tahun_anggaran');
        $input_sb = $request->input('strategi_bisnis');
        $input_distrik = $request->input('distrik');
        $input_lokasi = $request->input('lokasi');
        $input_fase = $request->input('fase');

        $input_draft = $request->input('draft_rkau');

        if ($request->input('strategi_bisnis') != NULL) {
            $input_sb = DB::table('strategi_bisnis')->select('name', 'id')->where('id', $request->input('strategi_bisnis'))->get()[0];
            // dd($input_sb);
            $distrik = Distrik::select('name','id')->where('strategi_bisnis_id',$input_sb->id)->get();
        }
        if ($request->input('distrik') != NULL) {
            $input_distrik = DB::table('distrik')->select('name', 'id')->where('id', $request->distrik)->get()[0];
            $lokasi = Lokasi::select('name','id')->where('distrik_id',$input_distrik->id)->get();
        }
        if ($request->input('lokasi') != NULL) {
            $input_lokasi = DB::table('lokasi')->select('name', 'id')->where('id', $request->lokasi)->get()[0];
        }
        if ($request->input('fase') != NULL) {
            $input_fase = DB::table('fases')->select('name', 'id')->where('id', $request->fase)->get()[0];
        }
        if ($request->input('draft_rkau') != NULL) {
            $input_draft = DB::table('file_imports')->select('draft_versi', 'id', 'name')->where('id', $request->draft_rkau)->get()[0];
            $drafts = $this->query_draft($input_sb->name, $input_lokasi->id, $input_tahun);
        }
        // dd($input_fase);

        if ($input_lokasi != NULL && $input_tahun != NULL && $input_draft != NULL) {

            $count = DB::table('excel_datas')->join('file_imports','file_imports.id','=','excel_datas.file_import_id')->join('sheets','sheets.id','=','excel_datas.sheet_id')->where('sheets.name', 'like', 'I-PEG')->where('excel_datas.lokasi_id', $request->input('lokasi'))->where('excel_datas.row','>','12')->where('excel_datas.kolom','=','D')->where('file_imports.tahun', $request->input('tahun_anggaran'))->where('file_imports.id', $request->input('draft_rkau'))->orderBy('excel_datas.row', 'ASC')->count();

            $number = DB::table('excel_datas')->join('file_imports','file_imports.id','=','excel_datas.file_import_id')->join('sheets','sheets.id','=','excel_datas.sheet_id')->where('sheets.name', 'like', 'I-PEG')->where('excel_datas.lokasi_id', $request->input('lokasi'))->where('excel_datas.row','>','12')->where('excel_datas.kolom','=','D')->where('file_imports.tahun', $request->input('tahun_anggaran'))->where('file_imports.id', $request->input('draft_rkau'))->orderBy('excel_datas.row', 'ASC')->get();
            // dd($number);

            $noprk = DB::table('excel_datas')->join('file_imports','file_imports.id','=','excel_datas.file_import_id')->join('sheets','sheets.id','=','excel_datas.sheet_id')->where('sheets.name', 'like', 'I-PEG')->where('excel_datas.lokasi_id', $request->input('lokasi'))->where('excel_datas.row','>','12')->where('excel_datas.kolom','=','E')->where('file_imports.tahun', $request->input('tahun_anggaran'))->where('file_imports.id', $request->input('draft_rkau'))->orderBy('excel_datas.row', 'ASC')->get();
            // dd($noprk);


            $rincian = DB::table('excel_datas')->select('excel_datas.row', 'excel_datas.kolom', 'excel_datas.value')->join('file_imports','file_imports.id','=','excel_datas.file_import_id')->join('sheets','sheets.id','=','excel_datas.sheet_id')->where('sheets.name', 'like', 'I-PEG')->where('excel_datas.lokasi_id', $request->input('lokasi'))->where('excel_datas.row','>','12')->where('excel_datas.kolom','=','F')->where('file_imports.tahun', $request->input('tahun_anggaran'))->where('file_imports.id', $request->input('draft_rkau'))->orderBy('excel_datas.row', 'ASC')->get();
            // dd($rincian);
            // dd($rincianExcel);
            // array ke 5 30 40 41, sub bab A, sub bab B, sub bab C, Jumlah Biaya
            $newRincian = [];
            $checkIndex = [];

            for ($i = 0 ; $i < count($rincian) ; $i) {
                if (strpos($rincian[$i]->value, 'Sub Jumlah') !== False || strpos($rincian[$i]->value, 'JUMLAH') !== False) {
                    array_push($checkIndex, $i);
                    $i++;
                }
                else {
                    array_push($newRincian, $rincian[$i]->value);
                    $i++;
                }
            }
            // dd($newRincian);
            $countA = $checkIndex[0]; //5
            $countB = $checkIndex[1]; //30
            $countC = $checkIndex[2]; //40
            $countD = $checkIndex[3];

            $estimasi2017 = DB::table('excel_datas')->join('file_imports','file_imports.id','=','excel_datas.file_import_id')->join('sheets','sheets.id','=','excel_datas.sheet_id')->where('sheets.name', 'like', 'I-PEG')->where('excel_datas.lokasi_id', $request->input('lokasi'))->where('excel_datas.row','>','12')->where('excel_datas.kolom','=','G')->where('file_imports.tahun', $request->input('tahun_anggaran'))->where('file_imports.id', $request->input('draft_rkau'))->orderBy('excel_datas.row', 'ASC')->get();

            $blankSpace = ' ';
            $numberExcel = [];
            foreach ($number as $key => $value) {
                array_push($numberExcel, $value->value);
            }
            array_splice($numberExcel, $countA, 0, $blankSpace);
            array_splice($numberExcel, $countA+1, 0, $blankSpace);
            array_splice($numberExcel, $countB+1, 0, $blankSpace);
            array_splice($numberExcel, $countB+2, 0, $blankSpace);
            array_splice($numberExcel, $countC+2, 0, $blankSpace);
            array_splice($numberExcel, $countC+3, 0, $blankSpace);
            array_splice($numberExcel, $countC+4, 0, $blankSpace);

            $noprkExcel = [];
            foreach ($noprk as $key => $value) {
                array_push($noprkExcel, $value->value);
            }
            array_splice($noprkExcel, $countA, 0, $blankSpace);
            array_splice($noprkExcel, $countA+1, 0, $blankSpace);
            array_splice($noprkExcel, $countB+1, 0, $blankSpace);
            array_splice($noprkExcel, $countB+2, 0, $blankSpace);
            array_splice($noprkExcel, $countC+2, 0, $blankSpace);
            array_splice($noprkExcel, $countC+3, 0, $blankSpace);
            array_splice($noprkExcel, $countC+4, 0, $blankSpace);

            $rincianExcel = [];
            foreach ($rincian as $key => $value) {
                array_push($rincianExcel, $value->value);
            }
            array_splice($rincianExcel, 6, 0, $blankSpace);
            array_splice($rincianExcel, 32, 0, $blankSpace);
            array_splice($rincianExcel, 43, 0, $blankSpace);


            $estimasiExcel = [];
            $estimasiExcelFix = [];
            foreach ($estimasi2017 as $key => $value) {
                array_push($estimasiExcel, $value->value);
            }
            array_splice($estimasiExcel, $countA+1, 0, $blankSpace);
            array_splice($estimasiExcel, $countB+2, 0, $blankSpace);
            array_splice($estimasiExcel, $countC+3, 0, $blankSpace);

            foreach ($estimasiExcel as $key => $value) {
                $jon = number_format(round($value),0,".",".");
                if ($jon == 0) {
                    $jon = ' ';
                }
                array_push($estimasiExcelFix, $jon);
            }

            $roundEstimasi = [];
            foreach ($estimasi2017 as $key => $value) {
                $round = round($value->value);
                array_push($roundEstimasi, $round);
            }

            $rkap2018 = DB::table('excel_datas')->join('file_imports','file_imports.id','=','excel_datas.file_import_id')->join('sheets','sheets.id','=','excel_datas.sheet_id')->where('sheets.name', 'like', 'I-PEG')->where('excel_datas.lokasi_id', $request->input('lokasi'))->where('excel_datas.row','>','12')->where('excel_datas.kolom','=','H')->where('file_imports.tahun', $request->input('tahun_anggaran'))->where('file_imports.id', $request->input('draft_rkau'))->orderBy('excel_datas.row', 'ASC')->get();
            $rkapExcel = [];
            $rkapExcelFix = [];
            foreach ($rkap2018 as $key => $value) {
                array_push($rkapExcel, $value->value);
            }
            array_splice($rkapExcel, $countA+1, 0, $blankSpace);
            array_splice($rkapExcel, $countB+2, 0, $blankSpace);
            array_splice($rkapExcel, $countC+3, 0, $blankSpace);
            // $rkapExcel = number_format(round($rkapExcel));
            foreach ($rkapExcel as $key => $value) {
                $jon = number_format(round($value),0,".",".");
                if ($jon == 0) {
                    $jon = ' ';
                }
                array_push($rkapExcelFix, $jon);
            }
            // dd($rkapExcel);

            $roundRkap = [];
            foreach ($rkap2018 as $key => $value) {
                $round2 = round($value->value);
                array_push($roundRkap, $round2);
            }

            unset($roundEstimasi[$countA]);
            unset($roundEstimasi[$countB]);
            unset($roundEstimasi[$countC]);
            unset($roundEstimasi[$countD]);
            $fixEstimasi = array_values($roundEstimasi);

            unset($roundRkap[$countA]);
            unset($roundRkap[$countB]);
            unset($roundRkap[$countC]);
            unset($roundRkap[$countD]);
            $fixRkap = array_values($roundRkap);

            // $combineall = array();
            // $combineall = array_slice($correct,0, 10);
            $judul = '';

            if($request->download && $request->type){
                $judul='';
                if($request->type=='pdf'){
                    $fill = [];
                    $judul = "Rincian Biaya Pegawai";
                    $fill=array($request->get('tahun_anggaran'), $input_sb->name, $input_distrik->name, $input_lokasi->name, $input_fase->name , $input_draft->draft_versi);
                    $judul='Table Rincian Biaya Pegawai';

                    $pdf=PDF::loadView('output/rincian-biaya-pegawai-pdf', compact('sb', 'fase', 'input_sb', 'input_distrik', 'input_lokasi', 'input_tahun','input_fase', 'input_draft', 'noprk', 'rincian', 'rincianCount', 'roundEstimasi', 'roundRkap', 'draft', 'number', 'count', 'countA', 'countB', 'countC', 'countD', 'newRincian', 'fixEstimasi', 'fixRkap', 'tahun', 'distrik', 'lokasi', 'drafts', 'fill', 'judul'));
                    return $pdf->download('Report Rincian Biaya Pegawai ' .$input_tahun.'.pdf');
                }
                else if ($request->type=='excel') {
                    $array['judul']='Rincian Biaya Pegawai';
                    $array['judult1']='Rincian Biaya Pegawai';
                    $array['tahun']=[' ','Tahun', $input_tahun];
                    $array['struktur_bisnis']=[' ','Struktur Bisnis', $input_sb->name];
                    $array['distrik']=[' ','Distrik', $input_distrik->name];
                    $array['lokasi']=[' ','Lokasi', $input_lokasi->name];
                    $array['fase']=[' ','Fase', $input_fase->name];
                    $array['jenis']=[' ','Jenis Draft', $input_fase->name];
                    $array['draft']=[' ','Draft', $input_draft->draft_versi];
                    // dd($array['tahun']);

                    $arrayNew = [];

                    $tmp = [];
                    $array1 = [' ', ' ', 'RINCIAN', 'PRAK REAL 2017', 'RKAP 2018'];
                    $arrayList = [];
                    $arrayList = ['No', 'No PRK', '1', '2', '3'];
                    // dd($number);
                    $tmp[0] = $array1;
                    $tmp[1] = $arrayList;

                    for ($i = 0 ; $i < count($numberExcel) ; $i++) {
                        $tmp[] = array(
                            'No' => $numberExcel[$i],
                            'NoPRK' => $noprkExcel[$i],
                            '1' => $rincianExcel[$i],
                            '2' => $estimasiExcelFix[$i],
                            '3' => $rkapExcelFix[$i]
                        );
                    }

                    $array['tmp']=$tmp;
                    // dd($array);

                    Excel::create('Report Rincian Biaya Pegawai', function($excel) use($array) {
                        $excel->setTitle('Report Rincian Biaya Pegawai');
                        $excel->setCreator('Laravel')->setCompany('Profio', 'PJB');
                        $excel->setDescription('Rincian Biaya Pegawai');

                        $excel->sheet('Sheet1', function($sheet) use ($array) {
                            $sheet->row(1, $array['tahun']);
                            $sheet->row(2, $array['struktur_bisnis']);
                            $sheet->row(3, $array['distrik']);
                            $sheet->row(4, $array['lokasi']);
                            $sheet->row(5, $array['fase']);
                            $sheet->row(6, $array['jenis']);
                            $sheet->row(7, $array['draft']);

                            $sheet->row(8, array('', '',''));
                            $sheet->row(9, array('', '',''));

                            foreach ($array['tmp'] as $key => $value) {
                                $sheet->row($key+10, $value);
                            }


                            // $sheet->sheet('Sheet1', function($sheet2) use ($tmp) {
                            //     $sheet2->fromArray($tmp, null, 'A1', false, false);
                            // });
                        });


                    })->download('xlsx');
                }
            }
            else {
                return view('output/rincian-biaya-pegawai', compact('sb', 'fase', 'input_sb', 'input_distrik', 'input_lokasi', 'input_tahun','input_fase', 'input_draft', 'noprk', 'rincian', 'rincianCount', 'roundEstimasi', 'roundRkap', 'draft', 'number', 'count', 'countA', 'countB', 'countC', 'countD', 'newRincian', 'fixEstimasi', 'fixRkap', 'tahun', 'distrik', 'lokasi', 'drafts'));
            }

        }

        return view('output/rincian-biaya-pegawai', compact('sb', 'fase', 'input_sb', 'input_distrik', 'input_lokasi', 'input_tahun','input_fase', 'input_draft', 'noprk', 'rincian', 'rincianCount', 'roundEstimasi', 'roundRkap', 'draft', 'number', 'count', 'countA', 'countB', 'countC', 'countD', 'newRincian', 'fixEstimasi', 'fixRkap', 'tahun', 'distrik', 'lokasi', 'drafts'));
    }

    public function Ajax($id)
    {
      $user_id = session('user_id');
      $user = User::find($user_id);
      $role_id = session('role_id');
      $role = Role::find($role_id);

      //kantor pusat
      if($role->is_kantor_pusat) {
        $ds = Distrik::where('strategi_bisnis_id', $id)->select("name","id")->get();
      }
      else {
        $ds = Distrik::where('id', $user->distrik_id)->select("name","id")->get();
      }

      return json_encode($ds);
    }

    public function myformAjax2($id)
    {
    	$lokasi = Lokasi::where('distrik_id', $id)->select("name", "id")->get();

    	return json_encode($lokasi);
    }

    public function ajax_draft_rkau($id_lokasi, $id_tahun)
    {
        $draft_rkau = DB::select("select distinct f.id, f.draft_versi
                                    from file_imports f
                                    join templates t on f.template_id = t.id
                                    join excel_datas e on e.file_import_id = f.id
                                    where t.jenis_id=1 and e.lokasi_id = ".$id_lokasi." and t.tahun= ".$id_tahun."
                                    group by f.id, f.draft_versi;");

        return json_encode($draft_rkau);
    }

    public function query_draft($strategi_bisnis, $id_lokasi, $id_tahun){
        $draft= DB::select("select distinct f.id, f.draft_versi
                            from file_imports f
                            join templates t on f.template_id = t.id
                            join excel_datas e on e.file_import_id = f.id
                            where t.jenis_id=1 and e.lokasi_id = ".$id_lokasi." and t.tahun= ".$id_tahun."
                            group by f.id, f.draft_versi;");
        return $draft;
    }
}
