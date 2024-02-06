<?php

namespace App\Http\Controllers;

use Auth;
use Response;
use Redirect;
use App\Entities\Dmr;
use App\Entities\Distrik;
use App\Entities\Lokasi;
use App\Entities\Role;
use App\Entities\StrategiBisnis;
use App\Entities\Tor;
use App\Entities\TorAttachment;
use App\Entities\TorReviewPhase;
use App\Entities\TorReviewStatus;
use App\Entities\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class TorController extends Controller
{
    public function index()
    {
        $role_id = session('role_id');
        $role = Role::find($role_id);
        $role_spv_unit_dmr_tor = Role::find(ROLE_ID_SPV_TOR); // SPV Unit (TOR)

        //bukan kantor pusat dan bukan Staff Unit
        // if($role->is_kantor_pusat != 1 && $role_id != 2) {
        //     return redirect('');
        // }

        //bukan kantor pusat dan bukan SPV Unit (TOR)
        // if($role->is_kantor_pusat != 1 && $role_id != ROLE_ID_SPV_TOR) {
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
        $tor = null;
        $input_tahun = Input::get('tahun_anggaran');
        if (! $input_tahun) {
            $input_tahun = date('Y');
        }

        $input_lokasi = Input::get('lokasi');

        if($input_distrik!= null)
            $lokasi = Lokasi::where('distrik_id',$input_distrik)->get();

        if($input_lokasi != null && $input_tahun != null){
            //kantor pusat hanya bisa lihat submitted
            if($role->is_kantor_pusat) {
                $tor = Tor::with('dmr')->where('lokasi_id',$input_lokasi)
                        ->where('tahun_anggaran', $input_tahun)
                        ->where('is_submitted', '1')
                        ->orderBy('is_submitted', 'asc')
                        ->orderBy('tor_review_phase_id', 'asc')
                        ->orderBy('tor_review_status_id', 'desc')
                        ->orderBy('created_at', 'desc')
                        ->get();
            }
            else {
                $tor = Tor::with('dmr')->where('lokasi_id',$input_lokasi)
                    ->where('tahun_anggaran', $input_tahun)
                    ->orderBy('is_submitted', 'asc')
                    ->orderBy('tor_review_phase_id', 'asc')
                    ->orderBy('tor_review_status_id', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        }
    	return view('tor.daftar', compact('Sb', 'distrik', 'lokasi', 'input_sb', 'input_distrik', 'input_lokasi', 'input_tahun', 'tor', 'role_spv_unit_dmr_tor', 'role_id'));
    }

    public function publish()
    {
        $role_id = session('role_id');
        $role = Role::find($role_id);
        $role_spv_unit_dmr_tor = Role::find(ROLE_ID_SPV_TOR); // SPV Unit TOR

        $input_tahun = Input::get('tahun_anggaran');
        $input_sb = Input::get('strategi_bisnis');
        $input_distrik = Input::get('distrik');
        $input_lokasi = Input::get('lokasi');

        // set default tahun
        if (! $input_tahun) {
            $input_tahun = date('Y');
        }

        $Sb = StrategiBisnis::all();
        if ($input_sb) {
            $distrik = Distrik::where('strategi_bisnis_id',$input_sb)->get();
        } else {
            $distrik = [];
        }
        if ($input_distrik) {
            $lokasi = Lokasi::where('distrik_id',$input_distrik)->get();
        } else {
            $lokasi = [];
        }

        $tor = [];
        $tor_obj = null;

        if ($input_tahun && $input_lokasi) {
            $tor_obj = Tor::with('dmr')->where('is_published', 1)->where('lokasi_id',$input_lokasi);
        }

        if ($tor_obj) {
            $tor = $tor_obj->get();
        }

        if (old('tahun_anggaran')) {
            $input_tahun = old('tahun_anggaran');
        }

        if (old('strategi_bisnis')) {
            $input_sb = old('strategi_bisnis');
        }

        if (old('distrik')) {
            $input_distrik = old('distrik');
        }

        if (old('lokasi')) {
            $input_lokasi = old('lokasi');
        }

        return view('tor.publish', compact('Sb', 'distrik', 'lokasi', 'input_sb', 'input_distrik', 'input_lokasi', 'input_tahun', 'tor', 'role_spv_unit_dmr_tor'));
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

      public function dmr_ajax(Request $request) {

          $id_lokasi = $request->id_lokasi;
          $tahun_anggaran_id = $request->tahun_anggaran_id;

          $dmr = Dmr::where('lokasi_id', $id_lokasi)->where('tahun_anggaran', $tahun_anggaran_id)->get();

  		return json_encode($dmr);
      }

    public function create(Request $request)
    {

        if ($request->isMethod('get')) {
            $user = User::find(session('user_id'));
            $distrik_id = $user->distrik->id;
            $distrik = Distrik::find($distrik_id);
            $item['dmr'] = NULL;
            $item['lokasi'] = Lokasi::where("distrik_id", $distrik_id)->get();
            $item['current_tor_review_phase'] = TorReviewPhase::where('role_id',session('role_id'))->first();
            // $item['reviewer'] = User::with('distrik')
            //     ->where("distrik_id", $distrik_id)
            //     ->where('current_id_roles', ROLE_ID_MGR_TOR)
            //     ->orderBy('distrik_id', 'asc')->get();
            // $item ['tor'] = Tor::all();
            // $item ['torattachment'] = TorAttachment::all();
            return view('tor.tambah', $item, compact('distrik'));
        }

        elseif ($request->isMethod('post')) {
            $validate_rule = [
                'tahun_anggaran_id'=>'required',
                'no_dokumen'=>'required|unique:tor',
                //'no_dokumen_dmr'=>'required|unique:tor,no_dokumen_dmr',
                // 'tor_filepath'=>'required:tor',
            ];
            $validate_msg = [
                'tahun_anggaran_id.required'=>'Tahun anggaran wajib diisi',
                'no_dokumen.required'=>'ID Dokumen (TOR) wajib diisi',
                'no_dokumen.unique'=>'ID Dokumen (TOR) sudah terpakai',
                'no_dokumen_dmr.required'=>'ID Dokumen (DMR) wajib diisi',
                'no_dokumen_dmr.unique'=>'ID Dokumen (DMR) sudah terpakai',
                // 'tor_filepath.required'=>'Berkas tor wajib diisi',
            ];
            // if (Input::get('is_submitted') == 1) {
            //     $validate_rule['manager_user_id'] = 'required';
            //     $validate_msg['manager_user_id.required'] = 'Reviewer wajib diisi';
            // }
            // dd(Input::get());
            $this->validate($request, $validate_rule, $validate_msg);

            $tahun_sekarang=date('Y');
            $tahun_anggaran = Input::get('tahun_anggaran_id');
            $lokasi_id = Input::get('lokasi_id');
            $lokasi = Lokasi::find($lokasi_id);
            $time = date('Y-m-d h:i:s');
            $reviewer_id = Input::get('reviewer_id');

            $item = array(
                'created_at' => $time,
                'created_by' => session('user_id'),
                'is_submitted' => Input::get('is_submitted'),
                'lokasi_id' => $lokasi_id,
                'no_dokumen' => Input::get('no_dokumen'),
                'tahun_anggaran' => $tahun_anggaran,
                'tor_filepath' => '',
                'tor_review_status_id' => 4, // Queue
                'tor_review_phase_id' => 1, // Phase id=1
                // 'manager_user_id' => Input::get('manager_user_id'),
                'no_dokumen_dmr' => Input::get('no_dokumen_dmr'),

                'aspek_keamanan_k3' => Input::get('aspek_keamanan_k3'),
                'data_teknis' => Input::get('data_teknis'),
                'delivery' => Input::get('delivery'),
                'detail_pelaksanaan_pekerjaan' => Input::get('detail_pelaksanaan_pekerjaan'),
                'garansi' => Input::get('garansi'),
                // 'judul_dokumen' => Input::get('judul_dokumen'),
                'judul_dokumen' => '',
                'kelengkapan_pelaksanaan_pekerjaan' => Input::get('kelengkapan_pelaksanaan_pekerjaan'),
                'kualifikasi_calon_pelaksanaan_pekerjaan' => Input::get('kualifikasi_calon_pelaksanaan_pekerjaan'),
                'lain_lain' => Input::get('lain_lain'),
                'laporan_hasil_pekerjaan' => Input::get('laporan_hasil_pekerjaan'),
                'lingkup_pekerjaan' => Input::get('lingkup_pekerjaan'),
                'material_sisa_limbah' => Input::get('material_sisa_limbah'),
                'pendahuluan' => Input::get('pendahuluan'),
                'performance_desain' => Input::get('performance_desain'),
                'quality_acceptance' => Input::get('quality_acceptance'),
            );
            if(Input::get('is_submitted') == 1){
                $item['submitted_at'] = $time;
                $item['submitted_by'] = session('user_id');
            }

            if (empty($request->file('filepath'))) {
                # code...
                return redirect()->back()->withInput()->with('msg', 'Dokumen TOR wajib diisi');
            }

            $transaction = Tor::create($item);

            // mendapatkan data file yang diupload dan update tor_filepath (tambah id)
            if ($request->hasFile('tor_filepath')) {
                $file = $request->file('tor_filepath');
                $destinationPath = "tor/".$transaction->id;
                // $filename= $file->getClientOriginalName();
                $filename= preg_replace('/[^A-Za-z0-9-.\  ]/', '', $file->getClientOriginalName());
                $request->file('tor_filepath')->move($destinationPath, $filename);
                $tor_filepath = $destinationPath.'/'.$filename;
                $item = Tor::find($transaction->id);
                $item->tor_filepath = $tor_filepath;
                $item->save();
            }


            //lampiran
            $files = $request->file('filepath');
            // $files = $request->file('attachment');

            if($request->hasFile('filepath'))
            {
                $i = 1;
                foreach ($files as $file) {
                    // $file->store('users/' . $this->user->id . '/messages');

                    $destinationPath = "tor";
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
                            'tor_id' => $transaction->id,
                            'filepath' => $save_filepath,
                            );
                $transaction_attahment = TorAttachment::create($item_attachment);
            }

            if ($transaction)
            {
                $request->session()->flash('success','Data berhasil ditambah');
            }

            return redirect('tor/daftar?tahun_anggaran='.$tahun_anggaran.'&strategi_bisnis='.$lokasi->distrik->strategi_bisnis_id.'&distrik='.$lokasi->distrik_id.'&lokasi='.$lokasi_id);
        }
    }

    public function update(Request $request,$id)
    {
    	// return view('tor.tambah');

        if ($request->isMethod('get')) {
            $item['tor'] = $tor = Tor::find($id);
            $item['dmr'] = $tor->dmr ?: ['no_dokumen' => ''];
            $item['lokasi'] = $tor->lokasi ?: ['name' => ''];
            // $item['lokasi'] = Lokasi::find($item['tor']['lokasi_id']);
            $item['torattachment'] = TorAttachment::where('tor_id', $id)
                ->where('for_review', 0)
                ->orderBy('id')->get();
            // dd($item ['torattachment']);

            $user = User::find(session('user_id'));
            $distrik_id = $user->distrik->id;
            $item['current_tor_review_phase'] = TorReviewPhase::where('role_id',session('role_id'))->first();
            // $item['reviewer'] = User::with('distrik')
            //     ->where("distrik_id", $distrik_id)
            //     ->where('current_id_roles', ROLE_ID_MGR_TOR)
            //     ->orderBy('distrik_id', 'asc')->get();

            return view('tor.edit', $item);
        }

        elseif ($request->isMethod('post')) {
            $item = Tor::find($id);

            $validate_rule = [];
            $validate_msg = [];
            if ( strlen($item->tor_filepath) == 0) {
                // $validate_rule['tor_filepath'] = 'required:tor';
                // $validate_msg['tor_filepath.required'] = 'Berkas tor wajib diisi';
            }
            // if (Input::get('is_submitted') == 1) {
            //     $validate_rule['manager_user_id'] = 'required';
            //     $validate_msg['manager_user_id.required'] = 'Reviewer wajib diisi';
            // }

            $this->validate($request, $validate_rule, $validate_msg);

            $tor_attachment_id = Input::get('tor_attachment_id');

            // menghapus attachment yg dicentang
            $delete_attachments = Input::get('delete_attachments');
            if($delete_attachments != null){
                foreach ($delete_attachments as $key => $value) {
                    $update_attachment = array(
                        'updated_at' => date('Y-m-d H:i:s'),
                        'filepath' => '',
                    );
                    $delete = TorAttachment::where('id', $value)->update($update_attachment);
                }
            }

            // mendapatkan data file yang diupload:
            if($request->file('tor_filepath')){
                $tor_filepath = null;
                $file = $request->file('tor_filepath');
                $destinationPath = "tor";
                // $filename= $file->getClientOriginalName();
                $filename= preg_replace('/[^A-Za-z0-9-.\  ]/', '', $file->getClientOriginalName());
                $request->file('tor_filepath')->move($destinationPath, $filename);
                $tor_filepath = $destinationPath.'/'.$filename;
            }

            if($request->file('filepath')) {
                // lampiran
                // $file = $request->file('filepath');
                // $destinationPath = "tor";
                // $filename = $file->getClientOriginalName();
                // $request->file('filepath')->move($destinationPath, $filename);
                // $filepath = $destinationPath.'/'.$filename;

                // $transaction = Tor::update();
                $filepath = array();
                $files = $request->file('filepath');
                $i = 1;
                foreach ($files as $key => $file) {
                    // $file->store('users/' . $this->user->id . '/messages');

                    $destinationPath = "tor";
                    // $filename = $file->getClientOriginalName();
                    $filename= preg_replace('/[^A-Za-z0-9-.\  ]/', '', $file->getClientOriginalName());
                    $file->move($destinationPath.'/'.$id.'/', $filename);
                    $filepath[$i] = $destinationPath.'/'.$id.'/'.$filename;
                    $tor_attachment_id_update[$i] = $tor_attachment_id[$key];
                    $i++;
                }

                // dd($filepath);
                for($i=1;$i<=10;$i++) {
                    if(isset($filepath[$i])) {
                        $save_filepath = $filepath[$i];

                        $idd = array(
                            // 'tor_id' => $id,
                            'filepath' => $save_filepath,
                        );

                        // dd($filepath[$i]);
                        $transaction_attahment = TorAttachment::where('id', $tor_attachment_id_update[$i])->update($idd);
                    }
                }
            }

            if($request->file('tor_filepath')){
                $item->tor_filepath = $tor_filepath;
            }

            $item->is_submitted = Input::get('is_submitted');
            // $item->manager_user_id = Input::get('manager_user_id');

            $item->aspek_keamanan_k3 = Input::get('aspek_keamanan_k3');
            $item->data_teknis = Input::get('data_teknis');
            $item->delivery = Input::get('delivery');
            $item->detail_pelaksanaan_pekerjaan = Input::get('detail_pelaksanaan_pekerjaan');
            $item->garansi = Input::get('garansi');
            // $item->judul_dokumen = Input::get('judul_dokumen');
            $item->judul_dokumen = '';
            $item->kelengkapan_pelaksanaan_pekerjaan = Input::get('kelengkapan_pelaksanaan_pekerjaan');
            $item->kualifikasi_calon_pelaksanaan_pekerjaan = Input::get('kualifikasi_calon_pelaksanaan_pekerjaan');
            $item->lain_lain = Input::get('lain_lain');
            $item->laporan_hasil_pekerjaan = Input::get('laporan_hasil_pekerjaan');
            $item->lingkup_pekerjaan = Input::get('lingkup_pekerjaan');
            $item->material_sisa_limbah = Input::get('material_sisa_limbah');
            $item->pendahuluan = Input::get('pendahuluan');
            $item->performance_desain = Input::get('performance_desain');
            $item->quality_acceptance = Input::get('quality_acceptance');


            $time = date('Y-m-d h:i:s');
            $item->updated_at = $time;
            $item->updated_by = session('user_id');

            if(Input::get('is_submitted') == 1){
                $item['submitted_at'] = $time;
                $item['submitted_by'] = session('user_id');
                $item['tor_review_status_id'] = 4;
                $item['tor_review_phase_id'] = 1;
            }

            $item->save();


            // if($request->file('filepath')){
            //     $data['filepath'] = $filepath;
            //     $item_attachment = TorAttachment::where('tor_id', $id)
            //         ->update($data);
            // }

            $request->session()->flash('success','Data berhasil diupdate');

            return redirect('tor/daftar?tahun_anggaran='.$item->tahun_anggaran.'&strategi_bisnis='.$item->lokasi->distrik->strategi_bisnis_id.'&distrik='.$item->lokasi->distrik_id.'&lokasi='.$item->lokasi_id);
        }
    }

    public function detail(Request $request, $id)
    {
        // return view('tor.tambah');
        if ($request->isMethod('get')) {
            $tor = Tor::find($id);
            if($tor!= null){
                $data = ['tor' => $tor ];
                // $data['torattachment'] = TorAttachment::all();
                $data['dmr'] = $tor->dmr ?: ['no_dokumen' => '', 'id' => ''];
                $data['torattachment'] = TorAttachment::where('tor_id', $id)
                    ->where('for_review', 0)
                    ->orderBy('id', 'asc')->get();

                $data['review_attachment'] = TorAttachment::where('tor_id', $id)
                    ->where('for_review', 1)
                    ->orderBy('id', 'asc')->get();

                return view('tor.detail', $data);
            }
            else {
                return redirect('tor/daftar');
            }

        } elseif ($request->isMethod('post')) {
            return back()->withInput();
        }
    }

    public function delete(Request $request,$id)
    {
        $item = Tor::find($id);
        if($item!= null){
            $return_path = '?tahun_anggaran='.$item->tahun_anggaran.'&strategi_bisnis='.$item->lokasi->distrik->strategi_bisnis_id.'&distrik='.$item->lokasi->distrik_id.'&lokasi='.$item->lokasi_id;

            // TOR boleh dihapus jika belum disubmit
            if($item->is_submitted == 0){
                // hapus file
                array_map('unlink', glob("tor/".$id."/*.*"));

                // hapus attachments
                if (file_exists("tor/".$id)) {
                    rmdir("tor/".$id);
                }

                // hapus tor dari db
                $item->delete();

                $request->session()->flash('success','Data berhasil dihapus');
            }
            return redirect('tor/daftar'.$return_path);
        }
    	return redirect('tor/daftar');
    }

    public function download_attachment(Request $request, $id)
    {
        $tor = Tor::find($id);
        if ( !is_null($tor) ) {
            $tor_attachment = $tor->where('tor_id', $id);
            if (count($tor_attachment) > 0) {
                $zip_path = $tor->getZip();
                if ($zip_path !== false) {
                    return Response::download($zip_path, basename($zip_path));
                }
            }

        }
        abort(404);
    }
}
