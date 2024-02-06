<?php namespace App\Http\Controllers;

// use Request;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Entities\Dmr;
use App\Entities\DmrAttachment;
use App\Entities\DmrReviewPhase;
use App\Entities\DmrReviewStatus;
use App\Entities\DmrReview;
use App\Entities\DmrReviewAttachment;
use App\Entities\StrategiBisnis;
use App\Entities\Distrik;
use App\Entities\Lokasi;
use App\Entities\User;
use App\Entities\ExcelData;
use App\Entities\Role;
use App\Entities\FileImport;
use App\Entities\ApprovalKkp;
use App\Entities\BidangDivisi;
use Mail;
use DB;

class ApprovalKkpController extends Controller
{
    public function index()
    {
        $grupdiv_id = session('grupdiv_id');
        $role_id = session('role_id');

        $dmr_review_phase = DmrReviewPhase::where('role_id', $role_id)->first();
        $role_spv_unit_dmr_tor = Role::find(ROLE_ID_SPV_UNIT_DMR);
        // dd($dmr_review_phase);

        //jika staff unit, redirect ke daftar dmr
        if($role_id == 2 || $role_id == 1)
            return redirect('dmr/daftar');

        //jika tidak memiliki hak approval, kembali ke home
        if($dmr_review_phase == null)
            return redirect('');

        if($role_id == ROLE_ID_STAFF || $role_id == ROLE_ID_KABID || $role_id == ROLE_ID_KADIV_RISK || $role_id == ROLE_ID_MANAGER_RISK)
        {
            $Sb = StrategiBisnis::all();
            $input_sb = Input::get('strategi_bisnis');
            $input_distrik = Input::get('distrik');
            $input_bagian = Input::get('bagian');
            if($input_sb!= null)
            {
                $distrik = Distrik::where('strategi_bisnis_id',$input_sb)->get();
                $bagian = BidangDivisi::all();
            }
            else 
            {
                $distrik = null;
                $bagian = BidangDivisi::all();
            }
        }
        else{
            $user_id = session('user_id');
            $user = User::find($user_id);
            $Sb = StrategiBisnis::where('id',$user->distrik->strategi_bisnis_id)->get();
            $input_sb = $user->distrik->strategi_bisnis_id;
            $input_distrik = $user->distrik_id;
            $input_bagian = $user->bidang_divisi->id;

            $distrik = Distrik::where('id',$input_distrik)->get();
            $bagian = BidangDivisi::where('id', $input_bagian)->get();
        }

        $approval_dmr = null;
        $input_tahun = Input::get('tahun_anggaran');
        $input_lokasi = Input::get('lokasi');

        if($input_distrik!= null)
            $lokasi = Lokasi::where('distrik_id',$input_distrik)->get();
        else {
            $lokasi = null;
        }

        if($input_lokasi != null AND $input_tahun != null){
            $next_review_phase = DmrReviewPhase::select('id')->where('urutan','>=',$dmr_review_phase->urutan)->get();
            $id_next_review_phase = array();
            foreach ($next_review_phase as $key => $value) {
                array_push($id_next_review_phase, $value->id);
            }

            // pengecekan untuk dmr yang tidak mempunyai dmr_review
            $dmr_non_review = Dmr::where('lokasi_id',$input_lokasi)
                ->where('tahun_anggaran', $input_tahun)
                ->where('is_submitted',1)
                ->whereIn('dmr_review_phase_id',$id_next_review_phase)
                ->doesntHave('dmr_reviews')
                ->get();
            // pembuatan dmr_review untuk dmr yang tidak mempunyai dmr_review
            if ($dmr_non_review->count()) {
                $this->set_dmr_review($dmr_non_review);
            }

            if ($role_id == ROLE_ID_MANAGER_RISK OR $role_id == ROLE_ID_KADIV_RISK) 
            {
                if ($input_distrik==21 || $input_distrik==28) 
                {
                    $approval_dmr = Dmr::where(function($q) use ($input_lokasi, $input_tahun, $id_next_review_phase) {
                        $q->where('lokasi_id',$input_lokasi)
                            ->where('tahun_anggaran', $input_tahun)
                            ->where('is_submitted',1)
                            ->where('is_kkp','1')
                            ->where('bidang_divisi_id',$input_bagian)
                            // ->where('is_kantor_pusat', 1)
                            ->whereIn('dmr_review_phase_id',$id_next_review_phase);
                    })
                    ->orWhere(function($q) use ($input_lokasi, $input_tahun) {
                        $q->where('lokasi_id',$input_lokasi)
                            ->where('tahun_anggaran', $input_tahun)
                            ->where('is_submitted', 1)
                            // ->where('is_kantor_pusat', 1)
                            ->where('dmr_review_phase_id', 4)
                            ->where('dmr_review_status_id', DMR_STATUS_APPROVED);
                    })
                    ->orderBy('id', 'desc')
                    ->get();
                }
                else
                {
                    $approval_dmr = Dmr::where(function($q) use ($input_lokasi, $input_tahun, $id_next_review_phase) {
                        $q->where('lokasi_id',$input_lokasi)
                            ->where('tahun_anggaran', $input_tahun)
                            ->where('is_submitted',1)
                            ->where('is_kkp','1')
                            // ->where('is_kantor_pusat', 1)
                            ->whereIn('dmr_review_phase_id',$id_next_review_phase);
                    })
                    ->orWhere(function($q) use ($input_lokasi, $input_tahun) {
                        $q->where('lokasi_id',$input_lokasi)
                            ->where('tahun_anggaran', $input_tahun)
                            ->where('is_submitted', 1)
                            // ->where('is_kantor_pusat', 1)
                            ->where('dmr_review_phase_id', 4)
                            ->where('dmr_review_status_id', DMR_STATUS_APPROVED);
                    })
                    ->orderBy('id', 'desc')
                    ->get();
                }
                
                // dd('KP');
            } 
            else 
            {
                if ($input_distrik==21 || $input_distrik==28) 
                {
                    // DB::enableQueryLog();
                    $approval_dmr = Dmr::where('lokasi_id',$input_lokasi)
                        ->where('tahun_anggaran', $input_tahun)
                        ->where('is_submitted',1)
                        ->where('is_kkp',1)
                        ->where('bidang_divisi_id',$input_bagian)
                        // ->whereNotIn('status_appr_id',[1,2])
                        ->whereIn('dmr_review_phase_id',$id_next_review_phase)
                        ->orderBy('id', 'desc')
                        ->get();
                        // dd(DB::getQueryLog());
                    // dd($approval_dmr);
                }
                else
                {
                    // DB::enableQueryLog();
                    $approval_dmr = Dmr::where('lokasi_id',$input_lokasi)
                        ->where('tahun_anggaran', $input_tahun)
                        ->where('is_submitted',1)
                        ->where('is_kkp',1)
                        // ->whereNotIn('status_appr_id',[1,2])
                        ->whereIn('dmr_review_phase_id',$id_next_review_phase)
                        ->orderBy('id', 'desc')
                        ->get();
                        // dd(DB::getQueryLog());
                    // dd($approval_dmr);
                }
            }

            //getappr untuk menentukan grupdiv apakah berhak approval atau tidak
            $getappr=null;
            if ($grupdiv_id) 
            {
                $getappr= DB::table('approval_kkp')
                ->select('approval_kkp.grupdiv_id', 'grup_divpembinaunit.name as grupdiv_name', 'approval_kkp.peran', 'approval_kkp.urutan', 'approval_kkp.status', 'approval_kkp.kkp_id')
                ->join('grup_divpembinaunit', 'grup_divpembinaunit.id', '=', 'approval_kkp.grupdiv_id')
                ->where('approval_kkp.status', 0)
                ->whereRaw("approval_kkp.urutan = (select min(a.urutan) from approval_kkp as a where a.kkp_id = approval_kkp.kkp_id and a.status = '0')")
                ->where('approval_kkp.grupdiv_id', $grupdiv_id)
                ->orderBy('urutan', 'asc')
                ->get();
            }

            $review_skr= DB::table('approval_kkp')
            ->select('approval_kkp.grupdiv_id', 'grup_divpembinaunit.name as grupdiv_name', 'approval_kkp.peran', 'approval_kkp.urutan', 'approval_kkp.status', 'approval_kkp.kkp_id')
            ->join('grup_divpembinaunit', 'grup_divpembinaunit.id', '=', 'approval_kkp.grupdiv_id')
            ->where('approval_kkp.status', 0)
            ->whereRaw("approval_kkp.urutan = (select min(a.urutan) from approval_kkp as a where a.kkp_id = approval_kkp.kkp_id and a.status = '0')")
            ->orderBy('urutan', 'asc')
            ->get();

            // DB::enableQueryLog();
            $review_by= DB::table('approval_kkp')
            ->select('approval_kkp.grupdiv_id', 'grup_divpembinaunit.name as grupdiv_name', 'approval_kkp.peran', 'approval_kkp.urutan', 'approval_kkp.status', 'approval_kkp.kkp_id')
            ->join('grup_divpembinaunit', 'grup_divpembinaunit.id', '=', 'approval_kkp.grupdiv_id')
            ->where('approval_kkp.status', 1)
            ->whereRaw("approval_kkp.urutan = (select max(a.urutan) from approval_kkp as a where a.kkp_id = approval_kkp.kkp_id and a.status = '1')")
            ->orderBy('urutan', 'asc')
            ->get();
            // dd(DB::getQueryLog());

            //get id distrik untuk mencari user dengan id distrik terkait
            $datrs=array();
            foreach ($approval_dmr as $key => $value) 
            {
                $grupdiv= $grupdivname= "";
                foreach ($review_skr as $valrs) 
                {
                    if ($value->id == $valrs->kkp_id) 
                    {
                        $grupdivname= $valrs->grupdiv_name;

                        if(empty($grupdiv))
                            $grupdiv= $grupdivname;
                        else
                        {
                            $grupdiv= $grupdiv.", ".$grupdivname;
                        }
                    }
                }
                $datrs[$key]['kkp_id']= $value->id;
                $datrs[$key]['grupdivname']= $grupdiv;


                $grupdivrb= $grupdivrbname= "";
                foreach ($review_by as $valrb) 
                {
                    if ($value->id == $valrb->kkp_id) 
                    {
                        $grupdivrbname= $valrb->grupdiv_name;

                        if(empty($grupdivrb))
                            $grupdivrb= $grupdivrbname;
                        else
                        {
                            $grupdivrb= $grupdivrb.", ".$grupdivrbname;
                        }
                    }
                }
                $datrb[$key]['kkp_id']= $value->id;
                $datrb[$key]['grupdivname']= $grupdivrb;
            }
        }

        return view('approval_kkp.daftar_approval_kkp', compact('Sb', 'input_tahun', 'input_sb', 'input_distrik', 'input_lokasi', 'distrik', 'lokasi', 'approval_dmr', 'dmr_review_phase', 'role_id', 'role_spv_unit_dmr_tor', 'datrs', 'getappr', 'review_skr', 'datrb', 'input_bagian', 'bagian'));
    }

