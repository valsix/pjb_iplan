<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use Auth;
use Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use App\Entities\ExcelData;
use App\Entities\StrategiBisnis;
use App\Entities\Distrik;
use App\Entities\Lokasi;
use App\Entities\User;
use App\Entities\Role;
use App\Entities\Dmr;
use App\Entities\DmrAttachment;
use App\Entities\DmrReview;
use App\Entities\DmrReviewPhase;
use App\Entities\DmrReviewAttachment;
use App\Entities\Tor;
use App\Entities\TorAttachment;
use App\Entities\GroupDivisiPembinaUnit;
use App\Entities\StatusAppr;
use App\Entities\KondisiAICluster;
use App\Entities\ApprovalKkp;
use DB;

class KkpControllerWldn extends Controller
{
    public function index()
    {
        $role_id = session('role_id');
        $role = Role::find($role_id);
        $role_spv_unit_dmr_tor = Role::find(ROLE_ID_SPV_UNIT_DMR);

        //bukan kantor pusat dan bukan Staff Unit
        // if($role->is_kantor_pusat != 1 && $role_id != 2) {
        //     return redirect('');
        // }

        //bukan kantor pusat dan bukan SPV Unit (DMR / TOR)
        // if($role->is_kantor_pusat != 1 && $role_id != 12) {
        //     return redirect('');
        // }

        // if($role_id != 1 && $role_id != 2)
        //     return redirect('');

        // if($role_id == 1){

        //kantor pusat
        if($role->is_kantor_pusat){
            $Sb = StrategiBisnis::all();
            $input_sb = Input::get('strategi_bisnis');
            $input_distrik = Input::get('distrik');
            if($input_sb!= null)
                $distrik = Distrik::where('strategi_bisnis_id',$input_sb)->get();
        }
        else{
            $user_id = session('user_id');
            $user = User::find($user_id);
            $Sb = StrategiBisnis::where('id',$user->distrik->strategi_bisnis_id)->get();
            $input_sb = $user->distrik->strategi_bisnis_id;
            $input_distrik = $user->distrik_id;
            $distrik = Distrik::where('id',$input_distrik)->get();
        }
        // $Sb = StrategiBisnis::all();
        $dmr = null;
        $input_tahun = Input::get('tahun_anggaran');

        $input_lokasi = Input::get('lokasi');

        if($input_distrik!= null)
            $lokasi = Lokasi::where('distrik_id',$input_distrik)->get();

        if($input_lokasi != null && $input_tahun != null)
        {
            // //kantor pusat hanya bisa lihat submitted
            // if($role->is_kantor_pusat) {
            //     // cek apakah ada dmr yang belum memiliki dmr_review
            //     // DB::enableQueryLog();
            //     $dmr_non_review = Dmr::where('lokasi_id',$input_lokasi)
            //             ->where('tahun_anggaran', $input_tahun)
            //             ->where('is_submitted', '1')
            //             ->where('is_kkp', '1')
            //             ->doesntHave('dmr_reviews')
            //             ->get();
            //     // dd(DB::getQueryLog());
            //     // jika ada, dmr yang belum memiliki dmr_review akan dibuatkan dmr_review
            //     if ($dmr_non_review->count()) {
            //         $this->set_dmr_review($dmr_non_review);
            //     }

            //     $dmr = Dmr::where('lokasi_id',$input_lokasi)
            //             ->where('tahun_anggaran', $input_tahun)
            //             ->where('is_submitted', '1')
            //             ->where('is_kkp', '1')
            //             ->orderBy('id', 'desc')
            //             ->get();
			// 			//dd($dmr);
            // }
            // else {
                // // cek apakah ada dmr yang belum memiliki dmr_review
                // // DB::enableQueryLog();
                // $dmr_non_review = Dmr::where('lokasi_id',$input_lokasi)
                //     ->where('tahun_anggaran', $input_tahun)
                //     ->where('is_kkp', '1')
                //     ->doesntHave('dmr_reviews')
                //     ->get();
                // // dd(DB::getQueryLog());
                // // jika ada, dmr yang belum memiliki dmr_review akan dibuatkan dmr_review
                // if ($dmr_non_review->count()) {
                //     $this->set_dmr_review($dmr_non_review);
                // }

                $dmr = Dmr::where('lokasi_id',$input_lokasi)
                    ->where('tahun_anggaran', $input_tahun)
                    ->where('is_kkp', '1')
                    ->orderBy('id', 'desc')
                    ->get();
					//dd($dmr);
            // }
        }
		
		//dd($dmr);
        return view('kkp.daftar', compact('Sb', 'distrik', 'lokasi', 'input_sb', 'input_distrik', 'input_lokasi', 'input_tahun', 'dmr', 'role_spv_unit_dmr_tor', 'role_id'));
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
                $dmr_review->rejected_at = $dnr->rejected_by;
                $dmr_review->rejected_by = $dnr->rejected_by;
                $dmr_review->is_new = 1;
                $dmr_review->created_by = session('user_id');
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

    public function anggaran_no_prk_ajax(Request $request) {

        $id_dokumen = $request->id_dokumen;
        $id_lokasi = $request->id_lokasi;
        $tahun = $request->tahun;
        // dd($id_dokumen, $id_lokasi , $tahun);
        $data_prk = '';
        $data_anggaran = '';
        // Get data id_dokumen
        // $dokumen = DB::table('excel_datas')
        //     ->join('file_imports', 'excel_datas.file_import_id', '=', 'file_imports.id')
        //     ->where('excel_datas.value', $id_dokumen)
        //     ->where('excel_datas.lokasi_id', $id_lokasi)
        //     ->where(function($q) {
        //         $q->where('excel_datas.kolom', 'AA')->orWhere('excel_datas.kolom', 'AB')->orWhere('excel_datas.kolom', 'Z');
        //     })
        //     ->where('file_imports.fase_id', 1)
        //     ->select(
        //         'excel_datas.file_import_id as file_import_id',
        //         'excel_datas.sheet_id as sheet_id',
        //         'excel_datas.lokasi_id as lokasi_id',
        //         'excel_datas.kolom as kolom',
        //         'excel_datas.row as row',
        //         'excel_datas.value as value',
        //         'file_imports.fase_id as fase_id'
        //     )
        //     ->first();

        // $dokumen = ExcelData::with(['file_import' => function ($query) {
        //         $query->where('fase_id', 1)
        //             ->where('tahun', 2020);
        //     }])
        //     ->where('value', $id_dokumen)
        //     ->where('lokasi_id', $id_lokasi)
        //     ->where(function($q) {
        //         $q->where('kolom', 'AA')->orWhere('kolom', 'AB')->orWhere('kolom', 'Z');
        //     })
        //     ->first();
        $dokumen = DB::table('excel_datas')
            ->join('file_imports', 'file_imports.id', '=', 'excel_datas.file_import_id')
            ->join('templates', 'templates.id', '=', 'file_imports.template_id')
            ->where('file_imports.fase_id', 1)
            ->where('file_imports.tahun', $tahun)
            ->where('excel_datas.value', $id_dokumen)
            ->where('excel_datas.lokasi_id', $id_lokasi)
            ->where(function($q) {
                $q->where('excel_datas.kolom', 'AA')->orWhere('excel_datas.kolom', 'AB')->orWhere('excel_datas.kolom', 'Z');
            })
            ->first();
        // dd($dokumen);
        if ($dokumen) {
            
            $excel_data = ExcelData::where('file_import_id', $dokumen->file_import_id)
            ->where('sheet_id', $dokumen->sheet_id)
            ->where('lokasi_id', $dokumen->lokasi_id)
            ->where('row', $dokumen->row)
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
        }

        $data = array(
                'data_prk' => $data_prk,
                'data_anggaran' => $data_anggaran,
        );

        // dd($data);
        return json_encode($data);
    }

    public function create(Request $request)
    {
        // return view('kkp.tambah');
        if ($request->isMethod('get')) {
            $user = User::find(session('user_id'));
            $distrik_id = $user->distrik->id;
            $distrik = Distrik::find($distrik_id);
            $item ['lokasi'] = Lokasi::where("distrik_id", $distrik_id)->get();
            // $item ['dmr'] = Dmr::all();
            // $item ['dmrattachment'] = DmrAttachment::all();
            return view('kkp.tambah', $item,compact('distrik'));
        }

        elseif ($request->isMethod('post')) {
            // $files = $request->file('filepath');
            // dd($files);
            // dd(Input::all());
            $this->validate($request, [
                'tahun_anggaran_id'=>'required',
                'no_dokumen'=>'required',
                'judul_dokumen'=>'required',
                'dmr_filepath'=>'required:dmr',
                // 'no_prk'=>'required|unique:dmr',
                // 'nama_prk'=>'required|unique:dmr',
                'jumlah_anggaran'=>'required|numeric:dmr',
                'latar_belakang'=>'required',
                // 'sasaran_tujuan'=>'required',
                // 'permasalahan'=>'required',
                // 'alternatif_pencapaian'=>'required',
                // 'benefit_operasional'=>'required',
                // 'benefit_finansial'=>'required',
                ]);
            // dd(Input::get('judul_dokumen'));exit();
            $tahun_sekarang=date('Y');

            // $tahun_anggaran = $tahun_sekarang+1;
            $tahun_anggaran = Input::get('tahun_anggaran_id');
            $no_dokumen = Input::get('no_dokumen');
            $lokasi_id = Input::get('lokasi_id');
            $lokasi = Lokasi::find($lokasi_id);
            $time = date('Y-m-d h:i:s');
            
            $dmr_is_exist = DMR::where('no_dokumen', $no_dokumen)->where('tahun_anggaran', $tahun_anggaran)->first();

            if ($dmr_is_exist) {
                # code...
                return redirect()->back()->withInput()->with('msg', 'No Dokumen Tidak Boleh Duplikat');
            }
            // mendapatkan data file yang diupload:
            // $file = $request->file('dmr_filepath');
            // $destinationPath = "dmr";
            // $filename= $file->getClientOriginalName();
            // $request->file('dmr_filepath')->move($destinationPath, $filename);
            // $dmr_filepath = $destinationPath.'/'.$filename;
            $is_kantor_pusat = 0;
            $excel_data_pjb2 = NULL;

            $excel_data_id_dokumen = DB::table('excel_datas')
                                    ->join('file_imports', 'file_imports.id', '=', 'excel_datas.file_import_id')
                                    ->join('templates', 'templates.id', '=', 'file_imports.template_id')
                                    ->where('file_imports.fase_id', 1)
                                    ->where('file_imports.tahun', $tahun_anggaran)
                                    ->where('excel_datas.value', Input::get('no_dokumen'))
                                    ->where('excel_datas.lokasi_id', Input::get('lokasi_id'))
                                    ->first();
            // dd($excel_data_id_dokumen);

            if ($excel_data_id_dokumen) {
                if ($excel_data_id_dokumen->jenis_id == 2) {
                    $col_pjb2 = 'DA';
                } elseif ($excel_data_id_dokumen->jenis_id == 3) {
                    $col_pjb2 = 'DA';
                } elseif ($excel_data_id_dokumen->jenis_id == 4) {
                    $col_pjb2 = 'CB';
                } elseif ($excel_data_id_dokumen->jenis_id == 5) {
                    $col_pjb2 = 'BV';
                } elseif ($excel_data_id_dokumen->jenis_id == 6) {
                    $col_pjb2 = 'CC';
                } else {
                    $col_pjb2 = 'ZZ';
                }

            // $excel_data_id_dokumen = ExcelData::where('value', Input::get('no_dokumen'))
            //     ->where('lokasi_id', $lokasi_id)
            //     ->first();

            // if ($excel_data_id_dokumen) {
            //     if ($excel_data_id_dokumen->file_import->template->jenis_id == 2) {
            //         $col_pjb2 = 'DA';
            //     } elseif ($excel_data_id_dokumen->file_import->template->jenis_id == 3) {
            //         $col_pjb2 = 'DA';
            //     } elseif ($excel_data_id_dokumen->file_import->template->jenis_id == 4) {
            //         $col_pjb2 = 'CB';
            //     } elseif ($excel_data_id_dokumen->file_import->template->jenis_id == 5) {
            //         $col_pjb2 = 'BV';
            //     } elseif ($excel_data_id_dokumen->file_import->template->jenis_id == 6) {
            //         $col_pjb2 = 'CC';
            //     } else {
            //         $col_pjb2 = 'ZZ';
            //     }

                // dd($excel_data_id_dokumen->row);
                $excel_data_pjb2 = ExcelData::where('file_import_id', $excel_data_id_dokumen->file_import_id)
                    ->where('lokasi_id', $lokasi_id)
                    ->where('row', $excel_data_id_dokumen->row)
                    ->where('kolom', $col_pjb2)
                    ->first();
            }

            if ($excel_data_pjb2) {
                if (is_numeric((int)$excel_data_pjb2->value) AND (int)$excel_data_pjb2->value != 0) $is_kantor_pusat = 1;
            }
            // dd($excel_data_pjb2);
            // dd($is_kantor_pusat);
            $item = array(
                // 'tahun_anggaran' => $tahun_anggaran,
                // 'lokasi_id' => $lokasi_id,
                // hardcode sementara untuk demo
                'tahun_anggaran' => $tahun_anggaran,
                'lokasi_id' => $lokasi_id,
                'dmr_filepath' => '',
                'created_at' => date('Y-m-d h:i:s'),
                // 'filepath' => $filepath,
                // 'created_by' => Auth::id(),
                'created_by' => session('user_id'),
                // 'created_by' => session('user_id'),
                'no_dokumen' => Input::get('no_dokumen'),
                'no_prk_form' => Input::get('no_prk_form'),
                'anggaran_prk_form' => Input::get('anggaran_prk_form'),
                'jumlah_anggaran' => Input::get('jumlah_anggaran'),
                'latar_belakang' => Input::get('latar_belakang'),

                'sasaran_tujuan' => '0',
                'permasalahan' => '0',
                'alternatif_pencapaian' => '0',
                'benefit_finansial' => '0',
                'benefit_operasional' => '0',

                'is_submitted' => Input::get('is_submitted'),
                'is_kantor_pusat' => $is_kantor_pusat,
                'judul_dokumen' => Input::get('judul_dokumen'),
                'is_kkp' => 1,
                'anggaran_percluster' => Input::get('anggaran_percluster'),
                'jenis_cluster' => Input::get('jenis_cluster'),
                );
            // dd($item);

            if(Input::get('is_submitted') == 1){
                $nilaiai= Input::get('jumlah_anggaran');
                $kondisi= KondisiAICluster::where('nilai_min', '<', $nilaiai)
                ->where('nilai_max', '>=', $nilaiai)
                ->first();
                // dd($kondisi);

                $item['submitted_at'] = date('Y-m-d h:i:s');
                $item['submitted_by'] = session('user_id');
                // $item['dmr_review_status_id'] = 4;
                // $item['dmr_review_phase_id'] = 1;

                $item['status_appr_id'] = 2;
                $item['kondisi_aicluster_id'] = $kondisi->id;
            }
            else
            {
                $item['status_appr_id'] = 1;
            }
            // dd($item);

            $transaction = Dmr::create($item);
            // dd($transaction);

            //update dmr_filepath (tambah id)
            $file = $request->file('dmr_filepath');
            $destinationPath = "dmr/".$transaction->id;
            // $filename= $file->getClientOriginalName();
            $filename= preg_replace('/[^A-Za-z0-9-.\  ]/', '', $file->getClientOriginalName());
            $request->file('dmr_filepath')->move($destinationPath, $filename);
            $dmr_filepath = $destinationPath.'/'.$filename;
            $item = Dmr::find($transaction->id);
            $item->dmr_filepath = $dmr_filepath;
            $item->save();

            //lampiran
            $files = $request->file('filepath');

            // $files = $request->file('attachment');

            if($request->hasFile('filepath'))
            {
                $i = 1;
                foreach ($files as $file) {
                    // $file->store('users/' . $this->user->id . '/messages');

                    $destinationPath = "dmr/".$transaction->id;
                    // $filename = $file->getClientOriginalName();
                    $filename= preg_replace('/[^A-Za-z0-9-.\  ]/', '', $file->getClientOriginalName());
                    $file->move($destinationPath.'/'.$transaction->id.'/', $filename);
                    $filepath[$i] = $destinationPath.'/'.$transaction->id.'/'.$filename;
                    $i++;
                }

            }
            for($i=1;$i<=10;$i++) {
                if(isset($filepath[$i])) {
                    $save_filepath = $filepath[$i];
                }
                else {
                    $save_filepath = '';
                }

                $item_attachment = array(
                            'dmr_id' => $transaction->id,
                            'filepath' => $save_filepath,
                            );
                $transaction_attahment = DmrAttachment::create($item_attachment);
            }

            if ($transaction)
            {
                $request->session()->flash('success','Data berhasil ditambah');
            }

            return redirect('kkp/daftar?tahun_anggaran='.$tahun_anggaran.'&strategi_bisnis='.$lokasi->distrik->strategi_bisnis_id.'&distrik='.$lokasi->distrik_id.'&lokasi='.$lokasi_id);
        }
    }

    public function update(Request $request,$id)
    {
        // return view('kkp.tambah');

        if ($request->isMethod('get')) {

            $dmr = Dmr::find($id);

            if ($dmr == NULL) {
                Session::flash('fail', 'DMR Tidak Ditemukan');
                return redirect('kkp/daftar');
            }

            $dmr_review = DmrReview::where('dmr_id', $dmr->id)->orderBy('id', 'desc')->first();
            // dd($dmr_review);
            $lokasi = Lokasi::find($dmr->lokasi_id);
            $dmrattachment = DmrAttachment::where('dmr_id', $id)->orderBy('id')->get();
            // dd($item ['dmrattachment']);
            return view('kkp.edit', compact('dmr', 'dmr_review', 'lokasi', 'dmrattachment'));
        }

        elseif ($request->isMethod('post')) {
            // dd(Input::all());
            $this->validate($request, [
                'no_dokumen'=>'required',
                // 'judul_dokumen'=>'required',
                // 'no_prk'=>'required',
                // 'nama_prk'=>'required',
                // 'jumlah_anggaran'=>'required|numeric:dmr',
                ]);
            $dmr_attachment_id = Input::get('dmr_attachment_id');

            //menghapus attachment yg dicentang
            $delete_attachments = Input::get('delete_attachments');
            if($delete_attachments != null){
                foreach ($delete_attachments as $key => $value) {
                    $update_attachment = array('updated_at' => date('Y-m-d H:i:s'),
                        'filepath' => '',
                        );
                    $delete = DmrAttachment::where('id', $value)->update($update_attachment);
                }
            }

            // mendapatkan data file yang diupload:
            if($request->file('dmr_filepath')){
                $file = $request->file('dmr_filepath');
                $destinationPath = "dmr/".$id;
                // $filename= $file->getClientOriginalName();
                $filename= preg_replace('/[^A-Za-z0-9-.\  ]/', '', $file->getClientOriginalName());
                $request->file('dmr_filepath')->move($destinationPath, $filename);
                $dmr_filepath = $destinationPath.'/'.$filename;
            }
            if($request->file('filepath'))
            {
                    //lampiran
                // $file = $request->file('filepath');
                // $destinationPath = "dmr";
                // $filename = $file->getClientOriginalName();
                // $request->file('filepath')->move($destinationPath, $filename);
                // $filepath = $destinationPath.'/'.$filename;

            // $transaction = Dmr::update();
            $filepath = array();
            $files = $request->file('filepath');
                $i = 1;
                foreach ($files as $key => $file) {
                    // $file->store('users/' . $this->user->id . '/messages');

                    $destinationPath = "dmr/".$id;
                    // $filename = $file->getClientOriginalName();
                    $filename= preg_replace('/[^A-Za-z0-9-.\  ]/', '', $file->getClientOriginalName());
                    $file->move($destinationPath.'/'.$id.'/', $filename);
                    $filepath[$i] = $destinationPath.'/'.$id.'/'.$filename;
                    $dmr_attachment_id_update[$i] = $dmr_attachment_id[$key];
                    $i++;
                }

            //dd($filepath);
                for($i=1;$i<=10;$i++) {
                    if(isset($filepath[$i])) {
                        $save_filepath = $filepath[$i];

                    // else {
                    //     $save_filepath = '';
                    // }

                    $idd = array(
                                // 'dmr_id' => $id,
                                'filepath' => $save_filepath,
                                );
                    // dd($filepath[$i]);
                    $transaction_attahment = DmrAttachment::where('id', $dmr_attachment_id_update[$i])->update($idd);
                    //->update($idd);
                  }
                }
            }

            $item = Dmr::find($id);

            if($request->file('dmr_filepath')){
                $item->dmr_filepath = $dmr_filepath;
            }

            $item->judul_dokumen = Input::get('judul_dokumen');
            $item->jumlah_anggaran = Input::get('jumlah_anggaran');
            $item->jenis_cluster = Input::get('jenis_cluster');
            $item->anggaran_percluster = Input::get('anggaran_percluster');
            $item->latar_belakang = Input::get('latar_belakang');
            $item->sasaran_tujuan = '0';
            $item->permasalahan = '0';
            $item->alternatif_pencapaian = '0';
            $item->benefit_finansial = '0';
            $item->benefit_operasional = '0';

            $time = date('Y-m-d h:i:s');
            $item->updated_at = date('Y-m-d h:i:s');
            $item->updated_by = session('user_id');

            if(Input::get('is_submitted') == 1){
                $nilaiai= Input::get('jumlah_anggaran');
                $kondisi= KondisiAICluster::where('nilai_min', '<', $nilaiai)
                ->where('nilai_max', '>=', $nilaiai)
                ->first();
                // dd($kondisi->id);

                if ($item->is_submitted == 0) {
                    $item->submitted_at = date('Y-m-d h:i:s');
                    $item->submitted_by = session('user_id');
                    $item->is_submitted = Input::get('is_submitted');
                }
                // $item->dmr_review_phase_id = 1;
                // $item->dmr_review_status_id = 4;

                $item->status_appr_id = 2;
                $item->kondisi_aicluster_id = $kondisi->id;
            }
            else
            {
                $item->status_appr_id = 1;
            }

            $item->save();

            $item = Dmr::find($id);
            if(Input::get('is_submitted') == 1){
                $new_dmr_review = new DmrReview;
                $new_dmr_review->dmr_id = $item->id;
                $new_dmr_review->dmr_review_phase_id = $item->dmr_review_phase_id;
                $new_dmr_review->dmr_review_status_id = $item->dmr_review_status_id;
                $new_dmr_review->alasan = $item->alasan;
                $new_dmr_review->alasan_latar_belakang = $item->alasan_latar_belakang;
                $new_dmr_review->alasan_sasaran_tujuan = $item->alasan_sasaran_tujuan;
                $new_dmr_review->alasan_permasalahan = $item->alasan_permasalahan;
                $new_dmr_review->alasan_alternatif_pencapaian = $item->alasan_alternatif_pencapaian;
                $new_dmr_review->alasan_benefit_operasional = $item->alasan_benefit_operasional;
                $new_dmr_review->alasan_benefit_finansial = $item->alasan_benefit_finansial;
                $new_dmr_review->is_new = 1;
                $new_dmr_review->created_by = session('user_id');
                $new_dmr_review->save();
            }

            $request->session()->flash('success','Data berhasil diupdate');
            return redirect('kkp/daftar?tahun_anggaran='.$item->tahun_anggaran.'&strategi_bisnis='.$item->lokasi->distrik->strategi_bisnis_id.'&distrik='.$item->lokasi->distrik_id.'&lokasi='.$item->lokasi_id);
        }
    }

    public function detail(Request $request,$id)
    {
        $fungsi= 'detail';
        // return view('kkp.tambah');
        if ($request->isMethod('get')) {
            $role_id = session('role_id');
            $dmr = Dmr::find($id);

            if ($dmr == NULL) {
                Session::flash('fail', 'DMR Tidak Ditemukan');
                return redirect('kkp/daftar');
            }

            $tor_dmr = Tor::where('no_dokumen_dmr', $dmr->no_dokumen)->first();
            if ($tor_dmr) {
                $tor_attachments = TorAttachment::where('tor_id', $tor_dmr->id)->where('for_review', 0)->get();
            } else {
                $tor_attachments = [];
            }

            $dmrattachment = DmrAttachment::where('dmr_id', $id)->get();

            $array_status_dmr = [DMR_STATUS_REVISED, DMR_STATUS_REJECTED];
            $dmr_reviews = DmrReview::where('dmr_id', $dmr->id)
                ->whereIn('dmr_review_status_id', $array_status_dmr)
                ->orderBy('id', 'desc')
                ->get();
            $input_dmr_review = '';

            $dat_approval = DB::table('approval_kkp')
            ->select('approval_kkp.grupdiv_id', 'grup_divpembinaunit.name as grupdiv_name', 'approval_kkp.peran', 'approval_kkp.urutan', 'approval_kkp.status', 'approval_kkp.kkp_id')
            ->join('grup_divpembinaunit', 'grup_divpembinaunit.id', '=', 'approval_kkp.grupdiv_id')
            ->where('approval_kkp.kkp_id', $id)
            ->orderBy('urutan', 'asc')
            ->get();

            // $dat_approval= null;
            $disabled= 'disabled';
            $grupdiv = GroupDivisiPembinaUnit::get();
            return view('kkp.detail', compact('role_id', 'dmr', 'dmrattachment', 'dmr_reviews', 'input_dmr_review', 'tor_attachments', 'dat_approval', 'grupdiv', 'disabled', 'fungsi'));
        }

        elseif ($request->isMethod('post')) {

            $role_id = session('role_id');
            $dmr = Dmr::find($id);            

            $dmr_review= $dmrattachment= $dmr_reviews= null; $tor_attachments= [];
            $input_dmr_review = Input::get('dmr_review_id');
            if ($input_dmr_review) 
            {
                $dmr_review = DmrReview::findOrFail($input_dmr_review);

                if ($dmr == NULL) {
                    Session::flash('fail', 'DMR Tidak Ditemukan');
                    return redirect('kkp/daftar');
                }

                $tor_dmr = Tor::where('no_dokumen', $dmr->no_dokumen)->first();
                if ($tor_dmr) {
                    $tor_attachments = TorAttachment::where('tor_id', $tor_dmr->id)->where('for_review', 0)->get();
                } else {
                    $tor_attachments = [];
                }

                $dmrattachment = DmrAttachment::where('dmr_id', $id)->get();

                $array_status_dmr = [DMR_STATUS_REVISED, DMR_STATUS_REJECTED];
                $dmr_reviews = DmrReview::where('dmr_id', $dmr->id)
                    ->whereIn('dmr_review_status_id', $array_status_dmr)
                    ->orderBy('id', 'desc')
                    ->get();
            }
            

            $dat_approval= null;
            $disabled= 'disabled';
            $grupdiv = GroupDivisiPembinaUnit::get();
            return view('kkp.detail', compact('role_id', 'dmr', 'dmrattachment', 'dmr_reviews', 'input_dmr_review', 'dmr_review', 'tor_attachments', 'dat_approval', 'grupdiv', 'disabled', 'fungsi'));
        }
    }

    public function setappr(Request $request,$id)
    {
        $fungsi = 'setappr';
        // return view('kkp.tambah');
        if ($request->isMethod('get')) {
            $role_id = session('role_id');
            $dmr = Dmr::find($id);

            if ($dmr == NULL) {
                Session::flash('fail', 'DMR Tidak Ditemukan');
                return redirect('kkp/daftar');
            }

            $disabled= '';
            if ($dmr->status_appr_id!=2) 
            {
                $disabled= 'disabled';
            }

            $dmr_review= $dmrattachment= $dmr_reviews= null; $tor_attachments= []; $input_dmr_review = '';

            // $tor_dmr = Tor::where('no_dokumen_dmr', $dmr->no_dokumen)->first();
            // if ($tor_dmr) {
            //     $tor_attachments = TorAttachment::where('tor_id', $tor_dmr->id)->where('for_review', 0)->get();
            // } else {
            //     $tor_attachments = [];
            // }

            // $dmrattachment = DmrAttachment::where('dmr_id', $id)->get();

            // $array_status_dmr = [DMR_STATUS_REVISED, DMR_STATUS_REJECTED];
            // $dmr_reviews = DmrReview::where('dmr_id', $dmr->id)
            //     ->whereIn('dmr_review_status_id', $array_status_dmr)
            //     ->orderBy('id', 'desc')
            //     ->get();
            // $input_dmr_review = '';

            $kondisi= $dmr->kondisi_aicluster_id;
            $dat_approval= $dat_approval2= null;
            if ($kondisi=='1' OR $kondisi=='') 
            {
                $dat_approval = DB::table('approval_kkp')
                ->select('approval_kkp.grupdiv_id', 'grup_divpembinaunit.name as grupdiv_name', 'approval_kkp.peran', 'approval_kkp.urutan', 'approval_kkp.status', 'approval_kkp.kkp_id', 'approval_kkp.inputke')
                ->join('grup_divpembinaunit', 'grup_divpembinaunit.id', '=', 'approval_kkp.grupdiv_id')
                ->where('approval_kkp.kkp_id', $id)
                ->orderBy('urutan', 'asc')
                ->get();

                $dat_approval2= null;
            }
            else
            {
                $dat_approval = DB::table('approval_kkp')
                ->select('approval_kkp.grupdiv_id', 'grup_divpembinaunit.name as grupdiv_name', 'approval_kkp.peran', 'approval_kkp.urutan', 'approval_kkp.status', 'approval_kkp.kkp_id', 'approval_kkp.inputke')
                ->join('grup_divpembinaunit', 'grup_divpembinaunit.id', '=', 'approval_kkp.grupdiv_id')
                ->where('approval_kkp.kkp_id', $id)
                ->orderBy('urutan', 'asc')
                ->where('approval_kkp.inputke', '1')
                ->get();

                $dat_approval2= DB::table('approval_kkp')
                ->select('approval_kkp.grupdiv_id', 'grup_divpembinaunit.name as grupdiv_name', 'approval_kkp.peran', 'approval_kkp.urutan', 'approval_kkp.status', 'approval_kkp.kkp_id', 'approval_kkp.inputke')
                ->join('grup_divpembinaunit', 'grup_divpembinaunit.id', '=', 'approval_kkp.grupdiv_id')
                ->where('approval_kkp.kkp_id', $id)
                ->orderBy('urutan', 'asc')
                ->where('approval_kkp.inputke', '2')
                ->get();
            }
            

            // $dat_approval= null;
            $grupdiv = GroupDivisiPembinaUnit::get();
            return view('kkp.detail', compact('role_id', 'dmr', 'dmrattachment', 'dmr_reviews', 'input_dmr_review', 'tor_attachments', 'dat_approval', 'dat_approval2', 'grupdiv', 'disabled', 'fungsi'));
        }

        elseif ($request->isMethod('post')) {

            $dat_appr_inputke = $request->data_approval_inputke;
            $dat_appr_grup = $request->data_approval_grup;
            $dat_appr_urut = $request->data_approval_urut;
            $dat_appr_peran = $request->data_approval_peran;

            $inputke='';
            if ($dat_appr_inputke) 
            {
                $inputke= $dat_appr_inputke[0];
            }

            if ($inputke) 
            {
                DB::table('approval_kkp')->where('kkp_id', $id)->where('inputke', $inputke)->delete();
            }
            else
            {
                 DB::table('approval_kkp')->where('kkp_id', $id)->delete();
            }

            
            if (count($dat_appr_grup) > 0) {
                for ($i=0; $i < count($dat_appr_grup); $i++) 
                {
                    $inputkee= $dat_appr_inputke[$i];
                    $grupdiv_id= $dat_appr_grup[$i];
                    $urut= $dat_appr_urut[$i];
                    $peran= $dat_appr_peran[$i];

                    DB::table('approval_kkp')->insert(
                        ['kkp_id' => $id, 'grupdiv_id' => $grupdiv_id, 'peran' => $peran, 'urutan' => $urut, 'inputke' => $inputkee, 'status' => '0', 'created_at' => date('Y-m-d h:i:s')]
                    );
                }
            }

            if(Input::get('is_submitted') == 1)
            {
                $item = Dmr::find($id);
                $item->status_appr_id = 6;
                $item->save();
            }      
            // else
            // {
            //     $item = Dmr::find($id);
            //     $item->status_appr_id = 2;
            //     $item->save();
            // }      

            $role_id = session('role_id');
            $dmr = Dmr::find($id);

            

            $dmr_review= $dmrattachment= $dmr_reviews= null; $tor_attachments= [];
            $input_dmr_review = Input::get('dmr_review_id');
            // if ($input_dmr_review) 
            // {
            //     $dmr_review = DmrReview::findOrFail($input_dmr_review);

            //     if ($dmr == NULL) {
            //         Session::flash('fail', 'DMR Tidak Ditemukan');
            //         return redirect('kkp/daftar');
            //     }

            //     $tor_dmr = Tor::where('no_dokumen', $dmr->no_dokumen)->first();
            //     if ($tor_dmr) {
            //         $tor_attachments = TorAttachment::where('tor_id', $tor_dmr->id)->where('for_review', 0)->get();
            //     } else {
            //         $tor_attachments = [];
            //     }

            //     $dmrattachment = DmrAttachment::where('dmr_id', $id)->get();

            //     $array_status_dmr = [DMR_STATUS_REVISED, DMR_STATUS_REJECTED];
            //     $dmr_reviews = DmrReview::where('dmr_id', $dmr->id)
            //         ->whereIn('dmr_review_status_id', $array_status_dmr)
            //         ->orderBy('id', 'desc')
            //         ->get();
            // }

            $disabled= '';
            if ($dmr->status_appr_id!=2) 
            {
                $disabled= 'disabled';
            }
            
            $kondisi= $dmr->kondisi_aicluster_id;
            $dat_approval= $dat_approval2= null;
            if ($kondisi=='1' OR $kondisi=='') 
            {
                $dat_approval = DB::table('approval_kkp')
                ->select('approval_kkp.grupdiv_id', 'grup_divpembinaunit.name as grupdiv_name', 'approval_kkp.peran', 'approval_kkp.urutan', 'approval_kkp.status', 'approval_kkp.kkp_id', 'approval_kkp.inputke')
                ->join('grup_divpembinaunit', 'grup_divpembinaunit.id', '=', 'approval_kkp.grupdiv_id')
                ->where('approval_kkp.kkp_id', $id)
                ->orderBy('urutan', 'asc')
                ->get();

                $dat_approval2= null;
            }
            else
            {
                $dat_approval = DB::table('approval_kkp')
                ->select('approval_kkp.grupdiv_id', 'grup_divpembinaunit.name as grupdiv_name', 'approval_kkp.peran', 'approval_kkp.urutan', 'approval_kkp.status', 'approval_kkp.kkp_id', 'approval_kkp.inputke')
                ->join('grup_divpembinaunit', 'grup_divpembinaunit.id', '=', 'approval_kkp.grupdiv_id')
                ->where('approval_kkp.kkp_id', $id)
                ->orderBy('urutan', 'asc')
                ->where('approval_kkp.inputke', '1')
                ->get();

                $dat_approval2= DB::table('approval_kkp')
                ->select('approval_kkp.grupdiv_id', 'grup_divpembinaunit.name as grupdiv_name', 'approval_kkp.peran', 'approval_kkp.urutan', 'approval_kkp.status', 'approval_kkp.kkp_id', 'approval_kkp.inputke')
                ->join('grup_divpembinaunit', 'grup_divpembinaunit.id', '=', 'approval_kkp.grupdiv_id')
                ->where('approval_kkp.kkp_id', $id)
                ->orderBy('urutan', 'asc')
                ->where('approval_kkp.inputke', '2')
                ->get();
            }

            // $dat_approval= null;
            $grupdiv = GroupDivisiPembinaUnit::get();
            return view('kkp.detail', compact('role_id', 'dmr', 'dmrattachment', 'dmr_reviews', 'input_dmr_review', 'dmr_review', 'tor_attachments', 'dat_approval', 'dat_approval2', 'grupdiv', 'disabled', 'fungsi'));
        }
    }

    public function delete(Request $request,$id)
    {
        $item = Dmr::find($id);
        if($item!= null){
            $return_path = '?tahun_anggaran='.$item->tahun_anggaran.'&strategi_bisnis='.$item->lokasi->distrik->strategi_bisnis_id.'&distrik='.$item->lokasi->distrik_id.'&lokasi='.$item->lokasi_id;

            // DMR boleh dihapus jika belum disubmit
            if($item->is_submitted == 0){
                // hapus file
                array_map('unlink', glob("dmr/".$id."/*.*"));

                // hapus attachments
                rmdir("dmr/".$id);

                // hapus dmr dari db
                $item->delete();

                $request->session()->flash('success','Data berhasil dihapus');
            }
            return redirect('kkp/daftar'.$return_path);
        }
        return redirect('kkp/daftar');
    }

    public function download_attachment(Request $request, $id)
    {   
        $dmr = Dmr::find($id);
        if ( !is_null($dmr) ) {
            if (file_exists($dmr->dmr_filepath)) {
            
                $dmr_filepath = preg_replace('/[^A-Za-z0-9-.\  ]/', '',(basename($dmr->dmr_filepath)));

                return Response::download($dmr->dmr_filepath, basename($dmr_filepath));
            }
            
            return redirect()->back()->with('msg', 'Dokumen Tidak Ditemukan');
        }
    }

    public function dmr_attachment(Request $request, $id)
    {   
        $dmr = DmrAttachment::find($id);
        if ( !is_null($dmr) ) {
            if (file_exists($dmr->filepath)) {
            
                $dmr_filepath = preg_replace('/[^A-Za-z0-9-.\  ]/', '',(basename($dmr->filepath)));

                return Response::download($dmr->filepath, basename($dmr_filepath));
            }
            
            return redirect()->back()->with('msg', 'Dokumen Tidak Ditemukan');
        }
    }

    public function review_attachment(Request $request, $id)
    {   
        $dmr = DmrReviewAttachment::find($id);
        if ( !is_null($dmr) ) {
            if (file_exists($dmr->filepath)) {
            
                $dmr_filepath = preg_replace('/[^A-Za-z0-9-.\  ]/', '',(basename($dmr->filepath)));

                return Response::download($dmr->filepath, basename($dmr_filepath));
            }
            
            return redirect()->back()->with('msg', 'Dokumen Tidak Ditemukan');
        }
    }
}
