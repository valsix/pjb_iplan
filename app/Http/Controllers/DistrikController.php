<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Entities\Distrik;
use App\Entities\Strategi_bisnis;
use App\Entities\Lokasi;
use App\Entities\JenisPembangkit;

use DB;

class DistrikController extends Controller
{
    public function index()
    {
        $this->data['strategi_bisnis_id'] = Input::get('strategi_bisnis_id');
        $this->data['name_distrik'] = Input::get('name_distrik');

        $this->data['strategi_bisnis'] = Strategi_bisnis::get();

        // $this->data['distrik'] = Distrik::all();
        $this->data['distrik'] = Distrik::searchStrategiBisnis($this->data['strategi_bisnis_id'])
                                        ->searchDistrik($this->data['name_distrik'])->get();

        $Sb = Strategi_bisnis::all();

        return view('distrik', $this->data, compact('Sb'));
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

    public function lokasi()
    {
        $lokasi = Lokasi::all();
        $data = ['lokasi' => $lokasi];
        return view('lokasi.daftar_lokasi', $data);
    }

    public function create(Request $request)
    {
        if ($request->isMethod('get')) {
            $item ['strategi_bisnis'] = Strategi_bisnis::all();

            $item['jenis_pembangkit'] = JenisPembangkit::all();
            $item['distrik_jenpem'] = null;

            return view('tambah_distrik', $item);
        }
        elseif ($request->isMethod('post')) {
            $this->validate($request, [
                    'strategi_bisnis_id' => 'required:strategi_bisnis',
                    'kode_distrik' => 'required:distrik,code1',
                    'name' => 'required|unique:distrik,name',
                ]);

            // $item = array(
            //             'strategi_bisnis_id' => Input::get('strategi_bisnis_id'),
            //             'code1' => Input::get('kode_distrik'),
            //             'name' => Input::get('name'));
            // // Distrik::create($item);

            // $transaction = Distrik::create($item);

            $item = new Distrik();
            $item->strategi_bisnis_id = $request->strategi_bisnis_id;
            $item->code1 = $request->kode_distrik;
            $item->name = $request->name;
            $item->save();

            $jenpemm = $request->jenpem;
            if (count($jenpemm) > 0) {
                foreach ($jenpemm as $jenpemid) {

                    DB::table('distrik_jenis_pembangkit')->insert(
                        ['jenis_pembangkit_id' => $jenpemid, 'distrik_id' => $item->id]
                    );
                }
            }

                // if($transaction)
                // {
                    $request->session()->flash('success', 'Data berhasil ditambahkan');
                // }

            return redirect('distrik/daftar');
        }
    }
    public function update(Request $request, $id)
    {
        if($request->isMethod("get"))
        {
            $item['distrik'] = Distrik::find($id);
            $item['strategi_bisnis'] = Strategi_bisnis::all();

            $item['jenis_pembangkit'] = JenisPembangkit::all();

            $item['distrik_jenpem'] = DB::table('distrik_jenis_pembangkit')
                ->select('jenis_pembangkit.name', 'jenis_pembangkit.id')
                ->join('jenis_pembangkit', 'jenis_pembangkit.id', '=', 'distrik_jenis_pembangkit.jenis_pembangkit_id')
                ->join('distrik', 'distrik.id', '=', 'distrik_jenis_pembangkit.distrik_id')
                ->join('strategi_bisnis', 'strategi_bisnis.id', '=', 'distrik.strategi_bisnis_id')
                ->where('distrik_jenis_pembangkit.distrik_id', $id)
                ->get();

                // dd($item['distrik_jenpem']);

            return view('edit_distrik',$item);
        }
        elseif ($request->isMethod('post')) 
        {
            $this->validate($request, [
                    'name' => 'required:distrik,name',
                ]);

            $item = Distrik::findOrFail($id);
            $item->name = ($request->has('name')) ? $request->name : '';
            $item->save();

            $jenpemm = $request->jenpem;
            DB::table('distrik_jenis_pembangkit')->where('distrik_id', $id)->delete();
            if (count($jenpemm) > 0) {
                foreach ($jenpemm as $jenpemid) {

                    DB::table('distrik_jenis_pembangkit')->insert(
                        ['jenis_pembangkit_id' => $jenpemid, 'distrik_id' => $item->id]
                    );
                }
            }

            $request->session()->flash('success', 'Data berhasil diubah');

            return redirect('distrik/daftar');
        }
    }
    public function delete(Request $request, $id)
    {
        $item = Distrik::find($id);
        $item->delete();

        $request->session()->flash('success', 'Data berhasil dihapus');

        return redirect('distrik/daftar');
    }
    public function search(Request $request, $id)
    {
        $search = $request->get('search');
        $distrik = pjb::where('name','LIKE', '%'.$search.'%')->paginate(10);
        return view('distrik.index', compact('distrik'));
    }

    public function detail(Request $request, $id)
    {
         if($request->isMethod("get"))
        {
            $item['distrik'] = Distrik::find($id);
            $item['strategi_bisnis'] = Strategi_bisnis::all();
            return view('detail_distrik',$item);
        }
        elseif ($request->isMethod('post')) {
            # code...
            $item = Distrik::find($id);
            $item->save();
            return redirect('distrik/daftar');
        }
    }
}
