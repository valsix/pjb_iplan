<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input; 
use App\Entities\Rencana_kerja;
use App\Entities\Lokasi;
use App\Entities\Distrik;
use App\Entities\Strategi_bisnis;

class RencanaKerjaController extends Controller
{
    //
    public function index()
    	{
    		$rencanakerja = Rencana_kerja::all();
        	$data = ['rencanakerja' => $rencanakerja];
        	
        	$Sb = Strategi_bisnis::all();

        	return view('rk.rencanakerja', $data, compact('Sb'));
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
	            $item ['lokasi'] = Lokasi::all();
	            return view('rk.tambah_rencana', $item); //folder
	        }
	        elseif ($request->isMethod('post')) {
	            $this->validate($request, [
                    'lokasi_id' => 'required',
                    'tahun_anggaran' => 'required',
                    'name_unit' => 'required',
                    'satuan_unit' => 'required',
                    'rkap_n_1' => 'required',
                    'prak_real_n_1' => 'required',
                    'rkap_n' => 'required',
                ]);
                // dd(Input::all());
	            $item = array(
	            			  'lokasi_id' => Input::get('lokasi_id'),
	            			  'tahun_anggaran' => Input::get('tahun_anggaran'),
	            			  'name_unit' => Input::get('name_unit'),
	            			  'satuan_unit' => Input::get('satuan_unit'),
	            			  'rkap_n_1' => Input::get('rkap_n_1'),
	            			  'prak_real_n_1' => Input::get('prak_real_n_1'),
	            			  'rkap_n' => Input::get('rkap_n'),
	            			  );
	            // dd($item);
	            $transaction = Rencana_kerja::create($item);

                if($transaction)
                {
                    $request->session()->flash('success', 'Data berhasil ditambahkan');
                }

	            return redirect('rencana_kerja'); //sesuai route
	        }
	    }

	public function update(Request $request,$id)
	    {
	    	if ($request->isMethod('get')) {
	            # code...
	            $item['lokasi'] = Lokasi::all();
	            $item['rencanakerja'] = Rencana_kerja::find($id);
	            return view('rk.edit_rencana', $item); //folder
	        }
	        elseif ($request->isMethod('post')) {
	            $this->validate($request, [
                    'tahun_anggaran' => 'required|unique:rencanakerja,tahun_anggaran',
                    'name_unit' => 'required|unique:rencanakerja,name_unit',
                    'satuan_unit' => 'required|unique:rencanakerja,satuan_unit',
                    'rkap_n_1' => 'required|unique:rencanakerja,rkap_n_1',
                    'prak_real_n_1' => 'required|unique:rencanakerja,prak_real_n_1',
                    'rkap_n' => 'required|unique:rencanakerja,rkap_n',
                ]);
	            $item = Rencana_kerja::find($id);
	            $item->tahun_anggaran = Input::get('tahun_anggaran');
	            $item->name_unit = Input::get('name_unit');
	            $item->satuan_unit = Input::get('satuan_unit');
	            $item->rkap_n_1 = Input::get('rkap_n_1');
	            $item->prak_real_n_1 = Input::get('prak_real_n_1');
	            $item->rkap_n = Input::get('rkap_n');
	            $item->save();

	            $request->session()->flash('success', 'Data berhasil diubah');

	            return redirect('rencana_kerja'); //sesuai route
	        }
	    }

	public function delete(Request $request,$id)
	    {
	    	$item = Rencana_kerja::find($id);
	    	$item->delete();

	    	$request->session()->flash('success', 'Data berhasil dihapus');

	    	return redirect('rencana_kerja');
	    }
}
