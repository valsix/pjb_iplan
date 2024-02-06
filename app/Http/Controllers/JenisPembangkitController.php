<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Entities\JenisPembangkit;
// use App\Entities\Strategi_bisnis;
// use App\Entities\Lokasi;

class JenisPembangkitController extends Controller
{
    public function index()
    {
        // $this->data['strategi_bisnis_id'] = Input::get('strategi_bisnis_id');
        // $this->data['name_distrik'] = Input::get('name_distrik');

        // $this->data['strategi_bisnis'] = Strategi_bisnis::get();

        // $this->data['distrik'] = Distrik::all();
        // $Sb = Strategi_bisnis::all();

        $this->data['jenis_pembangkit'] = JenisPembangkit::all();
        $Sb = array();

        return view('jenis_pembangkit/daftar', $this->data, compact('Sb'));
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
        $id= null;
        if ($request->isMethod('get')) 
        {
            // $item ['strategi_bisnis'] = Strategi_bisnis::all();
            $item['jenis_pembangkit'] = JenisPembangkit::find($id);
            $item['disabled']= '';
            return view('jenis_pembangkit/tambah',$item);
        }
        elseif ($request->isMethod('post')) 
        {
            $this->validate($request, [
                    'name' => 'required|unique:jenis_pembangkit,name',
                ]);
            $item = array(
                        'name' => Input::get('name'),
                        'keterangan' => Input::get('keterangan')
                    );
            // Distrik::create($item);

            $transaction = JenisPembangkit::create($item);

            if($transaction)
            {
                $request->session()->flash('success', 'Data berhasil ditambahkan');
            }

            return redirect('jenis_pembangkit/daftar');
        }
    }

    public function update(Request $request, $id)
    {
        if($request->isMethod("get"))
        {
            // $item['strategi_bisnis'] = Strategi_bisnis::all();
            $item['jenis_pembangkit'] = JenisPembangkit::find($id);
            $item['disabled']= '';
            return view('jenis_pembangkit/tambah',$item);
        }
        elseif ($request->isMethod('post')) 
        {
            $this->validate($request, [
                    'name' => 'required|unique:jenis_pembangkit,name',
                ]);
            $item = JenisPembangkit::find($id);
            $item->name = Input::get('name');
            $item->keterangan = Input::get('keterangan');
            $item->save();

            $request->session()->flash('success', 'Data berhasil diubah');

            return redirect('jenis_pembangkit/daftar');
        }
    }

    public function delete(Request $request, $id)
    {
        $item = JenisPembangkit::find($id);
        $item->delete();

        $request->session()->flash('success', 'Data berhasil dihapus');

        return redirect('jenis_pembangkit/daftar');
    }

    public function search(Request $request, $id)
    {
        $search = $request->get('search');
        $jenis_pembangkit = pjb::where('name','LIKE', '%'.$search.'%')->paginate(10);
        return view('jenis_pembangkit/daftar.index', compact('jenis_pembangkit'));
    }

    public function detail(Request $request, $id)
    {
         if($request->isMethod("get"))
        {
            // $item['strategi_bisnis'] = Strategi_bisnis::all();
            $item['jenis_pembangkit'] = JenisPembangkit::find($id);
            $item['disabled']= 'disabled';
            return view('jenis_pembangkit/tambah',$item);
        }
        elseif ($request->isMethod('post')) {
            # code...
            $item = JenisPembangkit::find($id);
            $item->save();
            return redirect('jenis_pembangkit/daftar');
        }
    }
}
