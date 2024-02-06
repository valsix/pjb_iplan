<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Entities\Unit;
use App\Entities\Entitas;
use App\Entities\Lokasi;
use App\Entities\Distrik;
use App\Entities\Strategi_bisnis;

class UnitController extends Controller
{
    //
    public function index()
    	{
    	$this->data['strategi_bisnis_id'] = Input::get('strategi_bisnis_id');
        $this->data['name_distrik'] = Input::get('name_distrik');
        $this->data['name_lokasi'] = Input::get('name_lokasi');
        $this->data['name_entitas'] = Input::get('name_entitas');
        $this->data['name_unit'] = Input::get('name_unit');

        $this->data['strategi_bisnis'] = Strategi_bisnis::get();

        $this->data['unit'] = Unit::all();
        // $this->data['distrik'] = Distrik::searchStrategiBisnis($this->data['strategi_bisnis_id'])
        //                                 ->searchDistrik($this->data['name_distrik'])
        //                                 ->searchLokasi($this->data['name_lokasi'])
        //                                 ->searchEntitas($this->data['name_entitas'])
        //                                 ->searchUnit($this->data['name_unit'])
        //                                 ->get();
        $Sb = Strategi_bisnis::all();

        return view('unit', $this->data, compact('Sb'));
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

    public function create(Request $request)
	    {
	        if ($request->isMethod('get')) {
	            # code...
	            $item ['entitas'] = Entitas::all();
	            $item ['lokasi'] = Lokasi::all();
	            $item ['distrik'] = Distrik::all();
				$item ['strategi_bisnis'] = Strategi_bisnis::all();

				$Sb = Strategi_bisnis::all();

	            return view('tambah_unit', $item, compact('Sb'));
	        }
	        elseif ($request->isMethod('post')) {
	            $this->validate($request, [
                    'strategi_bisnis_id' => 'required:strategi_bisnis',
                    'distrik_id' => 'required:distrik',
                    'lokasi_id' => 'required:lokasi',
                    'entitas_id' => 'required:entitas',
                    'name' => 'required|unique:unit,name',
                ]);
	            $item = array(
	            			  'name' => Input::get('name'),
	            			  'entitas_id' => Input::get('entitas_id'));
	            // dd($item);
	            $transaction = Unit::create($item);

                if($transaction)
                {
                    $request->session()->flash('success', 'Data berhasil ditambahkan');
                }
	            return redirect('daftar_unit');
	        }
	    }

	public function update(Request $request,$id)
	    {
	    	if ($request->isMethod('get')) {
	            # code...
	            $item ['unit'] = Unit::find($id);
	            $item ['entitas'] = Entitas::all();
	            $item ['lokasi'] = Lokasi::all();
	            $item ['distrik'] = Distrik::all();
	            $item ['strategi_bisnis'] = Strategi_bisnis::all();
	            // dd($item);
	            return view('edit_unit', $item);
	        }
	        elseif ($request->isMethod('post')) {
	            $this->validate($request, [
                    'name' => 'required|unique:unit,name',
                ]);
	            $item = Unit::find($id);
	            $item->name = Input::get('name');
	            $item->save();

	            $request->session()->flash('success', 'Data berhasil diubah');

	            return redirect('daftar_unit');
	        }
	    }

	    public function delete(Request $request,$id)
	    {
	    	$item = Unit::find($id);
	    	$item->delete();

	    	$request->session()->flash('success', 'Data berhasil dihapus');

	    	return redirect('daftar_unit');
	    }

	    public function search(Request $request)
   		{
        	$search = $request->get('search');
        	$unit = pjb::where('name','LIKE', '%'.$search.'%')->paginate(10);
        	return view('unit.index', compact('unit'));
 		}

 		public function detail()
 		{
 			return view('detail_unit', $item);
 		}
}