    private function set_dmr_review($dmr_non_review)
    {
        foreach ($dmr_non_review as $dnr) {
            if ($dnr->dmr_review_phase_id != 0) {
                $dmr_review = new DmrReview;
                $dmr_review->dmr_id = $dnr->id;
                $dmr_review->dmr_review_phase_id = $dnr->dmr_review_phase_id;
                $dmr_review->dmr_review_status_id = $dnr->dmr_review_status_id;
                $dmr_review->alasan = $dnr->alasan;
                $dmr_review->alasan_latar_belakang = $dnr->alasan_latar_belakang;
                $dmr_review->alasan_sasaran_tujuan = $dnr->alasan_sasaran_tujuan;
                $dmr_review->alasan_permasalahan = $dnr->alasan_permasalahan;
                $dmr_review->alasan_alternatif_pencapaian = $dnr->alasan_alternatif_pencapaian;
                $dmr_review->alasan_benefit_operasional = $dnr->alasan_benefit_operasional;
                $dmr_review->alasan_benefit_finansial = $dnr->alasan_benefit_finansial;
                $dmr_review->approved_at = $dnr->approved_at;
                $dmr_review->approved_by = $dnr->approved_by;
                $dmr_review->revised_at = $dnr->revised_at;
                $dmr_review->revised_by = $dnr->revised_by;
                $dmr_review->rejected_at = $dnr->rejected_at;
                $dmr_review->rejected_by = $dnr->rejected_by;
                $dmr_review->created_by = session('user_id');
                $dmr_review->is_new = 1;
                $dmr_review->save();
            }
        }
    }

