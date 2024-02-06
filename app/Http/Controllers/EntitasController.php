<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Entities\Entitas;
use App\Entities\Lokasi;
use App\Entities\Distrik;
use App\Entities\Strategi_bisnis;
use App\Entities\Unit;

class EntitasController extends Controller
{
    public function index()
    {

        $this->data['strategi_bisnis_id'] = Input::get('strategi_bisnis_id');
        $this->data['name_distrik'] = Input::get('name_distrik');
        $this->data['name_lokasi'] = Input::get('name_lokasi');
        $this->data['name_entitas'] = Input::get('name_entitas');

        $this->data['strategi_bisnis'] = Strategi_bisnis::get();

        $this->data['entitas'] = Entitas::/*searchStrategiBisnis($this->data['strategi_bisnis_id'])
                                        ->*/searchDistrik($this->data['name_distrik'])
                                        ->searchLokasi($this->data['name_lokasi'])
                                        ->searchEntitas($this->data['name_entitas'])
                                        ->get();
        $Sb = Strategi_bisnis::all();

        return view('entitas.daftar_entitas',  $this->data, compact('Sb'));
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

    public function tambah(Request $request)
    {
    	if ($request->isMethod('get')) {
    		# code...
            $item ['lokasi'] = Lokasi::all();
            $item ['distrik'] = Distrik::all();
            $item ['strategi_bisnis'] = Strategi_bisnis::all();
    		return view('entitas.tambah', $item);
    	}
    	elseif ($request->isMethod('post')) {
    		$this->validate($request, [
                    'strategi_bisnis_id' => 'required:strategi_bisnis',
                    'distrik_id' => 'required:distrik',
                    'lokasi_id' => 'required:lokasi',
                    'name' => 'required|unique:entitas,name',
                ]);
    		$item = array(
                          'name' => Input::get('name'),
                          'lokasi_id' => Input::get('lokasi_id')
                          );
    		
            $transaction = Entitas::create($item);

                if($transaction)
                {
                    $request->session()->flash('success', 'Data berhasil ditambahkan');
                }

    		return redirect('entitas/daftar');
    	}
    }

    public function unit()
    {
        $unit = Unit::all();
            $data = ['unit' => $unit];

             // dd($data);
             // $item ['entitas'] = Entitas::all();
             // dd($item);
            return view('unit', $data);
    }

    public function detail(Request $request,$id)
    {
     /*   return view('Entitas.detail');*/
        if ($request->isMethod('get')) {
            # code...
            $item ['entitas'] = Entitas::findOrFail($id);
            $item['lokasi'] = Lokasi::all();
            $item ['distrik'] = Distrik::all();
            $item ['strategi_bisnis'] = Strategi_bisnis::all();
            return view('Entitas.detail', $item);
        }
        elseif ($request->isMethod('post')) 
        {
            $item = Entitas::findOrFail($id);
            $item->save();
            return redirect('entitas/detail');
        }
    }


    public function edit(Request $request,$id)
    {
    	/*$item = Bahan::find($id);
    	$item->name = "minyak";
    	$item->dave();*/
    	if ($request->isMethod('get')) {
    		# code...
    		$item ['entitas'] = Entitas::find($id);
            $item['lokasi'] = Lokasi::all();
            $item ['distrik'] = Distrik::all();
            $item ['strategi_bisnis'] = Strategi_bisnis::all();
    		return view('Entitas.edit', $item);
    	}
    	elseif ($request->isMethod('post')) {
    		$this->validate($request, [
                    'name' => 'required|unique:entitas,name',
                ]);
    		$item = Entitas::find($id);
    		$item->name = Input::get('name');
    		$item->save();

            $request->session()->flash('success', 'Data berhasil diubah');

    		return redirect('entitas/daftar');
    	}
    }

    public function delete(Request $request,$id)
    {
    	$item = Entitas::find($id);
    	$item->delete();

        $request->session()->flash('success', 'Data berhasil dihapus');

        return redirect('entitas/daftar');
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $entitas = db_pjb::where('name','LIKE', '%'.$search.'%')->paginate(10);
        return view('entitas.index', compact('entitas'));
    }

    
}
