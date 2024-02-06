<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Entities\JenisBahanBakar;

class JenisBahanBakarController extends Controller
{ 
    public function index()
    {
    	$jenis_bahan_bakar = JenisBahanBakar::all();

        $data = ['jenis_bahan_bakar' => $jenis_bahan_bakar];

        return view('bahanbakar.daftar', $data);
    }

    public function tambah(Request $request)
    {
    	if ($request->isMethod('get')) {
    		# code...
            $item ['jenis_bahan_bakar'] = JenisBahanBakar::all();
    		return view('bahanbakar.tambah', $item);
    	}
    	elseif ($request->isMethod('post')) {
    		# code...
    		/*dd(Input::get('nama'));*/
            $this->validate($request, [
                    'nama' => 'required|unique:jenis_bahan_bakar',
                ]);

    		$item = array(
                          'nama' => Input::get('nama')
                          );
    		
            $transaction = JenisBahanBakar::create($item);

                if($transaction)
                {
                    $request->session()->flash('success', 'Data berhasil ditambahkan');
                }

    		return redirect('bahanbakar/daftar');
    	}
    }

    public function edit(Request $request,$id)
    {
    	/*$item = Bahan::find($id);
    	$item->nama = "minyak";
    	$item->dave();*/
    	if ($request->isMethod('get')) {
    		# code...
    		$item ['jenis_bahan_bakar'] = JenisBahanBakar::find($id);
    		return view('bahanbakar.edit', $item);
    	}
    	elseif ($request->isMethod('post')) {
    		# code...
    		// dd(Input::get('nama'));
            $this->validate($request, [
                    'nama' => 'required|unique:jenis_bahan_bakar',
                ]);
            
    		$item = JenisBahanBakar::find($id);
    		$item->nama = Input::get('nama');
    		$item->save();

            $request->session()->flash('success', 'Data berhasil diubah');

    		return redirect('bahanbakar/daftar');
    	}
    }

    public function delete(Request $request,$id)
    {
    	$item = JenisBahanBakar::find($id);
    	$item->delete();

        $request->session()->flash('success', 'Data berhasil dihapus');

        return redirect('bahanbakar/daftar');
    }

}
