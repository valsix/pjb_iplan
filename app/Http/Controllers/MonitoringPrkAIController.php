<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\StrategiBisnis;
use App\Entities\Distrik;
use App\Entities\Lokasi;
use App\Entities\Fase;
use App\Entities\Template;
use App\Entities\User;
use App\Entities\Role;
use App\Entities\FileImportKetetapan;
Use DB;
use Illuminate\Support\Facades\Input;
use Excel;

class MonitoringPrkAIController extends Controller
{
    public function Monitoring_PRK_AI(Request $request)
    {
        $data = Input::all();
        //dd($data);

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
        $tahun = Template::select('tahun')->where('jenis_id', 2)->orWhere('jenis_id',1)->orWhere('jenis_id',3)->distinct()->get();

        $int_count_10_pu = NULL;
        $int_count_10_pln = NULL;
        $int_count_10_kit = NULL;

        $input_tahun = $request->input('tahun_anggaran');
        $input_sb = $request->input('strategi_bisnis');
        $input_distrik = $request->input('distrik');
        $int_input_distrik = (int)$input_distrik;
        $input_lokasi = $request->input('lokasi');
        $int_input_lokasi = (int)$input_lokasi;
        $input_fase = $request->input('fase');
        $input_bulan = $request->input('bulan');
        $int_input_bulan = (int)$input_bulan;

        $nama_bln[0] = ''; 
        $nama_bln[1] = 'Januari'; 
        $nama_bln[2] = 'Februari'; 
        $nama_bln[3] = 'Maret'; 
        $nama_bln[4] = 'April'; 
        $nama_bln[5] = 'Mei'; 
        $nama_bln[6] = 'Juni'; 
        $nama_bln[7] = 'Juli'; 
        $nama_bln[8] = 'Agustus'; 
        $nama_bln[9] = 'September'; 
        $nama_bln[10] = 'Oktober'; 
        $nama_bln[11] = 'November'; 
        $nama_bln[12] = 'Desember'; 

        $nama_bln_dipilih = $nama_bln[$int_input_bulan];

        // $input_draft_form_10_pu = $this->get_drafts_ketetapan_ids(4,$int_input_distrik, $input_tahun);// get_drafts_form_10_pu_ids($int_input_distrik, $input_tahun, $input_fase);
        // $input_draft_form_10_pk = $this->get_drafts_ketetapan_ids(5,$int_input_distrik, $input_tahun);// get_drafts_form_10_pk_ids($int_input_distrik, $input_tahun, $input_fase);
        // $input_draft_form_10_pln = $this->get_drafts_ketetapan_ids(6,$int_input_distrik, $input_tahun);// get_drafts_form_10_pln_ids($int_input_distrik, $input_tahun, $input_fase);

        $input_draft_form_10_pu = $this->get_drafts_form_10_pu_ids($int_input_distrik, $input_tahun, $input_fase);
        $input_draft_form_10_pk = $this->get_drafts_form_10_pk_ids($int_input_distrik, $input_tahun, $input_fase);
        $input_draft_form_10_pln = $this->get_drafts_form_10_pln_ids($int_input_distrik, $input_tahun, $input_fase);

        $name_draft_form_10_pu = '';
        $name_draft_form_10_pk = '';
        $name_draft_form_10_pln = '';

        if($input_draft_form_10_pu){
            $name_draft_form_10_pu = $this->get_names(4, $input_draft_form_10_pu);
        }
        if($input_draft_form_10_pk){
            $name_draft_form_10_pk = $this->get_names(5, $input_draft_form_10_pk);
        }
        if($input_draft_form_10_pln){
            $name_draft_form_10_pln = $this->get_names(6, $input_draft_form_10_pln);
        }
        //dd($input_draft_form_10_pk_name[0]->name);

        // $temp = explode(",",  trim($input_draft_form_10_pk, "()"));
        // dd(explode(",",  trim($input_draft_form_10_pk, "()")));

        if ($request->input('strategi_bisnis') != NULL) {
            $input_sb = DB::table('strategi_bisnis')->select('name','id')->where('id', $request->input('strategi_bisnis'))->get()[0];
            $distrik = Distrik::select('name','id')->where('strategi_bisnis_id',$input_sb->id)->get();
        }
        if ($request->input('distrik') != NULL) {
            $input_distrik = DB::table('distrik')->select('name','id')->where('id', $request->distrik)->get()[0];
            $lokasi = Lokasi::select('name','id')->where('distrik_id',$input_distrik->id)->get();
        }
        if ($request->input('lokasi') != NULL) {
            $input_lokasi = DB::table('lokasi')->select('name','id')->where('id', $request->lokasi)->get()[0];
        }

        $input_lokasi = Lokasi::where('distrik_id', $request->distrik)->select("name", "id")->get();

        if ($request->input('fase') != NULL) {
            $input_fase = DB::table('fases')->select('name','id')->where('id', $request->fase)->get()[0];
        }
        /*
        if ($request->input('draft_form_10_pu') != NULL) {
            $input_draft_form_10_pu = DB::table('file_imports')->select('draft_versi', 'id', 'name')->where('id', $request->draft_form_10_pu)->get()[0];
            $draft_form_10_pu = $this->get_drafts(4,$int_input_distrik,$input_tahun);
        }
        if ($request->input('draft_form_10_pk') != NULL) {
            $input_draft_form_10_pk = DB::table('file_imports')->select('draft_versi', 'id', 'name')->where('id', $request->draft_form_10_pk)->get()[0];
            $draft_form_10_pk = $this->get_drafts(5,$int_input_distrik,$input_tahun);
        }
        if ($request->input('draft_form_10_pln') != NULL) {
            $input_draft_form_10_pln = DB::table('file_imports')->select('draft_versi', 'id', 'name')->where('id', $request->draft_form_10_pln)->get()[0];
            $draft_form_10_pln = $this->get_drafts(6,$int_input_distrik,$input_tahun);
        }
        */
        //dd($input_draft_form_10_pk);
        if ($input_distrik != NULL) {

            $dataparent = array();
            $datainti = array();
            $datakegiatan = array();
            //dd($dataparent);
        //Start Form 10 Penguatan KIT
        //if($request->input('draft_form_10_pk')) {
        if($input_draft_form_10_pk){
            //$count_lokasi_10_pk = DB::select("select count(distinct l.id) from excel_datas e, lokasi l where l.distrik_id = ".$input_distrik->id." and e.lokasi_id = l.id;")[0]->count;
            //dd((int)$count_lokasi_10_pk);

            // $count_10_pk = DB::select("select count(e.row)
            //                       from excel_datas e
            //                       join sheets s on s.id = e.sheet_id
            //                       join lokasi l on l.id = e.lokasi_id
            //                       where s.name like 'I-Form 10'
            //                       and e.file_import_id IN ".$input_draft_form_10_pk." and l.distrik_id = ".$int_input_distrik." and e.kolom = 'AM';")[0]->count;

            $count_10_pk = DB::select("select count(e.row)
                              from pgdl_excel_datas_revisi e
                              join sheets s on s.id = e.sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                              join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                              where s.name like 'I-Form 10'
                              and fk.id IN ".$input_draft_form_10_pk."
                              and l.distrik_id = ".$int_input_distrik."
                              and e.kolom = 'AM';")[0]->count;

            //Karena Prk duplikat, asal semua no_po dituliskan jadi count berdasarkan jumlah no_po
            // $count_10_pk = DB::select("select count(po_no)
            //                       from pgdl_pljprk_ai
            //                       where dstrct_code = ".$int_input_distrik." and "/*variabel apa mas yang isinya like 'I-Form 10'*/" and "/*variabel yang isinya draft_form_10_pk*/";")[0]->count;
            //dd($input_distrik);
            $int_count_10_pk = (int)$count_10_pk;

            $daftar_prk_kegiatan_form_10_pk = array();
            $daftar_prk_inti_form_10_pk = array();
            $daftar_prk_parent_form_10_pk = array();

            $form_10_pk_prk_parent = $this->get_form_10_parent($input_draft_form_10_pk,$int_input_distrik, 'H');
            foreach ($form_10_pk_prk_parent as $key => $value) {
                $daftar_prk_parent_form_10_pk[$value->value] = array('desc_prk_parent' => '',
                    'beban_mat'     => 0,
                    'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'total_year_estimate'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    'total_year_estimate_update' => 0,
                    'ai_ketetapan' => 0,
                    'ai_ketetapan_update' => 0,
                    );
            }

            $form_10_pk_prk_inti = $this->get_form_10_inti($input_draft_form_10_pk,$int_input_distrik, 'H');
            foreach ($form_10_pk_prk_inti as $key => $value) {
                $daftar_prk_inti_form_10_pk[$value->value] = array('desc_prk_inti' => '',
                    'prk_parent'    => substr($value->value, 0, 6),
                    'beban_mat'     => 0,
                    'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'total_year_estimate'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    'total_year_estimate_update' => 0,
                    'ai_ketetapan' => 0,
                    'ai_ketetapan_update' => 0,
                );
            }

            $form_10_pk_no_prk_kegiatan = $this->get_form_10($input_draft_form_10_pk,$int_input_distrik, 'H');
            $form_10_pk_desc_prk_kegiatan = $this->get_form_10($input_draft_form_10_pk,$int_input_distrik, 'S');
            $form_10_pk_desc_prk_inti = $this->get_form_10($input_draft_form_10_pk,$int_input_distrik, 'R');
            $form_10_pk_desc_prk_parent = $this->get_form_10($input_draft_form_10_pk,$int_input_distrik, 'Q');
            $form_10_pk_beban_mat = $this->get_form_10($input_draft_form_10_pk,$int_input_distrik, 'AI');
            $form_10_pk_ai_ketetapan = $this->get_form_10($input_draft_form_10_pk,$int_input_distrik, 'AN');
            $form_10_pk_total_year_estimate = $this->get_form_10($input_draft_form_10_pk,$int_input_distrik, 'AT');

            //dd($input_draft_form_10_pk.'--'.$int_input_distrik.'--'.'AN');

            $form_10_pk_ai_ketetapan_update = $this->get_form_10_update($input_draft_form_10_pk,$int_input_distrik, 'AN');
            $form_10_pk_total_year_estimate_update = $this->get_form_10_update($input_draft_form_10_pk,$int_input_distrik, 'AT');
            // $form_10_pk_cash_oth = $this->get_form_10($request->input('draft_form_10_pk'),$int_input_lokasi, 'AV');
            // $form_10_pk_ijin_proses = $this->get_form_10($request->input('draft_form_10_pk'),$int_input_lokasi, 'AX');

            $form_10_pk_disburse = array();

            $start_kolom = 'BC';
            for($bulan = 1; $bulan<=$int_input_bulan; $bulan++){
                $form_10_pk_disburse[$bulan] = $this->get_form_10_disburse($input_draft_form_10_pk,$int_input_distrik, $start_kolom);
                $start_kolom++;
            }
            //dd($form_10_pk_disburse);

/*
            $form_10_pk_disburse[1] = $this->get_form_10_disburse($request->input('draft_form_10_pk'),$int_input_distrik, 'BC');
            $form_10_pk_disburse[2] = $this->get_form_10_disburse($request->input('draft_form_10_pk'),$int_input_distrik, 'BD');
            $form_10_pk_disburse[3] = $this->get_form_10_disburse($request->input('draft_form_10_pk'),$int_input_distrik, 'BE');
            $form_10_pk_disburse[4] = $this->get_form_10_disburse($request->input('draft_form_10_pk'),$int_input_distrik, 'BF');
            $form_10_pk_disburse[5] = $this->get_form_10_disburse($request->input('draft_form_10_pk'),$int_input_distrik, 'BG');
            $form_10_pk_disburse[6] = $this->get_form_10_disburse($request->input('draft_form_10_pk'),$int_input_distrik, 'BH');
            $form_10_pk_disburse[7] = $this->get_form_10_disburse($request->input('draft_form_10_pk'),$int_input_distrik, 'BI');
            $form_10_pk_disburse[8] = $this->get_form_10_disburse($request->input('draft_form_10_pk'),$int_input_distrik, 'BJ');
            $form_10_pk_disburse[9] = $this->get_form_10_disburse($request->input('draft_form_10_pk'),$int_input_distrik, 'BK');
            $form_10_pk_disburse[10] = $this->get_form_10_disburse($request->input('draft_form_10_pk'),$int_input_distrik, 'BL');
            $form_10_pk_disburse[11] = $this->get_form_10_disburse($request->input('draft_form_10_pk'),$int_input_distrik, 'BM');
            $form_10_pk_disburse[12] = $this->get_form_10_disburse($request->input('draft_form_10_pk'),$int_input_distrik, 'BN');
            */
            //dd($form_10_pk_no_prk_kegiatan);

            // $form_10_pk_no_prk_kegiatan[0]->value = '183G0101';
            // $form_10_pk_no_prk_kegiatan[1]->value = '183G0101';
            // $form_10_pk_no_prk_kegiatan[2]->value = '183G0102';

            //for($i=0; $i<3; $i++){
            // dump($int_count_10_pk);
            for($i=0; $i<$int_count_10_pk; $i++){
                // dump($i, $form_10_pk_no_prk_kegiatan[$i]->value);

                $parent = substr($form_10_pk_no_prk_kegiatan[$i]->value,0,6);
                $inti = substr($form_10_pk_no_prk_kegiatan[$i]->value,0,8);

                //cek ada No PRK kegiatan
                $cek_no_prk_ketetapan = $this->cek_no_prk_form_10_ketetapan($input_draft_form_10_pk,$int_input_distrik, 'H', $form_10_pk_no_prk_kegiatan[$i]->value);
                // dump($cek_no_prk_ketetapan);
                if($cek_no_prk_ketetapan) {
                    // 'ai_ketetapan' => (float)$form_10_pk_ai_ketetapan[$i]->value,
                    $ai_form_10_pk_ketetapan[$i] = $this->get_form_10_ketetapan($input_draft_form_10_pk,$int_input_distrik, 'AN', 'H', $form_10_pk_no_prk_kegiatan[$i]->value)[0]->value;
                    // 'total_year_estimate' => (float)$form_10_pk_total_year_estimate[$i]->value,
                    $aki_form_10_pk_ketetapan[$i] = $this->get_form_10_ketetapan($input_draft_form_10_pk,$int_input_distrik, 'AT', 'H', $form_10_pk_no_prk_kegiatan[$i]->value)[0]->value;
                }
                else {
                    $ai_form_10_pk_ketetapan[$i] = 0;
                    $aki_form_10_pk_ketetapan[$i] = 0;
                }

                //$form_10_pk_no_prk_kegiatan[$i]->value = 'BR183G0102';
                //dd(substr($form_10_pk_no_prk_kegiatan[$i]->value,2));
                //dd($int_input_bulan);
                $dist_po_no = $this->get_no_po($form_10_pk_no_prk_kegiatan[$i]->value, $int_input_bulan);
                    // dump($form_10_pk_no_prk_kegiatan[$i]->value, $dist_po_no);
                // if($form_10_pk_no_prk_kegiatan[$i]->value == 'PT184A0403')
                // dump($dist_po_no, $form_10_pk_no_prk_kegiatan[$i]->value, $i);
                // dump($i, $form_10_pk_no_prk_kegiatan[$i]->value);
//-------------------------------------------diakali disini------------------------------------------------------------
                if($dist_po_no){
                    // dump('if');
                  foreach ($dist_po_no as $key => $value) {
                    //dd($value->po_no);

                    $item_po = empty($this->get_itemp_po($form_10_pk_no_prk_kegiatan[$i]->value, $int_input_bulan, $value->po_no, $value->po_item, $value->account_code)) ? '' : $this->get_itemp_po($form_10_pk_no_prk_kegiatan[$i]->value, $int_input_bulan, $value->po_no, $value->po_item, $value->account_code)[0];//$value->po_no);
                    // if($form_10_pk_no_prk_kegiatan[$i]->value == 'PT184A0403')
                        // dump('item po:', $item_po, $i);

                    $temp = array(
                        'prk_kegiatan' => $form_10_pk_no_prk_kegiatan[$i]->value,
                        'prk_inti' => $inti,
                        'prk_parent' => $parent,
                        'desc_prk_kegiatan' => $form_10_pk_desc_prk_kegiatan[$i]->value,
                        'desc_prk_inti' => $form_10_pk_desc_prk_inti[$i]->value,
                        'desc_prk_parent' => $form_10_pk_desc_prk_parent[$i]->value,
                        'beban_mat' => (float)$form_10_pk_beban_mat[$i]->value,
                        'cash_oth' => 0,
                        'ijin_proses' => 0,
                        // 'ai_ketetapan' => (float)$form_10_pk_ai_ketetapan[$i]->value,
                        // 'total_year_estimate' => (float)$form_10_pk_total_year_estimate[$i]->value,
                        'ai_ketetapan' => (float)$ai_form_10_pk_ketetapan[$i],
                        'total_year_estimate' => (float)$aki_form_10_pk_ketetapan[$i],
                        'ai_ketetapan_update' => (float)$form_10_pk_ai_ketetapan_update[$i]->value,
                        'total_year_estimate_update' => (float)$form_10_pk_total_year_estimate_update[$i]->value,
                        'po_no' => $value->po_no,
                        // 'item_po' => $item_po->total_item,
                        'item_po' => $value->po_item,
                        'account_code' => $value->account_code,
                        'kontrak' => (!$item_po) ? '' : $item_po->kontrak,
                        'disburse' => (!$item_po) ? '' : $item_po->disburse,
                    );
                    array_push($daftar_prk_kegiatan_form_10_pk, $temp);

                    //dd($daftar_prk_kegiatan_form_10_pk);

                      $daftar_prk_inti_form_10_pk[$inti]['desc_prk_inti'] = $temp['desc_prk_inti'];
                      $daftar_prk_inti_form_10_pk[$inti]['beban_mat'] += $temp['beban_mat'];
                      $daftar_prk_inti_form_10_pk[$inti]['total_year_estimate'] += $temp['total_year_estimate'];
                      $daftar_prk_inti_form_10_pk[$inti]['ai_ketetapan'] += $temp['ai_ketetapan'];
                      $daftar_prk_inti_form_10_pk[$inti]['total_year_estimate_update'] += $temp['total_year_estimate_update'];
                      $daftar_prk_inti_form_10_pk[$inti]['ai_ketetapan_update'] += $temp['ai_ketetapan_update'];

                      $daftar_prk_parent_form_10_pk[$parent]['desc_prk_parent'] = $temp['desc_prk_parent'];
                      $daftar_prk_parent_form_10_pk[$parent]['beban_mat'] += $temp['beban_mat'];
                      $daftar_prk_parent_form_10_pk[$parent]['total_year_estimate'] += $temp['total_year_estimate'];
                      $daftar_prk_parent_form_10_pk[$parent]['ai_ketetapan'] += $temp['ai_ketetapan'];
                      $daftar_prk_parent_form_10_pk[$parent]['total_year_estimate_update'] += $temp['total_year_estimate_update'];
                      $daftar_prk_parent_form_10_pk[$parent]['ai_ketetapan_update'] += $temp['ai_ketetapan_update'];
                  }
                }
                else{
                  $temp = array(
                      'prk_kegiatan' => $form_10_pk_no_prk_kegiatan[$i]->value,
                      'prk_inti' => $inti,
                      'prk_parent' => $parent,
                      'desc_prk_kegiatan' => $form_10_pk_desc_prk_kegiatan[$i]->value,
                      'desc_prk_inti' => $form_10_pk_desc_prk_inti[$i]->value,
                      'desc_prk_parent' => $form_10_pk_desc_prk_parent[$i]->value,
                      'beban_mat' => (float)$form_10_pk_beban_mat[$i]->value,
                      'cash_oth' => 0,
                      'ijin_proses' => 0,
                      // 'ai_ketetapan' => (float)$form_10_pk_ai_ketetapan[$i]->value,
                      // 'total_year_estimate' => (float)$form_10_pk_total_year_estimate[$i]->value,
                      'ai_ketetapan' => (float)$ai_form_10_pk_ketetapan[$i],
                      'total_year_estimate' => (float)$aki_form_10_pk_ketetapan[$i],
                      'ai_ketetapan_update' => (float)$form_10_pk_ai_ketetapan_update[$i]->value,
                      'total_year_estimate_update' => (float)$form_10_pk_total_year_estimate_update[$i]->value,
                      'po_no' => 0,
                      'item_po' => 0,
                      'account_code' => 0,
                      'kontrak' => 0,
                      'disburse' => 0,
                  );
                  array_push($daftar_prk_kegiatan_form_10_pk, $temp);

                    $daftar_prk_inti_form_10_pk[$inti]['desc_prk_inti'] = $temp['desc_prk_inti'];
                    $daftar_prk_inti_form_10_pk[$inti]['beban_mat'] += $temp['beban_mat'];
                    $daftar_prk_inti_form_10_pk[$inti]['total_year_estimate'] += $temp['total_year_estimate'];
                    $daftar_prk_inti_form_10_pk[$inti]['ai_ketetapan'] += $temp['ai_ketetapan'];
                    $daftar_prk_inti_form_10_pk[$inti]['total_year_estimate_update'] += $temp['total_year_estimate_update'];
                    $daftar_prk_inti_form_10_pk[$inti]['ai_ketetapan_update'] += $temp['ai_ketetapan_update'];

                    $daftar_prk_parent_form_10_pk[$parent]['desc_prk_parent'] = $temp['desc_prk_parent'];
                    $daftar_prk_parent_form_10_pk[$parent]['beban_mat'] += $temp['beban_mat'];
                    $daftar_prk_parent_form_10_pk[$parent]['total_year_estimate'] += $temp['total_year_estimate'];
                    $daftar_prk_parent_form_10_pk[$parent]['ai_ketetapan'] += $temp['ai_ketetapan'];
                    $daftar_prk_parent_form_10_pk[$parent]['total_year_estimate_update'] += $temp['total_year_estimate_update'];
                    $daftar_prk_parent_form_10_pk[$parent]['ai_ketetapan_update'] += $temp['ai_ketetapan_update'];
                }

                // $disburse = array();
                // for($bulan=1; $bulan<=$int_input_bulan; $bulan++){
                //     $disburse[$bulan] = (float)$form_10_pk_disburse[$bulan][$i]->value;
                //     // $daftar_prk_inti_form_10_pk[$inti]['disburse'][$bulan] += $disburse[$bulan];
                //     // $daftar_prk_parent_form_10_pk[$parent]['disburse'][$bulan] += $disburse[$bulan];
                // }

                //dd($daftar_prk_kegiatan_form_10_pk);

            }
            $dataparent['form_10_pk'] = $daftar_prk_parent_form_10_pk;
            $datainti['form_10_pk'] = $daftar_prk_inti_form_10_pk;
            $datakegiatan['form_10_pk'] = $daftar_prk_kegiatan_form_10_pk;
            // dump($dataparent['form_10_pk']);
            // dump($datainti['form_10_pk']);
            // dump($datakegiatan['form_10_pk']);

        } //end of cek request draft form 10 kit
        //End of query form 10 Penguatan KIT

        //Start Form 10 Pengembangan Usaha
        //if($request->input('draft_form_10_pu')) {
        if($input_draft_form_10_pu){
            // $count_10_pu = DB::select("select count(e.row)
            //                           from excel_datas e
            //                           join sheets s on s.id = e.sheet_id
            //                           join lokasi l on l.id = e.lokasi_id
            //                           where s.name like 'I-Form 10'
            //                           and e.file_import_id IN ".$input_draft_form_10_pu." and l.distrik_id = ".$int_input_distrik." and e.kolom = 'AM';")[0]->count;

            $count_10_pu = DB::select("select count(e.row)
                              from pgdl_excel_datas_revisi e
                              join sheets s on s.id = e.sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                              join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                              where s.name like 'I-Form 10'
                              and fk.id IN ".$input_draft_form_10_pu."
                              and l.distrik_id = ".$int_input_distrik."
                              and e.kolom = 'AM';")[0]->count;

            $int_count_10_pu = (int)$count_10_pu;

            $daftar_prk_kegiatan_form_10_pu = array();
            $daftar_prk_inti_form_10_pu = array();
            $daftar_prk_parent_form_10_pu = array();

            $form_10_pu_prk_parent = $this->get_form_10_parent($input_draft_form_10_pu,$int_input_distrik, 'I');
            foreach ($form_10_pu_prk_parent as $key => $value) {
                $daftar_prk_parent_form_10_pu[$value->value] = array('desc_prk_parent' => '',
                    'beban_mat'     => 0,
                    'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'total_year_estimate'   => 0,
                    'total_year_estimate_update'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    'ai_ketetapan' => 0,
                    'ai_ketetapan_update' => 0,
                    );
            }

            $form_10_pu_prk_inti = $this->get_form_10_inti($input_draft_form_10_pu,$int_input_distrik, 'I');
            foreach ($form_10_pu_prk_inti as $key => $value) {
                $daftar_prk_inti_form_10_pu[$value->value] = array('desc_prk_inti' => '',
                    'prk_parent'    => substr($value->value, 0, 6),
                    'beban_mat'     => 0,
                    'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'total_year_estimate'   => 0,
                    'total_year_estimate_update'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    'ai_ketetapan' => 0,
                    'ai_ketetapan_update' => 0,
                );
            }

            $form_10_pu_no_prk_kegiatan = $this->get_form_10($input_draft_form_10_pu,$int_input_distrik, 'I');
            $form_10_pu_desc_prk_kegiatan = $this->get_form_10($input_draft_form_10_pu,$int_input_distrik, 'T');
            $form_10_pu_desc_prk_inti = $this->get_form_10($input_draft_form_10_pu,$int_input_distrik, 'S');
            $form_10_pu_desc_prk_parent = $this->get_form_10($input_draft_form_10_pu,$int_input_distrik, 'R');
            $form_10_pu_beban_mat = $this->get_form_10($input_draft_form_10_pu,$int_input_distrik, 'AJ');
            $form_10_pu_ai_ketetapan = $this->get_form_10($input_draft_form_10_pu,$int_input_distrik, 'AO');
            $form_10_pu_total_year_estimate = $this->get_form_10($input_draft_form_10_pu,$int_input_distrik, 'AU');

            $form_10_pu_ai_ketetapan_update = $this->get_form_10_update($input_draft_form_10_pu,$int_input_distrik, 'AO');
            $form_10_pu_total_year_estimate_update = $this->get_form_10_update($input_draft_form_10_pu,$int_input_distrik, 'AU');
            // $form_10_pu_cash_oth = $this->get_form_10($request->input('draft_form_10_pu'),$int_input_lokasi, 'AV');
            // $form_10_pu_ijin_proses = $this->get_form_10($request->input('draft_form_10_pu'),$int_input_lokasi, 'AX');
            $form_10_pu_disburse = array();

            $start_kolom = 'BI';
            for($bulan = 1; $bulan<=$int_input_bulan; $bulan++){
                $form_10_pu_disburse[$bulan] = $this->get_form_10_disburse($input_draft_form_10_pu,$int_input_distrik, $start_kolom);
                $start_kolom++;
            }
            //dd($form_10_pu_disburse);

/*
            $form_10_pu_disburse[1] = $this->get_form_10_disburse($request->input('draft_form_10_pu'),$int_input_distrik, 'BI');
            $form_10_pu_disburse[2] = $this->get_form_10_disburse($request->input('draft_form_10_pu'),$int_input_distrik, 'BJ');
            $form_10_pu_disburse[3] = $this->get_form_10_disburse($request->input('draft_form_10_pu'),$int_input_distrik, 'BK');
            $form_10_pu_disburse[4] = $this->get_form_10_disburse($request->input('draft_form_10_pu'),$int_input_distrik, 'BL');
            $form_10_pu_disburse[5] = $this->get_form_10_disburse($request->input('draft_form_10_pu'),$int_input_distrik, 'BM');
            $form_10_pu_disburse[6] = $this->get_form_10_disburse($request->input('draft_form_10_pu'),$int_input_distrik, 'BN');
            $form_10_pu_disburse[7] = $this->get_form_10_disburse($request->input('draft_form_10_pu'),$int_input_distrik, 'BO');
            $form_10_pu_disburse[8] = $this->get_form_10_disburse($request->input('draft_form_10_pu'),$int_input_distrik, 'BP');
            $form_10_pu_disburse[9] = $this->get_form_10_disburse($request->input('draft_form_10_pu'),$int_input_distrik, 'BQ');
            $form_10_pu_disburse[10] = $this->get_form_10_disburse($request->input('draft_form_10_pu'),$int_input_distrik, 'BR');
            $form_10_pu_disburse[11] = $this->get_form_10_disburse($request->input('draft_form_10_pu'),$int_input_distrik, 'BS');
            $form_10_pu_disburse[12] = $this->get_form_10_disburse($request->input('draft_form_10_pu'),$int_input_distrik, 'BT');
*/

            for($i=0; $i<$int_count_10_pu; $i++){
                $parent = substr($form_10_pu_no_prk_kegiatan[$i]->value,0,6);
                $inti = substr($form_10_pu_no_prk_kegiatan[$i]->value,0,8);

                //cek ada No PRK kegiatan
                $cek_no_prk_ketetapan = $this->cek_no_prk_form_10_ketetapan($input_draft_form_10_pu,$int_input_distrik, 'I', $form_10_pu_no_prk_kegiatan[$i]->value);
                // dump($cek_no_prk_ketetapan);
                if($cek_no_prk_ketetapan) {
                    $ai_form_10_pu_ketetapan[$i] = $this->get_form_10_ketetapan($input_draft_form_10_pu,$int_input_distrik, 'AO', 'I', $form_10_pu_no_prk_kegiatan[$i]->value)[0]->value;
                    $aki_form_10_pu_ketetapan[$i] = $this->get_form_10_ketetapan($input_draft_form_10_pu,$int_input_distrik, 'AU', 'I', $form_10_pu_no_prk_kegiatan[$i]->value)[0]->value;
                }
                else {
                    $ai_form_10_pu_ketetapan[$i] = 0;
                    $aki_form_10_pu_ketetapan[$i] = 0;
                }

                $dist_po_no = $this->get_no_po($form_10_pu_no_prk_kegiatan[$i]->value, $int_input_bulan);

//-------------------------------------------diakali disini------------------------------------------------------------
                if($dist_po_no){
                  foreach ($dist_po_no as $key => $value) {
                    $item_po = empty($this->get_itemp_po($form_10_pu_no_prk_kegiatan[$i]->value, $int_input_bulan, $value->po_no, $value->po_item, $value->account_code)) ? '' : $this->get_itemp_po($form_10_pu_no_prk_kegiatan[$i]->value, $int_input_bulan, $value->po_no, $value->po_item, $value->account_code)[0];

                    $temp = array(
                        'prk_kegiatan' => $form_10_pu_no_prk_kegiatan[$i]->value,
                        'prk_inti' => $inti,
                        'prk_parent' => $parent,
                        'desc_prk_kegiatan' => $form_10_pu_desc_prk_kegiatan[$i]->value,
                        'desc_prk_inti' => $form_10_pu_desc_prk_inti[$i]->value,
                        'desc_prk_parent' => $form_10_pu_desc_prk_parent[$i]->value,
                        'beban_mat' => (float)$form_10_pu_beban_mat[$i]->value,
                        'cash_oth' => 0,
                        'ijin_proses' => 0,
                        // 'ai_ketetapan' => (float)$form_10_pu_ai_ketetapan[$i]->value,
                        // 'total_year_estimate' => (float)$form_10_pu_total_year_estimate[$i]->value,
                        'ai_ketetapan' => (float)$ai_form_10_pu_ketetapan[$i],
                        'total_year_estimate' => (float)$aki_form_10_pu_ketetapan[$i],
                        'ai_ketetapan_update' => (float)$form_10_pu_ai_ketetapan_update[$i]->value,
                        'total_year_estimate_update' => (float)$form_10_pu_total_year_estimate_update[$i]->value,
                        'po_no' => $value->po_no,
                        // 'item_po' => $item_po->total_item,
                        'item_po' => $value->po_item,
                        'account_code' => $value->account_code,
                        'kontrak' => (!$item_po) ? '' : $item_po->kontrak,
                        'disburse' => (!$item_po) ? '' : $item_po->disburse,

                    );
                    array_push($daftar_prk_kegiatan_form_10_pu, $temp);

                    $daftar_prk_inti_form_10_pu[$inti]['desc_prk_inti'] = $temp['desc_prk_inti'];
                    $daftar_prk_inti_form_10_pu[$inti]['beban_mat'] += $temp['beban_mat'];
                    $daftar_prk_inti_form_10_pu[$inti]['total_year_estimate'] += $temp['total_year_estimate'];
                    $daftar_prk_inti_form_10_pu[$inti]['ai_ketetapan'] += $temp['ai_ketetapan'];
                    $daftar_prk_inti_form_10_pu[$inti]['total_year_estimate_update'] += $temp['total_year_estimate_update'];
                    $daftar_prk_inti_form_10_pu[$inti]['ai_ketetapan_update'] += $temp['ai_ketetapan_update'];

                    $daftar_prk_parent_form_10_pu[$parent]['desc_prk_parent'] = $temp['desc_prk_parent'];
                    $daftar_prk_parent_form_10_pu[$parent]['beban_mat'] += $temp['beban_mat'];
                    $daftar_prk_parent_form_10_pu[$parent]['total_year_estimate'] += $temp['total_year_estimate'];
                    $daftar_prk_parent_form_10_pu[$parent]['ai_ketetapan'] += $temp['ai_ketetapan'];
                    $daftar_prk_parent_form_10_pu[$parent]['total_year_estimate_update'] += $temp['total_year_estimate_update'];
                    $daftar_prk_parent_form_10_pu[$parent]['ai_ketetapan_update'] += $temp['ai_ketetapan_update'];
                  }
                }
                else{
                    $temp = array(
                        'prk_kegiatan' => $form_10_pu_no_prk_kegiatan[$i]->value,
                        'prk_inti' => $inti,
                        'prk_parent' => $parent,
                        'desc_prk_kegiatan' => $form_10_pu_desc_prk_kegiatan[$i]->value,
                        'desc_prk_inti' => $form_10_pu_desc_prk_inti[$i]->value,
                        'desc_prk_parent' => $form_10_pu_desc_prk_parent[$i]->value,
                        'beban_mat' => (float)$form_10_pu_beban_mat[$i]->value,
                        'cash_oth' => 0,
                        'ijin_proses' => 0,
                        'total_year_estimate' => (float)$form_10_pu_total_year_estimate[$i]->value,
                        'ai_ketetapan' => (float)$form_10_pu_ai_ketetapan[$i]->value,
                        'total_year_estimate_update' => (float)$form_10_pu_total_year_estimate_update[$i]->value,
                        'ai_ketetapan_update' => (float)$form_10_pu_ai_ketetapan_update[$i]->value,
                        'po_no' => 0,
                        'item_po' => 0,
                        'account_code' => 0,
                        'kontrak' => 0,
                        'disburse' => 0,
                    );
                    array_push($daftar_prk_kegiatan_form_10_pu, $temp);

                    $daftar_prk_inti_form_10_pu[$inti]['desc_prk_inti'] = $temp['desc_prk_inti'];
                    $daftar_prk_inti_form_10_pu[$inti]['beban_mat'] += $temp['beban_mat'];
                    $daftar_prk_inti_form_10_pu[$inti]['total_year_estimate'] += $temp['total_year_estimate'];
                    $daftar_prk_inti_form_10_pu[$inti]['ai_ketetapan'] += $temp['ai_ketetapan'];
                    $daftar_prk_inti_form_10_pu[$inti]['total_year_estimate_update'] += $temp['total_year_estimate_update'];
                    $daftar_prk_inti_form_10_pu[$inti]['ai_ketetapan_update'] += $temp['ai_ketetapan_update'];

                    $daftar_prk_parent_form_10_pu[$parent]['desc_prk_parent'] = $temp['desc_prk_parent'];
                    $daftar_prk_parent_form_10_pu[$parent]['beban_mat'] += $temp['beban_mat'];
                    $daftar_prk_parent_form_10_pu[$parent]['total_year_estimate'] += $temp['total_year_estimate'];
                    $daftar_prk_parent_form_10_pu[$parent]['ai_ketetapan'] += $temp['ai_ketetapan'];
                    $daftar_prk_parent_form_10_pu[$parent]['total_year_estimate_update'] += $temp['total_year_estimate_update'];
                    $daftar_prk_parent_form_10_pu[$parent]['ai_ketetapan_update'] += $temp['ai_ketetapan_update'];
                }

                // $disburse = array();
                // for($bulan=1; $bulan<=$int_input_bulan; $bulan++){
                //     $disburse[$bulan] = (float)$form_10_pu_disburse[$bulan][$i]->value;
                //     // $daftar_prk_inti_form_10_pu[$inti]['disburse'][$bulan] += $disburse[$bulan];
                //     // $daftar_prk_parent_form_10_pu[$parent]['disburse'][$bulan] += $disburse[$bulan];
                // }

            }
            $dataparent['form_10_pu'] = $daftar_prk_parent_form_10_pu;
            $datainti['form_10_pu'] = $daftar_prk_inti_form_10_pu;
            $datakegiatan['form_10_pu'] = $daftar_prk_kegiatan_form_10_pu;
        } //end of cek request draft form 10 pengembangan usaha
        //End of query form 10 Pengembangan Usaha

        //Start Form 10 PLN
        if($input_draft_form_10_pln) {
            // $count_10_pln = DB::select("select count(e.row)
            //                           from excel_datas e
            //                           join sheets s on s.id = e.sheet_id
            //                           join lokasi l on l.id = e.lokasi_id
            //                           where s.name like 'I-Form 10'
            //                           and e.file_import_id IN ".$input_draft_form_10_pln." and l.distrik_id = ".$int_input_distrik." and e.kolom = 'AM';")[0]->count;

            $count_10_pln = DB::select("select count(e.row)
                              from pgdl_excel_datas_revisi e
                              join sheets s on s.id = e.sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                              join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                              where s.name like 'I-Form 10'
                              and fk.id IN ".$input_draft_form_10_pln."
                              and l.distrik_id = ".$int_input_distrik."
                              and e.kolom = 'AM';")[0]->count;

            $int_count_10_pln = (int)$count_10_pln;

            $daftar_prk_kegiatan_form_10_pln = array();
            $daftar_prk_inti_form_10_pln = array();
            $daftar_prk_parent_form_10_pln = array();

            $form_10_pln_prk_parent = $this->get_form_10_parent($input_draft_form_10_pln,$int_input_distrik, 'J');
            foreach ($form_10_pln_prk_parent as $key => $value) {
                $daftar_prk_parent_form_10_pln[$value->value] = array('desc_prk_parent' => '',
                    'beban_mat'     => 0,
                    'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'total_year_estimate'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    'ai_ketetapan' => 0,
                    'total_year_estimate_update'   => 0,
                    'ai_ketetapan_update' => 0,
                    );
            }

            $form_10_pln_prk_inti = $this->get_form_10_inti($input_draft_form_10_pln,$int_input_distrik, 'J');
            foreach ($form_10_pln_prk_inti as $key => $value) {
                $daftar_prk_inti_form_10_pln[$value->value] = array('desc_prk_inti' => '',
                    'prk_parent'    => substr($value->value, 0, 6),
                    'beban_mat'     => 0,
                    'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'total_year_estimate'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    'ai_ketetapan' => 0,
                    'total_year_estimate_update'   => 0,
                    'ai_ketetapan_update' => 0,
                );
            }

            $form_10_pln_no_prk_kegiatan = $this->get_form_10($input_draft_form_10_pln,$int_input_distrik, 'J');
            $form_10_pln_desc_prk_kegiatan = $this->get_form_10($input_draft_form_10_pln,$int_input_distrik, 'U');
            $form_10_pln_desc_prk_inti = $this->get_form_10($input_draft_form_10_pln,$int_input_distrik, 'T');
            $form_10_pln_desc_prk_parent = $this->get_form_10($input_draft_form_10_pln,$int_input_distrik, 'S');
            $form_10_pln_beban_mat = $this->get_form_10($input_draft_form_10_pln,$int_input_distrik, 'AK');
            $form_10_pln_ai_ketetapan = $this->get_form_10($input_draft_form_10_pln,$int_input_distrik, 'AP');
            $form_10_pln_total_year_estimate = $this->get_form_10($input_draft_form_10_pln,$int_input_distrik, 'AR');

            $form_10_pln_ai_ketetapan_update = $this->get_form_10_update($input_draft_form_10_pln,$int_input_distrik, 'AP');
            $form_10_pln_total_year_estimate_update = $this->get_form_10_update($input_draft_form_10_pln,$int_input_distrik, 'AR');

            $form_10_pln_disburse = array();

            $start_kolom = 'AX';
            for($bulan = 1; $bulan<=$int_input_bulan; $bulan++){
                $form_10_pln_disburse[$bulan] = $this->get_form_10_disburse($input_draft_form_10_pln,$int_input_distrik, $start_kolom);
                $start_kolom++;
            }
            //dd($form_10_pln_disburse);

/*
            $form_10_pln_disburse[1] = $this->get_form_10_disburse($request->input('draft_form_10_pln'),$int_input_distrik, 'AX');
            $form_10_pln_disburse[2] = $this->get_form_10_disburse($request->input('draft_form_10_pln'),$int_input_distrik, 'AY');
            $form_10_pln_disburse[3] = $this->get_form_10_disburse($request->input('draft_form_10_pln'),$int_input_distrik, 'AZ');
            $form_10_pln_disburse[4] = $this->get_form_10_disburse($request->input('draft_form_10_pln'),$int_input_distrik, 'BA');
            $form_10_pln_disburse[5] = $this->get_form_10_disburse($request->input('draft_form_10_pln'),$int_input_distrik, 'BB');
            $form_10_pln_disburse[6] = $this->get_form_10_disburse($request->input('draft_form_10_pln'),$int_input_distrik, 'BC');
            $form_10_pln_disburse[7] = $this->get_form_10_disburse($request->input('draft_form_10_pln'),$int_input_distrik, 'BD');
            $form_10_pln_disburse[8] = $this->get_form_10_disburse($request->input('draft_form_10_pln'),$int_input_distrik, 'BE');
            $form_10_pln_disburse[9] = $this->get_form_10_disburse($request->input('draft_form_10_pln'),$int_input_distrik, 'BF');
            $form_10_pln_disburse[10] = $this->get_form_10_disburse($request->input('draft_form_10_pln'),$int_input_distrik, 'BG');
            $form_10_pln_disburse[11] = $this->get_form_10_disburse($request->input('draft_form_10_pln'),$int_input_distrik, 'BH');
            $form_10_pln_disburse[12] = $this->get_form_10_disburse($request->input('draft_form_10_pln'),$int_input_distrik, 'BI');
*/
            for($i=0; $i<$int_count_10_pln; $i++){
                $parent = substr($form_10_pln_no_prk_kegiatan[$i]->value,0,6);
                $inti = substr($form_10_pln_no_prk_kegiatan[$i]->value,0,8);

                //cek ada No PRK kegiatan
                $cek_no_prk_ketetapan = $this->cek_no_prk_form_10_ketetapan($input_draft_form_10_pln,$int_input_distrik, 'J', $form_10_pln_no_prk_kegiatan[$i]->value);
                // dump($cek_no_prk_ketetapan);
                if($cek_no_prk_ketetapan) {
                    $ai_form_10_pln_ketetapan[$i] = $this->get_form_10_ketetapan($input_draft_form_10_pln,$int_input_distrik, 'AP', 'J', $form_10_pln_no_prk_kegiatan[$i]->value)[0]->value;
                    $aki_form_10_pln_ketetapan[$i] = $this->get_form_10_ketetapan($input_draft_form_10_pln,$int_input_distrik, 'AR', 'J', $form_10_pln_no_prk_kegiatan[$i]->value)[0]->value;
                }
                else {
                    $ai_form_10_pln_ketetapan[$i] = 0;
                    $aki_form_10_pln_ketetapan[$i] = 0;
                }

                $dist_po_no = $this->get_no_po($form_10_pln_no_prk_kegiatan[$i]->value, $int_input_bulan);

//-------------------------------------------diakali disini------------------------------------------------------------
                if($dist_po_no){
                  foreach ($dist_po_no as $key => $value) {
                    $item_po = empty($this->get_itemp_po($form_10_pln_no_prk_kegiatan[$i]->value, $int_input_bulan, $value->po_no, $value->po_item, $value->account_code)) ? '' : $this->get_itemp_po($form_10_pln_no_prk_kegiatan[$i]->value, $int_input_bulan, $value->po_no, $value->po_item, $value->account_code)[0];

                    $temp = array(
                        'prk_kegiatan' => $form_10_pln_no_prk_kegiatan[$i]->value,
                        'prk_inti' => $inti,
                        'prk_parent' => $parent,
                        'desc_prk_kegiatan' => $form_10_pln_desc_prk_kegiatan[$i]->value,
                        'desc_prk_inti' => $form_10_pln_desc_prk_inti[$i]->value,
                        'desc_prk_parent' => $form_10_pln_desc_prk_parent[$i]->value,
                        'beban_mat' => ((float)$form_10_pln_beban_mat[$i]->value) / 1.1,
                        'cash_oth' => 0,
                        'ijin_proses' => 0,
                        // 'ai_ketetapan' => (float)$form_10_pln_ai_ketetapan[$i]->value,
                        // 'total_year_estimate' => (float)$form_10_pln_total_year_estimate[$i]->value,
                        'ai_ketetapan' => (float)$ai_form_10_pln_ketetapan[$i],
                        'total_year_estimate' => (float)$aki_form_10_pln_ketetapan[$i],
                        'ai_ketetapan_update' => (float)$form_10_pln_ai_ketetapan_update[$i]->value,
                        'total_year_estimate_update' => (float)$form_10_pln_total_year_estimate_update[$i]->value,
                        'po_no' => $value->po_no,
                        // 'item_po' => $item_po->total_item,
                        'item_po' => $value->po_item,
                        'account_code' => $value->account_code,
                        'kontrak' => (!$item_po) ? '' : $item_po->kontrak,
                        'disburse' => (!$item_po) ? '' : $item_po->disburse,
                    );
                    array_push($daftar_prk_kegiatan_form_10_pln, $temp);

                    $daftar_prk_inti_form_10_pln[$inti]['desc_prk_inti'] = $temp['desc_prk_inti'];
                    $daftar_prk_inti_form_10_pln[$inti]['beban_mat'] += $temp['beban_mat'];
                    $daftar_prk_inti_form_10_pln[$inti]['total_year_estimate'] += $temp['total_year_estimate'];
                    $daftar_prk_inti_form_10_pln[$inti]['ai_ketetapan'] += $temp['ai_ketetapan'];
                    $daftar_prk_inti_form_10_pln[$inti]['total_year_estimate_update'] += $temp['total_year_estimate_update'];
                    $daftar_prk_inti_form_10_pln[$inti]['ai_ketetapan_update'] += $temp['ai_ketetapan_update'];

                    $daftar_prk_parent_form_10_pln[$parent]['desc_prk_parent'] = $temp['desc_prk_parent'];
                    $daftar_prk_parent_form_10_pln[$parent]['beban_mat'] += $temp['beban_mat'];
                    $daftar_prk_parent_form_10_pln[$parent]['total_year_estimate'] += $temp['total_year_estimate'];
                    $daftar_prk_parent_form_10_pln[$parent]['ai_ketetapan'] += $temp['ai_ketetapan'];
                    $daftar_prk_parent_form_10_pln[$parent]['total_year_estimate_update'] += $temp['total_year_estimate_update'];
                    $daftar_prk_parent_form_10_pln[$parent]['ai_ketetapan_update'] += $temp['ai_ketetapan_update'];
                  }
                }
                else{
                    $temp = array(
                        'prk_kegiatan' => $form_10_pln_no_prk_kegiatan[$i]->value,
                        'prk_inti' => $inti,
                        'prk_parent' => $parent,
                        'desc_prk_kegiatan' => $form_10_pln_desc_prk_kegiatan[$i]->value,
                        'desc_prk_inti' => $form_10_pln_desc_prk_inti[$i]->value,
                        'desc_prk_parent' => $form_10_pln_desc_prk_parent[$i]->value,
                        'beban_mat' => ((float)$form_10_pln_beban_mat[$i]->value) / 1.1,
                        'cash_oth' => 0,
                        'ijin_proses' => 0,
                        // 'ai_ketetapan' => (float)$form_10_pln_ai_ketetapan[$i]->value,
                        // 'total_year_estimate' => (float)$form_10_pln_total_year_estimate[$i]->value,
                        'ai_ketetapan' => (float)$ai_form_10_pln_ketetapan[$i],
                        'total_year_estimate' => (float)$aki_form_10_pln_ketetapan[$i],
                        'ai_ketetapan_update' => (float)$form_10_pln_ai_ketetapan_update[$i]->value,
                        'total_year_estimate_update' => (float)$form_10_pln_total_year_estimate_update[$i]->value,
                        'po_no' => 0,
                        'item_po' => 0,
                        'account_code' => 0,
                        'kontrak' => 0,
                        'disburse' => 0,
                    );
                    array_push($daftar_prk_kegiatan_form_10_pln, $temp);

                    $daftar_prk_inti_form_10_pln[$inti]['desc_prk_inti'] = $temp['desc_prk_inti'];
                    $daftar_prk_inti_form_10_pln[$inti]['beban_mat'] += $temp['beban_mat'];
                    $daftar_prk_inti_form_10_pln[$inti]['total_year_estimate'] += $temp['total_year_estimate'];
                    $daftar_prk_inti_form_10_pln[$inti]['ai_ketetapan'] += $temp['ai_ketetapan'];
                    $daftar_prk_inti_form_10_pln[$inti]['total_year_estimate_update'] += $temp['total_year_estimate_update'];
                    $daftar_prk_inti_form_10_pln[$inti]['ai_ketetapan_update'] += $temp['ai_ketetapan_update'];

                    $daftar_prk_parent_form_10_pln[$parent]['desc_prk_parent'] = $temp['desc_prk_parent'];
                    $daftar_prk_parent_form_10_pln[$parent]['beban_mat'] += $temp['beban_mat'];
                    $daftar_prk_parent_form_10_pln[$parent]['total_year_estimate'] += $temp['total_year_estimate'];
                    $daftar_prk_parent_form_10_pln[$parent]['ai_ketetapan'] += $temp['ai_ketetapan'];
                    $daftar_prk_parent_form_10_pln[$parent]['total_year_estimate_update'] += $temp['total_year_estimate_update'];
                    $daftar_prk_parent_form_10_pln[$parent]['ai_ketetapan_update'] += $temp['ai_ketetapan_update'];
                }

                // $disburse = array();
                // for($bulan=1; $bulan<=$int_input_bulan; $bulan++){
                //     $disburse[$bulan] = (float)$form_10_pln_disburse[$bulan][$i]->value;
                //     // $daftar_prk_inti_form_10_pln[$inti]['disburse'][$bulan] += $disburse[$bulan];
                //     // $daftar_prk_parent_form_10_pln[$parent]['disburse'][$bulan] += $disburse[$bulan];
                // }

            }
            $dataparent['form_10_pln'] = $daftar_prk_parent_form_10_pln;
            $datainti['form_10_pln'] = $daftar_prk_inti_form_10_pln;
            $datakegiatan['form_10_pln'] = $daftar_prk_kegiatan_form_10_pln;
            //dd($datakegiatan['form_10_pln']);

        } //end of cek request draft form 10 pln
        //End of query form 10 PLN

            if($request->download && $request->type){
                $judul='';
                if($request->type=='excel'){

                  //dd($sb, $fase, $input_tahun, $input_sb, $input_distrik, $input_lokasi, $input_fase, $input_draft_form_10_pk, $input_draft_form_10_pu, $input_draft_form_10_pln, $dataparent, $datainti, $datakegiatan);

                    Excel::create('Monitoring PRK AI', function ($excel) use($sb, $fase, $input_tahun, $input_sb, $input_distrik, $input_lokasi, $input_fase, $input_draft_form_10_pk, $input_draft_form_10_pu, $input_draft_form_10_pln, $dataparent, $datainti, $datakegiatan, $nama_bln_dipilih, $name_draft_form_10_pu, $name_draft_form_10_pk, $name_draft_form_10_pln) {
                            $excel->setTitle('Monitoring PRK AI');
                            $excel->setCreator('Laravel')->setCompany('Profio', 'PJB');
                            $excel->setDescription('Monitoring PRK AI');
                            $excel->sheet('Monitoring PRK AI', function ($sheet) use($sb, $fase, $input_tahun, $input_sb, $input_distrik, $input_lokasi, $input_fase, $input_draft_form_10_pk, $input_draft_form_10_pu, $input_draft_form_10_pln, $dataparent, $datainti, $datakegiatan, $nama_bln_dipilih, $name_draft_form_10_pu, $name_draft_form_10_pk, $name_draft_form_10_pln){
                                $sheet->loadView('output/monitoring-prk-ai-excel')
                                        ->with('sb', $sb)
                                        ->with('fase', $fase)
                                        ->with('input_tahun', $input_tahun)
                                        ->with('input_sb', $input_sb)
                                        ->with('input_distrik', $input_distrik)
                                        ->with('input_lokasi', $input_lokasi)
                                        ->with('input_fase', $input_fase)
                                        // ->with('input_draft_rkau', $input_draft_rkau)
                                        // ->with('input_draft_form_6_reimburse', $input_draft_form_6_reimburse)
                                        // ->with('input_draft_form_6_rutin', $input_draft_form_6_rutin)
                                        ->with('input_draft_form_10_pk', $input_draft_form_10_pk)
                                        ->with('input_draft_form_10_pu', $input_draft_form_10_pu)
                                        ->with('input_draft_form_10_pln', $input_draft_form_10_pln)
                                        ->with('dataparent', $dataparent)
                                        ->with('datainti', $datainti)
                                        ->with('datakegiatan', $datakegiatan)
                                        ->with('nama_bln_dipilih', $nama_bln_dipilih)
                                        ->with('name_draft_form_10_pu', $name_draft_form_10_pu)
                                        ->with('name_draft_form_10_pk', $name_draft_form_10_pk)
                                        ->with('name_draft_form_10_pln', $name_draft_form_10_pln);
                            });
                        })->download('xlsx');
                  }
              }
                //   return view('output/monitoring-prk-ai-excel', compact('sb', 'fase', 'input_tahun', 'input_sb', 'input_distrik', 'input_lokasi', 'input_fase', 'input_draft_rkau', 'input_draft_form_6_reimburse', 'input_draft_form_6_rutin', 'input_draft_form_10_pk', 'input_draft_form_10_pu', 'input_draft_form_10_pln', 'input_draft_form_penyusutan', 'input_draft_form_bahan_bakar','dataparent', 'datainti','datakegiatan', 'distrik', 'lokasi','tahun', 'draft_form_rkau', 'draft_form_penyusutan', 'draft_form_10_pln', 'draft_form_10_pu', 'draft_form_10_pk', 'draft_form_6_reimburse', 'draft_form_6_rutin', 'draft_form_bahan_bakar'));
                // }
                // else {
                //     return view('output/loader-ellipse', compact('sb', 'fase', 'input_tahun', 'input_sb', 'input_distrik', 'input_lokasi', 'input_fase', 'input_draft_rkau', 'input_draft_form_6_reimburse', 'input_draft_form_6_rutin', 'input_draft_form_10_pk', 'input_draft_form_10_pu', 'input_draft_form_10_pln', 'dataparent', 'datainti','datakegiatan'));
                // }
        }

        return view('output/monitoring-prk-ai', compact('sb', 'fase', 'input_tahun', 'input_sb', 'input_distrik', 'input_lokasi', 'input_fase', 'input_draft_form_10_pk', 'input_draft_form_10_pu', 'input_draft_form_10_pln', 'dataparent', 'datainti','datakegiatan', 'distrik', 'lokasi','tahun', 'draft_form_10_pln', 'draft_form_10_pu', 'draft_form_10_pk', 'nama_bln_dipilih', 'name_draft_form_10_pu', 'name_draft_form_10_pk', 'name_draft_form_10_pln'));
    }

/*
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
    */

//-------------------------------------------------NEW FUNCTIONS-----------------------------------------------------------------------------

    function get_form_10_ketetapan($file_import_id, $distrik_id, $kolom, $kolom_prk, $no_prk){
        // dump($file_import_id, $distrik_id, $kolom, $no_prk);
        $query = DB::select("select e.row, e.value
                              from excel_datas_ketetapan e
                              join sheets s on s.id = e.sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              where s.name like 'I-Form 10'
                              and e.file_import_ketetapan_id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom = '".$kolom."' and e.row IN 
                              (select e.row
                              from excel_datas_ketetapan e
                              join sheets s on s.id = e.sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              where s.name like 'I-Form 10'
                              and e.file_import_ketetapan_id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom = '".$kolom_prk."' and e.value like '".$no_prk."')
                              order by e.row LIMIT 1;");
        return $query;
    }

    public function get_drafts_ketetapan_ids($id_jenis, $id_distrik, $id_tahun)
    {
      $drafts =
      DB::select("select distinct f.id, f.file_import_id, f.draft_versi, f.name
          from file_imports_ketetapan f
          join templates t on f.template_id = t.id
          join excel_datas_ketetapan e on e.file_import_ketetapan_id = f.id
          join lokasi l on l.id = e.lokasi_id
          where t.jenis_id=".$id_jenis." and l.distrik_id = ".$id_distrik." and t.tahun= ".$id_tahun."
          group by f.id, f.file_import_id, f.draft_versi, f.name");

          // $drafts =
          // DB::select("select distinct f.id, f.draft_versi, f.name
          //     from file_imports f
          //     join templates t on f.template_id = t.id
          //     join excel_datas e on e.file_import_id = f.id
          //     join lokasi l on l.id = e.lokasi_id
          //     where t.jenis_id=".$id_jenis." and l.distrik_id = ".$id_distrik." and t.tahun= ".$id_tahun."
          //     group by f.id, f.draft_versi, f.name");

          //return json_encode($draft_rkau);
          if($drafts){
            $res = [];
            $i=0;
            foreach ($drafts as $key => $value) {
              $res[$i] = $value->file_import_id;
              $i++;
            }
            $res = implode(",", $res);
            //dd(var_dump($new));
            $res = "(".$res.")";
            return $res;
          }
          else return $drafts;
        // $draft_rkau = DB::select("select distinct f.id, f.draft_versi
        //                             from file_imports f
        //                             join templates t on f.template_id = t.id
        //                             join excel_datas e on e.file_import_id = f.id
        //                             join lokasi l on l.id = e.lokasi_id
        //                             where t.jenis_id=1 and l.distrik_id = ".$id_distrik." and t.tahun= ".$id_tahun."
        //                             group by f.id, f.draft_versi;");

    }

    function get_drafts_form_10_pu_ids($id_distrik, $id_tahun, $id_fase){
        $drafts =
            DB::select("select distinct f.id, f.file_import_id, f.draft_versi, f.name
            from file_imports_ketetapan f
            join templates t on f.template_id = t.id
            join excel_datas_ketetapan e on e.file_import_ketetapan_id = f.id
            join lokasi l on l.id = e.lokasi_id
            where t.jenis_id=4 and l.distrik_id = ".$id_distrik." and t.tahun= ".$id_tahun."
            group by f.id, f.file_import_id, f.draft_versi, f.name");

        if($drafts){
            $res = [];
            $i=0;
            foreach ($drafts as $key => $value) {
              // $res[$i] = $value->file_import_id;
              $res[$i] = $value->id;
              $i++;
            }
            $res = implode(",", $res);
            //dd(var_dump($new));
            $res = "(".$res.")";
            return $res;
        }
        else return $drafts;
    }

    function get_drafts_form_10_pk_ids($id_distrik, $id_tahun, $id_fase){
        $drafts =
            DB::select("select distinct f.id, f.file_import_id, f.draft_versi, f.name
            from file_imports_ketetapan f
            join templates t on f.template_id = t.id
            join excel_datas_ketetapan e on e.file_import_ketetapan_id = f.id
            join lokasi l on l.id = e.lokasi_id
            where t.jenis_id=5 and l.distrik_id = ".$id_distrik." and t.tahun= ".$id_tahun."
            group by f.id, f.file_import_id, f.draft_versi, f.name");

        if($drafts){
            $res = [];
            $i=0;
            foreach ($drafts as $key => $value) {
              // $res[$i] = $value->file_import_id;
              $res[$i] = $value->id;
              $i++;
            }
            $res = implode(",", $res);
            //dd(var_dump($new));
            $res = "(".$res.")";
            return $res;
        }
        else return $drafts;
    }

    function get_drafts_form_10_pln_ids($id_distrik, $id_tahun, $id_fase){
        $drafts =
            DB::select("select distinct f.id, f.file_import_id, f.draft_versi, f.name
            from file_imports_ketetapan f
            join templates t on f.template_id = t.id
            join excel_datas_ketetapan e on e.file_import_ketetapan_id = f.id
            join lokasi l on l.id = e.lokasi_id
            where t.jenis_id=6 and l.distrik_id = ".$id_distrik." and t.tahun= ".$id_tahun."
            group by f.id, f.file_import_id, f.draft_versi, f.name");

        if($drafts){
            $res = [];
            $i=0;
            foreach ($drafts as $key => $value) {
              // $res[$i] = $value->file_import_id;
              $res[$i] = $value->id;
              $i++;
            }
            $res = implode(",", $res);
            //dd(var_dump($new));
            $res = "(".$res.")";
            return $res;
        }
        else return $drafts;
    }

    function get_names($template_id, $file_id){
      // $names = DB::select("select f.name from file_imports f
      //                       join templates t on f.template_id = t.id
      //                       where f.id IN ".$file_id." and t.jenis_id = ".$template_id.";");

      $file_id = str_replace(array( '(', ')' ), '', $file_id);
      $fik = FileImportKetetapan::find($file_id);
      $names = $fik->draft_versi.' '.$fik->name;

      return $names;
    }


//-------------------------------------------------End of NEW FUNCTIONS----------------------------------------------------------------------

//-------------------------------------------------Update FUNCTIONS----------------------------------------------------------------------

    function get_form_10_update($file_import_id, $distrik_id, $kolom){
        //Query via excel_datas
        // $query = DB::select("select e.row, e.value
        //                       from excel_datas e
        //                       join sheets s on s.id = e.sheet_id
        //                       join lokasi l on l.id = e.lokasi_id
        //                       where s.name like 'I-Form 10'
        //                       and e.file_import_id IN ".$file_import_id."
        //                       and l.distrik_id = ".$distrik_id." and e.kolom = '".$kolom."' order by e.row;");

        //Query via pgdl
        $query = DB::select("select e.row, e.value
                              from pgdl_excel_datas_revisi e
                              join sheets s on s.id = e.sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                              join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                              where s.name like 'I-Form 10'
                              and fk.id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom = '".$kolom."' order by e.row;");

        return $query;
    }

    function get_form_10_disburse_update($file_import_id, $distrik_id, $kolom){
        //Query via excel_datas
        $query = DB::select("select (case when value = '' then 0 else value::float end) as value
                              from excel_datas e
                              join sheets s on s.id = e.sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              where s.name like 'I-Form 10'
                              and e.file_import_id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id." and e.kolom = '".$kolom."' ");

        // Query via tabel pgdl
        // $query = DB::select("select sum(case when value = '' then 0 else value::float end) as value
        //                       from pgdl_excel_datas_revisi e
        //                       join sheets s on s.id = e.sheet_id
        //                       join lokasi l on l.id = e.lokasi_id
        //                       join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
        //                       join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
        //                       where s.name like 'I-Form 6'
        //                       and fk.file_import_id IN ".$file_import_id."
        //                       and l.distrik_id = ".$distrik_id."
        //                       and (e.kolom = '".$kolom1."' or e.kolom = '".$kolom2."') group by e.row, fk.file_import_id");
        return $query;
    }

//-------------------------------------------------End Update FUNCTIONS----------------------------------------------------------------------

//-------------------------------------------------PO FUNCTIONS----------------------------------------------------------------------

    function get_no_po($no_prk, $month){
      $res = DB::select("select distinct po_no, account_code, po_item
                          from pgdl_pljprk_ai
                          where project_no = '".substr($no_prk,2)."' and months between 1 and ".$month."
                          order by po_no, po_item asc;");

      return $res;
    }

    function get_itemp_po($no_prk, $month, $po_no, $po_item, $account_code){
      // $res = DB::select(" select SUM(po_item) as total_item, SUM(tran_amount) as kontrak, SUM(val_required) as disburse
      //                     from pgdl_pljprk_ai
      //                     where project_no = '".substr($no_prk,2)."' and po_no ='".$po_no."' and months between 1 and ".$month." group by po_no;");


    $query_po_no = "= '".$po_no."'";
    if($po_no==NULL) {
        $query_po_no = 'IS NULL';
    }

    $query_po_item = "= '".$po_item."'";
    if($po_item==NULL) {
        $query_po_item = 'IS NULL';
    }
    // dump(substr($no_prk,2), $po_no, $po_item, $query_po_item);

    // $res = DB::select(" select val_received as kontrak, SUM(tran_amount) as disburse
    //                       from pgdl_pljprk_ai
    //                       where project_no = '".substr($no_prk,2)."' and po_no ='".$po_no."' and po_item =".$po_item." and months between 1 and ".$month."
    //                       group by val_received");

    $res = DB::select(" select val_required as kontrak, SUM(tran_amount) as disburse
                          from pgdl_pljprk_ai
                          where project_no = '".substr($no_prk,2)."' 
                            and po_no ".$query_po_no." 
                            and po_item ".$query_po_item." 
                            and account_code = '".$account_code."'
                            and months between 1 and ".$month."
                          group by val_required");

      return $res;
    }

//-------------------------------------------------End PO Functions----------------------------------------------------------------------
    function get_drafts($id_jenis, $id_distrik, $id_tahun){
        $drafts = DB::select("select distinct f.id, f.draft_versi
                                    from file_imports f
                                    join templates t on f.template_id = t.id
                                    join excel_datas e on e.file_import_id = f.id
                                    join lokasi l on l.id = e.lokasi_id
                                    where t.jenis_id=".$id_jenis." and l.distrik_id = ".$id_distrik." and t.tahun= ".$id_tahun."
                                    group by f.id, f.draft_versi;");
        return $drafts;
    }

    function get_form_10($file_import_id, $distrik_id, $kolom){
        // dump($file_import_id, $distrik_id, $kolom);
        // $query = DB::select("select e.row, e.value
        //                       from excel_datas e
        //                       join sheets s on s.id = e.sheet_id
        //                       join lokasi l on l.id = e.lokasi_id
        //                       where s.name like 'I-Form 10'
        //                       and e.file_import_id IN ".$file_import_id."
        //                       and l.distrik_id = ".$distrik_id." and e.kolom = '".$kolom."' order by e.row;");

        $query = DB::select("select e.row, e.value
                              from pgdl_excel_datas_revisi e
                              join sheets s on s.id = e.sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                              join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                              where s.name like 'I-Form 10'
                              and fk.id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom = '".$kolom."' order by e.row;");

        return $query;
    }
    function get_form_10_inti($file_import_id, $distrik_id, $kolom){
        // $query = DB::select("select distinct SUBSTRING(e.value,1,8) as value
        //                       from excel_datas e
        //                       join sheets s on s.id = e.sheet_id
        //                       join lokasi l on l.id = e.lokasi_id
        //                       where s.name like 'I-Form 10'
        //                       and e.file_import_id IN ".$file_import_id."
        //                       and l.distrik_id = ".$distrik_id." and e.kolom = '".$kolom."'");

        $query = DB::select("select distinct SUBSTRING(e.value,1,8) as value, e.row
                              from pgdl_excel_datas_revisi e
                              join sheets s on s.id = e.sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                              join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                              where s.name like 'I-Form 10'
                              and fk.id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom = '".$kolom."' order by e.row;");

        return $query;
    }

    function get_form_10_parent($file_import_id, $distrik_id, $kolom){
        // $query = DB::select("select distinct SUBSTRING(e.value,1,6) as value
        //                       from excel_datas e
        //                       join sheets s on s.id = e.sheet_id
        //                       join lokasi l on l.id = e.lokasi_id
        //                       where s.name like 'I-Form 10'
        //                       and e.file_import_id IN ".$file_import_id."
        //                       and l.distrik_id = ".$distrik_id." and e.kolom = '".$kolom."'");

        $query = DB::select("select distinct SUBSTRING(e.value,1,6) as value
                              from pgdl_excel_datas_revisi e
                              join sheets s on s.id = e.sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                              join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                              where s.name like 'I-Form 10'
                              and fk.id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom = '".$kolom."'");

        return $query;
    }

    function get_form_10_disburse($file_import_id, $distrik_id, $kolom){
        // $query = DB::select("select (case when value = '' then 0 else value::float end) as value
        //                       from excel_datas e
        //                       join sheets s on s.id = e.sheet_id
        //                       join lokasi l on l.id = e.lokasi_id
        //                       where s.name like 'I-Form 10'
        //                       and e.file_import_id IN ".$file_import_id."
        //                       and l.distrik_id = ".$distrik_id." and e.kolom = '".$kolom."' ");

        $query = DB::select("select sum(case when value = '' then 0 else value::float end) as value
                              from pgdl_excel_datas_revisi e
                              join sheets s on s.id = e.sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                              join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                              where s.name like 'I-Form 10'
                              and fk.id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom = '".$kolom."' 
                              group by e.row, fk.file_import_id order by e.row");

        return $query;
    }

    function cek_no_prk_form_10_ketetapan($file_import_id, $distrik_id, $kolom, $no_prk){
        //cek no PRK form 10 ketetapan
        $query = DB::select("select e.row, e.value
                              from excel_datas_ketetapan e
                              join sheets s on s.id = e.sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              where s.name like 'I-Form 10'
                              and e.file_import_ketetapan_id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom = '".$kolom."' and value = '".$no_prk."' order by e.row;");
        // dd($file_import_id, $distrik_id, $kolom);
        // $query = DB::select("select e.row, e.value
        //                       from pgdl_excel_datas_revisi e
        //                       join sheets s on s.id = e.sheet_id
        //                       join lokasi l on l.id = e.lokasi_id
        //                       join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
        //                       join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
        //                       where s.name like 'I-Form 6'
        //                       and fk.id IN ".$file_import_id."
        //                       and l.distrik_id = ".$distrik_id."
        //                       and e.kolom = '".$kolom."' and value = '".$no_prk."' order by e.row;");
        
        return $query;
    }

/*

    function get_prk_parent_desc($identity_parent){
        $result = collect(\DB::select("select desc_prk_parent from prk_parent where identity_prk_parent_ppa like '".$identity_parent."' or identity_prk_parent_jom like '".$identity_parent."' or identity_prk_parent_usaha_lain  like '".$identity_parent."'"))->first();
        // dd($result);
        return $result;
    }

    function get_prk_inti_desc($identity_parent, $identity_inti){
        $result = collect(\DB::select("select i.desc_prk_inti from prk_parent p join prk_inti i on p.id = i.prk_parent_id
                            where (identity_prk_parent_ppa like '".$identity_parent."' or identity_prk_parent_jom like '".$identity_parent."' or identity_prk_parent_usaha_lain  like '".$identity_parent."')
                            and identity_prk_inti like '".$identity_inti."'"))->first();
        return $result;
    }
    */
}
    
