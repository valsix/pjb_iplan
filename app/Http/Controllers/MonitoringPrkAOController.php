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
use App\Entities\PgdlReportDashboardSetting;
Use DB;
use Illuminate\Support\Facades\Input;
use Excel;

class MonitoringPrkAOController extends Controller
{
    public function Monitoring_PRK_AO(Request $request)
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

        $int_count_6_rutin = NULL;
        $int_count_6_rutin_update = NULL;
        $int_count_6_reimburse = NULL;

        $count_I_PEG = NULL;
        $count_I_ADM = NULL;
        $count_I_PENDUKUNG_EP = NULL;
        $count_I_BIAYA_USAHA = NULL;
        $count_I_DILUAR_USAHA = NULL;

        $input_tahun = $request->input('tahun_anggaran');
        $input_sb = $request->input('strategi_bisnis');
        $input_distrik = $request->input('distrik');
        $int_input_distrik = (int)$input_distrik;
        $input_lokasi = $request->input('lokasi');
        $int_input_lokasi = (int)$input_lokasi;
        $input_fase = $request->input('fase');
        $int_input_bulan = (int)$request->input('bulan');

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

        //$input_draft_rkau = $request->input('draft_rkau');
        //$input_draft_form_6_reimburse = $request->input('draft_form_6_reimburse');
        // $input_draft_rkau = $this->get_drafts_rkau_ids($int_input_distrik, $input_tahun, $input_fase);
        // $input_draft_form_6_reimburse = $this->get_drafts_form_6_reimburse_ids($int_input_distrik, $input_tahun, $input_fase);
        // $input_draft_form_6_rutin = $this->get_drafts_form_6_rutin_ids($int_input_distrik, $input_tahun, $input_fase);
        // $input_draft_form_bahan_bakar = $this->get_drafts_form_bahan_bakar_ids($int_input_distrik, $input_tahun, $input_fase);
        // $input_draft_form_penyusutan = $this->get_drafts_form_penyusutan_ids($int_input_distrik, $input_tahun, $input_fase);

        $input_draft_rkau = $this->get_drafts_ketetapan_ids(1,$int_input_distrik, $input_tahun);
        $input_draft_form_6_reimburse = $this->get_drafts_ketetapan_ids(2,$int_input_distrik, $input_tahun);
        $input_draft_form_6_rutin = $this->get_drafts_ketetapan_ids(3,$int_input_distrik, $input_tahun);
        // $input_draft_form_6_rutin_return_fi_id = $this->get_drafts_ketetapan_ids_return_file_import_id(3,$int_input_distrik, $input_tahun);
        $input_draft_form_bahan_bakar = $this->get_drafts_ketetapan_ids(7,$int_input_distrik, $input_tahun);
        $input_draft_form_penyusutan = $this->get_drafts_ketetapan_ids(9,$int_input_distrik, $input_tahun);

        //dd($input_draft_form_6_reimburse);

        //$input_draft_form_6_rutin = $request->input('draft_form_6_rutin');
        //$input_draft_form_bahan_bakar = $request->input('draft_form_bahan_bakar');
        //$input_draft_form_penyusutan = $request->input('draft_form_penyusutan');

        $name_draft_rkau = '';
        $name_draft_form_6_reimburse = '';
        $name_draft_form_6_rutin = '';
        $name_draft_form_bahan_bakar = '';
        $name_draft_form_penyusutan = '';

        if($input_draft_rkau){
            $name_draft_rkau = $this->get_names(1, $input_draft_rkau);
        }
        if($input_draft_form_6_reimburse){
            $name_draft_form_6_reimburse = $this->get_names(2, $input_draft_form_6_reimburse);
        }
        if($input_draft_form_6_rutin){
            $name_draft_form_6_rutin = $this->get_names(3, $input_draft_form_6_rutin);
        }
        if($input_draft_form_bahan_bakar){
            $name_draft_form_bahan_bakar = $this->get_names(7, $input_draft_form_bahan_bakar);
        }
        if($input_draft_form_penyusutan){
            $name_draft_form_penyusutan = $this->get_names(9, $input_draft_form_penyusutan);
        }

        if ($request->input('strategi_bisnis') != NULL) {
            $input_sb = DB::table('strategi_bisnis')->select('name','id')->where('id', $request->input('strategi_bisnis'))->get()[0];
            $distrik = Distrik::select('name','id')->where('strategi_bisnis_id',$input_sb->id)->get();
        }
        if ($request->input('distrik') != NULL) {
            $input_distrik = DB::table('distrik')->select('name','id','code1')->where('id', $request->distrik)->get()[0];
            $lokasi = Lokasi::select('name','id')->where('distrik_id',$input_distrik->id)->get();
        }
        if ($request->input('lokasi') != NULL) {
            $input_lokasi = DB::table('lokasi')->select('name','id')->where('id', $request->lokasi)->get()[0];
        }

        $input_lokasi = Lokasi::where('distrik_id', $request->distrik)->select("name", "id")->get();

        if ($request->input('fase') != NULL) {
            $input_fase = DB::table('fases')->select('name','id')->where('id', $request->fase)->get()[0];

            $draft_form_rkau = $this->get_drafts(1,$int_input_distrik,$input_tahun);
            $draft_form_6_reimburse = $this->get_drafts(2,$int_input_distrik,$input_tahun);
            $draft_form_6_rutin = $this->get_drafts(3,$int_input_distrik,$input_tahun);
            $draft_form_bahan_bakar = $this->get_drafts(7,$int_input_distrik,$input_tahun);
            $draft_form_penyusutan = $this->get_drafts(9,$int_input_distrik,$input_tahun);
            //dd($draft_form_6_reimburse);
        }
        /*
        if ($request->input('draft_rkau') != NULL) {
            $input_draft_rkau = DB::table('file_imports')->select('draft_versi', 'id', 'name')->where('id', $request->draft_rkau)->get()[0];
            $draft_form_rkau = $this->get_drafts(1,$int_input_distrik,$input_tahun);
            //dd($input_draft_rkau->name);
        }
        if ($request->input('draft_form_6_reimburse') != NULL) {
            $input_draft_form_6_reimburse = DB::table('file_imports')->select('draft_versi', 'id', 'name')->where('id', $request->draft_form_6_reimburse)->get()[0];
            $draft_form_6_reimburse = $this->get_drafts(2,$int_input_distrik,$input_tahun);
            //dd($draft_form_6_reimburse);
        }*/
        /*
        $draft_form_6_reimburse = $this->get_drafts(2,$int_input_distrik,$input_tahun);
        dd($draft_form_6_reimburse);
        if ($request->input('draft_form_6_rutin') != NULL) {
            $input_draft_form_6_rutin = DB::table('file_imports')->select('draft_versi', 'id', 'name')->where('id', $request->draft_form_6_rutin)->get()[0];
            $draft_form_6_rutin = $this->get_drafts(3,$int_input_distrik,$input_tahun);
        }
        if ($request->input('draft_form_bahan_bakar') != NULL) {
            $input_draft_form_bahan_bakar = DB::table('file_imports')->select('draft_versi', 'id', 'name')->where('id', $request->draft_form_bahan_bakar)->get()[0];
            $draft_form_bahan_bakar = $this->get_drafts(7,$int_input_distrik,$input_tahun);
        }
        if ($request->input('draft_form_penyusutan') != NULL) {
            $input_draft_form_penyusutan = DB::table('file_imports')->select('draft_versi', 'id', 'name')->where('id', $request->draft_form_penyusutan)->get()[0];
            $draft_form_penyusutan = $this->get_drafts(9,$int_input_distrik,$input_tahun);
        }
        */

        if ($input_distrik != NULL) {

            $dataparent = array();
            $datainti = array();
            $datakegiatan = array();

            // dd($input_draft_rkau);
            //Start query I-PEG
        if($input_draft_rkau){
        //if($request->input('draft_rkau')) {
            //ambil data report dashboard dinamis
            $pgdl_report_dashboard_page_id = 1;
            $jenis_id = 1;
            $pgdl_sheet_name = 'I-PEG';

            $setting_form_rkau_ipeg_prk_parent = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 1)
                            ->first();

            $setting_form_rkau_ipeg_prk_inti = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 2)
                            ->first();

