<?php namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Entities\Fase;
use App\Entities\Role;
use App\Entities\Approval;

class ApprovalController extends Controller
{
    public function index()
    {
    	$approval = Approval::all();

    	// dd($approval);
        $data = ['approval' => $approval];

        return view('approval.daftar_approval', $data);
    }

    public function create(Request $request)
	{
		if ($request->isMethod('get')) 
		{
			$item['fases'] = Fase::all();
			$item['roles'] = Role::all();
			return view('approval.tambah_approval', $item);
		}
		elseif ($request->isMethod('post')) {
			# code...
			// dd(Input::get());

			$this->validate($request, [
                    'fase_id' => 'required',
                    'role_id' => 'required',
                    'urutan' => 'required|unique:approval',
                ]);

			$item = array(
						'fase_id' => Input::get('fase_id'),
						'role_id' => Input::get('role_id'),
						'urutan' => Input::get('urutan'),
						'enabled' => 1);
			 $transaction = Approval::create($item);

			 if($transaction)
                {
                    $request->session()->flash('success', 'Data berhasil ditambahkan');
                }

			return redirect('approval/daftar');
		}
	}

	public function update(Request $request,$id)
	{
		//
		if ($request->isMethod("get")) {
			# code...
			$item['fases'] = Fase::all();
			$item['roles'] = Role::all();
			$item['approval'] = Approval::find($id);
			return view('approval.edit_approval', $item);
		}
		elseif ($request->isMethod('post')) {
    		# code...
    		// dd(Input::get('nama'));
            $this->validate($request, [
                    'fase_id' => 'required',
                    'role_id' => 'required',
                    'urutan' => 'required|unique:approval',
                ]);
            
    		$item = Approval::find($id);
    		$item->nama = Input::get('urutan');
    		$item->save();

            $request->session()->flash('success', 'Data berhasil diubah');

    		return redirect('approval/daftar');
    	}
		
	}

	public function delete(Request $request,$id)
	{
		$item = Approval::find($id);
		$item->delete();

		$request->session()->flash('success', 'Data berhasil dihapus');

		return redirect('approval/daftar');
	}
}
