<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entites\Role;
use App\Entities\User;
use App\Entities\Distrik;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show the application dashboard     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = session('user_id');
        $user = User::find($user_id);
        $firstRole = $user->setFirstCurrentRole();
        return view('index');
    }

    public function switchrole($roles_id)
    {
        session(['role_id' => $roles_id]);
        $user_id = session('user_id');
        $current_user = User::find($user_id);
        $current_user->current_id_roles = $roles_id;
        $current_user->save();
        
        return redirect('/');
    }

    public function switchsb($sb_id)
    {
        $user_id = session('user_id');
        $current_user = User::find($user_id);
        $kode_distrik = $current_user->distrik->code1;
        $switch_distrik_id = Distrik::where('code1', $kode_distrik)->where('strategi_bisnis_id', $sb_id)->first()->id;
        $current_user->distrik_id = $switch_distrik_id;
        $current_user->save();
        
    	return redirect('/');
    }

    public function switchrolegrupdiv($grupdivs_id)
    {
        session(['grupdiv_id' => $grupdivs_id]);
        $user_id = session('user_id');
        $current_user = User::find($user_id);
        $current_user->current_grupdiv_id = $grupdivs_id;
        $current_user->save();
        
        return redirect('/');
    }

    public function pagenotfound()
    {
        return view('error.404');
    }

    public function noaccess()
    {
        return view('error.noaccess');
    }
}
