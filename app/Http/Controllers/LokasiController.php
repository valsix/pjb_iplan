<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Entities\Lokasi;
use App\Entities\Distrik;
use App\Entities\Strategi_bisnis;
use App\Entities\Role;
use App\Entities\Jenis;
use App\Entities\LokasiJenis;


class LokasiController extends Controller
{
    public function index()
    {
    	$lokasi = Lokasi::all();
        $data = ['lokasi' => $lokasi];
    	$Sb = Strategi_bisnis::all();

        return view('lokasi.daftar_lokasi', $data, compact('Sb'));
    }

    public function Ajax($id)
    {
    	$ds = Distrik::where('strategi_bisnis_id', $id)->select("name","id")->get();

    	return json_encode($ds);
	}

    public function myformAjax2($id)
    {
    	$lokasi = Lokasi::where('distrik_id', $id)->select("name", "id", "min_uploaded_form")->get();

    	return json_encode($lokasi);
    }


    public function create(Request $request)
	{
		if ($request->isMethod('get')) 
		{
			$item['strategi_bisnis'] = Strategi_bisnis::all();
			$item['distrik'] = Distrik::all();

			$Sb = Strategi_bisnis::all();

			$jenis = Jenis::all();
			
			return view('lokasi.tambah_lokasi', $item,compact('jenis','Sb'));
		}
		elseif ($request->isMethod('post')) {
			$this->validate($request, [
                    'strategi_bisnis' => 'required',
                    'distrik' => 'required',
                    'name' => 'required',
                    'min_uploaded_form' => 'required',
                ]);
			
			$formperlokasi = Input::get('formke');
			if(count($formperlokasi) < Input::get('min_uploaded_form'))
			{
                return redirect()->back()->with('error','Jumlah form yang dimasukkan tidak boleh kurang dari minimal form di-upload');  
			}
			// $this->validate($request, [
   //                  'strategi_bisnis_id' ,
   //                  'distrik_id' ,
   //                  'name' , 
   //              ]);

			$item = array(
						'distrik_id' => Input::get('distrik'),
						'name' => Input::get('name'),
						'min_uploaded_form' => Input::get('min_uploaded_form'));

			$transaction = Lokasi::create($item);
			// 20191226 Hapus all()-> di row 81
			$lokid = Lokasi::where('name', '=', input::get('name'))
								  ->orderBy('created_at', 'desc')
								  ->value('id');
			
			if(!empty($formperlokasi))
			{
				foreach ($formperlokasi as $fpl) {
					$item = array(
								'lokasi_id' => $lokid,
								'jenis_id' => $fpl);

					$createitem = LokasiJenis::create($item);
				}
			}
				
            if($transaction)
            {   	
                $request->session()->flash('success', 'Data berhasil ditambahkan');
            }
			return redirect('lokasi/daftar');
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request,$id)
	{
		//
		if ($request->isMethod("get")) {
			# code...
			$item['strategi_bisnis'] = Strategi_bisnis::all();
			$item['distrik'] = Distrik::all();
			$item['lokasi'] = Lokasi::find($id);
			$jenis = Jenis::all();
			// $lokasijenis = LokasiJenis::all()->where('lokasi_id', $id);
			$lokasijenis = DB::table('lokasi_jenis')->leftJoin('jenis', 'lokasi_jenis.jenis_id', '=', 'jenis.id')
													->where('lokasi_id', '=', $id)
													->get();
			return view('lokasi.edit_lokasi', $item, compact('jenis', 'lokasijenis'));
		}
		elseif ($request->isMethod('post')) {
			$this->validate($request, [
                    'name' => 'required',
                    'min_uploaded_form' => 'required'
                ]);

			$formperlokasi = Input::get('formke');
			if(count($formperlokasi) < Input::get('min_uploaded_form'))
			{
                return redirect()->back()->with('error','Jumlah form yang dimasukkan tidak boleh kurang dari minimal form di-upload');  
			}

			$item = Lokasi::find($id);
			$item->name = Input::get('name');
			$item->min_uploaded_form = Input::get('min_uploaded_form');
			$item->save();

			DB::table('lokasi_jenis')->where('lokasi_id', '=', $id)->delete();
			
			if(!empty($formperlokasi))
			{
				foreach ($formperlokasi as $fpl) {
					$item = array(
								'lokasi_id' => $id,
								'jenis_id' => $fpl);

					$createitem = LokasiJenis::create($item);
				}
			}

			$request->session()->flash('success', 'Data berhasil diubah');

			return redirect('lokasi/daftar');
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

	public function delete(Request $request,$id)
	{
		$item = Lokasi::find($id);
		$item->delete();

		$request->session()->flash('success', 'Data berhasil diubah');

		return redirect('lokasi/daftar');
	}

	// public function search(Request $Request)
	// {
	// 	$cari = $request->get('search');
	// 	$golek = Lokasi::where('judul', 'LIKE', '%'.$cari.'%')->paginate(10);
	// 	return view('lokasi.daftar_lokasi', compact('golek'));
	// }
}


//cara 1
// $q = Lokasi::query()

// if (Input::has('lokasi')) {
// 	$q->where('lokasi', Input::get('lokasi'));
// }elseif () {
// 	# code...
// }
// else{
// 	$q = Lokasi::all();
// }

// $data = $q;
// return view ('nameview', $data);

// //cara 2
// $q = Lokasi::query()

// if (Input::has('lokasi')) {
// 	$q->where('lokasi', function($query){
// 		$query->whereHas('distrik')
// 	});
// }


