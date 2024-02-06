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
use App\Entities\PGDLExcelDataRevisi;

Use DB;
use Illuminate\Support\Facades\Input;
use Excel;

class LoaderEllipsePengendalianController extends Controller
{
    public function Loader_Ellipse(Request $request)
    {
        $data = Input::all();
        // dd($data);

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
        $int_count_6_reimburse = NULL;
        $int_count_10_pu = NULL;
        $int_count_10_pln = NULL;
        $int_count_10_kit = NULL;
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
        $input_bulan = $request->input('bulan');
        $int_input_bulan = (int)$input_bulan;
        if($input_tahun == NULL || $input_distrik == NULL){
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
        $nama_bulan = ["", "Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agt", "Sep", "Okt", "Nov", "Des"];

        $nama_bln_dipilih = ($int_input_bulan < 1 || $int_input_bulan > 12 ? '' : $nama_bln[$int_input_bulan]);

        $input_draft_rkau = $this->get_drafts_ketetapan_ids(1,$int_input_distrik, $input_tahun);
        $input_draft_form_6_reimburse = $this->get_drafts_ketetapan_ids(2,$int_input_distrik, $input_tahun);
        $input_draft_form_6_rutin = $this->get_drafts_ketetapan_ids(3,$int_input_distrik, $input_tahun);
        $input_draft_form_10_pu = $this->get_drafts_form_10_pu_ids($int_input_distrik, $input_tahun, $input_fase);
        $input_draft_form_10_pk = $this->get_drafts_form_10_pk_ids($int_input_distrik, $input_tahun, $input_fase);
        $input_draft_form_10_pln = $this->get_drafts_form_10_pln_ids($int_input_distrik, $input_tahun, $input_fase);
        $input_draft_form_bahan_bakar = $this->get_drafts_ketetapan_ids(7,$int_input_distrik, $input_tahun);
        $input_draft_form_penyusutan = $this->get_drafts_ketetapan_ids(9,$int_input_distrik, $input_tahun);

        $name_draft_rkau = '';
        $name_draft_form_6_reimburse = '';
        $name_draft_form_6_rutin = '';
        $name_draft_form_10_pu = '';
        $name_draft_form_10_pk = '';
        $name_draft_form_10_pln = '';
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
        if($input_draft_form_10_pu){
            $name_draft_form_10_pu = $this->get_names(4, $input_draft_form_10_pu);
        }
        if($input_draft_form_10_pk){
            $name_draft_form_10_pk = $this->get_names(5, $input_draft_form_10_pk);
        }
        if($input_draft_form_10_pln){
            $name_draft_form_10_pln = $this->get_names(6, $input_draft_form_10_pln);
        }
        if($input_draft_form_bahan_bakar){
            $name_draft_form_bahan_bakar = $this->get_names(7, $input_draft_form_bahan_bakar);
        }
        if($input_draft_form_penyusutan){
            $name_draft_form_penyusutan = $this->get_names(9, $input_draft_form_penyusutan);
        }

        if ($request->input('strategi_bisnis') != NULL) {
            $input_sb = DB::table('strategi_bisnis')->select('name','id')->where('id', $request->input('strategi_bisnis'))->get();
            if(count($input_sb) == 0)
                return redirect('output/pencarian-pengendalian');
            $input_sb = $input_sb[0];
            $distrik = Distrik::select('name','id')->where('strategi_bisnis_id',$input_sb->id)->get();
        }
        if ($request->input('distrik') != NULL) {
            $input_distrik = DB::table('distrik')->select('name','id')->where('id', $request->distrik)->get();
            if(count($input_distrik) == 0)
                return redirect('output/pencarian-pengendalian');
            $input_distrik = $input_distrik[0];
            $lokasi = Lokasi::select('name','id')->where('distrik_id',$input_distrik->id)->get();
        }
        if ($request->input('lokasi') != NULL) {
            $input_lokasi = DB::table('lokasi')->select('name','id')->where('id', $request->lokasi)->get();
            if(count($input_lokasi) == 0)
                return redirect('output/pencarian-pengendalian');
            $input_lokasi = $input_lokasi[0];
        }

        $input_lokasi = Lokasi::where('distrik_id', $request->distrik)->select("name", "id")->get();

        if ($request->input('fase') != NULL) {
            $input_fase = DB::table('fases')->select('name','id')->where('id', $request->fase)->get();
            if(count($input_fase) == 0)
                return redirect('output/pencarian-pengendalian');

            $input_fase = $input_fase[0];
            $draft_form_rkau = $this->get_drafts(1,$int_input_distrik,$input_tahun);
            $draft_form_6_reimburse = $this->get_drafts(2,$int_input_distrik,$input_tahun);
            $draft_form_6_rutin = $this->get_drafts(3,$int_input_distrik,$input_tahun);
            $draft_form_bahan_bakar = $this->get_drafts(7,$int_input_distrik,$input_tahun);
            $draft_form_penyusutan = $this->get_drafts(9,$int_input_distrik,$input_tahun);
        }
        // if($input_distrik == null || $input_tahun == null || $input_bulan == null)
        if($input_distrik == null || $input_tahun == null)
            return redirect('output/pencarian-pengendalian');

        $jenis_form_yg_digunakan = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', 4)
                                ->whereNotNull('jenis_id')
                                ->where('tahun', $input_tahun)
                                ->where('jenis_id','<>',7)
                                ->select('jenis_id')
                                ->distinct()
                                ->orderBy('jenis_id', 'ASC')
                                ->get();
        $notification_failed = '';
        if(count($jenis_form_yg_digunakan) == 0){
            $notification_failed = 'Setting Report Dashboard Loader Ellipse Pengendalian untuk tahun '.$input_tahun.' belum dibuat!';
            return view('output/loader-ellipse-pengendalian', compact('ai_pjb_result', 'input_tahun', 'distrik', 'lokasi', 'nama_bln_dipilih','notification_failed'));
        }
        else{
            $prk_parent_result = array();
            $prk_inti_result = array();
            $prk_kegiatan_result = array();

            // hardcode untuk prk parent dan inti
            $prk_parent_inti_source = array();
            $prk_parent_inti_source[3] = array('parent' => 'R', 'inti' => 'S',);
            $prk_parent_inti_source[2] = array('parent' => 'R', 'inti' => 'S',);
            $prk_parent_inti_source[5] = array('parent' => 'Q', 'inti' => 'R',);
            $prk_parent_inti_source[4] = array('parent' => 'R', 'inti' => 'S',);
            $prk_parent_inti_source[6] = array('parent' => 'S', 'inti' => 'T',);

            foreach ($jenis_form_yg_digunakan as $key => $jenis_form) {
                if($input_sb->name == 'OM'){
                    // Jika UP ambil dari I-Pendukung EP
                    $daftar_sheet = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', 4)
                                    ->whereNotNull('jenis_id')
                                    ->where('jenis_id',$jenis_form->jenis_id)
                                    ->where('tahun', $input_tahun)
                                    ->select('pgdl_sheet_name')
                                    ->distinct()
                                    ->get();
                }
                else{
                    // Jika UP, Pendukung EP diabaikan
                    $daftar_sheet = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', 4)
                                    ->whereNotNull('jenis_id')
                                    ->where('jenis_id',$jenis_form->jenis_id)
                                    ->where('tahun', $input_tahun)
                                    ->whereNotIn('pgdl_sheet_name', ['I-PENDUKUNG EP'])
                                    ->select('pgdl_sheet_name')
                                    ->distinct()
                                    ->get();
                }

                foreach($daftar_sheet as $sheet_id => $sheet_name){
                                // dd($daftar_sheet);

                    $file_imports_pgdl = $this->get_file_id_pengendalian($jenis_form->jenis_id,$input_tahun,$int_input_distrik);

                    $settings = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id',4)
                                ->where('jenis_id',$jenis_form->jenis_id)
                                ->where('pgdl_sheet_name',$sheet_name->pgdl_sheet_name)
                                ->where('tahun', $input_tahun)
                                ->orderBy('sequence')
                                ->get();
                    if($file_imports_pgdl != null){
                        $prk_data = $this->get_data_pengendalian($file_imports_pgdl, $sheet_name->pgdl_sheet_name, $settings[0]->kolom, $input_sb, $input_tahun);

                        $total_data = count($prk_data);

                        $result_data = array();
                        // $prk_parent = array();
                        // $prk_inti = array();

                        // prk parent
                        if($jenis_form->jenis_id == 1)
                            $prk_parent_list = $this->get_data_pengendalian_parent($file_imports_pgdl, $sheet_name->pgdl_sheet_name, $settings[0]->kolom, 1, 4, $input_sb, $input_tahun);
                        else
                            $prk_parent_list = $this->get_data_pengendalian_parent($file_imports_pgdl, $sheet_name->pgdl_sheet_name, $settings[0]->kolom, 3, 4, $input_sb, $input_tahun);

                        // deskripsi prk parent untuk form 6 dan 10
                        if($jenis_form->jenis_id >=2 and $jenis_form->jenis_id <=6 )
                            $result_data['PRK parent description'] = $this->get_data_pengendalian($file_imports_pgdl, $sheet_name->pgdl_sheet_name, $prk_parent_inti_source[$jenis_form->jenis_id]['parent'], $input_sb, $input_tahun);


                        // set array prk parent
                        foreach ($prk_parent_list as $parent) {
                            $prk_parent_result[$parent->value] = array();
                            foreach($settings as $column_setting){
                                $prk_parent_result[$parent->value][$column_setting->judul_kolom] = '';
                            }
                            $prk_parent_result[$parent->value]['Nomor Project / PRK'] = $parent->value;
                            $prk_parent_result[$parent->value]['Parent Project'] = '';
                        }
                        // dd($sheet_name->pgdl_sheet_name);

                        // prk inti
                        if($jenis_form->jenis_id == 1)
                            $prk_inti_list = $this->get_data_pengendalian_parent($file_imports_pgdl, $sheet_name->pgdl_sheet_name, $settings[0]->kolom, 1, 6, $input_sb, $input_tahun);
                        else
                            $prk_inti_list = $this->get_data_pengendalian_parent($file_imports_pgdl, $sheet_name->pgdl_sheet_name, $settings[0]->kolom, 3, 6, $input_sb, $input_tahun);

                        if($jenis_form->jenis_id >=2 and $jenis_form->jenis_id <=6 )
                            $result_data['PRK inti description'] = $this->get_data_pengendalian($file_imports_pgdl, $sheet_name->pgdl_sheet_name, $prk_parent_inti_source[$jenis_form->jenis_id]['inti'], $input_sb, $input_tahun);


                        // set array prk inti
                        foreach ($prk_inti_list as $inti) {
                            $prk_inti_result[$inti->value] = array();
                            foreach($settings as $column_setting){
                                $prk_inti_result[$inti->value][$column_setting->judul_kolom] = '';
                            }
                            $prk_inti_result[$inti->value]['Nomor Project / PRK'] = $inti->value;
                            $prk_inti_result[$inti->value]['Parent Project'] = substr($inti->value, 0,4);
                        }

                        // prk kegiatan
                        foreach($settings as $column_setting){
                            $jenis_id = $column_setting->jenis_id;
                            $column_result = array();
                            // jika dari pengendalian, cari berdasarkan kolom
                            if($column_setting->pgdl_report_dashboard_source_id == 2){
                                // jika dari RKAU selain sheet pendukung EP, ambil dari kolom J&K
                                if($sheet_name->pgdl_sheet_name != 'I-PENDUKUNG EP' && $jenis_form->jenis_id == 1 && $column_setting->judul_kolom == 'Cash (OTH)'){

                                    $column_result[0] = $this->get_data_pengendalian($file_imports_pgdl, $sheet_name->pgdl_sheet_name, $column_setting->kolom, $input_sb, $input_tahun);
                                    $column_result[1] = $this->get_data_pengendalian($file_imports_pgdl, $sheet_name->pgdl_sheet_name, ++$column_setting->kolom, $input_sb, $input_tahun);
                                    // $column_result = $this->get_data_pengendalian_disburse($file_imports_pgdl, $sheet_name->pgdl_sheet_name, 'J');
                                }

                                // jika dari form 10 atau form 6 yg pakai 2 kolom disburse
                                // elseif($jenis_form->jenis_id >= 2 && $jenis_form->jenis_id <=6 && in_array($column_setting->judul_kolom, $nama_bulan)){
                                elseif($jenis_form->jenis_id >= 2 && $jenis_form->jenis_id <=3 && in_array($column_setting->judul_kolom, $nama_bulan)){
                                    $column_result[0] = $this->get_data_pengendalian($file_imports_pgdl, $sheet_name->pgdl_sheet_name, $column_setting->kolom, $input_sb, $input_tahun);
                                    $column_result[1] = $this->get_data_pengendalian($file_imports_pgdl, $sheet_name->pgdl_sheet_name, ++$column_setting->kolom, $input_sb, $input_tahun);
                                    // $column_result = $this->get_data_pengendalian_disburse($file_imports_pgdl, $sheet_name->pgdl_sheet_name, $column_setting->kolom);
                                }

                                else
                                    $column_result = $this->get_data_pengendalian($file_imports_pgdl, $sheet_name->pgdl_sheet_name, $column_setting->kolom, $input_sb, $input_tahun);
                            }
                            $result_data[$column_setting->judul_kolom]= $column_result;

                        }
                        for($i = 0; $i< $total_data; $i++){
                            $temp = array();
                            $prk_desc_cache = [];
                            $no_prk_kegiatan = !empty($result_data['Nomor Project / PRK']) ? $result_data['Nomor Project / PRK'][$i]->value : '';
                            if($no_prk_kegiatan != '' || $no_prk_kegiatan!= null){


                                if($jenis_form->jenis_id == 1)
                                    $no_prk_inti = substr($no_prk_kegiatan, 0 , 6);
                                else
                                    $no_prk_inti = substr($no_prk_kegiatan, 2 , 6);

                                $no_prk_parent = substr($no_prk_inti, 0 , 4);

                                // untuk jenis form 10 & penyusutan, pada PRK terdapat kode distrik, kode tsb harus dihilangkan agar sesuai dg PRK dari form yg lain
                                if($jenis_form->jenis_id >=4)
                                    $no_prk_kegiatan = substr($no_prk_kegiatan, 2, 8);

                                foreach($settings as $column_setting){

                                    // ini untuk yg hardcode
                                    if($column_setting->pgdl_report_dashboard_source_id == 5){
                                        if($column_setting->judul_kolom == 'Parent Project')
                                            $temp[$column_setting->judul_kolom] = $no_prk_inti;
                                        // khusus form penyusutan, deskripsi projectnya adalah Penyusutan
                                        elseif($column_setting->judul_kolom == 'Deskripsi Project /PRK [40 karakter]' && $jenis_form->jenis_id == 9){
                                            $temp[$column_setting->judul_kolom] = "Penyusutan";
                                        }
                                        elseif($column_setting->judul_kolom == 'Ext.Description Line 1 [60 Karakter]')
                                            $temp[$column_setting->judul_kolom] = substr($temp['Deskripsi Project /PRK [40 karakter]'], 40 , 60);
                                        elseif($column_setting->judul_kolom == 'Ext.Description Line 2 [60 Karakter]')
                                            $temp[$column_setting->judul_kolom] = substr($temp['Deskripsi Project /PRK [40 karakter]'], 100 , 60);
                                        elseif($column_setting->judul_kolom == 'Cash (OTH)')
                                            $temp[$column_setting->judul_kolom] = 0;
                                        elseif($column_setting->judul_kolom == 'Ijin Proses (LAB)')
                                            $temp[$column_setting->judul_kolom] = 0;
                                        elseif($column_setting->judul_kolom == 'Account Code')
                                            $prk_parent_result[$no_prk_parent][$column_setting->judul_kolom] = $prk_inti_result[$no_prk_inti][$column_setting->judul_kolom] = $temp[$column_setting->judul_kolom] = 'K00000100';
                                        elseif($column_setting->judul_kolom == 'Years')
                                            $prk_parent_result[$no_prk_parent][$column_setting->judul_kolom] = $prk_inti_result[$no_prk_inti][$column_setting->judul_kolom] = $temp[$column_setting->judul_kolom] = $input_tahun;
                                        elseif($column_setting->judul_kolom == 'Version')
                                            $prk_parent_result[$no_prk_parent][$column_setting->judul_kolom] = $prk_inti_result[$no_prk_inti][$column_setting->judul_kolom] = $temp[$column_setting->judul_kolom] = '001';
                                        elseif($column_setting->judul_kolom == 'PRK Type'){
                                            $temp[$column_setting->judul_kolom] = 'PK';
                                            $prk_parent_result[$no_prk_parent][$column_setting->judul_kolom] = 'PP';
                                            $prk_inti_result[$no_prk_inti][$column_setting->judul_kolom] = 'PI';
                                        }
                                        elseif($column_setting->judul_kolom == 'Plan Start Date (yyyymmdd)')
                                            $prk_parent_result[$no_prk_parent][$column_setting->judul_kolom] = $prk_inti_result[$no_prk_inti][$column_setting->judul_kolom] = $temp[$column_setting->judul_kolom] = $input_tahun.'0101';
                                        elseif($column_setting->judul_kolom == 'Plan Finish Date (yyyymmdd)')
                                            $prk_parent_result[$no_prk_parent][$column_setting->judul_kolom] = $prk_inti_result[$no_prk_inti][$column_setting->judul_kolom] = $temp[$column_setting->judul_kolom] = $input_tahun.'1231';
                                        elseif($column_setting->judul_kolom == 'Tahun Disburse')
                                            $prk_parent_result[$no_prk_parent][$column_setting->judul_kolom] = $prk_inti_result[$no_prk_inti][$column_setting->judul_kolom] = $temp[$column_setting->judul_kolom] = $input_tahun;
                                        else
                                            $temp[$column_setting->judul_kolom] = '';

                                    }

                                    // ini untuk yg ambil dari revisi
                                    elseif($column_setting->pgdl_report_dashboard_source_id == 2){
                                        // khusus parent project, ambil dari substring prk kegiatan
                                        if($column_setting->judul_kolom == 'Nomor Project / PRK')
                                            $temp[$column_setting->judul_kolom] = $no_prk_kegiatan;
                                        elseif($column_setting->judul_kolom == 'Parent Project')
                                            $temp[$column_setting->judul_kolom] = $no_prk_inti;
                                        // khusus deskripsi project, ambil substring 40 karakter
                                        elseif($column_setting->judul_kolom == 'Deskripsi Project /PRK [40 karakter]'){
                                            $temp[$column_setting->judul_kolom] = !empty($result_data[$column_setting->judul_kolom]) ? substr($result_data[$column_setting->judul_kolom][$i]->value, 0 , 40) : 0 ;
                                        }
                                        elseif($column_setting->judul_kolom == 'Ext.Description Line 1 [60 Karakter]')
                                            $temp[$column_setting->judul_kolom] = !empty($result_data[$column_setting->judul_kolom]) ? substr($result_data[$column_setting->judul_kolom][$i]->value, 40 , 60) : 0;
                                            // $temp[$column_setting->judul_kolom] = substr($temp['Deskripsi Project /PRK [40 karakter]'], 40 , 60);
                                        elseif($column_setting->judul_kolom == 'Ext.Description Line 2 [60 Karakter]')
                                            $temp[$column_setting->judul_kolom] = !empty($result_data[$column_setting->judul_kolom]) ? substr($result_data[$column_setting->judul_kolom][$i]->value, 100 , 60) : 0;
                                            // $temp[$column_setting->judul_kolom] = substr($temp['Deskripsi Project /PRK [40 karakter]'], 100 , 60);
                                        elseif($column_setting->judul_kolom == 'Beban (MAT)' && $jenis_form->jenis_id == 6)
                                            $temp[$column_setting->judul_kolom] = !empty($result_data[$column_setting->judul_kolom]) ? $result_data[$column_setting->judul_kolom][$i]->value / 1.1 : 0 ;

                                        // jika cash oth diambil dari 2 kolom
                                        elseif($sheet_name->pgdl_sheet_name != 'I-PENDUKUNG EP' && $jenis_form->jenis_id == 1 && $column_setting->judul_kolom == 'Cash (OTH)'){
                                            $col1 = !empty($result_data[$column_setting->judul_kolom][0][$i]) ?
                                                ( is_numeric($result_data[$column_setting->judul_kolom][0][$i]->value) && $result_data[$column_setting->judul_kolom][0][$i]->value != '' ? $result_data[$column_setting->judul_kolom][0][$i]->value : 0 ) : 0;
                                            $col2 = !empty($result_data[$column_setting->judul_kolom][1][$i]) ?
                                                ( is_numeric($result_data[$column_setting->judul_kolom][1][$i]->value) && $result_data[$column_setting->judul_kolom][1][$i]->value != '' ? $result_data[$column_setting->judul_kolom][1][$i]->value : 0 ) : 0;
                                            $temp[$column_setting->judul_kolom] = $col1 + $col2;
                                        }
                                        // jika dari form 10 atau form 6 yg pakai 2 kolom disburse
                                        elseif($jenis_form->jenis_id >= 2 && $jenis_form->jenis_id <=3 && in_array($column_setting->judul_kolom, $nama_bulan)){
                                        // elseif($jenis_form->jenis_id >= 2 && $jenis_form->jenis_id <=6 && in_array($column_setting->judul_kolom, $nama_bulan)){
                                            $col1 = !empty($result_data[$column_setting->judul_kolom][0][$i]) ?
                                                ( is_numeric($result_data[$column_setting->judul_kolom][0][$i]->value) && $result_data[$column_setting->judul_kolom][0][$i]->value != '' ? $result_data[$column_setting->judul_kolom][0][$i]->value : 0 ) : 0;
                                            $col2 = !empty($result_data[$column_setting->judul_kolom][1][$i]) ?
                                                ( is_numeric($result_data[$column_setting->judul_kolom][1][$i]->value) && $result_data[$column_setting->judul_kolom][1][$i]->value != '' ? $result_data[$column_setting->judul_kolom][1][$i]->value : 0 ) : 0;
                                            $temp[$column_setting->judul_kolom] = $col1 + $col2;
                                        }
                                        else
                                            $temp[$column_setting->judul_kolom] = !empty($result_data[$column_setting->judul_kolom]) ? $result_data[$column_setting->judul_kolom][$i]->value : 0 ;
                                    }

                                    else{
                                        $temp[$column_setting->judul_kolom] = '-';
                                    }
                                }

                                array_push($prk_kegiatan_result, $temp);

                                // if ($temp['Nomor Project / PRK'] == '183Y0175') {
                                //     dd('A', $temp, $prk_kegiatan_result);
                                // }

                                // Set data PRK Parent
                                if($jenis_id == 9){
                                    $prk_parent_result[$no_prk_parent]['Deskripsi Project /PRK [40 karakter]'] = 'Penyusutan';
                                    $prk_parent_result[$no_prk_parent]['Ext.Description Line 1 [60 Karakter]'] = $prk_parent_result[$no_prk_parent]['Ext.Description Line 2 [60 Karakter]'] = '';
                                }
                                elseif($jenis_id == 1){
                                    $desc = $this->get_prk_parent_desc(substr($no_prk_parent, 2,2), $prk_desc_cache);
                                    $prk_parent_result[$no_prk_parent]['Deskripsi Project /PRK [40 karakter]'] = substr($desc, 0, 40);
                                    $prk_parent_result[$no_prk_parent]['Ext.Description Line 1 [60 Karakter]'] = substr($desc, 40, 60);
                                    $prk_parent_result[$no_prk_parent]['Ext.Description Line 2 [60 Karakter]'] = substr($desc, 100, 60);
                                }
                                else{
                                    $prk_parent_result[$no_prk_parent]['Deskripsi Project /PRK [40 karakter]'] = substr($result_data['PRK parent description'][$i]->value , 0, 40);
                                    $prk_parent_result[$no_prk_parent]['Ext.Description Line 1 [60 Karakter]'] = substr($result_data['PRK parent description'][$i]->value , 40, 60);
                                    $prk_parent_result[$no_prk_parent]['Ext.Description Line 2 [60 Karakter]'] = substr($result_data['PRK parent description'][$i]->value , 100, 60);
                                }
                                $prk_parent_result[$no_prk_parent]['Beban (MAT)'] += !empty($temp['Beban (MAT)']) ? $temp['Beban (MAT)'] : 0;
                                $prk_parent_result[$no_prk_parent]['Cash (OTH)'] += !empty($temp['Cash (OTH)']) ? $temp['Cash (OTH)'] : 0;
                                $prk_parent_result[$no_prk_parent]['Ijin Proses (LAB)'] += !empty($temp['Ijin Proses (LAB)']) ? $temp['Ijin Proses (LAB)'] : 0;
                                $prk_parent_result[$no_prk_parent]['Total Year Estimate'] += !empty($temp['Total Year Estimate']) ? $temp['Total Year Estimate'] : 0;


                                // Set data PRK Inti
                                if($jenis_id == 9){
                                    $prk_inti_result[$no_prk_inti]['Deskripsi Project /PRK [40 karakter]'] = 'Penyusutan';
                                    $prk_inti_result[$no_prk_inti]['Ext.Description Line 1 [60 Karakter]'] = $prk_inti_result[$no_prk_inti]['Ext.Description Line 2 [60 Karakter]'] = '';
                                }
                                elseif($jenis_id == 1){
                                    $desc = $this->get_prk_inti_desc(substr($no_prk_inti, 2,2), substr($no_prk_inti, 4,2), $prk_desc_cache);
                                    $prk_inti_result[$no_prk_inti]['Deskripsi Project /PRK [40 karakter]'] = substr($desc, 0, 40);
                                    $prk_inti_result[$no_prk_inti]['Ext.Description Line 1 [60 Karakter]'] = substr($desc, 40, 60);
                                    $prk_inti_result[$no_prk_inti]['Ext.Description Line 2 [60 Karakter]'] = substr($desc, 100, 60);
                                }
                                else{
                                    $prk_inti_result[$no_prk_inti]['Deskripsi Project /PRK [40 karakter]'] = substr($result_data['PRK inti description'][$i]->value,0,40);
                                    $prk_inti_result[$no_prk_inti]['Ext.Description Line 1 [60 Karakter]'] = substr($result_data['PRK inti description'][$i]->value , 40, 60);
                                    $prk_inti_result[$no_prk_inti]['Ext.Description Line 2 [60 Karakter]'] = substr($result_data['PRK inti description'][$i]->value , 100, 60);
                                }
                                $prk_inti_result[$no_prk_inti]['Beban (MAT)'] += !empty($temp['Beban (MAT)']) ? $temp['Beban (MAT)'] : 0;
                                $prk_inti_result[$no_prk_inti]['Cash (OTH)'] += !empty($temp['Cash (OTH)']) ? $temp['Cash (OTH)'] : 0;
                                $prk_inti_result[$no_prk_inti]['Ijin Proses (LAB)'] += !empty($temp['Ijin Proses (LAB)']) ? $temp['Ijin Proses (LAB)'] : 0;
                                $prk_inti_result[$no_prk_inti]['Total Year Estimate'] += !empty($temp['Total Year Estimate']) ? $temp['Total Year Estimate'] : 0;
                            }
                        }
                    }
                }
            }
        }
        // die();
        if ($input_distrik != NULL) {

            $bb_parent = [];
            $bb_inti = [];
            $bb_kegiatan = [];

            //Start form Bahan Bakar
            if($input_draft_form_bahan_bakar && $input_sb->name == 'UP') {
                $jenis_bahan_bakar = array();
                $prk_desc_cache = [];
                array_push($jenis_bahan_bakar, array('name' => 'HSD','description' => 'HSD','beban_mat1' => 'AN', 'beban_mat2' => 'AO','beban_mat3' => 'AQ','beban_mat4' => 'AR','beban_mat5' => 'AS','beban_mat6' => 'AT', 'cash_oth1' => 'AW', 'cash_oth2' => 'AX', 'cash_oth3' => null));
                array_push($jenis_bahan_bakar, array('name' => 'MFO','description' => 'MFO','beban_mat1' => 'AN', 'beban_mat2' => 'AO','beban_mat3' => 'AQ','beban_mat4' => 'AR','beban_mat5' => 'AS','beban_mat6' => 'AT', 'cash_oth1' => 'AW', 'cash_oth2' => 'AX', 'cash_oth3' => null));
                array_push($jenis_bahan_bakar, array('name' => 'IDO','description' => 'IDO','beban_mat1' => 'AN', 'beban_mat2' => 'AO','beban_mat3' => 'AQ','beban_mat4' => 'AR','beban_mat5' => 'AS','beban_mat6' => 'AT', 'cash_oth1' => 'AW', 'cash_oth2' => 'AX', 'cash_oth3' => null));
                array_push($jenis_bahan_bakar, array('name' => 'GAS ALAM','description' => 'Biaya bahan bakar - Gas alam','beban_mat1' => 'AN', 'beban_mat2' => 'AO','beban_mat3' => 'AQ','beban_mat4' => 'AR','beban_mat5' => 'AS','beban_mat6' => 'AT','cash_oth1' => 'AW', 'cash_oth2' => 'AX', 'cash_oth3' => null));
                array_push($jenis_bahan_bakar, array('name' => 'BATUBARA','description' => 'Batubara','beban_mat1' => 'AN', 'beban_mat2' => 'AO','beban_mat3' => 'AQ', 'beban_mat4' => 'AR','beban_mat5' => 'AS','beban_mat6' => 'AT','cash_oth1' => 'AW', 'cash_oth2' => 'AX', 'cash_oth3' => null));
                array_push($jenis_bahan_bakar, array('name' => 'MINYAK PELUMAS ','description' => 'Biaya bahan bakar - Minyak pelumas','beban_mat1' => 'AN', 'beban_mat2' => 'AO','beban_mat3' => 'AQ','beban_mat4' => 'AR','beban_mat5' => 'AS','beban_mat6' => 'AT','cash_oth1' => 'AY', 'cash_oth2' => null, 'cash_oth3' => null));
                array_push($jenis_bahan_bakar, array('name' => 'KIMIA','description' => 'Biaya bahan bakar - Kimia','beban_mat1' => 'AN', 'beban_mat2' => 'AO','beban_mat3' => 'AQ','beban_mat4' => 'AR','beban_mat5' => 'AS','beban_mat6' => 'AT','cash_oth1' => 'AZ', 'cash_oth2' => null, 'cash_oth3' => null));
                array_push($jenis_bahan_bakar, array('name' => 'RETRIBUSI','description' => 'Retribusi','beban_mat1' => 'AN', 'beban_mat2' => NULL,'beban_mat3' => NULL,'beban_mat4' => NULL,'beban_mat5' => NULL,'beban_mat6' => NULL,'cash_oth1' => 'AV', 'cash_oth2' => null, 'cash_oth3' => null));
                array_push($jenis_bahan_bakar, array('name' => 'EP','description' => 'EP','beban_mat1' => 'AN', 'beban_mat2' => 'AO','beban_mat3' => 'AQ','beban_mat4' => 'AR','beban_mat5' => 'AS','beban_mat6' => 'AT','cash_oth1' => 'AW', 'cash_oth2' => 'AX', 'cash_oth3' => 'AV'));
                array_push($jenis_bahan_bakar, array('name' => 'LAIN-LAIN','description' => 'LAIN-LAIN','beban_mat1' => 'AN', 'beban_mat2' => 'AO','beban_mat3' => 'AQ','beban_mat4' => 'AR','beban_mat5' => 'AS','beban_mat6' => 'AT','cash_oth1' => 'AW', 'cash_oth2' => 'AX', 'cash_oth3' => null));

                $daftar_prk_kegiatan_form_bahan_bakar = array();
                $daftar_prk_inti_form_bahan_bakar = array();
                $daftar_prk_parent_form_bahan_bakar = array();

                foreach($jenis_bahan_bakar as $jenis){

                  $form_bahan_bakar_prk_parent = $this->get_form_bahan_bakar_prk($input_draft_form_bahan_bakar,$int_input_distrik, 'J', $jenis['name'], "parent");
                  // dd(compact('form_bahan_bakar_prk_parent'));
                  foreach ($form_bahan_bakar_prk_parent as $key => $value) {
                      $no_prk = substr($value->value, 2, 4);

                      $temp = [
                          'Nomor Project / PRK' => $no_prk,
                          'Parent Project' => '',
                          'Deskripsi Project /PRK [40 karakter]' => $this->get_prk_parent_desc(substr($no_prk, 2,2), $prk_desc_cache),
                          'Ext.Description Line 1 [60 Karakter]' => '',
                          'Ext.Description Line 2 [60 Karakter]' => '',
                          'Account Code' => 'K00000100',
                          'Years' => $input_tahun,
                          'Version' => '001',
                          'PRK Type' => 'PP',
                          'Plan Start Date (yyyymmdd)' => $input_tahun . '0101',
                          'Plan Finish Date (yyyymmdd)' => $input_tahun . '1231',
                      ];

                      foreach($settings as $column_setting){
                          if (isset($temp[$column_setting->judul_kolom])) {
                              continue;
                          }
                          if (isCostLabel($column_setting->judul_kolom)) {
                              $temp[$column_setting->judul_kolom] = 0;
                          } else {
                              $temp[$column_setting->judul_kolom] = '';
                          }
                      }
                      $daftar_prk_parent_form_bahan_bakar[$no_prk] = $temp;
                  }

                  $form_bahan_bakar_prk_inti = $this->get_form_bahan_bakar_prk($input_draft_form_bahan_bakar,$int_input_distrik, 'J', $jenis['name'], "inti");
                  foreach ($form_bahan_bakar_prk_inti as $key => $value) {
                      $no_prk = substr($value->value, 2, 6);
                      $temp = [
                          'Nomor Project / PRK' => $no_prk,
                          'Parent Project' => substr($value->value, 2, 4),
                          'Deskripsi Project /PRK [40 karakter]' => $this->get_prk_inti_desc(substr($no_prk, 2,2), substr($no_prk, 4,2), $prk_desc_cache),
                          'Ext.Description Line 1 [60 Karakter]' => '',
                          'Ext.Description Line 2 [60 Karakter]' => '',
                          'Account Code' => 'K00000100',
                          'Years' => $input_tahun,
                          'Version' => '001',
                          'PRK Type' => 'PI',
                          'Plan Start Date (yyyymmdd)' => $input_tahun . '0101',
                          'Plan Finish Date (yyyymmdd)' => $input_tahun . '1231',
                      ];

                      foreach($settings as $column_setting){
                          if (isset($temp[$column_setting->judul_kolom])) {
                              continue;
                          }
                          if (isCostLabel($column_setting->judul_kolom)) {
                              $temp[$column_setting->judul_kolom] = 0;
                          } else {
                              $temp[$column_setting->judul_kolom] = '';
                          }
                      }

                      $daftar_prk_inti_form_bahan_bakar[$no_prk] = $temp;
                  }
                }


                foreach($jenis_bahan_bakar as $jenis){
                    // dd(compact('daftar_prk_parent_form_bahan_bakar'));
                    $form_bahan_bakar_prk_kegiatan = $this->get_form_bahan_bakar_prk($input_draft_form_bahan_bakar,$int_input_distrik, 'J', $jenis['name'], "kegiatan");

                    foreach ($form_bahan_bakar_prk_kegiatan as $key => $value) {
                        $no_prk_parent = substr($value->value, 2, 4);
                        $no_prk_inti = substr($value->value, 2, 6);
                        $no_prk = substr($value->value, 2, 8);

                        $temp = [
                            'Nomor Project / PRK' => $no_prk,
                            'Parent Project' => substr($value->value, 2, 6),
                            'Deskripsi Project /PRK [40 karakter]' => $jenis['name'],
                            'Ext.Description Line 1 [60 Karakter]' => '',
                            'Ext.Description Line 2 [60 Karakter]' => '',
                            'Account Code' => 'K00000100',
                            'Years' => $input_tahun,
                            'Version' => '001',
                            'PRK Type' => 'PK',
                            'Plan Start Date (yyyymmdd)' => $input_tahun . '0101',
                            'Plan Finish Date (yyyymmdd)' => $input_tahun . '1231',
                        ];

                        foreach($settings as $column_setting) {
                            if (isset($temp[$column_setting->judul_kolom])) {
                                continue;
                            }
                            if (isCostLabel($column_setting->judul_kolom)) {
                                $temp[$column_setting->judul_kolom] = 0;
                            } else {
                                $temp[$column_setting->judul_kolom] = '';
                            }
                        }

                        $sum_beban_mat = 0;

                        if($jenis['beban_mat2']!= null) {
                            $beban_mat2 = $this->get_form_bahan_bakar_per_prk_update($input_draft_form_bahan_bakar,$int_input_distrik, $jenis['beban_mat2'], $jenis['name'],'J',$value->value);
                            $sum_beban_mat += (float) array_sum($beban_mat2);
                        }

                        if($jenis['beban_mat3']!= null) {
                            $beban_mat3 = $this->get_form_bahan_bakar_per_prk_update($input_draft_form_bahan_bakar,$int_input_distrik, $jenis['beban_mat3'], $jenis['name'],'J',$value->value);
                            $sum_beban_mat += (float) array_sum($beban_mat3);
                        }

                        if($jenis['beban_mat4']!= null) {
                            $beban_mat4 = $this->get_form_bahan_bakar_per_prk_update($input_draft_form_bahan_bakar,$int_input_distrik, $jenis['beban_mat4'], $jenis['name'],'J',$value->value);
                            $sum_beban_mat += (float) array_sum($beban_mat4);
                        }

                        if($jenis['beban_mat5']!= null) {
                            $beban_mat5 = $this->get_form_bahan_bakar_per_prk_update($input_draft_form_bahan_bakar,$int_input_distrik, $jenis['beban_mat5'], $jenis['name'],'J',$value->value);
                            $sum_beban_mat += (float) array_sum($beban_mat5);
                        }

                        if($jenis['beban_mat6']!= null) {
                            $beban_mat6 = $this->get_form_bahan_bakar_per_prk_update($input_draft_form_bahan_bakar,$int_input_distrik, $jenis['beban_mat6'], $jenis['name'],'J',$value->value);
                            $sum_beban_mat += (float) array_sum($beban_mat6);
                        }

                        if ($jenis['beban_mat1']!= null) {
                          $beban_mat1 = $this->get_form_bahan_bakar_per_prk_update($input_draft_form_bahan_bakar,$int_input_distrik, $jenis['beban_mat1'], $jenis['name'],'J',$value->value);
                          $sum_beban_mat -= (float) array_sum($beban_mat1);
                        }

                        $temp['Total Year Estimate'] = $temp['Beban (MAT)'] = $sum_beban_mat;

                        $daftar_prk_inti_form_bahan_bakar[$no_prk_inti]['Total Year Estimate']
                            = $daftar_prk_inti_form_bahan_bakar[$no_prk_inti]['Beban (MAT)']
                            = $sum_beban_mat + $daftar_prk_inti_form_bahan_bakar[$no_prk_inti]['Beban (MAT)'];

                        $daftar_prk_parent_form_bahan_bakar[$no_prk_parent]['Total Year Estimate']
                            = $daftar_prk_parent_form_bahan_bakar[$no_prk_parent]['Beban (MAT)']
                            = $sum_beban_mat + $daftar_prk_parent_form_bahan_bakar[$no_prk_parent]['Beban (MAT)'];

                        $sum_cash_oth = 0;
                        $cash_oth1 = $this->get_form_bahan_bakar_per_prk_update($input_draft_form_bahan_bakar,$int_input_distrik, $jenis['cash_oth1'], $jenis['name'],'J',$value->value);
                        $sum_cash_oth += (float) array_sum($cash_oth1);

                        if($jenis['cash_oth2']!= null) {
                            $cash_oth2 = $this->get_form_bahan_bakar_per_prk_update($input_draft_form_bahan_bakar,$int_input_distrik, $jenis['cash_oth2'], $jenis['name'],'J',$value->value);
                            $sum_cash_oth += (float) array_sum($cash_oth2);
                        }

                        if($jenis['cash_oth3']!= null) {
                            $cash_oth3 = $this->get_form_bahan_bakar_per_prk_update($input_draft_form_bahan_bakar,$int_input_distrik, $jenis['cash_oth3'], $jenis['name'],'J',$value->value);
                            $sum_cash_oth -= (float) array_sum($cash_oth3);
                        }

                        $temp['Cash (OTH)'] = $sum_cash_oth;

                        $daftar_prk_inti_form_bahan_bakar[$no_prk_inti]['Cash (OTH)'] += $sum_cash_oth;

                        $daftar_prk_parent_form_bahan_bakar[$no_prk_parent]['Cash (OTH)'] += $sum_cash_oth;

                        $daftar_bulan = $this->get_form_bahan_bakar_per_prk_update($input_draft_form_bahan_bakar,$int_input_distrik, 'H', $jenis['name'],'J',$value->value);
                        for($i_bulan = 1; $i_bulan<=12; $i_bulan++){
                            foreach ($daftar_bulan as $key_bulan => $bulan) {
                                if($i_bulan == $bulan){
                                    $temp[$nama_bulan[$i_bulan]] += (float) $beban_mat1[$key_bulan];
                                    if($jenis['beban_mat2']!= null)
                                        $temp[$nama_bulan[$i_bulan]] += (float) $beban_mat2[$key_bulan];
                                    if($jenis['beban_mat3']!= null)
                                        $temp[$nama_bulan[$i_bulan]] -= (float) $beban_mat3[$key_bulan];
                                    // $temp['disburse'][$i_bulan] += (float) $beban_mat1[$key_bulan];
                                    // if($jenis['beban_mat2']!= null)
                                    //     $temp['disburse'][$i_bulan] += (float) $beban_mat2[$key_bulan];
                                    // if($jenis['beban_mat3']!= null)
                                    //     $temp['disburse'][$i_bulan] -= (float) $beban_mat3[$key_bulan];
                                }
                            }
                        }
                        $daftar_prk_kegiatan_form_bahan_bakar[$jenis['name']] = $temp;
                    }

                }
                // dd($daftar_prk_kegiatan_form_bahan_bakar);
                foreach ($daftar_prk_kegiatan_form_bahan_bakar as $key => $value) {
                    array_push($prk_kegiatan_result, $value);
                }

                foreach ($daftar_prk_inti_form_bahan_bakar as $key => $value) {
                    $prk_inti_result[$key] = $value;
                }

                foreach ($daftar_prk_parent_form_bahan_bakar as $key => $value) {
                    $prk_parent_result[$key] = $value;
                }

                // dd(compact(
                //     'daftar_prk_parent_form_bahan_bakar',
                //     'daftar_prk_inti_form_bahan_bakar',
                //     'daftar_prk_kegiatan_form_bahan_bakar'
                // ));

            }

            //End of query form Bahan Bakar
            if($request->download && $request->type){
                $judul='';
                if($request->type=='excel'){
                    Excel::create('Loader Ellipse Pengendalian', function ($excel) use($sb, $fase, $input_tahun, $input_sb, $input_distrik, $input_lokasi, $input_fase, $input_draft_rkau, $input_draft_form_6_reimburse, $input_draft_form_6_rutin, $input_draft_form_10_pk, $input_draft_form_10_pu, $input_draft_form_10_pln, $input_draft_form_bahan_bakar, $input_draft_form_penyusutan, $name_draft_rkau, $name_draft_form_6_reimburse, $name_draft_form_6_rutin, $name_draft_form_10_pk, $name_draft_form_10_pu, $name_draft_form_10_pln, $name_draft_form_bahan_bakar, $name_draft_form_penyusutan, $nama_bln_dipilih, $prk_parent_result, $prk_inti_result, $prk_kegiatan_result) {
                            $excel->setTitle('Loader Ellipse Pengendalian');
                            $excel->setCreator('Laravel')->setCompany('Profio', 'PJB');
                            $excel->setDescription('Loader Ellipse Pengendalian');
                            $excel->sheet('Loader Ellipse Pengendalian', function ($sheet) use($sb, $fase, $input_tahun, $input_sb, $input_distrik, $input_lokasi, $input_fase, $input_draft_rkau, $input_draft_form_6_reimburse, $input_draft_form_6_rutin, $input_draft_form_10_pk, $input_draft_form_10_pu, $input_draft_form_10_pln, $input_draft_form_bahan_bakar, $input_draft_form_penyusutan, $name_draft_rkau, $name_draft_form_6_reimburse, $name_draft_form_6_rutin, $name_draft_form_10_pk, $name_draft_form_10_pu, $name_draft_form_10_pln, $name_draft_form_bahan_bakar, $name_draft_form_penyusutan, $nama_bln_dipilih, $prk_parent_result, $prk_inti_result, $prk_kegiatan_result){
                                $sheet->loadView('output/loader-ellipse-pengendalian-excel')
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
                                        ->with('input_draft_form_10_pk', $input_draft_form_10_pk)
                                        ->with('input_draft_form_10_pu', $input_draft_form_10_pu)
                                        ->with('input_draft_form_10_pln', $input_draft_form_10_pln)
                                        ->with('input_draft_form_bahan_bakar', $input_draft_form_bahan_bakar)
                                        ->with('input_draft_form_penyusutan', $input_draft_form_penyusutan)
                                        ->with('name_draft_rkau', $name_draft_rkau)
                                        ->with('name_draft_form_6_reimburse', $name_draft_form_6_reimburse)
                                        ->with('name_draft_form_6_rutin', $name_draft_form_6_rutin)
                                        ->with('name_draft_form_10_pk', $name_draft_form_10_pk)
                                        ->with('name_draft_form_10_pu', $name_draft_form_10_pu)
                                        ->with('name_draft_form_10_pln', $name_draft_form_10_pln)
                                        ->with('name_draft_form_bahan_bakar', $name_draft_form_bahan_bakar)
                                        ->with('name_draft_form_penyusutan', $name_draft_form_penyusutan)
                                        ->with('nama_bln_dipilih', $nama_bln_dipilih)
                                        ->with('prk_parent_result', $prk_parent_result)
                                        ->with('prk_inti_result', $prk_inti_result)
                                        ->with('prk_kegiatan_result', $prk_kegiatan_result);
                            });
                        })->download('xlsx');
                }
            }

        }

        return view('output/loader-ellipse-pengendalian', compact('sb', 'fase', 'input_tahun', 'input_sb', 'input_distrik', 'input_lokasi', 'input_fase', 'input_draft_rkau', 'input_draft_form_6_reimburse', 'input_draft_form_6_rutin', 'input_draft_form_10_pk', 'input_draft_form_10_pu', 'input_draft_form_10_pln', 'input_draft_form_penyusutan', 'input_draft_form_bahan_bakar', 'distrik', 'lokasi','tahun', 'draft_form_rkau', 'draft_form_penyusutan', 'draft_form_10_pln', 'draft_form_10_pu', 'draft_form_10_pk', 'draft_form_6_reimburse', 'draft_form_6_rutin', 'draft_form_bahan_bakar', 'name_draft_rkau', 'name_draft_form_6_reimburse', 'name_draft_form_6_rutin', 'name_draft_form_10_pk', 'name_draft_form_10_pu', 'name_draft_form_10_pln', 'name_draft_form_bahan_bakar', 'name_draft_form_penyusutan', 'nama_bln_dipilih', 'prk_parent_result', 'prk_inti_result', 'prk_kegiatan_result'));
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

    function get_drafts($id_jenis, $id_lokasi, $id_tahun){
        $drafts = DB::select("select distinct f.id, f.draft_versi
                                    from file_imports_ketetapan f
                                    join templates t on f.template_id = t.id
                                    join excel_datas e on e.file_import_id = f.id
                                    where t.jenis_id=".$id_jenis." and e.lokasi_id = ".$id_lokasi." and t.tahun= ".$id_tahun."
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

    function get_file_id_pengendalian($jenis_id, $tahun_anggaran, $distrik_id){
        $files = DB::select("select p.id
                            from pgdl_file_imports_revisi p
                            join pgdl_templates t on t.id = p.pgdl_template_id
                            where t.jenis_id = ".$jenis_id."
                            and p.tahun=".$tahun_anggaran."
                            and p.distrik_id = ".$distrik_id.";");
        if($files){
            $res = [];
            $i=0;
            foreach ($files as $key => $value) {
              $res[$i] = $value->id;
              $i++;
            }
            $res = implode(",", $res);
            //dd(var_dump($new));
            $res = "(".$res.")";
            return $res;
        }
        return $files;
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

    function get_form_6($file_import_id, $distrik_id, $kolom){
        // $query = DB::select("select e.row, e.value from excel_datas e join sheets s on s.id = e.sheet_id where s.name like 'I-Form 6' and e.file_import_id = ".$file_import_id." and e.lokasi_id = ".$lokasi_id." and e.kolom = '".$kolom."' order by e.row;");

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
    function get_form_6_inti($file_import_id, $distrik_id, $kolom){
        // $query = DB::select("select distinct SUBSTRING(e.value,1,8) as value from excel_datas e join sheets s on s.id = e.sheet_id where s.name like 'I-Form 6' and e.file_import_id = ".$file_import_id." and e.lokasi_id = ".$lokasi_id." and e.kolom = '".$kolom."' order by value asc;");

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

        return $query;
    }

    function get_form_6_parent($file_import_ids, $distrik_id, $kolom){
        // $query = DB::select("select distinct SUBSTRING(e.value,1,6) as value from excel_datas e join sheets s on s.id = e.sheet_id where s.name like 'I-Form 6' and e.file_import_id = ".$file_import_id." and e.lokasi_id = ".$lokasi_id." and e.kolom = '".$kolom."' order by value asc;");

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
    function get_form_6_disburse($file_import_id, $lokasi_id, $kolom1, $kolom2){
        $query = DB::select("select sum(case when value = '' then 0 else value::float end) as value from excel_datas e join sheets s on s.id = e.sheet_id where s.name like 'I-Form 6' and e.file_import_id = ".$file_import_id." and e.lokasi_id = ".$lokasi_id." and (e.kolom = '".$kolom1."' or e.kolom = '".$kolom2."') group by e.row");
        return $query;
    }

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
        // $query = DB::select("select sum(case when value = '' then 0 else value::float end) as value
        //                       from excel_datas e
        //                       join sheets s on s.id = e.sheet_id
        //                       join lokasi l on l.id = e.lokasi_id
        //                       where s.name like 'I-Form 6'
        //                       and e.file_import_id IN ".$file_import_id."
        //                       and l.distrik_id = ".$distrik_id."
        //                       and (e.kolom = '".$kolom1."' or e.kolom = '".$kolom2."') group by e.row, e.file_import_id");

        return $query;
    }

    function get_form_10($file_import_id, $distrik_id, $kolom){
        // $query = DB::select("select e.row, e.value from excel_datas e join sheets s on s.id = e.sheet_id where s.name like 'I-Form 10' and e.file_import_id = ".$file_import_id." and e.lokasi_id = ".$lokasi_id." and e.kolom = '".$kolom."' order by e.row;");

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
        // $query = DB::select("select distinct SUBSTRING(e.value,1,8) as value from excel_datas e join sheets s on s.id = e.sheet_id where s.name like 'I-Form 10' and e.file_import_id = ".$file_import_id." and e.lokasi_id = ".$lokasi_id." and e.kolom = '".$kolom."' order by value asc;");

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
        // $query = DB::select("select distinct SUBSTRING(e.value,1,6) as value from excel_datas e join sheets s on s.id = e.sheet_id where s.name like 'I-Form 10' and e.file_import_id = ".$file_import_id." and e.lokasi_id = ".$lokasi_id." and e.kolom = '".$kolom."' order by value asc;");

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
        // $query = DB::select("select (case when value = '' then 0 else value::float end) as value from excel_datas e join sheets s on s.id = e.sheet_id where s.name like 'I-Form 10' and e.file_import_id = ".$file_import_id." and e.lokasi_id = ".$lokasi_id." and e.kolom = '".$kolom."' ");

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

    function get_form_rkau($file_import_id, $distrik_id, $sheet, $kolom){
        // $query = DB::select("select e.row, e.value
        //                     from excel_datas e
        //                     join sheets s on s.id = e.sheet_id
        //                     where s.name like '".$sheet."'
        //                     and e.file_import_id = ".$file_import_id."
        //                     and e.lokasi_id = ".$lokasi_id."
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
        //                     where s.name like '".$sheet."'
        //                     and e.file_import_id = ".$file_import_id."
        //                     and e.lokasi_id = ".$lokasi_id."
        //                     and e.kolom = '".$kolom."'
        //                     and e.row > 12 order by value asc;");

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
        //                     where s.name like '".$sheet."'
        //                     and e.file_import_id = ".$file_import_id."
        //                     and e.lokasi_id = ".$lokasi_id."
        //                     and e.kolom = '".$kolom."'
        //                     and e.row > 12 order by value asc;");

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

        return $query;
    }
    function get_form_rkau_disburse($file_import_id, $lokasi_id, $sheet, $kolom){
        $query = DB::select("select (case when value = '' then 0 else value::float end) as value, e.row
                            from excel_datas e
                            join sheets s on s.id = e.sheet_id
                            where s.name like '".$sheet."'
                            and e.file_import_id = ".$file_import_id."
                            and e.lokasi_id = ".$lokasi_id."
                            and e.kolom = '".$kolom."'
                            and e.row > 12");
        $result = array();
        foreach ($query as $value) {
            $result[$value->row] = $value->value;
        }
        return $result;
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

    function get_prk_parent_desc($identity_parent, $lookup_cache = null){
        $idx = $identity_parent;
        if (is_array($lookup_cache) && isset($lookup_cache[$idx])) {
            return $lookup_cache[$idx];
        }
        $result = collect(\DB::select("select desc_prk_parent from prk_parent where identity_prk_parent_ppa like '".$identity_parent."' or identity_prk_parent_jom like '".$identity_parent."' or identity_prk_parent_usaha_lain  like '".$identity_parent."'"))->first();

        if (is_array($lookup_cache) && $result) {
            return $lookup_cache[$idx] = $result->desc_prk_parent;
        } else {
            return '';
        }
    }

    function get_prk_inti_desc($identity_parent, $identity_inti, $lookup_cache = null){
        $idx = $identity_parent . $identity_inti;
        if (is_array($lookup_cache) && isset($lookup_cache[$idx])) {
            return $lookup_cache[$idx];
        }
        $result = collect(\DB::select("select i.desc_prk_inti from prk_parent p join prk_inti i on p.id = i.prk_parent_id
                            where (identity_prk_parent_ppa like '".$identity_parent."' or identity_prk_parent_jom like '".$identity_parent."' or identity_prk_parent_usaha_lain  like '".$identity_parent."')
                            and identity_prk_inti like '".$identity_inti."'"))->first();

        if (is_array($lookup_cache) && $result) {
            return $lookup_cache[$idx] = $result->desc_prk_inti;
        } else {
            return '';
        }
    }

    function get_form_penyusutan($file_import_id, $distrik_id, $kolom){
        // $query = DB::select("select e.row, e.value from excel_datas e join sheets s on s.id = e.sheet_id where s.name like 'I-Penyusutan' and e.file_import_id = ".$file_import_id." and e.lokasi_id = ".$lokasi_id." and e.kolom = '".$kolom."' order by e.row;");

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
        // $query = DB::select("select distinct SUBSTRING(e.value,1,8) as value from excel_datas e join sheets s on s.id = e.sheet_id where s.name like 'I-Penyusutan' and e.file_import_id = ".$file_import_id." and e.lokasi_id = ".$lokasi_id." and e.kolom = '".$kolom."' order by value asc;");

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
        // $query = DB::select("select distinct SUBSTRING(e.value,1,6) as value from excel_datas e join sheets s on s.id = e.sheet_id where s.name like 'I-Penyusutan' and e.file_import_id = ".$file_import_id." and e.lokasi_id = ".$lokasi_id." and e.kolom = '".$kolom."' order by value asc;");

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
    function get_form_penyusutan_disburse($file_import_id, $lokasi_id, $kolom){
        $query = DB::select("select (case when value = '' then 0 else value::float end) as value from excel_datas e join sheets s on s.id = e.sheet_id where s.name like 'I-Penyusutan' and e.file_import_id = ".$file_import_id." and e.lokasi_id = ".$lokasi_id." and e.kolom = '".$kolom."' ");
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

    function get_form_bahan_bakar($file_import_id, $lokasi_id, $kolom, $jenis_bahan_bakar){
        $query = DB::select("select e.row, e.value from excel_datas e
                            join sheets s on s.id = e.sheet_id
                            where s.name like 'Database KIT (P+S+I)'
                            and e.file_import_id = ".$file_import_id."
                            and e.lokasi_id = ".$lokasi_id."
                            and e.kolom like '".$kolom."' and row in (select e.row from excel_datas e
                            join sheets s on s.id = e.sheet_id
                            where s.name like 'Database KIT (P+S+I)'
                            and e.file_import_id = ".$file_import_id."
                            and e.lokasi_id = ".$lokasi_id."
                            and e.kolom like 'AF' and e.value like '".$jenis_bahan_bakar."')");
        return $query;
    }
    function get_form_bahan_bakar_prk($file_import_id, $distrik_id, $kolom, $jenis_bahan_bakar, $level){
        if($level == 'kegiatan') $substr = 10;
        else if($level == 'inti') $substr = 8;
        else if($level == 'parent') $substr = 6;

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
                              (select e.row
                              from pgdl_excel_datas_revisi e
                              join pgdl_sheets s on s.id = e.pgdl_sheet_id
                              join lokasi l on l.id = e.lokasi_id
                              join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
                              join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
                              where s.name like 'Database KIT (P+S+I)'
                              and fk.id IN ".$file_import_id."
                              and l.distrik_id = ".$distrik_id."
                              and e.kolom like 'AF' and e.value like '".$jenis_bahan_bakar."');");

        return $query;
    }
    function get_form_bahan_bakar_per_prk($file_import_id, $lokasi_id, $kolom, $jenis_bahan_bakar, $kolom_prk, $prk){
        $query = DB::select("select e.row, e.value from excel_datas e
                            join sheets s on s.id = e.sheet_id
                            where s.name like 'Database KIT (P+S+I)'
                            and e.file_import_id = ".$file_import_id."
                            and e.lokasi_id = ".$lokasi_id."
                            and e.kolom like '".$kolom."' and row in (
                                select e.row from excel_datas e
                                join sheets s on s.id = e.sheet_id
                                where s.name like 'Database KIT (P+S+I)'
                                and e.file_import_id = ".$file_import_id."
                                and e.lokasi_id = ".$lokasi_id."
                                and e.kolom like '".$kolom_prk."' and e.value like '".$prk."' and row in (
                                    select e.row from excel_datas e
                                    join sheets s on s.id = e.sheet_id
                                    where s.name like 'Database KIT (P+S+I)'
                                    and e.file_import_id = ".$file_import_id."
                                    and e.lokasi_id = ".$lokasi_id."
                                    and e.kolom like 'AF' and e.value like '".$jenis_bahan_bakar."'
                                )
                            )");
        $result = array();
        foreach ($query as $value) {
            $result[$value->row] = $value->value;
        }
        return $result;
    }

    function get_form_bahan_bakar_per_prk_update($file_import_id, $distrik_id, $kolom, $jenis_bahan_bakar, $kolom_prk, $prk){

      // Query via pgdl
      // $query = DB::select("select e.row, e.value from pgdl_excel_datas_revisi e
      //                     join sheets s on s.id = e.sheet_id
      //                     join lokasi l on l.id = e.lokasi_id
      //                     join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
      //                     join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
      //                     where s.name like 'Database KIT (P+S+I)'
      //                     and fk.file_import_id IN ".$file_import_id."
      //                     and l.distrik_id = ".$distrik_id."
      //                     and e.kolom like '".$kolom."' and row in (
      //                         select e.row from pgdl_excel_datas_revisi e
      //                         join sheets s on s.id = e.sheet_id
      //                         join lokasi l on l.id = e.lokasi_id
      //                         join pgdl_file_imports_revisi fr on fr.id = e.pgdl_file_import_revisi_id
      //                         join file_imports_ketetapan fk on fk.id = fr.file_import_ketetapan_id
      //                         where s.name like 'Database KIT (P+S+I)'
      //                         and fk.file_import_id IN ".$file_import_id."
      //                         and l.distrik_id = ".$distrik_id."
      //                         and e.kolom like '".$kolom_prk."' and e.value like '".$prk."' and row in (
      //                             select e.row from pgdl_excel_datas_revisi e
      //                             join sheets s on s.id = e.sheet_id
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

    function get_data_pengendalian($file_id, $sheet_name, $kolom, $strategi_bisnis, $input_tahun){
        $query = "select e.row, e.value
                            from pgdl_excel_datas_revisi e
                            join pgdl_sheets s on s.id = e.pgdl_sheet_id
                            where s.name = '".$sheet_name."'
                            and e.pgdl_file_import_revisi_id in ".$file_id."
                            and e.kolom = '".$kolom."'
                            and e.row > 12 ";

        // daftar row yg tidak boleh ditampilkan (Jumlah, SubTotal, Total) yg ada di Form RKAU
        $ignore_row = array();
        $ignore_row['I-PEG'] = $this->get_ignore_row('I-PEG', $input_tahun);
        // $ignore_row['I-PEG'] = "(18, 44, 72, 74)";
        $ignore_row['I-ADM'] = $this->get_ignore_row('I-ADM', $input_tahun);
        // $ignore_row['I-ADM'] = "(34)";
        $ignore_row['I-PENDUKUNG EP'] = $this->get_ignore_row('I-PENDUKUNG EP', $input_tahun);
        // $ignore_row['I-PENDUKUNG EP'] = "(35)";
        $ignore_row['I-DILUAR USAHA'] = $this->get_ignore_row('I-DILUAR USAHA', $input_tahun);
        // $ignore_row['I-DILUAR USAHA'] = "(5, 39, 51, 52)";
        // dd($ignore_row);
        if(array_key_exists($sheet_name, $ignore_row))
            $query .= " and e.row not in ".$ignore_row[$sheet_name];

        if($sheet_name == "I-Pendapatan"){
            // khusus I-Pendapatan, OM hanya menampilkan pendapatan Jasa
            if($strategi_bisnis->name == "OM")
                $query .= " and e.row > 19";
            // khusus I-Pendapatan, UP hanya menampilkan pendapatan komponen
            else
                $query .= " and e.row < 20";
        }

        $query .= " order by row asc";

        $datas = DB::select($query);

        return $datas;
    }

    function get_ignore_row($sheet_name, $input_tahun) {
        // dd($sheet_name);
        $ignore_rows = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', 9)
        ->where('tahun', $input_tahun)
        ->where('pgdl_sheet_name', $sheet_name)->get();
        // dd($ignore_rows);
        foreach ($ignore_rows as $value) {
            $baris[] = $value->kolom;
        }

        $unique = array_unique($baris);

        $kalimat = implode(", ",$unique);
        $data = "($kalimat)";

        return $data;
    }

    // function get_data_pengendalian_disburse($file_id, $sheet_name, $kolom_start){
    //     $datas = DB::select("select e.row, e.value
    //                         from pgdl_excel_datas_revisi e
    //                         join sheets s on s.id = e.sheet_id
    //                         where s.name = '".$sheet_name."'
    //                         and e.pgdl_file_import_revisi_id in ".$file_id."
    //                         and e.kolom = '".$kolom."';");
    //     return $datas;
    // }

    function get_data_pengendalian_parent($file_id, $sheet_name, $kolom, $start_substring, $length_substring, $strategi_bisnis, $input_tahun){
        $query = "select distinct SUBSTRING(e.value,$start_substring,$length_substring) as value
                            from pgdl_excel_datas_revisi e
                            join pgdl_sheets s on s.id = e.pgdl_sheet_id
                            where s.name = '".$sheet_name."'
                            and e.pgdl_file_import_revisi_id in ".$file_id."
                            and e.kolom = '".$kolom."'
                            and e.row > 12";
        if($sheet_name == "I-Pendapatan"){
            // khusus I-Pendapatan, OM hanya menampilkan pendapatan Jasa
            if($strategi_bisnis->name == "OM")
                $query .= " and e.row > 19";
            // khusus I-Pendapatan, UP hanya menampilkan pendapatan komponen
            else
                $query .= " and e.row < 20";
        }
        // daftar row yg tidak boleh ditampilkan (Jumlah, SubTotal, Total) yg ada di Form RKAU
        $ignore_row = array();
        $ignore_row['I-PEG'] = $this->get_ignore_row('I-PEG', $input_tahun);
        // $ignore_row['I-PEG'] = "(18, 44, 72, 74)";
        $ignore_row['I-ADM'] = $this->get_ignore_row('I-ADM', $input_tahun);
        // $ignore_row['I-ADM'] = "(34)";
        $ignore_row['I-PENDUKUNG EP'] = $this->get_ignore_row('I-PENDUKUNG EP', $input_tahun);
        // $ignore_row['I-PENDUKUNG EP'] = "(35)";
        $ignore_row['I-DILUAR USAHA'] = $this->get_ignore_row('I-DILUAR USAHA', $input_tahun);
        // $ignore_row['I-DILUAR USAHA'] = "(5, 39, 51, 52)";

        // daftar row yg tidak boleh ditampilkan (Jumlah, SubTotal, Total) yg ada di Form RKAU
        // $ignore_row = array();
        // $ignore_row['I-PEG'] = "(18, 44, 72, 74)";
        // $ignore_row['I-ADM'] = "(34)";
        // $ignore_row['I-PENDUKUNG EP'] = "(35)";
        // $ignore_row['I-DILUAR USAHA'] = "(5, 39, 51, 52)";

        if(array_key_exists($sheet_name, $ignore_row))
            $query .= " and e.row not in ".$ignore_row[$sheet_name];

        $datas = DB::select($query);
        // dd("select distinct SUBSTRING(e.value,1,$count_substring) as value from pgdl_excel_datas_revisi e join sheets s on s.id = e.sheet_id where s.name = '".$sheet_name."'  and e.pgdl_file_import_revisi_id in ".$file_id."  and e.kolom = '".$kolom."';");
        return $datas;
    }

    function get_data_pengendalian_disburse($file_id, $sheet_name, $kolom, $input_tahun){
        $query = "select sum(case when value = '' then 0 else value::float end) as value
                            from pgdl_excel_datas_revisi e
                            join pgdl_sheets s on s.id = e.pgdl_sheet_id
                            where s.name = '".$sheet_name."'
                            and e.pgdl_file_import_revisi_id in ".$file_id."
                            and (e.kolom = '".$kolom."' or e.kolom = '".++$kolom."')
                            and e.row > 12 ";

        // daftar row yg tidak boleh ditampilkan (Jumlah, SubTotal, Total) yg ada di Form RKAU
        $ignore_row = array();
        $ignore_row['I-PEG'] = $this->get_ignore_row('I-PEG', $input_tahun);
        // $ignore_row['I-PEG'] = "(18, 44, 72, 74)";
        $ignore_row['I-ADM'] = $this->get_ignore_row('I-ADM', $input_tahun);
        // $ignore_row['I-ADM'] = "(34)";
        $ignore_row['I-PENDUKUNG EP'] = $this->get_ignore_row('I-PENDUKUNG EP', $input_tahun);
        // $ignore_row['I-PENDUKUNG EP'] = "(35)";
        $ignore_row['I-DILUAR USAHA'] = $this->get_ignore_row('I-DILUAR USAHA', $input_tahun);
        // $ignore_row['I-DILUAR USAHA'] = "(5, 39, 51, 52)";

        // $ignore_row['I-PEG'] = "(18, 44, 72, 74)";
        // $ignore_row['I-ADM'] = "(34)";
        // $ignore_row['I-PENDUKUNG EP'] = "(35)";
        // $ignore_row['I-DILUAR USAHA'] = "(5, 39, 51, 52)";

        if(array_key_exists($sheet_name, $ignore_row))
            $query .= " and e.row not in ".$ignore_row[$sheet_name];
        $query .= " group by e.row
                    order by e.row";

        $datas = DB::select($query);
        return $datas;

    }
}
