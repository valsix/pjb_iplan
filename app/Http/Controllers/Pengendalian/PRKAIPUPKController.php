<?php

namespace App\Http\Controllers\Pengendalian;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Entities\StrategiBisnis;
use App\Entities\Distrik;
use App\Entities\Lokasi;
use App\Entities\Fase;
use App\Entities\Template;
use App\Entities\User;
use App\Entities\Role;
use App\Entities\FileImportKetetapan;
use App\Entities\PgdlReportDashboardSetting;
Use DB;
use Illuminate\Support\Facades\Input;
use Excel;

class PRKAIPUPKController extends Controller
{
    public function index(Request $request)
    {
        $data = Input::all();

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
        if(!$input_tahun)
        {
            return redirect()->action('PencarianReportDashboardPengendalianController@pencarian');
        }
        if($input_sb<1 || $input_sb>2)
        {
            return redirect()->action('PencarianReportDashboardPengendalianController@pencarian');
        }
        $distrik_id = Distrik::pluck('id')->toArray();
        if(!in_array($int_input_distrik,$distrik_id)){
            return redirect()->action('PencarianReportDashboardPengendalianController@pencarian');
        }
        $fase_id = Fase::pluck('id')->toArray();
        if(!in_array($input_fase,$fase_id)){
            return redirect()->action('PencarianReportDashboardPengendalianController@pencarian');
        }

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
        if($int_input_bulan<=12){
            $nama_bln_dipilih = $nama_bln[$int_input_bulan];
        }else{
            return redirect()->action('PencarianReportDashboardPengendalianController@pencarian');
        }

        $input_draft_form_10_pu = $this->get_drafts_form_10_pu_ids($int_input_distrik, $input_tahun, $input_fase);
        $input_draft_form_10_pk = $this->get_drafts_form_10_pk_ids($int_input_distrik, $input_tahun, $input_fase);

        $name_draft_form_10_pu = '';
        $name_draft_form_10_pk = '';

        if($input_draft_form_10_pu){
            $name_draft_form_10_pu = $this->get_names(4, $input_draft_form_10_pu);
        }
        if($input_draft_form_10_pk){
            $name_draft_form_10_pk = $this->get_names(5, $input_draft_form_10_pk);
        }

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

        if ($input_distrik != NULL) {

            $dataparent = array();
            $datainti = array();
            $datakegiatan = array();

            // Error Handling jika setting pada tahun yg dipilih belum diisi
            $jenis_form_yg_digunakan = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', 2)
                                    ->whereNotNull('jenis_id')
                                    ->where('tahun', $input_tahun)
                                    ->select('jenis_id')
                                    ->distinct()
                                    ->get();

            $notification_failed = '';
            if(count($jenis_form_yg_digunakan) == 0){
                $notification_failed = 'Setting Report Dashboard Monitoring PRK AI Pengembangan Usaha dan Penguatan Kit untuk tahun '.$input_tahun.' belum dibuat!';
                
                return view('pengendalian_output.monitoring_prk_ai_pu_pk.index', compact('sb', 'fase', 'input_tahun', 'input_sb', 'input_distrik', 'input_lokasi', 'input_fase', 'input_draft_form_10_pk', 'input_draft_form_10_pu', 'dataparent', 'datainti','datakegiatan', 'distrik', 'lokasi','tahun', 'draft_form_10_pu', 'draft_form_10_pk', 'nama_bln_dipilih', 'name_draft_form_10_pu', 'name_draft_form_10_pk', 'notification_failed'));
            }

        //Start Form 10 Penguatan KIT
        if($input_draft_form_10_pk){
            //ambil data report dashboard dinamis
            $pgdl_report_dashboard_page_id = 2;
            $jenis_id = 5;
            $pgdl_sheet_name = 'I-Form 10';

            $setting_no_prk_kegiatan = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 2)
                            ->first();
                            // dd( $setting_no_prk_kegiatan );
            $setting_desc_prk_kegiatan = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 3)
                            ->first();

            $setting_ai_ketetapan = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 4)
                            ->first();

