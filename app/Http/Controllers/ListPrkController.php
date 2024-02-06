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
Use DB;
use Illuminate\Support\Facades\Input;
use Excel;

class ListPrkController extends Controller
{
    public function List_Prk(Request $request)
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
        $input_lokasi = $request->input('lokasi');
        $int_input_lokasi = (int)$input_lokasi;
        $input_fase = $request->input('fase');

        $input_draft_rkau = $request->input('draft_rkau');
        $input_draft_form_6_reimburse = $request->input('draft_form_6_reimburse');
        $input_draft_form_6_rutin = $request->input('draft_form_6_rutin');
        $input_draft_form_10_pu = $request->input('draft_form_10_pu');
        $input_draft_form_10_pk = $request->input('draft_form_10_pk');
        $input_draft_form_10_pln = $request->input('draft_form_10_pln');
        $input_draft_form_bahan_bakar = $request->input('draft_form_bahan_bakar');
        $input_draft_form_penyusutan = $request->input('draft_form_penyusutan');
            
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
        if ($request->input('fase') != NULL) {
            $input_fase = DB::table('fases')->select('name','id')->where('id', $request->fase)->get()[0];
        }
        if ($request->input('draft_rkau') != NULL) {
            $input_draft_rkau = DB::table('file_imports')->select('draft_versi', 'id', 'name')->where('id', $request->draft_rkau)->get()[0];
            $draft_form_rkau = $this->get_drafts(1,$int_input_lokasi,$input_tahun);
        }
        if ($request->input('draft_form_6_reimburse') != NULL) {
            $input_draft_form_6_reimburse = DB::table('file_imports')->select('draft_versi', 'id', 'name')->where('id', $request->draft_form_6_reimburse)->get()[0];
            $draft_form_6_reimburse = $this->get_drafts(2,$int_input_lokasi,$input_tahun);
        }
        if ($request->input('draft_form_6_rutin') != NULL) {
            $input_draft_form_6_rutin = DB::table('file_imports')->select('draft_versi', 'id', 'name')->where('id', $request->draft_form_6_rutin)->get()[0];
            $draft_form_6_rutin = $this->get_drafts(3,$int_input_lokasi,$input_tahun);
        }
        if ($request->input('draft_form_10_pu') != NULL) {
            $input_draft_form_10_pu = DB::table('file_imports')->select('draft_versi', 'id', 'name')->where('id', $request->draft_form_10_pu)->get()[0];
            $draft_form_10_pu = $this->get_drafts(4,$int_input_lokasi,$input_tahun);
        }
        if ($request->input('draft_form_10_pk') != NULL) {
            $input_draft_form_10_pk = DB::table('file_imports')->select('draft_versi', 'id', 'name')->where('id', $request->draft_form_10_pk)->get()[0];
            $draft_form_10_pk = $this->get_drafts(5,$int_input_lokasi,$input_tahun);
        }
        if ($request->input('draft_form_10_pln') != NULL) {
            $input_draft_form_10_pln = DB::table('file_imports')->select('draft_versi', 'id', 'name')->where('id', $request->draft_form_10_pln)->get()[0];   
            $draft_form_10_pln = $this->get_drafts(6,$int_input_lokasi,$input_tahun);
        }
        if ($request->input('draft_form_bahan_bakar') != NULL) {
            $input_draft_form_bahan_bakar = DB::table('file_imports')->select('draft_versi', 'id', 'name')->where('id', $request->draft_form_bahan_bakar)->get()[0];   
            $draft_form_bahan_bakar = $this->get_drafts(7,$int_input_lokasi,$input_tahun);
        }
        if ($request->input('draft_form_penyusutan') != NULL) {
            $input_draft_form_penyusutan = DB::table('file_imports')->select('draft_versi', 'id', 'name')->where('id', $request->draft_form_penyusutan)->get()[0];   
            $draft_form_penyusutan = $this->get_drafts(9,$int_input_lokasi,$input_tahun);
        }


