



<?php

namespace App\Http\Controllers;

use Request;
use Illuminate\Support\Facades\Input;
use App\Entities\Risk_profile;
use App\Entities\Lokasi;
use App\Entities\Strategi_bisnis;
use App\Entities\Distrik;

class MasterRiskProfileController extends Controller
{
    //
    public function index()
    	{
    		$riskprofile = Risk_profile::all();
        	$data = ['riskprofile' => $riskprofile];
        	
        	$Sb = Strategi_bisnis::all();

        	return view('risk.riskprofile', $data, compact('Sb'));
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


   	public function create()
	    {
	        if (Request::isMethod('get')) {
	            # code...
	            $item ['lokasi'] = Lokasi::all();
	            return view('risk.tambah_risk', $item); //folder
	        }
	        elseif (Request::isMethod('post')) {
	            # code...
	            $item = array(
	            			  'lokasi_id' => Input::get('lokasi_id'),
	            			  'risk_tag' => Input::get('risk_tag'),
	            			  'risk_event' => Input::get('risk_event'),
	            			  'risk_corporate' => Input::get('risk_corporate'),
	            			  'possibility_level' => Input::get('possibility_level'),
	            			  'impact_level' => Input::get('impact_level'),
	            			  'risk_level' => Input::get('risk_level'),
	            			  );
	            // dd($item);
	            $transaction = Risk_profile::create($item);

                if($transaction)
                {
                    Request::session()->flash('success', 'Data berhasil ditambahkan');
                }

	            return redirect('risk_profile'); //sesuai route
	        }
	    }

	public function update($id)
	    {
	    	if (Request::isMethod('get')) {
	            # code...
	            $item['lokasi'] = Lokasi::all();
	            $item['riskprofile'] = Risk_profile::find($id);
	            return view('risk.edit_risk', $item); //folder
	        }
	        elseif (Request::isMethod('post')) {
	            # code...
	            $item = Risk_profile::find($id);
	            $item->risk_tag = Input::get('risk_tag');
	            $item->risk_event = Input::get('risk_event');
	            $item->risk_corporate = Input::get('risk_corporate');
	            $item->possibility_level = Input::get('possibility_level');
	            $item->impact_level = Input::get('impact_level');
	            $item->risk_level = Input::get('risk_level');
	            $item->save();

	            Request::session()->flash('success', 'Data berhasil diubah');

	            return redirect('risk_profile'); //sesuai route
	        }
	    }

	public function delete($id)
	    {
	    	$item = Risk_profile::find($id);
	    	$item->delete();

	    	Request::session()->flash('success', 'Data berhasil dihapus');

	    	return redirect('risk_profile');
	    }
}