            $setting_aki_ketetapan = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 5)
                            ->first();

            $setting_ai_ketetapan_update = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 6)
                            ->first();

            $setting_aki_ketetapan_update = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 7)
                            ->first();
            //end of ambil data report dashboard dinamis

            $count_10_pk = DB::select("select count(e.row)
                              from pgdl_excel_datas_revisi e
                              join pgdl_sheets s on s.id = e.pgdl_sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                              join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                              where s.name like 'I-Form 10'
                              and fk.id IN ".$input_draft_form_10_pk."
                              and l.distrik_id = ".$int_input_distrik."
                              and e.kolom = '".$setting_no_prk_kegiatan->kolom."';")[0]->count;

            $int_count_10_pk = (int)$count_10_pk;

            $daftar_prk_kegiatan_form_10_pk = array();
            $daftar_prk_inti_form_10_pk = array();
            $daftar_prk_parent_form_10_pk = array();

            // $form_10_pk_prk_parent = $this->get_form_10_parent($input_draft_form_10_pk,$int_input_distrik, 'H');
            $form_10_pk_prk_parent = $this->get_form_10_parent($input_draft_form_10_pk,$int_input_distrik, $setting_no_prk_kegiatan->kolom);

            foreach ($form_10_pk_prk_parent as $key => $value) {
                $daftar_prk_parent_form_10_pk[$value->value] = array(
                    // 'desc_prk_parent' => '',
                    // 'beban_mat'     => 0,
                    // 'cash_oth'      => 0,
                    // 'ijin_proses'   => 0,
                    'total_year_estimate'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    'total_year_estimate_update' => 0,
                    'ai_ketetapan' => 0,
                    'ai_ketetapan_update' => 0,
                    );
            }
            // dd($daftar_prk_parent_form_10_pk);

            // $form_10_pk_prk_inti = $this->get_form_10_inti($input_draft_form_10_pk,$int_input_distrik, 'H');
            $form_10_pk_prk_inti = $this->get_form_10_inti($input_draft_form_10_pk,$int_input_distrik, $setting_no_prk_kegiatan->kolom);
            foreach ($form_10_pk_prk_inti as $key => $value) {
                $daftar_prk_inti_form_10_pk[$value->value] = array(
                    // 'desc_prk_inti' => '',
                    // 'prk_parent'    => substr($value->value, 0, 6),
                    // 'beban_mat'     => 0,
                    // 'cash_oth'      => 0,
                    // 'ijin_proses'   => 0,
                    'total_year_estimate'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    'total_year_estimate_update' => 0,
                    'ai_ketetapan' => 0,
                    'ai_ketetapan_update' => 0,
                );
            }
            // dd($daftar_prk_parent_form_10_pk, $daftar_prk_inti_form_10_pk );
            // $form_10_pk_no_prk_kegiatan = $this->get_form_10($input_draft_form_10_pk,$int_input_distrik, 'H');
            // $form_10_pk_desc_prk_kegiatan = $this->get_form_10($input_draft_form_10_pk,$int_input_distrik, 'S');

            $form_10_pk_no_prk_kegiatan = $this->get_form_10($input_draft_form_10_pk,$int_input_distrik, $setting_no_prk_kegiatan->kolom);
            $form_10_pk_desc_prk_kegiatan = $this->get_form_10($input_draft_form_10_pk,$int_input_distrik, $setting_desc_prk_kegiatan->kolom);
            // $form_10_pk_desc_prk_inti = $this->get_form_10($input_draft_form_10_pk,$int_input_distrik, 'R');
            // $form_10_pk_desc_prk_parent = $this->get_form_10($input_draft_form_10_pk,$int_input_distrik, 'Q');
            // $form_10_pk_beban_mat = $this->get_form_10($input_draft_form_10_pk,$int_input_distrik, 'AI');
            // $form_10_pk_ai_ketetapan = $this->get_form_10($input_draft_form_10_pk,$int_input_distrik, 'AN');
            // $form_10_pk_total_year_estimate = $this->get_form_10($input_draft_form_10_pk,$int_input_distrik, 'AT');
            // dd($form_10_pk_no_prk_kegiatan,$form_10_pk_desc_prk_kegiatan );
            // $form_10_pk_ai_ketetapan_update = $this->get_form_10_update($input_draft_form_10_pk,$int_input_distrik, 'AN');
            // $form_10_pk_total_year_estimate_update = $this->get_form_10_update($input_draft_form_10_pk,$int_input_distrik, 'AT');

            $form_10_pk_ai_ketetapan_update = $this->get_form_10_update($input_draft_form_10_pk,$int_input_distrik, $setting_ai_ketetapan_update->kolom);
            $form_10_pk_total_year_estimate_update = $this->get_form_10_update($input_draft_form_10_pk,$int_input_distrik, $setting_aki_ketetapan_update->kolom);
        
            // $form_10_pk_cash_oth = $this->get_form_10($request->input('draft_form_10_pk'),$int_input_lokasi, 'AV');
            // $form_10_pk_ijin_proses = $this->get_form_10($request->input('draft_form_10_pk'),$int_input_lokasi, 'AX');

            // $form_10_pk_disburse = array();

            // $start_kolom = 'BC';
            // for($bulan = 1; $bulan<=$int_input_bulan; $bulan++){
            //     $form_10_pk_disburse[$bulan] = $this->get_form_10_disburse($input_draft_form_10_pk,$int_input_distrik, $start_kolom);
            //     $start_kolom++;
            // }

            for($i=0; $i<$int_count_10_pk; $i++){
                $parent = substr($form_10_pk_no_prk_kegiatan[$i]->value,0,6);
                $inti = substr($form_10_pk_no_prk_kegiatan[$i]->value,0,8);

                //cek ada No PRK kegiatan
                // $cek_no_prk_ketetapan = $this->cek_no_prk_form_10_ketetapan($input_draft_form_10_pk,$int_input_distrik, 'H', $form_10_pk_no_prk_kegiatan[$i]->value);
                $cek_no_prk_ketetapan = $this->cek_no_prk_form_10_ketetapan($input_draft_form_10_pk,$int_input_distrik, $setting_no_prk_kegiatan->kolom, $form_10_pk_no_prk_kegiatan[$i]->value);
               
                if($cek_no_prk_ketetapan) {
                    // $ai_form_10_pk_ketetapan[$i] = $this->get_form_10_ketetapan($input_draft_form_10_pk,$int_input_distrik, 'AN', 'H', $form_10_pk_no_prk_kegiatan[$i]->value)[0]->value;
                    // $aki_form_10_pk_ketetapan[$i] = $this->get_form_10_ketetapan($input_draft_form_10_pk,$int_input_distrik, 'AT', 'H', $form_10_pk_no_prk_kegiatan[$i]->value)[0]->value;

                    $ai_form_10_pk_ketetapan[$i] = $this->get_form_10_ketetapan($input_draft_form_10_pk,$int_input_distrik, $setting_ai_ketetapan->kolom, $setting_no_prk_kegiatan->kolom, $form_10_pk_no_prk_kegiatan[$i]->value)[0]->value;
                    $aki_form_10_pk_ketetapan[$i] = $this->get_form_10_ketetapan($input_draft_form_10_pk,$int_input_distrik, $setting_aki_ketetapan->kolom, $setting_no_prk_kegiatan->kolom, $form_10_pk_no_prk_kegiatan[$i]->value)[0]->value;
                }
                else {
                    $ai_form_10_pk_ketetapan[$i] = 0;
                    $aki_form_10_pk_ketetapan[$i] = 0;
                }

                $dist_po_no = $this->get_no_po($form_10_pk_no_prk_kegiatan[$i]->value, $int_input_bulan, $input_tahun, $int_input_distrik);
//-------------------------------------------diakali disini------------------------------------------------------------
                if($dist_po_no){
                  foreach ($dist_po_no as $key => $value) {
                    $item_po = empty($this->get_itemp_po($form_10_pk_no_prk_kegiatan[$i]->value, $int_input_bulan, $value->po_no, $value->po_item, $value->account_code, $input_tahun)) ? '' : $this->get_itemp_po($form_10_pk_no_prk_kegiatan[$i]->value, $int_input_bulan, $value->po_no, $value->po_item, $value->account_code, $input_tahun)[0];//$value->po_no);

                    $temp = array(
                        'prk_kegiatan' => $form_10_pk_no_prk_kegiatan[$i]->value,
                        // 'prk_inti' => $inti,
                        // 'prk_parent' => $parent,
                        'desc_prk_kegiatan' => $form_10_pk_desc_prk_kegiatan[$i]->value,
                        // 'desc_prk_inti' => $form_10_pk_desc_prk_inti[$i]->value,
                        // 'desc_prk_parent' => $form_10_pk_desc_prk_parent[$i]->value,
                        // 'beban_mat' => (float)$form_10_pk_beban_mat[$i]->value,
                        // 'cash_oth' => 0,
                        // 'ijin_proses' => 0,
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

                      // $daftar_prk_inti_form_10_pk[$inti]['desc_prk_inti'] = $temp['desc_prk_inti'];
                      // $daftar_prk_inti_form_10_pk[$inti]['beban_mat'] += $temp['beban_mat'];
                      $daftar_prk_inti_form_10_pk[$inti]['total_year_estimate'] += $temp['total_year_estimate'];
                      $daftar_prk_inti_form_10_pk[$inti]['ai_ketetapan'] += $temp['ai_ketetapan'];
                      $daftar_prk_inti_form_10_pk[$inti]['total_year_estimate_update'] += $temp['total_year_estimate_update'];
                      $daftar_prk_inti_form_10_pk[$inti]['ai_ketetapan_update'] += $temp['ai_ketetapan_update'];

                      // $daftar_prk_parent_form_10_pk[$parent]['desc_prk_parent'] = $temp['desc_prk_parent'];
                      // $daftar_prk_parent_form_10_pk[$parent]['beban_mat'] += $temp['beban_mat'];
                      $daftar_prk_parent_form_10_pk[$parent]['total_year_estimate'] += $temp['total_year_estimate'];
                      $daftar_prk_parent_form_10_pk[$parent]['ai_ketetapan'] += $temp['ai_ketetapan'];
                      $daftar_prk_parent_form_10_pk[$parent]['total_year_estimate_update'] += $temp['total_year_estimate_update'];
                      $daftar_prk_parent_form_10_pk[$parent]['ai_ketetapan_update'] += $temp['ai_ketetapan_update'];
                  }
                }
                else{
                  $temp = array(
                      'prk_kegiatan' => $form_10_pk_no_prk_kegiatan[$i]->value,
                      // 'prk_inti' => $inti,
                      // 'prk_parent' => $parent,
                      'desc_prk_kegiatan' => $form_10_pk_desc_prk_kegiatan[$i]->value,
                      // 'desc_prk_inti' => $form_10_pk_desc_prk_inti[$i]->value,
                      // 'desc_prk_parent' => $form_10_pk_desc_prk_parent[$i]->value,
                      // 'beban_mat' => (float)$form_10_pk_beban_mat[$i]->value,
                      // 'cash_oth' => 0,
                      // 'ijin_proses' => 0,
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

                    // $daftar_prk_inti_form_10_pk[$inti]['desc_prk_inti'] = $temp['desc_prk_inti'];
                    // $daftar_prk_inti_form_10_pk[$inti]['beban_mat'] += $temp['beban_mat'];
                    $daftar_prk_inti_form_10_pk[$inti]['total_year_estimate'] += $temp['total_year_estimate'];
                    $daftar_prk_inti_form_10_pk[$inti]['ai_ketetapan'] += $temp['ai_ketetapan'];
                    $daftar_prk_inti_form_10_pk[$inti]['total_year_estimate_update'] += $temp['total_year_estimate_update'];
                    $daftar_prk_inti_form_10_pk[$inti]['ai_ketetapan_update'] += $temp['ai_ketetapan_update'];

                    // $daftar_prk_parent_form_10_pk[$parent]['desc_prk_parent'] = $temp['desc_prk_parent'];
                    // $daftar_prk_parent_form_10_pk[$parent]['beban_mat'] += $temp['beban_mat'];
                    $daftar_prk_parent_form_10_pk[$parent]['total_year_estimate'] += $temp['total_year_estimate'];
                    $daftar_prk_parent_form_10_pk[$parent]['ai_ketetapan'] += $temp['ai_ketetapan'];
                    $daftar_prk_parent_form_10_pk[$parent]['total_year_estimate_update'] += $temp['total_year_estimate_update'];
                    $daftar_prk_parent_form_10_pk[$parent]['ai_ketetapan_update'] += $temp['ai_ketetapan_update'];
                }
            }

            $dataparent['form_10_pk'] = $daftar_prk_parent_form_10_pk;
            $datainti['form_10_pk'] = $daftar_prk_inti_form_10_pk;
            $datakegiatan['form_10_pk'] = $daftar_prk_kegiatan_form_10_pk;

        } //end of cek request draft form 10 kit
        //End of query form 10 Penguatan KIT

        //Start Form 10 Pengembangan Usaha
        if($input_draft_form_10_pu){
            //ambil data report dashboard dinamis
            $pgdl_report_dashboard_page_id = 2;
            $jenis_id = 4;
            $pgdl_sheet_name = 'I-Form 10';

            $setting_no_prk_kegiatan = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 2)
                            ->first();

            $setting_desc_prk_kegiatan = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 3)
                            ->first();

            $setting_ai_ketetapan = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 4)
                            ->first();

            $setting_aki_ketetapan = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 5)
                            ->first();

            $setting_ai_ketetapan_update = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 6)
                            ->first();

            $setting_aki_ketetapan_update = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 7)
                            ->first();
            //end of ambil data report dashboard dinamis

            $count_10_pu = DB::select("select count(e.row)
                              from pgdl_excel_datas_revisi e
                              join pgdl_sheets s on s.id = e.pgdl_sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                              join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                              where s.name like 'I-Form 10'
                              and fk.id IN ".$input_draft_form_10_pu."
                              and l.distrik_id = ".$int_input_distrik."
                              and e.kolom = '".$setting_no_prk_kegiatan->kolom."';")[0]->count;

            $int_count_10_pu = (int)$count_10_pu;

            $daftar_prk_kegiatan_form_10_pu = array();
            $daftar_prk_inti_form_10_pu = array();
            $daftar_prk_parent_form_10_pu = array();

            // $form_10_pu_prk_parent = $this->get_form_10_parent($input_draft_form_10_pu,$int_input_distrik, 'I');
            $form_10_pu_prk_parent = $this->get_form_10_parent($input_draft_form_10_pu,$int_input_distrik, $setting_no_prk_kegiatan->kolom);
            foreach ($form_10_pu_prk_parent as $key => $value) {
                $daftar_prk_parent_form_10_pu[$value->value] = array(
                    // 'desc_prk_parent' => '',
                    // 'beban_mat'     => 0,
                    // 'cash_oth'      => 0,
                    // 'ijin_proses'   => 0,
                    'total_year_estimate'   => 0,
                    'total_year_estimate_update'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    'ai_ketetapan' => 0,
                    'ai_ketetapan_update' => 0,
                    );
            }

            // $form_10_pu_prk_inti = $this->get_form_10_inti($input_draft_form_10_pu,$int_input_distrik, 'I');
            $form_10_pu_prk_inti = $this->get_form_10_inti($input_draft_form_10_pu,$int_input_distrik, $setting_no_prk_kegiatan->kolom);
            foreach ($form_10_pu_prk_inti as $key => $value) {
                $daftar_prk_inti_form_10_pu[$value->value] = array(
                    // 'desc_prk_inti' => '',
                    // 'prk_parent'    => substr($value->value, 0, 6),
                    // 'beban_mat'     => 0,
                    // 'cash_oth'      => 0,
                    // 'ijin_proses'   => 0,
                    'total_year_estimate'   => 0,
                    'total_year_estimate_update'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    'ai_ketetapan' => 0,
                    'ai_ketetapan_update' => 0,
                );
            }

            // $form_10_pu_no_prk_kegiatan = $this->get_form_10($input_draft_form_10_pu,$int_input_distrik, 'I');
            // $form_10_pu_desc_prk_kegiatan = $this->get_form_10($input_draft_form_10_pu,$int_input_distrik, 'T');

            $form_10_pu_no_prk_kegiatan = $this->get_form_10($input_draft_form_10_pu,$int_input_distrik, $setting_no_prk_kegiatan->kolom);
            $form_10_pu_desc_prk_kegiatan = $this->get_form_10($input_draft_form_10_pu,$int_input_distrik, $setting_desc_prk_kegiatan->kolom);
            // $form_10_pu_desc_prk_inti = $this->get_form_10($input_draft_form_10_pu,$int_input_distrik, 'S');
            // $form_10_pu_desc_prk_parent = $this->get_form_10($input_draft_form_10_pu,$int_input_distrik, 'R');
            // $form_10_pu_beban_mat = $this->get_form_10($input_draft_form_10_pu,$int_input_distrik, 'AJ');
            // $form_10_pu_ai_ketetapan = $this->get_form_10($input_draft_form_10_pu,$int_input_distrik, 'AO');
            // $form_10_pu_total_year_estimate = $this->get_form_10($input_draft_form_10_pu,$int_input_distrik, 'AU');

            // $form_10_pu_ai_ketetapan_update = $this->get_form_10_update($input_draft_form_10_pu,$int_input_distrik, 'AO');
            // $form_10_pu_total_year_estimate_update = $this->get_form_10_update($input_draft_form_10_pu,$int_input_distrik, 'AU');

            $form_10_pu_ai_ketetapan_update = $this->get_form_10_update($input_draft_form_10_pu,$int_input_distrik, $setting_ai_ketetapan_update->kolom);
            $form_10_pu_total_year_estimate_update = $this->get_form_10_update($input_draft_form_10_pu,$int_input_distrik, $setting_aki_ketetapan_update->kolom);

            // $form_10_pu_cash_oth = $this->get_form_10($request->input('draft_form_10_pu'),$int_input_lokasi, 'AV');
            // $form_10_pu_ijin_proses = $this->get_form_10($request->input('draft_form_10_pu'),$int_input_lokasi, 'AX');
            // $form_10_pu_disburse = array();

            // $start_kolom = 'BI';
            // for($bulan = 1; $bulan<=$int_input_bulan; $bulan++){
            //     $form_10_pu_disburse[$bulan] = $this->get_form_10_disburse($input_draft_form_10_pu,$int_input_distrik, $start_kolom);
            //     $start_kolom++;
            // }

            for($i=0; $i<$int_count_10_pu; $i++){
                $parent = substr($form_10_pu_no_prk_kegiatan[$i]->value,0,6);
                $inti = substr($form_10_pu_no_prk_kegiatan[$i]->value,0,8);

                //cek ada No PRK kegiatan
                // $cek_no_prk_ketetapan = $this->cek_no_prk_form_10_ketetapan($input_draft_form_10_pu,$int_input_distrik, 'I', $form_10_pu_no_prk_kegiatan[$i]->value);
                $cek_no_prk_ketetapan = $this->cek_no_prk_form_10_ketetapan($input_draft_form_10_pu,$int_input_distrik, $setting_no_prk_kegiatan->kolom, $form_10_pu_no_prk_kegiatan[$i]->value);

                if($cek_no_prk_ketetapan) {
                    // $ai_form_10_pu_ketetapan[$i] = $this->get_form_10_ketetapan($input_draft_form_10_pu,$int_input_distrik, 'AO', 'I', $form_10_pu_no_prk_kegiatan[$i]->value)[0]->value;
                    // $aki_form_10_pu_ketetapan[$i] = $this->get_form_10_ketetapan($input_draft_form_10_pu,$int_input_distrik, 'AU', 'I', $form_10_pu_no_prk_kegiatan[$i]->value)[0]->value;

                    $ai_form_10_pu_ketetapan[$i] = $this->get_form_10_ketetapan($input_draft_form_10_pu,$int_input_distrik, $setting_ai_ketetapan->kolom, $setting_no_prk_kegiatan->kolom, $form_10_pu_no_prk_kegiatan[$i]->value)[0]->value;
                    $aki_form_10_pu_ketetapan[$i] = $this->get_form_10_ketetapan($input_draft_form_10_pu,$int_input_distrik, $setting_aki_ketetapan->kolom, $setting_no_prk_kegiatan->kolom, $form_10_pu_no_prk_kegiatan[$i]->value)[0]->value;
                }
                else {
                    $ai_form_10_pu_ketetapan[$i] = 0;
                    $aki_form_10_pu_ketetapan[$i] = 0;
                }

                $dist_po_no = $this->get_no_po($form_10_pu_no_prk_kegiatan[$i]->value, $int_input_bulan, $input_tahun, $int_input_distrik);

//-------------------------------------------diakali disini------------------------------------------------------------
                if($dist_po_no){
                  foreach ($dist_po_no as $key => $value) {
                    $item_po = empty($this->get_itemp_po($form_10_pu_no_prk_kegiatan[$i]->value, $int_input_bulan, $value->po_no, $value->po_item, $value->account_code, $input_tahun)) ? '' : $this->get_itemp_po($form_10_pu_no_prk_kegiatan[$i]->value, $int_input_bulan, $value->po_no, $value->po_item, $value->account_code, $input_tahun)[0];

                    $temp = array(
                        'prk_kegiatan' => $form_10_pu_no_prk_kegiatan[$i]->value,
                        // 'prk_inti' => $inti,
                        // 'prk_parent' => $parent,
                        'desc_prk_kegiatan' => $form_10_pu_desc_prk_kegiatan[$i]->value,
                        // 'desc_prk_inti' => $form_10_pu_desc_prk_inti[$i]->value,
                        // 'desc_prk_parent' => $form_10_pu_desc_prk_parent[$i]->value,
                        // 'beban_mat' => (float)$form_10_pu_beban_mat[$i]->value,
                        // 'cash_oth' => 0,
                        // 'ijin_proses' => 0,
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

                    // $daftar_prk_inti_form_10_pu[$inti]['desc_prk_inti'] = $temp['desc_prk_inti'];
                    // $daftar_prk_inti_form_10_pu[$inti]['beban_mat'] += $temp['beban_mat'];
                    $daftar_prk_inti_form_10_pu[$inti]['total_year_estimate'] += $temp['total_year_estimate'];
                    $daftar_prk_inti_form_10_pu[$inti]['ai_ketetapan'] += $temp['ai_ketetapan'];
                    $daftar_prk_inti_form_10_pu[$inti]['total_year_estimate_update'] += $temp['total_year_estimate_update'];
                    $daftar_prk_inti_form_10_pu[$inti]['ai_ketetapan_update'] += $temp['ai_ketetapan_update'];

                    // $daftar_prk_parent_form_10_pu[$parent]['desc_prk_parent'] = $temp['desc_prk_parent'];
                    // $daftar_prk_parent_form_10_pu[$parent]['beban_mat'] += $temp['beban_mat'];
                    $daftar_prk_parent_form_10_pu[$parent]['total_year_estimate'] += $temp['total_year_estimate'];
                    $daftar_prk_parent_form_10_pu[$parent]['ai_ketetapan'] += $temp['ai_ketetapan'];
                    $daftar_prk_parent_form_10_pu[$parent]['total_year_estimate_update'] += $temp['total_year_estimate_update'];
                    $daftar_prk_parent_form_10_pu[$parent]['ai_ketetapan_update'] += $temp['ai_ketetapan_update'];
                  }
                }
                else{
                    $temp = array(
                        'prk_kegiatan' => $form_10_pu_no_prk_kegiatan[$i]->value,
                        // 'prk_inti' => $inti,
                        // 'prk_parent' => $parent,
                        'desc_prk_kegiatan' => $form_10_pu_desc_prk_kegiatan[$i]->value,
                        // 'desc_prk_inti' => $form_10_pu_desc_prk_inti[$i]->value,
                        // 'desc_prk_parent' => $form_10_pu_desc_prk_parent[$i]->value,
                        // 'beban_mat' => (float)$form_10_pu_beban_mat[$i]->value,
                        // 'cash_oth' => 0,
                        // 'ijin_proses' => 0,
                        // 'total_year_estimate' => (float)$form_10_pu_total_year_estimate[$i]->value,
                        // 'ai_ketetapan' => (float)$form_10_pu_ai_ketetapan[$i]->value,
                        'total_year_estimate' => (float)$aki_form_10_pu_ketetapan[$i],
                        'ai_ketetapan' => (float)$ai_form_10_pu_ketetapan[$i],
                        'total_year_estimate_update' => (float)$form_10_pu_total_year_estimate_update[$i]->value,
                        'ai_ketetapan_update' => (float)$form_10_pu_ai_ketetapan_update[$i]->value,
                        'po_no' => 0,
                        'item_po' => 0,
                        'account_code' => 0,
                        'kontrak' => 0,
                        'disburse' => 0,
                    );
                    array_push($daftar_prk_kegiatan_form_10_pu, $temp);

                    // $daftar_prk_inti_form_10_pu[$inti]['desc_prk_inti'] = $temp['desc_prk_inti'];
                    // $daftar_prk_inti_form_10_pu[$inti]['beban_mat'] += $temp['beban_mat'];
                    $daftar_prk_inti_form_10_pu[$inti]['total_year_estimate'] += $temp['total_year_estimate'];
                    $daftar_prk_inti_form_10_pu[$inti]['ai_ketetapan'] += $temp['ai_ketetapan'];
                    $daftar_prk_inti_form_10_pu[$inti]['total_year_estimate_update'] += $temp['total_year_estimate_update'];
                    $daftar_prk_inti_form_10_pu[$inti]['ai_ketetapan_update'] += $temp['ai_ketetapan_update'];

                    // $daftar_prk_parent_form_10_pu[$parent]['desc_prk_parent'] = $temp['desc_prk_parent'];
                    // $daftar_prk_parent_form_10_pu[$parent]['beban_mat'] += $temp['beban_mat'];
                    $daftar_prk_parent_form_10_pu[$parent]['total_year_estimate'] += $temp['total_year_estimate'];
                    $daftar_prk_parent_form_10_pu[$parent]['ai_ketetapan'] += $temp['ai_ketetapan'];
                    $daftar_prk_parent_form_10_pu[$parent]['total_year_estimate_update'] += $temp['total_year_estimate_update'];
                    $daftar_prk_parent_form_10_pu[$parent]['ai_ketetapan_update'] += $temp['ai_ketetapan_update'];
                }
            }

            $dataparent['form_10_pu'] = $daftar_prk_parent_form_10_pu;
            $datainti['form_10_pu'] = $daftar_prk_inti_form_10_pu;
            $datakegiatan['form_10_pu'] = $daftar_prk_kegiatan_form_10_pu;
        } //end of cek request draft form 10 pengembangan usaha
        //End of query form 10 Pengembangan Usaha
        $data_prk_item = array();
        foreach ($datakegiatan as $key_form => $value_per_form) {
            foreach ($value_per_form as $key => $value) {
                // kombinasi prk dan item PO
                $prk_po_key = $value['prk_kegiatan'].'-'.($value['po_no'] != null ? $value['po_no'] : '');
                // jika sudah ada di array, tinggal di sum saja
                if(array_key_exists($prk_po_key, $data_prk_item)){
                    array_push($data_prk_item[$prk_po_key]['per_item'], $value);

                    // default item po sudah ada 1. tp ada case tertentu yg menyebabkan item po = 0 padahal sudah ada 1 item po di dalamnya
                    $data_prk_item[$prk_po_key]['item_po'] = $data_prk_item[$prk_po_key]['item_po'] == 0 ? 1 : $data_prk_item[$prk_po_key]['item_po'];
                    $data_prk_item[$prk_po_key]['item_po']+=1;
                    
                    if($value['disburse']!= null)
                        $data_prk_item[$prk_po_key]['disburse']+= $value['disburse'];

                    if($value['kontrak']!= null)
                        $data_prk_item[$prk_po_key]['kontrak']+= $value['kontrak'];
                }

                // jika belum ada di array, tambahkan dulu
                else{
                    $data_prk_item[$prk_po_key] = $value;
                    $data_prk_item[$prk_po_key]['per_item'] = array();
                    array_push($data_prk_item[$prk_po_key]['per_item'], $value);
                    if($value['po_no'] != null || $value['disburse'] != null || $value['kontrak']!= null)
                        $data_prk_item[$prk_po_key]['item_po'] = 1;
                    else
                        $data_prk_item[$prk_po_key]['item_po'] = 0;
                }
            }
        }
            if($request->download && $request->type){
                $judul='';
                if($request->type=='excel'){
                    Excel::create('Monitoring PRK AI PU dan KIT', function ($excel) use($sb, $fase, $input_tahun, $input_sb, $input_distrik, $input_lokasi, $input_fase, $input_draft_form_10_pk, $input_draft_form_10_pu, $dataparent, $datainti, $datakegiatan, $nama_bln_dipilih, $name_draft_form_10_pu, $name_draft_form_10_pk, $data_prk_item) {
                            $excel->setTitle('Monitoring PRK AI PU dan KIT');
                            $excel->setCreator('Laravel')->setCompany('Profio', 'PJB');
                            $excel->setDescription('Monitoring PRK AI PU dan KIT');
                            $excel->sheet('Monitoring PRK AI PU dan KIT', function ($sheet) use($sb, $fase, $input_tahun, $input_sb, $input_distrik, $input_lokasi, $input_fase, $input_draft_form_10_pk, $input_draft_form_10_pu, $dataparent, $datainti, $datakegiatan, $nama_bln_dipilih, $name_draft_form_10_pu, $name_draft_form_10_pk, $data_prk_item){
                                $sheet->loadView('pengendalian_output.monitoring_prk_ai_pu_pk.excel')
                                        ->with('sb', $sb)
                                        ->with('fase', $fase)
                                        ->with('input_tahun', $input_tahun)
                                        ->with('input_sb', $input_sb)
                                        ->with('input_distrik', $input_distrik)
                                        ->with('input_lokasi', $input_lokasi)
                                        ->with('input_fase', $input_fase)
                                        ->with('input_draft_form_10_pk', $input_draft_form_10_pk)
                                        ->with('input_draft_form_10_pu', $input_draft_form_10_pu)
                                        ->with('dataparent', $dataparent)
                                        ->with('datainti', $datainti)
                                        ->with('datakegiatan', $datakegiatan)
                                        ->with('nama_bln_dipilih', $nama_bln_dipilih)
                                        ->with('name_draft_form_10_pu', $name_draft_form_10_pu)
                                        ->with('name_draft_form_10_pk', $name_draft_form_10_pk)
                                        ->with('data_prk_item', $data_prk_item);
                            });
                        })->download('xlsx');
                  }
              }
        }

        return view('pengendalian_output.monitoring_prk_ai_pu_pk.index', compact('sb', 'fase', 'input_tahun', 'input_sb', 'input_distrik', 'input_lokasi', 'input_fase', 'input_draft_form_10_pk', 'input_draft_form_10_pu', 'dataparent', 'datainti','datakegiatan', 'distrik', 'lokasi','tahun', 'draft_form_10_pu', 'draft_form_10_pk', 'nama_bln_dipilih', 'name_draft_form_10_pu', 'name_draft_form_10_pk', 'notification_failed', 'data_prk_item'));
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
                              join pgdl_sheets s on s.id = e.pgdl_sheet_id
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

    function get_no_po($no_prk, $month, $years, $int_input_distrik){
        //ambil data report dashboard dinamis
        $pgdl_report_dashboard_page_id = 2;

        $distrik = Distrik::where('id', $int_input_distrik)->first()->code1;
        // dd($distrik);
        $setting_po_no = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                        ->where('tahun', $years)
                        ->where('sequence', 8)
                        ->first();

        $setting_po_item = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                        ->where('tahun', $years)
                        ->where('sequence', 9)
                        ->first();

        $setting_account_code = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                        ->where('tahun', $years)
                        ->where('sequence', 10)
                        ->first();
        //end of ambil data report dashboard dinamis

      // $res = DB::select("select distinct po_no, account_code, po_item
      //                     from pgdl_pljprk_ai
      //                     where project_no = '".substr($no_prk,2)."' and months between 1 and ".$month."
      //                     and years = '".$years."'
      //                     order by po_no, po_item asc;");

      $res = DB::select("select distinct ".$setting_po_no->kolom.", ".$setting_account_code->kolom.", ".$setting_po_item->kolom."
                          from pgdl_pljprk_ai
                          where project_no = '".substr($no_prk,2)."' and months between 1 and ".$month."
                          and years = '".$years."'
                          and dstrct_code = '".$distrik."'
                          order by po_no, po_item asc;");

      return $res;
    }

    function get_itemp_po($no_prk, $month, $po_no, $po_item, $account_code, $years){
        //ambil data report dashboard dinamis
        $pgdl_report_dashboard_page_id = 2;

        $setting_val_required = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                        ->where('tahun', $years)
                        ->where('sequence', 11)
                        ->first();

        $setting_tran_amount = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                        ->where('tahun', $years)
                        ->where('sequence', 12)
                        ->first();
        //end of ambil data report dashboard dinamis

        $query_po_no = "= '".$po_no."'";
        if($po_no==NULL) {
            $query_po_no = 'IS NULL';
        }

        $query_po_item = "= '".$po_item."'";
        if($po_item==NULL) {
            $query_po_item = 'IS NULL';
        }

        $res = DB::select(" select ".$setting_val_required->kolom." as kontrak, SUM(".$setting_tran_amount->kolom.") as disburse
                          from pgdl_pljprk_ai
                          where project_no = '".substr($no_prk,2)."' 
                            and po_no ".$query_po_no." 
                            and po_item ".$query_po_item." 
                            and account_code = '".$account_code."'
                            and months between 1 and ".$month."
                            and years = '".$years."'
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
                              join pgdl_sheets s on s.id = e.pgdl_sheet_id
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
                              join pgdl_sheets s on s.id = e.pgdl_sheet_id
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
                              join pgdl_sheets s on s.id = e.pgdl_sheet_id
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
                              join pgdl_sheets s on s.id = e.pgdl_sheet_id
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

