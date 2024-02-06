<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\Permission;
use Illuminate\Support\Facades\Input;

class PermissionController extends Controller
{
    public function index()
    {
    	// $permission = Permission::all();
     //    $data = ['permission' => $permission];

     //    // dd($data);
     //    return view('akses/permission', $data);

         $params = null;

        $is_menu_filter = Input::get("is_menu_filter");
        if (isset($is_menu_filter) && $is_menu_filter != "") {
            $params['is_menu'] = [$is_menu_filter];
        } else {
            $is_menu_filter = null;
        }

        if (isset($params) > 0) {
            $permission = Permission::where($params)->get();
        } else {
            $permission = Permission::get();
        }

        return view('akses.permission', compact('permission', 'is_menu_filter'));
    }
    public function create()
	    {
	        if (Request::isMethod('get')) {
	            # code...
	            return view('akses.create_permission');
	        }
	        elseif (Request::isMethod('post')) {
	            # code...
	            $item = array(
	            	'name' => Input::get('name'),
	            	'display_name' => Input::get('display_name'),
	            	'description' => Input::get('description'));
	            // dd($item);
	            Permission::create($item);
	            return redirect('akses/daftar');
	        }
	    }
	 public function update($id)
	    {
	    	if (Request::isMethod('get')) {
	            # code...
	            $item['permission'] = Permission::find($id);
	            return view('akses.edit_permission', $item);
	        }
	        elseif (Request::isMethod('post')) {
	            # code...
	            $item = Permission::find($id);
	            $item->name = Input::get('name');
	            $item->display_name = Input::get('display_name');
	            $item->description = Input::get('description');
	            $item->save();
	            return redirect('akses/daftar');
	        }
	    }

	    public function delete($id)
	    {
	    	$item = Permission::find($id);
	    	$item->delete();
	    	return redirect('akses/daftar');
	    }
}
