<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\StrategiBisnis;
use App\Entities\Distrik;
use App\Entities\Lokasi;
use App\Entities\Fase;
use App\Entities\Template;
use App\Entities\DmrReviewPhase;
use App\Entities\DmrReviewStatus;
use App\Entities\User;
use App\Entities\Role;
use Illuminate\Support\Facades\DB;
Use Excel;
use PDF;

class StatusDmrController extends Controller
{
    public function Status_Dmr(Request $request)
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
      
        $fase = Fase::all();
        $tahun = Template::select('tahun')->where('jenis_id', 2)->orWhere('jenis_id',3)->orWhere('jenis_id', 4)->orWhere('jenis_id',4)->distinct()->get();

        $input_tahun = $request->input('tahun_anggaran');
        // dd($input_tahun);
        $input_sb = $request->input('strategi_bisnis');
        $input_distrik = $request->input('distrik');
        $input_lokasi = $request->input('lokasi');
        $input_fase = $request->input('fase');
        $input_form_6_rutin = $request->input('form_6_rutin');
        $input_form_6_reimburse = $request->input('form_6_reimburse');
        $input_form_10_pu = $request->input('form_10_pu');
        $input_form_10_pk = $request->input('form_10_pk');
        $input_form_10_pln = $request->input('form_10_pln');

        if ($request->input('strategi_bisnis') != NULL) {
            $input_sb = DB::table('strategi_bisnis')->select('name','id')->where('id', $request->input('strategi_bisnis'))->get()[0];
            $distrik = Distrik::select('name','id')->where('strategi_bisnis_id',$input_sb->id)->get();
        }
        if ($request->input('distrik') != NULL) {
            $input_distrik = DB::table('distrik')->select('name','code1','id')->where('id', $request->input('distrik'))->get()[0];
            $lokasi = Lokasi::select('name','id')->where('distrik_id',$input_distrik->id)->get();
        }
        if ($request->input('lokasi') != NULL) {
            $input_lokasi = DB::table('lokasi')->select('name','id')->where('id', $request->input('lokasi'))->get()[0];
        }
        if ($request->input('fase') != NULL) {
            $input_fase = DB::table('fases')->select('name','id')->where('id', $request->input('fase'))->get()[0];
        }
        if ($request->input('form_6_reimburse') != NULL) {
            $input_form_6_reimburse = DB::table('file_imports')->select('draft_versi','id','name')->where('id', $request->input('form_6_reimburse'))->get()[0];
            $drafts_form_6_reimburse = $this->query_draft(2, $input_lokasi->id, $input_tahun);
        }
        if ($request->input('form_6_rutin') != NULL) {
            $input_form_6_rutin = DB::table('file_imports')->select('draft_versi','id','name')->where('id', $request->input('form_6_rutin'))->get()[0];
            $drafts_form_6_rutin = $this->query_draft(3, $input_lokasi->id, $input_tahun);
        }
        if ($request->input('form_10_pu') != NULL) {
            $input_form_10_pu = DB::table('file_imports')->select('draft_versi','id','name')->where('id', $request->input('form_10_pu'))->get()[0];
            $drafts_form_10_pu = $this->query_draft(4, $input_lokasi->id, $input_tahun);
        }
        if ($request->input('form_10_pk') != NULL) {
            $input_form_10_pk = DB::table('file_imports')->select('draft_versi','id','name')->where('id', $request->input('form_10_pk'))->get()[0];
            $drafts_form_10_pk = $this->query_draft(5, $input_lokasi->id, $input_tahun);
        }
        if ($request->input('form_10_pln') != NULL) {
            $input_form_10_pln = DB::table('file_imports')->select('draft_versi','id','name')->where('id', $request->input('form_10_pln'))->get()[0];
            $drafts_form_10_pln = $this->query_draft(6, $input_lokasi->id, $input_tahun);
        }

