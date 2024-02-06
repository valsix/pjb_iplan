<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Entities\Permission;
use App\Entities\PermissionRole;
use App\Entities\Role;
use App\Entities\User;
use App\Entities\UserRole;
use Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Facades\Input;

class UserController extends Controller
{
     public function index()
    {
    	$users = User::get();

        return view('user.daftar_user', compact('users'));
    }

    public function create(Request $request)
	{
		if (Request::isMethod('get')) 
		{
			$user = null;
	        $roles = Role::get();
	        $current_roles = null;
	        return view('user.tambah_user', compact('user', 'roles', 'current_roles'));
		}
		elseif (Request::isMethod('post')) {
			$inputRules = [
			'nama' => 'required',
            'email' => 'required',
            'username' => 'required',
            'password' => 'required',
         
        ];

        $customAttributes = [
            'nama' => 'required',
            'email' => 'required',
            'username' => 'required',
            'password' => 'required',
        ];

        $validator = $this->getValidationFactory()->make($request->all(), $inputRules, [], $customAttributes);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $user = new User();
        $user->nama = $request->nama;
        $user->email = ($request->has('email')) ? $request->email : '';
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->save();

        $i = 0;
        $posisi = $request->posisi;
        foreach ($posisi as $row) {
            $data = array('user_id' => $user->id, 'role_id' => $posisi[$i]);
            $i++;
            DB::table('role_user')->insert($data);
        }

        return redirect()->route('user.daftar_user')->with('message', 'Data berhasil disimpan');
		}
	}

	public function update(Request $request, $id)
	{
		if (Request::isMethod("get")) {
			$user = User::findOrFail($id);
	        $roles = Role::get();
	        $current_roles = DB::table('roles')
	            ->join('role_user', 'role_user.role_id', '=', 'roles.id')
	            ->where('role_user.user_id', $id)
	            ->get();
	        return view('user.daftar_user', compact('user', 'roles', 'current_roles'));
		}
		elseif (Request::isMethod('post')) {
			$inputRules = [
            'nama' => 'required',
            'email' => 'required',
            'username' => 'required',
            'password' => 'required',
        ];

        $customAttributes = [
            'nama' => 'required',
            'email' => 'required',
            'username' => 'required',
            'password' => 'required',
        ];

        $validator = $this->getValidationFactory()->make($request->all(), $inputRules, [], $customAttributes);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $user = User::findOrFail($id);
        $user->nama = $request->nama;
        $user->email = ($request->has('email')) ? $request->email : '';
        $user->username = $request->username;
        if ($request->has('password')) {
            if ($request->password != '') {
                $user->password = bcrypt($request->password);
            }
        }
        $user->save();

        
        $k = 0;
        $data_posisi = $request->posisi;
        foreach ($data_posisi as $row) {
            //echo $data_posisi[$k].',';
            //checking data
            $data_check = DB::table('role_user')->where('user_id', $id)->where('role_id', $data_posisi[$k])->get();
            if ($data_check == null) {
                DB::table('role_user')->insert(array('user_id' => $id, 'role_id' => $data_posisi[$k]));
            }
            $k++;
        }

        return redirect()->route('user.daftar_user')->with('message', 'Data berhasil diubah');
		}
	}

	public function delete($id)
	{
		$item = User::find($id);
		$item->delete();

		return redirect('user/daftar');
	}



}
