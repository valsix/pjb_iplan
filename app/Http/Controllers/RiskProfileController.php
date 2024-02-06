<?php

namespace App\Http\Controllers;

// use Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Models\TingkatKemungkinan;
use App\Models\TingkatDampak;
use App\Models\LevelResiko;
use App\Entities\StrategiBisnis;
use App\Entities\Distrik;
use App\Entities\Lokasi;
use App\Entities\Fase;
use App\Entities\User;
use App\Entities\Role;
use DB;
use Excel;
use PDF;

class RiskProfileController extends Controller
{

  public function Risk_Profile(Request $request)
  {
    $user_id = session('user_id');
    $user = User::find($user_id);
    $role_id = session('role_id');
    $role = Role::find($role_id);
    // dd($user->distrik->strategi_bisnis);
    // $tingkat_kemungkinan = TingkatKemungkinan::all();
    // $tingkat_dampak = TingkatDampak::all();
    // $level_resiko = LevelResiko::all();
    $data['tingkat_kemungkinan'] = TingkatKemungkinan::all();
    $data['tingkat_dampak'] = TingkatDampak::all();
    $data['level_resiko'] = LevelResiko::all();
    $data['fase'] = Fase::get();
    //kantor pusat
    if($role->is_kantor_pusat) {
      $sb = StrategiBisnis::all();
    }
    else {
      $sb = StrategiBisnis::where('id', $user->distrik->strategi_bisnis->id)->get();  
    }

    $idraft= $request->input('draft');
    $ilokasi= $request->input('lokasi');
    $idistrik =$request->input('distrik');
    if ($request->input('tahun_anggaran') != NULL) {
      $input_tahun = $request->input('tahun_anggaran');
    }

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

    if ($request->input('draft') != NULL) {
      $versi = DB::table('file_imports')->select('draft_versi', 'name')->where('id',$request->input('draft'))->get()[0];
    }



    if(isset($request->draft)){
      //dd($request->draft);

      $hasil2 = DB::select("select e.kolom, e.row, e.value
      from excel_datas e
      join sheets s on s.id = e.sheet_id
      where e.file_import_id = ".$request->draft."
      and s.name like 'I-Risk Register'");
      $combineall = array();
      foreach($hasil2 as $h2){
        $combineall[$h2->row][$h2->kolom] = $h2->value;
      }
      $correct = array();
      foreach($combineall as $ca){
          if(array_key_exists("A",$ca) && array_key_exists("B",$ca) && array_key_exists("C",$ca)){
            array_push($correct,$ca);
          }
      }
      $combineall = array();
      $combineall = array_slice($correct,0, 10);
      if($request->download && $request->type){
          $judul='';
          switch ($request->type) {
            case 'excel':
                if($request->download=='risk_profile'){
                    $array['judul']='Risk Profile';
                    $array['judult1']='Risk profile';

                    $array['tahun']=['Tahun', ' ', $input_tahun];
                    $array['struktur_bisnis']=['Struktur Bisnis', ' ', $input_sb->name];
                    $array['distrik']=['Distrik', ' ', $input_distrik->name];
                    $array['lokasi']=['Lokasi', ' ', $input_lokasi->name];
                    $array['fase']=['Fase', ' ', $input_fase->name];
                    $array['draft']=['Draft', ' ', $versi->draft_versi];
                    $array['data']=$combineall;

                }
                Excel::create($array['judul'], function($excel) use ($array) {

                    $excel->setTitle('Report Risk Profile');
                    $excel->setCreator('Laravel-5.5')->setCompany('PJB');
                    $excel->sheet('Excel sheet', function($sheet) use ($array) {

                        $sheet->row(1, $array['tahun']);
                        $sheet->row(2, $array['struktur_bisnis']);
                        $sheet->row(3, $array['distrik']);
                        $sheet->row(4, $array['lokasi']);
                        $sheet->row(5, $array['fase']);
                        $sheet->row(6, $array['jenis']);
                        $sheet->row(7, $array['draft']);

                        $sheet->row(8, array('', '',''));
                        $sheet->row(9, array('', '',''));

                        $sheet->row(10, function ($row) {
                            $row->setFontFamily('Arial');
                            $row->setFontSize(15);
                            $row->setFontWeight('bold');
                        });
                        $sheet->mergeCells("A9".":C9");
                        $sheet->row(10, array($array['judult1'], '', ''));
                        $sheet->row(11, array('', '',''));

                        $sheet->row(12, array('No', 'Risk Tag','Risk Event'));
                        foreach ($array['data'] as $i => $rows) {
                          unset($rows['D']);
                          unset($rows['E']);
                          unset($rows['F']);
                          $sheet->row($i+13, $rows);
                        }
                    });
                })->export('xlsx');//Download Excel, Array To Excel
           break;
              case 'pdf':
                  $fill=[];
                  if($request->download=='risk_profile'){
                      $fill=array($input_tahun, $input_sb, $input_distrik , $input_lokasi, $input_fase,$versi);
                      $judul='Report Rencana Kinerja';
                  }
                  $pdf=PDF::loadView('output/risk-profile-pdf',$data, compact('combineall','judul', 'fill'));
                  return $pdf->download('Report Risk Profile' .$input_tahun.'.pdf'); //Download PDF, HTML To PDF
              break;
              default:
                  return redirect($rq->url());
                  break;
          }
      }
    }

    return view('output/risk-profile', $data, compact('sb','combineall',
    'input_fase','versi','idraft','ilokasi','idistrik','fases','input_tahun', 'input_sb', 'input_distrik', 'input_lokasi'
    ));
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

  public function myformAjax3($jenis,$lokasi,$tahun)
  {
    $draft = DB::select("select distinct f.id, f.draft_versi
    from file_imports f
    join templates t on f.template_id = t.id
    join excel_datas e on e.file_import_id = f.id
    where t.jenis_id=".$jenis." and e.lokasi_id = ".$lokasi." and f.tahun = ".$tahun."");

    return json_encode($draft);
  }

}