        if($input_tahun != null && $input_lokasi!= null){
            $data_query = array();
            if($request->input('form_6_reimburse')!= NULL)
                $data_query['form_6_reimburse'] = array('id' => $input_form_6_reimburse->id,
                    'kolom_dokumen_id' => 'AA',
                    'kolom_dmr' => 'AB',
                    'kolom_prk_parent' => 'R',
                    'kolom_deskripsi_prk' => 'T',
                    'kolom_prk' => 'I',
                    'sheet_name' => 'I-Form 6');

            if($request->input('form_6_rutin')!= NULL)
                $data_query['form_6_rutin'] = array('id' => $input_form_6_rutin->id,
                    'kolom_dokumen_id' => 'AA',
                    'kolom_dmr' => 'AB',
                    'kolom_prk_parent' => 'R',
                    'kolom_deskripsi_prk' => 'T',
                    'kolom_prk' => 'I',
                    'sheet_name' => 'I-Form 6');

            if($request->input('form_10_pu')!= NULL)
                $data_query['form_10_pu'] = array('id' => $input_form_10_pu->id,
                    'kolom_dokumen_id' => 'AA',
                    'kolom_dmr' => 'AB',
                    'kolom_prk_parent' => 'R',
                    'kolom_deskripsi_prk' => 'T',
                    'kolom_prk' => 'I',
                    'sheet_name' => 'I-Form 10');

            if($request->input('form_10_pk')!= NULL)
                $data_query['form_10_pk'] = array('id' => $input_form_10_pk->id,
                    'kolom_dokumen_id' => 'Z',
                    'kolom_dmr' => 'AA',
                    'kolom_prk_parent' => 'Q',
                    'kolom_deskripsi_prk' => 'S',
                    'kolom_prk' => 'H',
                    'sheet_name' => 'I-Form 10');

            if($request->input('form_10_pln')!= NULL)
                $data_query['form_10_pln'] = array('id' => $input_form_10_pln->id,
                    'kolom_dokumen_id' => 'AB',
                    'kolom_dmr' => 'AC',
                    'kolom_prk_parent' => 'S',
                    'kolom_deskripsi_prk' => 'U',
                    'kolom_prk' => 'J',
                    'sheet_name' => 'I-Form 10');
            // DMR
                $dmr = $this->get_dmr($input_tahun, $input_lokasi->id);
                // dd($dmr);
                $data_dmr = array();
                $summary_dmr = array();
                $summary_dmr['OH'] = array('total' => 0, 'queue' => 0, 'rejected' => 0, 'revised' => 0, 'approved' => 0);
                $summary_dmr['EJ'] = array('total' => 0, 'queue' => 0, 'rejected' => 0, 'revised' => 0, 'approved' => 0);
                
                $summary_dmr['Investasi'] = array('total' => 0, 'queue' => 0, 'rejected' => 0, 'revised' => 0, 'approved' => 0);
                $summary_dmr['Lainnya'] = array('total' => 0, 'queue' => 0, 'rejected' => 0, 'revised' => 0, 'approved' => 0);

                $total = array('approved' => 0, 'rejected' => 0, 'revised' => 0, 'total' => 0, 'queue' => 0);

                foreach ($data_query as $key_form => $q) {
                    // $hasil_query[$key] = array();
                    $documents_id = $this->query_form($q['id'], $input_lokasi->id, $q['kolom_dokumen_id'], $q['kolom_dmr'], $q['sheet_name']);
                    $prks = $this->query_form($q['id'], $input_lokasi->id, $q['kolom_prk'], $q['kolom_dmr'], $q['sheet_name']);
                    $nama_prks = $this->query_form($q['id'], $input_lokasi->id, $q['kolom_deskripsi_prk'], $q['kolom_dmr'], $q['sheet_name']);
                    $prk_parents = $this->query_form($q['id'], $input_lokasi->id, $q['kolom_prk_parent'], $q['kolom_dmr'], $q['sheet_name']);

                    foreach ($documents_id as $key => $dokumen_id) {
                        $d = array('dokumen_id' => $dokumen_id->value,
                                'prk' => '',
                                'nama_prk' => '',
                                'prk_parent' => '',
                                'parent' => '',
                                'document' => '-',
                                'no_dokumen' => '',
                                'anggaran'=>0,
                                'dmr_status' => 'Draft',
                                'review_status'=> '-',
                                'dmr_review_status_id' => 0,
                                'submitted_at' => '-',
                                'revised_at' => '-',
                                'rejected_at' => '-',
                                'approved_at' => '-',
                                'approved_by' => '-',);

                        foreach ($prks as $key2 => $prk) {
                            if($dokumen_id->row == $prk->row)
                                $d['prk'] = $prk->value;
                        }
                        foreach ($prk_parents as $key2 => $prk_parent) {
                            if($dokumen_id->row == $prk_parent->row)
                                $d['prk_parent'] = $prk_parent->value;
                        }
                        foreach ($nama_prks as $key2 => $nama_prk) {
                            if($dokumen_id->row == $nama_prk->row)
                                $d['nama_prk'] = $nama_prk->value;
                        }

                        foreach ($dmr as $key2 => $v_dmr) {
                            // if($v_dmr->no_dokumen == $dokumen_id->value && ($v_dmr->dmr_review_phase_id == 3 || $v_dmr->dmr_review_phase_id == 4)){
                            if($v_dmr->no_dokumen == $dokumen_id->value){
                                $d['dmr_status'] = 'Submitted';
                                if($v_dmr->dmr_review_phase_id == 4){
                                    $d['review_status'] = 'Approved';
                                    $d['dmr_review_status_id'] = 1;
                                }
                                else{
                                    $d['review_status'] = $v_dmr->review_status;
                                    $d['dmr_review_status_id'] = $v_dmr->dmr_review_status_id;
                                }
                                // $d['review_status'] = ($d['dmr_status'] == 'Submitted' && $v_dmr->dmr_review_status_id == null ? 'Queue' : $v_dmr->review_status);
                                if ($v_dmr->anggaran_prk_form == null) {
                                    # code...
                                    $d['anggaran'] = $v_dmr->jumlah_anggaran;
                                } else {
                                    $d['anggaran'] = $v_dmr->anggaran_prk_form; 
                                }
                                $d['document'] = $v_dmr->dmr_filepath;
                                $d['submitted_at'] = $v_dmr->submitted_at;
                                $d['revised_at'] = $v_dmr->revised_at;
                                $d['rejected_at'] = $v_dmr->rejected_at;
                                $d['approved_at'] = $v_dmr->approved_at;
                                $d['no_dokumen'] = $v_dmr->no_dokumen;
                                $d['approved_by'] = $v_dmr->name;
                            }
                        }
                        if($data_query[$key_form]['sheet_name'] == 'I-Form 10'){
                            $d['parent'] = 'Investasi';
                            $summary_dmr['Investasi']['total'] += 1;
                            $total['total'] += 1;

                            if($d['dmr_review_status_id'] == 1) {
                                $summary_dmr['Investasi']['approved'] += 1;
                                $total['approved'] += 1;
                            }
                            else if($d['dmr_review_status_id'] == 2) {
                                $summary_dmr['Investasi']['revised'] += 1;
                                $total['revised'] +=1;
                            }
                            else if($d['dmr_review_status_id'] == 3) {
                                $summary_dmr['Investasi']['rejected'] += 1;
                                $total['rejected'] += 1;
                            }
                            else if($d['submitted_at']!= '-'){
                                $summary_dmr['Investasi']['queue'] +=1;
                                $total['queue'] +=1;
                            }
                        }
                        else if($d['prk_parent'] == 'Har_Project'){
                            $d['parent'] = 'EJ';
                            $summary_dmr['EJ']['total'] += 1;
                            $total['total'] += 1;

                            if($d['dmr_review_status_id'] == 1) {
                                $summary_dmr['EJ']['approved'] += 1;
                                $total['approved'] += 1;
                            }
                            else if($d['dmr_review_status_id'] == 2) {
                                $summary_dmr['EJ']['revised'] += 1;
                                $total['revised'] +=1;
                            }
                            else if($d['dmr_review_status_id'] == 3) {
                                $summary_dmr['EJ']['rejected'] += 1;
                                $total['rejected'] += 1;
                            }
                            else if($d['submitted_at']!= '-'){
                                $summary_dmr['EJ']['queue'] +=1;
                                $total['queue'] +=1;
                            }
                        }
                        else if($d['prk_parent'] == 'Har_Overhoul'){
                            $d['parent'] = 'OH';
                            $summary_dmr['OH']['total'] += 1;
                            $total['total'] += 1;

                            if($d['dmr_review_status_id'] == 1) {
                                $summary_dmr['OH']['approved'] += 1;
                                $total['approved'] += 1;
                            }
                            else if($d['dmr_review_status_id'] == 2) {
                                $summary_dmr['OH']['revised'] += 1;
                                $total['revised'] +=1;
                            }
                            else if($d['dmr_review_status_id'] == 3) {
                                $summary_dmr['OH']['rejected'] += 1;
                                $total['rejected'] += 1;
                            }
                            else if($d['submitted_at']!= '-'){
                                $summary_dmr['OH']['queue'] +=1;
                                $total['queue'] +=1;
                            }
                        }

                        else{
                            $d['parent'] = 'Lainnya';
                            $summary_dmr['Lainnya']['total'] += 1;
                            $total['total'] += 1;

                            if($d['dmr_review_status_id'] == 1) {
                                $summary_dmr['Lainnya']['approved'] +=
                                $total['approved'] += 1; 1;
                            }
                            else if($d['dmr_review_status_id'] == 2) {
                                $summary_dmr['Lainnya']['revised'] += 1;
                                $total['revised'] +=1;
                            }
                            else if($d['dmr_review_status_id'] == 3) {
                                $summary_dmr['Lainnya']['rejected'] += 1;
                                $total['rejected'] += 1;
                            }
                            else if($d['submitted_at']!= '-'){
                                $summary_dmr['Lainnya']['queue'] +=1;
                                $total['queue'] +=1;
                            }
                        }
                        array_push($data_dmr, $d);
                    }

                }

                $piechart_summary = array();
                $piechart_summary['N-A'] = ($total['total'] - ($total['queue'] + $total['revised'] + $total['approved'] + $total['rejected']));
                $piechart_summary['Queue'] = $total['queue'];
                $piechart_summary['Revised'] = $total['revised'];
                $piechart_summary['Approved'] = $total['approved'];
                $piechart_summary['Rejected'] = $total['rejected'];
            // End of DMR

            // Start TOR
                $tor = $this->get_tor($input_tahun, $input_lokasi->id);
                $data_tor = array();
                $summary_tor = array();
                $summary_tor['OH'] = array('total' => 0, 'queue' => 0, 'rejected' => 0, 'revised' => 0, 'approved' => 0);
                $summary_tor['EJ'] = array('total' => 0, 'queue' => 0, 'rejected' => 0, 'revised' => 0, 'approved' => 0);
                $summary_tor['Investasi'] = array('total' => 0, 'queue' => 0, 'rejected' => 0, 'revised' => 0, 'approved' => 0);
                $summary_tor['Lainnya'] = array('total' => 0, 'queue' => 0, 'rejected' => 0, 'revised' => 0, 'approved' => 0);

                $total_tor = array('approved' => 0, 'rejected' => 0, 'revised' => 0, 'total' => 0, 'queue' => 0);

                foreach ($data_query as $key_form => $q) {
                    // $hasil_query[$key] = array();
                    $documents_id = $this->query_form($q['id'], $input_lokasi->id, $q['kolom_dokumen_id'], $q['kolom_dmr'], $q['sheet_name']);
                    $prks = $this->query_form($q['id'], $input_lokasi->id, $q['kolom_prk'], $q['kolom_dmr'], $q['sheet_name']);
                    $nama_prks = $this->query_form($q['id'], $input_lokasi->id, $q['kolom_deskripsi_prk'], $q['kolom_dmr'], $q['sheet_name']);
                    $prk_parents = $this->query_form($q['id'], $input_lokasi->id, $q['kolom_prk_parent'], $q['kolom_dmr'], $q['sheet_name']);

                    foreach ($documents_id as $key => $dokumen_id) {
                        $d = array('dokumen_id' => $dokumen_id->value,
                                'prk' => '',
                                'nama_prk' => '',
                                'prk_parent' => '',
                                'parent' => '',
                                'document' => '-',
                                'no_dokumen_dmr' => '',
                                'anggaran'=>0,
                                'tor_status' => 'Draft',
                                'review_status'=> '-',
                                'tor_review_status_id' => 0,
                                'submitted_at' => '-',
                                'revised_at' => '-',
                                'rejected_at' => '-',
                                'approved_at' => '-',);

                        foreach ($prks as $key2 => $prk) {
                            if($dokumen_id->row == $prk->row)
                                $d['prk'] = $prk->value;
                        }
                        foreach ($prk_parents as $key2 => $prk_parent) {
                            if($dokumen_id->row == $prk_parent->row)
                                $d['prk_parent'] = $prk_parent->value;
                        }
                        foreach ($nama_prks as $key2 => $nama_prk) {
                            if($dokumen_id->row == $nama_prk->row)
                                $d['nama_prk'] = $nama_prk->value;
                        }

                        foreach ($tor as $key2 => $v_tor) {
                            // if($v_tor->no_dokumen == $dokumen_id->value && ($v_tor->tor_review_phase_id == 3 || $v_tor->tor_review_phase_id == 4)){
                            if($v_tor->no_dokumen_dmr == $dokumen_id->value){
                                $d['tor_status'] = 'Submitted';
                                if($v_tor->tor_review_phase_id == 4){
                                    $d['review_status'] = 'Approved';
                                    $d['tor_review_status_id'] = 1;
                                }
                                else{
                                    $d['review_status'] = $v_tor->review_status;
                                    $d['tor_review_status_id'] = $v_tor->tor_review_status_id;
                                }
                                // $d['review_status'] = ($d['dmr_status'] == 'Submitted' && $v_tor->dmr_review_status_id == null ? 'Queue' : $v_tor->review_status);
                                // if ($v_tor->anggaran_prk_form == null) {
                                //  # code...
                                //  $d['anggaran'] = $v_tor->jumlah_anggaran;
                                // } else {
                                //  $d['anggaran'] = $v_tor->anggaran_prk_form;
                                // }
                                $d['document'] = $v_tor->tor_filepath;
                                $d['submitted_at'] = $v_tor->submitted_at;
                                $d['revised_at'] = $v_tor->revised_at;
                                $d['rejected_at'] = $v_tor->rejected_at;
                                $d['approved_at'] = $v_tor->approved_at;
                                $d['no_dokumen_dmr'] = $v_tor->no_dokumen_dmr;
                            }
                        }
                        if($data_query[$key_form]['sheet_name'] == 'I-Form 10'){
                            $d['parent'] = 'Investasi';
                            $summary_tor['Investasi']['total'] += 1;
                            $total_tor['total'] += 1;

                            if($d['tor_review_status_id'] == 1) {
                                $summary_tor['Investasi']['approved'] += 1;
                                $total_tor['approved'] += 1;
                            }
                            else if($d['tor_review_status_id'] == 2) {
                                $summary_tor['Investasi']['revised'] += 1;
                                $total_tor['revised'] +=1;
                            }
                            else if($d['tor_review_status_id'] == 3) {
                                $summary_tor['Investasi']['rejected'] += 1;
                                $total_tor['rejected'] += 1;
                            }
                            else if($d['submitted_at']!= '-'){
                                $summary_tor['Investasi']['queue'] +=1;
                                $total_tor['queue'] +=1;
                            }
                        }
                        else if($d['prk_parent'] == 'Har_Project'){
                            $d['parent'] = 'EJ';
                            $summary_tor['EJ']['total'] += 1;
                            $total_tor['total'] += 1;

                            if($d['tor_review_status_id'] == 1) {
                                $summary_tor['EJ']['approved'] += 1;
                                $total_tor['approved'] += 1;
                            }
                            else if($d['tor_review_status_id'] == 2) {
                                $summary_tor['EJ']['revised'] += 1;
                                $total_tor['revised'] +=1;
                            }
                            else if($d['tor_review_status_id'] == 3) {
                                $summary_tor['EJ']['rejected'] += 1;
                                $total_tor['rejected'] += 1;
                            }
                            else if($d['submitted_at']!= '-'){
                                $summary_tor['EJ']['queue'] +=1;
                                $total_tor['queue'] +=1;
                            }
                        }
                        else if($d['prk_parent'] == 'Har_Overhoul'){
                            $d['parent'] = 'OH';
                            $summary_tor['OH']['total'] += 1;
                            $total_tor['total'] += 1;

                            if($d['tor_review_status_id'] == 1) {
                                $summary_tor['OH']['approved'] += 1;
                                $total_tor['approved'] += 1;
                            }
                            else if($d['tor_review_status_id'] == 2) {
                                $summary_tor['OH']['revised'] += 1;
                                $total_tor['revised'] +=1;
                            }
                            else if($d['tor_review_status_id'] == 3) {
                                $summary_tor['OH']['rejected'] += 1;
                                $total_tor['rejected'] += 1;
                            }
                            else if($d['submitted_at']!= '-'){
                                $summary_tor['OH']['queue'] +=1;
                                $total_tor['queue'] +=1;
                            }
                        }

                        else{
                            $d['parent'] = 'Lainnya';
                            $summary_tor['Lainnya']['total'] += 1;
                            $total_tor['total'] += 1;

                            if($d['tor_review_status_id'] == 1) {
                                $summary_tor['Lainnya']['approved'] +=
                                $total_tor['approved'] += 1; 1;
                            }
                            else if($d['tor_review_status_id'] == 2) {
                                $summary_tor['Lainnya']['revised'] += 1;
                                $total_tor['revised'] +=1;
                            }
                            else if($d['tor_review_status_id'] == 3) {
                                $summary_tor['Lainnya']['rejected'] += 1;
                                $total_tor['rejected'] += 1;
                            }
                            else if($d['submitted_at']!= '-'){
                                $summary_tor['Lainnya']['queue'] +=1;
                                $total_tor['queue'] +=1;
                            }
                        }
                        array_push($data_tor, $d);
                    }

                }

                $piechart_summary_tor = array();
                $piechart_summary_tor['N-A'] = ($total_tor['total'] - ($total_tor['queue'] + $total_tor['revised'] + $total_tor['approved'] + $total_tor['rejected']));
                $piechart_summary_tor['Queue'] = $total_tor['queue'];
                $piechart_summary_tor['Revised'] = $total_tor['revised'];
                $piechart_summary_tor['Approved'] = $total_tor['approved'];
                $piechart_summary_tor['Rejected'] = $total_tor['rejected'];

            // End of TOR

            if($request->download && $request->type){
                $judul='';
                // DOWNLOAD Status DMR
                if($request->download == 'status-dmr'){
                    if($request->type=='pdf'){
                        $judul='Status DMR';
                        return view('output/status-dmr-pdf',compact('input_sb', 'judul','input_lokasi','input_distrik','input_tahun', 'data_dmr', 'summary_dmr','judul', 'total', 'input_fase', 'input_form_6_rutin', 'input_form_10_pln', 'input_form_6_reimburse', 'input_form_10_pk', 'input_form_10_pu', 'piechart_summary'));

                    }
                    else if($request->type=='excel'){
                        Excel::create('Status DMR', function ($excel) use($data_dmr, $summary_dmr, $input_sb, $input_tahun, $input_distrik, $input_lokasi) {
                            $excel->setTitle('Status DMR');
                            $excel->setCreator('Laravel')->setCompany('Profio', 'PJB');
                            $excel->setDescription('Rincian Status DMR');
                            $excel->sheet('Status DMR', function ($sheet) use($data_dmr, $summary_dmr, $input_sb, $input_tahun, $input_distrik, $input_lokasi){
                                $sheet->loadView('output/status-dmr-excel')->with('data_dmr', $data_dmr)->with('summary_dmr', $summary_dmr)->with('input_sb', $input_sb)->with('input_tahun', $input_tahun)->with('input_distrik', $input_distrik)->with('input_lokasi', $input_lokasi);
                            });
                        })->download('xlsx');
                    }
                }
                // DOWNLOAD Status TOR
                else{
                    if($request->type=='pdf'){
                        $judul='Status TOR';
                        return view('output/status-tor-pdf',compact('input_sb', 'judul','input_lokasi','input_distrik','input_tahun', 'data_tor', 'summary_tor','judul', 'total', 'input_fase', 'input_form_6_rutin', 'input_form_10_pln', 'input_form_6_reimburse', 'input_form_10_pk', 'input_form_10_pu', 'piechart_summary_tor'));

                    }
                    else if($request->type=='excel'){
                        Excel::create('Status TOR', function ($excel) use($data_tor, $summary_tor, $input_sb, $input_tahun, $input_distrik, $input_lokasi) {
                            $excel->setTitle('Status TOR');
                            $excel->setCreator('Laravel')->setCompany('Profio', 'PJB');
                            $excel->setDescription('Rincian Status TOR');
                            $excel->sheet('Status TOR', function ($sheet) use($data_tor, $summary_tor, $input_sb, $input_tahun, $input_distrik, $input_lokasi){
                                $sheet->loadView('output/status-tor-excel')->with('data_tor', $data_tor)->with('summary_tor', $summary_tor)->with('input_sb', $input_sb)->with('input_tahun', $input_tahun)->with('input_distrik', $input_distrik)->with('input_lokasi', $input_lokasi);
                            });
                        })->download('xlsx');
                    }
                }
            }

        }



