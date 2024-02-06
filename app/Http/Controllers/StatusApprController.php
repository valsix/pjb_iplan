<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Entities\StatusAppr;
// use App\Entities\Strategi_bisnis;
// use App\Entities\Lokasi;

class StatusApprController extends Controller
{
    public function index()
    {
        // $this->data['strategi_bisnis_id'] = Input::get('strategi_bisnis_id');
        // $this->data['name_distrik'] = Input::get('name_distrik');

        // $this->data['strategi_bisnis'] = Strategi_bisnis::get();

        // $this->data['distrik'] = Distrik::all();
        // $Sb = Strategi_bisnis::all();

        $this->data['status_appr'] = StatusAppr::all();
        $Sb = array();

        return view('status_appr/daftar', $this->data, compact('Sb'));
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
            $item['status_appr'] = StatusAppr::find($id);
            $item['disabled']= '';
            return view('status_appr/tambah',$item);
        }
        elseif ($request->isMethod('post')) 
        {
            $this->validate($request, [
                    'name' => 'required|unique:status_appr,name',
                ]);
            $item = array(
                        'name' => Input::get('name'),
                        'keterangan' => Input::get('keterangan')
                    );
            // Distrik::create($item);

            $transaction = StatusAppr::create($item);

            if($transaction)
            {
                $request->session()->flash('success', 'Data berhasil ditambahkan');
            }

            return redirect('status_appr/daftar');
        }
    }

    public function update(Request $request, $id)
    {
        if($request->isMethod("get"))
        {
            // $item['strategi_bisnis'] = Strategi_bisnis::all();
            $item['status_appr'] = StatusAppr::find($id);
            $item['disabled']= '';
            return view('status_appr/tambah',$item);
        }
        elseif ($request->isMethod('post')) 
        {
            $this->validate($request, [
                    'name' => 'required:status_appr,name',
                ]);
            $item = StatusAppr::find($id);
            $item->name = Input::get('name');
            $item->keterangan = Input::get('keterangan');
            $item->save();

            $request->session()->flash('success', 'Data berhasil diubah');

            return redirect('status_appr/daftar');
        }
    }

    public function delete(Request $request, $id)
    {
        $item = StatusAppr::find($id);
        $item->delete();

        $request->session()->flash('success', 'Data berhasil dihapus');

        return redirect('status_appr/daftar');
    }

    public function search(Request $request, $id)
    {
        $search = $request->get('search');
        $status_appr = pjb::where('name','LIKE', '%'.$search.'%')->paginate(10);
        return view('status_appr/daftar.index', compact('status_appr'));
    }

    public function detail(Request $request, $id)
    {
         if($request->isMethod("get"))
        {
            // $item['strategi_bisnis'] = Strategi_bisnis::all();
            $item['status_appr'] = StatusAppr::find($id);
            $item['disabled']= 'disabled';
            return view('status_appr/tambah',$item);
        }
        elseif ($request->isMethod('post')) {
            # code...
            $item = StatusAppr::find($id);
            $item->save();
            return redirect('status_appr/daftar');
        }
    }
}
