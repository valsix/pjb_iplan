<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\StrategiBisnis;
use App\Entities\Distrik;
use App\Entities\Lokasi;
use App\Entities\Fase;
use App\Entities\User;
use App\Entities\Role;
use Illuminate\Support\Facades\DB;
use Excel;
use PDF;

class RencanaKinerjaController extends Controller
{
  public function Rencana_Kinerja(Request $request)
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
    $idraft = $request->input('draft_rkau');
    $input_lokasi = $request->input('lokasi');
    $ilokasi = $input_lokasi;

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
    if ($request->input('draft_rkau') != NULL) {
      $versi_rkau = DB::table('file_imports')->select('draft_versi', 'name')->where('id',$request->input('draft_rkau'))->get()[0];
    }
    if($request->draft_rkau){
      $hasil = DB::select("select e.*
        FROM excel_datas e
        JOIN file_imports f ON e.file_import_id = f.id
        JOIN sheets s ON e.sheet_id = s.id
        WHERE e.lokasi_id = ".$request->lokasi."
        AND f.tahun = ".$request->tahun_anggaran."
        AND file_import_id = ".$request->draft_rkau."
        AND s.name LIKE 'I-Rencana Kinerja'
        AND e.row >= 9");
        $combine = array();
        foreach($hasil as $h2){
          $combine[$h2->row]["$h2->kolom"] = $h2->value;
        }
        $combine = array_slice($combine,0, 10);

        if($request->download && $request->type){
            $judul='';
            switch ($request->type) {
              case 'excel':
                  if($request->download=='rencana_kinerja'){
                      $array['judul']='Report Kinerja';
                      $array['judult1']='Rencana Kinerja';

                      $array['tahun']=['Tahun', ' ', $input_tahun];
                      $array['struktur_bisnis']=['Struktur Bisnis', ' ', $input_sb->name];
                      $array['distrik']=['Distrik', ' ', $input_distrik->name];
                      $array['lokasi']=['Lokasi', ' ', $input_lokasi->name];
                      $array['fase']=['Fase', ' ', $input_fase->name];
                      $array['draft']=['Draft', ' ', $versi_rkau->draft_versi];
                      $comb= array();
                      foreach($combine as $c){
                        if(!array_key_exists('C',$c) || !array_key_exists('D',$c) || !array_key_exists('E',$c) || !array_key_exists('F',$c) || !array_key_exists('G',$c) || !array_key_exists('H',$c)){
                            break;
                          }
                          array_push($comb,$c);
                        }
                      $array['data']=$comb;

                  }
                  Excel::create($array['judul'], function($excel) use ($array) {

                      $excel->setTitle('Report Rencana Kinerja');
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
                          $sheet->mergeCells("A9".":C9");
                          $sheet->row(9, array($array['judult1'], '', ''));
                          $sheet->row(10, array('', '',''));

                          $sheet->mergeCells("A11".":B12");
                          $sheet->row(11, array('Unit Existing', '','RKAP','Prak-real', 'RKAP','%RKAP n', '% RKAP n'));
                          $sheet->row(12, array('', '','n-1','n-1','n','THD RKAP n-1','THD Prak Real n-1'));
                          $sheet->mergeCells("A13".":B13");
                          $sheet->row(13, array('1','','2','3','4','5=4/2','6=4/3'));
                          foreach ($array['data'] as $i => $rows) {
                              $sheet->row($i+14, $rows);
                          }
                      });
                  })->export('xlsx');//Download Excel, Array To Excel
              break;
                case 'pdf':
                    $fill=[];
                    if($request->download=='rencana_kinerja'){
                      $comb= array();
                      foreach($combine as $c){
                        if(!array_key_exists('C',$c) || !array_key_exists('D',$c) || !array_key_exists('E',$c) || !array_key_exists('F',$c) || !array_key_exists('G',$c) || !array_key_exists('H',$c)){
                            break;
                          }
                          array_push($comb,$c);
                        }
                        $fill=array($input_tahun, $input_sb, $input_distrik , $input_lokasi, $input_fase,$versi_rkau);
                        $judul='Report Rencana Kinerja';
                    }
                    $pdf=PDF::loadView('output/rencana-kinerja-pdf', compact('comb','judul', 'fill'));
                    return $pdf->download('Report Rencana Kinerja' .$input_tahun.'.pdf'); //Download PDF, HTML To PDF
                break;
                default:
                    return redirect($rq->url());
                    break;
            }
        }
      }

      return view('output/rencana-kinerja', compact('input_fase','versi_rkau','idraft','ilokasi','idistrik','combine','fases','sb', 'input_tahun', 'input_sb', 'input_distrik', 'input_lokasi'));
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
  }
