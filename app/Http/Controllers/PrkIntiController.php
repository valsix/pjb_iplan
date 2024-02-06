<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Entities\PrkInti;
use App\Entities\PrkParent; 

class PrkIntiController extends Controller
{
    public function index()
    {
        $prk_inti = PrkInti::all();

        $data = ['prk_inti' => $prk_inti];

        return view('prk_inti.daftar', $data);
    }

    public function tambah(Request $request)
    {
        if ($request->isMethod('get')) {
                $item ['prk_inti'] = PrkInti::all();
                $item ['prk_parent'] = PrkParent::all();
                return view('prk_inti.tambah', $item);
            }
            elseif ($request->isMethod('post')) {
                // dd(Input::get());
                 $this->validate($request, [
                    'desc_prk_inti' => 'required',
                    'prk_parent_id' => 'required',
                    'identity_prk_inti' => 'required|unique:prk_inti',
                ]);

                $item = array(
                    'desc_prk_inti' => Input::get('desc_prk_inti'),
                    'prk_parent_id' => Input::get('prk_parent_id'),
                    'identity_prk_inti' => Input::get('identity_prk_inti'));
                
                $transaction = PrkInti::create($item);

                if($transaction)
                {
                    $request->session()->flash('success', 'Data berhasil ditambahkan');
                }

                return redirect('prkinti/daftar');
            }
    }
    public function update(Request $request,$id)
    {
        if($request->isMethod("get"))
        {
            $item['prk_inti'] = PrkInti::find($id);
            $item['prk_parent'] = PrkParent::all();
            return view('prk_inti.edit',$item);
        }
        elseif ($request->isMethod('post')) {
            # code...
            $this->validate($request, [
                    'desc_prk_inti' => 'required',
                    'identity_prk_inti' => 'required|unique:prk_inti',
                ]);
            $item = PrkInti::find($id);
            $item->desc_prk_inti = Input::get('desc_prk_inti');
            $item->identity_prk_inti= Input::get('identity_prk_inti');
            $item->save();

            $request->session()->flash('success', 'Data berhasil diubah');

            return redirect('prkinti/daftar');
        }
    }
    public function delete(Request $request,$id)
    {
        $item = PrkInti::find($id);
        $item->delete();

        $request->session()->flash('success', 'Data berhasil dihapus');

        return redirect('prkinti/daftar');
    }

}
