<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
// use App\Http\StrategiBisnis;
use App\Entities\Strategi_bisnis;
use App\Entities\Distrik;

class StrategiBisnisController extends Controller
{
    	public function index()
    	{
    		$this->data['strategi_bisnis'] = Input::get('strategi_bisnis');
    		$this->data['strategi_bisnis'] = Strategi_bisnis::searchStrategiBisnis($this->data['strategi_bisnis'])->get();

    		$Sb = Strategi_bisnis::all();

    		return view('strategi_bisnis', $this->data, compact('Sb'));
    	}

    	public function distrik()
    	{
    		$this->data['strategi_bisnis_id'] = Input::get('strategi_bisnis_id');
        	$this->data['name_distrik'] = Input::get('name_distrik');

        	$this->data['strategi_bisnis'] = Strategi_bisnis::get();

        	// $this->data['distrik'] = Distrik::all();
        	$this->data['distrik'] = Distrik::searchStrategiBisnis($this->data['strategi_bisnis_id'])
                                        ->searchDistrik($this->data['name_distrik'])->get();

        	return view('distrik', $this->data);
    	}
    	
    	public function create(Request $request)
	    {
	        if ($request->isMethod('get')) {
	            return view('tambah_strategi_bisnis');
	        }
	        elseif ($request->isMethod('post')) {
	            $this->validate($request, [
		            'name' => 'required|unique:strategi_bisnis',
		        ]);
	            $item = array('name' => Input::get('name'));
	            $transaction = Strategi_bisnis::create($item);

	            if($transaction)
	            {
		            $request->session()->flash('success', 'Data berhasil ditambahkan');
		        }

	            return redirect('daftar_strategi_bisnis');
	        }
	    }

	    public function update(Request $request, $id)
	    {
	    	if ($request->isMethod('get')) {
	            $item['strategi_bisnis'] = Strategi_bisnis::find($id);


	            return view('edit_strategi_bisnis', $item);
	        }
	        elseif ($request->isMethod('post')) {
	        	$this->validate($request, [
		            'name' => 'required|unique:strategi_bisnis',
		        ]);
	        	
	            $item = Strategi_bisnis::find($id);
	            $item->name = Input::get('name');
	            $item->save();

	            $request->session()->flash('success', 'Data berhasil diubah');

	            return redirect('daftar_strategi_bisnis');
	        }
	    }

	    public function delete(Request $request, $id)
	    {
	    	$item = Strategi_bisnis::find($id);
	    	$item->delete();

	    	$request->session()->flash('success', 'Data berhasil dihapus');

	    	return redirect('daftar_strategi_bisnis');
	    }

	    public function search(Request $request)
	    {
	        $search = $request->get('search');
	        $strategi_bisnis = pjb::where('name','LIKE', '%'.$search.'%')->paginate(10);
	        return view('strategi_bisnis.index', compact('strategi_bisnis'));
	    }

	    public function detail(Request $request, $id)
	    {
	    	if ($request->isMethod('get')) {
	            $item['strategi_bisnis'] = Strategi_bisnis::find($id);
	            return view('detail_strategi_bisnis', $item);
	        }
	        elseif ($request->isMethod('post')) {
	            $item = Strategi_bisnis::find($id);
	            $item->save();
	            return redirect('daftar_strategi_bisnis');
	        }
	    }
}