            $setting_form_rkau_ipeg_no_prk_kegiatan = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 3)
                            ->first();

            $setting_form_rkau_ipeg_desc_prk_kegiatan = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 4)
                            ->first();

            $setting_form_rkau_ipeg_beban_mat = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 6)
                            ->first();

            $setting_form_rkau_ipeg_beban_mat_update = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 8)
                            ->first();

            $setting_form_rkau_ipeg_start_kolom = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 9)
                            ->first();

            //end of ambil data report dashboard dinamis

            // $form_rkau_ipeg_prk_parent       = $this->get_form_rkau_parent($input_draft_rkau, $int_input_distrik, 'I-PEG', 'E');
            $form_rkau_ipeg_prk_parent       = $this->get_form_rkau_parent($input_draft_rkau, $int_input_distrik, 'I-PEG', $setting_form_rkau_ipeg_prk_parent->kolom);
            //dd($input_draft_rkau);
            $daftar_prk_parent_form_rkau_ipeg = array();
            foreach ($form_rkau_ipeg_prk_parent as $key => $value) {
                $desc_prk_parent = $this->get_prk_parent_desc(substr($value->value, 2,2));
                $daftar_prk_parent_form_rkau_ipeg[$value->value] = array(
                    'desc_prk_parent' => ($desc_prk_parent!= null ? $desc_prk_parent->desc_prk_parent : ''),
                    'beban_mat'     => 0,
                    'beban_mat_update'     => 0,
                    //'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'ijin_proses_update'   => 0,
                    // 'total_year_estimate'   => 0,
                    //'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    );
            }

            // $form_rkau_ipeg_prk_inti       = $this->get_form_rkau_inti($input_draft_rkau, $int_input_distrik, 'I-PEG', 'E');
            $form_rkau_ipeg_prk_inti       = $this->get_form_rkau_inti($input_draft_rkau, $int_input_distrik, 'I-PEG', $setting_form_rkau_ipeg_prk_inti->kolom);
            $daftar_prk_inti_form_rkau_ipeg = array();
            foreach ($form_rkau_ipeg_prk_inti as $key => $value) {
                $desc_prk_inti = $this->get_prk_inti_desc(substr($value->value, 2,2),substr($value->value, 4,2));
                $daftar_prk_inti_form_rkau_ipeg[$value->value] = array(
                    'desc_prk_inti' => ($desc_prk_inti!= null ? $desc_prk_inti->desc_prk_inti : ''),
                    'prk_parent'    => substr($value->value, 0,4),
                    'beban_mat'     => 0,
                    'beban_mat_update'     => 0,
                    //'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'ijin_proses_update'   => 0,
                    // 'total_year_estimate'   => 0,
                    //'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    );
            }

            // $daftar_prk_kegiatan_form_rkau_ipeg = array();
            // $form_rkau_ipeg_prk_kegiatan        = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-PEG', 'E');
            // $form_rkau_ipeg_desc_prk_kegiatan   = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-PEG', 'F');
            // $form_rkau_ipeg_beban_mat           = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-PEG', 'H');
            $daftar_prk_kegiatan_form_rkau_ipeg = array();
            $form_rkau_ipeg_prk_kegiatan        = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-PEG', $setting_form_rkau_ipeg_no_prk_kegiatan->kolom);
            $form_rkau_ipeg_desc_prk_kegiatan   = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-PEG', $setting_form_rkau_ipeg_desc_prk_kegiatan->kolom);
            $form_rkau_ipeg_beban_mat           = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-PEG', $setting_form_rkau_ipeg_beban_mat->kolom);
            //$form_rkau_ipeg_cash_oth_1          = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-PEG', 'J');
            //$form_rkau_ipeg_cash_oth_2          = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-PEG', 'K');
            // $form_rkau_ipeg_total_year_estimates= $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-PEG', 'H');

            // $form_rkau_ipeg_beban_mat_update    = $this->get_form_rkau_update($input_draft_rkau, $int_input_distrik, 'I-PEG', 'H');

            $form_rkau_ipeg_beban_mat_update    = $this->get_form_rkau_update($input_draft_rkau, $int_input_distrik, 'I-PEG', $setting_form_rkau_ipeg_beban_mat_update->kolom);

            //$form_rkau_ipeg_disburse = array();
            $form_rkau_ipeg_disburse_update = array();

            // $start_kolom = 'M';
            $start_kolom = $setting_form_rkau_ipeg_start_kolom->kolom;
            for($bulan = 1; $bulan<=$int_input_bulan; $bulan++){
                $form_rkau_ipeg_disburse_update[$bulan] = $this->get_form_rkau_disburse_update($input_draft_rkau, $int_input_distrik, 'I-PEG', $start_kolom);
                $start_kolom++;
            }

/*            $form_rkau_ipeg_disburse[1] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-PEG', 'M');
            $form_rkau_ipeg_disburse[2] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-PEG', 'N');
            $form_rkau_ipeg_disburse[3] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-PEG', 'O');
            $form_rkau_ipeg_disburse[4] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-PEG', 'P');
            $form_rkau_ipeg_disburse[5] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-PEG', 'Q');
            $form_rkau_ipeg_disburse[6] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-PEG', 'R');
            $form_rkau_ipeg_disburse[7] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-PEG', 'S');
            $form_rkau_ipeg_disburse[8] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-PEG', 'T');
            $form_rkau_ipeg_disburse[9] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-PEG', 'U');
            $form_rkau_ipeg_disburse[10] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-PEG', 'V');
            $form_rkau_ipeg_disburse[11] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-PEG', 'W');
            $form_rkau_ipeg_disburse[12] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-PEG', 'X');
*/
            //dd($form_rkau_ipeg_prk_inti);
            foreach ($form_rkau_ipeg_prk_kegiatan as $key => $value) {
                $parent = substr($value,0,4);
                $inti = substr($value,0,6);
                //dd($form_rkau_ipeg_disburse);
                $disburse = array();
                $disburse_sd_bulan = 0;
                $bln = array();
                //for($bulan=1; $bulan<=12; $bulan++){
                for($bulan=1; $bulan<=$int_input_bulan; $bulan++){
                    //$disburse[$bulan] = (float)$form_rkau_ipeg_disburse[$bulan][$key];
                    $disburse_sd_bulan += (float)$form_rkau_ipeg_disburse_update[$bulan][$key];
                    $bln[$bulan] = $bulan;
                    //$total_disburse += $disburse[$bulan];
                    // $daftar_prk_inti_form_rkau_ipeg[$inti]['disburse'][$bulan] += $disburse[$bulan];
                    // $daftar_prk_parent_form_rkau_ipeg[$parent]['disburse'][$bulan] += $disburse[$bulan];
                }
                $bln = implode(",",$bln);
                $bln = "(".$bln.")";

                // $get_disburse_sd_bulan_realisasi = $this->get_disburse_sd_bulan_realisasi_rkau($input_distrik->code1, $bln, $value);

                // if($get_disburse_sd_bulan_realisasi) {
                //     $disburse_sd_bulan_realisasi = (float)$get_disburse_sd_bulan_realisasi[0]->sum;
                // }
                // else {
                //     $disburse_sd_bulan_realisasi = 0;
                // }

                $get_actuals = (float)$this->get_actuals($input_distrik->code1, $value);
                $get_commitments = (float)$this->get_commitments($input_distrik->code1, $value);

                //cek ada No PRK kegiatan
                // $cek_no_prk_ketetapan = $this->cek_no_prk_form_rkau_ketetapan($input_draft_rkau,$int_input_distrik, 'E', $value, 'I-PEG');
                $cek_no_prk_ketetapan = $this->cek_no_prk_form_rkau_ketetapan($input_draft_rkau,$int_input_distrik, $setting_form_rkau_ipeg_no_prk_kegiatan->kolom, $value, 'I-PEG');
                if($cek_no_prk_ketetapan) {
                    // $beban_mat_form_rkau_ipeg_ketetapan[$key] = $this->get_form_rkau_ketetapan($input_draft_rkau,$int_input_distrik, 'H', 'E', $value, 'I-PEG')[0]->value;
                    $beban_mat_form_rkau_ipeg_ketetapan[$key] = $this->get_form_rkau_ketetapan($input_draft_rkau,$int_input_distrik, $setting_form_rkau_ipeg_beban_mat->kolom, $setting_form_rkau_ipeg_no_prk_kegiatan->kolom, $value, 'I-PEG')[0]->value;
                }
                else {
                    $beban_mat_form_rkau_ipeg_ketetapan[$key] = 0;
                }

                $temp = array(
                    'prk_kegiatan' => $value,
                    'prk_inti' => $inti,
                    'prk_parent' => $parent,
                    'desc_prk_kegiatan' => $form_rkau_ipeg_desc_prk_kegiatan[$key],
                    // 'desc_prk_inti' => $form_rkau_ipeg_desc_prk_inti[$key],
                    // 'desc_prk_parent' => $form_rkau_ipeg_desc_prk_parent[$key],
                    // 'beban_mat' => (float)$form_rkau_ipeg_beban_mat[$key],
                    'beban_mat' => (float)$beban_mat_form_rkau_ipeg_ketetapan[$key],
                    'beban_mat_update' => (float)$form_rkau_ipeg_beban_mat_update[$key],
                    //'cash_oth' => ((float)$form_rkau_ipeg_cash_oth_1[$key]) + ((float)$form_rkau_ipeg_cash_oth_2[$key]),
                    'ijin_proses' => 0,
                    'ijin_proses_update' => 0,
                    //'disburse' => $disburse,
                    'disburse_sd_bulan' => $disburse_sd_bulan,
                    'disburse_sd_bulan_realisasi' => $get_actuals,
                    'estimate_realisasi' => $get_actuals + $get_commitments,
                    // 'total_year_estimate' => (float)$form_rkau_ipeg_total_year_estimates[$key],
                    //'total_disburse' => (float)$total_disburse,
                );
                array_push($daftar_prk_kegiatan_form_rkau_ipeg, $temp);

                // $daftar_prk_inti_form_rkau_ipeg[$inti]['desc_prk_inti'] = $temp['desc_prk_inti'];
                $daftar_prk_inti_form_rkau_ipeg[$inti]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_inti_form_rkau_ipeg[$inti]['beban_mat_update'] += $temp['beban_mat_update'];
                //$daftar_prk_inti_form_rkau_ipeg[$inti]['cash_oth'] += $temp['cash_oth'];
                $daftar_prk_inti_form_rkau_ipeg[$inti]['ijin_proses'] += $temp['ijin_proses'];
                $daftar_prk_inti_form_rkau_ipeg[$inti]['ijin_proses_update'] += $temp['ijin_proses_update'];
                // $daftar_prk_inti_form_rkau_ipeg[$inti]['total_year_estimate'] += $temp['total_year_estimate'];
                //$daftar_prk_inti_form_rkau_ipeg[$inti]['total_disburse'] += $temp['total_disburse'];

                // $daftar_prk_parent_form_rkau_ipeg[$parent]['desc_prk_parent'] = $temp['desc_prk_parent'];
                $daftar_prk_parent_form_rkau_ipeg[$parent]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_parent_form_rkau_ipeg[$parent]['beban_mat_update'] += $temp['beban_mat_update'];
                //$daftar_prk_parent_form_rkau_ipeg[$parent]['cash_oth'] += $temp['cash_oth'];
                $daftar_prk_parent_form_rkau_ipeg[$parent]['ijin_proses'] += $temp['ijin_proses'];
                $daftar_prk_parent_form_rkau_ipeg[$parent]['ijin_proses_update'] += $temp['ijin_proses_update'];
                // $daftar_prk_parent_form_rkau_ipeg[$parent]['total_year_estimate'] += $temp['total_year_estimate'];
                //$daftar_prk_parent_form_rkau_ipeg[$inti]['total_disburse'] += $temp['total_disburse'];
            }
            $dataparent['form_rkau_ipeg'] = $daftar_prk_parent_form_rkau_ipeg;
            $datainti['form_rkau_ipeg'] = $daftar_prk_inti_form_rkau_ipeg;
            $datakegiatan['form_rkau_ipeg'] = $daftar_prk_kegiatan_form_rkau_ipeg;

        // //End of query I-PEG

        //Start query I-ADM
            //ambil data report dashboard dinamis
            $pgdl_report_dashboard_page_id = 1;
            $jenis_id = 1;
            $pgdl_sheet_name = 'I-ADM';

            $setting_form_rkau_iadm_prk_parent = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 1)
                            ->first();

            $setting_form_rkau_iadm_prk_inti = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 2)
                            ->first();

            $setting_form_rkau_iadm_no_prk_kegiatan = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 3)
                            ->first();

            $setting_form_rkau_iadm_desc_prk_kegiatan = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 4)
                            ->first();

            $setting_form_rkau_iadm_beban_mat = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 6)
                            ->first();

            $setting_form_rkau_iadm_beban_mat_update = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 8)
                            ->first();

            $setting_form_rkau_iadm_start_kolom = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 9)
                            ->first();

            //end of ambil data report dashboard dinamis

            // $form_rkau_iadm_prk_parent       = $this->get_form_rkau_parent($input_draft_rkau, $int_input_distrik, 'I-ADM', 'E');
            $form_rkau_iadm_prk_parent       = $this->get_form_rkau_parent($input_draft_rkau, $int_input_distrik, 'I-ADM', $setting_form_rkau_iadm_prk_parent->kolom);
            $daftar_prk_parent_form_rkau_iadm = array();
            foreach ($form_rkau_iadm_prk_parent as $key => $value) {
                $desc_prk_parent = $this->get_prk_parent_desc(substr($value->value, 2,2));
                $daftar_prk_parent_form_rkau_iadm[$value->value] = array(
                    'desc_prk_parent' => ($desc_prk_parent!= null ? $desc_prk_parent->desc_prk_parent : ''),
                    'beban_mat'     => 0,
                    'beban_mat_update'     => 0,
                    //'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'ijin_proses_update'   => 0,
                    // 'total_year_estimate'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    );
            }

            // $form_rkau_iadm_prk_inti       = $this->get_form_rkau_inti($input_draft_rkau, $int_input_distrik, 'I-ADM', 'E');
            $form_rkau_iadm_prk_inti       = $this->get_form_rkau_inti($input_draft_rkau, $int_input_distrik, 'I-ADM', $setting_form_rkau_iadm_prk_inti->kolom);
            $daftar_prk_inti_form_rkau_iadm = array();
            foreach ($form_rkau_iadm_prk_inti as $key => $value) {
                $desc_prk_inti = $this->get_prk_inti_desc(substr($value->value, 2,2),substr($value->value, 4,2));
                $daftar_prk_inti_form_rkau_iadm[$value->value] = array(
                    'desc_prk_inti' => ($desc_prk_inti != null ? $desc_prk_inti->desc_prk_inti : ''),
                    'prk_parent'    => substr($value->value, 0,4),
                    'beban_mat'     => 0,
                    'beban_mat_update'     => 0,
                    //'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'ijin_proses_update'   => 0,
                    // 'total_year_estimate'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    );
            }

            $daftar_prk_kegiatan_form_rkau_iadm = array();
            // $form_rkau_iadm_prk_kegiatan       = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-ADM', 'E');
            // $form_rkau_iadm_desc_prk_kegiatan   = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-ADM', 'F');
            // $form_rkau_iadm_beban_mat           = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-ADM', 'H');

            $form_rkau_iadm_prk_kegiatan       = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-ADM', $setting_form_rkau_iadm_no_prk_kegiatan->kolom);
            $form_rkau_iadm_desc_prk_kegiatan   = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-ADM', $setting_form_rkau_iadm_desc_prk_kegiatan->kolom);
            $form_rkau_iadm_beban_mat           = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-ADM', $setting_form_rkau_iadm_beban_mat->kolom);
            //$form_rkau_iadm_cash_oth_1          = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-ADM', 'J');
            //$form_rkau_iadm_cash_oth_2          = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-ADM', 'K');
            // $form_rkau_iadm_total_year_estimates= $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-ADM', 'H');

            // $form_rkau_iadm_beban_mat_update    = $this->get_form_rkau_update($input_draft_rkau, $int_input_distrik, 'I-ADM', 'H');
            $form_rkau_iadm_beban_mat_update    = $this->get_form_rkau_update($input_draft_rkau, $int_input_distrik, 'I-ADM', $setting_form_rkau_iadm_beban_mat_update->kolom);

            $form_rkau_iadm_disburse_update = array();

            // $start_kolom = 'M';
            $start_kolom = $setting_form_rkau_iadm_start_kolom->kolom;
            for($bulan = 1; $bulan<=$int_input_bulan; $bulan++){
                $form_rkau_iadm_disburse_update[$bulan] = $this->get_form_rkau_disburse_update($input_draft_rkau, $int_input_distrik, 'I-ADM', $start_kolom);
                $start_kolom++;
            }
/*
            $form_rkau_iadm_disburse[1] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-ADM', 'M');
            $form_rkau_iadm_disburse[2] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-ADM', 'N');
            $form_rkau_iadm_disburse[3] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-ADM', 'O');
            $form_rkau_iadm_disburse[4] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-ADM', 'P');
            $form_rkau_iadm_disburse[5] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-ADM', 'Q');
            $form_rkau_iadm_disburse[6] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-ADM', 'R');
            $form_rkau_iadm_disburse[7] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-ADM', 'S');
            $form_rkau_iadm_disburse[8] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-ADM', 'T');
            $form_rkau_iadm_disburse[9] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-ADM', 'U');
            $form_rkau_iadm_disburse[10] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-ADM', 'V');
            $form_rkau_iadm_disburse[11] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-ADM', 'W');
            $form_rkau_iadm_disburse[12] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-ADM', 'X');
*/
            foreach ($form_rkau_iadm_prk_kegiatan as $key => $value) {
                $parent = substr($value,0,4);
                $inti = substr($value,0,6);

                //$disburse = array();
                $disburse_sd_bulan = 0;
                $bln = array();
                //for($bulan=1; $bulan<=12; $bulan++){
                for($bulan=1; $bulan<=$int_input_bulan; $bulan++){
                    //$disburse[$bulan] = (float)$form_rkau_iadm_disburse[$bulan][$key];
                    $disburse_sd_bulan += (float)$form_rkau_iadm_disburse_update[$bulan][$key];
                    $bln[$bulan] = $bulan;
                    // $daftar_prk_inti_form_rkau_iadm[$inti]['disburse'][$bulan] += $disburse[$bulan];
                    // $daftar_prk_parent_form_rkau_iadm[$parent]['disburse'][$bulan] += $disburse[$bulan];
                }
                $bln = implode(",",$bln);
                $bln = "(".$bln.")";

                // $get_disburse_sd_bulan_realisasi = $this->get_disburse_sd_bulan_realisasi_rkau($input_distrik->code1, $bln, $value);

                // if($get_disburse_sd_bulan_realisasi) {
                //     $disburse_sd_bulan_realisasi = (float)$get_disburse_sd_bulan_realisasi[0]->sum;
                // }
                // else {
                //     $disburse_sd_bulan_realisasi = 0;
                // }

                $get_actuals = (float)$this->get_actuals($input_distrik->code1, $value);
                $get_commitments = (float)$this->get_commitments($input_distrik->code1, $value);

                //cek ada No PRK kegiatan
                // $cek_no_prk_ketetapan = $this->cek_no_prk_form_rkau_ketetapan($input_draft_rkau,$int_input_distrik, 'E', $value, 'I-ADM');
                $cek_no_prk_ketetapan = $this->cek_no_prk_form_rkau_ketetapan($input_draft_rkau,$int_input_distrik, $setting_form_rkau_iadm_no_prk_kegiatan->kolom, $value, 'I-ADM');
                if($cek_no_prk_ketetapan) {
                    // $beban_mat_form_rkau_iadm_ketetapan[$key] = $this->get_form_rkau_ketetapan($input_draft_rkau,$int_input_distrik, 'H', 'E', $value, 'I-ADM')[0]->value;
                    $beban_mat_form_rkau_iadm_ketetapan[$key] = $this->get_form_rkau_ketetapan($input_draft_rkau,$int_input_distrik, $setting_form_rkau_iadm_beban_mat->kolom, $setting_form_rkau_iadm_no_prk_kegiatan->kolom, $value, 'I-ADM')[0]->value;
                }
                else {
                    $beban_mat_form_rkau_iadm_ketetapan[$key] = 0;
                }

                $temp = array(
                    'prk_kegiatan' => $value,
                    'prk_inti' => $inti,
                    'prk_parent' => $parent,
                    'desc_prk_kegiatan' => $form_rkau_iadm_desc_prk_kegiatan[$key],
                    // 'desc_prk_inti' => $form_rkau_iadm_desc_prk_inti[$key],
                    // 'desc_prk_parent' => $form_rkau_iadm_desc_prk_parent[$key],
                    // 'beban_mat' => (float)$form_rkau_iadm_beban_mat[$key],
                    'beban_mat' => (float)$beban_mat_form_rkau_iadm_ketetapan[$key],
                    'beban_mat_update' => (float)$form_rkau_iadm_beban_mat_update[$key],
                    //'cash_oth' => ((float)$form_rkau_iadm_cash_oth_1[$key]) + ((float)$form_rkau_iadm_cash_oth_2[$key]),
                    'ijin_proses' => 0,
                    'ijin_proses_update' => 0,
                    //'disburse' => $disburse,
                    'disburse_sd_bulan' => $disburse_sd_bulan,
                    'disburse_sd_bulan_realisasi' => $get_actuals,
                    'estimate_realisasi' => $get_actuals + $get_commitments,
                    // 'total_year_estimate' => (float)$form_rkau_iadm_total_year_estimates[$key],
                );
                array_push($daftar_prk_kegiatan_form_rkau_iadm, $temp);

                // $daftar_prk_inti_form_rkau_iadm[$inti]['desc_prk_inti'] = $temp['desc_prk_inti'];
                $daftar_prk_inti_form_rkau_iadm[$inti]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_inti_form_rkau_iadm[$inti]['beban_mat_update'] += $temp['beban_mat_update'];
              //  $daftar_prk_inti_form_rkau_iadm[$inti]['cash_oth'] += $temp['cash_oth'];
                $daftar_prk_inti_form_rkau_iadm[$inti]['ijin_proses'] += $temp['ijin_proses'];
                $daftar_prk_inti_form_rkau_iadm[$inti]['ijin_proses_update'] += $temp['ijin_proses_update'];
                // $daftar_prk_inti_form_rkau_iadm[$inti]['total_year_estimate'] += $temp['total_year_estimate'];

                // $daftar_prk_parent_form_rkau_iadm[$parent]['desc_prk_parent'] = $temp['desc_prk_parent'];
                $daftar_prk_parent_form_rkau_iadm[$parent]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_parent_form_rkau_iadm[$parent]['beban_mat_update'] += $temp['beban_mat_update'];
                //$daftar_prk_parent_form_rkau_iadm[$parent]['cash_oth'] += $temp['cash_oth'];
                $daftar_prk_parent_form_rkau_iadm[$parent]['ijin_proses'] += $temp['ijin_proses'];
                $daftar_prk_parent_form_rkau_iadm[$parent]['ijin_proses_update'] += $temp['ijin_proses_update'];
                // $daftar_prk_parent_form_rkau_iadm[$parent]['total_year_estimate'] += $temp['total_year_estimate'];
            }
            $dataparent['form_rkau_iadm'] = $daftar_prk_parent_form_rkau_iadm;
            $datainti['form_rkau_iadm'] = $daftar_prk_inti_form_rkau_iadm;
            $datakegiatan['form_rkau_iadm'] = $daftar_prk_kegiatan_form_rkau_iadm;

            //End of query I-ADM
            if ($input_sb->id == 1) {
                # code...
                //Start query I-PENDUKUNG EP
                //ambil data report dashboard dinamis
                $pgdl_report_dashboard_page_id = 1;
                $jenis_id = 1;
                $pgdl_sheet_name = 'I-PENDUKUNG EP';

                $setting_form_rkau_ipendukungep_prk_parent = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                                ->where('tahun', $input_tahun)
                                ->where('jenis_id', $jenis_id)
                                ->where('pgdl_sheet_name', $pgdl_sheet_name)
                                ->where('sequence', 1)
                                ->first();
                                $setting_form_rkau_ipendukungep_prk_inti = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 2)
                            ->first();

                $setting_form_rkau_ipendukungep_no_prk_kegiatan = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                                ->where('tahun', $input_tahun)
                                ->where('jenis_id', $jenis_id)
                                ->where('pgdl_sheet_name', $pgdl_sheet_name)
                                ->where('sequence', 3)
                                ->first();

                $setting_form_rkau_ipendukungep_desc_prk_kegiatan = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                                ->where('tahun', $input_tahun)
                                ->where('jenis_id', $jenis_id)
                                ->where('pgdl_sheet_name', $pgdl_sheet_name)
                                ->where('sequence', 4)
                                ->first();

                $setting_form_rkau_ipendukungep_ijin_proses = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                                ->where('tahun', $input_tahun)
                                ->where('jenis_id', $jenis_id)
                                ->where('pgdl_sheet_name', $pgdl_sheet_name)
                                ->where('sequence', 5)
                                ->first();

                $setting_form_rkau_ipendukungep_beban_mat = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                                ->where('tahun', $input_tahun)
                                ->where('jenis_id', $jenis_id)
                                ->where('pgdl_sheet_name', $pgdl_sheet_name)
                                ->where('sequence', 6)
                                ->first();

                $setting_form_rkau_ipendukungep_ijin_proses_update = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                                ->where('tahun', $input_tahun)
                                ->where('jenis_id', $jenis_id)
                                ->where('pgdl_sheet_name', $pgdl_sheet_name)
                                ->where('sequence', 7)
                                ->first();

                $setting_form_rkau_ipendukungep_beban_mat_update = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                                ->where('tahun', $input_tahun)
                                ->where('jenis_id', $jenis_id)
                                ->where('pgdl_sheet_name', $pgdl_sheet_name)
                                ->where('sequence', 8)
                                ->first();

                $setting_form_rkau_ipendukungep_start_kolom = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                                ->where('tahun', $input_tahun)
                                ->where('jenis_id', $jenis_id)
                                ->where('pgdl_sheet_name', $pgdl_sheet_name)
                                ->where('sequence', 9)
                                ->first();

                //end of ambil data report dashboard dinamis

                // $form_rkau_ipendukungep_prk_parent       = $this->get_form_rkau_parent($input_draft_rkau, $int_input_distrik, 'I-PENDUKUNG EP', 'C');
                $form_rkau_ipendukungep_prk_parent       = $this->get_form_rkau_parent($input_draft_rkau, $int_input_distrik, 'I-PENDUKUNG EP', $setting_form_rkau_ipendukungep_prk_parent->kolom);
                $daftar_prk_parent_form_rkau_ipendukungep = array();
                foreach ($form_rkau_ipendukungep_prk_parent as $key => $value) {
                    $desc_prk_parent = $this->get_prk_parent_desc(substr($value->value, 2,2));
                    $daftar_prk_parent_form_rkau_ipendukungep[$value->value] = array(
                        'desc_prk_parent' => ($desc_prk_parent!= null ? $desc_prk_parent->desc_prk_parent : ''),
                        'beban_mat'     => 0,
                        'beban_mat_update'     => 0,
                        //'cash_oth'      => 0,
                        'ijin_proses'   => 0,
                        'ijin_proses_update'   => 0,
                        // 'total_year_estimate'   => 0,
                        //'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                        );
                }

                // $form_rkau_ipendukungep_prk_inti       = $this->get_form_rkau_inti($input_draft_rkau, $int_input_distrik, 'I-PENDUKUNG EP', 'C');
                $form_rkau_ipendukungep_prk_inti       = $this->get_form_rkau_inti($input_draft_rkau, $int_input_distrik, 'I-PENDUKUNG EP', $setting_form_rkau_ipendukungep_prk_inti->kolom);
                $daftar_prk_inti_form_rkau_ipendukungep = array();
                foreach ($form_rkau_ipendukungep_prk_inti as $key => $value) {
                    $desc_prk_inti = $this->get_prk_inti_desc(substr($value->value, 2,2),substr($value->value, 4,2));
                    $daftar_prk_inti_form_rkau_ipendukungep[$value->value] = array(
                        'desc_prk_inti' => ($desc_prk_inti != null ? $desc_prk_inti->desc_prk_inti : ''),
                        'prk_parent'    => substr($value->value, 0,4),
                        'beban_mat'     => 0,
                        'beban_mat_update'     => 0,
                        //'cash_oth'      => 0,
                        'ijin_proses'   => 0,
                        'ijin_proses_update'   => 0,
                        // 'total_year_estimate'   => 0,
                        //'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                        );
                }

                $daftar_prk_kegiatan_form_rkau_ipendukungep = array();
                // $form_rkau_ipendukungep_prk_kegiatan       = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-PENDUKUNG EP', 'C');
                // $form_rkau_ipendukungep_desc_prk_kegiatan   = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-PENDUKUNG EP', 'D');
                // $form_rkau_ipendukungep_beban_mat           = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-PENDUKUNG EP', 'E');
                $form_rkau_ipendukungep_prk_kegiatan       = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-PENDUKUNG EP', $setting_form_rkau_ipendukungep_no_prk_kegiatan->kolom);
                $form_rkau_ipendukungep_desc_prk_kegiatan   = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-PENDUKUNG EP', $setting_form_rkau_ipendukungep_desc_prk_kegiatan->kolom);
                $form_rkau_ipendukungep_beban_mat           = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-PENDUKUNG EP', $setting_form_rkau_ipendukungep_beban_mat->kolom);
                //$form_rkau_ipendukungep_cash_oth          = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-PENDUKUNG EP', 'F');
                // $form_rkau_ipendukungep_ijin_proses          = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-PENDUKUNG EP', 'H');
                $form_rkau_ipendukungep_ijin_proses          = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-PENDUKUNG EP', $setting_form_rkau_ipendukungep_ijin_proses->kolom);
                // $form_rkau_ipendukungep_total_year_estimates = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-PENDUKUNG EP', 'E');

                // $form_rkau_ipendukungep_beban_mat_update       = $this->get_form_rkau_update($input_draft_rkau, $int_input_distrik, 'I-PENDUKUNG EP', 'E');
                // $form_rkau_ipendukungep_ijin_proses_update    = $this->get_form_rkau_update($input_draft_rkau, $int_input_distrik, 'I-PENDUKUNG EP', 'H');
                $form_rkau_ipendukungep_beban_mat_update       = $this->get_form_rkau_update($input_draft_rkau, $int_input_distrik, 'I-PENDUKUNG EP', $setting_form_rkau_ipendukungep_beban_mat_update->kolom);
                $form_rkau_ipendukungep_ijin_proses_update    = $this->get_form_rkau_update($input_draft_rkau, $int_input_distrik, 'I-PENDUKUNG EP', $setting_form_rkau_ipendukungep_ijin_proses_update->kolom);

                $form_rkau_ipendukungep_disburse_update = array();

                // $start_kolom = 'K';
                $start_kolom = $setting_form_rkau_ipendukungep_start_kolom->kolom;
                for($bulan = 1; $bulan<=$int_input_bulan; $bulan++){
                    //$form_rkau_ipendukungep_disburse[$bulan] = $this->get_form_rkau_disburse($input_draft_rkau, $int_input_distrik, 'I-PENDUKUNG EP', $start_kolom);
                    $form_rkau_ipendukungep_disburse_update[$bulan] = $this->get_form_rkau_disburse_update($input_draft_rkau, $int_input_distrik, 'I-PENDUKUNG EP', $start_kolom);
                    $start_kolom++;
                }


    /*            $form_rkau_ipendukungep_disburse[1] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-PENDUKUNG EP', 'K');
                $form_rkau_ipendukungep_disburse[2] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-PENDUKUNG EP', 'L');
                $form_rkau_ipendukungep_disburse[3] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-PENDUKUNG EP', 'M');
                $form_rkau_ipendukungep_disburse[4] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-PENDUKUNG EP', 'N');
                $form_rkau_ipendukungep_disburse[5] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-PENDUKUNG EP', 'O');
                $form_rkau_ipendukungep_disburse[6] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-PENDUKUNG EP', 'P');
                $form_rkau_ipendukungep_disburse[7] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-PENDUKUNG EP', 'Q');
                $form_rkau_ipendukungep_disburse[8] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-PENDUKUNG EP', 'R');
                $form_rkau_ipendukungep_disburse[9] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-PENDUKUNG EP', 'S');
                $form_rkau_ipendukungep_disburse[10] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-PENDUKUNG EP', 'T');
                $form_rkau_ipendukungep_disburse[11] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-PENDUKUNG EP', 'U');
                $form_rkau_ipendukungep_disburse[12] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-PENDUKUNG EP', 'V');
    */
                foreach ($form_rkau_ipendukungep_prk_kegiatan as $key => $value) {
                    $parent = substr($value,0,4);
                    $inti = substr($value,0,6);

                    //$disburse = array();
                    $disburse_sd_bulan = 0;
                    $bln = array();
                    //for($bulan=1; $bulan<=12; $bulan++){
                    for($bulan=1; $bulan<=$int_input_bulan; $bulan++){
                        //$disburse[$bulan] = (float)$form_rkau_ipendukungep_disburse[$bulan][$key];
                        $disburse_sd_bulan += (float)$form_rkau_ipendukungep_disburse_update[$bulan][$key];
                        $bln[$bulan] = $bulan;
                        // $daftar_prk_inti_form_rkau_ipendukungep[$inti]['disburse'][$bulan] += $disburse[$bulan];
                        // $daftar_prk_parent_form_rkau_ipendukungep[$parent]['disburse'][$bulan] += $disburse[$bulan];
                    }
                    $bln = implode(",",$bln);
                    $bln = "(".$bln.")";

                    // $get_disburse_sd_bulan_realisasi = $this->get_disburse_sd_bulan_realisasi_rkau($input_distrik->code1, $bln, $value);

                    // if($get_disburse_sd_bulan_realisasi) {
                    //     $disburse_sd_bulan_realisasi = (float)$get_disburse_sd_bulan_realisasi[0]->sum;
                    // }
                    // else {
                    //     $disburse_sd_bulan_realisasi = 0;
                    // }

                    $get_actuals = (float)$this->get_actuals($input_distrik->code1, $value);
                    $get_commitments = (float)$this->get_commitments($input_distrik->code1, $value);

                    //cek ada No PRK kegiatan
                    // $cek_no_prk_ketetapan = $this->cek_no_prk_form_rkau_ketetapan($input_draft_rkau,$int_input_distrik, 'C', $value, 'I-PENDUKUNG EP');
                    $cek_no_prk_ketetapan = $this->cek_no_prk_form_rkau_ketetapan($input_draft_rkau,$int_input_distrik, $setting_form_rkau_ipendukungep_no_prk_kegiatan->kolom, $value, 'I-PENDUKUNG EP');
                    if($cek_no_prk_ketetapan) {
                        // $beban_mat_form_rkau_ipendukungep_ketetapan[$key] = $this->get_form_rkau_ketetapan($input_draft_rkau,$int_input_distrik, 'E', 'C', $value, 'I-PENDUKUNG EP')[0]->value;
                        // $ijin_proses_form_rkau_ipendukungep_ketetapan[$key] = $this->get_form_rkau_ketetapan($input_draft_rkau,$int_input_distrik, 'H', 'C', $value, 'I-PENDUKUNG EP')[0]->value;
                        $beban_mat_form_rkau_ipendukungep_ketetapan[$key] = $this->get_form_rkau_ketetapan($input_draft_rkau,$int_input_distrik, $setting_form_rkau_ipendukungep_beban_mat->kolom, $setting_form_rkau_ipendukungep_no_prk_kegiatan->kolom, $value, 'I-PENDUKUNG EP')[0]->value;
                        $ijin_proses_form_rkau_ipendukungep_ketetapan[$key] = $this->get_form_rkau_ketetapan($input_draft_rkau,$int_input_distrik, $setting_form_rkau_ipendukungep_ijin_proses->kolom, $setting_form_rkau_ipendukungep_no_prk_kegiatan->kolom, $value, 'I-PENDUKUNG EP')[0]->value;
                    }
                    else {
                        $beban_mat_form_rkau_ipendukungep_ketetapan[$key] = 0;
                        $ijin_proses_form_rkau_ipendukungep_ketetapan[$key] = 0;
                    }

                    $temp = array(
                        'prk_kegiatan' => $value,
                        'prk_inti' => $inti,
                        'prk_parent' => $parent,
                        'desc_prk_kegiatan' => $form_rkau_ipendukungep_desc_prk_kegiatan[$key],
                        // 'desc_prk_inti' => $form_rkau_ipendukungep_desc_prk_inti[$key],
                        // 'desc_prk_parent' => $form_rkau_ipendukungep_desc_prk_parent[$key],
                        // 'beban_mat' => (float)$form_rkau_ipendukungep_beban_mat[$key],
                        'beban_mat' => (float)$beban_mat_form_rkau_ipendukungep_ketetapan[$key],
                        'beban_mat_update' => (float)$form_rkau_ipendukungep_beban_mat_update[$key],
                        //'cash_oth' => (float)$form_rkau_ipendukungep_cash_oth[$key],
                        // 'ijin_proses' =>(float)$form_rkau_ipendukungep_ijin_proses[$key],
                        'ijin_proses' =>(float)$ijin_proses_form_rkau_ipendukungep_ketetapan[$key],
                        'ijin_proses_update' =>(float)$form_rkau_ipendukungep_ijin_proses_update[$key],
                        //'disburse' => $disburse,
                        'disburse_sd_bulan' => $disburse_sd_bulan,
                        'disburse_sd_bulan_realisasi' => $get_actuals,
                        'estimate_realisasi' => $get_actuals + $get_commitments,
                        // 'total_year_estimate' => (float)$form_rkau_ipendukungep_total_year_estimates[$key],
                    );
                    array_push($daftar_prk_kegiatan_form_rkau_ipendukungep, $temp);

                    // $daftar_prk_inti_form_rkau_ipendukungep[$inti]['desc_prk_inti'] = $temp['desc_prk_inti'];
                    $daftar_prk_inti_form_rkau_ipendukungep[$inti]['beban_mat'] += $temp['beban_mat'];
                    $daftar_prk_inti_form_rkau_ipendukungep[$inti]['beban_mat_update'] += $temp['beban_mat_update'];
                    //$daftar_prk_inti_form_rkau_ipendukungep[$inti]['cash_oth'] += $temp['cash_oth'];
                    $daftar_prk_inti_form_rkau_ipendukungep[$inti]['ijin_proses'] += $temp['ijin_proses'];
                    $daftar_prk_inti_form_rkau_ipendukungep[$inti]['ijin_proses_update'] += $temp['ijin_proses_update'];
                    // $daftar_prk_inti_form_rkau_ipendukungep[$inti]['total_year_estimate'] += $temp['total_year_estimate'];

                    // $daftar_prk_parent_form_rkau_ipendukungep[$parent]['desc_prk_parent'] = $temp['desc_prk_parent'];
                    $daftar_prk_parent_form_rkau_ipendukungep[$parent]['beban_mat'] += $temp['beban_mat'];
                    $daftar_prk_parent_form_rkau_ipendukungep[$parent]['beban_mat_update'] += $temp['beban_mat_update'];
                    //$daftar_prk_parent_form_rkau_ipendukungep[$parent]['cash_oth'] += $temp['cash_oth'];
                    $daftar_prk_parent_form_rkau_ipendukungep[$parent]['ijin_proses'] += $temp['ijin_proses'];
                    $daftar_prk_parent_form_rkau_ipendukungep[$parent]['ijin_proses_update'] += $temp['ijin_proses_update'];
                    // $daftar_prk_parent_form_rkau_ipendukungep[$parent]['total_year_estimate'] += $temp['total_year_estimate'];
                }
                $dataparent['form_rkau_ipendukungep'] = $daftar_prk_parent_form_rkau_ipendukungep;
                $datainti['form_rkau_ipendukungep'] = $daftar_prk_inti_form_rkau_ipendukungep;
                $datakegiatan['form_rkau_ipendukungep'] = $daftar_prk_kegiatan_form_rkau_ipendukungep;

                // //End of query I-PENDUKUNG EP
            } // kurung tutup if ($input_sb->id == 1) {

        //Start query I-BIAYA USAHA LAINNYA
            //ambil data report dashboard dinamis
            $pgdl_report_dashboard_page_id = 1;
            $jenis_id = 1;
            $pgdl_sheet_name = 'I-BIAYA USAHA LAINNYA';

            $setting_form_rkau_ibiayausahalainnya_prk_parent = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 1)
                            ->first();

            $setting_form_rkau_ibiayausahalainnya_prk_inti = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 2)
                            ->first();

            $setting_form_rkau_ibiayausahalainnya_no_prk_kegiatan = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 3)
                            ->first();

            $setting_form_rkau_ibiayausahalainnya_desc_prk_kegiatan = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 4)
                            ->first();

            $setting_form_rkau_ibiayausahalainnya_beban_mat = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 6)
                            ->first();

            $setting_form_rkau_ibiayausahalainnya_beban_mat_update = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 8)
                            ->first();

            $setting_form_rkau_ibiayausahalainnya_start_kolom = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 9)
                            ->first();

            //end of ambil data report dashboard dinamis

            // $form_rkau_ibiayausahalainnya_prk_parent       = $this->get_form_rkau_parent($input_draft_rkau, $int_input_distrik, 'I-BIAYA USAHA LAINNYA', 'E');
            $form_rkau_ibiayausahalainnya_prk_parent       = $this->get_form_rkau_parent($input_draft_rkau, $int_input_distrik, 'I-BIAYA USAHA LAINNYA', $setting_form_rkau_ibiayausahalainnya_prk_parent->kolom);
            $daftar_prk_parent_form_rkau_ibiayausahalainnya = array();
            foreach ($form_rkau_ibiayausahalainnya_prk_parent as $key => $value) {
                $desc_prk_parent = $this->get_prk_parent_desc(substr($value->value, 2,2));
                $daftar_prk_parent_form_rkau_ibiayausahalainnya[$value->value] = array(
                    'desc_prk_parent' => ($desc_prk_parent!= null ? $desc_prk_parent->desc_prk_parent : ''),
                    'beban_mat'     => 0,
                    'beban_mat_update'     => 0,
                    //'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'ijin_proses_update'   => 0,
                    // 'total_year_estimate'   => 0,
                    //'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    );
            }

            // $form_rkau_ibiayausahalainnya_prk_inti       = $this->get_form_rkau_inti($input_draft_rkau, $int_input_distrik, 'I-BIAYA USAHA LAINNYA', 'E');
            $form_rkau_ibiayausahalainnya_prk_inti       = $this->get_form_rkau_inti($input_draft_rkau, $int_input_distrik, 'I-BIAYA USAHA LAINNYA', $setting_form_rkau_ibiayausahalainnya_prk_inti->kolom);
            $daftar_prk_inti_form_rkau_ibiayausahalainnya = array();
            foreach ($form_rkau_ibiayausahalainnya_prk_inti as $key => $value) {
                $desc_prk_inti = $this->get_prk_inti_desc(substr($value->value, 2,2),substr($value->value, 4,2));
                $daftar_prk_inti_form_rkau_ibiayausahalainnya[$value->value] = array(
                    'desc_prk_inti' => ($desc_prk_inti != null ? $desc_prk_inti->desc_prk_inti : ''),
                    'prk_parent'    => substr($value->value, 0,4),
                    'beban_mat'     => 0,
                    'beban_mat_update'     => 0,
                    //'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'ijin_proses_update'   => 0,
                    // 'total_year_estimate'   => 0,
                    //'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    );
            }

            $daftar_prk_kegiatan_form_rkau_ibiayausahalainnya = array();
            // $form_rkau_ibiayausahalainnya_prk_kegiatan       = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-BIAYA USAHA LAINNYA', 'E');
            // $form_rkau_ibiayausahalainnya_desc_prk_kegiatan   = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-BIAYA USAHA LAINNYA', 'F');
            // $form_rkau_ibiayausahalainnya_beban_mat           = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-BIAYA USAHA LAINNYA', 'H');
            $form_rkau_ibiayausahalainnya_prk_kegiatan       = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-BIAYA USAHA LAINNYA', $setting_form_rkau_ibiayausahalainnya_no_prk_kegiatan->kolom);
            $form_rkau_ibiayausahalainnya_desc_prk_kegiatan   = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-BIAYA USAHA LAINNYA', $setting_form_rkau_ibiayausahalainnya_desc_prk_kegiatan->kolom);
            $form_rkau_ibiayausahalainnya_beban_mat           = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-BIAYA USAHA LAINNYA', $setting_form_rkau_ibiayausahalainnya_beban_mat->kolom);
            //$form_rkau_ibiayausahalainnya_cash_oth_1          = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-BIAYA USAHA LAINNYA', 'J');
            //$form_rkau_ibiayausahalainnya_cash_oth_2          = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-BIAYA USAHA LAINNYA', 'K');
            // $form_rkau_ibiayausahalainnya_total_year_estimates= $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-BIAYA USAHA LAINNYA', 'H');

            // $form_rkau_ibiayausahalainnya_beban_mat_update           = $this->get_form_rkau_update($input_draft_rkau, $int_input_distrik, 'I-BIAYA USAHA LAINNYA', 'H');
            $form_rkau_ibiayausahalainnya_beban_mat_update           = $this->get_form_rkau_update($input_draft_rkau, $int_input_distrik, 'I-BIAYA USAHA LAINNYA', $setting_form_rkau_ibiayausahalainnya_beban_mat_update->kolom);

            $form_rkau_ibiayausahalainnya_disburse_update = array();

            // $start_kolom = 'M';
            $start_kolom = $setting_form_rkau_ibiayausahalainnya_start_kolom->kolom;
            for($bulan = 1; $bulan<=$int_input_bulan; $bulan++){
                $form_rkau_ibiayausahalainnya_disburse_update[$bulan] = $this->get_form_rkau_disburse_update($input_draft_rkau, $int_input_distrik, 'I-BIAYA USAHA LAINNYA', $start_kolom);
                $start_kolom++;
            }
/*
            $form_rkau_ibiayausahalainnya_disburse[1] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-BIAYA USAHA LAINNYA', 'M');
            $form_rkau_ibiayausahalainnya_disburse[2] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-BIAYA USAHA LAINNYA', 'N');
            $form_rkau_ibiayausahalainnya_disburse[3] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-BIAYA USAHA LAINNYA', 'O');
            $form_rkau_ibiayausahalainnya_disburse[4] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-BIAYA USAHA LAINNYA', 'P');
            $form_rkau_ibiayausahalainnya_disburse[5] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-BIAYA USAHA LAINNYA', 'Q');
            $form_rkau_ibiayausahalainnya_disburse[6] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-BIAYA USAHA LAINNYA', 'R');
            $form_rkau_ibiayausahalainnya_disburse[7] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-BIAYA USAHA LAINNYA', 'S');
            $form_rkau_ibiayausahalainnya_disburse[8] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-BIAYA USAHA LAINNYA', 'T');
            $form_rkau_ibiayausahalainnya_disburse[9] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-BIAYA USAHA LAINNYA', 'U');
            $form_rkau_ibiayausahalainnya_disburse[10] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-BIAYA USAHA LAINNYA', 'V');
            $form_rkau_ibiayausahalainnya_disburse[11] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-BIAYA USAHA LAINNYA', 'W');
            $form_rkau_ibiayausahalainnya_disburse[12] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-BIAYA USAHA LAINNYA', 'X');
*/
            foreach ($form_rkau_ibiayausahalainnya_prk_kegiatan as $key => $value) {
                $parent = substr($value,0,4);
                $inti = substr($value,0,6);

                $disburse = array();
                $bln = array();
                $disburse_sd_bulan = 0;
                //for($bulan=1; $bulan<=12; $bulan++){
                for($bulan=1; $bulan<=$int_input_bulan; $bulan++){
                    //$disburse[$bulan] = (float)$form_rkau_ibiayausahalainnya_disburse[$bulan][$key];
                    $bln[$bulan] = $bulan;
                    $disburse_sd_bulan += (float)$form_rkau_ibiayausahalainnya_disburse_update[$bulan][$key];
                    // $daftar_prk_inti_form_rkau_ibiayausahalainnya[$inti]['disburse'][$bulan] += $disburse[$bulan];
                    // $daftar_prk_parent_form_rkau_ibiayausahalainnya[$parent]['disburse'][$bulan] += $disburse[$bulan];
                }
                $bln = implode(",",$bln);
                $bln = "(".$bln.")";

                // $get_disburse_sd_bulan_realisasi = $this->get_disburse_sd_bulan_realisasi_rkau($input_distrik->code1, $bln, $value);

                // if($get_disburse_sd_bulan_realisasi) {
                //     $disburse_sd_bulan_realisasi = (float)$get_disburse_sd_bulan_realisasi[0]->sum;
                // }
                // else {
                //     $disburse_sd_bulan_realisasi = 0;
                // }

                $get_actuals = (float)$this->get_actuals($input_distrik->code1, $value);
                $get_commitments = (float)$this->get_commitments($input_distrik->code1, $value);

                //cek ada No PRK kegiatan
                // $cek_no_prk_ketetapan = $this->cek_no_prk_form_rkau_ketetapan($input_draft_rkau,$int_input_distrik, 'E', $value, 'I-BIAYA USAHA LAINNYA');
                $cek_no_prk_ketetapan = $this->cek_no_prk_form_rkau_ketetapan($input_draft_rkau,$int_input_distrik, $setting_form_rkau_ibiayausahalainnya_no_prk_kegiatan->kolom, $value, 'I-BIAYA USAHA LAINNYA');
                if($cek_no_prk_ketetapan) {
                    // $beban_mat_form_rkau_ibiayausahalainnya_ketetapan[$key] = $this->get_form_rkau_ketetapan($input_draft_rkau,$int_input_distrik, 'H', 'E', $value, 'I-BIAYA USAHA LAINNYA')[0]->value;
                    $beban_mat_form_rkau_ibiayausahalainnya_ketetapan[$key] = $this->get_form_rkau_ketetapan($input_draft_rkau,$int_input_distrik, $setting_form_rkau_ibiayausahalainnya_beban_mat->kolom, $setting_form_rkau_ibiayausahalainnya_no_prk_kegiatan->kolom, $value, 'I-BIAYA USAHA LAINNYA')[0]->value;
                }
                else {
                    $beban_mat_form_rkau_ibiayausahalainnya_ketetapan[$key] = 0;
                }

                $temp = array(
                    'prk_kegiatan' => $value,
                    'prk_inti' => $inti,
                    'prk_parent' => $parent,
                    'desc_prk_kegiatan' => $form_rkau_ibiayausahalainnya_desc_prk_kegiatan[$key],
                    // 'desc_prk_inti' => $form_rkau_ibiayausahalainnya_desc_prk_inti[$key],
                    // 'desc_prk_parent' => $form_rkau_ibiayausahalainnya_desc_prk_parent[$key],
                    // 'beban_mat' => (float)$form_rkau_ibiayausahalainnya_beban_mat[$key],
                    'beban_mat' => (float)$beban_mat_form_rkau_ibiayausahalainnya_ketetapan[$key],
                    'beban_mat_update' => (float)$form_rkau_ibiayausahalainnya_beban_mat_update[$key],
                    //'cash_oth' => ((float)$form_rkau_ibiayausahalainnya_cash_oth_1[$key]) + ((float)$form_rkau_ibiayausahalainnya_cash_oth_2[$key]),
                    'ijin_proses' => 0,
                    'ijin_proses_update' => 0,
                    'disburse_sd_bulan' => $disburse_sd_bulan,
                    'disburse_sd_bulan_realisasi' => $get_actuals,
                    'estimate_realisasi' => $get_actuals + $get_commitments,
                    //'disburse' => $disburse,
                    // 'total_year_estimate' => (float)$form_rkau_ibiayausahalainnya_total_year_estimates[$key],
                );
                array_push($daftar_prk_kegiatan_form_rkau_ibiayausahalainnya, $temp);

                // $daftar_prk_inti_form_rkau_ibiayausahalainnya[$inti]['desc_prk_inti'] = $temp['desc_prk_inti'];
                $daftar_prk_inti_form_rkau_ibiayausahalainnya[$inti]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_inti_form_rkau_ibiayausahalainnya[$inti]['beban_mat_update'] += $temp['beban_mat_update'];
                //$daftar_prk_inti_form_rkau_ibiayausahalainnya[$inti]['cash_oth'] += $temp['cash_oth'];
                $daftar_prk_inti_form_rkau_ibiayausahalainnya[$inti]['ijin_proses'] += $temp['ijin_proses'];
                $daftar_prk_inti_form_rkau_ibiayausahalainnya[$inti]['ijin_proses_update'] += $temp['ijin_proses_update'];
                // $daftar_prk_inti_form_rkau_ibiayausahalainnya[$inti]['total_year_estimate'] += $temp['total_year_estimate'];

                // $daftar_prk_parent_form_rkau_ibiayausahalainnya[$parent]['desc_prk_parent'] = $temp['desc_prk_parent'];
                $daftar_prk_parent_form_rkau_ibiayausahalainnya[$parent]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_parent_form_rkau_ibiayausahalainnya[$parent]['beban_mat_update'] += $temp['beban_mat_update'];
                //$daftar_prk_parent_form_rkau_ibiayausahalainnya[$parent]['cash_oth'] += $temp['cash_oth'];
                $daftar_prk_parent_form_rkau_ibiayausahalainnya[$parent]['ijin_proses'] += $temp['ijin_proses'];
                $daftar_prk_parent_form_rkau_ibiayausahalainnya[$parent]['ijin_proses_update'] += $temp['ijin_proses_update'];
                // $daftar_prk_parent_form_rkau_ibiayausahalainnya[$parent]['total_year_estimate'] += $temp['total_year_estimate'];
            }
            $dataparent['form_rkau_ibiayausahalainnya'] = $daftar_prk_parent_form_rkau_ibiayausahalainnya;
            $datainti['form_rkau_ibiayausahalainnya'] = $daftar_prk_inti_form_rkau_ibiayausahalainnya;
            $datakegiatan['form_rkau_ibiayausahalainnya'] = $daftar_prk_kegiatan_form_rkau_ibiayausahalainnya;

        // //End of query I-BIAYA USAHA LAINNYA

        //Start query I-DILUAR USAHA
            //ambil data report dashboard dinamis
            $pgdl_report_dashboard_page_id = 1;
            $jenis_id = 1;
            $pgdl_sheet_name = 'I-DILUAR USAHA';

            $setting_form_rkau_idiluarusaha_prk_parent = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 1)
                            ->first();

            $setting_form_rkau_idiluarusaha_prk_inti = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 2)
                            ->first();

            $setting_form_rkau_idiluarusaha_no_prk_kegiatan = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 3)
                            ->first();

            $setting_form_rkau_idiluarusaha_desc_prk_kegiatan = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 4)
                            ->first();

            $setting_form_rkau_idiluarusaha_beban_mat = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 6)
                            ->first();

            $setting_form_rkau_idiluarusaha_beban_mat_update = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 8)
                            ->first();

            $setting_form_rkau_idiluarusaha_start_kolom = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 9)
                            ->first();

            //end of ambil data report dashboard dinamis

            // $form_rkau_idiluarusaha_prk_parent       = $this->get_form_rkau_parent($input_draft_rkau, $int_input_distrik, 'I-DILUAR USAHA', 'E');
            $form_rkau_idiluarusaha_prk_parent       = $this->get_form_rkau_parent($input_draft_rkau, $int_input_distrik, 'I-DILUAR USAHA', $setting_form_rkau_idiluarusaha_prk_parent->kolom);
            $daftar_prk_parent_form_rkau_idiluarusaha = array();
            foreach ($form_rkau_idiluarusaha_prk_parent as $key => $value) {
                $desc_prk_parent = $this->get_prk_parent_desc(substr($value->value, 2,2));
                $daftar_prk_parent_form_rkau_idiluarusaha[$value->value] = array(
                    'desc_prk_parent' => ($desc_prk_parent!= null ? $desc_prk_parent->desc_prk_parent : ''),
                    'beban_mat'     => 0,
                    'beban_mat_update'     => 0,
                    //'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'ijin_proses_update'   => 0,
                    // 'total_year_estimate'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    );
            }

            // $form_rkau_idiluarusaha_prk_inti       = $this->get_form_rkau_inti($input_draft_rkau, $int_input_distrik, 'I-DILUAR USAHA', 'E');
            $form_rkau_idiluarusaha_prk_inti       = $this->get_form_rkau_inti($input_draft_rkau, $int_input_distrik, 'I-DILUAR USAHA', $setting_form_rkau_idiluarusaha_prk_inti->kolom);
            $daftar_prk_inti_form_rkau_idiluarusaha = array();
            foreach ($form_rkau_idiluarusaha_prk_inti as $key => $value) {
                $desc_prk_inti = $this->get_prk_inti_desc(substr($value->value, 2,2),substr($value->value, 4,2));
                $daftar_prk_inti_form_rkau_idiluarusaha[$value->value] = array(
                    'desc_prk_inti' => ($desc_prk_inti != null ? $desc_prk_inti->desc_prk_inti : ''),
                    'prk_parent'    => substr($value->value, 0,4),
                    'beban_mat'     => 0,
                    'beban_mat_update'     => 0,
                    //'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'ijin_proses_update'   => 0,
                    // 'total_year_estimate'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    );
            }

            $daftar_prk_kegiatan_form_rkau_idiluarusaha = array();
            // $form_rkau_idiluarusaha_prk_kegiatan       = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-DILUAR USAHA', 'E');
            // $form_rkau_idiluarusaha_desc_prk_kegiatan   = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-DILUAR USAHA', 'F');
            // $form_rkau_idiluarusaha_beban_mat           = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-DILUAR USAHA', 'H');
            $form_rkau_idiluarusaha_prk_kegiatan       = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-DILUAR USAHA', $setting_form_rkau_idiluarusaha_no_prk_kegiatan->kolom);
            $form_rkau_idiluarusaha_desc_prk_kegiatan   = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-DILUAR USAHA', $setting_form_rkau_idiluarusaha_desc_prk_kegiatan->kolom);
            $form_rkau_idiluarusaha_beban_mat           = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-DILUAR USAHA', $setting_form_rkau_idiluarusaha_beban_mat->kolom);
            //$form_rkau_idiluarusaha_cash_oth_1          = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-DILUAR USAHA', 'J');
            //$form_rkau_idiluarusaha_cash_oth_2          = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-DILUAR USAHA', 'K');
            // $form_rkau_idiluarusaha_total_year_estimates= $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-DILUAR USAHA', 'H');

            // $form_rkau_idiluarusaha_beban_mat_update    = $this->get_form_rkau_update($input_draft_rkau, $int_input_distrik, 'I-DILUAR USAHA', 'H');

            $form_rkau_idiluarusaha_beban_mat_update    = $this->get_form_rkau_update($input_draft_rkau, $int_input_distrik, 'I-DILUAR USAHA', $setting_form_rkau_idiluarusaha_beban_mat_update->kolom);

            $form_rkau_idiluarusaha_disburse_update = array();

            // $start_kolom = 'M';
            $start_kolom = $setting_form_rkau_idiluarusaha_start_kolom->kolom;
            for($bulan = 1; $bulan<=$int_input_bulan; $bulan++){
                $form_rkau_idiluarusaha_disburse_update[$bulan] = $this->get_form_rkau_disburse_update($input_draft_rkau, $int_input_distrik, 'I-DILUAR USAHA', $start_kolom);
                $start_kolom++;
            }
            //dd($form_rkau_idiluarusaha_prk_kegiatan[1][0]);

/*
            $form_rkau_idiluarusaha_disburse[1] = $this->get_form_rkau_disburse($input_draft_rkau, $int_input_distrik, 'I-DILUAR USAHA', 'M');
            $form_rkau_idiluarusaha_disburse[2] = $this->get_form_rkau_disburse($input_draft_rkau, $int_input_distrik, 'I-DILUAR USAHA', 'N');
            $form_rkau_idiluarusaha_disburse[3] = $this->get_form_rkau_disburse($input_draft_rkau, $int_input_distrik, 'I-DILUAR USAHA', 'O');
            $form_rkau_idiluarusaha_disburse[4] = $this->get_form_rkau_disburse($input_draft_rkau, $int_input_distrik, 'I-DILUAR USAHA', 'P');
            $form_rkau_idiluarusaha_disburse[5] = $this->get_form_rkau_disburse($input_draft_rkau, $int_input_distrik, 'I-DILUAR USAHA', 'Q');
            $form_rkau_idiluarusaha_disburse[6] = $this->get_form_rkau_disburse($input_draft_rkau, $int_input_distrik, 'I-DILUAR USAHA', 'R');
            $form_rkau_idiluarusaha_disburse[7] = $this->get_form_rkau_disburse($input_draft_rkau, $int_input_distrik, 'I-DILUAR USAHA', 'S');
            $form_rkau_idiluarusaha_disburse[8] = $this->get_form_rkau_disburse($input_draft_rkau, $int_input_distrik, 'I-DILUAR USAHA', 'T');
            $form_rkau_idiluarusaha_disburse[9] = $this->get_form_rkau_disburse($input_draft_rkau, $int_input_distrik, 'I-DILUAR USAHA', 'U');
            $form_rkau_idiluarusaha_disburse[10] = $this->get_form_rkau_disburse($input_draft_rkau, $int_input_distrik, 'I-DILUAR USAHA', 'V');
            $form_rkau_idiluarusaha_disburse[11] = $this->get_form_rkau_disburse($input_draft_rkau, $int_input_distrik, 'I-DILUAR USAHA', 'W');
            $form_rkau_idiluarusaha_disburse[12] = $this->get_form_rkau_disburse($input_draft_rkau, $int_input_distrik, 'I-DILUAR USAHA', 'X');
*/
            foreach ($form_rkau_idiluarusaha_prk_kegiatan as $key => $value) {
                $parent = substr($value,0,4);
                $inti = substr($value,0,6);
                //dd($bulan."--".$key);
                //dd($form_rkau_idiluarusaha_disburse[6][13]);
                $disburse = array();
                $bln = array();
                $disburse_sd_bulan = 0;
                //for($bulan=1; $bulan<=12; $bulan++){
                for($bulan=1; $bulan<=$int_input_bulan; $bulan++){
                    //$disburse[$bulan] = (float)$form_rkau_idiluarusaha_disburse[$bulan][$key];
                    $bln[$bulan] = $bulan;
                    $disburse_sd_bulan += (float)$form_rkau_idiluarusaha_disburse_update[$bulan][$key];
                    // $daftar_prk_inti_form_rkau_idiluarusaha[$inti]['disburse'][$bulan] += $disburse[$bulan];
                    // $daftar_prk_parent_form_rkau_idiluarusaha[$parent]['disburse'][$bulan] += $disburse[$bulan];
                }

                $bln = implode(",",$bln);
                $bln = "(".$bln.")";

                // $get_disburse_sd_bulan_realisasi = $this->get_disburse_sd_bulan_realisasi_rkau($input_distrik->code1, $bln, $value);

                // if($get_disburse_sd_bulan_realisasi) {
                //     $disburse_sd_bulan_realisasi = (float)$get_disburse_sd_bulan_realisasi[0]->sum;
                // }
                // else {
                //     $disburse_sd_bulan_realisasi = 0;
                // }

                $get_actuals = (float)$this->get_actuals($input_distrik->code1, $value);
                $get_commitments = (float)$this->get_commitments($input_distrik->code1, $value);

                //cek ada No PRK kegiatan
                // $cek_no_prk_ketetapan = $this->cek_no_prk_form_rkau_ketetapan($input_draft_rkau,$int_input_distrik, 'E', $value, 'I-DILUAR USAHA');
                $cek_no_prk_ketetapan = $this->cek_no_prk_form_rkau_ketetapan($input_draft_rkau,$int_input_distrik, $setting_form_rkau_idiluarusaha_no_prk_kegiatan->kolom, $value, 'I-DILUAR USAHA');
                if($cek_no_prk_ketetapan) {
                    // $beban_mat_form_rkau_idiluarusaha_ketetapan[$key] = $this->get_form_rkau_ketetapan($input_draft_rkau,$int_input_distrik, 'H', 'E', $value, 'I-DILUAR USAHA')[0]->value;
                    $beban_mat_form_rkau_idiluarusaha_ketetapan[$key] = $this->get_form_rkau_ketetapan($input_draft_rkau,$int_input_distrik, $setting_form_rkau_idiluarusaha_beban_mat->kolom, $setting_form_rkau_idiluarusaha_no_prk_kegiatan->kolom, $value, 'I-DILUAR USAHA')[0]->value;
                }
                else {
                    $beban_mat_form_rkau_idiluarusaha_ketetapan[$key] = 0;
                }

                $temp = array(
                    'prk_kegiatan' => $value,
                    'prk_inti' => $inti,
                    'prk_parent' => $parent,
                    'desc_prk_kegiatan' => $form_rkau_idiluarusaha_desc_prk_kegiatan[$key],
                    // 'desc_prk_inti' => $form_rkau_idiluarusaha_desc_prk_inti[$key],
                    // 'desc_prk_parent' => $form_rkau_idiluarusaha_desc_prk_parent[$key],
                    // 'beban_mat' => (float)$form_rkau_idiluarusaha_beban_mat[$key],
                    'beban_mat' => (float)$beban_mat_form_rkau_idiluarusaha_ketetapan[$key],
                    'beban_mat_update' => (float)$form_rkau_idiluarusaha_beban_mat_update[$key],
                    //'cash_oth' => ((float)$form_rkau_idiluarusaha_cash_oth_1[$key]) + ((float)$form_rkau_idiluarusaha_cash_oth_2[$key]),
                    'ijin_proses' => 0,
                    'ijin_proses_update' => 0,
                    'disburse_sd_bulan' => $disburse_sd_bulan,
                    'disburse_sd_bulan_realisasi' => $get_actuals,
                    'estimate_realisasi' => $get_actuals + $get_commitments,
                    //'disburse' => $disburse,
                    // 'total_year_estimate' => (float)$form_rkau_idiluarusaha_total_year_estimates[$key],
                );
                array_push($daftar_prk_kegiatan_form_rkau_idiluarusaha, $temp);

                // $daftar_prk_inti_form_rkau_idiluarusaha[$inti]['desc_prk_inti'] = $temp['desc_prk_inti'];
                $daftar_prk_inti_form_rkau_idiluarusaha[$inti]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_inti_form_rkau_idiluarusaha[$inti]['beban_mat_update'] += $temp['beban_mat_update'];
                //$daftar_prk_inti_form_rkau_idiluarusaha[$inti]['cash_oth'] += $temp['cash_oth'];
                $daftar_prk_inti_form_rkau_idiluarusaha[$inti]['ijin_proses'] += $temp['ijin_proses'];
                $daftar_prk_inti_form_rkau_idiluarusaha[$inti]['ijin_proses_update'] += $temp['ijin_proses_update'];
                // $daftar_prk_inti_form_rkau_idiluarusaha[$inti]['total_year_estimate'] += $temp['total_year_estimate'];

                // $daftar_prk_parent_form_rkau_idiluarusaha[$parent]['desc_prk_parent'] = $temp['desc_prk_parent'];
                $daftar_prk_parent_form_rkau_idiluarusaha[$parent]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_parent_form_rkau_idiluarusaha[$parent]['beban_mat_update'] += $temp['beban_mat_update'];
                //$daftar_prk_parent_form_rkau_idiluarusaha[$parent]['cash_oth'] += $temp['cash_oth'];
                $daftar_prk_parent_form_rkau_idiluarusaha[$parent]['ijin_proses'] += $temp['ijin_proses'];
                $daftar_prk_parent_form_rkau_idiluarusaha[$parent]['ijin_proses_update'] += $temp['ijin_proses_update'];
                // $daftar_prk_parent_form_rkau_idiluarusaha[$parent]['total_year_estimate'] += $temp['total_year_estimate'];
            }
            $dataparent['form_rkau_idiluarusaha'] = $daftar_prk_parent_form_rkau_idiluarusaha;
            $datainti['form_rkau_idiluarusaha'] = $daftar_prk_inti_form_rkau_idiluarusaha;
            $datakegiatan['form_rkau_idiluarusaha'] = $daftar_prk_kegiatan_form_rkau_idiluarusaha;

        // //End of query I-DILUAR USAHA

        //Start query I-Pendapatan
            //ambil data report dashboard dinamis
            $pgdl_report_dashboard_page_id = 1;
            $jenis_id = 1;
            $pgdl_sheet_name = 'I-Pendapatan';

            $setting_form_rkau_ipendapatan_prk_parent = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 1)
                            ->first();

            $setting_form_rkau_ipendapatan_prk_inti = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 2)
                            ->first();

            $setting_form_rkau_ipendapatan_no_prk_kegiatan = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 3)
                            ->first();

            $setting_form_rkau_ipendapatan_desc_prk_kegiatan = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 4)
                            ->first();

            $setting_form_rkau_ipendapatan_beban_mat = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 6)
                            ->first();

            $setting_form_rkau_ipendapatan_beban_mat_update = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 8)
                            ->first();

            $setting_form_rkau_ipendapatan_start_kolom = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 9)
                            ->first();

            //end of ambil data report dashboard dinamis

            // $form_rkau_ipendapatan_prk_parent       = $this->get_form_rkau_parent($input_draft_rkau, $int_input_distrik, 'I-Pendapatan', 'E');
            $form_rkau_ipendapatan_prk_parent       = $this->get_form_rkau_parent($input_draft_rkau, $int_input_distrik, 'I-Pendapatan', $setting_form_rkau_ipendapatan_prk_parent->kolom);
            $daftar_prk_parent_form_rkau_ipendapatan = array();
            foreach ($form_rkau_ipendapatan_prk_parent as $key => $value) {
                $desc_prk_parent = $this->get_prk_parent_desc(substr($value->value, 2,2));
                $daftar_prk_parent_form_rkau_ipendapatan[$value->value] = array(
                    'desc_prk_parent' => ($desc_prk_parent!= null ? $desc_prk_parent->desc_prk_parent : ''),
                    'beban_mat'     => 0,
                    'beban_mat_update'     => 0,
                    //'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'ijin_proses_update'   => 0,
                    // 'total_year_estimate'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    );
            }

            // $form_rkau_ipendapatan_prk_inti       = $this->get_form_rkau_inti($input_draft_rkau, $int_input_distrik, 'I-Pendapatan', 'E');
            $form_rkau_ipendapatan_prk_inti       = $this->get_form_rkau_inti($input_draft_rkau, $int_input_distrik, 'I-Pendapatan', $setting_form_rkau_ipendapatan_prk_inti->kolom);
            $daftar_prk_inti_form_rkau_ipendapatan = array();
            foreach ($form_rkau_ipendapatan_prk_inti as $key => $value) {
                $desc_prk_inti = $this->get_prk_inti_desc(substr($value->value, 2,2),substr($value->value, 4,2));
                $daftar_prk_inti_form_rkau_ipendapatan[$value->value] = array(
                    'desc_prk_inti' => ($desc_prk_inti != null ? $desc_prk_inti->desc_prk_inti : ''),
                    'prk_parent'    => substr($value->value, 0,4),
                    'beban_mat'     => 0,
                    'beban_mat_update'     => 0,
                    //'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'ijin_proses_update'   => 0,
                    // 'total_year_estimate'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    );
            }

            $daftar_prk_kegiatan_form_rkau_ipendapatan = array();
            // $form_rkau_ipendapatan_prk_kegiatan       = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-Pendapatan', 'E');
            // $form_rkau_ipendapatan_desc_prk_kegiatan   = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-Pendapatan', 'F');
            // $form_rkau_ipendapatan_beban_mat           = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-Pendapatan', 'H');
            $form_rkau_ipendapatan_prk_kegiatan       = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-Pendapatan', $setting_form_rkau_ipendapatan_no_prk_kegiatan->kolom);
            $form_rkau_ipendapatan_desc_prk_kegiatan   = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-Pendapatan', $setting_form_rkau_ipendapatan_desc_prk_kegiatan->kolom);
            $form_rkau_ipendapatan_beban_mat           = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-Pendapatan', $setting_form_rkau_ipendapatan_beban_mat->kolom);
            //$form_rkau_ipendapatan_cash_oth_1          = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-Pendapatan', 'J');
            //$form_rkau_ipendapatan_cash_oth_2          = $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-Pendapatan', 'K');
            // $form_rkau_ipendapatan_total_year_estimates= $this->get_form_rkau($input_draft_rkau, $int_input_distrik, 'I-Pendapatan', 'H');

            // $form_rkau_ipendapatan_beban_mat_update    = $this->get_form_rkau_update($input_draft_rkau, $int_input_distrik, 'I-Pendapatan', 'H');

            $form_rkau_ipendapatan_beban_mat_update    = $this->get_form_rkau_update($input_draft_rkau, $int_input_distrik, 'I-Pendapatan', $setting_form_rkau_ipendapatan_beban_mat_update->kolom);

            $form_rkau_ipendapatan_disburse_update = array();

            // $start_kolom = 'M';
            $start_kolom = $setting_form_rkau_ipendapatan_start_kolom->kolom;
            for($bulan = 1; $bulan<=$int_input_bulan; $bulan++){
                $form_rkau_ipendapatan_disburse_update[$bulan] = $this->get_form_rkau_disburse_update($input_draft_rkau, $int_input_distrik, 'I-Pendapatan', $start_kolom);
                $start_kolom++;
            }

/*
            $form_rkau_ipendapatan_disburse[1] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-Pendapatan', 'M');
            $form_rkau_ipendapatan_disburse[2] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-Pendapatan', 'N');
            $form_rkau_ipendapatan_disburse[3] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-Pendapatan', 'O');
            $form_rkau_ipendapatan_disburse[4] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-Pendapatan', 'P');
            $form_rkau_ipendapatan_disburse[5] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-Pendapatan', 'Q');
            $form_rkau_ipendapatan_disburse[6] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-Pendapatan', 'R');
            $form_rkau_ipendapatan_disburse[7] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-Pendapatan', 'S');
            $form_rkau_ipendapatan_disburse[8] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-Pendapatan', 'T');
            $form_rkau_ipendapatan_disburse[9] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-Pendapatan', 'U');
            $form_rkau_ipendapatan_disburse[10] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-Pendapatan', 'V');
            $form_rkau_ipendapatan_disburse[11] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-Pendapatan', 'W');
            $form_rkau_ipendapatan_disburse[12] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_distrik, 'I-Pendapatan', 'X');
*/
            foreach ($form_rkau_ipendapatan_prk_kegiatan as $key => $value) {
                $parent = substr($value,0,4);
                $inti = substr($value,0,6);

                $disburse = array();
                $bln = array();
                $disburse_sd_bulan = 0;
                //for($bulan=1; $bulan<=12; $bulan++){
                for($bulan=1; $bulan<=$int_input_bulan; $bulan++){
                    //$disburse[$bulan] = (float)$form_rkau_ipendapatan_disburse[$bulan][$key];
                    $bln[$bulan] = $bulan;
                    $disburse_sd_bulan += (float)$form_rkau_ipendapatan_disburse_update[$bulan][$key];
                    // $daftar_prk_inti_form_rkau_ipendapatan[$inti]['disburse'][$bulan] += $disburse[$bulan];
                    // $daftar_prk_parent_form_rkau_ipendapatan[$parent]['disburse'][$bulan] += $disburse[$bulan];
                }

                $bln = implode(",",$bln);
                $bln = "(".$bln.")";

                // $get_disburse_sd_bulan_realisasi = $this->get_disburse_sd_bulan_realisasi_rkau($input_distrik->code1, $bln, $value);

                // if($get_disburse_sd_bulan_realisasi) {
                //     $disburse_sd_bulan_realisasi = (float)$get_disburse_sd_bulan_realisasi[0]->sum;
                // }
                // else {
                //     $disburse_sd_bulan_realisasi = 0;
                // }

                $get_actuals = (float)$this->get_actuals($input_distrik->code1, $value);
                $get_commitments = (float)$this->get_commitments($input_distrik->code1, $value);

                //cek ada No PRK kegiatan
                // $cek_no_prk_ketetapan = $this->cek_no_prk_form_rkau_ketetapan($input_draft_rkau,$int_input_distrik, 'E', $value, 'I-Pendapatan');
                $cek_no_prk_ketetapan = $this->cek_no_prk_form_rkau_ketetapan($input_draft_rkau,$int_input_distrik, $setting_form_rkau_ipendapatan_no_prk_kegiatan->kolom, $value, 'I-Pendapatan');
                if($cek_no_prk_ketetapan) {
                    // $beban_mat_form_rkau_ipendapatan_ketetapan[$key] = $this->get_form_rkau_ketetapan($input_draft_rkau,$int_input_distrik, 'H', 'E', $value, 'I-Pendapatan')[0]->value;
                    $beban_mat_form_rkau_ipendapatan_ketetapan[$key] = $this->get_form_rkau_ketetapan($input_draft_rkau,$int_input_distrik, $setting_form_rkau_ipendapatan_beban_mat->kolom, $setting_form_rkau_ipendapatan_no_prk_kegiatan->kolom, $value, 'I-Pendapatan')[0]->value;
                }
                else {
                    $beban_mat_form_rkau_ipendapatan_ketetapan[$key] = 0;
                }

                $temp = array(
                    'prk_kegiatan' => $value,
                    'prk_inti' => $inti,
                    'prk_parent' => $parent,
                    'desc_prk_kegiatan' => $form_rkau_ipendapatan_desc_prk_kegiatan[$key],
                    // 'desc_prk_inti' => $form_rkau_ipendapatan_desc_prk_inti[$key],
                    // 'desc_prk_parent' => $form_rkau_ipendapatan_desc_prk_parent[$key],
                    // 'beban_mat' => (float)$form_rkau_ipendapatan_beban_mat[$key],
                    'beban_mat' => (float)$beban_mat_form_rkau_ipendapatan_ketetapan[$key],
                    'beban_mat_update' => (float)$form_rkau_ipendapatan_beban_mat_update[$key],
                    //'cash_oth' => ((float)$form_rkau_ipendapatan_cash_oth_1[$key]) + ((float)$form_rkau_ipendapatan_cash_oth_2[$key]),
                    'ijin_proses' => 0,
                    'ijin_proses_update' => 0,
                    //'disburse' => $disburse,
                    'disburse_sd_bulan' => $disburse_sd_bulan,
                    'disburse_sd_bulan_realisasi' => $get_actuals,
                    'estimate_realisasi' => $get_actuals + $get_commitments,
                    // 'total_year_estimate' => (float)$form_rkau_ipendapatan_total_year_estimates[$key],
                );
                array_push($daftar_prk_kegiatan_form_rkau_ipendapatan, $temp);

                // $daftar_prk_inti_form_rkau_ipendapatan[$inti]['desc_prk_inti'] = $temp['desc_prk_inti'];
                $daftar_prk_inti_form_rkau_ipendapatan[$inti]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_inti_form_rkau_ipendapatan[$inti]['beban_mat_update'] += $temp['beban_mat_update'];
                //$daftar_prk_inti_form_rkau_ipendapatan[$inti]['cash_oth'] += $temp['cash_oth'];
                $daftar_prk_inti_form_rkau_ipendapatan[$inti]['ijin_proses'] += $temp['ijin_proses'];
                $daftar_prk_inti_form_rkau_ipendapatan[$inti]['ijin_proses_update'] += $temp['ijin_proses_update'];
                // $daftar_prk_inti_form_rkau_ipendapatan[$inti]['total_year_estimate'] += $temp['total_year_estimate'];

                // $daftar_prk_parent_form_rkau_ipendapatan[$parent]['desc_prk_parent'] = $temp['desc_prk_parent'];
                $daftar_prk_parent_form_rkau_ipendapatan[$parent]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_parent_form_rkau_ipendapatan[$parent]['beban_mat_update'] += $temp['beban_mat_update'];
              //  $daftar_prk_parent_form_rkau_ipendapatan[$parent]['cash_oth'] += $temp['cash_oth'];
                $daftar_prk_parent_form_rkau_ipendapatan[$parent]['ijin_proses'] += $temp['ijin_proses'];
                $daftar_prk_parent_form_rkau_ipendapatan[$parent]['ijin_proses_update'] += $temp['ijin_proses_update'];
                // $daftar_prk_parent_form_rkau_ipendapatan[$parent]['total_year_estimate'] += $temp['total_year_estimate'];
            }
            $dataparent['form_rkau_ipendapatan'] = $daftar_prk_parent_form_rkau_ipendapatan;
            $datainti['form_rkau_ipendapatan'] = $daftar_prk_inti_form_rkau_ipendapatan;
            $datakegiatan['form_rkau_ipendapatan'] = $daftar_prk_kegiatan_form_rkau_ipendapatan;

        } //end of cek request draft rkau
        // //End of query I-Pendapatan

        //Start Form 6 Reimbuse
        if($input_draft_form_6_reimburse){
            // $count_6_reimburse = DB::select("select count(e.row)
            //                       from excel_datas e
            //                       join sheets s on s.id = e.sheet_id
            //                       join lokasi l on l.id = e.lokasi_id
            //                       where s.name like 'I-Form 6'
            //                       and e.file_import_id IN ".$input_draft_form_6_reimburse."
            //                       and l.distrik_id = ".$int_input_distrik." and e.kolom = 'AM';")[0]->count;
            $count_6_reimburse = DB::select("select count(e.row)
                              from pgdl_excel_datas_revisi e
                              join pgdl_sheets s on s.id = e.pgdl_sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                              join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                              where s.name like 'I-Form 6'
                              and fk.id IN ".$input_draft_form_6_reimburse."
                              and l.distrik_id = ".$int_input_distrik."
                              and e.kolom = 'AM';")[0]->count;
            $int_count_6_reimburse = (int)$count_6_reimburse;
            //dd($input_draft_form_6_reimburse);

            $daftar_prk_kegiatan_form_6_reimburse = array();
            $daftar_prk_inti_form_6_reimburse = array();
            $daftar_prk_parent_form_6_reimburse = array();

            //ambil data report dashboard dinamis
            $pgdl_report_dashboard_page_id = 1;
            $jenis_id = 2;
            $pgdl_sheet_name = 'I-Form 6';

            $setting_form_6_reimburse_prk_parent = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 1)
                            ->first();

            $setting_form_6_reimburse_prk_inti = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 2)
                            ->first();

            $setting_form_6_reimburse_no_prk_kegiatan = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 3)
                            ->first();

            $setting_form_6_reimburse_desc_prk_kegiatan = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 4)
                            ->first();

            $setting_form_6_reimburse_ijin_proses = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 5)
                            ->first();

            $setting_form_6_reimburse_beban_mat = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 6)
                            ->first();

            $setting_form_6_reimburse_ijin_proses_update = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 7)
                            ->first();

            $setting_form_6_reimburse_beban_mat_update = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 8)
                            ->first();

            $setting_form_6_reimburse_start_kolom = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 9)
                            ->first();

            //end of ambil data report dashboard dinamis

            // $form_6_reimburse_prk_parent = $this->get_form_6_parent($input_draft_form_6_reimburse,$int_input_distrik, 'I');

            $form_6_reimburse_prk_parent = $this->get_form_6_parent($input_draft_form_6_reimburse,$int_input_distrik, $setting_form_6_reimburse_prk_parent->kolom);
            //dd($form_6_reimburse_prk_parent);
            foreach ($form_6_reimburse_prk_parent as $key => $value) {
                $daftar_prk_parent_form_6_reimburse[$value->value] = array('desc_prk_parent' => '',
                    'beban_mat'     => 0,
                    'beban_mat_update'     => 0,
                    //'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'ijin_proses_update'   => 0,
                    //'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    // 'total_year_estimate' => 0,

                    );
            }

            // $form_6_reimburse_prk_inti = $this->get_form_6_inti($input_draft_form_6_reimburse ,$int_input_distrik, 'I');

            $form_6_reimburse_prk_inti = $this->get_form_6_inti($input_draft_form_6_reimburse ,$int_input_distrik, $setting_form_6_reimburse_prk_inti->kolom);
            //dd($form_6_reimburse_prk_inti);
            foreach ($form_6_reimburse_prk_inti as $key => $value) {
                $daftar_prk_inti_form_6_reimburse[$value->value] = array('desc_prk_inti' => '',
                    'prk_parent'    => substr($value->value, 0, 6),
                    'beban_mat'     => 0,
                    'beban_mat_update'     => 0,
                    //'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'ijin_proses_update'   => 0,
                    //'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    // 'total_year_estimate' => 0,
                );
            }

            // $form_6_reimburse_no_prk_kegiatan = $this->get_form_6($input_draft_form_6_reimburse,$int_input_distrik, 'I');
            // $form_6_reimburse_desc_prk_kegiatan = $this->get_form_6($input_draft_form_6_reimburse,$int_input_distrik, 'T');

            $form_6_reimburse_no_prk_kegiatan = $this->get_form_6($input_draft_form_6_reimburse,$int_input_distrik, $setting_form_6_reimburse_no_prk_kegiatan->kolom);
            $form_6_reimburse_desc_prk_kegiatan = $this->get_form_6($input_draft_form_6_reimburse,$int_input_distrik, $setting_form_6_reimburse_desc_prk_kegiatan->kolom);
            $form_6_reimburse_desc_prk_inti = $this->get_form_6($input_draft_form_6_reimburse,$int_input_distrik, 'S');
            $form_6_reimburse_desc_prk_parent = $this->get_form_6($input_draft_form_6_reimburse,$int_input_distrik, 'R');
            // $form_6_reimburse_beban_mat = $this->get_form_6($input_draft_form_6_reimburse,$int_input_distrik, 'AN');
            $form_6_reimburse_beban_mat = $this->get_form_6($input_draft_form_6_reimburse,$int_input_distrik, $setting_form_6_reimburse_beban_mat->kolom);
            // $form_6_reimburse_total_year_estimate = $this->get_form_6($input_draft_form_6_reimburse,$int_input_distrik, 'AN');
            //$form_6_reimburse_cash_oth = $this->get_form_6($input_draft_form_6_reimburse,$int_input_distrik, 'AV');
            // $form_6_reimburse_ijin_proses = $this->get_form_6($input_draft_form_6_reimburse,$int_input_distrik, 'AX');
            $form_6_reimburse_ijin_proses = $this->get_form_6($input_draft_form_6_reimburse,$int_input_distrik, $setting_form_6_reimburse_ijin_proses->kolom);

            // $form_6_reimburse_beban_mat_update = $this->get_form_6_update($input_draft_form_6_reimburse,$int_input_distrik, 'AN');
            // $form_6_reimburse_ijin_proses_update = $this->get_form_6_update($input_draft_form_6_reimburse,$int_input_distrik, 'AX');

            $form_6_reimburse_beban_mat_update = $this->get_form_6_update($input_draft_form_6_reimburse,$int_input_distrik, $setting_form_6_reimburse_beban_mat_update->kolom);
            $form_6_reimburse_ijin_proses_update = $this->get_form_6_update($input_draft_form_6_reimburse,$int_input_distrik, $setting_form_6_reimburse_ijin_proses_update->kolom);

            $form_6_reimburse_disburse_update = array();

            // $start_kolom = 'BA';
            $start_kolom = $setting_form_6_reimburse_start_kolom->kolom;
            for($bulan = 1; $bulan<=$int_input_bulan; $bulan++){
                //$form_6_reimburse_disburse[$bulan] = $this->get_form_6_disburse($input_draft_form_6_reimburse,$int_input_distrik, $start_kolom, $start_kolom++);
                $start_kolom_awal = $start_kolom;
                $start_kolom++;
                $form_6_reimburse_disburse_update[$bulan] = $this->get_form_6_disburse_update($input_draft_form_6_reimburse,$int_input_distrik, $start_kolom_awal, $start_kolom++);
                $start_kolom++;
            }
