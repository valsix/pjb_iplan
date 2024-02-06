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
use PDF;
use Excel;
use PHPExcel_Style_Border;
use PHPExcel_Style_Alignment;

class RincianBiayaHarController extends Controller
{
    public function Rincian_Biaya_Har(Request $request)
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
        $fs = Fase::all();
        $hasil=null;
        $input_tahun = $request->input('tahun_anggaran');
        $input_sb = $request->input('strategi_bisnis');
        $input_distrik = $request->input('distrik');
        $input_lokasi = $request->input('lokasi');
        $int_input_lokasi = (int)$input_lokasi;
        $input_fase = $request->input('fase1');
        $request_draft_form_6_rutin = $request->input('draft1');

        if ($request->input('strategi_bisnis') != NULL) {
            $input_sb = DB::table('strategi_bisnis')->select('name')->where('id', $request->input('strategi_bisnis'))->get()[0];
        }
        if ($request->input('distrik') != NULL) {
            $input_distrik = DB::table('distrik')->select('name')->where('id', $request->distrik)->get()[0];
        }
        if ($request->input('lokasi') != NULL) {
            $input_lokasi = DB::table('lokasi')->select('name')->where('id', $request->lokasi)->get()[0];
        }
        if ($request->input('fase1') != NULL) {
            $input_fase = DB::table('fases')->select('name','id')->where('id', $request->fase1)->get()[0];
        }
        if ($request->input('draft1') != NULL) {
            $input_form_6_rutin = DB::table('file_imports')->select('draft_versi', 'id', 'name')->where('id', $request->draft1)->get()[0];
        }

        // kodingan asli

        // if($request->input('lokasi')){
        //     $kode='';
        //     if($request->input('kode_ak') OR $request->input('kode_prk') OR $request->input('kegiatan')){
        //         $kode=" and ( e.value = '".$request->input('kode_ak')."' or e.value = '".$request->input('kode_prk')."' or e.value = '".$request->input('kegiatan')."' ) ";
        //     }
        //     $hasil=DB::select("select e.* from excel_datas e
        //                     join sheets s on s.id = e.sheet_id
        //                     join file_imports f on f.id = e.file_import_id
        //                     where s.name like 'I-Form 6'
        //                     and e.file_import_id = ".$request->input('draft1')."
        //                     and e.lokasi_id = ".$request->input('lokasi')."
        //                     and (e.kolom = 'S' or e.kolom = 'R' or e.kolom = 'H' or e.kolom = 'I' or e.kolom = 'T' or e.kolom = 'AK' or e.kolom = 'AL' or e.kolom = 'AM' or e.kolom = 'AN' or e.kolom = 'AO' or e.kolom = 'AP' or e.kolom = 'AQ' or e.kolom = 'AR' or e.kolom = 'AS' or e.kolom = 'AT' or e.kolom = 'AV' or e.kolom = 'AW' or e.kolom = 'AX' or e.kolom = 'AY' ".$kode.' )');
        //     /*S = Inti R = Parent (Deskripsi PRK) */
        //     /*ALOKASI (UP/UBJOM, UPHAR/STOCKIST, UPHB, PJAC, PJB2) mengikuti kegiatan */
        //     $data=[];
        //     $parent=[];
        //     $inti=[];
        //     $count=0;
        //     foreach($hasil as $i=>$val){
        //         switch ($val->kolom) {
        //             case 'R':
        //                 $parent[]=$val->value;
        //                 break;
        //             case 'S':
        //                 $inti[]=$val->value;
        //                 break;
        //             default:
        //                 if($count==16){
        //                     $count=0;
        //                     if(is_numeric($val->value)){
        //                         $push[]= $val->value;
        //                     }else{
        //                         /**/
        //                         $push[]=$val->value;
        //                     }
        //                     array_push($data, $push);
        //                     unset($push);
        //                 }else{
        //                     if(is_numeric($val->value)){
        //                         $push[]= $val->value;
        //                     }else{
        //                         $push[]=$val->value;
        //                     }
        //                     $count++;
        //                 }
        //                 break;
        //         }
        //     }
        //     $hasil2=$this->sparator($data, array_values(array_unique($parent)), array_values(array_unique($inti)));
        //     // dd($hasil2, array_values(array_unique($inti)));
        //     // $hasil2=$data;
        //     switch ($request->download) {
        //         case 'pdf':
        //             return $this->downloadPDF($request, $hasil2);
        //             break;
        //         case 'excel':
        //             return $this->downloadExcel($request, $hasil2);
        //             break;
        //         default:
        //             return view('output/rincian-biaya-har', compact('sb', 'input_tahun', 'input_sb', 'input_distrik', 'input_lokasi', 'fs', 'hasil2'));
        //             break;
        //     }
        // }
        // $hasil2=null;
        // return view('output/rincian-biaya-har', compact('sb', 'input_tahun', 'input_sb', 'input_distrik', 'input_lokasi', 'fs', 'hasil2'));

        // end of kodingan asli

        if ($request->input('fase1') != NULL) {
            $input_fase = DB::table('fases')->select('name','id')->where('id', $request->fase1)->get()[0];
        }
        if ($request->input('draft1') != NULL) {
            $input_form_6_rutin = DB::table('file_imports')->select('draft_versi', 'id', 'name')->where('id', $request->draft1)->get()[0];
        }