    public function Ajax($id)
     {
        $ds = Distrik::where('strategi_bisnis_id', $id)->select("name","id")->get();

        return json_encode($ds);
     }

    public function myformAjax2($id)
      {
        $lokasi = Lokasi::where('distrik_id', $id)->select("name", "id")->get();

        return json_encode($lokasi);
      }

    public function approval(Request $request, $id)
    {
        if ($request->isMethod("get")) {

            $item['dmr_review_status'] = DmrReviewStatus::all();
            // $item['dmr_attachment'] = DmrAttachment::all();
            $item['dmr_attachment'] = DmrAttachment::where('dmr_id', $id)->get();
            $item['dmr'] = Dmr::find($id);

            $lokasi = Lokasi::find($item['dmr']->lokasi_id);
            $item['bidang_divisi']=null;
            if ($lokasi->distrik_id==21 || $lokasi->distrik_id==28) 
            {
                $item['bidang_divisi']= BidangDivisi::find($item['dmr']->bidang_divisi_id);
            }
            
            $item['status_dokumen'] = FALSE;

            // $dokumen = ExcelData::whereHas('file_import', function($q) {
            //         $q->where('fase_id', 1);
            //     })
            //     ->where('value', $item['dmr']->no_dokumen)
            //     ->where('lokasi_id', $item['dmr']->lokasi_id)
            //     ->where(function($q) {
            //         $q->where('kolom', 'AA')->orWhere('kolom', 'AB')->orWhere('kolom', 'Z');
            //     })
            //     ->first();

            $dokumen = DB::table('excel_datas')
                        ->join('file_imports', 'file_imports.id', '=', 'excel_datas.file_import_id')
                        ->join('templates', 'templates.id', '=', 'file_imports.template_id')
                       // ->where('file_imports.fase_id', 1)
                        ->where(function($q) 
                            {
                            //20200312 - perubahan pengecekan dokumen fase 3    
                            $q->where('file_imports.fase_id', 1)->orWhere('file_imports.fase_id', 2)->orWhere('file_imports.fase_id', 3);
                            })
                        ->where('file_imports.tahun', $item['dmr']->tahun_anggaran)
                        ->where('excel_datas.value', $item['dmr']->no_dokumen)
                        ->where('excel_datas.lokasi_id', $item['dmr']->lokasi_id)
                        ->where(function($q) {
                            $q->where('excel_datas.kolom', 'AA')->orWhere('excel_datas.kolom', 'AB')->orWhere('excel_datas.kolom', 'Z');
                        })
                        ->first();
            // dd($item['dmr']);
            // dd($dokumen);
            if ($dokumen) {
                // $file_import = FileImport::find($dokumen->file_import_id);
                // dd($file_import);
                // dd($dokumen->file_import);
                $item['status_dokumen'] = TRUE;

                $data_prk = $data_anggaran = NULL;
                $excel_data = ExcelData::where('sheet_id', $dokumen->sheet_id)
                    ->where('lokasi_id', $dokumen->lokasi_id)
                    ->where('row', $dokumen->row)
                    ->where('file_import_id', $dokumen->file_import_id)
                    ->get();

                if (sizeof($excel_data) > 0) {
                    $col_no_prk = $col_anggaran_prk = '';
                    // if ($dokumen->file_import->template->jenis_id == 2) {
                    //     $col_no_prk = 'I';
                    //     $col_anggaran_prk = 'AN';
                    // } elseif ($dokumen->file_import->template->jenis_id == 3) {
                    //     $col_no_prk = 'I';
                    //     $col_anggaran_prk = 'AN';
                    // } elseif ($dokumen->file_import->template->jenis_id == 4) {
                    //     $col_no_prk = 'I';
                    //     $col_anggaran_prk = 'AJ';
                    // } elseif ($dokumen->file_import->template->jenis_id == 5) {
                    //     $col_no_prk = 'H';
                    //     $col_anggaran_prk = 'AI';
                    // } elseif ($dokumen->file_import->template->jenis_id == 6) {
                    //     $col_no_prk = 'J';
                    //     $col_anggaran_prk = 'AK';
                    // } else {
                    //     $col_no_prk = 'ZZ';
                    //     $col_anggaran_prk = 'ZZ';
                    // }
                    if ($dokumen->jenis_id == 2) {
                        $col_no_prk = 'I';
                        $col_anggaran_prk = 'AN';
                    } elseif ($dokumen->jenis_id == 3) {
                        $col_no_prk = 'I';
                        $col_anggaran_prk = 'AN';
                    } elseif ($dokumen->jenis_id == 4) {
                        $col_no_prk = 'I';
                        $col_anggaran_prk = 'AJ';
                    } elseif ($dokumen->jenis_id == 5) {
                        $col_no_prk = 'H';
                        $col_anggaran_prk = 'AI';
                    } elseif ($dokumen->jenis_id == 6) {
                        $col_no_prk = 'J';
                        $col_anggaran_prk = 'AK';
                    } else {
                        $col_no_prk = 'ZZ';
                        $col_anggaran_prk = 'ZZ';
                    }
                    
                    foreach ($excel_data as $key => $value) {
                        if ($value->kolom == $col_no_prk) {
                            $data_prk = $value->value;
                        } elseif ($value->kolom == $col_anggaran_prk) {
                            $data_anggaran = $value->value;
                        }
                    }
                }
                //dd($data_prk, $data_anggaran);
                
                $item['dmr']->update(['no_prk_form' => $data_prk, 'anggaran_prk_form' => $data_anggaran]);
            } elseif ($item['dmr']->no_prk_form AND $item['dmr']->anggaran_prk_form) {
                $item['status_dokumen'] = TRUE;
            } elseif (is_null($item['dmr']->is_kantor_pusat) AND (is_null($item['dmr']->no_prk_form) OR is_null($item['dmr']->anggaran_prk_form))) {
                $item['status_dokumen'] = 2;
            }
            $item['dmr'] = Dmr::find($id);

            $item['statskkp']= 'false';
            if ($item['dmr']['status_appr_id']==3 || $item['dmr']['status_appr_id']==6 || $item['dmr']['status_appr_id']==4 || $item['dmr']['status_appr_id']==7) 
            {
                $item['statskkp']= 'true';
            }

            $item['getappr']= DB::table('approval_kkp')
                ->select('approval_kkp.grupdiv_id', 'grup_divpembinaunit.name as grupdiv_name', 'approval_kkp.peran', 'approval_kkp.urutan', 'approval_kkp.status', 'approval_kkp.kkp_id')
                ->join('grup_divpembinaunit', 'grup_divpembinaunit.id', '=', 'approval_kkp.grupdiv_id')
                ->where('approval_kkp.status', 0)
                ->whereRaw("approval_kkp.urutan = (select min(a.urutan) from approval_kkp as a where a.kkp_id = approval_kkp.kkp_id and a.status = '0')")
                ->where('approval_kkp.grupdiv_id', session('grupdiv_id'))
                ->where('approval_kkp.kkp_id', $item['dmr']['id'])
                ->orderBy('urutan', 'asc')
                ->first();

            $item['grupdiv_id']= session('grupdiv_id');

            $appr_kkp = ApprovalKkp::where('grupdiv_id', session('grupdiv_id'))
                ->where('kkp_id', $item['dmr']->id)
                ->where('status', 0)
                ->first();
                // return $appr_kkp;
            $item['peran']= '';
            if (count($appr_kkp)>0) 
            {
                $item['peran']= $appr_kkp->peran;
            }
            

            // dd($item['dmr']['anggaran_prk']);
            // dd(session('role_id'));
            $item['current_dmr_review_phase'] = DmrReviewPhase::where('role_id',session('role_id'))->first();
            // dd($item['current_dmr_review_phase']);
            $item['dmr_review'] = DmrReview::where('dmr_id', $item['dmr']['id'])
                ->where('dmr_review_phase_id', $item['current_dmr_review_phase']['id'])
                ->orderBy('id', 'desc')
                ->first();
            // dd($item['dmr_review']);

            $item['apprnew']='false';
            if ($item['dmr']->dmr_review_phase_id=='3' && $item['dmr']->dmr_review_status_id=='4') 
            {
                if (count($item['getappr'])>0) 
                {
                    if ($item['getappr']->grupdiv_id != $item['grupdiv_id'] || ($item['getappr']->grupdiv_id == $item['grupdiv_id'] && $item['statskkp'] == 'false')) 
                    {
                        $item['apprnew']='true';
                    }
                }
            }

            return view('approval_kkp.detail_approval_kkp', $item);
        }
        elseif ($request->isMethod('post')) {
            if(Input::get('dmr_review_status_id') == DMR_STATUS_REVISED)
                $this->validate($request, [
                    'alasan' => 'required'],
                    ['alasan.required' => 'Alasan Umum wajib diisi jika status approval adalah Revised.',
                ]);
            // Mencari DMR yang direview
            $dmr = Dmr::find($id);

            $lokasi = Lokasi::find($dmr->lokasi_id);
            $bidang_divisi=null;
            if ($lokasi->distrik_id==21 || $lokasi->distrik_id==28) 
            {
                $bidang_divisi= BidangDivisi::find($dmr->bidang_divisi_id);
            }

            //untuk alur approval baru kkp
            if ($dmr->dmr_review_phase_id=='3' && $dmr->dmr_review_status_id=='4') 
            {
                $appr_kkp = ApprovalKkp::where('grupdiv_id', session('grupdiv_id'))
                ->where('kkp_id', $dmr->id)
                ->where('status', 0)
                ->first();
                // return $appr_kkp;
                $peran= $appr_kkp->peran;

                // Update dmr_review
                $dmr_review = new DmrReview;

                $dmr_review->dmr_id = $dmr->id;
                $dmr_review->dmr_review_phase_id = $appr_kkp->grupdiv_id; //direviw oleh
                $dmr_review->is_new = 1;
                $dmr_review->created_by = session('user_id');
                $dmr_review->alasan = $dmr->alasan = Input::get('alasan');                

                $dmr->updated_by = session('user_id');

                if (Input::get('dmr_review_status_id') == DMR_STATUS_APPROVED) 
                {
                    $dmr->alasan = $dmr->alasan_latar_belakang = $dmr->alasan_sasaran_tujuan = $dmr->alasan_permasalahan =
                        $dmr->alasan_alternatif_pencapaian = $dmr->alasan_benefit_operasional = $dmr->alasan_benefit_finansial = NULL;
                    $dmr->revised_at = $dmr->revised_by = $dmr->rejected_at = $dmr->rejected_by = NULL;

                    $appr_kkpupd= ApprovalKkp::where('grupdiv_id', session('grupdiv_id'))
                        ->where('kkp_id', $dmr->id)
                        ->where('status', 0)
                        ->update([
                            'status' => 1, //update di table approval_kkp status udah check/appr
                            'pegawai_id' => session('user_id'),
                            'tanggal' => date("Y-m-d"),
                            'updated_at' => date("Y-m-d H:i:s"),
                        ]);
                        // ->update(['status' => '1'])
                        // ->update(['pegawai_id' => session('user_id')])
                        // ->update(['tanggal' => date("Y-m-d")])
                        // ->update(['update_at' => date("Y-m-d H:i:s")])
                        ;

                    // $appr_kkp->status= 1;
                    // $appr_kkp->pegawai_id= session('user_id');
                    // $appr_kkp->tanggal= date("Y-m-d");
                    // $appr_kkp->update_at= date("Y-m-d H:i:s");
                    // $appr_kkp->where('grupdiv_id', session('grupdiv_id'))
                    // ->where('kkp_id', $dmr->id)
                    // ->where('status', 0)
                    // $appr_kkp->save();

                    $countperan = ApprovalKkp::where('kkp_id', $dmr->id)
                    ->where('status', 0)
                    ->where('peran', $peran)
                    ->count();

                    if ($peran=='checker') 
                    {
                        if ($countperan=='0') 
                        {
                            $dmr->status_appr_id = 4; // checkall semua udah check
                            $dmr_review->dmr_review_status_id = 4; //statusnya pada dmr_review
                        }
                        else
                        {
                            $dmr->status_appr_id = 3; // check by sebagian udah check
                            $dmr_review->dmr_review_status_id = 3; //statusnya pada dmr_review
                        }
                    }
                    else
                    {
                        if ($countperan=='0') 
                        {
                            $dmr->status_appr_id = 5; // approved udah approved
                            $dmr->is_publish = 1;
                            $dmr_review->dmr_review_status_id = 5; //statusnya pada dmr_review
                        }
                        else
                        {
                            $dmr->status_appr_id = 7; // approve by sebagian yg udah appr
                            $dmr_review->dmr_review_status_id = 7; //statusnya pada dmr_review
                        }
                    }
                    
                    $dmr_review->appr_ke = '2';
                    $dmr_review->approved_at = $dmr->approved_at = date("Y-m-d H:i:s");
                    $dmr_review->approved_by = $dmr->approved_by = session('user_id');
                    $dmr_review->save();

                    // $urutan = $dmr->dmr_review_phase->urutan;
                    // if ($urutan < $max_phase) {
                    //     $dmr->dmr_review_status_id = DMR_STATUS_QUEUE;
                    //     $dmr->dmr_review_phase_id = $prev_dmr_review_phase_id + 1;

                    //     $new_dmr_review = new DmrReview;
                    //     $new_dmr_review->dmr_id = $dmr->id;
                    //     $new_dmr_review->dmr_review_phase_id = $prev_dmr_review_phase_id + 1;
                    //     $new_dmr_review->dmr_review_status_id = DMR_STATUS_QUEUE;
                    //     $new_dmr_review->is_new = 1;
                    //     $new_dmr_review->created_by = session('user_id');
                    //     $new_dmr_review->save();
                    // } else {
                    //     // $dmr->is_publish = 1;
                    // }

                    // DmrReview::where('dmr_id', $dmr->id)
                    //     ->where('dmr_review_phase_id', $prev_dmr_review_phase_id)
                    //     ->where('is_new', 1)
                    //     ->update(['is_new' => 0]);
                }
                else if (Input::get('dmr_review_status_id') == DMR_STATUS_REVISED) 
                {
                    $appr_kkpupd= ApprovalKkp::where('kkp_id', $dmr->id)
                        ->update([
                            'status' => 2, //update di table approval_kkp status revised
                            'pegawai_id' => session('user_id'),
                            'tanggal' => date("Y-m-d"),
                            'updated_at' => date("Y-m-d H:i:s"),
                        ]);

                    $dmr_review->appr_ke = '2';
                    $dmr_review->dmr_review_status_id = 8; //statusnya pada dmr_review
                    $dmr_review->revised_at = $dmr->revised_at = date("Y-m-d H:i:s");
                    $dmr_review->revised_by = $dmr->revised_by = session('user_id');
                    $dmr_review->save();

                    $dmr->status_appr_id = 8; //status revised
                }

                $dmr->save();

                // if (Input::get('dmr_review_status_id') == DMR_STATUS_REVISED) {
                    //lampiran Review
                    $files = $request->file('filepath_review');
                    // dd($files);
                    // setting filepath
                    if ($request->hasFile('filepath_review')) {
                        $i = 1;
                        foreach ($files as $file) {
                            $destinationPath = "dmr_review";
                            // $filename = $file->getClientOriginalName();
                            $filename= preg_replace('/[^A-Za-z0-9-.\  ]/', '', $file->getClientOriginalName());
                            $file->move($destinationPath.'/'.$dmr_review->id.'/', $filename);
                            $filepath[$i] = $destinationPath.'/'.$dmr_review->id.'/'.$filename;
                            $i++;
                        }
                    }
                    // simpan ke db
                    for( $i = 1 ; $i <= 5 ; $i++ ) {
                        if(isset($filepath[$i])) {
                            $save_filepath = $filepath[$i];
                        }
                        else {
                            $save_filepath = '';
                        }

                        $dmr_review_attachment = array(
                            'dmr_review_id' => $dmr_review->id,
                            'filepath' => $save_filepath,
                            );
                        DmrReviewAttachment::create($dmr_review_attachment);
                    }
                // }
            }
            else
            {
                //untuk alur approval lama
                // 20200826 krp perubahan anggaran_prk_form menjadi menggunakan jumlah_anggaran (anggaran input)
                if ($dmr->jumlah_anggaran >= MINIMUM_ANGGARAN) {
                    $max_phase = MAX_PHASE_KP;
                } else {
                    $max_phase = MAX_PHASE_NON_KP;
                }
                // Mencari dmr_review_phase sesuai dengan role
                $dmr_review_phase = DmrReviewPhase::where('role_id', session('role_id'))->first();
                // Update dmr_review
                $dmr_review = new DmrReview;
                $dmr_review->dmr_id = $dmr->id;
                $dmr_review->dmr_review_phase_id = $dmr->dmr_review_phase_id;
                $dmr_review->dmr_review_status_id = Input::get('dmr_review_status_id');
                $dmr_review->is_new = 1;
                $dmr_review->created_by = session('user_id');

                $dmr->dmr_review_status_id = Input::get('dmr_review_status_id');
                $dmr->updated_by = session('user_id');

                if (Input::get('dmr_review_status_id') == DMR_STATUS_REVISED OR Input::get('dmr_review_status_id') == DMR_STATUS_REJECTED) {
                    $dmr_review->alasan = $dmr->alasan = Input::get('alasan');
                    $dmr_review->alasan_latar_belakang = $dmr->alasan_latar_belakang = Input::get('alasan_latar_belakang');
                    $dmr_review->alasan_sasaran_tujuan = $dmr->alasan_sasaran_tujuan = Input::get('alasan_sasaran_tujuan');
                    $dmr_review->alasan_permasalahan = $dmr->alasan_permasalahan = Input::get('alasan_permasalahan');
                    $dmr_review->alasan_alternatif_pencapaian = $dmr->alasan_alternatif_pencapaian = Input::get('alasan_alternatif_pencapaian');
                    $dmr_review->alasan_benefit_operasional = $dmr->alasan_benefit_operasional = Input::get('alasan_benefit_operasional');
                    $dmr_review->alasan_benefit_finansial = $dmr->alasan_benefit_finansial = Input::get('alasan_benefit_finansial');
                }

                $prev_dmr_review_phase_id = $dmr->dmr_review_phase_id;

                if (Input::get('dmr_review_status_id') == DMR_STATUS_APPROVED) {
                    $dmr->alasan = $dmr->alasan_latar_belakang = $dmr->alasan_sasaran_tujuan = $dmr->alasan_permasalahan =
                        $dmr->alasan_alternatif_pencapaian = $dmr->alasan_benefit_operasional = $dmr->alasan_benefit_finansial = NULL;
                    $dmr_review->approved_at = $dmr->approved_at = date("Y-m-d H:i:s");
                    $dmr_review->approved_by = $dmr->approved_by = session('user_id');
                    $dmr_review->save();

                    $dmr->revised_at = $dmr->revised_by = $dmr->rejected_at = $dmr->rejected_by = NULL;

                    $urutan = $dmr->dmr_review_phase->urutan;
                    if ($urutan < $max_phase) {
                        $dmr->dmr_review_status_id = DMR_STATUS_QUEUE;
                        $dmr->dmr_review_phase_id = $prev_dmr_review_phase_id + 1;

                        $new_dmr_review = new DmrReview;
                        $new_dmr_review->dmr_id = $dmr->id;
                        $new_dmr_review->dmr_review_phase_id = $prev_dmr_review_phase_id + 1;
                        $new_dmr_review->dmr_review_status_id = DMR_STATUS_QUEUE;
                        $new_dmr_review->is_new = 1;
                        $new_dmr_review->created_by = session('user_id');
                        $new_dmr_review->save();
                    } else {
                        $dmr->is_publish = 1;
                    }

                    DmrReview::where('dmr_id', $dmr->id)
                        ->where('dmr_review_phase_id', $prev_dmr_review_phase_id)
                        ->where('is_new', 1)
                        ->update(['is_new' => 0]);
                } else if (Input::get('dmr_review_status_id') == DMR_STATUS_REVISED) {
                    $dmr_review->revised_at = $dmr->revised_at = date("Y-m-d H:i:s");
                    $dmr_review->revised_by = $dmr->revised_by = session('user_id');
                    $dmr_review->save();
                } else if (Input::get('dmr_review_status_id') == DMR_STATUS_REJECTED) {
                    $dmr_review->rejected_at = $dmr->rejected_at = date("Y-m-d H:i:s");
                    $dmr_review->rejected_by = $dmr->rejected_by = session('user_id');
                    $dmr_review->save();
                }
                $dmr->save();

                if (Input::get('dmr_review_status_id') == DMR_STATUS_REVISED OR Input::get('dmr_review_status_id') == DMR_STATUS_REJECTED) {
                    //lampiran Review
                    $files = $request->file('filepath_review');
                    // dd($files);
                    // setting filepath
                    if ($request->hasFile('filepath_review')) {
                        $i = 1;
                        foreach ($files as $file) {
                            $destinationPath = "dmr_review";
                            // $filename = $file->getClientOriginalName();
                            $filename= preg_replace('/[^A-Za-z0-9-.\  ]/', '', $file->getClientOriginalName());
                            $file->move($destinationPath.'/'.$dmr_review->id.'/', $filename);
                            $filepath[$i] = $destinationPath.'/'.$dmr_review->id.'/'.$filename;
                            $i++;
                        }
                    }
                    // simpan ke db
                    for( $i = 1 ; $i <= 5 ; $i++ ) {
                        if(isset($filepath[$i])) {
                            $save_filepath = $filepath[$i];
                        }
                        else {
                            $save_filepath = '';
                        }

                        $dmr_review_attachment = array(
                            'dmr_review_id' => $dmr_review->id,
                            'filepath' => $save_filepath,
                            );
                        DmrReviewAttachment::create($dmr_review_attachment);
                    }
                }
            }

            $request->session()->flash('success','Approval KKP berhasil diubah');
            return redirect('approval_kkp/daftar?tahun_anggaran='.$dmr->tahun_anggaran.'&strategi_bisnis='.$dmr->lokasi->distrik->strategi_bisnis_id.'&distrik='.$dmr->lokasi->distrik_id.'&lokasi='.$dmr->lokasi_id.'&bidang_divisi='.$bidang_divisi);
        }
    }