        if ($input_lokasi != NULL) {

            $dataparent = array();
            $datainti = array();
            $datakegiatan = array();

        //Start query I-PEG
        if($request->input('draft_rkau')) {
            $form_rkau_ipeg_prk_parent       = $this->get_form_rkau_parent($request->input('draft_rkau'), $int_input_lokasi, 'I-PEG', 'E');
            $daftar_prk_parent_form_rkau_ipeg = array();
            foreach ($form_rkau_ipeg_prk_parent as $key => $value) {
                $desc_prk_parent = $this->get_prk_parent_desc(substr($value->value, 2,2));
                $daftar_prk_parent_form_rkau_ipeg[$value->value] = array(
                    'desc_prk_parent' => ($desc_prk_parent!= null ? $desc_prk_parent->desc_prk_parent : ''), 
                    'beban_mat'     => 0,
                    'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'total_year_estimate'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    );
            }
            
            $form_rkau_ipeg_prk_inti       = $this->get_form_rkau_inti($request->input('draft_rkau'), $int_input_lokasi, 'I-PEG', 'E');
            $daftar_prk_inti_form_rkau_ipeg = array();
            foreach ($form_rkau_ipeg_prk_inti as $key => $value) {
                $desc_prk_inti = $this->get_prk_inti_desc(substr($value->value, 2,2),substr($value->value, 4,2));
                $daftar_prk_inti_form_rkau_ipeg[$value->value] = array(
                    'desc_prk_inti' => ($desc_prk_inti!= null ? $desc_prk_inti->desc_prk_inti : ''), 
                    'prk_parent'    => substr($value->value, 0,4),
                    'beban_mat'     => 0,
                    'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'total_year_estimate'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    );
            }

            $daftar_prk_kegiatan_form_rkau_ipeg = array();
            $form_rkau_ipeg_prk_kegiatan       = $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-PEG', 'E');
            $form_rkau_ipeg_desc_prk_kegiatan   = $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-PEG', 'F');
            $form_rkau_ipeg_beban_mat           = $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-PEG', 'H');
            $form_rkau_ipeg_cash_oth_1          = $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-PEG', 'J');
            $form_rkau_ipeg_cash_oth_2          = $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-PEG', 'K');
            $form_rkau_ipeg_total_year_estimates= $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-PEG', 'H');
            
            $form_rkau_ipeg_disburse = array();
            $form_rkau_ipeg_disburse[1] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-PEG', 'M');
            $form_rkau_ipeg_disburse[2] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-PEG', 'N');    
            $form_rkau_ipeg_disburse[3] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-PEG', 'O');    
            $form_rkau_ipeg_disburse[4] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-PEG', 'P');    
            $form_rkau_ipeg_disburse[5] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-PEG', 'Q');    
            $form_rkau_ipeg_disburse[6] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-PEG', 'R');    
            $form_rkau_ipeg_disburse[7] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-PEG', 'S');    
            $form_rkau_ipeg_disburse[8] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-PEG', 'T');    
            $form_rkau_ipeg_disburse[9] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-PEG', 'U');    
            $form_rkau_ipeg_disburse[10] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-PEG', 'V');    
            $form_rkau_ipeg_disburse[11] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-PEG', 'W');    
            $form_rkau_ipeg_disburse[12] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-PEG', 'X');

            foreach ($form_rkau_ipeg_prk_kegiatan as $key => $value) {
                $parent = substr($value,0,4);
                $inti = substr($value,0,6);
                
                $disburse = array();
                for($bulan=1; $bulan<=12; $bulan++){
                    $disburse[$bulan] = (float)$form_rkau_ipeg_disburse[$bulan][$key];
                    // $daftar_prk_inti_form_rkau_ipeg[$inti]['disburse'][$bulan] += $disburse[$bulan];
                    // $daftar_prk_parent_form_rkau_ipeg[$parent]['disburse'][$bulan] += $disburse[$bulan];
                }
                
                $temp = array(
                    'prk_kegiatan' => $value,
                    'prk_inti' => $inti,
                    'prk_parent' => $parent,
                    'desc_prk_kegiatan' => $form_rkau_ipeg_desc_prk_kegiatan[$key],
                    // 'desc_prk_inti' => $form_rkau_ipeg_desc_prk_inti[$key],
                    // 'desc_prk_parent' => $form_rkau_ipeg_desc_prk_parent[$key],
                    'beban_mat' => (float)$form_rkau_ipeg_beban_mat[$key],
                    'cash_oth' => ((float)$form_rkau_ipeg_cash_oth_1[$key]) + ((float)$form_rkau_ipeg_cash_oth_2[$key]),
                    'ijin_proses' => 0,
                    'disburse' => $disburse,
                    'total_year_estimate' => (float)$form_rkau_ipeg_total_year_estimates[$key],
                );
                array_push($daftar_prk_kegiatan_form_rkau_ipeg, $temp);
                
                // $daftar_prk_inti_form_rkau_ipeg[$inti]['desc_prk_inti'] = $temp['desc_prk_inti'];
                $daftar_prk_inti_form_rkau_ipeg[$inti]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_inti_form_rkau_ipeg[$inti]['cash_oth'] += $temp['cash_oth'];
                $daftar_prk_inti_form_rkau_ipeg[$inti]['ijin_proses'] += $temp['ijin_proses'];
                $daftar_prk_inti_form_rkau_ipeg[$inti]['total_year_estimate'] += $temp['total_year_estimate'];

                // $daftar_prk_parent_form_rkau_ipeg[$parent]['desc_prk_parent'] = $temp['desc_prk_parent'];
                $daftar_prk_parent_form_rkau_ipeg[$parent]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_parent_form_rkau_ipeg[$parent]['cash_oth'] += $temp['cash_oth'];
                $daftar_prk_parent_form_rkau_ipeg[$parent]['ijin_proses'] += $temp['ijin_proses'];
                $daftar_prk_parent_form_rkau_ipeg[$parent]['total_year_estimate'] += $temp['total_year_estimate'];
            }
            $dataparent['form_rkau_ipeg'] = $daftar_prk_parent_form_rkau_ipeg;
            $datainti['form_rkau_ipeg'] = $daftar_prk_inti_form_rkau_ipeg;
            $datakegiatan['form_rkau_ipeg'] = $daftar_prk_kegiatan_form_rkau_ipeg;
            
        // //End of query I-PEG


        //Start query I-ADM
            $form_rkau_iadm_prk_parent       = $this->get_form_rkau_parent($request->input('draft_rkau'), $int_input_lokasi, 'I-ADM', 'E');
            $daftar_prk_parent_form_rkau_iadm = array();
            foreach ($form_rkau_iadm_prk_parent as $key => $value) {
                $desc_prk_parent = $this->get_prk_parent_desc(substr($value->value, 2,2));
                $daftar_prk_parent_form_rkau_iadm[$value->value] = array(
                    'desc_prk_parent' => ($desc_prk_parent!= null ? $desc_prk_parent->desc_prk_parent : ''), 
                    'beban_mat'     => 0,
                    'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'total_year_estimate'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    );
            }
            
            $form_rkau_iadm_prk_inti       = $this->get_form_rkau_inti($request->input('draft_rkau'), $int_input_lokasi, 'I-ADM', 'E');
            $daftar_prk_inti_form_rkau_iadm = array();
            foreach ($form_rkau_iadm_prk_inti as $key => $value) {
                $desc_prk_inti = $this->get_prk_inti_desc(substr($value->value, 2,2),substr($value->value, 4,2));
                $daftar_prk_inti_form_rkau_iadm[$value->value] = array(
                    'desc_prk_inti' => ($desc_prk_inti != null ? $desc_prk_inti->desc_prk_inti : ''), 
                    'prk_parent'    => substr($value->value, 0,4),
                    'beban_mat'     => 0,
                    'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'total_year_estimate'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    );
            }

            $daftar_prk_kegiatan_form_rkau_iadm = array();
            $form_rkau_iadm_prk_kegiatan       = $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-ADM', 'E');
            $form_rkau_iadm_desc_prk_kegiatan   = $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-ADM', 'F');
            $form_rkau_iadm_beban_mat           = $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-ADM', 'H');
            $form_rkau_iadm_cash_oth_1          = $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-ADM', 'J');
            $form_rkau_iadm_cash_oth_2          = $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-ADM', 'K');
            $form_rkau_iadm_total_year_estimates= $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-ADM', 'H');
            
            $form_rkau_iadm_disburse = array();
            $form_rkau_iadm_disburse[1] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-ADM', 'M');
            $form_rkau_iadm_disburse[2] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-ADM', 'N');    
            $form_rkau_iadm_disburse[3] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-ADM', 'O');    
            $form_rkau_iadm_disburse[4] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-ADM', 'P');    
            $form_rkau_iadm_disburse[5] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-ADM', 'Q');    
            $form_rkau_iadm_disburse[6] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-ADM', 'R');    
            $form_rkau_iadm_disburse[7] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-ADM', 'S');    
            $form_rkau_iadm_disburse[8] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-ADM', 'T');    
            $form_rkau_iadm_disburse[9] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-ADM', 'U');    
            $form_rkau_iadm_disburse[10] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-ADM', 'V');    
            $form_rkau_iadm_disburse[11] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-ADM', 'W');    
            $form_rkau_iadm_disburse[12] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-ADM', 'X');

            foreach ($form_rkau_iadm_prk_kegiatan as $key => $value) {
                $parent = substr($value,0,4);
                $inti = substr($value,0,6);
                
                $disburse = array();
                for($bulan=1; $bulan<=12; $bulan++){
                    $disburse[$bulan] = (float)$form_rkau_iadm_disburse[$bulan][$key];
                    // $daftar_prk_inti_form_rkau_iadm[$inti]['disburse'][$bulan] += $disburse[$bulan];
                    // $daftar_prk_parent_form_rkau_iadm[$parent]['disburse'][$bulan] += $disburse[$bulan];
                }
                
                $temp = array(
                    'prk_kegiatan' => $value,
                    'prk_inti' => $inti,
                    'prk_parent' => $parent,
                    'desc_prk_kegiatan' => $form_rkau_iadm_desc_prk_kegiatan[$key],
                    // 'desc_prk_inti' => $form_rkau_iadm_desc_prk_inti[$key],
                    // 'desc_prk_parent' => $form_rkau_iadm_desc_prk_parent[$key],
                    'beban_mat' => (float)$form_rkau_iadm_beban_mat[$key],
                    'cash_oth' => ((float)$form_rkau_iadm_cash_oth_1[$key]) + ((float)$form_rkau_iadm_cash_oth_2[$key]),
                    'ijin_proses' => 0,
                    'disburse' => $disburse,
                    'total_year_estimate' => (float)$form_rkau_iadm_total_year_estimates[$key],
                );
                array_push($daftar_prk_kegiatan_form_rkau_iadm, $temp);
                
                // $daftar_prk_inti_form_rkau_iadm[$inti]['desc_prk_inti'] = $temp['desc_prk_inti'];
                $daftar_prk_inti_form_rkau_iadm[$inti]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_inti_form_rkau_iadm[$inti]['cash_oth'] += $temp['cash_oth'];
                $daftar_prk_inti_form_rkau_iadm[$inti]['ijin_proses'] += $temp['ijin_proses'];
                $daftar_prk_inti_form_rkau_iadm[$inti]['total_year_estimate'] += $temp['total_year_estimate'];

                // $daftar_prk_parent_form_rkau_iadm[$parent]['desc_prk_parent'] = $temp['desc_prk_parent'];
                $daftar_prk_parent_form_rkau_iadm[$parent]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_parent_form_rkau_iadm[$parent]['cash_oth'] += $temp['cash_oth'];
                $daftar_prk_parent_form_rkau_iadm[$parent]['ijin_proses'] += $temp['ijin_proses'];
                $daftar_prk_parent_form_rkau_iadm[$parent]['total_year_estimate'] += $temp['total_year_estimate'];
            }
            $dataparent['form_rkau_iadm'] = $daftar_prk_parent_form_rkau_iadm;
            $datainti['form_rkau_iadm'] = $daftar_prk_inti_form_rkau_iadm;
            $datakegiatan['form_rkau_iadm'] = $daftar_prk_kegiatan_form_rkau_iadm;
            
        // //End of query I-ADM

        //Start query I-PENDUKUNG EP
            
            $form_rkau_ipendukungep_prk_parent       = $this->get_form_rkau_parent($request->input('draft_rkau'), $int_input_lokasi, 'I-PENDUKUNG EP', 'C');
            $daftar_prk_parent_form_rkau_ipendukungep = array();
            foreach ($form_rkau_ipendukungep_prk_parent as $key => $value) {
                $desc_prk_parent = $this->get_prk_parent_desc(substr($value->value, 2,2));
                $daftar_prk_parent_form_rkau_ipendukungep[$value->value] = array(
                    'desc_prk_parent' => ($desc_prk_parent!= null ? $desc_prk_parent->desc_prk_parent : ''), 
                    'beban_mat'     => 0,
                    'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'total_year_estimate'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    );
            }
            
            $form_rkau_ipendukungep_prk_inti       = $this->get_form_rkau_inti($request->input('draft_rkau'), $int_input_lokasi, 'I-PENDUKUNG EP', 'C');
            $daftar_prk_inti_form_rkau_ipendukungep = array();
            foreach ($form_rkau_ipendukungep_prk_inti as $key => $value) {
                $desc_prk_inti = $this->get_prk_inti_desc(substr($value->value, 2,2),substr($value->value, 4,2));
                $daftar_prk_inti_form_rkau_ipendukungep[$value->value] = array(
                    'desc_prk_inti' => ($desc_prk_inti != null ? $desc_prk_inti->desc_prk_inti : ''), 
                    'prk_parent'    => substr($value->value, 0,4),
                    'beban_mat'     => 0,
                    'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'total_year_estimate'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    );
            }

            $daftar_prk_kegiatan_form_rkau_ipendukungep = array();
            $form_rkau_ipendukungep_prk_kegiatan       = $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-PENDUKUNG EP', 'C');
            $form_rkau_ipendukungep_desc_prk_kegiatan   = $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-PENDUKUNG EP', 'D');
            $form_rkau_ipendukungep_beban_mat           = $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-PENDUKUNG EP', 'E');
            $form_rkau_ipendukungep_cash_oth          = $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-PENDUKUNG EP', 'F');
            $form_rkau_ipendukungep_ijin_proses          = $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-PENDUKUNG EP', 'H');
            $form_rkau_ipendukungep_total_year_estimates= $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-PENDUKUNG EP', 'E');
            
            $form_rkau_ipendukungep_disburse = array();
            $form_rkau_ipendukungep_disburse[1] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-PENDUKUNG EP', 'K');
            $form_rkau_ipendukungep_disburse[2] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-PENDUKUNG EP', 'L');    
            $form_rkau_ipendukungep_disburse[3] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-PENDUKUNG EP', 'M');    
            $form_rkau_ipendukungep_disburse[4] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-PENDUKUNG EP', 'N');    
            $form_rkau_ipendukungep_disburse[5] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-PENDUKUNG EP', 'O');    
            $form_rkau_ipendukungep_disburse[6] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-PENDUKUNG EP', 'P');    
            $form_rkau_ipendukungep_disburse[7] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-PENDUKUNG EP', 'Q');    
            $form_rkau_ipendukungep_disburse[8] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-PENDUKUNG EP', 'R');    
            $form_rkau_ipendukungep_disburse[9] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-PENDUKUNG EP', 'S');    
            $form_rkau_ipendukungep_disburse[10] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-PENDUKUNG EP', 'T');    
            $form_rkau_ipendukungep_disburse[11] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-PENDUKUNG EP', 'U');    
            $form_rkau_ipendukungep_disburse[12] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-PENDUKUNG EP', 'V');

            foreach ($form_rkau_ipendukungep_prk_kegiatan as $key => $value) {
                $parent = substr($value,0,4);
                $inti = substr($value,0,6);
                
                $disburse = array();
                for($bulan=1; $bulan<=12; $bulan++){
                    $disburse[$bulan] = (float)$form_rkau_ipendukungep_disburse[$bulan][$key];
                    // $daftar_prk_inti_form_rkau_ipendukungep[$inti]['disburse'][$bulan] += $disburse[$bulan];
                    // $daftar_prk_parent_form_rkau_ipendukungep[$parent]['disburse'][$bulan] += $disburse[$bulan];
                }
                
                $temp = array(
                    'prk_kegiatan' => $value,
                    'prk_inti' => $inti,
                    'prk_parent' => $parent,
                    'desc_prk_kegiatan' => $form_rkau_ipendukungep_desc_prk_kegiatan[$key],
                    // 'desc_prk_inti' => $form_rkau_ipendukungep_desc_prk_inti[$key],
                    // 'desc_prk_parent' => $form_rkau_ipendukungep_desc_prk_parent[$key],
                    'beban_mat' => (float)$form_rkau_ipendukungep_beban_mat[$key],
                    'cash_oth' => (float)$form_rkau_ipendukungep_cash_oth[$key],
                    'ijin_proses' =>(float)$form_rkau_ipendukungep_ijin_proses[$key],
                    'disburse' => $disburse,
                    'total_year_estimate' => (float)$form_rkau_ipendukungep_total_year_estimates[$key],
                );
                array_push($daftar_prk_kegiatan_form_rkau_ipendukungep, $temp);
                
                // $daftar_prk_inti_form_rkau_ipendukungep[$inti]['desc_prk_inti'] = $temp['desc_prk_inti'];
                $daftar_prk_inti_form_rkau_ipendukungep[$inti]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_inti_form_rkau_ipendukungep[$inti]['cash_oth'] += $temp['cash_oth'];
                $daftar_prk_inti_form_rkau_ipendukungep[$inti]['ijin_proses'] += $temp['ijin_proses'];
                $daftar_prk_inti_form_rkau_ipendukungep[$inti]['total_year_estimate'] += $temp['total_year_estimate'];

                // $daftar_prk_parent_form_rkau_ipendukungep[$parent]['desc_prk_parent'] = $temp['desc_prk_parent'];
                $daftar_prk_parent_form_rkau_ipendukungep[$parent]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_parent_form_rkau_ipendukungep[$parent]['cash_oth'] += $temp['cash_oth'];
                $daftar_prk_parent_form_rkau_ipendukungep[$parent]['ijin_proses'] += $temp['ijin_proses'];
                $daftar_prk_parent_form_rkau_ipendukungep[$parent]['total_year_estimate'] += $temp['total_year_estimate'];
            }
            $dataparent['form_rkau_ipendukungep'] = $daftar_prk_parent_form_rkau_ipendukungep;
            $datainti['form_rkau_ipendukungep'] = $daftar_prk_inti_form_rkau_ipendukungep;
            $datakegiatan['form_rkau_ipendukungep'] = $daftar_prk_kegiatan_form_rkau_ipendukungep;
            
        // //End of query I-PENDUKUNG EP

        //Start query I-BIAYA USAHA LAINNYA
            $form_rkau_ibiayausahalainnya_prk_parent       = $this->get_form_rkau_parent($request->input('draft_rkau'), $int_input_lokasi, 'I-BIAYA USAHA LAINNYA', 'E');
            $daftar_prk_parent_form_rkau_ibiayausahalainnya = array();
            foreach ($form_rkau_ibiayausahalainnya_prk_parent as $key => $value) {
                $desc_prk_parent = $this->get_prk_parent_desc(substr($value->value, 2,2));
                $daftar_prk_parent_form_rkau_ibiayausahalainnya[$value->value] = array(
                    'desc_prk_parent' => ($desc_prk_parent!= null ? $desc_prk_parent->desc_prk_parent : ''), 
                    'beban_mat'     => 0,
                    'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'total_year_estimate'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    );
            }
            
            $form_rkau_ibiayausahalainnya_prk_inti       = $this->get_form_rkau_inti($request->input('draft_rkau'), $int_input_lokasi, 'I-BIAYA USAHA LAINNYA', 'E');
            $daftar_prk_inti_form_rkau_ibiayausahalainnya = array();
            foreach ($form_rkau_ibiayausahalainnya_prk_inti as $key => $value) {
                $desc_prk_inti = $this->get_prk_inti_desc(substr($value->value, 2,2),substr($value->value, 4,2));
                $daftar_prk_inti_form_rkau_ibiayausahalainnya[$value->value] = array(
                    'desc_prk_inti' => ($desc_prk_inti != null ? $desc_prk_inti->desc_prk_inti : ''), 
                    'prk_parent'    => substr($value->value, 0,4),
                    'beban_mat'     => 0,
                    'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'total_year_estimate'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    );
            }

            $daftar_prk_kegiatan_form_rkau_ibiayausahalainnya = array();
            $form_rkau_ibiayausahalainnya_prk_kegiatan       = $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-BIAYA USAHA LAINNYA', 'E');
            $form_rkau_ibiayausahalainnya_desc_prk_kegiatan   = $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-BIAYA USAHA LAINNYA', 'F');
            $form_rkau_ibiayausahalainnya_beban_mat           = $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-BIAYA USAHA LAINNYA', 'H');
            $form_rkau_ibiayausahalainnya_cash_oth_1          = $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-BIAYA USAHA LAINNYA', 'J');
            $form_rkau_ibiayausahalainnya_cash_oth_2          = $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-BIAYA USAHA LAINNYA', 'K');
            $form_rkau_ibiayausahalainnya_total_year_estimates= $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-BIAYA USAHA LAINNYA', 'H');
            
            $form_rkau_ibiayausahalainnya_disburse = array();
            $form_rkau_ibiayausahalainnya_disburse[1] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-BIAYA USAHA LAINNYA', 'M');
            $form_rkau_ibiayausahalainnya_disburse[2] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-BIAYA USAHA LAINNYA', 'N');    
            $form_rkau_ibiayausahalainnya_disburse[3] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-BIAYA USAHA LAINNYA', 'O');    
            $form_rkau_ibiayausahalainnya_disburse[4] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-BIAYA USAHA LAINNYA', 'P');    
            $form_rkau_ibiayausahalainnya_disburse[5] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-BIAYA USAHA LAINNYA', 'Q');    
            $form_rkau_ibiayausahalainnya_disburse[6] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-BIAYA USAHA LAINNYA', 'R');    
            $form_rkau_ibiayausahalainnya_disburse[7] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-BIAYA USAHA LAINNYA', 'S');    
            $form_rkau_ibiayausahalainnya_disburse[8] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-BIAYA USAHA LAINNYA', 'T');    
            $form_rkau_ibiayausahalainnya_disburse[9] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-BIAYA USAHA LAINNYA', 'U');    
            $form_rkau_ibiayausahalainnya_disburse[10] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-BIAYA USAHA LAINNYA', 'V');    
            $form_rkau_ibiayausahalainnya_disburse[11] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-BIAYA USAHA LAINNYA', 'W');    
            $form_rkau_ibiayausahalainnya_disburse[12] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-BIAYA USAHA LAINNYA', 'X');

            foreach ($form_rkau_ibiayausahalainnya_prk_kegiatan as $key => $value) {
                $parent = substr($value,0,4);
                $inti = substr($value,0,6);
                
                $disburse = array();
                for($bulan=1; $bulan<=12; $bulan++){
                    $disburse[$bulan] = (float)$form_rkau_ibiayausahalainnya_disburse[$bulan][$key];
                    // $daftar_prk_inti_form_rkau_ibiayausahalainnya[$inti]['disburse'][$bulan] += $disburse[$bulan];
                    // $daftar_prk_parent_form_rkau_ibiayausahalainnya[$parent]['disburse'][$bulan] += $disburse[$bulan];
                }
                
                $temp = array(
                    'prk_kegiatan' => $value,
                    'prk_inti' => $inti,
                    'prk_parent' => $parent,
                    'desc_prk_kegiatan' => $form_rkau_ibiayausahalainnya_desc_prk_kegiatan[$key],
                    // 'desc_prk_inti' => $form_rkau_ibiayausahalainnya_desc_prk_inti[$key],
                    // 'desc_prk_parent' => $form_rkau_ibiayausahalainnya_desc_prk_parent[$key],
                    'beban_mat' => (float)$form_rkau_ibiayausahalainnya_beban_mat[$key],
                    'cash_oth' => ((float)$form_rkau_ibiayausahalainnya_cash_oth_1[$key]) + ((float)$form_rkau_ibiayausahalainnya_cash_oth_2[$key]),
                    'ijin_proses' => 0,
                    'disburse' => $disburse,
                    'total_year_estimate' => (float)$form_rkau_ibiayausahalainnya_total_year_estimates[$key],
                );
                array_push($daftar_prk_kegiatan_form_rkau_ibiayausahalainnya, $temp);
                
                // $daftar_prk_inti_form_rkau_ibiayausahalainnya[$inti]['desc_prk_inti'] = $temp['desc_prk_inti'];
                $daftar_prk_inti_form_rkau_ibiayausahalainnya[$inti]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_inti_form_rkau_ibiayausahalainnya[$inti]['cash_oth'] += $temp['cash_oth'];
                $daftar_prk_inti_form_rkau_ibiayausahalainnya[$inti]['ijin_proses'] += $temp['ijin_proses'];
                $daftar_prk_inti_form_rkau_ibiayausahalainnya[$inti]['total_year_estimate'] += $temp['total_year_estimate'];

                // $daftar_prk_parent_form_rkau_ibiayausahalainnya[$parent]['desc_prk_parent'] = $temp['desc_prk_parent'];
                $daftar_prk_parent_form_rkau_ibiayausahalainnya[$parent]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_parent_form_rkau_ibiayausahalainnya[$parent]['cash_oth'] += $temp['cash_oth'];
                $daftar_prk_parent_form_rkau_ibiayausahalainnya[$parent]['ijin_proses'] += $temp['ijin_proses'];
                $daftar_prk_parent_form_rkau_ibiayausahalainnya[$parent]['total_year_estimate'] += $temp['total_year_estimate'];
            }
            $dataparent['form_rkau_ibiayausahalainnya'] = $daftar_prk_parent_form_rkau_ibiayausahalainnya;
            $datainti['form_rkau_ibiayausahalainnya'] = $daftar_prk_inti_form_rkau_ibiayausahalainnya;
            $datakegiatan['form_rkau_ibiayausahalainnya'] = $daftar_prk_kegiatan_form_rkau_ibiayausahalainnya;
            
        // //End of query I-BIAYA USAHA LAINNYA

        //Start query I-DILUAR USAHA
            $form_rkau_idiluarusaha_prk_parent       = $this->get_form_rkau_parent($request->input('draft_rkau'), $int_input_lokasi, 'I-DILUAR USAHA', 'E');
            $daftar_prk_parent_form_rkau_idiluarusaha = array();
            foreach ($form_rkau_idiluarusaha_prk_parent as $key => $value) {
                $desc_prk_parent = $this->get_prk_parent_desc(substr($value->value, 2,2));
                $daftar_prk_parent_form_rkau_idiluarusaha[$value->value] = array(
                    'desc_prk_parent' => ($desc_prk_parent!= null ? $desc_prk_parent->desc_prk_parent : ''), 
                    'beban_mat'     => 0,
                    'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'total_year_estimate'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    );
            }
            
            $form_rkau_idiluarusaha_prk_inti       = $this->get_form_rkau_inti($request->input('draft_rkau'), $int_input_lokasi, 'I-DILUAR USAHA', 'E');
            $daftar_prk_inti_form_rkau_idiluarusaha = array();
            foreach ($form_rkau_idiluarusaha_prk_inti as $key => $value) {
                $desc_prk_inti = $this->get_prk_inti_desc(substr($value->value, 2,2),substr($value->value, 4,2));
                $daftar_prk_inti_form_rkau_idiluarusaha[$value->value] = array(
                    'desc_prk_inti' => ($desc_prk_inti != null ? $desc_prk_inti->desc_prk_inti : ''), 
                    'prk_parent'    => substr($value->value, 0,4),
                    'beban_mat'     => 0,
                    'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'total_year_estimate'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    );
            }

            $daftar_prk_kegiatan_form_rkau_idiluarusaha = array();
            $form_rkau_idiluarusaha_prk_kegiatan       = $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-DILUAR USAHA', 'E');
            $form_rkau_idiluarusaha_desc_prk_kegiatan   = $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-DILUAR USAHA', 'F');
            $form_rkau_idiluarusaha_beban_mat           = $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-DILUAR USAHA', 'H');
            $form_rkau_idiluarusaha_cash_oth_1          = $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-DILUAR USAHA', 'J');
            $form_rkau_idiluarusaha_cash_oth_2          = $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-DILUAR USAHA', 'K');
            $form_rkau_idiluarusaha_total_year_estimates= $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-DILUAR USAHA', 'H');
            
            $form_rkau_idiluarusaha_disburse = array();
            $form_rkau_idiluarusaha_disburse[1] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-DILUAR USAHA', 'M');
            $form_rkau_idiluarusaha_disburse[2] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-DILUAR USAHA', 'N');    
            $form_rkau_idiluarusaha_disburse[3] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-DILUAR USAHA', 'O');    
            $form_rkau_idiluarusaha_disburse[4] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-DILUAR USAHA', 'P');    
            $form_rkau_idiluarusaha_disburse[5] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-DILUAR USAHA', 'Q');    
            $form_rkau_idiluarusaha_disburse[6] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-DILUAR USAHA', 'R');    
            $form_rkau_idiluarusaha_disburse[7] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-DILUAR USAHA', 'S');    
            $form_rkau_idiluarusaha_disburse[8] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-DILUAR USAHA', 'T');    
            $form_rkau_idiluarusaha_disburse[9] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-DILUAR USAHA', 'U');    
            $form_rkau_idiluarusaha_disburse[10] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-DILUAR USAHA', 'V');    
            $form_rkau_idiluarusaha_disburse[11] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-DILUAR USAHA', 'W');    
            $form_rkau_idiluarusaha_disburse[12] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-DILUAR USAHA', 'X');

            foreach ($form_rkau_idiluarusaha_prk_kegiatan as $key => $value) {
                $parent = substr($value,0,4);
                $inti = substr($value,0,6);
                
                $disburse = array();
                for($bulan=1; $bulan<=12; $bulan++){
                    $disburse[$bulan] = (float)$form_rkau_idiluarusaha_disburse[$bulan][$key];
                    // $daftar_prk_inti_form_rkau_idiluarusaha[$inti]['disburse'][$bulan] += $disburse[$bulan];
                    // $daftar_prk_parent_form_rkau_idiluarusaha[$parent]['disburse'][$bulan] += $disburse[$bulan];
                }
                
                $temp = array(
                    'prk_kegiatan' => $value,
                    'prk_inti' => $inti,
                    'prk_parent' => $parent,
                    'desc_prk_kegiatan' => $form_rkau_idiluarusaha_desc_prk_kegiatan[$key],
                    // 'desc_prk_inti' => $form_rkau_idiluarusaha_desc_prk_inti[$key],
                    // 'desc_prk_parent' => $form_rkau_idiluarusaha_desc_prk_parent[$key],
                    'beban_mat' => (float)$form_rkau_idiluarusaha_beban_mat[$key],
                    'cash_oth' => ((float)$form_rkau_idiluarusaha_cash_oth_1[$key]) + ((float)$form_rkau_idiluarusaha_cash_oth_2[$key]),
                    'ijin_proses' => 0,
                    'disburse' => $disburse,
                    'total_year_estimate' => (float)$form_rkau_idiluarusaha_total_year_estimates[$key],
                );
                array_push($daftar_prk_kegiatan_form_rkau_idiluarusaha, $temp);
                
                // $daftar_prk_inti_form_rkau_idiluarusaha[$inti]['desc_prk_inti'] = $temp['desc_prk_inti'];
                $daftar_prk_inti_form_rkau_idiluarusaha[$inti]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_inti_form_rkau_idiluarusaha[$inti]['cash_oth'] += $temp['cash_oth'];
                $daftar_prk_inti_form_rkau_idiluarusaha[$inti]['ijin_proses'] += $temp['ijin_proses'];
                $daftar_prk_inti_form_rkau_idiluarusaha[$inti]['total_year_estimate'] += $temp['total_year_estimate'];

                // $daftar_prk_parent_form_rkau_idiluarusaha[$parent]['desc_prk_parent'] = $temp['desc_prk_parent'];
                $daftar_prk_parent_form_rkau_idiluarusaha[$parent]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_parent_form_rkau_idiluarusaha[$parent]['cash_oth'] += $temp['cash_oth'];
                $daftar_prk_parent_form_rkau_idiluarusaha[$parent]['ijin_proses'] += $temp['ijin_proses'];
                $daftar_prk_parent_form_rkau_idiluarusaha[$parent]['total_year_estimate'] += $temp['total_year_estimate'];
            }
            $dataparent['form_rkau_idiluarusaha'] = $daftar_prk_parent_form_rkau_idiluarusaha;
            $datainti['form_rkau_idiluarusaha'] = $daftar_prk_inti_form_rkau_idiluarusaha;
            $datakegiatan['form_rkau_idiluarusaha'] = $daftar_prk_kegiatan_form_rkau_idiluarusaha;
            
        // //End of query I-DILUAR USAHA

        //Start query I-Pendapatan
            $form_rkau_ipendapatan_prk_parent       = $this->get_form_rkau_parent($request->input('draft_rkau'), $int_input_lokasi, 'I-Pendapatan', 'E');
            $daftar_prk_parent_form_rkau_ipendapatan = array();
            foreach ($form_rkau_ipendapatan_prk_parent as $key => $value) {
                $desc_prk_parent = $this->get_prk_parent_desc(substr($value->value, 2,2));
                $daftar_prk_parent_form_rkau_ipendapatan[$value->value] = array(
                    'desc_prk_parent' => ($desc_prk_parent!= null ? $desc_prk_parent->desc_prk_parent : ''), 
                    'beban_mat'     => 0,
                    'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'total_year_estimate'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    );
            }
            
            $form_rkau_ipendapatan_prk_inti       = $this->get_form_rkau_inti($request->input('draft_rkau'), $int_input_lokasi, 'I-Pendapatan', 'E');
            $daftar_prk_inti_form_rkau_ipendapatan = array();
            foreach ($form_rkau_ipendapatan_prk_inti as $key => $value) {
                $desc_prk_inti = $this->get_prk_inti_desc(substr($value->value, 2,2),substr($value->value, 4,2));
                $daftar_prk_inti_form_rkau_ipendapatan[$value->value] = array(
                    'desc_prk_inti' => ($desc_prk_inti != null ? $desc_prk_inti->desc_prk_inti : ''), 
                    'prk_parent'    => substr($value->value, 0,4),
                    'beban_mat'     => 0,
                    'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'total_year_estimate'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    );
            }

            $daftar_prk_kegiatan_form_rkau_ipendapatan = array();
            $form_rkau_ipendapatan_prk_kegiatan       = $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-Pendapatan', 'E');
            $form_rkau_ipendapatan_desc_prk_kegiatan   = $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-Pendapatan', 'F');
            $form_rkau_ipendapatan_beban_mat           = $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-Pendapatan', 'H');
            $form_rkau_ipendapatan_cash_oth_1          = $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-Pendapatan', 'J');
            $form_rkau_ipendapatan_cash_oth_2          = $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-Pendapatan', 'K');
            $form_rkau_ipendapatan_total_year_estimates= $this->get_form_rkau($request->input('draft_rkau'), $int_input_lokasi, 'I-Pendapatan', 'H');
            
            $form_rkau_ipendapatan_disburse = array();
            $form_rkau_ipendapatan_disburse[1] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-Pendapatan', 'M');
            $form_rkau_ipendapatan_disburse[2] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-Pendapatan', 'N');    
            $form_rkau_ipendapatan_disburse[3] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-Pendapatan', 'O');    
            $form_rkau_ipendapatan_disburse[4] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-Pendapatan', 'P');    
            $form_rkau_ipendapatan_disburse[5] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-Pendapatan', 'Q');    
            $form_rkau_ipendapatan_disburse[6] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-Pendapatan', 'R');    
            $form_rkau_ipendapatan_disburse[7] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-Pendapatan', 'S');    
            $form_rkau_ipendapatan_disburse[8] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-Pendapatan', 'T');    
            $form_rkau_ipendapatan_disburse[9] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-Pendapatan', 'U');    
            $form_rkau_ipendapatan_disburse[10] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-Pendapatan', 'V');    
            $form_rkau_ipendapatan_disburse[11] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-Pendapatan', 'W');    
            $form_rkau_ipendapatan_disburse[12] = $this->get_form_rkau_disburse($request->input('draft_rkau'), $int_input_lokasi, 'I-Pendapatan', 'X');

            foreach ($form_rkau_ipendapatan_prk_kegiatan as $key => $value) {
                $parent = substr($value,0,4);
                $inti = substr($value,0,6);
                
                $disburse = array();
                for($bulan=1; $bulan<=12; $bulan++){
                    $disburse[$bulan] = (float)$form_rkau_ipendapatan_disburse[$bulan][$key];
                    // $daftar_prk_inti_form_rkau_ipendapatan[$inti]['disburse'][$bulan] += $disburse[$bulan];
                    // $daftar_prk_parent_form_rkau_ipendapatan[$parent]['disburse'][$bulan] += $disburse[$bulan];
                }
                
                $temp = array(
                    'prk_kegiatan' => $value,
                    'prk_inti' => $inti,
                    'prk_parent' => $parent,
                    'desc_prk_kegiatan' => $form_rkau_ipendapatan_desc_prk_kegiatan[$key],
                    // 'desc_prk_inti' => $form_rkau_ipendapatan_desc_prk_inti[$key],
                    // 'desc_prk_parent' => $form_rkau_ipendapatan_desc_prk_parent[$key],
                    'beban_mat' => (float)$form_rkau_ipendapatan_beban_mat[$key],
                    'cash_oth' => ((float)$form_rkau_ipendapatan_cash_oth_1[$key]) + ((float)$form_rkau_ipendapatan_cash_oth_2[$key]),
                    'ijin_proses' => 0,
                    'disburse' => $disburse,
                    'total_year_estimate' => (float)$form_rkau_ipendapatan_total_year_estimates[$key],
                );
                array_push($daftar_prk_kegiatan_form_rkau_ipendapatan, $temp);
                
                // $daftar_prk_inti_form_rkau_ipendapatan[$inti]['desc_prk_inti'] = $temp['desc_prk_inti'];
                $daftar_prk_inti_form_rkau_ipendapatan[$inti]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_inti_form_rkau_ipendapatan[$inti]['cash_oth'] += $temp['cash_oth'];
                $daftar_prk_inti_form_rkau_ipendapatan[$inti]['ijin_proses'] += $temp['ijin_proses'];
                $daftar_prk_inti_form_rkau_ipendapatan[$inti]['total_year_estimate'] += $temp['total_year_estimate'];

                // $daftar_prk_parent_form_rkau_ipendapatan[$parent]['desc_prk_parent'] = $temp['desc_prk_parent'];
                $daftar_prk_parent_form_rkau_ipendapatan[$parent]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_parent_form_rkau_ipendapatan[$parent]['cash_oth'] += $temp['cash_oth'];
                $daftar_prk_parent_form_rkau_ipendapatan[$parent]['ijin_proses'] += $temp['ijin_proses'];
                $daftar_prk_parent_form_rkau_ipendapatan[$parent]['total_year_estimate'] += $temp['total_year_estimate'];
            }
            $dataparent['form_rkau_ipendapatan'] = $daftar_prk_parent_form_rkau_ipendapatan;
            $datainti['form_rkau_ipendapatan'] = $daftar_prk_inti_form_rkau_ipendapatan;
            $datakegiatan['form_rkau_ipendapatan'] = $daftar_prk_kegiatan_form_rkau_ipendapatan;
           
        } //end of cek request draft rkau 
        // //End of query I-Pendapatan

        //Start Form 6 Reimbuse
        if($request->input('draft_form_6_reimburse')) {
            $count_6_reimburse = DB::select("select count(e.row) from excel_datas e join sheets s on s.id = e.sheet_id where s.name like 'I-Form 6' and e.file_import_id = ".$request->input('draft_form_6_reimburse')." and e.lokasi_id = ".$int_input_lokasi." and e.kolom = 'AM';")[0]->count;
            $int_count_6_reimburse = (int)$count_6_reimburse;

            $daftar_prk_kegiatan_form_6_reimburse = array();
            $daftar_prk_inti_form_6_reimburse = array();
            $daftar_prk_parent_form_6_reimburse = array();
            
            $form_6_reimburse_prk_parent = $this->get_form_6_parent($request->input('draft_form_6_reimburse'),$int_input_lokasi, 'I');
            foreach ($form_6_reimburse_prk_parent as $key => $value) {
                $daftar_prk_parent_form_6_reimburse[$value->value] = array('desc_prk_parent' => '', 
                    'beban_mat'     => 0,
                    'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    'total_year_estimate' => 0,

                    );
            }

            $form_6_reimburse_prk_inti = $this->get_form_6_inti($request->input('draft_form_6_reimburse'),$int_input_lokasi, 'I');
            foreach ($form_6_reimburse_prk_inti as $key => $value) {
                $daftar_prk_inti_form_6_reimburse[$value->value] = array('desc_prk_inti' => '', 
                    'prk_parent'    => substr($value->value, 0, 6),
                    'beban_mat'     => 0,
                    'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    'total_year_estimate' => 0,
                );
            }

            $form_6_reimburse_no_prk_kegiatan = $this->get_form_6($request->input('draft_form_6_reimburse'),$int_input_lokasi, 'I');
            $form_6_reimburse_desc_prk_kegiatan = $this->get_form_6($request->input('draft_form_6_reimburse'),$int_input_lokasi, 'T');
            $form_6_reimburse_desc_prk_inti = $this->get_form_6($request->input('draft_form_6_reimburse'),$int_input_lokasi, 'S');
            $form_6_reimburse_desc_prk_parent = $this->get_form_6($request->input('draft_form_6_reimburse'),$int_input_lokasi, 'R');
            $form_6_reimburse_beban_mat = $this->get_form_6($request->input('draft_form_6_reimburse'),$int_input_lokasi, 'AN');
            $form_6_reimburse_total_year_estimate = $this->get_form_6($request->input('draft_form_6_reimburse'),$int_input_lokasi, 'AN');
            $form_6_reimburse_cash_oth = $this->get_form_6($request->input('draft_form_6_reimburse'),$int_input_lokasi, 'AV');
            $form_6_reimburse_ijin_proses = $this->get_form_6($request->input('draft_form_6_reimburse'),$int_input_lokasi, 'AX');
            $form_6_reimburse_disburse = array();
            $form_6_reimburse_disburse[1] = $this->get_form_6_disburse($request->input('draft_form_6_reimburse'),$int_input_lokasi, 'BA', 'BB');
            $form_6_reimburse_disburse[2] = $this->get_form_6_disburse($request->input('draft_form_6_reimburse'),$int_input_lokasi, 'BC', 'BD');
            $form_6_reimburse_disburse[3] = $this->get_form_6_disburse($request->input('draft_form_6_reimburse'),$int_input_lokasi, 'BE', 'BF');
            $form_6_reimburse_disburse[4] = $this->get_form_6_disburse($request->input('draft_form_6_reimburse'),$int_input_lokasi, 'BG', 'BH');
            $form_6_reimburse_disburse[5] = $this->get_form_6_disburse($request->input('draft_form_6_reimburse'),$int_input_lokasi, 'BI', 'BJ');
            $form_6_reimburse_disburse[6] = $this->get_form_6_disburse($request->input('draft_form_6_reimburse'),$int_input_lokasi, 'BK', 'BL');
            $form_6_reimburse_disburse[7] = $this->get_form_6_disburse($request->input('draft_form_6_reimburse'),$int_input_lokasi, 'BM', 'BN');
            $form_6_reimburse_disburse[8] = $this->get_form_6_disburse($request->input('draft_form_6_reimburse'),$int_input_lokasi, 'BO', 'BP');
            $form_6_reimburse_disburse[9] = $this->get_form_6_disburse($request->input('draft_form_6_reimburse'),$int_input_lokasi, 'BQ', 'BR');
            $form_6_reimburse_disburse[10] = $this->get_form_6_disburse($request->input('draft_form_6_reimburse'),$int_input_lokasi, 'BS', 'BT');
            $form_6_reimburse_disburse[11] = $this->get_form_6_disburse($request->input('draft_form_6_reimburse'),$int_input_lokasi, 'BU', 'BV');
            $form_6_reimburse_disburse[12] = $this->get_form_6_disburse($request->input('draft_form_6_reimburse'),$int_input_lokasi, 'BW', 'BX');
            
            for($i=0; $i<$int_count_6_reimburse; $i++){
                $parent = substr($form_6_reimburse_no_prk_kegiatan[$i]->value,0,6);
                $inti = substr($form_6_reimburse_no_prk_kegiatan[$i]->value,0,8);
                
                $disburse = array();
                for($bulan=1; $bulan<=12; $bulan++){
                    $disburse[$bulan] = (float)$form_6_reimburse_disburse[$bulan][$i]->value;
                    // $daftar_prk_inti_form_6_reimburse[$inti]['disburse'][$bulan] += $disburse[$bulan];
                    // $daftar_prk_parent_form_6_reimburse[$parent]['disburse'][$bulan] += $disburse[$bulan];
                }
                
                $temp = array(
                    'prk_kegiatan' => $form_6_reimburse_no_prk_kegiatan[$i]->value,
                    'prk_inti' => $inti,
                    'prk_parent' => $parent,
                    'desc_prk_kegiatan' => $form_6_reimburse_desc_prk_kegiatan[$i]->value,
                    'desc_prk_inti' => $form_6_reimburse_desc_prk_inti[$i]->value,
                    'desc_prk_parent' => $form_6_reimburse_desc_prk_parent[$i]->value,
                    'beban_mat' => (float)$form_6_reimburse_beban_mat[$i]->value,
                    'cash_oth' => (float)$form_6_reimburse_cash_oth[$i]->value,
                    'ijin_proses' => (float)$form_6_reimburse_ijin_proses[$i]->value,
                    'disburse' => $disburse,
                    'total_year_estimate' => (float)$form_6_reimburse_total_year_estimate[$i]->value,
                );
                array_push($daftar_prk_kegiatan_form_6_reimburse, $temp);
                
                $daftar_prk_inti_form_6_reimburse[$inti]['desc_prk_inti'] = $temp['desc_prk_inti'];
                $daftar_prk_inti_form_6_reimburse[$inti]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_inti_form_6_reimburse[$inti]['cash_oth'] += $temp['cash_oth'];
                $daftar_prk_inti_form_6_reimburse[$inti]['ijin_proses'] += $temp['ijin_proses'];

                $daftar_prk_parent_form_6_reimburse[$parent]['desc_prk_parent'] = $temp['desc_prk_parent'];
                $daftar_prk_parent_form_6_reimburse[$parent]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_parent_form_6_reimburse[$parent]['cash_oth'] += $temp['cash_oth'];
                $daftar_prk_parent_form_6_reimburse[$parent]['ijin_proses'] += $temp['ijin_proses'];
            }
            $dataparent['form_6_reimburse'] = $daftar_prk_parent_form_6_reimburse;
            $datainti['form_6_reimburse'] = $daftar_prk_inti_form_6_reimburse;
            $datakegiatan['form_6_reimburse'] = $daftar_prk_kegiatan_form_6_reimburse;

        } //end of cek request draft form 6 reimburse
        //End of query form 6 reimburse

        //Start Form 6 Rutin
        if($request->input('draft_form_6_rutin')) {
            $count_6_rutin = DB::select("select count(e.row) from excel_datas e join sheets s on s.id = e.sheet_id where s.name like 'I-Form 6' and e.file_import_id = ".$request->input('draft_form_6_rutin')." and e.lokasi_id = ".$int_input_lokasi." and e.kolom = 'AM';")[0]->count;
            $int_count_6_rutin = (int)$count_6_rutin;

            $daftar_prk_kegiatan_form_6_rutin = array();
            $daftar_prk_inti_form_6_rutin = array();
            $daftar_prk_parent_form_6_rutin = array();
            
            $form_6_rutin_prk_parent = $this->get_form_6_parent($request->input('draft_form_6_rutin'),$int_input_lokasi, 'I');
            foreach ($form_6_rutin_prk_parent as $key => $value) {
                $daftar_prk_parent_form_6_rutin[$value->value] = array('desc_prk_parent' => '', 
                    'beban_mat'     => 0,
                    'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    'total_year_estimate' => 0,
                    );
            }

            $form_6_rutin_prk_inti = $this->get_form_6_inti($request->input('draft_form_6_rutin'),$int_input_lokasi, 'I');
            foreach ($form_6_rutin_prk_inti as $key => $value) {
                $daftar_prk_inti_form_6_rutin[$value->value] = array('desc_prk_inti' => '', 
                    'prk_parent'    => substr($value->value, 0, 6),
                    'beban_mat'     => 0,
                    'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    'total_year_estimate' => 0,
                );
            }

            $form_6_rutin_no_prk_kegiatan = $this->get_form_6($request->input('draft_form_6_rutin'),$int_input_lokasi, 'I');
            $form_6_rutin_desc_prk_kegiatan = $this->get_form_6($request->input('draft_form_6_rutin'),$int_input_lokasi, 'T');
            $form_6_rutin_desc_prk_inti = $this->get_form_6($request->input('draft_form_6_rutin'),$int_input_lokasi, 'S');
            $form_6_rutin_desc_prk_parent = $this->get_form_6($request->input('draft_form_6_rutin'),$int_input_lokasi, 'R');
            $form_6_rutin_beban_mat = $this->get_form_6($request->input('draft_form_6_rutin'),$int_input_lokasi, 'AN');
            $form_6_rutin_total_year_estimate = $this->get_form_6($request->input('draft_form_6_rutin'),$int_input_lokasi, 'AN');
            $form_6_rutin_cash_oth = $this->get_form_6($request->input('draft_form_6_rutin'),$int_input_lokasi, 'AV');
            $form_6_rutin_ijin_proses = $this->get_form_6($request->input('draft_form_6_rutin'),$int_input_lokasi, 'AX');
            $form_6_rutin_disburse = array();
            $form_6_rutin_disburse[1] = $this->get_form_6_disburse($request->input('draft_form_6_rutin'),$int_input_lokasi, 'BA', 'BB');
            $form_6_rutin_disburse[2] = $this->get_form_6_disburse($request->input('draft_form_6_rutin'),$int_input_lokasi, 'BC', 'BD');
            $form_6_rutin_disburse[3] = $this->get_form_6_disburse($request->input('draft_form_6_rutin'),$int_input_lokasi, 'BE', 'BF');
            $form_6_rutin_disburse[4] = $this->get_form_6_disburse($request->input('draft_form_6_rutin'),$int_input_lokasi, 'BG', 'BH');
            $form_6_rutin_disburse[5] = $this->get_form_6_disburse($request->input('draft_form_6_rutin'),$int_input_lokasi, 'BI', 'BJ');
            $form_6_rutin_disburse[6] = $this->get_form_6_disburse($request->input('draft_form_6_rutin'),$int_input_lokasi, 'BK', 'BL');
            $form_6_rutin_disburse[7] = $this->get_form_6_disburse($request->input('draft_form_6_rutin'),$int_input_lokasi, 'BM', 'BN');
            $form_6_rutin_disburse[8] = $this->get_form_6_disburse($request->input('draft_form_6_rutin'),$int_input_lokasi, 'BO', 'BP');
            $form_6_rutin_disburse[9] = $this->get_form_6_disburse($request->input('draft_form_6_rutin'),$int_input_lokasi, 'BQ', 'BR');
            $form_6_rutin_disburse[10] = $this->get_form_6_disburse($request->input('draft_form_6_rutin'),$int_input_lokasi, 'BS', 'BT');
            $form_6_rutin_disburse[11] = $this->get_form_6_disburse($request->input('draft_form_6_rutin'),$int_input_lokasi, 'BU', 'BV');
            $form_6_rutin_disburse[12] = $this->get_form_6_disburse($request->input('draft_form_6_rutin'),$int_input_lokasi, 'BW', 'BX');
            
            for($i=0; $i<$int_count_6_rutin; $i++){
                $parent = substr($form_6_rutin_no_prk_kegiatan[$i]->value,0,6);
                $inti = substr($form_6_rutin_no_prk_kegiatan[$i]->value,0,8);
                
                $disburse = array();
                for($bulan=1; $bulan<=12; $bulan++){
                    $disburse[$bulan] = (float)$form_6_rutin_disburse[$bulan][$i]->value;
                    // $daftar_prk_inti_form_6_rutin[$inti]['disburse'][$bulan] += $disburse[$bulan];
                    // $daftar_prk_parent_form_6_rutin[$parent]['disburse'][$bulan] += $disburse[$bulan];
                }
                
                $temp = array(
                    'prk_kegiatan' => $form_6_rutin_no_prk_kegiatan[$i]->value,
                    'prk_inti' => $inti,
                    'prk_parent' => $parent,
                    'desc_prk_kegiatan' => $form_6_rutin_desc_prk_kegiatan[$i]->value,
                    'desc_prk_inti' => $form_6_rutin_desc_prk_inti[$i]->value,
                    'desc_prk_parent' => $form_6_rutin_desc_prk_parent[$i]->value,
                    'beban_mat' => (float)$form_6_rutin_beban_mat[$i]->value,
                    'cash_oth' => (float)$form_6_rutin_cash_oth[$i]->value,
                    'ijin_proses' => (float)$form_6_rutin_ijin_proses[$i]->value,
                    'disburse' => $disburse,
                    'total_year_estimate' => (float)$form_6_rutin_total_year_estimate[$i]->value,
                );
                array_push($daftar_prk_kegiatan_form_6_rutin, $temp);
                
                $daftar_prk_inti_form_6_rutin[$inti]['desc_prk_inti'] = $temp['desc_prk_inti'];
                $daftar_prk_inti_form_6_rutin[$inti]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_inti_form_6_rutin[$inti]['cash_oth'] += $temp['cash_oth'];
                $daftar_prk_inti_form_6_rutin[$inti]['ijin_proses'] += $temp['ijin_proses'];

                $daftar_prk_parent_form_6_rutin[$parent]['desc_prk_parent'] = $temp['desc_prk_parent'];
                $daftar_prk_parent_form_6_rutin[$parent]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_parent_form_6_rutin[$parent]['cash_oth'] += $temp['cash_oth'];
                $daftar_prk_parent_form_6_rutin[$parent]['ijin_proses'] += $temp['ijin_proses'];
            }
            $dataparent['form_6_rutin'] = $daftar_prk_parent_form_6_rutin;
            $datainti['form_6_rutin'] = $daftar_prk_inti_form_6_rutin;
            $datakegiatan['form_6_rutin'] = $daftar_prk_kegiatan_form_6_rutin;

        } //end of cek request draft form 6 rutin
        //End of query form 6 rutin


        //Start Form 10 Penguatan KIT
        if($request->input('draft_form_10_pk')) {
            $count_10_pk = DB::select("select count(e.row) from excel_datas e join sheets s on s.id = e.sheet_id where s.name like 'I-Form 10' and e.file_import_id = ".$request->input('draft_form_10_pk')." and e.lokasi_id = ".$int_input_lokasi." and e.kolom = 'AM';")[0]->count;
            $int_count_10_pk = (int)$count_10_pk;

            $daftar_prk_kegiatan_form_10_pk = array();
            $daftar_prk_inti_form_10_pk = array();
            $daftar_prk_parent_form_10_pk = array();
            
            $form_10_pk_prk_parent = $this->get_form_10_parent($request->input('draft_form_10_pk'),$int_input_lokasi, 'H');
            foreach ($form_10_pk_prk_parent as $key => $value) {
                $daftar_prk_parent_form_10_pk[$value->value] = array('desc_prk_parent' => '', 
                    'beban_mat'     => 0,
                    'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'total_year_estimate'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    'total_year_estimate' => 0,
                    );
            }

            $form_10_pk_prk_inti = $this->get_form_10_inti($request->input('draft_form_10_pk'),$int_input_lokasi, 'H');
            foreach ($form_10_pk_prk_inti as $key => $value) {
                $daftar_prk_inti_form_10_pk[$value->value] = array('desc_prk_inti' => '', 
                    'prk_parent'    => substr($value->value, 0, 6),
                    'beban_mat'     => 0,
                    'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'total_year_estimate'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    'total_year_estimate' => 0,
                );
            }

            $form_10_pk_no_prk_kegiatan = $this->get_form_10($request->input('draft_form_10_pk'),$int_input_lokasi, 'H');
            $form_10_pk_desc_prk_kegiatan = $this->get_form_10($request->input('draft_form_10_pk'),$int_input_lokasi, 'S');
            $form_10_pk_desc_prk_inti = $this->get_form_10($request->input('draft_form_10_pk'),$int_input_lokasi, 'R');
            $form_10_pk_desc_prk_parent = $this->get_form_10($request->input('draft_form_10_pk'),$int_input_lokasi, 'Q');
            $form_10_pk_beban_mat = $this->get_form_10($request->input('draft_form_10_pk'),$int_input_lokasi, 'AI');
            $form_10_pk_total_year_estimate = $this->get_form_10($request->input('draft_form_10_pk'),$int_input_lokasi, 'AT');
            // $form_10_pk_cash_oth = $this->get_form_10($request->input('draft_form_10_pk'),$int_input_lokasi, 'AV');
            // $form_10_pk_ijin_proses = $this->get_form_10($request->input('draft_form_10_pk'),$int_input_lokasi, 'AX');
            $form_10_pk_disburse = array();
            $form_10_pk_disburse[1] = $this->get_form_10_disburse($request->input('draft_form_10_pk'),$int_input_lokasi, 'BC');
            $form_10_pk_disburse[2] = $this->get_form_10_disburse($request->input('draft_form_10_pk'),$int_input_lokasi, 'BD');
            $form_10_pk_disburse[3] = $this->get_form_10_disburse($request->input('draft_form_10_pk'),$int_input_lokasi, 'BE');
            $form_10_pk_disburse[4] = $this->get_form_10_disburse($request->input('draft_form_10_pk'),$int_input_lokasi, 'BF');
            $form_10_pk_disburse[5] = $this->get_form_10_disburse($request->input('draft_form_10_pk'),$int_input_lokasi, 'BG');
            $form_10_pk_disburse[6] = $this->get_form_10_disburse($request->input('draft_form_10_pk'),$int_input_lokasi, 'BH');
            $form_10_pk_disburse[7] = $this->get_form_10_disburse($request->input('draft_form_10_pk'),$int_input_lokasi, 'BI');
            $form_10_pk_disburse[8] = $this->get_form_10_disburse($request->input('draft_form_10_pk'),$int_input_lokasi, 'BJ');
            $form_10_pk_disburse[9] = $this->get_form_10_disburse($request->input('draft_form_10_pk'),$int_input_lokasi, 'BK');
            $form_10_pk_disburse[10] = $this->get_form_10_disburse($request->input('draft_form_10_pk'),$int_input_lokasi, 'BL');
            $form_10_pk_disburse[11] = $this->get_form_10_disburse($request->input('draft_form_10_pk'),$int_input_lokasi, 'BM');
            $form_10_pk_disburse[12] = $this->get_form_10_disburse($request->input('draft_form_10_pk'),$int_input_lokasi, 'BN');
            
            for($i=0; $i<$int_count_10_pk; $i++){
                $parent = substr($form_10_pk_no_prk_kegiatan[$i]->value,0,6);
                $inti = substr($form_10_pk_no_prk_kegiatan[$i]->value,0,8);
                
                $disburse = array();
                for($bulan=1; $bulan<=12; $bulan++){
                    $disburse[$bulan] = (float)$form_10_pk_disburse[$bulan][$i]->value;
                    // $daftar_prk_inti_form_10_pk[$inti]['disburse'][$bulan] += $disburse[$bulan];
                    // $daftar_prk_parent_form_10_pk[$parent]['disburse'][$bulan] += $disburse[$bulan];
                }
                
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
                    'total_year_estimate' => (float)$form_10_pk_total_year_estimate[$i]->value,
                    'disburse' => $disburse,
                );
                array_push($daftar_prk_kegiatan_form_10_pk, $temp);
                
                $daftar_prk_inti_form_10_pk[$inti]['desc_prk_inti'] = $temp['desc_prk_inti'];
                $daftar_prk_inti_form_10_pk[$inti]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_inti_form_10_pk[$inti]['total_year_estimate'] += $temp['total_year_estimate'];

                $daftar_prk_parent_form_10_pk[$parent]['desc_prk_parent'] = $temp['desc_prk_parent'];
                $daftar_prk_parent_form_10_pk[$parent]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_parent_form_10_pk[$parent]['total_year_estimate'] += $temp['total_year_estimate'];
            }
            $dataparent['form_10_pk'] = $daftar_prk_parent_form_10_pk;
            $datainti['form_10_pk'] = $daftar_prk_inti_form_10_pk;
            $datakegiatan['form_10_pk'] = $daftar_prk_kegiatan_form_10_pk;

        } //end of cek request draft form 10 kit
        //End of query form 10 Penguatan KIT

        

        //Start Form 10 Pengembangan Usaha
        if($request->input('draft_form_10_pu')) {
            $count_10_pu = DB::select("select count(e.row) from excel_datas e join sheets s on s.id = e.sheet_id where s.name like 'I-Form 10' and e.file_import_id = ".$request->input('draft_form_10_pu')." and e.lokasi_id = ".$int_input_lokasi." and e.kolom = 'AM';")[0]->count;
            $int_count_10_pu = (int)$count_10_pu;

            $daftar_prk_kegiatan_form_10_pu = array();
            $daftar_prk_inti_form_10_pu = array();
            $daftar_prk_parent_form_10_pu = array();
            
            $form_10_pu_prk_parent = $this->get_form_10_parent($request->input('draft_form_10_pu'),$int_input_lokasi, 'I');
            foreach ($form_10_pu_prk_parent as $key => $value) {
                $daftar_prk_parent_form_10_pu[$value->value] = array('desc_prk_parent' => '', 
                    'beban_mat'     => 0,
                    'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'total_year_estimate'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    );
            }

            $form_10_pu_prk_inti = $this->get_form_10_inti($request->input('draft_form_10_pu'),$int_input_lokasi, 'I');
            foreach ($form_10_pu_prk_inti as $key => $value) {
                $daftar_prk_inti_form_10_pu[$value->value] = array('desc_prk_inti' => '', 
                    'prk_parent'    => substr($value->value, 0, 6),
                    'beban_mat'     => 0,
                    'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'total_year_estimate'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                );
            }

            $form_10_pu_no_prk_kegiatan = $this->get_form_10($request->input('draft_form_10_pu'),$int_input_lokasi, 'I');
            $form_10_pu_desc_prk_kegiatan = $this->get_form_10($request->input('draft_form_10_pu'),$int_input_lokasi, 'T');
            $form_10_pu_desc_prk_inti = $this->get_form_10($request->input('draft_form_10_pu'),$int_input_lokasi, 'S');
            $form_10_pu_desc_prk_parent = $this->get_form_10($request->input('draft_form_10_pu'),$int_input_lokasi, 'R');
            $form_10_pu_beban_mat = $this->get_form_10($request->input('draft_form_10_pu'),$int_input_lokasi, 'AJ');
            $form_10_pu_total_year_estimate = $this->get_form_10($request->input('draft_form_10_pu'),$int_input_lokasi, 'AU');
            // $form_10_pu_cash_oth = $this->get_form_10($request->input('draft_form_10_pu'),$int_input_lokasi, 'AV');
            // $form_10_pu_ijin_proses = $this->get_form_10($request->input('draft_form_10_pu'),$int_input_lokasi, 'AX');
            $form_10_pu_disburse = array();
            $form_10_pu_disburse[1] = $this->get_form_10_disburse($request->input('draft_form_10_pu'),$int_input_lokasi, 'BI');
            $form_10_pu_disburse[2] = $this->get_form_10_disburse($request->input('draft_form_10_pu'),$int_input_lokasi, 'BJ');
            $form_10_pu_disburse[3] = $this->get_form_10_disburse($request->input('draft_form_10_pu'),$int_input_lokasi, 'BK');
            $form_10_pu_disburse[4] = $this->get_form_10_disburse($request->input('draft_form_10_pu'),$int_input_lokasi, 'BL');
            $form_10_pu_disburse[5] = $this->get_form_10_disburse($request->input('draft_form_10_pu'),$int_input_lokasi, 'BM');
            $form_10_pu_disburse[6] = $this->get_form_10_disburse($request->input('draft_form_10_pu'),$int_input_lokasi, 'BN');
            $form_10_pu_disburse[7] = $this->get_form_10_disburse($request->input('draft_form_10_pu'),$int_input_lokasi, 'BO');
            $form_10_pu_disburse[8] = $this->get_form_10_disburse($request->input('draft_form_10_pu'),$int_input_lokasi, 'BP');
            $form_10_pu_disburse[9] = $this->get_form_10_disburse($request->input('draft_form_10_pu'),$int_input_lokasi, 'BQ');
            $form_10_pu_disburse[10] = $this->get_form_10_disburse($request->input('draft_form_10_pu'),$int_input_lokasi, 'BR');
            $form_10_pu_disburse[11] = $this->get_form_10_disburse($request->input('draft_form_10_pu'),$int_input_lokasi, 'BS');
            $form_10_pu_disburse[12] = $this->get_form_10_disburse($request->input('draft_form_10_pu'),$int_input_lokasi, 'BT');
            
            for($i=0; $i<$int_count_10_pu; $i++){
                $parent = substr($form_10_pu_no_prk_kegiatan[$i]->value,0,6);
                $inti = substr($form_10_pu_no_prk_kegiatan[$i]->value,0,8);
                
                $disburse = array();
                for($bulan=1; $bulan<=12; $bulan++){
                    $disburse[$bulan] = (float)$form_10_pu_disburse[$bulan][$i]->value;
                    // $daftar_prk_inti_form_10_pu[$inti]['disburse'][$bulan] += $disburse[$bulan];
                    // $daftar_prk_parent_form_10_pu[$parent]['disburse'][$bulan] += $disburse[$bulan];
                }
                
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
                    'disburse' => $disburse,
                );
                array_push($daftar_prk_kegiatan_form_10_pu, $temp);
                
                $daftar_prk_inti_form_10_pu[$inti]['desc_prk_inti'] = $temp['desc_prk_inti'];
                $daftar_prk_inti_form_10_pu[$inti]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_inti_form_10_pu[$inti]['total_year_estimate'] += $temp['total_year_estimate'];
            
                $daftar_prk_parent_form_10_pu[$parent]['desc_prk_parent'] = $temp['desc_prk_parent'];
                $daftar_prk_parent_form_10_pu[$parent]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_parent_form_10_pu[$parent]['total_year_estimate'] += $temp['total_year_estimate'];
                $dataparent['form_10_pu'] = $daftar_prk_parent_form_10_pu;
                $datainti['form_10_pu'] = $daftar_prk_inti_form_10_pu;
                $datakegiatan['form_10_pu'] = $daftar_prk_kegiatan_form_10_pu;

            }
        } //end of cek request draft form 10 pengembangan usaha
        //End of query form 10 Pengembangan Usaha


        //Start Form 10 PLN
        if($request->input('draft_form_10_pln')) {
            $count_10_pln = DB::select("select count(e.row) from excel_datas e join sheets s on s.id = e.sheet_id where s.name like 'I-Form 10' and e.file_import_id = ".$request->input('draft_form_10_pln')." and e.lokasi_id = ".$int_input_lokasi." and e.kolom = 'AM';")[0]->count;
            $int_count_10_pln = (int)$count_10_pln;

            $daftar_prk_kegiatan_form_10_pln = array();
            $daftar_prk_inti_form_10_pln = array();
            $daftar_prk_parent_form_10_pln = array();
            
            $form_10_pln_prk_parent = $this->get_form_10_parent($request->input('draft_form_10_pln'),$int_input_lokasi, 'J');
            foreach ($form_10_pln_prk_parent as $key => $value) {
                $daftar_prk_parent_form_10_pln[$value->value] = array('desc_prk_parent' => '', 
                    'beban_mat'     => 0,
                    'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'total_year_estimate'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    );
            }

            $form_10_pln_prk_inti = $this->get_form_10_inti($request->input('draft_form_10_pln'),$int_input_lokasi, 'J');
            foreach ($form_10_pln_prk_inti as $key => $value) {
                $daftar_prk_inti_form_10_pln[$value->value] = array('desc_prk_inti' => '', 
                    'prk_parent'    => substr($value->value, 0, 6),
                    'beban_mat'     => 0,
                    'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'total_year_estimate'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                );
            }

            $form_10_pln_no_prk_kegiatan = $this->get_form_10($request->input('draft_form_10_pln'),$int_input_lokasi, 'J');
            $form_10_pln_desc_prk_kegiatan = $this->get_form_10($request->input('draft_form_10_pln'),$int_input_lokasi, 'U');
            $form_10_pln_desc_prk_inti = $this->get_form_10($request->input('draft_form_10_pln'),$int_input_lokasi, 'T');
            $form_10_pln_desc_prk_parent = $this->get_form_10($request->input('draft_form_10_pln'),$int_input_lokasi, 'S');
            $form_10_pln_beban_mat = $this->get_form_10($request->input('draft_form_10_pln'),$int_input_lokasi, 'AK');
            $form_10_pln_total_year_estimate = $this->get_form_10($request->input('draft_form_10_pln'),$int_input_lokasi, 'AR');
            
            $form_10_pln_disburse = array();
            $form_10_pln_disburse[1] = $this->get_form_10_disburse($request->input('draft_form_10_pln'),$int_input_lokasi, 'AX');
            $form_10_pln_disburse[2] = $this->get_form_10_disburse($request->input('draft_form_10_pln'),$int_input_lokasi, 'AY');
            $form_10_pln_disburse[3] = $this->get_form_10_disburse($request->input('draft_form_10_pln'),$int_input_lokasi, 'AZ');
            $form_10_pln_disburse[4] = $this->get_form_10_disburse($request->input('draft_form_10_pln'),$int_input_lokasi, 'BA');
            $form_10_pln_disburse[5] = $this->get_form_10_disburse($request->input('draft_form_10_pln'),$int_input_lokasi, 'BB');
            $form_10_pln_disburse[6] = $this->get_form_10_disburse($request->input('draft_form_10_pln'),$int_input_lokasi, 'BC');
            $form_10_pln_disburse[7] = $this->get_form_10_disburse($request->input('draft_form_10_pln'),$int_input_lokasi, 'BD');
            $form_10_pln_disburse[8] = $this->get_form_10_disburse($request->input('draft_form_10_pln'),$int_input_lokasi, 'BE');
            $form_10_pln_disburse[9] = $this->get_form_10_disburse($request->input('draft_form_10_pln'),$int_input_lokasi, 'BF');
            $form_10_pln_disburse[10] = $this->get_form_10_disburse($request->input('draft_form_10_pln'),$int_input_lokasi, 'BG');
            $form_10_pln_disburse[11] = $this->get_form_10_disburse($request->input('draft_form_10_pln'),$int_input_lokasi, 'BH');
            $form_10_pln_disburse[12] = $this->get_form_10_disburse($request->input('draft_form_10_pln'),$int_input_lokasi, 'BI');
            
            for($i=0; $i<$int_count_10_pln; $i++){
                $parent = substr($form_10_pln_no_prk_kegiatan[$i]->value,0,6);
                $inti = substr($form_10_pln_no_prk_kegiatan[$i]->value,0,8);
                
                $disburse = array();
                for($bulan=1; $bulan<=12; $bulan++){
                    $disburse[$bulan] = (float)$form_10_pln_disburse[$bulan][$i]->value;
                    // $daftar_prk_inti_form_10_pln[$inti]['disburse'][$bulan] += $disburse[$bulan];
                    // $daftar_prk_parent_form_10_pln[$parent]['disburse'][$bulan] += $disburse[$bulan];
                }
                
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
                    'total_year_estimate' => (float)$form_10_pln_total_year_estimate[$i]->value,
                    'disburse' => $disburse,
                );
                array_push($daftar_prk_kegiatan_form_10_pln, $temp);
                
                $daftar_prk_inti_form_10_pln[$inti]['desc_prk_inti'] = $temp['desc_prk_inti'];
                $daftar_prk_inti_form_10_pln[$inti]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_inti_form_10_pln[$inti]['total_year_estimate'] += $temp['total_year_estimate'];

                $daftar_prk_parent_form_10_pln[$parent]['desc_prk_parent'] = $temp['desc_prk_parent'];
                $daftar_prk_parent_form_10_pln[$parent]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_parent_form_10_pln[$parent]['total_year_estimate'] += $temp['total_year_estimate'];
            }

            $dataparent['form_10_pln'] = $daftar_prk_parent_form_10_pln;
            $datainti['form_10_pln'] = $daftar_prk_inti_form_10_pln;
            $datakegiatan['form_10_pln'] = $daftar_prk_kegiatan_form_10_pln;

        } //end of cek request draft form 10 pln
        //End of query form 10 PLN

        //Start form Penyusutan
        if($request->input('draft_form_penyusutan')) {
            $count_penyusutan = DB::select("select count(e.row) from excel_datas e join sheets s on s.id = e.sheet_id where s.name like 'I-Penyusutan' and e.file_import_id = ".$request->input('draft_form_penyusutan')." and e.lokasi_id = ".$int_input_lokasi." and e.kolom = 'H';")[0]->count;
            $int_count_penyusutan = (int)$count_penyusutan;

            $daftar_prk_kegiatan_form_penyusutan = array();
            $daftar_prk_inti_form_penyusutan = array();
            $daftar_prk_parent_form_penyusutan = array();
            
            $form_penyusutan_prk_parent = $this->get_form_penyusutan_parent($request->input('draft_form_penyusutan'),$int_input_lokasi, 'H');
            foreach ($form_penyusutan_prk_parent as $key => $value) {
                // $desc_prk_parent = $this->get_prk_parent_desc(substr($value->value, 4,2));
                $daftar_prk_parent_form_penyusutan[$value->value] = array(
                    // 'desc_prk_parent' => ($desc_prk_parent!= null ? $desc_prk_parent->desc_prk_parent : ''),
                    'desc_prk_parent' => 'Penyusutan',
                    'beban_mat'     => 0,
                    'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'total_year_estimate'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    );
            }

            $form_penyusutan_prk_inti = $this->get_form_penyusutan_inti($request->input('draft_form_penyusutan'),$int_input_lokasi, 'H');
            foreach ($form_penyusutan_prk_inti as $key => $value) {
                // $desc_prk_inti = $this->get_prk_inti_desc(substr($value->value, 4,2),substr($value->value, 6,2));
                $daftar_prk_inti_form_penyusutan[$value->value] = array(
                    // 'desc_prk_inti' => ($desc_prk_inti!= null ? $desc_prk_inti->desc_prk_inti : ''),
                    'desc_prk_inti' => 'Penyusutan',
                    'prk_parent'    => substr($value->value, 0, 6),
                    'beban_mat'     => 0,
                    'cash_oth'      => 0,
                    'ijin_proses'   => 0,
                    'total_year_estimate'   => 0,
                    'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                );
            }

            $form_penyusutan_no_prk_kegiatan = $this->get_form_penyusutan($request->input('draft_form_penyusutan'),$int_input_lokasi, 'H');
            // $form_penyusutan_desc_prk_kegiatan = $this->get_form_penyusutan($request->input('draft_form_penyusutan'),$int_input_lokasi, 'H');
            //$form_penyusutan_desc_prk_inti = $this->get_form_penyusutan($request->input('draft_form_penyusutan'),$int_input_lokasi, 'T');
            //$form_penyusutan_desc_prk_parent = $this->get_form_penyusutan($request->input('draft_form_penyusutan'),$int_input_lokasi, 'S');
            $form_penyusutan_beban_mat = $this->get_form_penyusutan($request->input('draft_form_penyusutan'),$int_input_lokasi, 'O');
            $form_penyusutan_total_year_estimate = $this->get_form_penyusutan($request->input('draft_form_penyusutan'),$int_input_lokasi, 'O');
            
            $form_penyusutan_disburse = array();
            $form_penyusutan_disburse[1] = $this->get_form_penyusutan_disburse($request->input('draft_form_penyusutan'),$int_input_lokasi, 'Q');
            $form_penyusutan_disburse[2] = $this->get_form_penyusutan_disburse($request->input('draft_form_penyusutan'),$int_input_lokasi, 'R');
            $form_penyusutan_disburse[3] = $this->get_form_penyusutan_disburse($request->input('draft_form_penyusutan'),$int_input_lokasi, 'S');
            $form_penyusutan_disburse[4] = $this->get_form_penyusutan_disburse($request->input('draft_form_penyusutan'),$int_input_lokasi, 'T');
            $form_penyusutan_disburse[5] = $this->get_form_penyusutan_disburse($request->input('draft_form_penyusutan'),$int_input_lokasi, 'U');
            $form_penyusutan_disburse[6] = $this->get_form_penyusutan_disburse($request->input('draft_form_penyusutan'),$int_input_lokasi, 'V');
            $form_penyusutan_disburse[7] = $this->get_form_penyusutan_disburse($request->input('draft_form_penyusutan'),$int_input_lokasi, 'W');
            $form_penyusutan_disburse[8] = $this->get_form_penyusutan_disburse($request->input('draft_form_penyusutan'),$int_input_lokasi, 'X');
            $form_penyusutan_disburse[9] = $this->get_form_penyusutan_disburse($request->input('draft_form_penyusutan'),$int_input_lokasi, 'Y');
            $form_penyusutan_disburse[10] = $this->get_form_penyusutan_disburse($request->input('draft_form_penyusutan'),$int_input_lokasi, 'Z');
            $form_penyusutan_disburse[11] = $this->get_form_penyusutan_disburse($request->input('draft_form_penyusutan'),$int_input_lokasi, 'AA');
            $form_penyusutan_disburse[12] = $this->get_form_penyusutan_disburse($request->input('draft_form_penyusutan'),$int_input_lokasi, 'AB');
            
            for($i=0; $i<$int_count_penyusutan; $i++){
                $parent = substr($form_penyusutan_no_prk_kegiatan[$i]->value,0,6);
                $inti = substr($form_penyusutan_no_prk_kegiatan[$i]->value,0,8);
                
                $disburse = array();
                for($bulan=1; $bulan<=12; $bulan++){
                    $disburse[$bulan] = (float)$form_penyusutan_disburse[$bulan][$i]->value;
                    // $daftar_prk_inti_form_penyusutan[$inti]['disburse'][$bulan] += $disburse[$bulan];
                    // $daftar_prk_parent_form_penyusutan[$parent]['disburse'][$bulan] += $disburse[$bulan];
                }
                
                $temp = array(
                    'prk_kegiatan' => $form_penyusutan_no_prk_kegiatan[$i]->value,
                    'prk_inti' => $inti,
                    'prk_parent' => $parent,
                    // 'desc_prk_kegiatan' => $form_penyusutan_desc_prk_kegiatan[$i]->value,
                    'desc_prk_kegiatan' => 'Penyusutan',
                    //'desc_prk_inti' => $form_penyusutan_desc_prk_inti[$i]->value,
                    //'desc_prk_parent' => $form_penyusutan_desc_prk_parent[$i]->value,
                    'beban_mat' => ((float)$form_penyusutan_beban_mat[$i]->value) / 1.1,
                    'cash_oth' => 0,
                    'ijin_proses' => 0,
                    'total_year_estimate' => (float)$form_penyusutan_total_year_estimate[$i]->value,
                    'disburse' => $disburse,
                );
                array_push($daftar_prk_kegiatan_form_penyusutan, $temp);
                
                $daftar_prk_inti_form_penyusutan[$inti]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_inti_form_penyusutan[$inti]['total_year_estimate'] += $temp['total_year_estimate'];

                $daftar_prk_parent_form_penyusutan[$parent]['beban_mat'] += $temp['beban_mat'];
                $daftar_prk_parent_form_penyusutan[$parent]['total_year_estimate'] += $temp['total_year_estimate'];
            }

            $dataparent['form_penyusutan'] = $daftar_prk_parent_form_penyusutan;
            $datainti['form_penyusutan'] = $daftar_prk_inti_form_penyusutan;
            $datakegiatan['form_penyusutan'] = $daftar_prk_kegiatan_form_penyusutan;

        } //end of cek request draft Penyusutan
        //End of query form Penyusutan

        //Start form Bahan Bakar
        if($request->input('draft_form_bahan_bakar')) {
            $jenis_bahan_bakar = array();
            array_push($jenis_bahan_bakar, array('name' => 'HSD','description' => 'HSD','beban_mat1' => 'AO', 'beban_mat2' => 'AQ','beban_mat3' => null,'cash_oth1' => 'AW', 'cash_oth2' => 'AX', 'cash_oth3' => null));
            array_push($jenis_bahan_bakar, array('name' => 'MFO','description' => 'MFO','beban_mat1' => 'AO', 'beban_mat2' => 'AQ','beban_mat3' => null, 'cash_oth1' => 'AW', 'cash_oth2' => 'AX', 'cash_oth3' => null));
            array_push($jenis_bahan_bakar, array('name' => 'IDO','description' => 'IDO','beban_mat1' => 'AO', 'beban_mat2' => 'AQ','beban_mat3' => null, 'cash_oth1' => 'AW', 'cash_oth2' => 'AX', 'cash_oth3' => null));
            array_push($jenis_bahan_bakar, array('name' => 'GAS ALAM','description' => 'Biaya bahan bakar - Gas alam','beban_mat1' => 'AO', 'beban_mat2' => 'AQ','beban_mat3' => null,'cash_oth1' => 'AW', 'cash_oth2' => 'AX', 'cash_oth3' => null));
            array_push($jenis_bahan_bakar, array('name' => 'BATUBARA','description' => 'Batubara','beban_mat1' => 'AO', 'beban_mat2' => 'AQ','beban_mat3' => null,'cash_oth1' => 'AW', 'cash_oth2' => 'AX', 'cash_oth3' => null));
            array_push($jenis_bahan_bakar, array('name' => 'MINYAK PELUMAS ','description' => 'Biaya bahan bakar - Minyak pelumas','beban_mat1' => 'AS', 'beban_mat2' => null,'beban_mat3' => null,'cash_oth1' => 'AY', 'cash_oth2' => null, 'cash_oth3' => null));
            array_push($jenis_bahan_bakar, array('name' => 'KIMIA','description' => 'Biaya bahan bakar - Kimia','beban_mat1' => 'AT', 'beban_mat2' => null,'beban_mat3' => null,'cash_oth1' => 'AZ', 'cash_oth2' => null, 'cash_oth3' => null));
            array_push($jenis_bahan_bakar, array('name' => 'RETRIBUSI','description' => 'Retribusi','beban_mat1' => 'AN', 'beban_mat2' => null,'beban_mat3' => null,'cash_oth1' => 'AV', 'cash_oth2' => null, 'cash_oth3' => null));
            array_push($jenis_bahan_bakar, array('name' => 'EP','description' => 'EP','beban_mat1' => 'AO', 'beban_mat2' => 'AQ', 'beban_mat3' => 'AN', 'cash_oth1' => 'AW', 'cash_oth2' => 'AX', 'cash_oth3' => 'AV'));

            // $daftar_prk_kegiatan_form_bahan_bakar = array();
            // $daftar_prk_inti_form_bahan_bakar = array();
            // $daftar_prk_parent_form_bahan_bakar = array();
            foreach($jenis_bahan_bakar as $jenis){
                $daftar_prk_kegiatan_form_bahan_bakar = array();
                $daftar_prk_inti_form_bahan_bakar = array();
                $daftar_prk_parent_form_bahan_bakar = array();

                $form_bahan_bakar_prk_parent = $this->get_form_bahan_bakar_prk($request->input('draft_form_bahan_bakar'),$int_input_lokasi, 'J', $jenis['name'],"parent");
                foreach ($form_bahan_bakar_prk_parent as $key => $value) {
                    $daftar_prk_parent_form_bahan_bakar[$value->value] = array(
                        'desc_prk_parent' => $jenis['description'],
                        // 'desc_prk_parent' => 'Bahan Bakar',
                        'beban_mat'     => 0,
                        'cash_oth'      => 0,
                        'ijin_proses'   => 0,
                        'total_year_estimate'   => 0,
                        'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                        );
                }
                // dd($daftar_prk_parent_form_bahan_bakar);
            
                $form_bahan_bakar_prk_inti = $this->get_form_bahan_bakar_prk($request->input('draft_form_bahan_bakar'),$int_input_lokasi, 'J', $jenis['name'],"inti");
                foreach ($form_bahan_bakar_prk_inti as $key => $value) {
                    $daftar_prk_inti_form_bahan_bakar[$value->value] = array(
                        'desc_prk_inti' => $jenis['description'],
                        'prk_parent'    => substr($value->value, 0, 6),
                        'beban_mat'     => 0,
                        'cash_oth'      => 0,
                        'ijin_proses'   => 0,
                        'total_year_estimate'   => 0,
                        'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    );
                }

                $form_bahan_bakar_prk_kegiatan = $this->get_form_bahan_bakar_prk($request->input('draft_form_bahan_bakar'),$int_input_lokasi, 'J', $jenis['name'],"kegiatan");
                foreach ($form_bahan_bakar_prk_kegiatan as $key => $value) {
                    $temp = array(
                        'prk_kegiatan'  => $value->value,
                        'desc_prk_kegiatan' => $jenis['description'],
                        'prk_inti'    => substr($value->value, 0, 8),
                        'beban_mat'     => 0,
                        'cash_oth'      => 0,
                        'ijin_proses'   => 0,
                        'total_year_estimate'   => 0,
                        'disburse'      => array(0,0,0,0,0,0,0,0,0,0,0,0,0),
                    );

                    $beban_mat1 = $this->get_form_bahan_bakar_per_prk($request->input('draft_form_bahan_bakar'),$int_input_lokasi, $jenis['beban_mat1'], $jenis['name'],'J',$value->value);
                    $temp['beban_mat'] += (float) array_sum($beban_mat1);

                    if($jenis['beban_mat2']!= null) {
                        $beban_mat2 = $this->get_form_bahan_bakar_per_prk($request->input('draft_form_bahan_bakar'),$int_input_lokasi, $jenis['beban_mat2'], $jenis['name'],'J',$value->value);
                        $temp['beban_mat'] += (float) array_sum($beban_mat2);
                    }

                    if($jenis['beban_mat3']!= null) {
                        $beban_mat3 = $this->get_form_bahan_bakar_per_prk($request->input('draft_form_bahan_bakar'),$int_input_lokasi, $jenis['beban_mat3'], $jenis['name'],'J',$value->value);
                        $temp['beban_mat'] -= (float) array_sum($beban_mat1);
                    }
                    
                    $cash_oth1 = $this->get_form_bahan_bakar_per_prk($request->input('draft_form_bahan_bakar'),$int_input_lokasi, $jenis['cash_oth1'], $jenis['name'],'J',$value->value);
                    $temp['cash_oth'] += (float) array_sum($cash_oth1);

                    if($jenis['cash_oth2']!= null) {
                        $cash_oth2 = $this->get_form_bahan_bakar_per_prk($request->input('draft_form_bahan_bakar'),$int_input_lokasi, $jenis['cash_oth2'], $jenis['name'],'J',$value->value);
                        $temp['cash_oth'] += (float) array_sum($cash_oth2);
                    }
                    
                    if($jenis['cash_oth3']!= null) {
                        $cash_oth3 = $this->get_form_bahan_bakar_per_prk($request->input('draft_form_bahan_bakar'),$int_input_lokasi, $jenis['cash_oth3'], $jenis['name'],'J',$value->value);
                        $temp['cash_oth'] -= (float) array_sum($cash_oth1);
                    }
                    
                    $temp['total_year_estimate'] += $temp['beban_mat'];

                    $daftar_bulan = $this->get_form_bahan_bakar_per_prk($request->input('draft_form_bahan_bakar'),$int_input_lokasi, 'H', $jenis['name'],'J',$value->value);
                    for($i_bulan = 1; $i_bulan<=12; $i_bulan++){
                        foreach ($daftar_bulan as $key_bulan => $bulan) {
                            if($i_bulan == $bulan){
                                $temp['disburse'][$i_bulan] += (float) $beban_mat1[$key_bulan];       
                                if($jenis['beban_mat2']!= null)
                                    $temp['disburse'][$i_bulan] += (float) $beban_mat2[$key_bulan];       
                                if($jenis['beban_mat3']!= null)
                                    $temp['disburse'][$i_bulan] -= (float) $beban_mat3[$key_bulan];       
                            }
                        }
                    }                    
                    array_push($daftar_prk_kegiatan_form_bahan_bakar, $temp);
                }

                $dataparent[$jenis['name']] = $daftar_prk_parent_form_bahan_bakar;
                $datainti[$jenis['name']] = $daftar_prk_inti_form_bahan_bakar;
                $datakegiatan[$jenis['name']] = $daftar_prk_kegiatan_form_bahan_bakar;
            }

        } //end of cek request Bahan Bakar
        //End of query form Bahan Bakar

            if($request->download && $request->type){
                $judul='';
                if($request->type=='excel'){
                    Excel::create('List PRK', function ($excel) use($sb, $fase, $input_tahun, $input_sb, $input_distrik, $input_lokasi, $input_fase, $input_draft_rkau, $input_draft_form_6_reimburse, $input_draft_form_6_rutin, $input_draft_form_10_pk, $input_draft_form_10_pu, $input_draft_form_10_pln, $dataparent, $datainti, $datakegiatan) {
                            $excel->setTitle('List PRK');
                            $excel->setCreator('Laravel')->setCompany('Profio', 'PJB');
                            $excel->setDescription('List PRK');
                            $excel->sheet('List PRK', function ($sheet) use($sb, $fase, $input_tahun, $input_sb, $input_distrik, $input_lokasi, $input_fase, $input_draft_rkau, $input_draft_form_6_reimburse, $input_draft_form_6_rutin, $input_draft_form_10_pk, $input_draft_form_10_pu, $input_draft_form_10_pln, $dataparent, $datainti, $datakegiatan){
                                $sheet->loadView('output/list-prk-excel')
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
                                        ->with('dataparent', $dataparent)
                                        ->with('datainti', $datainti)
                                        ->with('datakegiatan', $datakegiatan);
                            });
                        })->download('xlsx');
                }
            }
            // else {
            //     return view('output/list-prk', compact('sb', 'fase', 'input_tahun', 'input_sb', 'input_distrik', 'input_lokasi', 'input_fase', 'input_draft_rkau', 'input_draft_form_6_reimburse', 'input_draft_form_6_rutin', 'input_draft_form_10_pk', 'input_draft_form_10_pu', 'input_draft_form_10_pln', 'dataparent', 'datainti','datakegiatan'));              
            // }          

        }

        return view('output/list-prk', compact('sb', 'fase', 'input_tahun', 'input_sb', 'input_distrik', 'input_lokasi', 'input_fase', 'input_draft_rkau', 'input_draft_form_6_reimburse', 'input_draft_form_6_rutin', 'input_draft_form_10_pk', 'input_draft_form_10_pu', 'input_draft_form_10_pln', 'input_draft_form_penyusutan', 'input_draft_form_bahan_bakar','dataparent', 'datainti','datakegiatan', 'distrik', 'lokasi','tahun', 'draft_form_rkau', 'draft_form_penyusutan', 'draft_form_10_pln', 'draft_form_10_pu', 'draft_form_10_pk', 'draft_form_6_reimburse', 'draft_form_6_rutin', 'draft_form_bahan_bakar'));
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
                                    from file_imports f 
                                    join templates t on f.template_id = t.id
                                    join excel_datas e on e.file_import_id = f.id
                                    where t.jenis_id=".$id_jenis." and e.lokasi_id = ".$id_lokasi." and t.tahun= ".$id_tahun."
                                    group by f.id, f.draft_versi;");
        return $drafts;
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

    function get_form_10($file_import_id, $lokasi_id, $kolom){
        $query = DB::select("select e.row, e.value from excel_datas e join sheets s on s.id = e.sheet_id where s.name like 'I-Form 10' and e.file_import_id = ".$file_import_id." and e.lokasi_id = ".$lokasi_id." and e.kolom = '".$kolom."' order by e.row;");
        return $query;
    }
    function get_form_10_inti($file_import_id, $lokasi_id, $kolom){
        $query = DB::select("select distinct SUBSTRING(e.value,1,8) as value from excel_datas e join sheets s on s.id = e.sheet_id where s.name like 'I-Form 10' and e.file_import_id = ".$file_import_id." and e.lokasi_id = ".$lokasi_id." and e.kolom = '".$kolom."' order by value asc;");
        
        return $query;
    }

    function get_form_10_parent($file_import_id, $lokasi_id, $kolom){
        $query = DB::select("select distinct SUBSTRING(e.value,1,6) as value from excel_datas e join sheets s on s.id = e.sheet_id where s.name like 'I-Form 10' and e.file_import_id = ".$file_import_id." and e.lokasi_id = ".$lokasi_id." and e.kolom = '".$kolom."' order by value asc;");
        return $query;
    }
    function get_form_10_disburse($file_import_id, $lokasi_id, $kolom){
        $query = DB::select("select (case when value = '' then 0 else value::float end) as value from excel_datas e join sheets s on s.id = e.sheet_id where s.name like 'I-Form 10' and e.file_import_id = ".$file_import_id." and e.lokasi_id = ".$lokasi_id." and e.kolom = '".$kolom."' ");
        return $query;
    }

    function get_form_rkau($file_import_id, $lokasi_id, $sheet, $kolom){
        $query = DB::select("select e.row, e.value 
                            from excel_datas e 
                            join sheets s on s.id = e.sheet_id  
                            where s.name like '".$sheet."' 
                            and e.file_import_id = ".$file_import_id." 
                            and e.lokasi_id = ".$lokasi_id." 
                            and e.kolom = '".$kolom."'
                            and e.row > 12
                            order by e.row");
        $result = array();
        foreach ($query as $value) {
            $result[$value->row] = $value->value;
        }
        return $result;
    }
    function get_form_rkau_inti($file_import_id, $lokasi_id, $sheet, $kolom){
        $query = DB::select("select distinct SUBSTRING(e.value,1,6) as value
                            from excel_datas e 
                            join sheets s on s.id = e.sheet_id  
                            where s.name like '".$sheet."' 
                            and e.file_import_id = ".$file_import_id." 
                            and e.lokasi_id = ".$lokasi_id." 
                            and e.kolom = '".$kolom."'
                            and e.row > 12 order by value asc;");
        return $query;
    }

    function get_form_rkau_parent($file_import_id, $lokasi_id, $sheet, $kolom){
        $query = DB::select("select distinct SUBSTRING(e.value,1,4) as value 
                            from excel_datas e 
                            join sheets s on s.id = e.sheet_id  
                            where s.name like '".$sheet."' 
                            and e.file_import_id = ".$file_import_id." 
                            and e.lokasi_id = ".$lokasi_id." 
                            and e.kolom = '".$kolom."'
                            and e.row > 12 order by value asc;");
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

    function get_form_penyusutan($file_import_id, $lokasi_id, $kolom){
        $query = DB::select("select e.row, e.value from excel_datas e join sheets s on s.id = e.sheet_id where s.name like 'I-Penyusutan' and e.file_import_id = ".$file_import_id." and e.lokasi_id = ".$lokasi_id." and e.kolom = '".$kolom."' order by e.row;");
        return $query;
    }
    function get_form_penyusutan_inti($file_import_id, $lokasi_id, $kolom){
        $query = DB::select("select distinct SUBSTRING(e.value,1,8) as value from excel_datas e join sheets s on s.id = e.sheet_id where s.name like 'I-Penyusutan' and e.file_import_id = ".$file_import_id." and e.lokasi_id = ".$lokasi_id." and e.kolom = '".$kolom."' order by value asc;");
        
        return $query;
    }

    function get_form_penyusutan_parent($file_import_id, $lokasi_id, $kolom){
        $query = DB::select("select distinct SUBSTRING(e.value,1,6) as value from excel_datas e join sheets s on s.id = e.sheet_id where s.name like 'I-Penyusutan' and e.file_import_id = ".$file_import_id." and e.lokasi_id = ".$lokasi_id." and e.kolom = '".$kolom."' order by value asc;");
        return $query;
    }
    function get_form_penyusutan_disburse($file_import_id, $lokasi_id, $kolom){
        $query = DB::select("select (case when value = '' then 0 else value::float end) as value from excel_datas e join sheets s on s.id = e.sheet_id where s.name like 'I-Penyusutan' and e.file_import_id = ".$file_import_id." and e.lokasi_id = ".$lokasi_id." and e.kolom = '".$kolom."' ");
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
    function get_form_bahan_bakar_prk($file_import_id, $lokasi_id, $kolom, $jenis_bahan_bakar, $level){
        if($level == 'kegiatan') $substr = 10;
        else if($level == 'inti') $substr = 8;
        else if($level == 'parent') $substr = 6;
        $query = DB::select("select distinct SUBSTRING(e.value,1,".$substr.") as value from excel_datas e 
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
}