        return view('output/status-dmr', compact('sb', 'tahun', 'fase', 'distrik', 'lokasi',
            'input_tahun', 'input_sb', 'input_distrik', 'input_lokasi', 'input_fase',
            'input_form_10_pln', 'input_form_10_pk', 'input_form_10_pu', 'input_form_6_rutin', 'input_form_6_reimburse',
            'drafts_form_10_pln', 'drafts_form_10_pk', 'drafts_form_10_pu', 'drafts_form_6_rutin', 'drafts_form_6_reimburse',
            'data_dmr', 'summary_dmr', 'piechart_summary',
            'data_tor', 'summary_tor', 'piechart_summary_tor'
            ));
    }

    public function Ajax($id_lokasi, $id)
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

    public function ajax_draft($id_lokasi, $id_tahun, $id_jenis)
    {
        $draft= DB::select("select distinct f.id, f.draft_versi
                            from file_imports f
                            join templates t on f.template_id = t.id
                            join excel_datas e on e.file_import_id = f.id
                            where t.jenis_id=".$id_jenis." and e.lokasi_id = ".$id_lokasi." and t.tahun= ".$id_tahun."
                            group by f.id, f.draft_versi;");

        return json_encode($draft);
    }

    public function query_draft($jenis_id, $id_lokasi, $id_tahun){
        $draft= DB::select("select distinct f.id, f.draft_versi
                            from file_imports f
                            join templates t on f.template_id = t.id
                            join excel_datas e on e.file_import_id = f.id
                            where t.jenis_id=".$jenis_id." and e.lokasi_id = ".$id_lokasi." and t.tahun= ".$id_tahun."
                            group by f.id, f.draft_versi;");
        return $draft;
    }

    public function query_form($file_import_id, $lokasi_id, $kolom_yg_diambil, $kolom_dmr, $nama_sheet){
        $data = DB::select("select e.row, e.value, e.kolom
                from excel_datas e
                join sheets s on s.id = e.sheet_id
                where e.lokasi_id = ".$lokasi_id." and e.file_import_id = ".$file_import_id."
                and s.name like '".$nama_sheet."' and e.kolom like '".$kolom_yg_diambil."' and row in
                (select e.row
                    from excel_datas e
                    join sheets s on s.id = e.sheet_id
                    where e.lokasi_id = ".$lokasi_id." and e.file_import_id = ".$file_import_id." and s.name like '".$nama_sheet."' and e.kolom like '".$kolom_dmr."' and upper(e.value) like 'YA'
                )");
        return $data;
    }

    public function get_dmr($tahun, $lokasi_id){
        // $review_phase_stek = DmrReviewPhase::where('role_id',9)->get();
        // $review_phase_kadiv_anggaran = DmrReviewPhase::where('role_id',7)->get();
        $data = DB::select("select u.name, d.*, s.name as review_status from dmr d
                            left join dmr_review_status s on s.id = d.dmr_review_status_id
                            left join users u on u.id = d.approved_by 
                            where d.tahun_anggaran = ".$tahun." and lokasi_id=".$lokasi_id."");
        return $data;
    }

    public function get_tor($tahun, $lokasi_id){
        // $review_phase_stek = DmrReviewPhase::where('role_id',9)->get();
        // $review_phase_kadiv_anggaran = DmrReviewPhase::where('role_id',7)->get();
        $data = DB::select("select t.*, s.name as review_status from tor t
                            left join tor_review_status s on s.id = t.tor_review_status_id
                            where t.tahun_anggaran = ".$tahun." and lokasi_id=".$lokasi_id."");
        return $data;
    }    
}
