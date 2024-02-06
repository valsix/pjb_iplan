<?php

namespace App\Http\Controllers\Pengendalian;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request; 
use App\Entities\StrategiBisnis; 
use App\Entities\Fase; 
use App\Entities\Distrik;
use App\Entities\Lokasi;
use App\Entities\User;
use App\Entities\Role;
use App\Entities\PgdlLRParentCode;
use DB;
use Input;
use Excel;
use PDF;

class KodeParentPosLRController extends Controller
{
    public function index(Request $rq) 
    {   $lrc = PgdlLRParentCode::orderBy('id','ASC')->get();
        // dd($lrc);   
        return view('pengendalian_input.kode_parent_pos_lr.index',compact('lrc'));
    }

    public function store(Request $request)
    {
        $id_lr = $request->id;
        $up = $request->up;
        $om = $request->om;
        foreach ($id_lr as $idx => $id) {
            $lrc = PgdlLRParentCode::find($id);
            $lrc->kode_parent_up = $up[$idx];
            $lrc->kode_parent_om = $om[$idx];
            $lrc->save();
        }

        $data = "Berhasil Disimpan";
        return $data;

    }

}
