<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Entities\BidangDivisi;
// use App\Entities\Strategi_bisnis;
// use App\Entities\Lokasi;

use GuzzleHttp\Client;

class BidangDivisiController extends Controller
{
    public function index()
    {
        // $this->data['strategi_bisnis_id'] = Input::get('strategi_bisnis_id');
        // $this->data['name_distrik'] = Input::get('name_distrik');

        // $this->data['strategi_bisnis'] = Strategi_bisnis::get();

        // $this->data['distrik'] = Distrik::all();
        // $Sb = Strategi_bisnis::all();

        $this->data['bidang_divisi'] = BidangDivisi::all();
        $Sb = array();

        return view('bidang_divisi/daftar', $this->data, compact('Sb'));
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

    public function create(Request $request)
    {
        $id=null;
        if ($request->isMethod('get')) 
        {
            // $item ['strategi_bisnis'] = Strategi_bisnis::all();
            $item['bidang_divisi']= BidangDivisi::find($id);
            $item['disabled']= '';
            return view('bidang_divisi/tambah',$item);
        }
        elseif ($request->isMethod('post')) 
        {
            $this->validate($request, [
                    'kode' => 'required|unique:bidang_divisi,kode',
                    'name' => 'required:bidang_divisi,name',
                ]);
            $item = array(
                        'kode' => Input::get('kode'),
                        'name' => Input::get('name'),
                        'keterangan' => Input::get('keterangan')
                    );
            // Distrik::create($item);

            $transaction = BidangDivisi::create($item);

            if($transaction)
            {
                $request->session()->flash('success', 'Data berhasil ditambahkan');
            }

            return redirect('bidang_divisi/daftar');
        }
    }

    public function update(Request $request, $id)
    {
        if($request->isMethod("get"))
        {
            $item['bidang_divisi'] = BidangDivisi::find($id);
            $item['disabled']= '';
            // $item['strategi_bisnis'] = Strategi_bisnis::all();
            return view('bidang_divisi/tambah',$item);
        }
        elseif ($request->isMethod('post')) 
        {
            $this->validate($request, [
                    'kode' => 'required|unique:bidang_divisi,kode',
                    'name' => 'required:bidang_divisi,name',
                ]);
            $item = BidangDivisi::find($id);
            $item->kode = Input::get('kode');
            $item->name = Input::get('name');
            $item->keterangan = Input::get('keterangan');
            $item->save();

            $request->session()->flash('success', 'Data berhasil diubah');

            return redirect('bidang_divisi/daftar');
        }
    }

    public function sinkron(Request $request)
    {
        $client = new Client();
        $response = $client->get('https://talentman.plnnusantarapower.co.id/api/daftar_jabatan'); // Ganti URL sesuai dengan API yang Anda inginkan.

        if ($response->getStatusCode() == 200) {
            $data = json_decode($response->getBody(), true);
            // return $data;
            for ($i=0; $i < count($data); $i++) { 
                $kode= $data[$i]['KODE_BAGIAN'];
                $name= $data[$i]['BAGIAN'];

                $kodes= str_replace(" ", "", $kode);

                $cek= BidangDivisi::where('kode', $kodes)->where('name', $name)->count();
                $cek2= BidangDivisi::where('kode', $kodes)->count();

                if (!$cek && $cek2) 
                {
                    $appr_kkpupd= BidangDivisi::where('kode', $kodes)
                    ->update([
                        'name' => $name,
                        'updated_at' => date("Y-m-d H:i:s"),
                    ]);
                }

                if(!$cek2)
                {
                    $item = array(
                        'kode' => $kodes,
                        'name' => $name,
                        'created_at' => date("Y-m-d H:i:s")
                    );

                    $transaction = BidangDivisi::create($item);
                }
            }

            // return $response->getBody();

            // Lakukan sesuatu dengan data yang diterima dari API.
            // Misalnya, tampilkan data atau simpan ke database.
        } else {
            // Handle kesalahan jika diperlukan.
        }

        $request->session()->flash('success', 'Data berhasil disinkronkan');

        return redirect('bidang_divisi/daftar');
    }

    public function delete(Request $request, $id)
    {
        $item = BidangDivisi::find($id);
        $item->delete();

        $request->session()->flash('success', 'Data berhasil dihapus');

        return redirect('bidang_divisi/daftar');
    }

    public function search(Request $request, $id)
    {
        $search = $request->get('search');
        $bidang_divisi = pjb::where('name','LIKE', '%'.$search.'%')->paginate(10);
        return view('bidang_divisi/daftar.index', compact('bidang_divisi'));
    }

    public function detail(Request $request, $id)
    {
        if($request->isMethod("get"))
        {
            $item['bidang_divisi'] = BidangDivisi::find($id);
            $item['disabled']= 'disabled';
            // $item['strategi_bisnis'] = Strategi_bisnis::all();
            return view('bidang_divisi/tambah',$item);
        }
        elseif ($request->isMethod('post')) 
        {
            # code...
            $item = BidangDivisi::find($id);
            $item->save();
            return redirect('bidang_divisi/daftar');
        }
    }
}
