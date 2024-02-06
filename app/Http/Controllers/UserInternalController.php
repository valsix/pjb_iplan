<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Entities\UserInternal;
use App\Entities\User;
use App\Entities\BidangDivisi;
// use App\Entities\Strategi_bisnis;
// use App\Entities\Lokasi;
use DB;
use GuzzleHttp\Client;

class UserInternalController extends Controller
{
    public function index()
    {
        $this->data['user_internal'] = UserInternal::all();
        $Sb = array();

        return view('user_internal/daftar', $this->data, compact('Sb'));
    }

    public function sinkron(Request $request)
    {
        $client = new Client();
        $response = $client->get('https://talentman.plnnusantarapower.co.id/api/daftar_jabatan_erm'); // Ganti URL sesuai dengan API yang Anda inginkan.

        if ($response->getStatusCode() == 200) {
            $data = json_decode($response->getBody(), true);
            // return $data;
            for ($i=0; $i < count($data); $i++) { 

                $position_id= $data[$i]['POSITION_ID'];
                $nama_posisi= $data[$i]['NAMA_POSISI'];
                $kode_klasifikasi_unit= $data[$i]['KODE_KLASIFIKASI_UNIT'];
                $klasifikasi_unit= $data[$i]['KLASIFIKASI_UNIT'];
                $kode_unit= $data[$i]['KODE_UNIT'];
                $unit= $data[$i]['UNIT'];
                $kode_ditbid= $data[$i]['KODE_DITBID'];
                $ditbid= $data[$i]['DITBID'];
                $kode_bagian= $data[$i]['KODE_BAGIAN'];
                $bagian= $data[$i]['BAGIAN'];
                $occup_status= $data[$i]['OCCUP_STATUS'];
                $nama_lengkap= $data[$i]['NAMA_LENGKAP'];
                $email= $data[$i]['EMAIL'];
                $nid= $data[$i]['NID'];
                $posisi= $data[$i]['POSISI'];

                if (empty($nid)) {
                    continue;
                }

                // $cek= UserInternal::where('nid', $nid)->count();
                // // $cek2= UserInternal::where('kode', $kodes)->count();

                // if ($cek) 
                // {
                //     $appr_uiupd= UserInternal::where('nid', $nid)
                //     ->update([
                //         'position_id' => $position_id,
                //         'nama_posisi' => $nama_posisi,
                //         'kode_klasifikasi_unit' => $kode_klasifikasi_unit,
                //         'klasifikasi_unit' => $klasifikasi_unit,
                //         'kode_unit' => $kode_unit,
                //         'unit' => $unit,
                //         'kode_ditbid' => $kode_ditbid,
                //         'ditbid' => $ditbid,
                //         'kode_bagian' => $kode_bagian,
                //         'bagian' => $bagian,
                //         'occup_status' => $occup_status,
                //         'nama_lengkap' => $nama_lengkap,
                //         'email' => $email,
                //         // 'posisi' => $posisi,
                //         // 'updated_at' => date("Y-m-d H:i:s")
                //     ]);
                // }
                // else
                // {
                    $item = array(
                        'position_id' => $position_id,
                        'nama_posisi' => $nama_posisi,
                        'kode_klasifikasi_unit' => $kode_klasifikasi_unit,
                        'klasifikasi_unit' => $klasifikasi_unit,
                        'kode_unit' => $kode_unit,
                        'unit' => $unit,
                        'kode_ditbid' => $kode_ditbid,
                        'ditbid' => $ditbid,
                        'kode_bagian' => $kode_bagian,
                        'bagian' => $bagian,
                        'occup_status' => $occup_status,
                        'nama_lengkap' => $nama_lengkap,
                        'email' => $email,
                        // 'posisi' => $posisi,
                        'nid' => $nid,
                        'created_at' => date("Y-m-d H:i:s")
                    );

                    $transaction = UserInternal::create($item);
                // }

                $user_internal= UserInternal::where('nid', $nid)->first();

                if ($user_internal) 
                {
                    $position_id= $user_internal->position_id;

                    $kodes= str_replace(" ", "", $user_internal->kode_bagian);
                    // DB::enableQueryLog();
                    $bidang_divisi= BidangDivisi::where('kode', $kodes)->first();
                    // dd(DB::getQueryLog());

                    $bidang_divisi_id= null;
                    if ($bidang_divisi) 
                    {
                        $bidang_divisi_id= $bidang_divisi->id;
                    }

                    if ($position_id) 
                    {
                        $appr_uupd= User::where('username', $nid)
                        ->update([
                            'position_id' => $position_id,
                            'bidang_divisi_id' => $bidang_divisi_id,
                        ]);
                    }   
                }
            }

            // return $response->getBody();

            // Lakukan sesuatu dengan data yang diterima dari API.
            // Misalnya, tampilkan data atau simpan ke database.
        } else {
            // Handle kesalahan jika diperlukan.
        }

        $request->session()->flash('success', 'Data berhasil disinkronkan');

        return redirect('user_internal/manage');
    }