    public function publish()
    {
        $role_id = session('role_id');
        // $dmr_review_phase = DmrReviewPhase::where('role_id', $role_id)->first();
        // dd($dmr_review_phase);
        //jika staff unit, redirect ke daftar dmr
        // if($role_id == 2 || $role_id == 1)
        //     return redirect('dmr/daftar');
        //jika tidak memiliki hak approval, kembali ke home
        // if($dmr_review_phase == null)
        //     return redirect('');

        if($role_id == ROLE_ID_STAFF || $role_id == ROLE_ID_KABID || $role_id == ROLE_ID_MANAGER_RISK || $role_id == ROLE_ID_KADIV_RISK)
        {
            $Sb = StrategiBisnis::all();
            $input_sb = Input::get('strategi_bisnis');
            $input_distrik = Input::get('distrik');
            $input_bagian = Input::get('bagian');

            if($input_sb!= null)
            {
                $distrik = Distrik::where('strategi_bisnis_id',$input_sb)->get();
                $bagian = BidangDivisi::all();
            }
            else 
            {
                $distrik = null;
                $bagian = BidangDivisi::all();
            }
        }
        else{
            $user_id = session('user_id');
            $user = User::find($user_id);
            $Sb = StrategiBisnis::where('id',$user->distrik->strategi_bisnis_id)->get();
            $input_sb = $user->distrik->strategi_bisnis_id;
            $input_distrik = $user->distrik_id;
            $input_bagian = $user->bidang_divisi->id;

            $distrik = Distrik::where('id',$input_distrik)->get();
            $bagian = BidangDivisi::where('id', $input_bagian)->get();
        }

        $approval_dmr = null;
        $input_tahun = Input::get('tahun_anggaran');
        $input_lokasi = Input::get('lokasi');

        if($input_distrik!= null)
            $lokasi = Lokasi::where('distrik_id',$input_distrik)->get();
        else {
            $lokasi = null;
        }

        $required= 'required';
        if ($role_id=='1' OR $role_id=='9') 
        {
            $required='';
            if($input_tahun != null)
            {
                if ($input_lokasi!=null) 
                {
                    if ($input_distrik==21 || $input_distrik==28) 
                    {
                        $approval_dmr = Dmr::where('lokasi_id',$input_lokasi)
                        ->where('tahun_anggaran', $input_tahun)
                        ->where('is_submitted',1)
                        ->where('is_publish',1)
                        ->where('is_kkp','1')
                        ->where('bidang_divisi_id',$input_bagian)
                        ->orderBy('id', 'desc')
                        ->get();
                    }
                    else
                    {
                        $approval_dmr = Dmr::where('lokasi_id',$input_lokasi)
                        ->where('tahun_anggaran', $input_tahun)
                        ->where('is_submitted',1)
                        ->where('is_publish',1)
                        ->where('is_kkp','1')
                        ->orderBy('id', 'desc')
                        ->get();
                    }
                }
                else
                {
                    $approval_dmr = Dmr::where('tahun_anggaran', $input_tahun)
                        ->where('is_submitted',1)
                        ->where('is_publish',1)
                        ->where('is_kkp','1')
                        ->orderBy('id', 'desc')
                        ->get();
                        //dd($dmr);
                }
            } 
        }
        else
        {
            if($input_lokasi != null AND $input_tahun != null)
            {
                if ($input_distrik==21 || $input_distrik==28) 
                {
                    $approval_dmr = Dmr::where('lokasi_id',$input_lokasi)
                    ->where('tahun_anggaran', $input_tahun)
                    ->where('is_submitted',1)
                    ->where('is_publish',1)
                    ->where('is_kkp','1')
                    ->where('bidang_divisi_id',$input_bagian)
                    ->orderBy('id', 'desc')
                    ->get();
                }
                else
                {
                    $approval_dmr = Dmr::where('lokasi_id',$input_lokasi)
                    ->where('tahun_anggaran', $input_tahun)
                    ->where('is_submitted',1)
                    ->where('is_publish',1)
                    ->where('is_kkp','1')
                    ->orderBy('id', 'desc')
                    ->get();
                }
                
                // penggabungan dmr_review dengan dmr sesuai dengan dmr_review_phase_id
                if ($approval_dmr->count()) {
                    foreach ($approval_dmr as $d) {
                        if ($d->dmr_review_phase_id == 1 OR $d->dmr_review_phase_id == 2 OR $d->is_kantor_pusat == FALSE) {
                            $dmr_review_1 = DmrReview::where('dmr_id', $d->id)->where('dmr_review_phase_id', $d->dmr_review_phase_id)->orderBy('id', 'desc')->first();
                            // if ($dmr_review_1) {
                            //     dd('Kosong');
                            // }
                            $d->dmr_review_1 = $dmr_review_1;
                        } elseif ($d->dmr_review_phase_id == 3) {
                            $dmr_review_1 = DmrReview::where('dmr_id', $d->id)->where('dmr_review_phase_id', 3)->orderBy('id', 'desc')->first();
                            $dmr_review_2 = DmrReview::where('dmr_id', $d->id)->where('dmr_review_phase_id', 5)->orderBy('id', 'desc')->first();
                            $d->dmr_review_1 = $dmr_review_1;
                            $d->dmr_review_2 = $dmr_review_2;
                        } elseif ($d->dmr_review_phase_id == 4) {
                            $dmr_review_1 = DmrReview::where('dmr_id', $d->id)->where('dmr_review_phase_id', 4)->orderBy('id', 'desc')->first();
                            $dmr_review_2 = DmrReview::where('dmr_id', $d->id)->where('dmr_review_phase_id', 6)->orderBy('id', 'desc')->first();
                            $d->dmr_review_1 = $dmr_review_1;
                            $d->dmr_review_2 = $dmr_review_2;
                        }
                    }
                }
            }   
        }
            

        return view('approval_kkp.daftar_published_kkp', compact('Sb', 'input_tahun', 'input_sb', 'input_distrik', 'input_lokasi', 'distrik', 'lokasi', 'approval_dmr', 'role_id', 'input_bagian', 'bagian', 'required'));
    }

