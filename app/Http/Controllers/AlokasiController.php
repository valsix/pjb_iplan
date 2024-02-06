<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Entities\Alokasi;

class AlokasiController extends Controller
{
    	public function index()
    	{
    		$alokasi = Alokasi::all();
        	$data = ['alokasi' => $alokasi];

        	// dd($data);
        	return view('alokasi', $data);
    	}
    	
    	public function create(Request $request)
	    {
	        if ($request->isMethod('get')) {
	            # code...
	            return view('tambah_alokasi');
	        }
	        elseif ($request->isMethod('post')) {
	            $this->validate($request, [
                    'nama' => 'required|unique:alokasi,nama',
                ]);
	            $item = array('nama' => Input::get('nama'));
	            // dd($item);
	            $transaction = Alokasi::create($item);

                if($transaction)
                {
                    $request->session()->flash('success', 'Data berhasil ditambahkan');
                }

	            return redirect('alokasi');
	        }
	    }

	    public function update(Request $request,$id)
	    {
	    	if ($request->isMethod('get')) {
	            # code...
	            $item['alokasi'] = Alokasi::find($id);
	            return view('edit_alokasi', $item);
	        }
	        elseif ($request->isMethod('post')) {
	            $this->validate($request, [
                    'nama' => 'required|unique:alokasi,nama',
                ]);
	            $item = Alokasi::find($id);
	            $item->nama = Input::get('nama');
	            $item->save();

	            $request->session()->flash('success', 'Data berhasil diubah');

	            return redirect('alokasi');
	        }
	    }

	    public function delete(Request $request,$id)
	    {
	    	$item = Alokasi::find($id);
	    	$item->delete();

	    	$request->session()->flash('success', 'Data berhasil dihapus');

	    	return redirect('alokasi');
	    }
}
