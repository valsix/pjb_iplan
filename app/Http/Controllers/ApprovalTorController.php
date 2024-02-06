<?php

namespace App\Http\Controllers;

// use Request;
use DB;
use Mail;
use URL;
use App\Entities\Distrik;
use App\Entities\Lokasi;
use App\Entities\StrategiBisnis;
use App\Entities\Tor;
use App\Entities\TorAttachment;
use App\Entities\TorReviewPhase;
use App\Entities\TorReviewStatus;
use App\Entities\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class ApprovalTorController extends Controller
{
    public function index()
    {
        $user_id = session('user_id');
        $role_id = session('role_id');
        $tor_review_phase = TorReviewPhase::where('role_id', $role_id)->first();

        $input_tahun = Input::get('tahun_anggaran');
        $input_sb = Input::get('strategi_bisnis');
        $input_distrik = Input::get('distrik');
        $input_lokasi = Input::get('lokasi');

        if (! $input_tahun) {
            $input_tahun = date('Y');
        }

        //jika staff unit, redirect ke daftar tor
        if($role_id == ROLE_ID_SPV_UNIT || $role_id == ROLE_ID_ADM)
            return redirect('tor/daftar');
        //jika tidak memiliki hak approval, kembali ke home
        if($tor_review_phase == null)
            return redirect('');

        if($role_id == ROLE_ID_STAFF || $role_id == ROLE_ID_KABID){
            $Sb = StrategiBisnis::all();
            if($input_sb!= null)
                $distrik = Distrik::where('strategi_bisnis_id',$input_sb)->get();
        }
        else{
            $user = User::find($user_id);
            $Sb = StrategiBisnis::where('id',$user->distrik->strategi_bisnis_id)->get();
            $input_sb = $user->distrik->strategi_bisnis_id;
            $input_distrik = $user->distrik_id;
            $distrik = Distrik::where('id',$input_distrik)->get();
        }

        $approval_tor = null;

        if($input_distrik!= null)
            $lokasi = Lokasi::where('distrik_id',$input_distrik)->get();

        if($input_lokasi != null){
            $next_review_phase = TorReviewPhase::select('id')->where('urutan','>=',$tor_review_phase->urutan)->get();
            $id_next_review_phase = array();
            foreach ($next_review_phase as $key => $value) {
                array_push($id_next_review_phase, $value->id);
            }

            // $approval_tor = Tor::where('lokasi_id',$input_lokasi)
            //     ->where('is_submitted',1)
            //     ->where('manager_user_id', $user_id)
            //     ->where('tahun_anggaran', $input_tahun)->get();

            $approval_tor = Tor::with('dmr')->where('lokasi_id',$input_lokasi)
                ->where('is_submitted',1)
                ->where('tahun_anggaran', $input_tahun)
                ->whereIn('tor_review_phase_id',$id_next_review_phase)
                ->orderBy('tor_review_phase_id', 'asc')
                ->orderBy('tor_review_status_id', 'desc')
                ->orderBy('created_at', 'desc')->get();
        }
        // dd($approval_tor);
        // dd($approval_tor[0]->tor_review_phase);
        // dd($approval_tor[0]->tor_review_status);
        // dd($tor_review_phase->role_id);

        return view('approval_tor.daftar_approval_tor', compact('Sb', 'input_tahun', 'input_sb', 'input_distrik', 'input_lokasi', 'distrik', 'lokasi', 'approval_tor', 'tor_review_phase'));
    }

    public function Ajax($id)
    {
        $role_id = session('role_id');
        if (in_array($role_id, [ROLE_ID_SPV_TOR, ROLE_ID_MGR_TOR, ROLE_ID_GM])) {
            $user = User::find( session('user_id') );
            $ds = Distrik::where('id', $user->distrik_id)->where('strategi_bisnis_id', $id)->select("name","id")->get();
        } else {
            $ds = Distrik::where('strategi_bisnis_id', $id)->select("name","id")->get();
        }

        return json_encode($ds);
    }

    public function myformAjax2($id)
      {
        $lokasi = Lokasi::where('distrik_id', $id)->select("name", "id")->get();

        return json_encode($lokasi);
      }

    public function approval(Request $request, $id)
    {
        $user_id = session('user_id');
		//dd($request);
        if ($request->isMethod("get")) {
			
			//echo "<h1>METHOD GET</h1>";
			//dd($request);
			
            $user = User::find($user_id);
            $distrik_id = $user->distrik->id;
            $role_id = $user->current_id_roles;

            $item['tor'] = $tor = Tor::find($id);
            $item['current_tor_review_phase'] = TorReviewPhase::where('role_id',session('role_id'))->first();

            // redirect jika role_id tidak sesuai dengan phase pada tor
            if ($tor->tor_review_phase->role->id != $role_id || $tor->tor_review_status->id != TOR_STATUS_QUEUE) {
                return redirect(URL::previous());
            }

            // TODO: redirect jika lokasi tidak sesuai jika reviewer di bawah staff kantor pusat

            $item['tor_review_status'] = TorReviewStatus::all();
            // $item['tor_attachment'] = TorAttachment::all();
            $item['tor_attachment'] = TorAttachment::where('tor_id', $id)
                ->where('for_review', 0)
                ->orderBy('id', 'asc')->get();
            $item['tor_review_attachment'] = TorAttachment::where('tor_id', $id)
                ->where('for_review', 1)
                ->orderBy('id', 'asc')->get();
            // handle jika review attachment review masih kosong
            if (count($item['tor_review_attachment']) == 0) {
                for ($i=0; $i<5; $i++) {
                    $tra = new TorAttachment();
                    $tra->filepath = '';
                    $tra->tor_id = $id;
                    $tra->for_review = 1;
                    $tra->save();
                }
                $item['tor_review_attachment'] = TorAttachment::where('tor_id', $id)
                    ->where('for_review', 1)
                    ->orderBy('id', 'asc')->get();
            }

			//dd($item);

            // Tampilkan pilihan reviewer jika pada phase < 4
            // $item['reviewer'] = false;
            // if ($tor->tor_review_phase->urutan < 4) {
            //     $reviewer =  User::with('distrik')
            //         ->where('current_id_roles', ROLE_REVIEW_CHAIN[$role_id]) // TODO: Ganti pake data dari DB dengan model TorReviewPhase
            //         ->orderBy('distrik_id', 'asc');

            //     // Mulai pada phase-2 (GM) maka tidak perlu pake filter berdasarkan distrik
            //     if ($tor->tor_review_phase->urutan < 2) {
            //         $reviewer = $reviewer->where("distrik_id", $distrik_id);
            //     }

            //     $item['reviewer'] = $reviewer->get();
            // }

            return view('approval_tor.detail_approval_tor', $item);
        }
        elseif ($request->isMethod('post')) {
			
			echo "<h1>METHOD POST</h1>";
			
            // dd(Input::get('tor_review_status_id'));
            $tor_review_status_id = Input::get('tor_review_status_id');
            $item = Tor::find($id);
			//dd($item);

            // rule validator
            $validate_rule = [];
            $validate_msg = [];
            // if (Input::get('tor_review_status_id') == TOR_STATUS_APPROVED && $item->tor_review_phase->urutan < 4) {
            //     $validate_rule['manager_user_id'] = 'required';
            //     $validate_msg['manager_user_id.required'] = 'Reviewer wajib diisi';
            // }

            if($tor_review_status_id == TOR_STATUS_REJECTED || $tor_review_status_id == TOR_STATUS_REVISED) {
                $validate_rule['alasan'] = 'required';
                $validate_msg['alasan.required'] = 'Maaf, Alasan Umum wajib diisi.';
            }

            $this->validate($request, $validate_rule, $validate_msg);

            $review_data = [];

            $review_data['alasan'] = Input::get('alasan');
            $review_data['alasan_aspek_keamanan_k3'] = Input::get('alasan_aspek_keamanan_k3');
            $review_data['alasan_data_teknis'] = Input::get('alasan_data_teknis');
            $review_data['alasan_delivery'] = Input::get('alasan_delivery');
            $review_data['alasan_detail_pelaksanaan_pekerjaan'] = Input::get('alasan_detail_pelaksanaan_pekerjaan');
            $review_data['alasan_garansi'] = Input::get('alasan_garansi');
            $review_data['alasan_kelengkapan_pelaksanaan_pekerjaan'] = Input::get('alasan_kelengkapan_pelaksanaan_pekerjaan');
            $review_data['alasan_kualifikasi_calon_pelaksanaan_pekerjaan'] = Input::get('alasan_kualifikasi_calon_pelaksanaan_pekerjaan');
            $review_data['alasan_lain_lain'] = Input::get('alasan_lain_lain');
            $review_data['alasan_laporan_hasil_pekerjaan'] = Input::get('alasan_laporan_hasil_pekerjaan');
            $review_data['alasan_lingkup_pekerjaan'] = Input::get('alasan_lingkup_pekerjaan');
            $review_data['alasan_material_sisa_limbah'] = Input::get('alasan_material_sisa_limbah');
            $review_data['alasan_pendahuluan'] = Input::get('alasan_pendahuluan');
            $review_data['alasan_performance_desain'] = Input::get('alasan_performance_desain');
            $review_data['alasan_quality_acceptance'] = Input::get('alasan_quality_acceptance');

            $today = date("Y-m-d H:i:s");
            
            if (Input::get('is_submit_review') == 1) {
                $review_data['tor_review_status_id'] = Input::get('tor_review_status_id');
                
                if (Input::get('tor_review_status_id') == TOR_STATUS_APPROVED) {
                    $review_data['approved_at'] = $today;
                    $review_data['approved_by'] = $user_id;

                    //hapus riwayat sebelumnya
                    $review_data['revised_at'] = null;
                    $review_data['revised_by'] = null;
                    $review_data['rejected_at'] = null;
                    $review_data['rejected_by'] = null;

                    //masuk queue ke fase berikutnya
                    $max_urutan = TorReviewPhase::max('urutan');
                    $urutan = $item->tor_review_phase->urutan;
                    $dmr = $item->dmr;
                    if ( is_null($dmr) ) {
                        $jumlah_anggaran = '0';
                    } else {
                        $jumlah_anggaran = $dmr->jumlah_anggaran;
                    }
                    
                    //dd($max_urutan);
                    
					//20190814 - recoding terkait masalah approval TOR dgn anggaran 3M langsung ke publish
                    if ($urutan === 4) {
                        $review_data['is_published'] = 1;
                    } elseif ( $urutan == 2 && $jumlah_anggaran == 0 ) {
                        return redirect()->back()->with('message', 'Anggaran PRK DMR berisi nol/belum diisi');
                    } elseif ( $urutan == 2 && $jumlah_anggaran < MINIMUM_ANGGARAN ){
                        $review_data['is_published'] = 1;
                    } elseif ( $urutan < $max_urutan){
                        $review_data['tor_review_status_id'] = TOR_STATUS_QUEUE;
                        $tor_review_phase = TorReviewPhase::where('urutan', $urutan+1)->first(); // TODO: ganti pake method model nextPhaseId($counter)
                        $review_data['tor_review_phase_id'] = $tor_review_phase->id;
                    }

                }
                else if (Input::get('tor_review_status_id') == TOR_STATUS_REVISED) {
                   $review_data['revised_at'] = $today;
                   $review_data['revised_by'] = $user_id;
                }
                else if (Input::get('tor_review_status_id') == TOR_STATUS_REJECTED) {
                   $review_data['rejected_at'] = $today;
                   $review_data['rejected_by'] = $user_id;
                }
            }

            // Mulai transaksi DB
            DB::beginTransaction();

            $item->update($review_data);


            // menghapus lampiran attachment yg dicentang
            $delete_review_attachments_id = Input::get('delete_review_attachments_id');
            if($delete_review_attachments_id != null){
                foreach ($delete_review_attachments_id as $key => $value) {
                    $update_attachment = array(
                        'updated_at' => date('Y-m-d H:i:s'),
                        'filepath' => '',
                        'for_review' => 1,
                    );
                    $delete = TorAttachment::where('id', $value)->update($update_attachment);
                }
            }

            // menyimpan lampiran review
            $review_attachment_id = Input::get('review_attachment_id');
            $review_filepath = array();
            $destinationPath = "tor_review";
            $review_attachment_update = array();
            if($request->file('review_filepath')) {
                $files = $request->file('review_filepath');
                $i = 1;
                foreach ($files as $key => $file) {
                    // $filename = $file->getClientOriginalName();
                    $filename= preg_replace('/[^A-Za-z0-9-.\  ]/', '', $file->getClientOriginalName());
                    $file->move($destinationPath.'/'.$id.'/', $filename);
                    $review_filepath[$i] = $destinationPath.'/'.$id.'/'.$filename;
                    $review_attachment_update[$i] = $review_attachment_id[$key];
                    $i++;
                }

                // dd($review_filepath);
                for($i=1; $i<=5; $i++) {
                    if(isset($review_filepath[$i])) {
                        $idd = array(
                            'filepath' => $review_filepath[$i],
                        );

                        // dd($review_filepath[$i]);
                        $transaction_attahment = TorAttachment::where('id', $review_attachment_update[$i])->update($idd);
                    }
                }
            }

            if (Input::get('is_submit_review') == 1) {
                $review_data['review_attachment'] = TorAttachment::where('tor_id', $id)->where('for_review', 1)->get()->toArray();
                $review_data['review_role_name'] = $item->tor_review_phase->role->name;
                $review_data['reviewed_at'] = $today;
                if (Input::get('tor_review_status_id') == TOR_STATUS_APPROVED) {
                    $review_data['review_status_name'] = TorReviewStatus::find(TOR_STATUS_APPROVED)->name;
                } else {
                    $review_data['review_status_name'] = $item->tor_review_status->name;
                }

                // Simpan history review sebagai string json
                $review_list = json_decode($item->review_list, true) ?: [];
                if (! is_array($review_list) ) {
                    $review_list = [];
                }
                $review_data['id'] = count($review_list);
                // $review_list[] = $review_data; // append review baru
                array_unshift($review_list, $review_data); // prepend review baru

                $item->review_list = json_encode($review_list);
                $item->save();
                // dd('review_list', $review_list);
            }

            // dd('review_data', $review_data);

            // Komit transaksi DB
            DB::commit();

            $request->session()->flash('success','Approval TOR berhasil diubah');
            return redirect('approval_tor/daftar?tahun_anggaran='.$item->tahun_anggaran.'&strategi_bisnis='.$item->lokasi->distrik->strategi_bisnis_id.'&distrik='.$item->lokasi->distrik_id.'&lokasi='.$item->lokasi_id);
        }
    }


    // public function detail($id)
    // {
    //     //
    //     if ($request->isMethod("get")) {

    //         $item['tor_review_status'] = TorReviewStatus::all();
    //         // $item['tor_attachment'] = TorAttachment::all();
    //         $item['tor'] = Tor::find($id);
    //         $item['tor_attachment'] = TorAttachment::where('tor_id', $id)->get();
    //         dd($item['tor_attachment']);
    //          // if($request->file('tor_filepath')){
    //          //    $item->tor_filepath = $tor_filepath;
    //         return view('approval_tor/detail', $item);
    //     }
    //     elseif ($request->isMethod('post')) {
    //       //dd(Input::get('alasan'));
    //         $item = Tor::find($id);
    //         $item->alasan = Input::get('tor_review_status');
    //         $item->alasan = Input::get('alasan');
    //         $item->save();
    //         return redirect('approval_tor/daftar');

    //     }
    // }
    //     }
    // }

    public function send_email(Request $request)
    {
        $title ="halo";
        $content = "juga";

        Mail::send('approval_tor.send', ['title' => $title, 'content' => $content], function ($message)
        {

            $message->from('RKAP-ONLINE@no-reply.com', 'RKAP-ONLINE');

            $message->to('feri@ptpjb.com');

        });

        return response()->json(['message' => 'Request completed']);
        # code...
    }
}