/*
            $form_6_reimburse_disburse[1] = $this->get_form_6_disburse($input_draft_form_6_reimburse,$int_input_distrik, 'BA', 'BB');
            $form_6_reimburse_disburse[2] = $this->get_form_6_disburse($input_draft_form_6_reimburse,$int_input_distrik, 'BC', 'BD');
            $form_6_reimburse_disburse[3] = $this->get_form_6_disburse($input_draft_form_6_reimburse,$int_input_distrik, 'BE', 'BF');
            $form_6_reimburse_disburse[4] = $this->get_form_6_disburse($input_draft_form_6_reimburse,$int_input_distrik, 'BG', 'BH');
            $form_6_reimburse_disburse[5] = $this->get_form_6_disburse($input_draft_form_6_reimburse,$int_input_distrik, 'BI', 'BJ');
            $form_6_reimburse_disburse[6] = $this->get_form_6_disburse($input_draft_form_6_reimburse,$int_input_distrik, 'BK', 'BL');
            $form_6_reimburse_disburse[7] = $this->get_form_6_disburse($input_draft_form_6_reimburse,$int_input_distrik, 'BM', 'BN');
            $form_6_reimburse_disburse[8] = $this->get_form_6_disburse($input_draft_form_6_reimburse,$int_input_distrik, 'BO', 'BP');
            $form_6_reimburse_disburse[9] = $this->get_form_6_disburse($input_draft_form_6_reimburse,$int_input_distrik, 'BQ', 'BR');
            $form_6_reimburse_disburse[10] = $this->get_form_6_disburse($input_draft_form_6_reimburse,$int_input_distrik, 'BS', 'BT');
            $form_6_reimburse_disburse[11] = $this->get_form_6_disburse($input_draft_form_6_reimburse,$int_input_distrik, 'BU', 'BV');
            $form_6_reimburse_disburse[12] = $this->get_form_6_disburse($input_draft_form_6_reimburse,$int_input_distrik, 'BW', 'BX');
*/
            for($i=0; $i<$int_count_6_reimburse; $i++){
                $parent = substr($form_6_reimburse_no_prk_kegiatan[$i]->value,0,6);
                $inti = substr($form_6_reimburse_no_prk_kegiatan[$i]->value,0,8);

                //dd($form_6_reimburse_no_prk_kegiatan[$i]->value);

                $disburse = array();
                $bln = array();
                $disburse_sd_bulan = 0;
                //dd($form_6_reimburse_disburse);
                //for($bulan=1; $bulan<=12; $bulan++){
                for($bulan=1; $bulan<=$int_input_bulan; $bulan++){
                    //$disburse[$bulan] = (float)$form_6_reimburse_disburse[$bulan][$i]->value;
                    $bln[$bulan] = $bulan;
                    // $disburse_sd_bulan += (float)$form_6_reimburse_disburse_update[$bulan][$i]->value;
                    if($form_6_reimburse_disburse_update[$bulan]) {
                      $disburse_sd_bulan += (float)$form_6_reimburse_disburse_update[$bulan][$i]->value;
                    }
                    else {
                      $disburse_sd_bulan += 0;
                    }
                    // $daftar_prk_inti_form_6_reimburse[$inti]['disburse'][$bulan] += $disburse[$bulan];
                    // $daftar_prk_parent_form_6_reimburse[$parent]['disburse'][$bulan] += $disburse[$bulan];
                }
                $bln = implode(",",$bln);
                $bln = "(".$bln.")";

                //$form_6_reimburse_no_prk_kegiatan[$i]->value = "183G0101";
                // $disburse_sd_bulan_realisasi = (float)$this->get_disburse_sd_bulan_realisasi($input_distrik->code1, $bln, $form_6_reimburse_no_prk_kegiatan[$i]->value)[0]->sum;

                // $get_disburse_sd_bulan_realisasi = $this->get_disburse_sd_bulan_realisasi($input_distrik->code1, $bln, $form_6_reimburse_no_prk_kegiatan[$i]->value);

                // if($get_disburse_sd_bulan_realisasi) {
                //     $disburse_sd_bulan_realisasi = (float)$get_disburse_sd_bulan_realisasi[0]->sum;
                // }
                // else {
                //     $disburse_sd_bulan_realisasi = 0;
                // }

                $get_actuals = (float)$this->get_actuals($input_distrik->code1, substr($form_6_reimburse_no_prk_kegiatan[$i]->value, 2));
                $get_commitments = (float)$this->get_commitments($input_distrik->code1, substr($form_6_reimburse_no_prk_kegiatan[$i]->value, 2));

                //cek ada No PRK kegiatan
                // $cek_no_prk_ketetapan = $this->cek_no_prk_form_6_ketetapan($input_draft_form_6_reimburse,$int_input_distrik, 'I', $form_6_reimburse_no_prk_kegiatan[$i]->value);
                $cek_no_prk_ketetapan = $this->cek_no_prk_form_6_ketetapan($input_draft_form_6_reimburse,$int_input_distrik, $setting_form_6_reimburse_no_prk_kegiatan->kolom, $form_6_reimburse_no_prk_kegiatan[$i]->value);
                if($cek_no_prk_ketetapan) {
                    // $beban_mat_ketetapan[$i] = $form_6_rutin_beban_mat[$i]->value;
                    // $beban_mat_form_6_reimburse_ketetapan[$i] = $this->get_form_6_ketetapan($input_draft_form_6_reimburse,$int_input_distrik, 'AN', 'I', $form_6_reimburse_no_prk_kegiatan[$i]->value)[0]->value;
                    // $ijin_proses_form_6_reimburse_ketetapan[$i] = $this->get_form_6_ketetapan($input_draft_form_6_reimburse,$int_input_distrik, 'AX', 'I', $form_6_reimburse_no_prk_kegiatan[$i]->value)[0]->value;

                    $beban_mat_form_6_reimburse_ketetapan[$i] = $this->get_form_6_ketetapan($input_draft_form_6_reimburse,$int_input_distrik, $setting_form_6_reimburse_beban_mat->kolom, $setting_form_6_reimburse_no_prk_kegiatan->kolom, $form_6_reimburse_no_prk_kegiatan[$i]->value)[0]->value;

                    $ijin_proses_form_6_reimburse_ketetapan[$i] = $this->get_form_6_ketetapan($input_draft_form_6_reimburse,$int_input_distrik, $setting_form_6_reimburse_ijin_proses->kolom, $setting_form_6_reimburse_no_prk_kegiatan->kolom, $form_6_reimburse_no_prk_kegiatan[$i]->value)[0]->value;
                }
                else {
                    $beban_mat_form_6_reimburse_ketetapan[$i] = 0;
                    $ijin_proses_form_6_reimburse_ketetapan[$i] = 0;
                }

                $temp = array(
                    'prk_kegiatan' => $form_6_reimburse_no_prk_kegiatan[$i]->value,
                    'prk_inti' => $inti,
                    'prk_parent' => $parent,
                    'desc_prk_kegiatan' => $form_6_reimburse_desc_prk_kegiatan[$i]->value,
                    'desc_prk_inti' => $form_6_reimburse_desc_prk_inti[$i]->value,
                    'desc_prk_parent' => $form_6_reimburse_desc_prk_parent[$i]->value,
                    // 'beban_mat' => (float)$form_6_reimburse_beban_mat[$i]->value,
                    'beban_mat' => (float)$beban_mat_form_6_reimburse_ketetapan[$i],
                    'beban_mat_update' => (float)$form_6_reimburse_beban_mat_update[$i]->value,
                    //'cash_oth' => (float)$form_6_reimburse_cash_oth[$i]->value,
                    // 'ijin_proses' => (float)$form_6_reimburse_ijin_proses[$i]->value,
                    'ijin_proses' => (float)$ijin_proses_form_6_reimburse_ketetapan[$i],
                    'ijin_proses_update' => (float)$form_6_reimburse_ijin_proses_update[$i]->value,
                    //'disburse' => $disburse,
                    'disburse_sd_bulan' => $disburse_sd_bulan,
                    // 'total_year_estimate' => (float)$form_6_reimburse_total_year_estimate[$i]->value,
                    'disburse_sd_bulan_realisasi' => $get_actuals,
                    'estimate_realisasi' => $get_actuals + $get_commitments,
                );
                array_push($daftar_prk_kegiatan_form_6_reimburse, $temp);

                $daftar_prk_inti_form_6_reimburse[$inti]['desc_prk_inti'] = $temp['desc_prk_inti'];
                $daftar_prk_inti_form_6_reimburse[$inti]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_inti_form_6_reimburse[$inti]['beban_mat_update'] += $temp['beban_mat_update'];
                //$daftar_prk_inti_form_6_reimburse[$inti]['cash_oth'] += $temp['cash_oth'];
                $daftar_prk_inti_form_6_reimburse[$inti]['ijin_proses'] += $temp['ijin_proses'];
                $daftar_prk_inti_form_6_reimburse[$inti]['ijin_proses_update'] += $temp['ijin_proses_update'];

                $daftar_prk_parent_form_6_reimburse[$parent]['desc_prk_parent'] = $temp['desc_prk_parent'];
                $daftar_prk_parent_form_6_reimburse[$parent]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_parent_form_6_reimburse[$parent]['beban_mat_update'] += $temp['beban_mat_update'];
                //$daftar_prk_parent_form_6_reimburse[$parent]['cash_oth'] += $temp['cash_oth'];
                $daftar_prk_parent_form_6_reimburse[$parent]['ijin_proses'] += $temp['ijin_proses'];
                $daftar_prk_parent_form_6_reimburse[$parent]['ijin_proses_update'] += $temp['ijin_proses_update'];
            }
            $dataparent['form_6_reimburse'] = $daftar_prk_parent_form_6_reimburse;
            $datainti['form_6_reimburse'] = $daftar_prk_inti_form_6_reimburse;
            $datakegiatan['form_6_reimburse'] = $daftar_prk_kegiatan_form_6_reimburse;

        } //end of cek request draft form 6 reimburse
        //End of query form 6 reimburse

        //Start Form 6 Rutin
        if($input_draft_form_6_rutin) {
          //dd($input_draft_form_6_rutin);
            // $count_6_rutin = DB::select("select count(e.row)
            //                         from excel_datas e
            //                         join sheets s on s.id = e.sheet_id
            //                         join lokasi l on l.id = e.lokasi_id
            //                         where s.name like 'I-Form 6'
            //                         and e.file_import_id IN ".$input_draft_form_6_rutin."
            //                         and l.distrik_id = ".$int_input_distrik." and e.kolom = 'AM';")[0]->count;
            $count_6_rutin = DB::select("select count(distinct e.row)
                              from pgdl_excel_datas_revisi e
                              join pgdl_sheets s on s.id = e.pgdl_sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                              join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                              where s.name like 'I-Form 6'
                              and fk.id IN ".$input_draft_form_6_rutin."
                              and l.distrik_id = ".$int_input_distrik."
                              and e.kolom = 'AM';")[0]->count;
            $int_count_6_rutin = (int)$count_6_rutin;
            // dd($int_count_6_rutin);

            $daftar_prk_kegiatan_form_6_rutin = array();
            $daftar_prk_inti_form_6_rutin = array();
            $daftar_prk_parent_form_6_rutin = array();

            //ambil data report dashboard dinamis
            $pgdl_report_dashboard_page_id = 1;
            $jenis_id = 3;
            $pgdl_sheet_name = 'I-Form 6';

            $setting_form_6_rutin_prk_parent = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 1)
                            ->first();

            $setting_form_6_rutin_prk_inti = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 2)
                            ->first();

            $setting_form_6_rutin_no_prk_kegiatan = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 3)
                            ->first();

            $setting_form_6_rutin_desc_prk_kegiatan = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 4)
                            ->first();

            $setting_form_6_rutin_ijin_proses = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 5)
                            ->first();

            $setting_form_6_rutin_beban_mat = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 6)
                            ->first();

            $setting_form_6_rutin_ijin_proses_update = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 7)
                            ->first();

            $setting_form_6_rutin_beban_mat_update = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 8)
                            ->first();

            $setting_form_6_rutin_start_kolom = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 9)
                            ->first();

            //end of ambil data report dashboard dinamis

            // $form_6_rutin_prk_parent = $this->get_form_6_parent($input_draft_form_6_rutin,$int_input_distrik, 'I');

            $form_6_rutin_prk_parent = $this->get_form_6_parent($input_draft_form_6_rutin,$int_input_distrik, $setting_form_6_rutin_prk_parent->kolom);
            foreach ($form_6_rutin_prk_parent as $key => $value) {
                $daftar_prk_parent_form_6_rutin[$value->value] = array('desc_prk_parent' => '',
                    'beban_mat'     => 0,
                    'beban_mat_update'     => 0,
                    //'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'ijin_proses_update'   => 0,
                    //'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    // 'total_year_estimate' => 0,
                    );
            }

            // $form_6_rutin_prk_inti = $this->get_form_6_inti($input_draft_form_6_rutin,$int_input_distrik, 'I');
            $form_6_rutin_prk_inti = $this->get_form_6_inti($input_draft_form_6_rutin,$int_input_distrik, $setting_form_6_rutin_prk_inti->kolom);
            foreach ($form_6_rutin_prk_inti as $key => $value) {
                $daftar_prk_inti_form_6_rutin[$value->value] = array('desc_prk_inti' => '',
                    'prk_parent'    => substr($value->value, 0, 6),
                    'beban_mat'     => 0,
                    'beban_mat_update'     => 0,
                    //'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'ijin_proses_update'   => 0,
                    //'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    // 'total_year_estimate' => 0,
                );
            }

            // $form_6_rutin_no_prk_kegiatan = $this->get_form_6($input_draft_form_6_rutin,$int_input_distrik, 'I');
            // $form_6_rutin_desc_prk_kegiatan = $this->get_form_6($input_draft_form_6_rutin,$int_input_distrik, 'T');

            $form_6_rutin_no_prk_kegiatan = $this->get_form_6($input_draft_form_6_rutin,$int_input_distrik, $setting_form_6_rutin_no_prk_kegiatan->kolom);
            $form_6_rutin_desc_prk_kegiatan = $this->get_form_6($input_draft_form_6_rutin,$int_input_distrik, $setting_form_6_rutin_desc_prk_kegiatan->kolom);
            $form_6_rutin_desc_prk_inti = $this->get_form_6($input_draft_form_6_rutin,$int_input_distrik, 'S');
            $form_6_rutin_desc_prk_parent = $this->get_form_6($input_draft_form_6_rutin,$int_input_distrik, 'R');
            // $form_6_rutin_beban_mat = $this->get_form_6($input_draft_form_6_rutin,$int_input_distrik, 'AN');
            $form_6_rutin_beban_mat = $this->get_form_6($input_draft_form_6_rutin,$int_input_distrik, $setting_form_6_rutin_beban_mat->kolom);
            // $form_6_rutin_total_year_estimate = $this->get_form_6($input_draft_form_6_rutin,$int_input_distrik, 'AN');
            //$form_6_rutin_cash_oth = $this->get_form_6($input_draft_form_6_rutin,$int_input_distrik, 'AV');
            // $form_6_rutin_ijin_proses = $this->get_form_6($input_draft_form_6_rutin,$int_input_distrik, 'AX');
            $form_6_rutin_ijin_proses = $this->get_form_6($input_draft_form_6_rutin,$int_input_distrik, $setting_form_6_rutin_ijin_proses->kolom);
            $form_6_rutin_disburse = array();

            // $form_6_rutin_beban_mat_update = $this->get_form_6_update($input_draft_form_6_rutin,$int_input_distrik, 'AN');
            // $form_6_rutin_ijin_proses_update = $this->get_form_6_update($input_draft_form_6_rutin,$int_input_distrik, 'AX');

            $form_6_rutin_beban_mat_update = $this->get_form_6_update($input_draft_form_6_rutin,$int_input_distrik, $setting_form_6_rutin_beban_mat_update->kolom);
            $form_6_rutin_ijin_proses_update = $this->get_form_6_update($input_draft_form_6_rutin,$int_input_distrik, $setting_form_6_rutin_ijin_proses_update->kolom);
            $form_6_rutin_disburse_sd_bulan = array();

            //$start_kolom = 'BA';
            // $start_kolom1 = 'BA';
            $start_kolom1 = $setting_form_6_rutin_start_kolom->kolom;
            // dump($int_input_bulan);
            for($bulan = 1; $bulan<=$int_input_bulan; $bulan++){
                //$form_6_rutin_disburse[$bulan] = $this->get_form_6_disburse($input_draft_form_6_rutin,$int_input_distrik, $start_kolom, $start_kolom++);
                $start_kolom_awal = $start_kolom1;
                $start_kolom1++;
                  $form_6_rutin_disburse_sd_bulan[$bulan] = $this->get_form_6_disburse_update($input_draft_form_6_rutin,$int_input_distrik, $start_kolom_awal, $start_kolom1);
                $start_kolom1++;
            }
            //dd($form_6_rutin_disburse_sd_bulan);
