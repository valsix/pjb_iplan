<?php

namespace App\Http\Controllers;

use Request;
use Illuminate\Support\Facades\Input;
use App\Entities\Prk;
use App\Entities\Lokasi;

class PrkController extends Controller
{
    public function index()
    {
        $prk = Prk::all();

        // dd($distrik);
        $data = ['prk' => $prk];

        return view('prk', $data);
    }

    public function tambah()
    {
        if (Request::isMethod('get')) {
                # code...
                $item ['lokasi'] = Lokasi::all();
                return view('tambah_prk', $item);
            }
            elseif (Request::isMethod('post')) {
                //dd(Input::get('tahun'));
                $item = array(
                    'kode_distrik' => Input::get('kode_distrik'),
                    'lokasi_id' => Input::get('lokasi_id'),
                    'tahun' => Input::get('tahun'),
                    'identity_parent' => Input::get('identity_parent'),
                    'identity_inti' => Input::get('identity_inti'),
                    'identity_kegiatan' => Input::get('identity_kegiatan'),
                    'ket_identity_inti' => Input::get('ket_identity_inti'),
                    'ket_identity_kegiatan' => Input::get('ket_identity_kegiatan'));
                // dd($item);
                $transaction = Prk::create($item);

                if($transaction)
                {
                    Request::session()->flash('success', 'Data berhasil ditambahkan');
                }
                return redirect('prk/daftar');
            }
    }
    public function update($id)
    {
        if(Request::isMethod("get"))
        {
            $item ['lokasi'] = Lokasi::all();
            $item['prk'] = Prk::find($id);
            return view('edit_prk',$item);
        }
        elseif (Request::isMethod('post')) {
            # code...
            $item = Prk::find($id);
            $item->kode_distrik = Input::get('kode_distrik');
            $item->tahun= Input::get('tahun');
            $item->identity_parent= Input::get('identity_parent');
            $item->identity_inti= Input::get('identity_inti');
            $item->identity_kegiatan= Input::get('identity_kegiatan');
            $item->ket_identity_inti= Input::get('ket_identity_inti');
            $item->ket_identity_kegiatan= Input::get('ket_identity_kegiatan');
            $item->save();

            Request::session()->flash('success', 'Data berhasil diubah');

            return redirect('prk/daftar');
        }
    }
    public function delete($id)
    {
        $item = Prk::find($id);
        $item->delete();

        Request::session()->flash('success', 'Data berhasil dihapus');

        return redirect('prk/daftar');
    }
    public function search(Request $request)
    {
        $search = $request->get('search');
        $distrik = pjb::where('nama','LIKE', '%'.$search.'%')->paginate(10);
        return view('distrik.index', compact('distrik'));
    }
}
