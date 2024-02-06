<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Entities\PrkParent;
// use Illuminate\Http\Request;

class PrkParentController extends Controller
{
    public function index()
    	{
    		$prk_parent = PrkParent::all();
        	$data = ['prk_parent' => $prk_parent];

        	// dd($data);
        	return view('prk_parent.daftar_prk_parent', $data);
    	}
    	
    	public function create(Request $request)
	    {
	        if ($request->isMethod('get')) {
	            # code...
	            return view('prk_parent.tambah_prk_parent');
	        }
	        elseif ($request->isMethod('post')) {
	            # code...
	            // dd(Input::get());

	        	$this->validate($request, [
	        		'desc_prk_parent' => 'required',
                    'identity_prk_parent' => 'required|unique:prk_parent',
                    'name_prk_parent' => 'required|unique:prk_parent',
                ]);

	            $item = array(
	            				'desc_prk_parent' => Input::get('desc_prk_parent'),
	            				'identity_prk_parent' => Input::get('identity_prk_parent'),
	            				'name_prk_parent' => Input::get('name_prk_parent'));
	            
	            $transaction = PrkParent::create($item);

                if($transaction)
                {
                    $request->session()->flash('success', 'Data berhasil ditambahkan');
                }

	            return redirect('prk_parent/daftar');
	        }
	    }

	    public function update(Request $request,$id)
	    {
	    	if ($request->isMethod('get')) {
	            # code...
	            $item['prk_parent'] = PrkParent::find($id);
	            return view('prk_parent.edit_prk_parent', $item);
	        }
	        elseif ($request->isMethod('post')) {
	            # code...
	            $this->validate($request, [
	        		'desc_prk_parent' => 'required',
                    'identity_prk_parent' => 'required|unique:prk_parent',
                    'name_prk_parent' => 'required|unique:prk_parent',
                ]);
	            $item = PrkParent::find($id);
	            $item->desc_prk_parent = Input::get('desc_prk_parent');
	            $item->identity_prk_parent = Input::get('identity_prk_parent');
	            $item->name_prk_parent = Input::get('name_prk_parent');
	            $item->save();

	            $request->session()->flash('success', 'Data berhasil diubah');

	            return redirect('prk_parent/daftar');
	        }
	    }

	    public function delete(Request $request,$id)
	    {
	    	$item = PrkParent::find($id);
	    	$item->delete();

	    	$request->session()->flash('success', 'Data berhasil dihapus');

	    	return redirect('prk_parent/daftar');
	    }
}