//            dd($start_kolom);
/*
            $form_6_rutin_disburse[1] = $this->get_form_6_disburse($input_draft_form_6_rutin,$int_input_distrik, 'BA', 'BB');
            $form_6_rutin_disburse[2] = $this->get_form_6_disburse($input_draft_form_6_rutin,$int_input_distrik, 'BC', 'BD');
            $form_6_rutin_disburse[3] = $this->get_form_6_disburse($input_draft_form_6_rutin,$int_input_distrik, 'BE', 'BF');
            $form_6_rutin_disburse[4] = $this->get_form_6_disburse($input_draft_form_6_rutin,$int_input_distrik, 'BG', 'BH');
            $form_6_rutin_disburse[5] = $this->get_form_6_disburse($input_draft_form_6_rutin,$int_input_distrik, 'BI', 'BJ');
            $form_6_rutin_disburse[6] = $this->get_form_6_disburse($input_draft_form_6_rutin,$int_input_distrik, 'BK', 'BL');
            $form_6_rutin_disburse[7] = $this->get_form_6_disburse($input_draft_form_6_rutin,$int_input_distrik, 'BM', 'BN');
            $form_6_rutin_disburse[8] = $this->get_form_6_disburse($input_draft_form_6_rutin,$int_input_distrik, 'BO', 'BP');
            $form_6_rutin_disburse[9] = $this->get_form_6_disburse($input_draft_form_6_rutin,$int_input_distrik, 'BQ', 'BR');
            $form_6_rutin_disburse[10] = $this->get_form_6_disburse($input_draft_form_6_rutin,$int_input_distrik, 'BS', 'BT');
            $form_6_rutin_disburse[11] = $this->get_form_6_disburse($input_draft_form_6_rutin,$int_input_distrik, 'BU', 'BV');
            $form_6_rutin_disburse[12] = $this->get_form_6_disburse($input_draft_form_6_rutin,$int_input_distrik, 'BW', 'BX');
*/
            // dd($form_6_rutin_no_prk_kegiatan);
            for($i=0; $i<$int_count_6_rutin; $i++){
                $parent = substr($form_6_rutin_no_prk_kegiatan[$i]->value,0,6);
                $inti = substr($form_6_rutin_no_prk_kegiatan[$i]->value,0,8);
                //dd($form_6_rutin_disburse);

                $disburse = array();
                $bln = array();
                $disburse_sd_bulan = 0;
                //for($bulan=1; $bulan<=12; $bulan++){
                for($bulan=1; $bulan<=$int_input_bulan; $bulan++){
                    //$disburse[$bulan] = (float)$form_6_rutin_disburse[$bulan][$i]->value;
                    //if($bulan<=$int_input_bulan){
                    $bln[$bulan] = $bulan;
                    if($form_6_rutin_disburse_sd_bulan[$bulan]) {
                      $disburse_sd_bulan += (float)$form_6_rutin_disburse_sd_bulan[$bulan][$i]->value;
                    }
                    else {
                      $disburse_sd_bulan += 0;
                    }
                    //}
                    // $daftar_prk_inti_form_6_rutin[$inti]['disburse'][$bulan] += $disburse[$bulan];
                    // $daftar_prk_parent_form_6_rutin[$parent]['disburse'][$bulan] += $disburse[$bulan];
                }
                // dump($disburse_sd_bulan);
                //dd($disburse);

                $bln = implode(",",$bln);
                $bln = "(".$bln.")";

                // $get_disburse_sd_bulan_realisasi = $this->get_disburse_sd_bulan_realisasi($input_distrik->code1, $bln, $form_6_rutin_no_prk_kegiatan[$i]->value);

                // if($get_disburse_sd_bulan_realisasi) {
                //     $disburse_sd_bulan_realisasi = (float)$get_disburse_sd_bulan_realisasi[0]->sum;
                // }
                // else {
                //     $disburse_sd_bulan_realisasi = 0;
                // }

                $get_actuals = (float)$this->get_actuals($input_distrik->code1, substr($form_6_rutin_no_prk_kegiatan[$i]->value,2));
                $get_commitments = (float)$this->get_commitments($input_distrik->code1, substr($form_6_rutin_no_prk_kegiatan[$i]->value,2));

                //cek ada No PRK kegiatan
                $cek_no_prk_ketetapan = $this->cek_no_prk_form_6_ketetapan($input_draft_form_6_rutin,$int_input_distrik, 'I', $form_6_rutin_no_prk_kegiatan[$i]->value);
                if($cek_no_prk_ketetapan) {
                    // $beban_mat_ketetapan[$i] = $form_6_rutin_beban_mat[$i]->value;
                    // $beban_mat_form_6_rutin_ketetapan[$i] = $this->get_form_6_ketetapan($input_draft_form_6_rutin,$int_input_distrik, 'AN', 'I', $form_6_rutin_no_prk_kegiatan[$i]->value)[0]->value;

                    $beban_mat_form_6_rutin_ketetapan[$i] = $this->get_form_6_ketetapan($input_draft_form_6_rutin,$int_input_distrik, $setting_form_6_rutin_beban_mat->kolom, $setting_form_6_rutin_no_prk_kegiatan->kolom, $form_6_rutin_no_prk_kegiatan[$i]->value)[0]->value;

                    // $ijin_proses_ketetapan[$i] = $form_6_rutin_ijin_proses[$i]->value;
                    // $ijin_proses_form_6_rutin_ketetapan[$i] = $this->get_form_6_ketetapan($input_draft_form_6_rutin,$int_input_distrik, 'AX', 'I', $form_6_rutin_no_prk_kegiatan[$i]->value)[0]->value;

                    $ijin_proses_form_6_rutin_ketetapan[$i] = $this->get_form_6_ketetapan($input_draft_form_6_rutin,$int_input_distrik, $setting_form_6_rutin_ijin_proses->kolom, $setting_form_6_rutin_no_prk_kegiatan->kolom, $form_6_rutin_no_prk_kegiatan[$i]->value)[0]->value;
                }
                else {
                    $beban_mat_form_6_rutin_ketetapan[$i] = 0;
                    $ijin_proses_form_6_rutin_ketetapan[$i] = 0;
                }

                $temp = array(
                    'prk_kegiatan' => $form_6_rutin_no_prk_kegiatan[$i]->value,
                    'prk_inti' => $inti,
                    'prk_parent' => $parent,
                    'desc_prk_kegiatan' => $form_6_rutin_desc_prk_kegiatan[$i]->value,
                    'desc_prk_inti' => $form_6_rutin_desc_prk_inti[$i]->value,
                    'desc_prk_parent' => $form_6_rutin_desc_prk_parent[$i]->value,
                    // 'beban_mat' => (float)$form_6_rutin_beban_mat[$i]->value,
                    'beban_mat' => (float)$beban_mat_form_6_rutin_ketetapan[$i],
                    'beban_mat_update' => (float)$form_6_rutin_beban_mat_update[$i]->value,
                    //'cash_oth' => (float)$form_6_rutin_cash_oth[$i]->value,
                    // 'ijin_proses' => (float)$form_6_rutin_ijin_proses[$i]->value,
                    'ijin_proses' => (float)$ijin_proses_form_6_rutin_ketetapan[$i],
                    'ijin_proses_update' => (float)$form_6_rutin_ijin_proses_update[$i]->value,
                    //'disburse' => $disburse,
                    'disburse_sd_bulan' => $disburse_sd_bulan,
                    'disburse_sd_bulan_realisasi' => $get_actuals,
                    'estimate_realisasi' => $get_actuals + $get_commitments,
                    // 'total_year_estimate' => (float)$form_6_rutin_total_year_estimate[$i]->value,
                );
                array_push($daftar_prk_kegiatan_form_6_rutin, $temp);

                //dd($temp['beban_mat_update']);

                $daftar_prk_inti_form_6_rutin[$inti]['desc_prk_inti'] = $temp['desc_prk_inti'];
                $daftar_prk_inti_form_6_rutin[$inti]['beban_mat'] += $temp['beban_mat'];
                // dump($temp['beban_mat']);
                // dump($daftar_prk_inti_form_6_rutin[$inti]['beban_mat']);
                $daftar_prk_inti_form_6_rutin[$inti]['beban_mat_update'] += $temp['beban_mat_update'];
                //$daftar_prk_inti_form_6_rutin[$inti]['cash_oth'] += $temp['cash_oth'];
                $daftar_prk_inti_form_6_rutin[$inti]['ijin_proses'] += $temp['ijin_proses'];
                $daftar_prk_inti_form_6_rutin[$inti]['ijin_proses_update'] += $temp['ijin_proses_update'];

                $daftar_prk_parent_form_6_rutin[$parent]['desc_prk_parent'] = $temp['desc_prk_parent'];
                $daftar_prk_parent_form_6_rutin[$parent]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_parent_form_6_rutin[$parent]['beban_mat_update'] += $temp['beban_mat_update'];
                //$daftar_prk_parent_form_6_rutin[$parent]['cash_oth'] += $temp['cash_oth'];
                $daftar_prk_parent_form_6_rutin[$parent]['ijin_proses'] += $temp['ijin_proses'];
                $daftar_prk_parent_form_6_rutin[$parent]['ijin_proses_update'] += $temp['ijin_proses_update'];
            }
            $dataparent['form_6_rutin'] = $daftar_prk_parent_form_6_rutin;
            $datainti['form_6_rutin'] = $daftar_prk_inti_form_6_rutin;
            $datakegiatan['form_6_rutin'] = $daftar_prk_kegiatan_form_6_rutin;

        } //end of cek request draft form 6 rutin
        //End of query form 6 rutin

        //Start form Penyusutan
