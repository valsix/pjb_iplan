<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Entities\StrategiBisnis; 
use App\Entities\Fase; 
use App\Entities\Distrik;
use App\Entities\Lokasi;
use App\Entities\User;
use App\Entities\Role;
use DB;
use Input;
use Excel;
use PDF;

class LrController extends Controller
{
    public function LR(Request $rq) 
    {
        // dd($rq->all());
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

        $fs = Fase::all();
        $tahun1 = $rq->input('tahun1');
        $tahun2 = $tahun1-1;

        // ambil nilai request input
        if ($rq->input('strategi_bisnis1') != NULL) {
            $input_sb = DB::table('strategi_bisnis')->select('name','id')->where('id', $rq->input('strategi_bisnis1'))->get()[0];
        }
        if ($rq->input('distrik1') != NULL) {
            $input_distrik = DB::table('distrik')->select('name','id')->where('id', $rq->distrik1)->get()[0];
        }
        if ($rq->input('lokasi1') != NULL) {
            $input_lokasi = DB::table('lokasi')->select('name','id')->where('id', $rq->lokasi1)->get()[0];
        }
        if ($rq->input('fase1') != NULL) {
            $input_fase = DB::table('fases')->select('name','id')->where('id', $rq->fase1)->get()[0];
        }
        if ($rq->input('draft1') != NULL) {
            $input_draft_rkau = DB::table('file_imports')->select('draft_versi', 'id', 'name')->where('id', $rq->draft1)->get()[0];
        }

        // dd($rq->lokasi1,$rq->draft1 );

        if($rq->tahun1){
            $hasil1 = DB::select("select e.row, e.kolom, e.value from excel_datas e join sheets s on s.id = e.sheet_id where s.name like 'I-LR' and lokasi_id = ".$rq->lokasi1." and e.file_import_id = ".$rq->draft1." and row > 12 order by e.row, e.kolom ");
            // $hasil2 = DB::select("select e.row, e.kolom, e.value from excel_datas e join sheets s on s.id = e.sheet_id where s.name like 'I-LR' and lokasi_id = ".$rq->lokasi2." and e.file_import_id = ".$rq->draft2." and row > 12 order by e.row, e.kolom");
            if($rq->download && $rq->type){
                $judul='';
                switch ($rq->type) {
                    case 'excel':
                        $array['kolom']=array('Keterangan', 'Estimasi Real- '.$tahun2, 'RKAP N -'.$tahun1);
                        $baris=[];
                        $row=[];
                        if($rq->download=='lr_unit_pembangkit'){
                            $array['judul']='PROYEKSI LABA RUGI KOMPARATIF (Dalam Ribuan Rupiah)';
                            $array['tahun']=['Tahun', $tahun1];
                            $array['struktur_bisnis']=['Struktur Bisnis', isset($input_sb) ? $input_sb->name : ''];
                            $array['distrik']=['Distrik', isset($input_distrik) ? $input_distrik->name : ''];
                            $array['lokasi']=['Lokasi', isset($input_lokasi) ? $input_lokasi->name : ''];
                            $array['fase']=['Fase', isset($input_fase) ? $input_fase->name : ''];
                            $array['draft']=['Form RKAU', isset($input_draft_rkau) ? $input_draft_rkau->draft_versi.' - '.$input_draft_rkau->name : ''];
                            $count=0;
                            $barislr=0;
                            foreach($hasil1 as $i=>$val){
                                $barislr = $barislr+1;
                                if($val->row==65){ //khusus 9. Produksi dan Penjualan
                                    $baris[0] = $val->value;
                                    $baris[1] = ' ';
                                    $baris[2] = ' ';
                                    array_push($row, $baris);
                                }
                                else{
                                  // if($count==0){
                                    if($val->kolom=='D'){
                                        if($barislr==4 || $barislr==22 || $barislr==31 
                                            || $barislr==37 || $barislr==67 || $barislr==88 || $barislr==91 || $barislr==94 || $barislr==97
                                            || $barislr==106 || $barislr==109 || $barislr==112 || $barislr==115 || $barislr==118
                                            || $barislr==124 || $barislr==127
                                            || $barislr==140 || $barislr==143 || $barislr==146 || $barislr==149
                                            ) {
                                            $baris[0] = '    '.$val->value;
                                        }
                                        elseif($barislr==7 || $barislr==10 || $barislr==13 || $barislr==16 || $barislr==19
                                            || $barislr==25 || $barislr==28
                                            || $barislr==40 || $barislr==43 || $barislr==46 || $barislr==49 || $barislr==52 || $barislr==55
                                            || $barislr==58 || $barislr==61 || $barislr==64
                                            || $barislr==70 || $barislr==79
                                        ) {
                                            $baris[0] = '          '.$val->value;
                                        }
                                        elseif($barislr==73 || $barislr==76
                                            || $barislr==82 || $barislr==85
                                        ) {
                                            $baris[0] = '                   '.$val->value;
                                        }
                                        else{
                                            $baris[0] = $val->value;                                   
                                        }
                                        $count++;
                                    // }elseif($count==1){
                                    }
                                    elseif($val->kolom=='E'){
                                       $baris[1] = isset($val->value) ? $val->value : '0';
                                       $count++;
                                  // }elseif($count==2){
                                    }
                                    elseif($val->kolom=='F'){
                                        $baris[2]= isset($val->value) ? $val->value : '0';
                                        array_push($row, $baris);
                                        $count=0;
                                    }
                                }
                            }
                            $array['data']=$row;
                        }else if($rq->download=='lr_jasa_o_m'){
                            $array['judul']='PROYEKSI LABA RUGI KOMPARATIF (Dalam Ribuan Rupiah)';
                            $array['tahun']=['Tahun', $tahun1];
                            $array['struktur_bisnis']=['Struktur Bisnis', isset($input_sb) ? $input_sb->name : ''];
                            $array['distrik']=['Distrik', isset($input_distrik) ? $input_distrik->name : ''];
                            $array['lokasi']=['Lokasi', isset($input_lokasi) ? $input_lokasi->name : ''];
                            $array['fase']=['Fase', isset($input_fase) ? $input_fase->name : ''];
                            $array['draft']=['Form RKAU', isset($input_draft_rkau) ? $input_draft_rkau->draft_versi.' - '.$input_draft_rkau->name : ''];
                            $count=0;
                            $barislr=0;
                            foreach($hasil2 as $i=>$val){
                                $barislr = $barislr+1;
                                if($val->row==65){ //khusus 9. Produksi dan Penjualan
                                    $baris[0] = $val->value;
                                    $baris[1] = ' ';
                                    $baris[2] = ' ';
                                    array_push($row, $baris);
                                }else{
                                  // if($count==0){
                                    if($val->kolom=='D'){
                                        if($barislr==4 || $barislr==22 || $barislr==31 
                                            || $barislr==37 || $barislr==67 || $barislr==88 || $barislr==91 || $barislr==94 || $barislr==97
                                            || $barislr==106 || $barislr==109 || $barislr==112 || $barislr==115 || $barislr==118
                                            || $barislr==124 || $barislr==127
                                            || $barislr==140 || $barislr==143 || $barislr==146 || $barislr==149
                                            ) {
                                            $baris[0] = '    '.$val->value;
                                        }
                                        elseif($barislr==7 || $barislr==10 || $barislr==13 || $barislr==16 || $barislr==19
                                            || $barislr==25 || $barislr==28
                                            || $barislr==40 || $barislr==43 || $barislr==46 || $barislr==49 || $barislr==52 || $barislr==55
                                            || $barislr==58 || $barislr==61 || $barislr==64
                                            || $barislr==70 || $barislr==79
                                        ) {
                                            $baris[0] = '          '.$val->value;
                                        }
                                        elseif($barislr==73 || $barislr==76
                                            || $barislr==82 || $barislr==85
                                        ) {
                                            $baris[0] = '                   '.$val->value;
                                        }
                                        else{
                                            $baris[0] = $val->value;                                   
                                        }
                                        $count++;
                                    // }elseif($count==1){
                                    }
                                    elseif($val->kolom=='E'){
                                       $baris[1] = isset($val->value) ? $val->value : '0';
                                       $count++;
                                  // }elseif($count==2){
                                    }
                                    elseif($val->kolom=='F'){
                                        $baris[2]= isset($val->value) ? $val->value : '0';
                                        array_push($row, $baris);
                                        $count=0;
                                    }
                                }
                            }
                            $array['data']=$row;
                        }
                        Excel::create($array['judul'], function($excel) use ($array) {
                            $excel->setTitle('Document');
                            $excel->setCreator('Laravel-5.5')->setCompany('PJB');
                            $excel->sheet('Excel sheet', function($sheet) use ($array) {
                                $sheet->row(1, $array['tahun']);
                                $sheet->row(2, $array['struktur_bisnis']);
                                $sheet->row(3, $array['distrik']);
                                $sheet->row(4, $array['lokasi']);
                                $sheet->row(5, $array['fase']);
                                $sheet->row(6, $array['draft']);
                            
                                $sheet->row(7, array('', '',''));
                                
                                $sheet->row(8, function ($row) {
                                    $row->setFontFamily('Arial');
                                    $row->setFontSize(12);
                                    $row->setFontWeight('bold');
                                });
                                $sheet->row(8, array('PROYEKSI LABA RUGI KOMPARATIF', '', ''));
                                $sheet->row(9, array('(Dalam Ribuan Rupiah)', '',''));
                                $sheet->row(10, array('', '',''));

                                $sheet->row(11, function ($row) {
                                    $row->setAlignment('center');
                                    $row->setFontFamily('Arial');
                                    $row->setFontSize(11);
                                    $row->setFontWeight('bold');
                                });
                                $sheet->row(11, $array['kolom']);

                                $sheet->row(12, function ($row) {
                                    $row->setAlignment('center');
                                    $row->setFontFamily('Arial');
                                    $row->setFontSize(11);
                                    $row->setFontWeight('bold');
                                });
                                $sheet->row(12, array(1,2,3));
                                foreach ($array['data'] as $i => $rows) {
                                    $sheet->row($i+15, $rows);
                                }
                            });
                        })->export('xlsx');//Download Excel, Array To Excel
                    break;
                    case 'pdf':
                        $fill=[];
                        if($rq->download=='lr_unit_pembangkit'){
                            $fill=array($rq->get('thn-form1'), $rq->get('sb-form1'), $rq->get('d-form1'), $rq->get('lok-form1'), $rq->get('fas-form1'),$rq->get('dr-form1'));
                            $judul='Table LR Unit Pembangkit';
                        }else if($rq->download=='lr_jasa_o_m'){
                            $fill=array($rq->get('thn-form2'), $rq->get('sb-form2'), $rq->get('d-form2'), $rq->get('lok-form2'), $rq->get('fas-form2'),$rq->get('df-form2'));
                            $judul='Table LR Jasa O & M';
                        }
                        $pdf=PDF::loadView('output/laba-rugi1-pdf', compact('tahun1', 'tahun2', 'sb', 'fs', 'hasil1', 'hasil2', 'judul', 'fill','input_sb', 'input_fase', 'input_draft_rkau'));
                        return $pdf->download('Laporan Laba Rugi.pdf'); //Download PDF, HTML To PDF
                    break;
                    default:
                        return redirect($rq->url());
                        break;
                }
            }else{
                // dd($hasil1);
                return view('output/laba-rugi', compact('sb', 'fs', 'tahun1', 'tahun2', 'hasil1', 'input_tahun', 'input_sb', 'input_distrik', 'input_lokasi', 'input_fase', 'input_draft_rkau'));
            }
        }else if(count($rq->input())<6 and count($rq->input())>1){
            return redirect($rq->url());
        }else{
            return view('output/laba-rugi', compact('sb', 'fs', 'tahun2', 'tahun1', 'input_tahun', 'input_sb', 'input_distrik', 'input_lokasi', 'input_fase', 'input_draft_rkau'));
        }
    }
 
    // public function PostLR()
    // {

    //     $data['sb_selected'] = input::get('strategi_bisnis');
    //     $data['sb_name_selected'] = StrategiBisnis::where('id',input::get('strategi_bisnis'))->select('name');
     
    //     return view('output/laba-rugi', compact('data'));
    // }

    public function myformAjax($id)
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

    public function myformAjax3($fase_id, $lokasi_id, $tahun)
    {
        $draft=DB::select("SELECT DISTINCT f.id, f.draft_versi
            FROM file_imports f 
            join templates t ON f.template_id = t.id
            join excel_datas e ON e.file_import_id = f.id
            join jenis j on t.jenis_id = j.id
            WHERE e.lokasi_id = ".$lokasi_id." AND t.tahun=".$tahun." and j.name like 'RKAU'
            GROUP BY f.id, f.draft_versi");
        /*$array=array(
                array('id'=>1,'draft_versi'=>'2017-10-29'),
                array('id'=>2,'draft_versi'=>'2017-10-30'),
                array('id'=>3,'draft_versi'=>'2017-10-31'),
            );*/
        return json_encode($draft);
    }
}