        if($request->input('lokasi')){
            $dataparent = array();
            $datainti = array();
            $datakegiatan = array();

            //Start Form 6 Rutin
            if($request_draft_form_6_rutin) {
                $count_6_rutin = DB::select("select count(e.row) from excel_datas e join sheets s on s.id = e.sheet_id where s.name like 'I-Form 6' and e.file_import_id = ".$request_draft_form_6_rutin." and e.lokasi_id = ".$int_input_lokasi." and e.kolom = 'AM';")[0]->count;
                $int_count_6_rutin = (int)$count_6_rutin;

                $daftar_prk_kegiatan_form_6_rutin = array();
                $daftar_prk_inti_form_6_rutin = array();
                $daftar_prk_parent_form_6_rutin = array();
                
                $form_6_rutin_prk_parent = $this->get_form_6_parent($request_draft_form_6_rutin,$int_input_lokasi, 'I');
                // dd($form_6_rutin_prk_parent);
                foreach ($form_6_rutin_prk_parent as $key => $value) {
                    $daftar_prk_parent_form_6_rutin[$value->value] = array(
                        'kode_aktivitas' => '', 
                        'desc_prk_parent' => '', 
                        'desc_prk_parent' => '', 
                        'persediaan_lr' => 0,
                        'pengadaan_lr' => 0,
                        'jasa_lr' => 0,
                        'total_lr' => 0,
                        'material_hutang_cf' => 0,
                        'jasa_hutang_cf' => 0,
                        'pengadaan_cf' => 0,
                        'persediaan_cf' => 0,
                        'jml_material_cf' => 0,
                        'jml_jasa_cf' => 0,
                        'total_cf' => 0,
                        'alokasi' => 0,
                        'persetujuan' => 0,
                        'disburse' => 0,
                        );
                }

                $form_6_rutin_prk_inti = $this->get_form_6_inti($request_draft_form_6_rutin,$int_input_lokasi, 'I');
                foreach ($form_6_rutin_prk_inti as $key => $value) {
                    $daftar_prk_inti_form_6_rutin[$value->value] = array(
                        'kode_aktivitas' => '', 
                        'prk_parent'    => substr($value->value, 0, 6),
                        'desc_prk_inti' => '', 
                        'persediaan_lr' => 0,
                        'pengadaan_lr' => 0,
                        'jasa_lr' => 0,
                        'total_lr' => 0,
                        'material_hutang_cf' => 0,
                        'jasa_hutang_cf' => 0,
                        'pengadaan_cf' => 0,
                        'persediaan_cf' => 0,
                        'jml_material_cf' => 0,
                        'jml_jasa_cf' => 0,
                        'total_cf' => 0,
                        'alokasi' => 0,
                        'persetujuan' => 0,
                        'disburse' => 0,
                    );
                }

                $form_6_rutin_kode_aktivitas = $this->get_form_6($request_draft_form_6_rutin,$int_input_lokasi, 'H');
                $form_6_rutin_no_prk_kegiatan = $this->get_form_6($request_draft_form_6_rutin,$int_input_lokasi, 'I');
                $form_6_rutin_desc_prk_kegiatan = $this->get_form_6($request_draft_form_6_rutin,$int_input_lokasi, 'T');
                $form_6_rutin_desc_prk_inti = $this->get_form_6($request_draft_form_6_rutin,$int_input_lokasi, 'S');
                $form_6_rutin_desc_prk_parent = $this->get_form_6($request_draft_form_6_rutin,$int_input_lokasi, 'R');
                $form_6_rutin_persediaan_lr = $this->get_form_6($request_draft_form_6_rutin,$int_input_lokasi, 'AK');
                $form_6_rutin_pengadaan_lr = $this->get_form_6($request_draft_form_6_rutin,$int_input_lokasi, 'AL');
                $form_6_rutin_jasa_lr = $this->get_form_6($request_draft_form_6_rutin,$int_input_lokasi, 'AM');
                $form_6_rutin_total_lr = $this->get_form_6($request_draft_form_6_rutin,$int_input_lokasi, 'AN');
                $form_6_rutin_material_hutang_cf = $this->get_form_6($request_draft_form_6_rutin,$int_input_lokasi, 'AO');
                $form_6_rutin_jasa_hutang_cf = $this->get_form_6($request_draft_form_6_rutin,$int_input_lokasi, 'AP');
                $form_6_rutin_pengadaan_cf = $this->get_form_6($request_draft_form_6_rutin,$int_input_lokasi, 'AQ');
                $form_6_rutin_persediaan_cf = $this->get_form_6($request_draft_form_6_rutin,$int_input_lokasi, 'AR');
                $form_6_rutin_jml_material_cf = $this->get_form_6($request_draft_form_6_rutin,$int_input_lokasi, 'AS');
                $form_6_rutin_jml_jasa_cf = $this->get_form_6($request_draft_form_6_rutin,$int_input_lokasi, 'AT');
                $form_6_rutin_total_cf = $this->get_form_6($request_draft_form_6_rutin,$int_input_lokasi, 'AV');
                $form_6_rutin_alokasi = $this->get_form_6($request_draft_form_6_rutin,$int_input_lokasi, 'AW');
                $form_6_rutin_persetujuan = $this->get_form_6($request_draft_form_6_rutin,$int_input_lokasi, 'AX');
                $form_6_rutin_disburse = $this->get_form_6($request_draft_form_6_rutin,$int_input_lokasi, 'AY');
                
                for($i=0; $i<$int_count_6_rutin; $i++){
                    $parent = substr($form_6_rutin_no_prk_kegiatan[$i]->value,0,6);
                    $inti = substr($form_6_rutin_no_prk_kegiatan[$i]->value,0,8);
                    
                    // $disburse = array();
                    // for($bulan=1; $bulan<=12; $bulan++){
                    //     $disburse[$bulan] = (float)$form_6_rutin_disburse[$bulan][$i]->value;
                    //     // $daftar_prk_inti_form_6_rutin[$inti]['disburse'][$bulan] += $disburse[$bulan];
                    //     // $daftar_prk_parent_form_6_rutin[$parent]['disburse'][$bulan] += $disburse[$bulan];
                    // }
                    
                    $temp = array(
                        // 'beban_mat' => (float)$form_6_rutin_beban_mat[$i]->value,
                        // 'cash_oth' => (float)$form_6_rutin_cash_oth[$i]->value,
                        // 'ijin_proses' => (float)$form_6_rutin_ijin_proses[$i]->value,
                        // 'disburse' => $disburse,
                        // 'total_year_estimate' => (float)$form_6_rutin_total_year_estimate[$i]->value,

                        'kode_aktivitas' => $form_6_rutin_kode_aktivitas[$i]->value, 
                        'prk_kegiatan' => $form_6_rutin_no_prk_kegiatan[$i]->value,
                        'prk_inti' => $inti,
                        'prk_parent' => $parent,
                        'desc_prk_kegiatan' => $form_6_rutin_desc_prk_kegiatan[$i]->value,
                        'desc_prk_inti' => $form_6_rutin_desc_prk_inti[$i]->value,
                        'desc_prk_parent' => $form_6_rutin_desc_prk_parent[$i]->value,
                        'persediaan_lr' => (float)$form_6_rutin_persediaan_lr[$i]->value,
                        'pengadaan_lr' => (float)$form_6_rutin_pengadaan_lr[$i]->value,
                        'jasa_lr' => (float)$form_6_rutin_jasa_lr[$i]->value,
                        'total_lr' => (float)$form_6_rutin_total_lr[$i]->value,
                        'material_hutang_cf' => (float)$form_6_rutin_material_hutang_cf[$i]->value,
                        'jasa_hutang_cf' => (float)$form_6_rutin_jasa_hutang_cf[$i]->value,
                        'pengadaan_cf' => (float)$form_6_rutin_pengadaan_cf[$i]->value,
                        'persediaan_cf' => (float)$form_6_rutin_persediaan_cf[$i]->value,
                        'jml_material_cf' => (float)$form_6_rutin_jml_material_cf[$i]->value,
                        'jml_jasa_cf' => (float)$form_6_rutin_jml_jasa_cf[$i]->value,
                        'total_cf' => (float)$form_6_rutin_total_cf[$i]->value,
                        'alokasi' => $form_6_rutin_alokasi[$i]->value,
                        'persetujuan' => (float)$form_6_rutin_persetujuan[$i]->value,
                        'disburse' => $form_6_rutin_disburse[$i]->value,
                    );
                    array_push($daftar_prk_kegiatan_form_6_rutin, $temp);

                    $daftar_prk_inti_form_6_rutin[$inti]['kode_aktivitas'] = $temp['kode_aktivitas'];
                    $daftar_prk_inti_form_6_rutin[$inti]['desc_prk_inti'] = $temp['desc_prk_inti'];
                    $daftar_prk_inti_form_6_rutin[$inti]['persediaan_lr'] += $temp['persediaan_lr'];
                    $daftar_prk_inti_form_6_rutin[$inti]['pengadaan_lr'] += $temp['pengadaan_lr'];
                    $daftar_prk_inti_form_6_rutin[$inti]['jasa_lr'] += $temp['jasa_lr'];
                    $daftar_prk_inti_form_6_rutin[$inti]['total_lr'] += $temp['total_lr'];
                    $daftar_prk_inti_form_6_rutin[$inti]['material_hutang_cf'] += $temp['material_hutang_cf'];
                    $daftar_prk_inti_form_6_rutin[$inti]['jasa_hutang_cf'] += $temp['jasa_hutang_cf'];
                    $daftar_prk_inti_form_6_rutin[$inti]['pengadaan_cf'] += $temp['pengadaan_cf'];
                    $daftar_prk_inti_form_6_rutin[$inti]['persediaan_cf'] += $temp['persediaan_cf'];
                    $daftar_prk_inti_form_6_rutin[$inti]['jml_material_cf'] += $temp['jml_material_cf'];
                    $daftar_prk_inti_form_6_rutin[$inti]['jml_jasa_cf'] += $temp['jml_jasa_cf'];
                    $daftar_prk_inti_form_6_rutin[$inti]['total_cf'] += $temp['total_cf'];
                    $daftar_prk_inti_form_6_rutin[$inti]['alokasi'] = $temp['alokasi'];
                    $daftar_prk_inti_form_6_rutin[$inti]['persetujuan'] += $temp['persetujuan'];
                    $daftar_prk_inti_form_6_rutin[$inti]['disburse'] = $temp['disburse'];

                    $daftar_prk_parent_form_6_rutin[$parent]['kode_aktivitas'] = $temp['kode_aktivitas'];
                    $daftar_prk_parent_form_6_rutin[$parent]['desc_prk_parent'] = $temp['desc_prk_parent'];
                    $daftar_prk_parent_form_6_rutin[$parent]['persediaan_lr'] += $temp['persediaan_lr'];
                    $daftar_prk_parent_form_6_rutin[$parent]['pengadaan_lr'] += $temp['pengadaan_lr'];
                    $daftar_prk_parent_form_6_rutin[$parent]['jasa_lr'] += $temp['jasa_lr'];
                    $daftar_prk_parent_form_6_rutin[$parent]['total_lr'] += $temp['total_lr'];
                    $daftar_prk_parent_form_6_rutin[$parent]['material_hutang_cf'] += $temp['material_hutang_cf'];
                    $daftar_prk_parent_form_6_rutin[$parent]['jasa_hutang_cf'] += $temp['jasa_hutang_cf'];
                    $daftar_prk_parent_form_6_rutin[$parent]['pengadaan_cf'] += $temp['pengadaan_cf'];
                    $daftar_prk_parent_form_6_rutin[$parent]['persediaan_cf'] += $temp['persediaan_cf'];
                    $daftar_prk_parent_form_6_rutin[$parent]['jml_material_cf'] += $temp['jml_material_cf'];
                    $daftar_prk_parent_form_6_rutin[$parent]['jml_jasa_cf'] += $temp['jml_jasa_cf'];
                    $daftar_prk_parent_form_6_rutin[$parent]['total_cf'] += $temp['total_cf'];
                    $daftar_prk_parent_form_6_rutin[$parent]['alokasi'] = $temp['alokasi'];
                    $daftar_prk_parent_form_6_rutin[$parent]['persetujuan'] += $temp['persetujuan'];
                    $daftar_prk_parent_form_6_rutin[$parent]['disburse'] = $temp['disburse'];
                }
                $dataparent['form_6_rutin'] = $daftar_prk_parent_form_6_rutin;
                $datainti['form_6_rutin'] = $daftar_prk_inti_form_6_rutin;
                $datakegiatan['form_6_rutin'] = $daftar_prk_kegiatan_form_6_rutin;

            } //end of cek request draft form 6 rutin
            //End of query form 6 rutin

            $jml_data = count($dataparent['form_6_rutin'])+count($datainti['form_6_rutin'])+count($datakegiatan['form_6_rutin']);

            if($request->download && $request->type){
                $judul='';
                if($request->type=='excel'){
                    Excel::create('Rincian Biaya HAR', function ($excel) use($sb, $input_tahun, $input_sb, $input_distrik, $input_lokasi, $input_fase, $dataparent, $datainti, $datakegiatan, $input_form_6_rutin, $jml_data) {
                            $excel->setTitle('Rincian Biaya HAR');
                            $excel->setCreator('Laravel-5.5')->setCompany('Profio', 'PJB');
                            $excel->setDescription('Rincian Biaya HAR');
                            $excel->sheet('Rincian Biaya HAR', function ($sheet) use($sb, $input_tahun, $input_sb, $input_distrik, $input_lokasi, $input_fase, $dataparent, $datainti, $datakegiatan, $input_form_6_rutin, $jml_data){
                                $sheet->loadView('output/rincian-biaya-har-excel')
                                        ->with('sb', $sb)
                                        ->with('input_tahun', $input_tahun)
                                        ->with('input_sb', $input_sb)
                                        ->with('input_distrik', $input_distrik)
                                        ->with('input_lokasi', $input_lokasi)
                                        ->with('input_fase', $input_fase)
                                        ->with('input_form_6_rutin', $input_form_6_rutin)
                                        ->with('dataparent', $dataparent)
                                        ->with('datainti', $datainti)
                                        ->with('datakegiatan', $datakegiatan);
                            });

                            $styleArray = array(
                            'borders' => array(
                                'allborders' => array(
                                  'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            ),
                            'alignment' => array(
                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                    'rotation'   => 0,
                                    'wrap'       => true
                                ),
                            'font'=> array(
                                    'bold'=> true
                                )
                        );

                        // Header
                        $excel->getActiveSheet()->getStyle('A'.(8).':A'.(10))->applyFromArray($styleArray);
                        $excel->getActiveSheet()->getStyle('B'.(8).':B'.(10))->applyFromArray($styleArray);
                        $excel->getActiveSheet()->getStyle('C'.(8).':C'.(10))->applyFromArray($styleArray);
                        $excel->getActiveSheet()->getStyle('D'.(8).':G'.(8))->applyFromArray($styleArray);
                        $excel->getActiveSheet()->getStyle('D'.(9).':E'.(9))->applyFromArray($styleArray);
                        $excel->getActiveSheet()->getStyle('F'.(9).':F'.(10))->applyFromArray($styleArray);
                        $excel->getActiveSheet()->getStyle('G'.(9).':G'.(10))->applyFromArray($styleArray);
                        $excel->getActiveSheet()->getStyle('H'.(8).':N'.(8))->applyFromArray($styleArray);
                        $excel->getActiveSheet()->getStyle('H'.(9).':I'.(9))->applyFromArray($styleArray);
                        $excel->getActiveSheet()->getStyle('J'.(9).':K'.(9))->applyFromArray($styleArray);
                        $excel->getActiveSheet()->getStyle('L'.(9).':L'.(10))->applyFromArray($styleArray);
                        $excel->getActiveSheet()->getStyle('M'.(9).':M'.(10))->applyFromArray($styleArray);
                        $excel->getActiveSheet()->getStyle('N'.(9).':N'.(10))->applyFromArray($styleArray);
                        $excel->getActiveSheet()->getStyle('O'.(8).':O'.(10))->applyFromArray($styleArray);
                        $excel->getActiveSheet()->getStyle('P'.(8).':P'.(10))->applyFromArray($styleArray);
                        $excel->getActiveSheet()->getStyle('Q'.(8).':Q'.(10))->applyFromArray($styleArray);
                        // end of Header

                        $border=array(
                            'borders' => array(
                                'allborders' => array(
                                  'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            ));
                        $excel->getActiveSheet()->getStyle('A'.(8).':Q'.($jml_data+11))->applyFromArray($border);
                        })->download('xlsx');
                }
            }
        }

        return view('output/rincian-biaya-har', compact('sb', 'input_tahun', 'input_sb', 'input_distrik', 'input_lokasi', 'fs', 'hasil2', 'dataparent', 'datainti','datakegiatan', 'distrik', 'lokasi','tahun', 'input_form_6_rutin'));
    }

    private function sparator($data, $text_parent, $text_inti){
        $kode_inti=[];
        $kode_parent=[];
        foreach ($data as $i => $row) {
            $kode_inti[]=substr($row[1], 0,8);
            $kode_parent[]=substr($row[1], 0,6);
        }
        $kode_parent=array_values(array_unique($kode_parent)); //reset key array, hapus value array yang sama
        $kode_inti=array_values(array_unique($kode_inti)); //reset key array, hapus value array yang sama
        // dd($kode_inti, $text_inti);

        $row_inti=[];
        $row_parent=[];
        foreach ($kode_inti as $in=> $inti) {
            foreach ($data as $i => $row) {
                if(substr($row[1], 0,8)==$inti){
                    if(!isset($push_inti[$inti])){
                        if($in>=count($text_inti)){
                            $text=$text_inti[(count($text_inti)-1)];
                        }else{
                            $text=$text_inti[$in];
                        }
                        $push_inti[$inti]=[
                                0 => $row[0],
                                1 => substr($row[1], 0,8),
                                2 => $text,
                                3 => $row[3],
                                4 => $row[4],
                                5 => $row[5],
                                6 => $row[6],
                                7 => $row[7],
                                8 => $row[8],
                                9 => $row[9],
                                10 => $row[10],
                                11 => $row[11],
                                12 => $row[12],
                                13 => $row[13],
                                14 => $row[14],
                                15 => $row[15],
                                16 => $row[16]
                            ];
                        $push_row[$inti]=$row;
                        array_push($row_inti, $push_inti[$inti]);
                        array_push($row_inti, $row);
                    }else{
                        $sementara=$row_inti;
                        foreach ($sementara as $key => $val) {
                            if($val[1]==$push_inti[$inti][1]){ /*KODE PRK*/
                                $row_inti[$key][3]=$row_inti[$key][3]+$row[3];
                                $row_inti[$key][4]=$row_inti[$key][4]+$row[4];
                                $row_inti[$key][5]=$row_inti[$key][5]+$row[5];
                                $row_inti[$key][6]=$row_inti[$key][6]+$row[6];
                                $row_inti[$key][7]=$row_inti[$key][7]+$row[7];
                                $row_inti[$key][8]=$row_inti[$key][8]+$row[8];
                                $row_inti[$key][9]=$row_inti[$key][9]+$row[9];
                                $row_inti[$key][10]=$row_inti[$key][10]+$row[10];
                                $row_inti[$key][11]=$row_inti[$key][11]+$row[11];
                                $row_inti[$key][12]=$row_inti[$key][12]+$row[12];
                                $row_inti[$key][13]=$row_inti[$key][13]+$row[13];
                                array_push($row_inti, $row);
                            }
                        }
                    }
                }
            }
        }
        $row_inti_only=[];
        foreach ($kode_parent as $in=> $parent) {
            foreach ($row_inti as $i => $row) {
                if(strlen($row[1])==8 AND substr($row[1], 0,6)==$parent){
                    array_push($row_inti_only, $row);
                }
            }
        }
        $row_parent_only=[];
        foreach ($kode_parent as $in=> $parent) {
            foreach ($row_inti_only as $i => $row) {
                if(substr($row[1], 0,6)==$parent){
                    if(!isset($push_parent_only[$parent])){
                        if($in>=count($text_parent)){
                            $text=$text_parent[(count($text_parent)-1)];
                        }else{
                            $text=$text_parent[$in];
                        }
                        $push_parent_only[$parent]=[
                                0 => $row[0],
                                1 => substr($row[1], 0,6),
                                2 => $text,
                                3 => $row[3],
                                4 => $row[4],
                                5 => $row[5],
                                6 => $row[6],
                                7 => $row[7],
                                8 => $row[8],
                                9 => $row[9],
                                10 => $row[10],
                                11 => $row[11],
                                12 => $row[12],
                                13 => $row[13],
                                14 => $row[14],
                                15 => $row[15],
                                16 => $row[16]
                            ];
                        array_push($row_parent_only, $push_parent_only[$parent]);
                    }else{
                        $sementara=$row_parent_only;
                        foreach ($sementara as $key => $val) {
                            if($val[1]==$push_parent_only[$parent][1]){ /*KODE PRK*/
                                $row_parent_only[$key][3]=$row_parent_only[$key][3]+$row[3];
                                $row_parent_only[$key][4]=$row_parent_only[$key][4]+$row[4];
                                $row_parent_only[$key][5]=$row_parent_only[$key][5]+$row[5];
                                $row_parent_only[$key][6]=$row_parent_only[$key][6]+$row[6];
                                $row_parent_only[$key][7]=$row_parent_only[$key][7]+$row[7];
                                $row_parent_only[$key][8]=$row_parent_only[$key][8]+$row[8];
                                $row_parent_only[$key][9]=$row_parent_only[$key][9]+$row[9];
                                $row_parent_only[$key][10]=$row_parent_only[$key][10]+$row[10];
                                $row_parent_only[$key][11]=$row_parent_only[$key][11]+$row[11];
                                $row_parent_only[$key][12]=$row_parent_only[$key][12]+$row[12];
                                $row_parent_only[$key][13]=$row_parent_only[$key][13]+$row[13];
                                // array_push($row_parent_only, $row);
                            }
                        }
                    }
                }
            }
        }
        $final=[];
        foreach ($row_parent_only as $i => $rpo) {
            foreach ($row_inti as $o => $ri) {
                if(strlen($ri[1])==8 and substr($ri[1], 0,6)==$rpo[1]){
                    if(!isset($final[$rpo[1]])){
                        $final[$rpo[1]]=$rpo;
                        $final[$ri[1]]=$ri;
                    }
                }
                if(strlen($ri[1])==10 and substr($ri[1], 0,6)==$rpo[1]){
                    if(!isset($final[$ri[1]])){
                        $final[$ri[1]]=$ri;
                    }
                }
            }
        }
        // dd( array_values($final));
        return array_values($final);
    }

    private function downloadExcel(Request $request, $hasil){
        $fm=$request->input();
        $data=[];
        $count=0;
        foreach($hasil as $i=>$val){
            $push=[];
            foreach ($val as $key => $row) {
                if(is_numeric($row)){
                    $row=number_format($row, 0, ',', ',');
                }
                array_push($push, $row);
            }
            array_push($data, $push);
        }
        unset($hasil);
        $filter=array(
                array('Tahun Anggaran', $request->input('tahun-fm')),
                array('Struktur Bisnis', $request->input('sb-fm')),
                array('Distrik', $request->input('distrik-fm')),
                array('Lokasi', $request->input('lokasi-fm')),
                array('Fase', $request->input('fase-fm')),
                array('Draft', $request->input('draft-fm')),
                array('Kode Aktifitas', $request->input('kode_ak-fm') ? $request->input('kode_ak-fm') : ''),
                array('Kode PRK', $request->input('kode_prk-fm') ? $request->input('kode_prk-fm') : ''),
                array('Deskripsi PRK kegiatan', $request->input('desk_prk-fm') ? $request->input('desk_prk-fm') : '')
            );
        $hasil['filter']=$filter;
        $hasil['data']=$data;
        Excel::create('Rincian Biaya Har', function($excel) use ($hasil) {
            $excel->setTitle('Rincian Biaya Harian');
            $excel->setCreator('Laravel-5.5')->setCompany('PJB');
            $excel->sheet('Excel sheet', function($sheet) use ($hasil) {
                $sheet->fromArray($hasil['filter'], null, 'A1');
                /*Header*/
                    $sheet->getCell('C'.(count($hasil['filter'])+4))->setValue('Deskripsi PRK Kegiatan');
                    $sheet->getCell('A'.(count($hasil['filter'])+4))->setValue('Kode Aktifitas');
                    $sheet->getCell('B'.(count($hasil['filter'])+4))->setValue('Kode PRK');
                    $sheet->getCell('D'.(count($hasil['filter'])+4))->setValue('Total Pemakaian (Laba/Rugi)');
                    $sheet->getCell('D'.(count($hasil['filter'])+5))->setValue('Material');
                    $sheet->getCell('D'.(count($hasil['filter'])+6))->setValue('Persedian');
                    $sheet->getCell('E'.(count($hasil['filter'])+6))->setValue('Pengadaan Langsung Pakai');

                    $sheet->getCell('F'.(count($hasil['filter'])+5))->setValue('Jasa');
                    $sheet->getCell('G'.(count($hasil['filter'])+5))->setValue('Total');

                    $sheet->getCell('H'.(count($hasil['filter'])+4))->setValue('TOTAL PEMAKAIAN (CASH FLOW)');
                    $sheet->getCell('H'.(count($hasil['filter'])+5))->setValue('Pembayaran Hutang');
                    $sheet->getCell('H'.(count($hasil['filter'])+6))->setValue('Material');
                    $sheet->getCell('I'.(count($hasil['filter'])+6))->setValue('Jasa');

                    $sheet->getCell('J'.(count($hasil['filter'])+5))->setValue('Material');
                    $sheet->getCell('J'.(count($hasil['filter'])+6))->setValue('Pengadaan Langsung Pakai');
                    $sheet->getCell('K'.(count($hasil['filter'])+6))->setValue('Persediaan');

                    $sheet->getCell('L'.(count($hasil['filter'])+5))->setValue('Jumlah Material');
                    $sheet->getCell('M'.(count($hasil['filter'])+5))->setValue('Jumlah Jasa');
                    $sheet->getCell('N'.(count($hasil['filter'])+5))->setValue('Total');

                    $sheet->getCell('O'.(count($hasil['filter'])+4))->setValue('ALOKASI (UP/UBJOM, UPHAR/STOCKIST, UPHB, PJAC, PJB2)');
                    $sheet->getCell('P'.(count($hasil['filter'])+4))->setValue('Persetujuan Proses Kontrak Pengadaan');
                    $sheet->getCell('Q'.(count($hasil['filter'])+4))->setValue('Disburse');
                /*Header*/
                foreach ($hasil['data'] as $i => $row) {
                    $sheet->getCell('A'.(count($hasil['filter'])+4+3+$i))->setValue($row[0]);
                    $sheet->getCell('B'.(count($hasil['filter'])+4+3+$i))->setValue($row[1]);
                    $sheet->getCell('C'.(count($hasil['filter'])+4+3+$i))->setValue($row[2]);
                    $sheet->getCell('D'.(count($hasil['filter'])+4+3+$i))->setValue($row[3]);
                    $sheet->getCell('E'.(count($hasil['filter'])+4+3+$i))->setValue($row[4]);
                    $sheet->getCell('F'.(count($hasil['filter'])+4+3+$i))->setValue($row[5]);
                    $sheet->getCell('G'.(count($hasil['filter'])+4+3+$i))->setValue($row[6]);
                    $sheet->getCell('H'.(count($hasil['filter'])+4+3+$i))->setValue($row[7]);
                    $sheet->getCell('I'.(count($hasil['filter'])+4+3+$i))->setValue($row[8]);
                    $sheet->getCell('J'.(count($hasil['filter'])+4+3+$i))->setValue($row[9]);
                    $sheet->getCell('K'.(count($hasil['filter'])+4+3+$i))->setValue($row[10]);
                    $sheet->getCell('L'.(count($hasil['filter'])+4+3+$i))->setValue($row[11]);
                    $sheet->getCell('M'.(count($hasil['filter'])+4+3+$i))->setValue($row[12]);
                    $sheet->getCell('N'.(count($hasil['filter'])+4+3+$i))->setValue($row[13]);
                    $sheet->getCell('O'.(count($hasil['filter'])+4+3+$i))->setValue($row[14]);
                    $sheet->getCell('P'.(count($hasil['filter'])+4+3+$i))->setValue($row[15]);
                    $sheet->getCell('Q'.(count($hasil['filter'])+4+3+$i))->setValue($row[16]);
                }
            });

            $excel->getActiveSheet()->mergeCells('A'.(count($hasil['filter'])+4).':A'.(count($hasil['filter'])+4+2)); /*Kode Aktifitas*/
            $excel->getActiveSheet()->mergeCells('B'.(count($hasil['filter'])+4).':B'.(count($hasil['filter'])+4+2)); /*Kode PRK*/
            $excel->getActiveSheet()->mergeCells('C'.(count($hasil['filter'])+4).':C'.(count($hasil['filter'])+4+2)); /*Deskripsi PRK Kegiatan*/
            $excel->getActiveSheet()->mergeCells('D'.(count($hasil['filter'])+4).':G'.(count($hasil['filter'])+4)); /*Total Pemakaian (Laba/Rugi)*/
            $excel->getActiveSheet()->mergeCells('D'.(count($hasil['filter'])+5).':E'.(count($hasil['filter'])+5)); /*Material*/
            $excel->getActiveSheet()->mergeCells('F'.(count($hasil['filter'])+5).':F'.(count($hasil['filter'])+5+1)); /*Jasa*/
            $excel->getActiveSheet()->mergeCells('G'.(count($hasil['filter'])+5).':G'.(count($hasil['filter'])+5+1)); /*Total*/

            $excel->getActiveSheet()->mergeCells('H'.(count($hasil['filter'])+4).':N'.(count($hasil['filter'])+4)); /*TOTAL PEMAKAIAN (CASH FLOW)*/
            $excel->getActiveSheet()->mergeCells('H'.(count($hasil['filter'])+5).':I'.(count($hasil['filter'])+5)); /*Pembayaran Hutang*/
            $excel->getActiveSheet()->mergeCells('J'.(count($hasil['filter'])+5).':K'.(count($hasil['filter'])+5)); /*Material*/

            $excel->getActiveSheet()->mergeCells('L'.(count($hasil['filter'])+5).':L'.(count($hasil['filter'])+5+1)); /*Jumlah Material*/
            $excel->getActiveSheet()->mergeCells('M'.(count($hasil['filter'])+5).':M'.(count($hasil['filter'])+5+1)); /*Jumlah Jasa*/
            $excel->getActiveSheet()->mergeCells('N'.(count($hasil['filter'])+5).':N'.(count($hasil['filter'])+5+1)); /*Total*/

            $excel->getActiveSheet()->mergeCells('O'.(count($hasil['filter'])+4).':O'.(count($hasil['filter'])+4+2)); /*ALOKASI (UP/UBJOM, UPHAR/STOCKIST, UPHB, PJAC, PJB2)*/
            $excel->getActiveSheet()->mergeCells('P'.(count($hasil['filter'])+4).':P'.(count($hasil['filter'])+4+2)); /*Persetujuan Proses Kontrak Pengadaan*/
            $excel->getActiveSheet()->mergeCells('Q'.(count($hasil['filter'])+4).':Q'.(count($hasil['filter'])+4+2)); /*Disburse*/

            $styleArray = array(
                'borders' => array(
                    'allborders' => array(
                      'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                ),
                'alignment' => array(
                        'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'rotation'   => 0,
                        'wrap'       => true
                    ),
                'font'=> array(
                        'bold'=> true
                    )
            );
            $excel->getActiveSheet()->getStyle('A'.(count($hasil['filter'])+4).':Q'.(count($hasil['filter'])+4))->applyFromArray($styleArray);
            $excel->getActiveSheet()->getStyle('A'.(count($hasil['filter'])+4).':Q'.(count($hasil['filter'])+6))->applyFromArray($styleArray);
            $border=array(
                'borders' => array(
                    'allborders' => array(
                      'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                ));
            $excel->getActiveSheet()->getStyle('A'.(count($hasil['filter'])+4+3).':Q'.(count($hasil['filter'])+count($hasil['data'])+6))->applyFromArray($border);
            $excel->getActiveSheet()->getCell('B1')->setValue('');
        })->export('xlsx');//Download Excel, Array To Excel
        // return json_encode($hails);
    }

    private function downloadPDF(Request $request, $hasil){
        $input_tahun = $request->input('tahun_anggaran');
        $head='Rincian Program Pemeliharaan Per Aktivitas Tahun '.$input_tahun;
        $input_sb = DB::table('strategi_bisnis')->select('name')->where('id', $request->input('strategi_bisnis'))->get()[0];
        $input_distrik = DB::table('distrik')->select('name')->where('id', $request->distrik)->get()[0];
        $input_lokasi = DB::table('lokasi')->select('name')->where('id', $request->lokasi)->get()[0];
        $fase=$request->input('fase-fm');
        $draft=$request->input('draft-fm');
        // return view('output/rincian-biaya-har-pdf', compact('head', 'sb', 'input_tahun', 'input_sb', 'input_distrik', 'input_lokasi', 'hasil', 'fase', 'draft'));
        $pdf= PDF::loadView('output/rincian-biaya-har-pdf', compact('head', 'sb', 'input_tahun', 'input_sb', 'input_distrik', 'input_lokasi', 'hasil', 'fase', 'draft'));
        $pdf->setPaper('A2', 'landscape');
        return $pdf->stream();
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

    public function myformAjax3($fase_id, $lokasi_id, $tahun)
    {
        $draft=DB::select("SELECT DISTINCT f.id, f.draft_versi
            FROM file_imports f
            join templates t ON f.template_id = t.id
            join excel_datas e ON e.file_import_id = f.id
            join jenis j on t.jenis_id = j.id
            WHERE e.lokasi_id = ".$lokasi_id." AND t.tahun=".$tahun." and j.id = 3
            GROUP BY f.id, f.draft_versi");
        return json_encode($draft);
    }

    public function myformAjax4($draft, $lokasi)
    {
        $return['kode_ak']=DB::select("SELECT e.value from excel_datas e join sheets s on s.id = e.sheet_id join file_imports f on f.id = e.file_import_id where s.name like 'I-Form 6' and e.file_import_id = ".$draft." and e.lokasi_id = ".$lokasi." and (e.kolom = 'H') group by e.value ");
        $return['kode_prk']=DB::select("SELECT e.* from excel_datas e join sheets s on s.id = e.sheet_id join file_imports f on f.id = e.file_import_id where s.name like 'I-Form 6' and e.file_import_id = ".$draft." and e.lokasi_id = ".$lokasi." and (e.kolom = 'I')");
        $return['kegiatan']=DB::select("SELECT e.* from excel_datas e join sheets s on s.id = e.sheet_id join file_imports f on f.id = e.file_import_id where s.name like 'I-Form 6' and e.file_import_id = ".$draft." and e.lokasi_id = ".$lokasi." and (e.kolom = 'T')");
        return json_encode($return);
    }

    function get_form_6($file_import_id, $lokasi_id, $kolom){
        $query = DB::select("select e.row, e.value from excel_datas e join sheets s on s.id = e.sheet_id where s.name like 'I-Form 6' and e.file_import_id = ".$file_import_id." and e.lokasi_id = ".$lokasi_id." and e.kolom = '".$kolom."' order by e.row;");
        return $query;
    }
    function get_form_6_inti($file_import_id, $lokasi_id, $kolom){
        $query = DB::select("select distinct SUBSTRING(e.value,1,8) as value from excel_datas e join sheets s on s.id = e.sheet_id where s.name like 'I-Form 6' and e.file_import_id = ".$file_import_id." and e.lokasi_id = ".$lokasi_id." and e.kolom = '".$kolom."' order by value asc;");
        return $query;
    }

    function get_form_6_parent($file_import_id, $lokasi_id, $kolom){
        $query = DB::select("select distinct SUBSTRING(e.value,1,6) as value from excel_datas e join sheets s on s.id = e.sheet_id where s.name like 'I-Form 6' and e.file_import_id = ".$file_import_id." and e.lokasi_id = ".$lokasi_id." and e.kolom = '".$kolom."' order by value asc;");
        return $query;
    }
    function get_form_6_disburse($file_import_id, $lokasi_id, $kolom1, $kolom2){
        $query = DB::select("select sum(case when value = '' then 0 else value::float end) as value from excel_datas e join sheets s on s.id = e.sheet_id where s.name like 'I-Form 6' and e.file_import_id = ".$file_import_id." and e.lokasi_id = ".$lokasi_id." and (e.kolom = '".$kolom1."' or e.kolom = '".$kolom2."') group by e.row");
        return $query;
    }
}