//        if($request->input('draft_form_penyusutan')) {
        if($input_draft_form_penyusutan){
          //dd($input_draft_form_penyusutan);
            // $count_penyusutan = DB::select("select count(e.row)
            //                               from excel_datas e
            //                               join sheets s on s.id = e.sheet_id
            //                               join lokasi l on l.id = e.lokasi_id
            //                               where s.name like 'I-Penyusutan'
            //                               and e.file_import_id IN ".$input_draft_form_penyusutan."
            //                               and l.distrik_id = ".$int_input_distrik." and e.kolom = 'H';")[0]->count;

            $count_penyusutan = DB::select("select count(e.row)
                              from pgdl_excel_datas_revisi e
                              join pgdl_sheets s on s.id = e.pgdl_sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                              join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                              where s.name like 'I-Penyusutan'
                              and fk.id IN ".$input_draft_form_penyusutan."
                              and l.distrik_id = ".$int_input_distrik."
                              and e.kolom = 'H';")[0]->count;

            $int_count_penyusutan = (int)$count_penyusutan;

            $daftar_prk_kegiatan_form_penyusutan = array();
            $daftar_prk_inti_form_penyusutan = array();
            $daftar_prk_parent_form_penyusutan = array();

            //ambil data report dashboard dinamis
            $pgdl_report_dashboard_page_id = 1;
            $jenis_id = 9;
            $pgdl_sheet_name = 'I-Penyusutan';

            $setting_form_penyusutan_prk_parent = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 1)
                            ->first();

            $setting_form_penyusutan_prk_inti = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 2)
                            ->first();

            $setting_form_penyusutan_no_prk_kegiatan = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 3)
                            ->first();

            $setting_form_penyusutan_beban_mat = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 6)
                            ->first();

            $setting_form_penyusutan_beban_mat_update = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 8)
                            ->first();

            $setting_form_penyusutan_start_kolom = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                            ->where('tahun', $input_tahun)
                            ->where('jenis_id', $jenis_id)
                            ->where('pgdl_sheet_name', $pgdl_sheet_name)
                            ->where('sequence', 9)
                            ->first();

            //end of ambil data report dashboard dinamis

            // $form_penyusutan_prk_parent = $this->get_form_penyusutan_parent($input_draft_form_penyusutan,$int_input_distrik, 'H');

            $form_penyusutan_prk_parent = $this->get_form_penyusutan_parent($input_draft_form_penyusutan,$int_input_distrik, $setting_form_penyusutan_prk_parent->kolom);
            foreach ($form_penyusutan_prk_parent as $key => $value) {
                // $desc_prk_parent = $this->get_prk_parent_desc(substr($value->value, 4,2));
                $daftar_prk_parent_form_penyusutan[$value->value] = array(
                    // 'desc_prk_parent' => ($desc_prk_parent!= null ? $desc_prk_parent->desc_prk_parent : ''),
                    'desc_prk_parent' => 'Penyusutan',
                    'beban_mat'     => 0,
                    'beban_mat_update'     => 0,
                    //'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'ijin_proses_update'   => 0,
                    // 'total_year_estimate'   => 0,
                    //'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    );
            }

            // $form_penyusutan_prk_inti = $this->get_form_penyusutan_inti($input_draft_form_penyusutan,$int_input_distrik, 'H');
            $form_penyusutan_prk_inti = $this->get_form_penyusutan_inti($input_draft_form_penyusutan,$int_input_distrik, $setting_form_penyusutan_prk_inti->kolom);
            foreach ($form_penyusutan_prk_inti as $key => $value) {
                // $desc_prk_inti = $this->get_prk_inti_desc(substr($value->value, 4,2),substr($value->value, 6,2));
                $daftar_prk_inti_form_penyusutan[$value->value] = array(
                    // 'desc_prk_inti' => ($desc_prk_inti!= null ? $desc_prk_inti->desc_prk_inti : ''),
                    'desc_prk_inti' => 'Penyusutan',
                    'prk_parent'    => substr($value->value, 0, 6),
                    'beban_mat'     => 0,
                    'beban_mat_update'     => 0,
                    //'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'ijin_proses_update'   => 0,
                    // 'total_year_estimate'   => 0,
                    //'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                );
            }

            // $form_penyusutan_no_prk_kegiatan = $this->get_form_penyusutan($input_draft_form_penyusutan,$int_input_distrik, 'H');
            $form_penyusutan_no_prk_kegiatan = $this->get_form_penyusutan($input_draft_form_penyusutan,$int_input_distrik, $setting_form_penyusutan_no_prk_kegiatan->kolom);
            // $form_penyusutan_desc_prk_kegiatan = $this->get_form_penyusutan($request->input('draft_form_penyusutan'),$int_input_lokasi, 'H');
            //$form_penyusutan_desc_prk_inti = $this->get_form_penyusutan($request->input('draft_form_penyusutan'),$int_input_lokasi, 'T');
            //$form_penyusutan_desc_prk_parent = $this->get_form_penyusutan($request->input('draft_form_penyusutan'),$int_input_lokasi, 'S');
            // $form_penyusutan_beban_mat = $this->get_form_penyusutan($input_draft_form_penyusutan,$int_input_distrik, 'O');
            $form_penyusutan_beban_mat = $this->get_form_penyusutan($input_draft_form_penyusutan,$int_input_distrik, $setting_form_penyusutan_beban_mat->kolom);
            // $form_penyusutan_total_year_estimate = $this->get_form_penyusutan($input_draft_form_penyusutan,$int_input_distrik, 'O');

            // $form_penyusutan_beban_mat_update = $this->get_form_penyusutan_update($input_draft_form_penyusutan,$int_input_distrik, 'O');
            $form_penyusutan_beban_mat_update = $this->get_form_penyusutan_update($input_draft_form_penyusutan,$int_input_distrik, $setting_form_penyusutan_beban_mat_update->kolom);

            $form_penyusutan_disburse = array();

            // $start_kolom = 'Q';
            $start_kolom = $setting_form_penyusutan_start_kolom->kolom;
            for($bulan = 1; $bulan<=$int_input_bulan; $bulan++){
                $form_penyusutan_disburse[$bulan] = $this->get_form_penyusutan_disburse_update($input_draft_form_penyusutan,$int_input_distrik, $start_kolom);
            }
/*
            $form_penyusutan_disburse[1] = $this->get_form_penyusutan_disburse($request->input('draft_form_penyusutan'),$int_input_distrik, 'Q');
            $form_penyusutan_disburse[2] = $this->get_form_penyusutan_disburse($request->input('draft_form_penyusutan'),$int_input_distrik, 'R');
            $form_penyusutan_disburse[3] = $this->get_form_penyusutan_disburse($request->input('draft_form_penyusutan'),$int_input_distrik, 'S');
            $form_penyusutan_disburse[4] = $this->get_form_penyusutan_disburse($request->input('draft_form_penyusutan'),$int_input_distrik, 'T');
            $form_penyusutan_disburse[5] = $this->get_form_penyusutan_disburse($request->input('draft_form_penyusutan'),$int_input_distrik, 'U');
            $form_penyusutan_disburse[6] = $this->get_form_penyusutan_disburse($request->input('draft_form_penyusutan'),$int_input_distrik, 'V');
            $form_penyusutan_disburse[7] = $this->get_form_penyusutan_disburse($request->input('draft_form_penyusutan'),$int_input_distrik, 'W');
            $form_penyusutan_disburse[8] = $this->get_form_penyusutan_disburse($request->input('draft_form_penyusutan'),$int_input_distrik, 'X');
            $form_penyusutan_disburse[9] = $this->get_form_penyusutan_disburse($request->input('draft_form_penyusutan'),$int_input_distrik, 'Y');
            $form_penyusutan_disburse[10] = $this->get_form_penyusutan_disburse($request->input('draft_form_penyusutan'),$int_input_distrik, 'Z');
            $form_penyusutan_disburse[11] = $this->get_form_penyusutan_disburse($request->input('draft_form_penyusutan'),$int_input_distrik, 'AA');
            $form_penyusutan_disburse[12] = $this->get_form_penyusutan_disburse($request->input('draft_form_penyusutan'),$int_input_distrik, 'AB');
*/
            for($i=0; $i<$int_count_penyusutan; $i++){
                $parent = substr($form_penyusutan_no_prk_kegiatan[$i]->value,0,6);
                $inti = substr($form_penyusutan_no_prk_kegiatan[$i]->value,0,8);

                //dd($form_penyusutan_disburse);
                $disburse = array();
                $bln = array();
                $disburse_sd_bulan = 0;
                //for($bulan=1; $bulan<=12; $bulan++){
                for($bulan=1; $bulan<=$int_input_bulan; $bulan++){
                    //$disburse[$bulan] = (float)$form_penyusutan_disburse[$bulan][$i]->value;
                    $bln[$bulan] = $bulan;
                    // $disburse_sd_bulan += (float)$form_penyusutan_disburse[$bulan][$i]->value;
                    if($form_penyusutan_disburse[$bulan]) {
                      $disburse_sd_bulan += (float)$form_penyusutan_disburse[$bulan][$i]->value;
                    }
                    else {
                      $disburse_sd_bulan += 0;
                    }

                    // $daftar_prk_inti_form_penyusutan[$inti]['disburse'][$bulan] += $disburse[$bulan];
                    // $daftar_prk_parent_form_penyusutan[$parent]['disburse'][$bulan] += $disburse[$bulan];
                }

                $bln = implode(",",$bln);
                $bln = "(".$bln.")";

                // $disburse_sd_bulan_realisasi = (float)$this->get_disburse_sd_bulan_realisasi($input_distrik->code1, $bln, $form_penyusutan_no_prk_kegiatan[$i]->value)[0]->sum;

                // $get_disburse_sd_bulan_realisasi = $this->get_disburse_sd_bulan_realisasi($input_distrik->code1, $bln, $form_penyusutan_no_prk_kegiatan[$i]->value);

                // if($get_disburse_sd_bulan_realisasi) {
                //     $disburse_sd_bulan_realisasi = (float)$get_disburse_sd_bulan_realisasi[0]->sum;
                // }
                // else {
                //     $disburse_sd_bulan_realisasi = 0;
                // }

                $get_actuals = (float)$this->get_actuals($input_distrik->code1, substr($form_penyusutan_no_prk_kegiatan[$i]->value,2));
                $get_commitments = (float)$this->get_commitments($input_distrik->code1, substr($form_penyusutan_no_prk_kegiatan[$i]->value,2));

                //cek ada No PRK kegiatan
                // $cek_no_prk_ketetapan = $this->cek_no_prk_form_penyusutan_ketetapan($input_draft_form_penyusutan,$int_input_distrik, 'H', $form_penyusutan_no_prk_kegiatan[$i]->value);
                $cek_no_prk_ketetapan = $this->cek_no_prk_form_penyusutan_ketetapan($input_draft_form_penyusutan,$int_input_distrik, $setting_form_penyusutan_no_prk_kegiatan->kolom, $form_penyusutan_no_prk_kegiatan[$i]->value);
                if($cek_no_prk_ketetapan) {
                    // $beban_mat_form_penyusutan_ketetapan[$i] = $this->get_form_penyusutan_ketetapan($input_draft_form_penyusutan,$int_input_distrik, 'O', 'H', $form_penyusutan_no_prk_kegiatan[$i]->value)[0]->value;
                    $beban_mat_form_penyusutan_ketetapan[$i] = $this->get_form_penyusutan_ketetapan($input_draft_form_penyusutan,$int_input_distrik, $setting_form_penyusutan_beban_mat->kolom, $setting_form_penyusutan_no_prk_kegiatan->kolom, $form_penyusutan_no_prk_kegiatan[$i]->value)[0]->value;
                }
                else {
                    $beban_mat_form_penyusutan_ketetapan[$i] = 0;
                }

                $temp = array(
                    'prk_kegiatan' => $form_penyusutan_no_prk_kegiatan[$i]->value,
                    'prk_inti' => $inti,
                    'prk_parent' => $parent,
                    // 'desc_prk_kegiatan' => $form_penyusutan_desc_prk_kegiatan[$i]->value,
                    'desc_prk_kegiatan' => 'Penyusutan',
                    //'desc_prk_inti' => $form_penyusutan_desc_prk_inti[$i]->value,
                    //'desc_prk_parent' => $form_penyusutan_desc_prk_parent[$i]->value,
                    'beban_mat' => (float)$beban_mat_form_penyusutan_ketetapan[$i],
                    'beban_mat_update' => ((float)$form_penyusutan_beban_mat_update[$i]->value),
                    //'cash_oth' => 0,
                    'ijin_proses' => 0,
                    'ijin_proses_update' => 0,
                    // 'total_year_estimate' => (float)$form_penyusutan_total_year_estimate[$i]->value,
                    //'disburse' => $disburse,
                    'disburse_sd_bulan' => (float)$disburse_sd_bulan,
                    'disburse_sd_bulan_realisasi' => $get_actuals,
                    'estimate_realisasi' => $get_actuals + $get_commitments,
                );
                array_push($daftar_prk_kegiatan_form_penyusutan, $temp);

                $daftar_prk_inti_form_penyusutan[$inti]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_inti_form_penyusutan[$inti]['beban_mat_update'] += $temp['beban_mat_update'];
                // $daftar_prk_inti_form_penyusutan[$inti]['total_year_estimate'] += $temp['total_year_estimate'];

                $daftar_prk_parent_form_penyusutan[$parent]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_parent_form_penyusutan[$parent]['beban_mat_update'] += $temp['beban_mat_update'];
                // $daftar_prk_parent_form_penyusutan[$parent]['total_year_estimate'] += $temp['total_year_estimate'];
            }

            $dataparent['form_penyusutan'] = $daftar_prk_parent_form_penyusutan;
            $datainti['form_penyusutan'] = $daftar_prk_inti_form_penyusutan;
            $datakegiatan['form_penyusutan'] = $daftar_prk_kegiatan_form_penyusutan;

        } //end of cek request draft Penyusutan
        //End of query form Penyusutan

        //Start form Bahan Bakar
        if($input_draft_form_bahan_bakar){
            $jenis_bahan_bakar = [
                [ 'name' => 'HSD','description' => 'HSD','beban_mat1' => 'AO', 'beban_mat2' => 'AQ','beban_mat3' => null,'cash_oth1' => 'AW', 'cash_oth2' => 'AX', 'cash_oth3' => null ],
                [ 'name' => 'MFO','description' => 'MFO','beban_mat1' => 'AO', 'beban_mat2' => 'AQ','beban_mat3' => null, 'cash_oth1' => 'AW', 'cash_oth2' => 'AX', 'cash_oth3' => null ],
                [ 'name' => 'IDO','description' => 'IDO','beban_mat1' => 'AO', 'beban_mat2' => 'AQ','beban_mat3' => null, 'cash_oth1' => 'AW', 'cash_oth2' => 'AX', 'cash_oth3' => null ],
                [ 'name' => 'GAS ALAM','description' => 'Biaya bahan bakar - Gas alam','beban_mat1' => 'AO', 'beban_mat2' => 'AQ','beban_mat3' => null,'cash_oth1' => 'AW', 'cash_oth2' => 'AX', 'cash_oth3' => null ],
                [ 'name' => 'BATUBARA','description' => 'Batubara','beban_mat1' => 'AO', 'beban_mat2' => 'AQ','beban_mat3' => null,'cash_oth1' => 'AW', 'cash_oth2' => 'AX', 'cash_oth3' => null ],
                // [ 'name' => 'MINYAK PELUMAS','description' => 'Biaya bahan bakar - Minyak pelumas','beban_mat1' => 'AS', 'beban_mat2' => null,'beban_mat3' => null,'cash_oth1' => 'AY', 'cash_oth2' => null, 'cash_oth3' => null ],
                [ 'name' => 'LAIN-LAIN','description' => 'Biaya bahan bakar - Minyak pelumas','beban_mat1' => 'AS', 'beban_mat2' => null,'beban_mat3' => null,'cash_oth1' => 'AY', 'cash_oth2' => null, 'cash_oth3' => null ],
                [ 'name' => 'LAIN-LAIN','description' => 'Biaya bahan bakar - Kimia','beban_mat1' => 'AT', 'beban_mat2' => null,'beban_mat3' => null,'cash_oth1' => 'AZ', 'cash_oth2' => null, 'cash_oth3' => null ],
                // [ 'name' => 'KIMIA','description' => 'Biaya bahan bakar - Kimia','beban_mat1' => 'AT', 'beban_mat2' => null,'beban_mat3' => null,'cash_oth1' => 'AZ', 'cash_oth2' => null, 'cash_oth3' => null ],
                [ 'name' => 'RETRIBUSI','description' => 'Retribusi','beban_mat1' => 'AN', 'beban_mat2' => null,'beban_mat3' => null,'cash_oth1' => 'AV', 'cash_oth2' => null, 'cash_oth3' => null ],
                // [ 'name' => 'EP','description' => 'EP','beban_mat1' => 'AO', 'beban_mat2' => 'AQ', 'beban_mat3' => 'AN', 'cash_oth1' => 'AW', 'cash_oth2' => 'AX', 'cash_oth3' => 'AV' ],
                [ 'name' => 'EP','description' => 'EP','beban_mat1' => 'AU', 'beban_mat2' => null, 'beban_mat3' => null, 'cash_oth1' => null, 'cash_oth2' => null, 'cash_oth3' => null ],
            ];

            foreach($jenis_bahan_bakar as $jenis){
                $daftar_prk_kegiatan_form_bahan_bakar = array();
                $daftar_prk_inti_form_bahan_bakar = array();
                $daftar_prk_parent_form_bahan_bakar = array();

                //ambil data report dashboard dinamis
                $pgdl_report_dashboard_page_id = 1;
                $jenis_id = 7;
                $pgdl_sheet_name = 'Database KIT (P+S+I)';

                $setting_form_bahan_bakar_prk_parent = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                                ->where('tahun', $input_tahun)
                                ->where('jenis_id', $jenis_id)
                                ->where('pgdl_sheet_name', $pgdl_sheet_name)
                                ->where('sequence', 1)
                                ->first();

                $setting_form_bahan_bakar_prk_inti = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                                ->where('tahun', $input_tahun)
                                ->where('jenis_id', $jenis_id)
                                ->where('pgdl_sheet_name', $pgdl_sheet_name)
                                ->where('sequence', 2)
                                ->first();

                $setting_form_bahan_bakar_no_prk_kegiatan = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', $pgdl_report_dashboard_page_id)
                                ->where('tahun', $input_tahun)
                                ->where('jenis_id', $jenis_id)
                                ->where('pgdl_sheet_name', $pgdl_sheet_name)
                                ->where('sequence', 3)
                                ->first();

                //end of ambil data report dashboard dinamis

                $form_bahan_bakar_prk_parent = $this->get_form_bahan_bakar_prk($input_draft_form_bahan_bakar,$int_input_distrik, $setting_form_bahan_bakar_prk_parent->kolom, $jenis,"parent");
                foreach ($form_bahan_bakar_prk_parent as $key => $value) {
                    $daftar_prk_parent_form_bahan_bakar[$value->value] = array(
                        'desc_prk_parent' => $jenis['description'],
                        // 'desc_prk_parent' => 'Bahan Bakar',
                        'beban_mat'     => 0,
                        'beban_mat_update'     => 0,
                        //'cash_oth'      => 0,
                        'ijin_proses'   => 0,
                        'ijin_proses_update'   => 0,
                        // 'total_year_estimate'   => 0,
                        //'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                        );
                }

                $form_bahan_bakar_prk_inti = $this->get_form_bahan_bakar_prk($input_draft_form_bahan_bakar,$int_input_distrik, $setting_form_bahan_bakar_prk_inti->kolom, $jenis,"inti");
                foreach ($form_bahan_bakar_prk_inti as $key => $value) {
                    $daftar_prk_inti_form_bahan_bakar[$value->value] = array(
                        'desc_prk_inti' => $jenis['description'],
                        'prk_parent'    => substr($value->value, 0, 6),
                        'beban_mat'     => 0,
                        'beban_mat_update'     => 0,
                        //'cash_oth'      => 0,
                        'ijin_proses'   => 0,
                        'ijin_proses_update'   => 0,
                        // 'total_year_estimate'   => 0,
                        //'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    );
                }

                // DEBUG:
                // if ($jenis['beban_mat1'] == 'AS') {
                //     dd( compact('jenis', 'form_bahan_bakar_prk_inti', 'daftar_prk_inti_form_bahan_bakar'));
                // }

                $form_bahan_bakar_prk_kegiatan = $this->get_form_bahan_bakar_prk($input_draft_form_bahan_bakar,$int_input_distrik, $setting_form_bahan_bakar_no_prk_kegiatan->kolom, $jenis,"kegiatan");
                $form_bahan_bakar_prk_kegiatan_update = $this->get_form_bahan_bakar_prk_update($input_draft_form_bahan_bakar,$int_input_distrik, $setting_form_bahan_bakar_no_prk_kegiatan->kolom, $jenis['name'],"kegiatan");


                foreach ($form_bahan_bakar_prk_kegiatan as $key => $value) {
                    $temp = array(
                        'prk_kegiatan'  => $value->value,
                        'desc_prk_kegiatan' => $jenis['description'],
                        'prk_inti'    => substr($value->value, 0, 8),
                        'beban_mat'     => 0,
                        'beban_mat_update'     => 0,
                        //'cash_oth'      => 0,
                        'ijin_proses'   => 0,
                        'ijin_proses_update'   => 0,
                        // 'total_year_estimate'   => 0,
                        'disburse_sd_bulan' => 0,
                        'disburse_sd_bulan_realisasi' => 0,
                        //'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    );
                    //dd($jenis);

                    $beban_mat1 = $this->get_form_bahan_bakar_per_prk_ketetapan($input_draft_form_bahan_bakar,$int_input_distrik, $jenis['beban_mat1'], $jenis['name'],$setting_form_bahan_bakar_no_prk_kegiatan->kolom,$value->value);
                    $beban_mat1_update = $this->get_form_bahan_bakar_per_prk_update($input_draft_form_bahan_bakar,$int_input_distrik, $jenis['beban_mat1'], $jenis['name'],$setting_form_bahan_bakar_no_prk_kegiatan->kolom,$value->value);

                    $temp['beban_mat'] += (float) array_sum($beban_mat1);
                    $temp['beban_mat_update'] += (float) array_sum($beban_mat1_update);

                    $ddebug[] = [$jenis, $temp, $beban_mat1];

                    if($jenis['beban_mat2']!= null) {
                        // $beban_mat2 = $this->get_form_bahan_bakar_per_prk_ketetapan($input_draft_form_bahan_bakar,$int_input_distrik, $jenis['beban_mat2'], $jenis['name'],'J',$value->value);
                        // $beban_mat2_update = $this->get_form_bahan_bakar_per_prk_update($input_draft_form_bahan_bakar,$int_input_distrik, $jenis['beban_mat2'], $jenis['name'],'J',$value->value);
                        $beban_mat2 = $this->get_form_bahan_bakar_per_prk_ketetapan($input_draft_form_bahan_bakar,$int_input_distrik, $jenis['beban_mat2'], $jenis['name'],$setting_form_bahan_bakar_no_prk_kegiatan->kolom,$value->value);
                        $beban_mat2_update = $this->get_form_bahan_bakar_per_prk_update($input_draft_form_bahan_bakar,$int_input_distrik, $jenis['beban_mat2'], $jenis['name'],$setting_form_bahan_bakar_no_prk_kegiatan->kolom,$value->value);
                        $temp['beban_mat'] += (float) array_sum($beban_mat2);
                        $temp['beban_mat_update'] += (float) array_sum($beban_mat2_update);

                        //dd($beban_mat2_update);
                    }


                    if($jenis['beban_mat3']!= null) {
                        // $beban_mat3 = $this->get_form_bahan_bakar_per_prk_ketetapan($input_draft_form_bahan_bakar,$int_input_distrik, $jenis['beban_mat3'], $jenis['name'],'J',$value->value);
                        $beban_mat3 = $this->get_form_bahan_bakar_per_prk_ketetapan($input_draft_form_bahan_bakar,$int_input_distrik, $jenis['beban_mat3'], $jenis['name'],$setting_form_bahan_bakar_no_prk_kegiatan->kolom,$value->value);
                        $temp['beban_mat'] -= (float) array_sum($beban_mat3);

                        // $beban_mat3_update = $this->get_form_bahan_bakar_per_prk_update($input_draft_form_bahan_bakar,$int_input_distrik, $jenis['beban_mat3'], $jenis['name'],'J',$value->value);
                        $beban_mat3_update = $this->get_form_bahan_bakar_per_prk_update($input_draft_form_bahan_bakar,$int_input_distrik, $jenis['beban_mat3'], $jenis['name'],$setting_form_bahan_bakar_no_prk_kegiatan->kolom,$value->value);
                        $temp['beban_mat_update'] -= (float) array_sum($beban_mat3_update);
                    }

                    //$cash_oth1 = $this->get_form_bahan_bakar_per_prk($input_draft_form_bahan_bakar,$int_input_distrik, $jenis['cash_oth1'], $jenis['name'],'J',$value->value);
                    //$temp['cash_oth'] += (float) array_sum($cash_oth1);

                    //if($jenis['cash_oth2']!= null) {
                    //    $cash_oth2 = $this->get_form_bahan_bakar_per_prk($input_draft_form_bahan_bakar,$int_input_distrik, $jenis['cash_oth2'], $jenis['name'],'J',$value->value);
                    //    $temp['cash_oth'] += (float) array_sum($cash_oth2);
                    //}

                    //if($jenis['cash_oth3']!= null) {
                    //    $cash_oth3 = $this->get_form_bahan_bakar_per_prk($input_draft_form_bahan_bakar,$int_input_distrik, $jenis['cash_oth3'], $jenis['name'],'J',$value->value);
                    //    $temp['cash_oth'] -= (float) array_sum($cash_oth1);
                    //}

                    // $temp['total_year_estimate'] += $temp['beban_mat'];

                    $bln = array();
                    $disburse_sd_bulan = 0;
                    $daftar_bulan = $this->get_form_bahan_bakar_per_prk_update($input_draft_form_bahan_bakar,$int_input_distrik, 'H', $jenis['name'],$setting_form_bahan_bakar_no_prk_kegiatan->kolom,$value->value);
                    for($i_bulan = 1; $i_bulan<=$int_input_bulan; $i_bulan++){
                    $bln[$i_bulan] = $i_bulan;
                        foreach ($daftar_bulan as $key_bulan => $bulan) {
                            if($i_bulan == $bulan){
                                $temp['disburse_sd_bulan'] += (float) $beban_mat1_update[$key_bulan];
                                if($jenis['beban_mat2']!= null)
                                    $temp['disburse_sd_bulan'] += (float) $beban_mat2_update[$key_bulan];
                                if($jenis['beban_mat3']!= null)
                                    $temp['disburse_sd_bulan'] -= (float) $beban_mat3_update[$key_bulan];
                            }
                        }
                    }
                    $bln = implode(",", $bln);
                    $bln = "(".$bln.")";

                    // $get_disburse_sd_bulan_realisasi = $this->get_disburse_sd_bulan_realisasi($input_distrik->code1, $bln, $value->value);

                    // if($get_disburse_sd_bulan_realisasi) {
                    //     $temp['disburse_sd_bulan_realisasi'] = (float)$get_disburse_sd_bulan_realisasi[0]->sum;
                    // }
                    // else {
                    //     $temp['disburse_sd_bulan_realisasi'] = 0;
                    // }

                    // Change request Mei 2021, form input bahan bakar
                    $temp['disburse_sd_bulan'] = $this->form_input_bahan_bakar($int_input_bulan, $input_tahun, $input_distrik->id, $value->value);

                    $temp['disburse_sd_bulan_realisasi'] = (float)$this->get_actuals($input_distrik->code1, $value->value);
                    $temp['estimate_realisasi'] = (float)$this->get_commitments($input_distrik->code1, $value->value);

                    array_push($daftar_prk_kegiatan_form_bahan_bakar, $temp);

                }

                $dataparent[$jenis['description']] = $daftar_prk_parent_form_bahan_bakar;
                $datainti[$jenis['description']] = $daftar_prk_inti_form_bahan_bakar;
                $datakegiatan[$jenis['description']] = $daftar_prk_kegiatan_form_bahan_bakar;

                // DEBUG:
                // if ($jenis['beban_mat1'] == 'AS') {
                //     dd( compact('jenis', 'form_bahan_bakar_prk_kegiatan', 'daftar_prk_kegiatan_form_bahan_bakar', 'datakegiatan'));
                // }
            }

        } //end of cek request Bahan Bakar
        //End of query form Bahan Bakar
        // dd($dataparent, $datainti, $datakegiatan);

            if($request->download && $request->type){
                $judul='';
                if($request->type=='excel'){
                    Excel::create('Monitoring PRK AO', function ($excel) use($sb, $fase, $input_tahun, $input_sb, $input_distrik, $input_lokasi, $input_fase, $input_draft_rkau, $input_draft_form_6_reimburse, $input_draft_form_6_rutin, $dataparent, $datainti, $datakegiatan, $name_draft_rkau, $name_draft_form_6_reimburse, $name_draft_form_6_rutin, $name_draft_form_bahan_bakar, $name_draft_form_penyusutan, $nama_bln_dipilih) {
                            $excel->setTitle('Monitoring PRK AO');
                            $excel->setCreator('Laravel')->setCompany('Profio', 'PJB');
                            $excel->setDescription('Monitoring PRK AO');
                            $excel->sheet('Monitoring PRK AO', function ($sheet) use($sb, $fase, $input_tahun, $input_sb, $input_distrik, $input_lokasi, $input_fase, $input_draft_rkau, $input_draft_form_6_reimburse, $input_draft_form_6_rutin, $dataparent, $datainti, $datakegiatan, $name_draft_rkau, $name_draft_form_6_reimburse, $name_draft_form_6_rutin, $name_draft_form_bahan_bakar, $name_draft_form_penyusutan, $nama_bln_dipilih){
                                $sheet->setColumnFormat(array(
                                    'J' => '@'
                                ));
                                $sheet->loadView('output/monitoring-prk-ao-excel')
                                        ->with('sb', $sb)
                                        ->with('fase', $fase)
                                        ->with('input_tahun', $input_tahun)
                                        ->with('input_sb', $input_sb)
                                        ->with('input_distrik', $input_distrik)
                                        ->with('input_lokasi', $input_lokasi)
                                        ->with('input_fase', $input_fase)
                                        ->with('input_draft_rkau', $input_draft_rkau)
                                        ->with('input_draft_form_6_reimburse', $input_draft_form_6_reimburse)
                                        ->with('input_draft_form_6_rutin', $input_draft_form_6_rutin)
                                        // ->with('input_draft_form_10_pk', $input_draft_form_10_pk)
                                        // ->with('input_draft_form_10_pu', $input_draft_form_10_pu)
                                        // ->with('input_draft_form_10_pln', $input_draft_form_10_pln)
                                        ->with('dataparent', $dataparent)
                                        ->with('datainti', $datainti)
                                        ->with('datakegiatan', $datakegiatan)
                                        ->with('name_draft_rkau', $name_draft_rkau)
                                        ->with('name_draft_form_6_reimburse', $name_draft_form_6_reimburse)
                                        ->with('name_draft_form_6_rutin', $name_draft_form_6_rutin)
                                        ->with('name_draft_form_bahan_bakar', $name_draft_form_bahan_bakar)
                                        ->with('name_draft_form_penyusutan', $name_draft_form_penyusutan)
                                        ->with('nama_bln_dipilih', $nama_bln_dipilih);
                            });
                        })->download('xlsx');
                }
            }
            // else {
            //     return view('output/loader-ellipse', compact('sb', 'fase', 'input_tahun', 'input_sb', 'input_distrik', 'input_lokasi', 'input_fase', 'input_draft_rkau', 'input_draft_form_6_reimburse', 'input_draft_form_6_rutin', 'input_draft_form_10_pk', 'input_draft_form_10_pu', 'input_draft_form_10_pln', 'dataparent', 'datainti','datakegiatan'));
            // }

        }

        return view('output/monitoring-prk-ao', compact('sb', 'fase', 'input_tahun', 'input_sb', 'input_distrik', 'input_lokasi', 'input_fase', 'input_draft_rkau', 'input_draft_form_6_reimburse', 'input_draft_form_6_rutin', 'input_draft_form_penyusutan', 'input_draft_form_bahan_bakar','dataparent', 'datainti','datakegiatan', 'distrik', 'lokasi','tahun', 'draft_form_rkau', 'draft_form_penyusutan', 'draft_form_6_reimburse', 'draft_form_6_rutin', 'draft_form_bahan_bakar', 'name_draft_rkau', 'name_draft_form_6_reimburse', 'name_draft_form_6_rutin', 'name_draft_form_bahan_bakar', 'name_draft_form_penyusutan', 'nama_bln_dipilih'));
    }


    // Change request Mei 2021, Form input bahan bakar
    function form_input_bahan_bakar($bulan, $tahun, $distrik_id, $value) {

        $data = DB::select("select e.value from excel_data_input_bahan_bakar e join 
                                file_input_bahan_bakar f on f.id = e.file_input_bahan_bakar_id 
                                where f.tahun = '".$tahun."'
                                and e.prk = '".$value."'
                                and e.distrik_id = ".$distrik_id." 
                                and e.month <= ".$bulan."
                             ");
        if (empty($data)) {
            return 0;
        } else {
            $result = 0;
            foreach ($data as $key => $value) {
                $result += $value->value;
            }

            return $result;
        }
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

    public function ajax_draft_form_bahan_bakar($id_lokasi, $id_tahun)
    {
        $draft_form_bahan_bakar = DB::select("select distinct f.id, f.draft_versi
                                    from file_imports f
                                    join templates t on f.template_id = t.id
                                    join excel_datas e on e.file_import_id = f.id
                                    where t.jenis_id=7 and e.lokasi_id = ".$id_lokasi." and t.tahun= ".$id_tahun."
                                    group by f.id, f.draft_versi;");

        return json_encode($draft_form_bahan_bakar);
    }

    public function ajax_draft_form_penyusutan($id_lokasi, $id_tahun)
    {
        $draft_form_penyusutan = DB::select("select distinct f.id, f.draft_versi
                                    from file_imports f
                                    join templates t on f.template_id = t.id
                                    join excel_datas e on e.file_import_id = f.id
                                    where t.jenis_id=9 and e.lokasi_id = ".$id_lokasi." and t.tahun= ".$id_tahun."
                                    group by f.id, f.draft_versi;");

        return json_encode($draft_form_penyusutan);
    }
    */

    //----------------------------------------- New Functions -----------------------------------------------------

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
        // $draft_rkau = DB::select("select distinct f.id, f.draft_versi
        //                             from file_imports f
        //                             join templates t on f.template_id = t.id
        //                             join excel_datas e on e.file_import_id = f.id
        //                             join lokasi l on l.id = e.lokasi_id
        //                             where t.jenis_id=1 and l.distrik_id = ".$id_distrik." and t.tahun= ".$id_tahun."
        //                             group by f.id, f.draft_versi;");

    }

    // public function get_drafts_ketetapan_ids_return_file_import_id($id_jenis, $id_distrik, $id_tahun)
    // {
    //     $drafts =
    //       DB::select("select distinct f.id, f.file_import_id, f.draft_versi, f.name
    //           from file_imports_ketetapan f
    //           join templates t on f.template_id = t.id
    //           join excel_datas_ketetapan e on e.file_import_ketetapan_id = f.id
    //           join lokasi l on l.id = e.lokasi_id
    //           where t.jenis_id=".$id_jenis." and l.distrik_id = ".$id_distrik." and t.tahun= ".$id_tahun."
    //           group by f.id, f.file_import_id, f.draft_versi, f.name");

    //       if($drafts){
    //         $res = [];
    //         $i=0;
    //         foreach ($drafts as $key => $value) {
    //           $res[$i] = $value->file_import_id;
    //           // $res[$i] = $value->id;
    //           $i++;
    //         }
    //         $res = implode(",", $res);
    //         //dd(var_dump($new));
    //         $res = "(".$res.")";
    //         return $res;
    //       }
    //       else return $drafts;
    // }

    // public function get_drafts_rkau_ids($id_distrik, $id_tahun, $id_fase)
    // {
    //   $draft_rkau =
    //   DB::select("select distinct f.id, f.file_import_id, f.draft_versi, f.name
    //       from file_imports_ketetapan f
    //       join templates t on f.template_id = t.id
    //       join excel_datas_ketetapan e on e.file_import_ketetapan_id = f.id
    //       join lokasi l on l.id = e.lokasi_id
    //       where t.jenis_id=1 and l.distrik_id = ".$id_distrik." and t.tahun= ".$id_tahun."
    //       group by f.id, f.file_import_id, f.draft_versi, f.name");
    //       //return json_encode($draft_rkau);
    //       if($draft_rkau){
    //         $res = [];
    //         $i=0;
    //         foreach ($draft_rkau as $key => $value) {
    //           $res[$i] = $value->id;
    //           $i++;
    //         }
    //         $res = implode(",", $res);
    //         //dd(var_dump($new));
    //         $res = "(".$res.")";
    //         return $res;
    //       }
    //       else return $draft_rkau;
    //     // $draft_rkau = DB::select("select distinct f.id, f.draft_versi
    //     //                             from file_imports f
    //     //                             join templates t on f.template_id = t.id
    //     //                             join excel_datas e on e.file_import_id = f.id
    //     //                             join lokasi l on l.id = e.lokasi_id
    //     //                             where t.jenis_id=1 and l.distrik_id = ".$id_distrik." and t.tahun= ".$id_tahun."
    //     //                             group by f.id, f.draft_versi;");
    //
    // }
    //
    // public function get_drafts_form_6_reimburse_ids($id_distrik, $id_tahun, $id_fase){
    //
    //   $draft_form_6_reimburse =
    //   DB::select("select distinct f.id, f.file_import_id, f.draft_versi, f.name
    //       from file_imports_ketetapan f
    //       join templates t on f.template_id = t.id
    //       join excel_datas_ketetapan e on e.file_import_ketetapan_id = f.id
    //       join lokasi l on l.id = e.lokasi_id
    //       where t.jenis_id=2 and l.distrik_id = ".$id_distrik." and t.tahun= ".$id_tahun."
    //       group by f.id, f.file_import_id, f.draft_versi, f.name");
    //
    //               if($draft_form_6_reimburse){
    //                 $res = [];
    //                 $i=0;
    //                 foreach ($draft_form_6_reimburse as $key => $value) {
    //                   $res[$i] = $value->id;
    //                   $i++;
    //                 }
    //                 $res = implode(",", $res);
    //                 //dd(var_dump($new));
    //                 $res = "(".$res.")";
    //                 //dd($res);
    //                 //return $draft_form_6_reimburse;
    //                 return $res;
    //               }
    //               else {
    //                 return $draft_form_6_reimburse;
    //               }
    //   /*$draft_form_6_reimburse = DB::select("select distinct f.id
    //                               from file_imports f
    //                               join templates t on f.template_id = t.id
    //                               join excel_datas e on e.file_import_id = f.id
    //                               join lokasi l on l.id = e.lokasi_id
    //                               where t.jenis_id=2 and l.distrik_id = ".$id_distrik." and t.tahun= ".$id_tahun."
    //                               group by f.id, f.draft_versi;");
    //   /*
    //   $res = array_map(function ($draft_form_6_reimburse){
    //     return (array)$draft_form_6_reimburse;
    //   }, $res);
    //
    //   $res = json_decode(json_encode($$draft_form_6_reimburse), true);
    //   */
    //
    // }
    //
    // public function get_drafts_form_6_rutin_ids($id_distrik, $id_tahun, $id_fase)
    // {
    //   // $draft_form_6_rutin =
    //   //     DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
    //   //     from file_imports f
    //   //     join templates t on f.template_id = t.id
    //   //     join excel_datas e on e.file_import_id = f.id
    //   //     join file_approval fa on fa.file_import_id = f.id
    //   //     join file_approval_status fas on fas.id = fa.file_approval_status_id
    //   //     join approval app on app.id = fa.approval_id
    //   //     join lokasi l on l.id = e.lokasi_id
    //   //     where t.jenis_id=3 and l.distrik_id = ".$id_distrik." and t.tahun= ".$id_tahun."
    //   //     and app.fase_id = ".$id_fase."
    //   //     and fa.file_approval_status_id = 4
    //   //     and app.urutan = (select max(urutan) from approval where approval.fase_id = ".$id_fase.")
    //   //     group by f.id, f.draft_versi, f.name, fas.name");
    //
    //   // //jika ada draft yg sudah diapproved oleh urutan terakhir pada fase tsb (misal GM/Kadiv Anggaran)
    //   // if($draft_form_6_rutin) {
    //   //   if($draft_form_6_rutin){
    //   //     $res = [];
    //   //     $i=0;
    //   //     foreach ($draft_form_6_rutin as $key => $value) {
    //   //       $res[$i] = $value->id;
    //   //       $i++;
    //   //     }
    //   //     $res = implode(",", $res);
    //   //     $res = "(".$res.")";
    //   //     return $res;
    //   //   }
    //   //   else {
    //   //     return $draft_form_6_rutin;
    //   //   }
    //   // }
    //   // else {
    //   // // ambil draft yg status nya draft/submitted/approved (tetapi belum urutan terakhir)
    //   //     $draft_form_6_rutin =
    //   //         DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
    //   //             from file_imports f
    //   //             join templates t on f.template_id = t.id
    //   //             join excel_datas e on e.file_import_id = f.id
    //   //             join file_approval fa on fa.file_import_id = f.id
    //   //             join file_approval_status fas on fas.id = fa.file_approval_status_id
    //   //             join approval app on app.id = fa.approval_id
    //   //             join lokasi l on l.id = e.lokasi_id
    //   //             where t.jenis_id=3 and l.distrik_id = ".$id_distrik." and t.tahun= ".$id_tahun."
    //   //             and app.fase_id = ".$id_fase."
    //   //             and (fa.file_approval_status_id = 1 or fa.file_approval_status_id = 2 or fa.file_approval_status_id = 4)
    //   //             group by f.id, f.draft_versi, f.name, fas.name");
    //
    //   //             if($draft_form_6_rutin){
    //   //               $res = [];
    //   //               $i=0;
    //   //               foreach ($draft_form_6_rutin as $key => $value) {
    //   //                 $res[$i] = $value->id;
    //   //                 $i++;
    //   //               }
    //   //               $res = implode(",", $res);
    //   //               $res = "(".$res.")";
    //   //               return $res;
    //   //             }
    //   //             else {
    //   //               return $draft_form_6_rutin;
    //   //             }
    //   // }
    //
    //     $draft_form_6_rutin =
    //         DB::select("select distinct f.id, f.file_import_id, f.draft_versi, f.name
    //             from file_imports_ketetapan f
    //             join templates t on f.template_id = t.id
    //             join excel_datas_ketetapan e on e.file_import_ketetapan_id = f.id
    //             join lokasi l on l.id = e.lokasi_id
    //             where t.jenis_id=3 and l.distrik_id = ".$id_distrik." and t.tahun= ".$id_tahun."
    //             group by f.id, f.file_import_id, f.draft_versi, f.name");
    //
    //     if($draft_form_6_rutin){
    //         $res = [];
    //         $i=0;
    //         foreach ($draft_form_6_rutin as $key => $value) {
    //             $res[$i] = $value->file_import_id;
    //             $i++;
    //         }
    //         $res = implode(",", $res);
    //         $res = "(".$res.")";
    //         return $res;
    //     }
    //     else {
    //         return $draft_form_6_rutin;
    //     }
    //
    //     // $draft_form_6_rutin = DB::select("select distinct f.id, f.draft_versi
    //     //                             from file_imports f
    //     //                             join templates t on f.template_id = t.id
    //     //                             join excel_datas e on e.file_import_id = f.id
    //     //                             join lokasi l on l.id = e.lokasi_id
    //     //                             where t.jenis_id=3 and l.distrik_id = ".$id_distrik." and t.tahun= ".$id_tahun."
    //     //                             group by f.id, f.draft_versi;");
    // }
    //
    // public function get_drafts_form_bahan_bakar_ids($id_distrik, $id_tahun, $id_fase)
    // {
    //       $draft_form_bahan_bakar =
    //       DB::select("select distinct f.id, f.file_import_id, f.draft_versi, f.name
    //           from file_imports_ketetapan f
    //           join templates t on f.template_id = t.id
    //           join excel_datas_ketetapan e on e.file_import_ketetapan_id = f.id
    //           join lokasi l on l.id = e.lokasi_id
    //           where t.jenis_id=7 and l.distrik_id = ".$id_distrik." and t.tahun= ".$id_tahun."
    //           group by f.id, f.file_import_id, f.draft_versi, f.name");
    //
    //               if($draft_form_bahan_bakar){
    //                 $res = [];
    //                 $i=0;
    //                 foreach ($draft_form_bahan_bakar as $key => $value) {
    //                   $res[$i] = $value->id;
    //                   $i++;
    //                 }
    //                 $res = implode(",", $res);
    //                                            $res = "(".$res.")";
    //                 return $res;
    //               }
    //               else {
    //                 return $draft_form_bahan_bakar;
    //               }
    //     // $draft_form_bahan_bakar = DB::select("select distinct f.id, f.draft_versi
    //     //                             from file_imports f
    //     //                             join templates t on f.template_id = t.id
    //     //                             join excel_datas e on e.file_import_id = f.id
    //     //                             join lokasi l on l.id = e.lokasi_id
    //     //                             where t.jenis_id=7 and l.distrik_id = ".$id_distrik." and t.tahun= ".$id_tahun."
    //     //                             group by f.id, f.draft_versi;");
    //
    // }
    //
    // public function get_drafts_form_penyusutan_ids($id_distrik, $id_tahun, $id_fase)
    // {
    //   $draft_form_penyusutan =
    //   DB::select("select distinct f.id, f.file_import_id, f.draft_versi, f.name
    //       from file_imports_ketetapan f
    //       join templates t on f.template_id = t.id
    //       join excel_datas_ketetapan e on e.file_import_ketetapan_id = f.id
    //       join lokasi l on l.id = e.lokasi_id
    //       where t.jenis_id=9 and l.distrik_id = ".$id_distrik." and t.tahun= ".$id_tahun."
    //       group by f.id, f.file_import_id, f.draft_versi, f.name");
    //
    //               if($draft_form_penyusutan){
    //                 $res = [];
    //                 $i=0;
    //                 foreach ($draft_form_penyusutan as $key => $value) {
    //                   $res[$i] = $value->id;
    //                   $i++;
    //                 }
    //                 $res = implode(",", $res);
    //                 $res = "(".$res.")";
    //                 return $res;
    //               }
    //               else {
    //                 return $draft_form_penyusutan;
    //               }
    //     // $draft_form_penyusutan = DB::select("select distinct f.id, f.draft_versi
    //     //                             from file_imports f
    //     //                             join templates t on f.template_id = t.id
    //     //                             join excel_datas e on e.file_import_id = f.id
    //     //                             join lokasi l on l.id = e.lokasi_id
    //     //                             where t.jenis_id=9 and l.distrik_id = ".$id_distrik." and t.tahun= ".$id_tahun."
    //     //                             group by f.id, f.draft_versi;");
    //
    // }

    // kodingan lama pljprk

    // public function get_disburse_sd_bulan_realisasi($dstrct_code, $bln, $no_prk){
    //     // dd($dstrct_code, $no_prk, $bln);
    //   $total_disburse = DB::select("select sum(tran_amount)
    //                                 from pgdl_pljprk_ao
    //                                 where dstrct_code like '".$dstrct_code."'
    //                                 and project_no like '".substr($no_prk,2)."'
    //                                 and months IN ".$bln." group by project_no;");

    //   return $total_disburse;
    // }

    // public function get_disburse_sd_bulan_realisasi_rkau($dstrct_code, $bln, $no_prk){
    //     // dd($dstrct_code, $no_prk, $bln);
    //   $total_disburse = DB::select("select sum(tran_amount)
    //                                 from pgdl_pljprk_ao
    //                                 where dstrct_code like '".$dstrct_code."'
    //                                 and project_no like '".$no_prk."'
    //                                 and months IN ".$bln." group by project_no;");
    //   // dd($total_disburse);
    //   return $total_disburse;
    // }

    // end kodingan lama pljprk

    public function get_actuals($dstrct_code, $no_prk){
      $total_disburse = DB::select("select actuals
                                    from pbc_ao
                                    where dstrct_code like '".$dstrct_code."'
                                    and prk_kegiatan like '".$no_prk."'
                                    ");

      return $total_disburse ? $total_disburse[0]->actuals : 0 ;
    }

    public function get_commitments($dstrct_code, $no_prk){
      $total_disburse = DB::select("select commitments
                                    from pbc_ao
                                    where dstrct_code like '".$dstrct_code."'
                                    and prk_kegiatan like '".$no_prk."'
                                    ");

      return $total_disburse ? $total_disburse[0]->commitments : 0 ;
    }

    //----------------------------------------- End of New Functions -----------------------------------------------------

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

    function get_names($template_id, $file_id){
      // $names = DB::select("select f.draft_versi, f.name from file_imports_ketetapan f
      //                        join templates t on f.template_id = t.id
      //                        where f.id IN ".$file_id." and t.jenis_id = ".$template_id.";");
      $namesdb = DB::select("select f.draft_versi, f.name from file_imports_ketetapan f
                             where f.id IN ".$file_id);

      // $file_id = str_replace(array( '(', ')' ), '', $file_id);
      // dd($file_id);
      // $fik = FileImportKetetapan::whereIn('id', $file_id);
      // dd($names);
      $names = "";
      $n = 0;
      foreach ($namesdb as $key => $value) {
        if($n == 0) {
            $names = $value->draft_versi.' - '.$value->name;
        }
        else {
            $names = $value->draft_versi.' - '.$value->name.", ".$names;
        }

        $n++;
      }
      // $names = $fik->draft_versi.' '.$fik->name;

      return $names;
    }

    function get_form_6($file_import_id, $distrik_id, $kolom){
        // $query = DB::select("select e.row, e.value
        //                       from excel_datas_ketetapan e
        //                       join sheets s on s.id = e.sheet_id
        //                       join lokasi l on l.id = e.lokasi_id
        //                       where s.name like 'I-Form 6'
        //                       and e.file_import_ketetapan_id IN ".$file_import_id."
        //                       and l.distrik_id = ".$distrik_id."
        //                       and e.kolom = '".$kolom."' order by e.row;");
        // dd($file_import_id, $distrik_id, $kolom);
        $query = DB::select("select e.row, e.value
                              from pgdl_excel_datas_revisi e
                              join pgdl_sheets s on s.id = e.pgdl_sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                              join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                              where s.name like 'I-Form 6'
                              and fk.id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom = '".$kolom."' order by e.row;");
        return $query;
    }

    function cek_no_prk_form_rkau_ketetapan($file_import_id, $distrik_id, $kolom, $no_prk, $sheet){
        //cek no PRK form rkau ketetapan
        $query = DB::select("select e.row, e.value
                              from excel_datas_ketetapan e
                              join sheets s on s.id = e.sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              where s.name like '".$sheet."'
                              and e.file_import_ketetapan_id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom = '".$kolom."'
                              and value = '".$no_prk."'
                              and e.row > 12
                              order by e.row;");

        $result = array();
        foreach ($query as $value) {
            $result[$value->row] = $value->value;
        }

        return $query;
    }

    function cek_no_prk_form_6_ketetapan($file_import_id, $distrik_id, $kolom, $no_prk){
        //cek no PRK form 6 ketetapan
        $query = DB::select("select e.row, e.value
                              from excel_datas_ketetapan e
                              join sheets s on s.id = e.sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              where s.name like 'I-Form 6'
                              and e.file_import_ketetapan_id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom = '".$kolom."' and value = '".$no_prk."' order by e.row;");
        // dd($file_import_id, $distrik_id, $kolom);
        // $query = DB::select("select e.row, e.value
        //                       from pgdl_excel_datas_revisi e
        //                       join pgdl_sheets s on s.id = e.pgdl_sheet_id
        //                       join lokasi l on l.id = e.lokasi_id
        //                       join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
        //                       join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
        //                       where s.name like 'I-Form 6'
        //                       and fk.id IN ".$file_import_id."
        //                       and l.distrik_id = ".$distrik_id."
        //                       and e.kolom = '".$kolom."' and value = '".$no_prk."' order by e.row;");

        return $query;
    }

    function cek_no_prk_form_penyusutan_ketetapan($file_import_id, $distrik_id, $kolom, $no_prk){
        //cek no PRK form penyusutan ketetapan
        $query = DB::select("select e.row, e.value
                              from excel_datas_ketetapan e
                              join sheets s on s.id = e.sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              where s.name like 'I-Penyusutan'
                              and e.file_import_ketetapan_id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom = '".$kolom."' and value = '".$no_prk."' order by e.row;");

        return $query;
    }

    function get_form_rkau_ketetapan($file_import_id, $distrik_id, $kolom, $kolom_prk, $no_prk, $sheet){
        // dump($file_import_id, $distrik_id, $kolom, $kolom_prk, $no_prk, $sheet);
        $query = DB::select("select e.row, e.value
                              from excel_datas_ketetapan e
                              join sheets s on s.id = e.sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              where s.name like '".$sheet."'
                              and e.file_import_ketetapan_id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom = '".$kolom."' and e.row IN
                              (select e.row
                              from excel_datas_ketetapan e
                              join sheets s on s.id = e.sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              where s.name like '".$sheet."'
                              and e.file_import_ketetapan_id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom = '".$kolom_prk."' and e.value like '".$no_prk."')
                              order by e.row LIMIT 1;");

        // $query = DB::select("select e.row, e.value
        //                       from excel_datas_ketetapan e
        //                       join sheets s on s.id = e.sheet_id
        //                       join lokasi l on l.id = e.lokasi_id
        //                       where s.name like '".$sheet."'
        //                       and e.file_import_ketetapan_id IN ".$file_import_id."
        //                       and l.distrik_id = ".$distrik_id."
        //                       and e.kolom = '".$kolom."'
        //                       and e.row > 12
        //                       order by e.row;");

        $result = array();
        foreach ($query as $value) {
            $result[$value->row] = $value->value;
        }

        return $query;
    }

    function get_form_6_ketetapan($file_import_id, $distrik_id, $kolom, $kolom_prk, $no_prk){
        // $query = DB::select("select e.row, e.value
        //                       from excel_datas_ketetapan e
        //                       join sheets s on s.id = e.sheet_id
        //                       join lokasi l on l.id = e.lokasi_id
        //                       where s.name like 'I-Form 6'
        //                       and e.file_import_ketetapan_id IN ".$file_import_id."
        //                       and l.distrik_id = ".$distrik_id."
        //                       and e.kolom = '".$kolom."' order by e.row;");

        $query = DB::select("select e.row, e.value
                              from excel_datas_ketetapan e
                              join sheets s on s.id = e.sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              where s.name like 'I-Form 6'
                              and e.file_import_ketetapan_id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom = '".$kolom."' and e.row IN
                              (select e.row
                              from excel_datas_ketetapan e
                              join sheets s on s.id = e.sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              where s.name like 'I-Form 6'
                              and e.file_import_ketetapan_id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom = '".$kolom_prk."' and e.value like '".$no_prk."')
                              order by e.row LIMIT 1;");

        return $query;
    }

    function get_form_penyusutan_ketetapan($file_import_id, $distrik_id, $kolom, $kolom_prk, $no_prk){
        // $query = DB::select("select e.row, e.value
        //                       from excel_datas_ketetapan e
        //                       join sheets s on s.id = e.sheet_id
        //                       join lokasi l on l.id = e.lokasi_id
        //                       where s.name like 'I-Penyusutan'
        //                       and e.file_import_ketetapan_id IN ".$file_import_id."
        //                       and l.distrik_id = ".$distrik_id."
        //                       and e.kolom = '".$kolom."' order by e.row;");

        $query = DB::select("select e.row, e.value
                              from excel_datas_ketetapan e
                              join sheets s on s.id = e.sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              where s.name like 'I-Penyusutan'
                              and e.file_import_ketetapan_id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom = '".$kolom."' and e.row IN
                              (select e.row
                              from excel_datas_ketetapan e
                              join sheets s on s.id = e.sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              where s.name like 'I-Penyusutan'
                              and e.file_import_ketetapan_id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom = '".$kolom_prk."' and e.value like '".$no_prk."')
                              order by e.row LIMIT 1;");

        return $query;
    }

    function get_form_6_inti($file_import_id, $distrik_id, $kolom){
        // $query = DB::select("select distinct SUBSTRING(e.value,1,8) as value
        //                       from excel_datas_ketetapan e
        //                       join sheets s on s.id = e.sheet_id
        //                       join lokasi l on l.id = e.lokasi_id
        //                       where s.name like 'I-Form 6'
        //                       and e.file_import_ketetapan_id IN ".$file_import_id."
        //                       and l.distrik_id = ".$distrik_id."
        //                       and e.kolom = '".$kolom."'");

        $query = DB::select("select distinct SUBSTRING(e.value,1,8) as value
                              from pgdl_excel_datas_revisi e
                              join pgdl_sheets s on s.id = e.pgdl_sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                              join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                              where s.name like 'I-Form 6'
                              and fk.id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom = '".$kolom."'");

        //jumlah = 15
        //dd($kolom);
        return $query;
    }

    function get_form_6_parent($file_import_ids, $distrik_id, $kolom){
        // $query = DB::select("select distinct SUBSTRING(e.value,1,6) as value
        //                       from excel_datas_ketetapan e
        //                       join sheets s on s.id = e.sheet_id
        //                       join lokasi l on l.id = e.lokasi_id
        //                       where s.name like 'I-Form 6'
        //                       and e.file_import_ketetapan_id IN ".$file_import_ids."
        //                       and l.distrik_id = ".$distrik_id."
        //                       and e.kolom = '".$kolom."'");

        $query = DB::select("select distinct SUBSTRING(e.value,1,6) as value
                              from pgdl_excel_datas_revisi e
                              join pgdl_sheets s on s.id = e.pgdl_sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                              join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                              where s.name like 'I-Form 6'
                              and fk.id IN ".$file_import_ids."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom = '".$kolom."'");

        return $query;
    }

    function get_form_6_disburse($file_import_id, $distrik_id, $kolom1, $kolom2){
        $query = DB::select("select sum(case when value = '' then 0 else value::float end) as value
                              from excel_datas e
                              join sheets s on s.id = e.sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              where s.name like 'I-Form 6'
                              and e.file_import_id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and (e.kolom = '".$kolom1."' or e.kolom = '".$kolom2."') group by e.row, e.file_import_id");
        // jumlah = 15
        //dd($query);
        return $query;
    }

//------------------------------------------------- Fungsi Update -----------------------------------------------------

    function get_form_6_update($file_import_id, $distrik_id, $kolom){

        // Query via tabel pgdl
        $query = DB::select("select e.row, e.value
                              from pgdl_excel_datas_revisi e
                              join pgdl_sheets s on s.id = e.pgdl_sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                              join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                              where s.name like 'I-Form 6'
                              and fk.id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom = '".$kolom."' order by e.row;");

        //Query via tabel excel datas
        // $query = DB::select("select e.row, e.value
        //                       from excel_datas e
        //                       join sheets s on s.id = e.sheet_id
        //                       join lokasi l on l.id = e.lokasi_id
        //                       where s.name like 'I-Form 6'
        //                       and e.file_import_id IN ".$file_import_id."
        //                       and l.distrik_id = ".$distrik_id."
        //                       and e.kolom = '".$kolom."' order by e.row;");

        return $query;
    }

/*
    function get_form_6_inti_update($file_import_id, $distrik_id, $kolom){

        //Query via tabel pgdl
        $query = DB::select("select distinct SUBSTRING(e.value,1,8) as value
                              from pgdl_excel_datas_revisi e
                              join pgdl_sheets s on s.id = e.pgdl_sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              where s.name like 'I-Form 6'
                              and e.pgdl_file_import_revisi_id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom = '".$kolom."'");


        //Query via tabel excel datas
        // $query = DB::select("select distinct SUBSTRING(e.value,1,8) as value
        //                       from excel_datas e
        //                       join sheets s on s.id = e.sheet_id
        //                       join lokasi l on l.id = e.lokasi_id
        //                       where s.name like 'I-Form 6'
        //                       and e.file_import_id IN ".$file_import_id."
        //                       and l.distrik_id = ".$distrik_id."
        //                       and e.kolom = '".$kolom."'");

        return $query;
    }

    function get_form_6_parent_update($file_import_ids, $distrik_id, $kolom){

        // Query via tabel pgdl
        // $query = DB::select("select distinct SUBSTRING(e.value,1,6) as value
        //                       from pgdl_excel_datas_revisi e
        //                       join pgdl_sheets s on s.id = e.pgdl_sheet_id
        //                       join lokasi l on l.id = e.lokasi_id
        //                       where s.name like 'I-Form 6'
        //                       and e.pgdl_file_import_revisi_id IN ".$file_import_ids."
        //                       and l.distrik_id = ".$distrik_id."
        //                       and e.kolom = '".$kolom."'");
        //

        //Query via tabel excel datas
        $query = DB::select("select distinct SUBSTRING(e.value,1,6) as value
                              from excel_datas e
                              join sheets s on s.id = e.sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              where s.name like 'I-Form 6'
                              and e.file_import_id IN ".$file_import_ids."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom = '".$kolom."'");

        return $query;
    }
*/
    function get_form_6_disburse_update($file_import_id, $distrik_id, $kolom1, $kolom2){
        // dump($file_import_id, $distrik_id, $kolom1, $kolom2);
        // Query via tabel pgdl
        $query = DB::select("select sum(case when value = '' then 0 else value::float end) as value
                              from pgdl_excel_datas_revisi e
                              join pgdl_sheets s on s.id = e.pgdl_sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                              join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                              where s.name like 'I-Form 6'
                              and fk.id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and (e.kolom = '".$kolom1."' or e.kolom = '".$kolom2."') group by e.row, fk.file_import_id order by e.row");


        //Query via tabel excel datas
// /        $query = DB::select("select sum(case when value = '' then 0 else value::float end) as value
//                               from excel_datas e
//                               join sheets s on s.id = e.sheet_id
//                               join lokasi l on l.id = e.lokasi_id
//                               where s.name like 'I-Form 6'
//                               and e.file_import_id IN ".$file_import_id."
//                               and l.distrik_id = ".$distrik_id."
//                               and (e.kolom = '".$kolom1."' or e.kolom = '".$kolom2."') group by e.row, e.file_import_id");

        return $query;
    }

    function get_form_rkau_update($file_import_id, $distrik_id, $sheet, $kolom){

      //Query via pgdl_excel_datas_revisi
        $query = DB::select("select e.row, e.value
                          from pgdl_excel_datas_revisi e
                          join pgdl_sheets s on s.id = e.pgdl_sheet_id
                          join lokasi l on l.id = e.lokasi_id
                          join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                          join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                          where s.name like '".$sheet."'
                          and fk.id IN ".$file_import_id."
                          and l.distrik_id = ".$distrik_id."
                          and e.kolom = '".$kolom."'
                          and e.row > 12
                         order by e.row");

        // //Query Via tabel excel_datas
        // $query = DB::select("select e.row, e.value
        //                     from excel_datas e
        //                     join sheets s on s.id = e.sheet_id
        //                     join lokasi l on l.id = e.lokasi_id
        //                     where s.name like '".$sheet."'
        //                     and e.file_import_id IN ".$file_import_id."
        //                     and l.distrik_id = ".$distrik_id."
        //                     and e.kolom = '".$kolom."'
        //                     and e.row > 12
        //                     order by e.row");

        $result = array();
        foreach ($query as $value) {
            $result[$value->row] = $value->value;
        }
        return $result;
    }


    function get_form_rkau_inti_update($file_import_id, $distrik_id, $sheet, $kolom){

      //Query via pgdl_excel_datas_revisi
      $query = DB::select("select distinct SUBSTRING(e.value,1,6) as value
                          from pgdl_excel_datas_revisi e
                          join pgdl_sheets s on s.id = e.pgdl_sheet_id
                          join lokasi l on l.id = e.lokasi_id
                          join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                          join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                          where s.name like '".$sheet."'
                          and fk.file_import_id IN ".$file_import_id."
                          and l.distrik_id = ".$distrik_id."
                          and e.kolom = '".$kolom."'
                          and e.row > 12");

        //Query Via tabel excel_datas
        // $query = DB::select("select distinct SUBSTRING(e.value,1,6) as value
        //                     from excel_datas e
        //                     join sheets s on s.id = e.sheet_id
        //                     join lokasi l on l.id = e.lokasi_id
        //                     where s.name like '".$sheet."'
        //                     and e.file_import_id IN ".$file_import_id."
        //                     and l.distrik_id = ".$distrik_id."
        //                     and e.kolom = '".$kolom."'
        //                     and e.row > 12");
        return $query;
    }

    function get_form_rkau_parent_update($file_import_id, $distrik_id, $sheet, $kolom){

      //Query via pgdl_excel_datas_revisi
      $query = DB::select("select distinct SUBSTRING(e.value,1,4) as value
                          from pgdl_excel_datas_revisi e
                          join pgdl_sheets s on s.id = e.pgdl_sheet_id
                          join lokasi l on l.id = e.lokasi_id
                          join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                          join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                          where s.name like '".$sheet."'
                          and fk.file_import_id IN ".$file_import_id."
                          and l.distrik_id = ".$distrik_id."
                          and e.kolom = '".$kolom."'
                          and e.row > 12");

        //Query Via tabel excel_datas
        // $query = DB::select("select distinct SUBSTRING(e.value,1,4) as value
        //                     from excel_datas e
        //                     join sheets s on s.id = e.sheet_id
        //                     join lokasi l on l.id = e.lokasi_id
        //                     where s.name like '".$sheet."'
        //                     and e.file_import_id IN ".$file_import_id."
        //                     and l.distrik_id = ".$distrik_id."
        //                     and e.kolom = '".$kolom."'
        //                     and e.row > 12");

        return $query;
    }
    function get_form_rkau_disburse_update($file_import_id, $distrik_id, $sheet, $kolom){

      //Query via pgdl_excel_datas_revisi
      $query = DB::select("select (case when value = '' then 0 else value::float end) as value, e.row
                          from pgdl_excel_datas_revisi e
                          join pgdl_sheets s on s.id = e.pgdl_sheet_id
                          join lokasi l on l.id = e.lokasi_id
                          join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                          join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                          where s.name like '".$sheet."'
                          and fk.id IN ".$file_import_id."
                          and l.distrik_id = ".$distrik_id."
                          and e.kolom = '".$kolom."'
                          and e.row > 12");

        //Query Via tabel excel_datas
        // $query = DB::select("select (case when value = '' then 0 else value::float end) as value, e.row
        //                     from excel_datas e
        //                     join sheets s on s.id = e.sheet_id
        //                     join lokasi l on l.id = e.lokasi_id
        //                     where s.name like '".$sheet."'
        //                     and e.file_import_id IN ".$file_import_id."
        //                     and l.distrik_id = ".$distrik_id."
        //                     and e.kolom = '".$kolom."'
        //                     and e.row > 12");
        $result = array();
        foreach ($query as $value) {
            $result[$value->row] = $value->value;
        }
        return $result;
    }

    function get_form_penyusutan_update($file_import_id, $distrik_id, $kolom){

      //Query via pgdl_excel_datas_revisi
      $query = DB::select("select e.row, e.value
                            from pgdl_excel_datas_revisi e
                            join pgdl_sheets s on s.id = e.pgdl_sheet_id
                            join lokasi l on l.id = e.lokasi_id
                            join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                            join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                            where s.name like 'I-Penyusutan'
                            and fk.id IN ".$file_import_id."
                            and l.distrik_id = ".$distrik_id." and e.kolom = '".$kolom."' order by e.row;");

        //Query via excel_datas
        // $query = DB::select("select e.row, e.value
        //                       from excel_datas e
        //                       join sheets s on s.id = e.sheet_id
        //                       join lokasi l on l.id = e.lokasi_id
        //                       where s.name like 'I-Penyusutan'
        //                       and e.file_import_id IN ".$file_import_id."
        //                       and l.distrik_id = ".$distrik_id." and e.kolom = '".$kolom."' order by e.row;");
        return $query;
    }

    function get_form_penyusutan_disburse_update($file_import_id, $distrik_id, $kolom){

        //Query via pgdl_excel_datas_revisi
        $query = DB::select("select (case when value = '' then 0 else value::float end) as value
                            from pgdl_excel_datas_revisi e
                            join pgdl_sheets s on s.id = e.pgdl_sheet_id
                            join lokasi l on l.id = e.lokasi_id
                            join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                            join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                            where s.name like 'I-Penyusutan'
                            and fk.id IN ".$file_import_id."
                            and l.distrik_id = ".$distrik_id." and e.kolom = '".$kolom."' ");

        //Query via excel_datas
        // $query = DB::select("select (case when value = '' then 0 else value::float end) as value
        //                       from excel_datas e
        //                       join sheets s on s.id = e.sheet_id
        //                       join lokasi l on l.id = e.lokasi_id
        //                       where s.name like 'I-Penyusutan'
        //                       and e.file_import_id IN ".$file_import_id."
        //                       and l.distrik_id = ".$distrik_id." and e.kolom = '".$kolom."' ");
        return $query;
    }
    function get_form_bahan_bakar_update($file_import_id, $distrik_id, $kolom, $jenis_bahan_bakar){

      // Query via pgdl_excel_datas_revisi
      $query = DB::select("select e.row, e.value from pgdl_excel_datas_revisi e
                          join pgdl_sheets s on s.id = e.pgdl_sheet_id
                          join lokasi l on l.id = e.lokasi_id
                          join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                          join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                          where s.name like 'Database KIT (P+S+I)'
                          and fk._file_import_id IN ".$file_import_id."
                          and l.distrik_id = ".$distrik_id."
                          and e.kolom like '".$kolom."' and row in (select e.row from pgdl_excel_datas_revisi e
                          join pgdl_sheets s on s.id = e.pgdl_sheet_id
                          join lokasi l on l.id = e.lokasi_id
                          join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                          join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                          where s.name like 'Database KIT (P+S+I)'
                          and fk._file_import_id IN ".$file_import_id."
                          and l.distrik_id = ".$distrik_id."
                          and e.kolom like 'AF' and e.value like '".$jenis_bahan_bakar."')");

        // //Query via excel_datas
        // $query = DB::select("select e.row, e.value from excel_datas e
        //                     join sheets s on s.id = e.sheet_id
        //                     join lokasi l on l.id = e.lokasi_id
        //                     where s.name like 'Database KIT (P+S+I)'
        //                     and e.file_import_id IN ".$file_import_id."
        //                     and l.distrik_id = ".$distrik_id."
        //                     and e.kolom like '".$kolom."' and row in (select e.row from excel_datas e
        //                     join sheets s on s.id = e.sheet_id
        //                     where s.name like 'Database KIT (P+S+I)'
        //                     and e.file_import_id IN ".$file_import_id."
        //                     and l.distrik_id = ".$distrik_id."
        //                     and e.kolom like 'AF' and e.value like '".$jenis_bahan_bakar."')");
        return $query;
    }

    function get_form_bahan_bakar_prk_update($file_import_id, $distrik_id, $kolom, $jenis_bahan_bakar, $level){
        if($level == 'kegiatan') $substr = 10;
        else if($level == 'inti') $substr = 8;
        else if($level == 'parent') $substr = 6;

        // Query via pgdl_excel_datas_revisi
        $query = DB::select("select distinct SUBSTRING(e.value,1,".$substr.") as value from pgdl_excel_datas_revisi e
                            join pgdl_sheets s on s.id = e.pgdl_sheet_id
                            join lokasi l on l.id = e.lokasi_id
                            join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                            join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                            where s.name like 'Database KIT (P+S+I)'
                            and fk.file_import_id IN ".$file_import_id."
                            and l.distrik_id = ".$distrik_id."
                            and e.kolom like '".$kolom."' and row in (select e.row from pgdl_excel_datas_revisi e
                            join pgdl_sheets s on s.id = e.pgdl_sheet_id
                            join lokasi l on l.id = e.lokasi_id
                            join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                            join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                            where s.name like 'Database KIT (P+S+I)'
                            and fk.file_import_id IN ".$file_import_id."
                            and l.distrik_id = ".$distrik_id."
                            and e.kolom like 'AF' and e.value like '".$jenis_bahan_bakar."')");

        //Query via excel_datas
        // $query = DB::select("select distinct SUBSTRING(e.value,1,".$substr.") as value from excel_datas e
        //                     join sheets s on s.id = e.sheet_id
        //                     join lokasi l on l.id = e.lokasi_id
        //                     where s.name like 'Database KIT (P+S+I)'
        //                     and e.file_import_id IN ".$file_import_id."
        //                     and l.distrik_id = ".$distrik_id."
        //                     and e.kolom like '".$kolom."' and row in (select e.row from excel_datas e
        //                     join sheets s on s.id = e.sheet_id
        //                     join lokasi l on l.id = e.lokasi_id
        //                     where s.name like 'Database KIT (P+S+I)'
        //                     and e.file_import_id IN ".$file_import_id."
        //                     and l.distrik_id = ".$distrik_id."
        //                     and e.kolom like 'AF' and e.value like '".$jenis_bahan_bakar."')");

        return $query;
    }

    function get_form_bahan_bakar_per_prk_update($file_import_id, $distrik_id, $kolom, $jenis_bahan_bakar, $kolom_prk, $prk){

      // Query via pgdl
      // $query = DB::select("select e.row, e.value from pgdl_excel_datas_revisi e
      //                     join pgdl_sheets s on s.id = e.pgdl_sheet_id
      //                     join lokasi l on l.id = e.lokasi_id
      //                     join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
      //                     join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
      //                     where s.name like 'Database KIT (P+S+I)'
      //                     and fk.file_import_id IN ".$file_import_id."
      //                     and l.distrik_id = ".$distrik_id."
      //                     and e.kolom like '".$kolom."' and row in (
      //                         select e.row from pgdl_excel_datas_revisi e
      //                         join pgdl_sheets s on s.id = e.pgdl_sheet_id
      //                         join lokasi l on l.id = e.lokasi_id
      //                         join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
      //                         join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
      //                         where s.name like 'Database KIT (P+S+I)'
      //                         and fk.file_import_id IN ".$file_import_id."
      //                         and l.distrik_id = ".$distrik_id."
      //                         and e.kolom like '".$kolom_prk."' and e.value like '".$prk."' and row in (
      //                             select e.row from pgdl_excel_datas_revisi e
      //                             join pgdl_sheets s on s.id = e.pgdl_sheet_id
      //                             join lokasi l on l.id = e.lokasi_id
      //                             join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
      //                             join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
      //                             where s.name like 'Database KIT (P+S+I)'
      //                             and fk.file_import_id IN ".$file_import_id."
      //                             and l.distrik_id = ".$distrik_id."
      //                             and e.kolom like 'AF' and e.value like '".$jenis_bahan_bakar."'
      //                         )
      //                     )");

        $query = DB::select("select e.row, e.value
                            from pgdl_excel_datas_revisi e
                            join pgdl_sheets s on s.id = e.pgdl_sheet_id
                            join lokasi l on l.id = e.lokasi_id
                            join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                            join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                            where s.name like 'Database KIT (P+S+I)'
                            and fk.id IN ".$file_import_id."
                            and l.distrik_id = ".$distrik_id."
                            and e.kolom = '".$kolom."' and row IN
                            (select e.row
                            from pgdl_excel_datas_revisi e
                            join pgdl_sheets s on s.id = e.pgdl_sheet_id
                            join lokasi l on l.id = e.lokasi_id
                            join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                            join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                            where s.name like 'Database KIT (P+S+I)'
                            and fk.id IN ".$file_import_id."
                            and l.distrik_id = ".$distrik_id."
                            and e.kolom like '".$kolom_prk."' and e.value like '".$prk."'
                            and row IN
                            (select e.row
                            from pgdl_excel_datas_revisi e
                            join pgdl_sheets s on s.id = e.pgdl_sheet_id
                            join lokasi l on l.id = e.lokasi_id
                            join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                            join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                            where s.name like 'Database KIT (P+S+I)'
                            and fk.id IN ".$file_import_id."
                            and l.distrik_id = ".$distrik_id."
                            and e.kolom like 'AF' and e.value like '".$jenis_bahan_bakar."')
                            );");

      //Query via excel_datas
        // $query = DB::select("select e.row, e.value from excel_datas e
        //                     join sheets s on s.id = e.sheet_id
        //                     join lokasi l on l.id = e.lokasi_id
        //                     where s.name like 'Database KIT (P+S+I)'
        //                     and e.file_import_id IN ".$file_import_id."
        //                     and l.distrik_id = ".$distrik_id."
        //                     and e.kolom like '".$kolom."' and row in (
        //                         select e.row from excel_datas e
        //                         join sheets s on s.id = e.sheet_id
        //                         join lokasi l on l.id = e.lokasi_id
        //                         where s.name like 'Database KIT (P+S+I)'
        //                         and e.file_import_id IN ".$file_import_id."
        //                         and l.distrik_id = ".$distrik_id."
        //                         and e.kolom like '".$kolom_prk."' and e.value like '".$prk."' and row in (
        //                             select e.row from excel_datas e
        //                             join sheets s on s.id = e.sheet_id
        //                             join lokasi l on l.id = e.lokasi_id
        //                             where s.name like 'Database KIT (P+S+I)'
        //                             and e.file_import_id IN ".$file_import_id."
        //                             and l.distrik_id = ".$distrik_id."
        //                             and e.kolom like 'AF' and e.value like '".$jenis_bahan_bakar."'
        //                         )
        //                     )");
        $result = array();
        foreach ($query as $value) {
            $result[$value->row] = $value->value;
        }
        return $result;
    }

    //--------------------------------------End of Update-------------------------------------------------------------------

    function get_form_rkau($file_import_id, $distrik_id, $sheet, $kolom){
        // $query = DB::select("select e.row, e.value
        //                     from excel_datas e
        //                     join sheets s on s.id = e.sheet_id
        //                     join lokasi l on l.id = e.lokasi_id
        //                     where s.name like '".$sheet."'
        //                     and e.file_import_id IN ".$file_import_id."
        //                     and l.distrik_id = ".$distrik_id."
        //                     and e.kolom = '".$kolom."'
        //                     and e.row > 12
        //                     order by e.row");

        $query = DB::select("select e.row, e.value
                              from pgdl_excel_datas_revisi e
                              join pgdl_sheets s on s.id = e.pgdl_sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                              join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                              where s.name like '".$sheet."'
                              and fk.id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom = '".$kolom."'
                              and e.row > 12
                              order by e.row;");

        $result = array();
        foreach ($query as $value) {
            $result[$value->row] = $value->value;
        }
        return $result;
    }


    function get_form_rkau_inti($file_import_id, $distrik_id, $sheet, $kolom){
        // $query = DB::select("select distinct SUBSTRING(e.value,1,6) as value
        //                     from excel_datas e
        //                     join sheets s on s.id = e.sheet_id
        //                     join lokasi l on l.id = e.lokasi_id
        //                     where s.name like '".$sheet."'
        //                     and e.file_import_id IN ".$file_import_id."
        //                     and l.distrik_id = ".$distrik_id."
        //                     and e.kolom = '".$kolom."'
        //                     and e.row > 12");

        $query = DB::select("select distinct SUBSTRING(e.value,1,6) as value
                              from pgdl_excel_datas_revisi e
                              join pgdl_sheets s on s.id = e.pgdl_sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                              join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                              where s.name like '".$sheet."'
                              and fk.id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom = '".$kolom."'
                              and e.row > 12");

        return $query;
    }

    function get_form_rkau_parent($file_import_id, $distrik_id, $sheet, $kolom){
        // $query = DB::select("select distinct SUBSTRING(e.value,1,4) as value
        //                     from excel_datas e
        //                     join sheets s on s.id = e.sheet_id
        //                     join lokasi l on l.id = e.lokasi_id
        //                     where s.name like '".$sheet."'
        //                     and e.file_import_id IN ".$file_import_id."
        //                     and l.distrik_id = ".$distrik_id."
        //                     and e.kolom = '".$kolom."'
        //                     and e.row > 12");

        $query = DB::select("select distinct SUBSTRING(e.value,1,4) as value
                              from pgdl_excel_datas_revisi e
                              join pgdl_sheets s on s.id = e.pgdl_sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                              join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                              where s.name like '".$sheet."'
                              and fk.id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom = '".$kolom."'
                              and e.row > 12");

        //dd($file_import_id."--".$sheet."--".$kolom);
        return $query;
    }
    function get_form_rkau_disburse($file_import_id, $distrik_id, $sheet, $kolom){
        $query = DB::select("select (case when value = '' then 0 else value::float end) as value, e.row
                            from excel_datas_ketetapan e
                            join sheets s on s.id = e.sheet_id
                            join lokasi l on l.id = e.lokasi_id
                            where s.name like '".$sheet."'
                            and e.file_import_ketetapan_id IN ".$file_import_id."
                            and l.distrik_id = ".$distrik_id."
                            and e.kolom = '".$kolom."'
                            and e.row > 12");
        $result = array();
        foreach ($query as $value) {
            $result[$value->row] = $value->value;
        }
        return $result;
    }

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

    function get_form_penyusutan($file_import_id, $distrik_id, $kolom){
        // $query = DB::select("select e.row, e.value
        //                       from excel_datas e
        //                       join sheets s on s.id = e.sheet_id
        //                       join lokasi l on l.id = e.lokasi_id
        //                       where s.name like 'I-Penyusutan'
        //                       and e.file_import_id IN ".$file_import_id."
        //                       and l.distrik_id = ".$distrik_id." and e.kolom = '".$kolom."' order by e.row;");

        $query = DB::select("select e.row, e.value
                              from pgdl_excel_datas_revisi e
                              join pgdl_sheets s on s.id = e.pgdl_sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                              join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                              where s.name like 'I-Penyusutan'
                              and fk.id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom = '".$kolom."' order by e.row;");

        return $query;
    }
    function get_form_penyusutan_inti($file_import_id, $distrik_id, $kolom){
        // $query = DB::select("select distinct SUBSTRING(e.value,1,8) as value
        //                       from excel_datas e
        //                       join sheets s on s.id = e.sheet_id
        //                       join lokasi l on l.id = e.lokasi_id
        //                       where s.name like 'I-Penyusutan'
        //                       and e.file_import_id IN ".$file_import_id."
        //                       and l.distrik_id = ".$distrik_id." and e.kolom = '".$kolom."'");

        $query = DB::select("select distinct SUBSTRING(e.value,1,8) as value
                              from pgdl_excel_datas_revisi e
                              join pgdl_sheets s on s.id = e.pgdl_sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                              join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                              where s.name like 'I-Penyusutan'
                              and fk.id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom = '".$kolom."'");

        return $query;
    }

    function get_form_penyusutan_parent($file_import_id, $distrik_id, $kolom){
        // $query = DB::select("select distinct SUBSTRING(e.value,1,6) as value
        //                       from excel_datas e
        //                       join sheets s on s.id = e.sheet_id
        //                       join lokasi l on l.id = e.lokasi_id
        //                       where s.name like 'I-Penyusutan'
        //                       and e.file_import_id IN ".$file_import_id."
        //                       and l.distrik_id = ".$distrik_id." and e.kolom = '".$kolom."'");

        $query = DB::select("select distinct SUBSTRING(e.value,1,6) as value
                              from pgdl_excel_datas_revisi e
                              join pgdl_sheets s on s.id = e.pgdl_sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                              join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                              where s.name like 'I-Penyusutan'
                              and fk.id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom = '".$kolom."'");

        return $query;
    }
    function get_form_penyusutan_disburse($file_import_id, $distrik_id, $kolom){
        $query = DB::select("select (case when value = '' then 0 else value::float end) as value
                              from excel_datas e
                              join sheets s on s.id = e.sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              where s.name like 'I-Penyusutan'
                              and e.file_import_id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id." and e.kolom = '".$kolom."' ");
        return $query;
    }

    function get_form_bahan_bakar($file_import_id, $distrik_id, $kolom, $jenis_bahan_bakar){
        $query = DB::select("select e.row, e.value from excel_datas e
                            join sheets s on s.id = e.sheet_id
                            join lokasi l on l.id = e.lokasi_id
                            where s.name like 'Database KIT (P+S+I)'
                            and e.file_import_id IN ".$file_import_id."
                            and l.distrik_id = ".$distrik_id."
                            and e.kolom like '".$kolom."' and row in (select e.row from excel_datas e
                            join sheets s on s.id = e.sheet_id
                            where s.name like 'Database KIT (P+S+I)'
                            and e.file_import_id IN ".$file_import_id."
                            and l.distrik_id = ".$distrik_id."
                            and e.kolom like 'AF' and e.value like '".$jenis_bahan_bakar."')");
        return $query;
    }

    function get_form_bahan_bakar_prk($file_import_id, $distrik_id, $kolom, $jenis_bahan_bakar, $level){
        if($level == 'kegiatan') $substr = 10;
        else if($level == 'inti') $substr = 8;
        else if($level == 'parent') $substr = 6;
        // $query = DB::select("select distinct SUBSTRING(e.value,1,".$substr.") as value from excel_datas e
        //                     join sheets s on s.id = e.sheet_id
        //                     join lokasi l on l.id = e.lokasi_id
        //                     where s.name like 'Database KIT (P+S+I)'
        //                     and e.file_import_id IN ".$file_import_id."
        //                     and l.distrik_id = ".$distrik_id."
        //                     and e.kolom like '".$kolom."' and row in (select e.row from excel_datas e
        //                     join sheets s on s.id = e.sheet_id
        //                     join lokasi l on l.id = e.lokasi_id
        //                     where s.name like 'Database KIT (P+S+I)'
        //                     and e.file_import_id IN ".$file_import_id."
        //                     and l.distrik_id = ".$distrik_id."
        //                     and e.kolom like 'AF' and e.value like '".$jenis_bahan_bakar."')");

        $query = DB::select("select distinct SUBSTRING(e.value,1,".$substr.") as value
                              from pgdl_excel_datas_revisi e
                              join pgdl_sheets s on s.id = e.pgdl_sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                              join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                              where s.name like 'Database KIT (P+S+I)'
                              and fk.id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom = '".$kolom."' and row IN
                              (
                                  select e.row
                                  from pgdl_excel_datas_revisi e
                                  join pgdl_sheets s on s.id = e.pgdl_sheet_id
                                  join lokasi l on l.id = e.lokasi_id
                                  join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                                  join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                                  where s.name like 'Database KIT (P+S+I)'
                                  and fk.id IN ".$file_import_id."
                                  and l.distrik_id = ".$distrik_id."
                                  and e.kolom like 'AF' and e.value like '".$jenis_bahan_bakar['name']."' and e.row IN
                                  (
                                      select e.row
                                      from pgdl_excel_datas_revisi e
                                      join pgdl_sheets s on s.id = e.pgdl_sheet_id
                                      join lokasi l on l.id = e.lokasi_id
                                      join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                                      join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                                      where s.name like 'Database KIT (P+S+I)'
                                      and fk.id IN ".$file_import_id."
                                      and l.distrik_id = ".$distrik_id."
                                      and e.kolom like '".$jenis_bahan_bakar['beban_mat1']."' and (e.value is not null and e.value != '' and e.value != '0')
                                  )
                              );"
                          );
        // DEBUG:
        // if ($jenis_bahan_bakar['name'] == 'LAIN-LAIN') {
        //     $s1 = DB::select("select e.row, e.kolom, e.value
        //               from pgdl_excel_datas_revisi e
        //               join pgdl_sheets s on s.id = e.pgdl_sheet_id
        //               join lokasi l on l.id = e.lokasi_id
        //               join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
        //               join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
        //               where s.name like 'Database KIT (P+S+I)'
        //               and fk.id IN ".$file_import_id."
        //               and l.distrik_id = ".$distrik_id."
        //               and e.kolom like 'AF' and e.value like '".$jenis_bahan_bakar['name']."' and e.row IN
        //               (
        //                   select e.row
        //                   from pgdl_excel_datas_revisi e
        //                   join pgdl_sheets s on s.id = e.pgdl_sheet_id
        //                   join lokasi l on l.id = e.lokasi_id
        //                   join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
        //                   join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
        //                   where s.name like 'Database KIT (P+S+I)'
        //                   and fk.id IN ".$file_import_id."
        //                   and l.distrik_id = ".$distrik_id."
        //                   and e.kolom like '".$jenis_bahan_bakar['beban_mat1']."' and (e.value is not null and e.value != '' and e.value != '0')
        //               )");
        //     $s2 = DB::select("select e.row, e.kolom, e.value
        //               from pgdl_excel_datas_revisi e
        //               join pgdl_sheets s on s.id = e.pgdl_sheet_id
        //               join lokasi l on l.id = e.lokasi_id
        //               join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
        //               join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
        //               where s.name like 'Database KIT (P+S+I)'
        //               and fk.id IN ".$file_import_id."
        //               and l.distrik_id = ".$distrik_id."
        //               and e.kolom like '".$jenis_bahan_bakar['beban_mat1']."' and (e.value is not null and e.value != '' and e.value != '0')");
        //     dd(compact('jenis_bahan_bakar', 's1', 's2', 'query'));
        // }

        return $query;
    }

    function get_form_bahan_bakar_per_prk($file_import_id, $distrik_id, $kolom, $jenis_bahan_bakar, $kolom_prk, $prk){
        // $query = DB::select("select e.row, e.value from excel_datas e
        //                     join sheets s on s.id = e.sheet_id
        //                     join lokasi l on l.id = e.lokasi_id
        //                     where s.name like 'Database KIT (P+S+I)'
        //                     and e.file_import_id IN ".$file_import_id."
        //                     and l.distrik_id = ".$distrik_id."
        //                     and e.kolom like '".$kolom."' and row in (
        //                         select e.row from excel_datas e
        //                         join sheets s on s.id = e.sheet_id
        //                         join lokasi l on l.id = e.lokasi_id
        //                         where s.name like 'Database KIT (P+S+I)'
        //                         and e.file_import_id IN ".$file_import_id."
        //                         and l.distrik_id = ".$distrik_id."
        //                         and e.kolom like '".$kolom_prk."' and e.value like '".$prk."' and row in (
        //                             select e.row from excel_datas e
        //                             join sheets s on s.id = e.sheet_id
        //                             join lokasi l on l.id = e.lokasi_id
        //                             where s.name like 'Database KIT (P+S+I)'
        //                             and e.file_import_id IN ".$file_import_id."
        //                             and l.distrik_id = ".$distrik_id."
        //                             and e.kolom like 'AF' and e.value like '".$jenis_bahan_bakar."'
        //                         )
        //                     )");

        $query = DB::select("select e.row, e.value
                              from pgdl_excel_datas_revisi e
                              join pgdl_sheets s on s.id = e.pgdl_sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                              join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                              where s.name like 'Database KIT (P+S+I)'
                              and fk.id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom = '".$kolom."' and row IN
                              (select e.row
                              from pgdl_excel_datas_revisi e
                              join pgdl_sheets s on s.id = e.pgdl_sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                              join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                              where s.name like 'Database KIT (P+S+I)'
                              and fk.id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom like '".$kolom_prk."' and e.value like '".$prk."'
                              and row IN
                              (select e.row
                              from pgdl_excel_datas_revisi e
                              join pgdl_sheets s on s.id = e.pgdl_sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                              join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                              where s.name like 'Database KIT (P+S+I)'
                              and fk.id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom like 'AF' and e.value like '".$jenis_bahan_bakar."')
                              );");

        $result = array();
        foreach ($query as $value) {
            $result[$value->row] = $value->value;
        }
        return $result;
    }

    function get_form_bahan_bakar_per_prk_ketetapan($file_import_id, $distrik_id, $kolom, $jenis_bahan_bakar, $kolom_prk, $prk){
        $query = DB::select("select e.row, e.value
                              from excel_datas_ketetapan e
                              join sheets s on s.id = e.sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              where s.name like 'Database KIT (P+S+I)'
                              and e.file_import_ketetapan_id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom = '".$kolom."' and row IN
                              (select e.row
                              from excel_datas_ketetapan e
                              join sheets s on s.id = e.sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              where s.name like 'Database KIT (P+S+I)'
                              and e.file_import_ketetapan_id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom like '".$kolom_prk."' and e.value like '".$prk."'
                              and row IN
                              (select e.row
                              from excel_datas_ketetapan e
                              join sheets s on s.id = e.sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              where s.name like 'Database KIT (P+S+I)'
                              and e.file_import_ketetapan_id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom like 'AF' and e.value like '".$jenis_bahan_bakar."')
                              );");

        $result = array();
        foreach ($query as $value) {
            $result[$value->row] = $value->value;
        }
        return $result;
    }
}
