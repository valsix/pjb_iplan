<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Entities\KondisiAICluster;
// use App\Entities\Strategi_bisnis;
// use App\Entities\Lokasi;

class KondisiAIClusterController extends Controller
{
    public function index()
    {
        // $this->data['strategi_bisnis_id'] = Input::get('strategi_bisnis_id');
        // $this->data['name_distrik'] = Input::get('name_distrik');

        // $this->data['strategi_bisnis'] = Strategi_bisnis::get();

        // $this->data['distrik'] = Distrik::all();
        // $Sb = Strategi_bisnis::all();

        $this->data['kondisi_aicluster'] = KondisiAICluster::all();
        $Sb = array();

        return view('kondisi_aicluster/daftar', $this->data, compact('Sb'));
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
            $item['kondisi_aicluster']= KondisiAICluster::find($id);
            $item['disabled']= '';
            return view('kondisi_aicluster/tambah',$item);
        }
        elseif ($request->isMethod('post')) 
        {
            $this->validate($request, [
                    'name' => 'required|unique:kondisi_aicluster,name',
                    'nilai_min' => 'required:kondisi_aicluster,nilai_min',
                    'nilai_max' => 'required:kondisi_aicluster,nilai_max',
                ]);
            $item = array(
                        'name' => Input::get('name'),
                        'keterangan' => Input::get('keterangan'),
                        'nilai_min' => Input::get('nilai_min'),
                        'nilai_max' => Input::get('nilai_max')
                    );
            // Distrik::create($item);

            $transaction = KondisiAICluster::create($item);

            if($transaction)
            {
                $request->session()->flash('success', 'Data berhasil ditambahkan');
            }

            return redirect('kondisi_aicluster/daftar');
        }
    }

    public function update(Request $request, $id)
    {
        if($request->isMethod("get"))
        {
            $item['kondisi_aicluster'] = KondisiAICluster::find($id);
            $item['disabled']= '';
            // $item['strategi_bisnis'] = Strategi_bisnis::all();
            return view('kondisi_aicluster/tambah',$item);
        }
        elseif ($request->isMethod('post')) 
        {
            $this->validate($request, [
                    'name' => 'required:kondisi_aicluster,name',
                    'nilai_min' => 'required:kondisi_aicluster,nilai_min',
                    'nilai_max' => 'required:kondisi_aicluster,nilai_max',
                ]);
            $item = KondisiAICluster::find($id);
            $item->name = Input::get('name');
            $item->keterangan = Input::get('keterangan');
            $item->nilai_min = Input::get('nilai_min');
            $item->nilai_max = Input::get('nilai_max');
            $item->save();

            $request->session()->flash('success', 'Data berhasil diubah');

            return redirect('kondisi_aicluster/daftar');
        }
    }

    public function delete(Request $request, $id)
    {
        $item = KondisiAICluster::find($id);
        $item->delete();

        $request->session()->flash('success', 'Data berhasil dihapus');

        return redirect('kondisi_aicluster/daftar');
    }

    public function search(Request $request, $id)
    {
        $search = $request->get('search');
        $kondisi_aicluster = pjb::where('name','LIKE', '%'.$search.'%')->paginate(10);
        return view('kondisi_aicluster/daftar.index', compact('kondisi_aicluster'));
    }

    public function detail(Request $request, $id)
    {
        if($request->isMethod("get"))
        {
            $item['kondisi_aicluster'] = KondisiAICluster::find($id);
            $item['disabled']= 'disabled';
            // $item['strategi_bisnis'] = Strategi_bisnis::all();
            return view('kondisi_aicluster/tambah',$item);
        }
        elseif ($request->isMethod('post')) {
            # code...
            $item = KondisiAICluster::find($id);
            $item->save();
            return redirect('kondisi_aicluster/daftar');
        }
    }
}
