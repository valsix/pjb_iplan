<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\StrategiBisnis;
use App\Entities\Distrik;
use App\Entities\Lokasi;
use App\Entities\Fase;
use App\Entities\User;
use App\Entities\Role;
use Excel;
use PDF;
use Illuminate\Support\Facades\DB;

class ProgramStrategisController extends Controller
{
  function cmp($a, $b)
  {
    if ($a["aki"] == $b["aki"]) {
      return 0;
    }
    return ($a["aki"] > $b["aki"]) ? -1 : 1;
  }

  function cmp2($a, $b)
  {
    if ($a["AN"] == $b["AN"]) {
      return 0;
    }
    return ($a["AN"] > $b["AN"]) ? -1 : 1;
  }

  function cmp3($a, $b)
  {
    if ($a["sum"] == $b["sum"]) {
      return 0;
    }
    return ($a["sum"] > $b["sum"]) ? -1 : 1;
  }

  public function getCombined($lokasi,$draft,$form,$noprk,$dprk,$aki){


    $hasil = DB::select("select e.* from excel_datas e
    join sheets s on s.id = e.sheet_id
    join file_imports f on f.id = e.file_import_id
    where s.name like '".$form."'
    and e.file_import_id = ".$draft."
    and e.lokasi_id = ".$lokasi."
    and (e.kolom = '".$noprk."' or e.kolom = '".$dprk."' or e.kolom = '".$aki."')");
    $combine = array();
    foreach($hasil as $h){
      if($h->kolom == $noprk){
        $kol="noprk";
      }
      elseif($h->kolom==$dprk){
        $kol="desprk";
      }
      else{
        $kol="aki";
      }
      $combine[$h->row.$draft][$kol] = $h->value;
    }
    return $combine;
  }

  public function Program_Strategis(Request $request)
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
    
    $fases = Fase::get();
    $input_tahun = $request->input('tahun_anggaran');
    $input_sb = $request->input('strategi_bisnis');
    $input_distrik = $request->input('distrik');
    $idistrik = $input_distrik;
    $input_lokasi = $request->input('lokasi');
    $ilokasi = $input_lokasi;
    $input_draft_form_6_reimburse = $request->input('draft_form_6_reimburse');
    $input_draft_form_6_rutin = $request->input('draft_form_6_rutin');
    $input_draft_form_10_pu = $request->input('draft_form_10_pu');
    $input_draft_form_10_pk = $request->input('draft_form_10_pk');
    $input_draft_form_10_pln = $request->input('draft_form_10_pln');


    if ($request->input('strategi_bisnis') != NULL) {
      $input_sb = DB::table('strategi_bisnis')->select('name')->where('id', $request->input('strategi_bisnis'))->get()[0];
    }
    if ($request->input('distrik') != NULL) {
      $input_distrik = DB::table('distrik')->select('name')->where('id', $request->distrik)->get()[0];
    }
    if ($request->input('lokasi') != NULL) {
      $input_lokasi = DB::table('lokasi')->select('name')->where('id', $request->lokasi)->get()[0];
    }
    if ($request->input('fase') != NULL) {
      $input_fase = DB::table('fases')->select('name')->where('id', $request->input('fase'))->get()[0];
    }
    if ($request->input('draft_form_6_reimburse') != NULL) {
      $versi6_reimburse = DB::table('file_imports')->select('draft_versi', 'name')->where('id',$request->input('draft_form_6_reimburse'))->get()[0];
    }
    if ($request->input('draft_form_6_rutin') != NULL) {
      $versi6_rutin = DB::table('file_imports')->select('draft_versi', 'name')->where('id', $request->input('draft_form_6_rutin'))->get()[0];
    }
    if ($request->input('draft_form_10_pu') != NULL) {
      $versi10_pu = DB::table('file_imports')->select('draft_versi', 'name')->where('id', $request->input('draft_form_10_pu'))->get()[0];
    }
    if ($request->input('draft_form_10_pk') != NULL) {
      $versi10_pk = DB::table('file_imports')->select('draft_versi', 'name')->where('id', $request->input('draft_form_10_pk'))->get()[0];
    }
    if ($request->input('draft_form_10_pln') != NULL) {
      $versi10_pln = DB::table('file_imports')->select('draft_versi', 'name')->where('id', $request->input('draft_form_10_pln'))->get()[0];
    }


    if($request->lokasi && $request->tahun_anggaran){
      //Program Investasi
      $combineall = array();
      if($input_draft_form_10_pu) {
        $combineall = array_merge($combineall, $this->getCombined($request->lokasi,$input_draft_form_10_pu,'I-Form 10','I','T','AU'));
      }
	  //by FFR 20190328 - dashboard program strategis ga muncul ketika tidak upload ai kit. kurang if di row 133 - 136
      if($input_draft_form_10_pk) {
	  $combineall = array_merge($combineall, $this->getCombined($request->lokasi,$input_draft_form_10_pk,'I-Form 10','H','S','AT'));
	  }
      if($input_draft_form_10_pln) {
        $combineall = array_merge($combineall, $this->getCombined($request->lokasi,$input_draft_form_10_pln,'I-Form 10','J','U','AR'));
      }
      usort($combineall,array($this, "cmp"));
      $tempCombine= $combineall;
      $combineall = array_slice($combineall,0, 10);

      //Program Proyek
      $hasil2_rutin = [];
      if($input_draft_form_6_rutin) {
        $hasil2_rutin = DB::select("select e.* from excel_datas e
        join sheets s on s.id = e.sheet_id
        join file_imports f on f.id = e.file_import_id
        where s.name like 'I-Form 6'
        and e.file_import_id = ".$input_draft_form_6_rutin."
        and e.lokasi_id = ".$request->lokasi."
        and (e.kolom = 'I' or e.kolom = 'T' or e.kolom = 'O' or e.kolom = 'S' or e.kolom = 'AN')
        and row in (
          select e.row from excel_datas e
          join sheets s on s.id = e.sheet_id
          join file_imports f on f.id = e.file_import_id
          where s.name like 'I-Form 6'
          and e.file_import_id = ".$input_draft_form_6_rutin."
          and e.lokasi_id = ".$request->lokasi."
          and e.kolom like 'L' and (value like '2O' OR value like '3O')
        )");
      }
      $hasil2_reimburse = [];
      if($input_draft_form_6_reimburse) {
        $hasil2_reimburse = DB::select("select e.* from excel_datas e
        join sheets s on s.id = e.sheet_id
        join file_imports f on f.id = e.file_import_id
        where s.name like 'I-Form 6'
        and e.file_import_id = ".$input_draft_form_6_reimburse."
        and e.lokasi_id = ".$request->lokasi."
        and (e.kolom = 'I' or e.kolom = 'T' or e.kolom = 'O' or e.kolom = 'S' or e.kolom = 'AN')
        and row in (
          select e.row from excel_datas e
          join sheets s on s.id = e.sheet_id
          join file_imports f on f.id = e.file_import_id
          where s.name like 'I-Form 6'
          and e.file_import_id = ".$input_draft_form_6_reimburse."
          and e.lokasi_id = ".$request->lokasi."
          and e.kolom like 'L' and (value like '2O' OR value like '3O')
        )");
      }

      $hasil2 = array_merge($hasil2_rutin, $hasil2_reimburse);

      $combine2 = array();
      foreach($hasil2 as $h2){
        $combine2[$h2->row.$h2->file_import_id]["$h2->kolom"] = $h2->value;
      }
      // $combine3 = $combine2;
      usort($combine2,array($this, "cmp2"));
      $combine2 = array_slice($combine2,0, 10);

      //Program Overhaul
      $hasil3_rutin = [];
      if($input_draft_form_6_rutin) {
        $hasil3_rutin = DB::select("select e.* from excel_datas e
        join sheets s on s.id = e.sheet_id
        join file_imports f on f.id = e.file_import_id
        where s.name like 'I-Form 6'
        and e.file_import_id = ".$input_draft_form_6_rutin."
        and e.lokasi_id = ".$request->lokasi."
        and (e.kolom = 'I' or e.kolom = 'T' or e.kolom = 'O' or e.kolom = 'S' or e.kolom = 'AN')
        and row in (
          select e.row from excel_datas e
          join sheets s on s.id = e.sheet_id
          join file_imports f on f.id = e.file_import_id
          where s.name like 'I-Form 6'
          and e.file_import_id = ".$input_draft_form_6_rutin."
          and e.lokasi_id = ".$request->lokasi."
          and e.kolom like 'L' and (value like '2N' OR value like '3N')
        )");
      }

      $hasil3_reimburse = [];
      if($input_draft_form_6_reimburse) {
        $hasil3_reimburse = DB::select("select e.* from excel_datas e
        join sheets s on s.id = e.sheet_id
        join file_imports f on f.id = e.file_import_id
        where s.name like 'I-Form 6'
        and e.file_import_id = ".$input_draft_form_6_reimburse."
        and e.lokasi_id = ".$request->lokasi."
        and (e.kolom = 'I' or e.kolom = 'T' or e.kolom = 'O' or e.kolom = 'S' or e.kolom = 'AN')
        and row in (
          select e.row from excel_datas e
          join sheets s on s.id = e.sheet_id
          join file_imports f on f.id = e.file_import_id
          where s.name like 'I-Form 6'
          and e.file_import_id = ".$input_draft_form_6_reimburse."
          and e.lokasi_id = ".$request->lokasi."
          and e.kolom like 'L' and (value like '2N' OR value like '3N')
        )");
      }

      $hasil3 = array_merge($hasil3_rutin, $hasil3_reimburse);

      $combine3 = array();
      foreach($hasil3 as $h3){
        $combine3[$h3->row.$h3->file_import_id]["$h3->kolom"] = $h3->value;
      }
      $prkInti = array();
      foreach($combine3 as $key => $c3){
        if(empty($prkInti[$c3['O']])){
          $prkInti[$c3['O']]["sum"] = $c3["AN"];
          $prkInti[$c3['O']]["key"] = $c3['O'];
        }
        else{
          $prkInti[$c3['O']]["sum"] += $c3["AN"];
        }
        $prkInti[$c3['O']]["inti"] = $c3['S'];
      }
      usort($prkInti,array($this, "cmp3"));
      $tempPRK = $prkInti;
      $prkInti = array_slice($prkInti,0, 10);

      //Graph1
      $countPP = sizeof($combine3);
      $countPO = sizeof($tempPRK);
      $countPI = sizeof($tempCombine);
      $allCount = $countPP + $countPO + $countPI;
      $percentPP = ($countPP/$allCount);
      $percentPO = ($countPO/$allCount);
      $percentPI = ($countPI/$allCount);

      //Graph2
      $sumPI=0;
      $sumPP=0;
      $sumPO=0;
      foreach($tempCombine as $key =>$value){
        $sumPI+=$value['aki'];
      }
	  //20190405 terjadi bug dashboard project = dashboard OH, kesalahan pada script combine2, sebelum perbaikan di isi combine3
      foreach($combine2 as $key =>$value){
        $sumPP+=$value['AN'];
      }
      foreach($tempPRK as $key =>$value){
        $sumPO+=$value['sum'];
      }

      if($request->download && $request->type){
          $judul='';
          switch ($request->type) {
            case 'excel':
                if($request->download=='program_strategis'){
                    $array['judul']='Report Program Strategis';
                    $array['judult1']='Tabel Program Investasi';
                    $array['judult2']='Tabel Program Proyek';
                    $array['judult3']='Tabel Program Overhaul';

                    $array['tahun']=['Tahun', ' ', $input_tahun];
                    $array['struktur_bisnis']=['Struktur Bisnis', ' ', $input_sb->name];
                    $array['distrik']=['Distrik', ' ', $input_distrik->name];
                    $array['lokasi']=['Lokasi', ' ', $input_lokasi->name];
                    $array['fase']=['Fase', ' ', $input_fase->name];
                    $array['draft']=['Draft', ' ', $versi6_reimburse->draft_versi];
                    $array['data']=$combineall;
                    $array['data2']=$combine2;
                    $array['data3']=$prkInti;


                    $array['totalPI'] = 0;
                    $array['totalPP']=0;
                    $array['totalPO']=0;
                    foreach($combineall as $key =>$value){
                      $array['totalPI']+=$value['aki'];
                    }
                    foreach($combine2 as $key =>$value){
                      $array['totalPP']+=$value['AN'];
                    }
                    foreach($prkInti as $key =>$value){
                      $array['totalPO']+=$value['sum'];
                    }
                }
                Excel::create($array['judul'], function($excel) use ($array) {

                    $excel->setTitle('Report Program Strategis');
                    $excel->setCreator('Laravel-5.5')->setCompany('PJB');
                    $excel->sheet('Excel sheet', function($sheet) use ($array) {

                        $sheet->row(1, $array['tahun']);
                        $sheet->row(2, $array['struktur_bisnis']);
                        $sheet->row(3, $array['distrik']);
                        $sheet->row(4, $array['lokasi']);
                        $sheet->row(5, $array['fase']);
                        $sheet->row(6, $array['draft']);

                        $sheet->row(7, array('', '',''));
                        $sheet->row(8, array('', '',''));

                        $sheet->row(9, function ($row) {
                            $row->setFontFamily('Arial');
                            $row->setFontSize(15);
                            $row->setFontWeight('bold');
                        });
                        //Tabel PI
                        $sheet->mergeCells("A9".":C9");
                        $sheet->row(9, array($array['judult1'], '', ''));
                        $sheet->row(10, array('', '',''));

                        $sheet->row(11, function ($row) {
                            $row->setFontFamily('Arial');
                            $row->setFontSize(12);
                            $row->setFontWeight('bold');
                        });
                        $sheet->row(11, array('No', 'No PRK','Program', 'AKI'));
                        foreach ($array['data'] as $i => $rows) {
                            $rows = array_merge(array('nomor' => $i+1), $rows);
                            $sheet->row($i+12, $rows);
                        }
                        $sheet->row(22, array('', '','Total', $array['totalPI']));

                        //Tabel PP
                        $sheet->row(24, function ($row) {
                            $row->setFontFamily('Arial');
                            $row->setFontSize(15);
                            $row->setFontWeight('bold');
                        });
                        $sheet->mergeCells("A24".":C24");
                        $sheet->row(24, array($array['judult2'], '', ''));
                        $sheet->row(25, array('', '',''));

                        $sheet->row(25, function ($row) {
                            $row->setFontFamily('Arial');
                            $row->setFontSize(12);
                            $row->setFontWeight('bold');
                        });
                        $sheet->row(25, array('No', 'No PRK','Program', 'Anggaran'));
                        foreach ($array['data2'] as $i => $rows) {
                            $rows = array_merge(array('nomor' => $i+1), $rows);
                            unset($rows['O']);
                            unset($rows['T']);
                            $sheet->row($i+26, $rows);
                        }
                        $sheet->row(36, array('', '','Total', $array['totalPP']));

                        //Tabel PO
                        $sheet->row(38, function ($row) {
                            $row->setFontFamily('Arial');
                            $row->setFontSize(15);
                            $row->setFontWeight('bold');
                        });
                        $sheet->mergeCells("A9".":C9");
                        $sheet->row(38, array($array['judult3'], '', ''));
                        $sheet->row(39, array('', '',''));

                        $sheet->row(40, function ($row) {
                            $row->setFontFamily('Arial');
                            $row->setFontSize(12);
                            $row->setFontWeight('bold');
                        });
                        $sheet->row(40, array('No', 'No PRK','Program', 'Anggaran'));
                        foreach ($array['data3'] as $i => $rows) {
                            $temp = $rows["sum"];
                            unset($rows["sum"]);
                            $rows += array("sum" => $temp);
                            $rows = array_merge(array('nomor' => $i+1), $rows);
                            $sheet->row($i+41, $rows);
                        }
                        $sheet->row(51, array('', '','Total', $array['totalPO']));

                    });
                })->export('xlsx');//Download Excel, Array To Excel
            break;
              case 'pdf':
                  $fill=[];
                  if($request->download=='program_investasi'){
                      $fill=array($input_tahun, $input_sb, $input_distrik , $input_lokasi, $input_fase,$versi6_rutin,$versi6_reimburse,$versi10_pk,$versi10_pu,$versi10_pln);
                      $judul='Report Program Strategis';
                  }
                //  $pdf=PDF::loadView('output/program-investasi-pdf', compact('sumPO','sumPP','sumPI','percentPI','percentPO','percentPP','countPP','countPO','countPI','allCount','combineall','combine2','prkInti','judul', 'fill'));
                  //return $pdf->download('Report Program Strategis ' .$input_tahun.'.pdf'); //Download PDF, HTML To PDF
                  return view('output/program-investasi-pdf',compact('sumPO','sumPP','sumPI','percentPI','percentPO','percentPP','countPP','countPO','countPI','allCount','combineall','combine2','prkInti','judul', 'fill'));
              break;
              default:
                  return redirect($rq->url());
                  break;
          }
      }
    }
    return view('output/program-strategis', compact ('sumPO','sumPP','sumPI','percentPI','percentPO','percentPP','countPP','countPO','countPI','allCount','fases','sb', 'input_tahun', 'input_sb', 'input_distrik', 'input_lokasi','combineall','combine2','prkInti',
    'input_draft_form_6_reimburse',
    'input_draft_form_6_rutin',
    'input_draft_form_10_pu',
    'input_draft_form_10_pk',
    'input_draft_form_10_pln',
    'versi6_reimburse',
    'versi6_rutin',
    'versi10_pu',
    'versi10_pk',
    'versi10_pln',
    'idistrik',
    'ilokasi',
    'input_fase'));
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

  public function ajax_draft_form_6_reimburse($id_lokasi, $id_tahun)
  {
    $draft_form_6_reimburse = DB::select("select distinct f.id, f.draft_versi
    from file_imports f
    join templates t on f.template_id = t.id
    join excel_datas e on e.file_import_id = f.id
    where t.jenis_id=2 and e.lokasi_id = ".$id_lokasi." and t.tahun= ".$id_tahun."
    group by f.id, f.draft_versi;");

    return json_encode($draft_form_6_reimburse);
  }

  public function ajax_draft_form_6_rutin($id_lokasi, $id_tahun)
  {
    $draft_form_6_rutin = DB::select("select distinct f.id, f.draft_versi
    from file_imports f
    join templates t on f.template_id = t.id
    join excel_datas e on e.file_import_id = f.id
    where t.jenis_id=3 and e.lokasi_id = ".$id_lokasi." and t.tahun= ".$id_tahun."
    group by f.id, f.draft_versi;");

    return json_encode($draft_form_6_rutin);
  }

  public function ajax_draft_form_10_pengembangan_usaha($id_lokasi, $id_tahun)
  {
    $draft_form_10_pu = DB::select("select distinct f.id, f.draft_versi
    from file_imports f
    join templates t on f.template_id = t.id
    join excel_datas e on e.file_import_id = f.id
    where t.jenis_id=4 and e.lokasi_id = ".$id_lokasi." and t.tahun= ".$id_tahun."
    group by f.id, f.draft_versi;");

    return json_encode($draft_form_10_pu);
  }

  public function ajax_draft_form_10_penguatan_kit($id_lokasi, $id_tahun)
  {
    $draft_form_10_pk = DB::select("select distinct f.id, f.draft_versi
    from file_imports f
    join templates t on f.template_id = t.id
    join excel_datas e on e.file_import_id = f.id
    where t.jenis_id=5 and e.lokasi_id = ".$id_lokasi." and t.tahun= ".$id_tahun."
    group by f.id, f.draft_versi;");

    return json_encode($draft_form_10_pk);
  }

  public function ajax_draft_form_10_pln($id_lokasi, $id_tahun)
  {
    $draft_form_10_pln = DB::select("select distinct f.id, f.draft_versi
    from file_imports f
    join templates t on f.template_id = t.id
    join excel_datas e on e.file_import_id = f.id
    where t.jenis_id=6 and e.lokasi_id = ".$id_lokasi." and t.tahun= ".$id_tahun."
    group by f.id, f.draft_versi;");

    return json_encode($draft_form_10_pln);
  }

}