    // public function detail($id)
    // {
    //     //
    //     if ($request->isMethod("get")) {

    //         $item['dmr_review_status'] = DmrReviewStatus::all();
    //         // $item['dmr_attachment'] = DmrAttachment::all();
    //         $item['dmr'] = Dmr::find($id);
    //         $item['dmr_attachment'] = DmrAttachment::where('dmr_id', $id)->get();
    //         dd($item['dmr_attachment']);
    //          // if($request->file('dmr_filepath')){
    //          //    $item->dmr_filepath = $dmr_filepath;
    //         return view('approval_kkp/detail', $item);
    //     }
    //     elseif ($request->isMethod('post')) {
    //       //dd(Input::get('alasan'));
    //         $item = Dmr::find($id);
    //         $item->alasan = Input::get('dmr_review_status');
    //         $item->alasan = Input::get('alasan');
    //         $item->save();
    //         return redirect('approval_kkp/daftar');

    //     }
    // }
    //     }
    // }
    public function send_email(Request $request)
    {
        $title ="halo";
        $content = "juga";

        Mail::send('approval_dmr.send', ['title' => $title, 'content' => $content], function ($message)
        {

            $message->from('iplan@no-reply.com', 'IPLAN');

            $message->to('feri@ptpjb.com');

        });

        return response()->json(['message' => 'Request completed']);
        # code...
    }
}
