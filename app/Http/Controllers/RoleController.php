<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Entities\Role;
use App\Entities\Permission;
use App\Entities\User; 

class RoleController extends Controller
{
    public function index()
    {
    	$roles = Role::get();
        return view('role.daftar', compact('roles'));
    }

    public function tambah(Request $request)
    {
        if ($request->isMethod('get')) {
    		$roles = null;
            $permission = Permission::all();
            $permission_role = null;
            $users = null;
            return view('role.tambah', compact('roles', 'permission', 'permission_role', 'users'));
    	}
    	elseif ($request->isMethod('post')) {
    		$this->validate($request, [
            'nama' => 'required',
            'alias' => 'required',
        ], [
            'required' => 'Kolom ini tidak boleh kosong',
        ]);

        $roles = new Role();
        $roles->display_name = $request->nama;
        $roles->name = $request->alias;
        $roles->description = $request->deskripsi;
        $roles->save();

        $menu_akseses = $request->menu_akses;
        if (count($menu_akseses) > 0) {
            foreach ($menu_akseses as $menu_akses) {
                $permissionRole = new PermissionRole();
                $permissionRole->permission_id = $menu_akses;
                $permissionRole->role_id = $roles->id;
                $permissionRole->save();
            }
        }

        return redirect()->route('role.daftar')->with('message', 'Data berhasil disimpan');
    	}
    }

    public function edit($id)
    {
    	
    }

    public function delete($id)
    {
    	$item = Role::find($id);
    	$item->delete();
        return redirect('role/daftar');
    }
}