    public function search(Request $request, $id)
    {
        $search = $request->get('search');
        $user_internal = pjb::where('name','LIKE', '%'.$search.'%')->paginate(10);
        return view('user_internal/daftar.index', compact('user_internal'));
    }

    public function detail(Request $request, $id)
    {
        if($request->isMethod("get"))
        {
            $item['user_internal'] = UserInternal::find($id);
            $item['disabled']= 'disabled';
            // $item['strategi_bisnis'] = Strategi_bisnis::all();
            return view('user_internal/tambah',$item);
        }
        elseif ($request->isMethod('post')) 
        {
            # code...
            $item = UserInternal::find($id);
            $item->save();
            return redirect('user_internal/daftar');
        }
    }

    // public function Ajax($id)
    //  {
    //     $ds = Distrik::where('strategi_bisnis_id', $id)->select("name","id")->get();

    //     return json_encode($ds);
    //  }

    // public function myformAjax2($id)
    //  {
    //     $lokasi = Lokasi::where('distrik_id', $id)->select("name", "id")->get();

    //     return json_encode($lokasi);
    //  }

    // public function lokasi()
    // {
    //     $lokasi = Lokasi::all();
    //     $data = ['lokasi' => $lokasi];
    //     return view('lokasi.daftar_lokasi', $data);
    // }

    // public function create(Request $request)
    // {
    //     $id=null;
    //     if ($request->isMethod('get')) 
    //     {
    //         // $item ['strategi_bisnis'] = Strategi_bisnis::all();
    //         $item['user_internal']= UserInternal::find($id);
    //         $item['disabled']= '';
    //         return view('user_internal/tambah',$item);
    //     }
    //     elseif ($request->isMethod('post')) 
    //     {
    //         $this->validate($request, [
    //                 'kode' => 'required|unique:user_internal,kode',
    //                 'name' => 'required:user_internal,name',
    //             ]);
    //         $item = array(
    //                     'kode' => Input::get('kode'),
    //                     'name' => Input::get('name'),
    //                     'keterangan' => Input::get('keterangan')
    //                 );
    //         // Distrik::create($item);

    //         $transaction = UserInternal::create($item);

    //         if($transaction)
    //         {
    //             $request->session()->flash('success', 'Data berhasil ditambahkan');
    //         }

    //         return redirect('user_internal/daftar');
    //     }
    // }

    // public function update(Request $request, $id)
    // {
    //     if($request->isMethod("get"))
    //     {
    //         $item['user_internal'] = UserInternal::find($id);
    //         $item['disabled']= '';
    //         // $item['strategi_bisnis'] = Strategi_bisnis::all();
    //         return view('user_internal/tambah',$item);
    //     }
    //     elseif ($request->isMethod('post')) 
    //     {
    //         $this->validate($request, [
    //                 'kode' => 'required|unique:user_internal,kode',
    //                 'name' => 'required:user_internal,name',
    //             ]);
    //         $item = UserInternal::find($id);
    //         $item->kode = Input::get('kode');
    //         $item->name = Input::get('name');
    //         $item->keterangan = Input::get('keterangan');
    //         $item->save();

    //         $request->session()->flash('success', 'Data berhasil diubah');

    //         return redirect('user_internal/daftar');
    //     }
    // }

    

    // public function delete(Request $request, $id)
    // {
    //     $item = UserInternal::find($id);
    //     $item->delete();

    //     $request->session()->flash('success', 'Data berhasil dihapus');

    //     return redirect('user_internal/daftar');
    // }

}
