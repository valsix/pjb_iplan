<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\StrategiBisnis;
use App\Entities\Distrik;
use App\Entities\Lokasi;
use App\Entities\Fase;
use App\Entities\Template;
use App\Entities\User;
use App\Entities\Role;
use Illuminate\Support\Facades\DB;
use PDF;
use Session;
use Excel;

class RincianBiayaAdministrasiController extends Controller
{
    public function Rincian_Biaya_Administrasi(Request $request)
    {
      $user_id = session('user_id');
      $user = User::find($user_id);
      $role_id = session('role_id');
      $role = Role::find($role_id);

      //kantor pusat
      if($role->is_kantor_pusat) {
        $sb = StrategiBisnis::all();
      }
      else {
        $sb = StrategiBisnis::where('id', $user->distrik->strategi_bisnis->id)->get();
      }

        $fase = Fase::all();
        $tahun = Template::select('tahun')->where('jenis_id', 7)->orWhere('jenis_id',1)->distinct()->get();

        $input_tahun = $request->input('tahun_anggaran');
        $input_sb = $request->input('strategi_bisnis');
        $input_distrik = $request->input('distrik');
        $input_lokasi = $request->input('lokasi');
        $input_fase = $request->input('fase');
        $input_draft = $request->input('draft_rkau');

        if ($request->input('strategi_bisnis') != NULL) {
            $input_sb = DB::table('strategi_bisnis')->select('name', 'id')->where('id', $request->input('strategi_bisnis'))->get()[0];
            $distrik = Distrik::select('name','id')->where('strategi_bisnis_id',$input_sb->id)->get();
        }
        if ($request->input('distrik') != NULL) {
            $input_distrik = DB::table('distrik')->select('name', 'id')->where('id', $request->distrik)->get()[0];
            $lokasi = Lokasi::select('name','id')->where('distrik_id',$input_distrik->id)->get();
        }
        if ($request->input('lokasi') != NULL) {
            $input_lokasi = DB::table('lokasi')->select('name', 'id')->where('id', $request->lokasi)->get()[0];
        }
        if ($request->input('fase') != NULL) {
            $input_fase = DB::table('fases')->select('name', 'id')->where('id', $request->fase)->get()[0];
        }
        if ($request->input('draft_rkau') != NULL) {
            $input_draft = DB::table('file_imports')->select('draft_versi', 'id', 'name')->where('id', $request->input('draft_rkau'))->get()[0];
            $drafts = $this->query_draft($input_sb->name, $input_lokasi->id, $input_tahun);
        }

        if ($input_lokasi != NULL && $input_tahun != NULL && $input_draft != NULL && $input_fase != NULL) {
            $count = DB::table('excel_datas')->join('file_imports','file_imports.id','=','excel_datas.file_import_id')->join('sheets','sheets.id','=','excel_datas.sheet_id')->where('sheets.name', 'like', 'I-ADM')->where('excel_datas.lokasi_id', $request->input('lokasi'))->where('excel_datas.row','>','12')->where('excel_datas.kolom','=','F')->where('file_imports.tahun', $request->input('tahun_anggaran'))->where('file_imports.id', $request->input('draft_rkau'))->where('file_imports.fase_id', $request->input('fase'))->count();

            $number = DB::table('excel_datas')->select('excel_datas.value', 'excel_datas.row', 'excel_datas.kolom')->join('file_imports','file_imports.id','=','excel_datas.file_import_id')->join('sheets','sheets.id','=','excel_datas.sheet_id')->where('sheets.name', 'like', 'I-ADM')->where('excel_datas.lokasi_id', $request->input('lokasi'))->where('excel_datas.row','>','12')->where('excel_datas.kolom','=','D')->where('file_imports.tahun', $request->input('tahun_anggaran'))->where('file_imports.id', $request->input('draft_rkau'))->where('file_imports.fase_id', $request->input('fase'))->orderBy('excel_datas.row', 'ASC')->get();

            $noprk = DB::table('excel_datas')->select('excel_datas.value', 'excel_datas.row', 'excel_datas.kolom')->join('file_imports','file_imports.id','=','excel_datas.file_import_id')->join('sheets','sheets.id','=','excel_datas.sheet_id')->where('sheets.name', 'like', 'I-ADM')->where('excel_datas.lokasi_id', $request->input('lokasi'))->where('excel_datas.row','>','12')->where('excel_datas.kolom','=','E')->where('file_imports.tahun', $request->input('tahun_anggaran'))->where('file_imports.id', $request->input('draft_rkau'))->where('file_imports.fase_id', $request->input('fase'))->orderBy('excel_datas.row', 'ASC')->get();

            $rincian = DB::table('excel_datas')->select('excel_datas.value', 'excel_datas.row', 'excel_datas.kolom')->join('file_imports','file_imports.id','=','excel_datas.file_import_id')->join('sheets','sheets.id','=','excel_datas.sheet_id')->where('sheets.name', 'like', 'I-ADM')->where('excel_datas.lokasi_id', $request->input('lokasi'))->where('excel_datas.row','>','12')->where('excel_datas.kolom','=','F')->where('file_imports.tahun', $request->input('tahun_anggaran'))->where('file_imports.id', $request->input('draft_rkau'))->where('file_imports.fase_id', $request->input('fase'))->orderBy('excel_datas.row', 'ASC')->get();

            $estimasi2017 = DB::table('excel_datas')->select('excel_datas.value', 'excel_datas.row', 'excel_datas.kolom')->join('file_imports','file_imports.id','=','excel_datas.file_import_id')->join('sheets','sheets.id','=','excel_datas.sheet_id')->where('sheets.name', 'like', 'I-ADM')->where('excel_datas.lokasi_id', $request->input('lokasi'))->where('excel_datas.row','>','12')->where('excel_datas.kolom','=','G')->where('file_imports.tahun', $request->input('tahun_anggaran'))->where('file_imports.id', $request->input('draft_rkau'))->where('file_imports.fase_id', $request->input('fase'))->orderBy('excel_datas.row', 'ASC')->get();

            $newEstimasi = [];
            foreach ($estimasi2017 as $key => $value) {
                $aa = (round($value->value));
                array_push($newEstimasi, $aa);
            }

            $rkap2018 = DB::table('excel_datas')->join('file_imports','file_imports.id','=','excel_datas.file_import_id')->join('sheets','sheets.id','=','excel_datas.sheet_id')->where('sheets.name', 'like', 'I-ADM')->where('excel_datas.lokasi_id', $request->input('lokasi'))->where('excel_datas.row','>','12')->where('excel_datas.kolom','=','H')->where('file_imports.tahun', $request->input('tahun_anggaran'))->where('file_imports.id', $request->input('draft_rkau'))->where('file_imports.fase_id', $request->input('fase'))->orderBy('excel_datas.row', 'ASC')->get();

            $newRkap = [];
            foreach ($rkap2018 as $key => $value) {
                $bb = number_format(round($value->value));
                array_push($newRkap, $bb);
            }



            if($request->download && $request->type){
                $judul='';
                if($request->type=='pdf'){
                    $fill = [];
                    $fill=array($request->get('tahun_anggaran'), $input_sb->name, $input_distrik->name, $input_lokasi->name, $input_fase->name, $input_draft->draft_versi);
                    $judul='Table Rincian Biaya Administrasi';

                    $pdf=PDF::loadView('output/rincian-biaya-administrasi-pdf', compact('sb', 'fase', 'input_sb', 'input_distrik', 'input_lokasi', 'input_tahun','input_fase', 'input_draft', 'noprk', 'rincian', 'newEstimasi', 'newRkap', 'number', 'count', 'judul', 'fill'));
                    // return $pdf->download('Report Rincian Biaya Pegawai ' .$input_tahun.'.pdf');
                    return $pdf->download('Report Rincian Biaya Administrasi '.$input_tahun.'.pdf');
                }
                else if($request->type=='excel'){
                    $array['judul']='Rincian Biaya Administrasi';
                    $array['judult1']='Rincian Biaya Administrasi';
                    $array['tahun']=[' ','Tahun', $input_tahun];
                    $array['struktur_bisnis']=[' ','Struktur Bisnis', $input_sb->name];
                    $array['distrik']=[' ','Distrik', $input_distrik->name];
                    $array['lokasi']=[' ','Lokasi', $input_lokasi->name];
                    $array['fase']=[' ','Fase', $input_fase->name];
                    $array['jenis']=[' ','Jenis Draft', $input_fase->name];
                    $array['draft']=[' ','Draft', $input_draft->draft_versi];
                    // dd($array);
                    $arrayNew = [];

                    $tmp = [];
                    $array1 = [' ', ' ', 'RINCIAN', 'PRAK REAL 2017', 'RKAP 2018'];
                    $arrayList = [];
                    $arrayList = ['No', 'No PRK', '1', '2', '3'];
                    // dd($number);
                    $tmp[0] = $array1;
                    $tmp[1] = $arrayList;

                    for ($i = 0 ; $i < $count ; $i++) {
                        $tmp[] = array(
                            'No' => $number[$i]->value,
                            'NoPRK' => $noprk[$i]->value,
                            '1' => $rincian[$i]->value,
                            '2' => number_format(round($estimasi2017[$i]->value),0,".","."),
                            '3' => number_format(round($rkap2018[$i]->value),0,".",".")
                        );
                    }
                    $array['tmp']=$tmp;
                    // dd($array);

                   Excel::create('Report Rincian Biaya Administrasi', function($excel) use($array) {
                        $excel->setTitle('Report Rincian Biaya Administrasi');
                        $excel->setCreator('Laravel')->setCompany('Profio', 'PJB');
                        $excel->setDescription('Rincian Biaya Administrasi');

                        $excel->sheet('Sheet1', function($sheet) use ($array) {
                            $sheet->row(1, $array['tahun']);
                            $sheet->row(2, $array['struktur_bisnis']);
                            $sheet->row(3, $array['distrik']);
                            $sheet->row(4, $array['lokasi']);
                            $sheet->row(5, $array['fase']);
                            $sheet->row(6, $array['jenis']);
                            $sheet->row(7, $array['draft']);

                            $sheet->row(8, array('', '',''));
                            $sheet->row(9, array('', '',''));

                            foreach ($array['tmp'] as $key => $value) {
                                $sheet->row($key+10, $value);
                            }


                            // $sheet->sheet('Sheet1', function($sheet2) use ($tmp) {
                            //     $sheet2->fromArray($tmp, null, 'A1', false, false);
                            // });
                        });
                    })->download('xlsx');
                }
            }
            else {
                return view('output/rincian-biaya-administrasi', compact('sb', 'fase', 'input_sb', 'input_distrik', 'input_lokasi', 'input_tahun','input_fase', 'input_draft', 'noprk', 'rincian', 'estimasi2017', 'rkap2018', 'number', 'count', 'newEstimasi', 'newRkap', 'tahun', 'drafts', 'distrik', 'lokasi'));
            }
        }

        return view('output/rincian-biaya-administrasi', compact('sb', 'fase', 'draft', 'input_tahun', 'input_sb', 'input_distrik', 'input_lokasi', 'input_fase', 'input_draft', 'number', 'noprk', 'rincian', 'estimasi2017', 'rkap2018', 'count', 'newEstimasi', 'newRkap', 'tahun', 'drafts', 'distrik', 'lokasi'));
    }

    public function Ajax($id)
    {
      $user_id = session('user_id');
      $user = User::find($user_id);
      $role_id = session('role_id');
      $role = Role::find($role_id);

      //kantor pusat
      if($role->is_kantor_pusat) {
        $ds = Distrik::where('strategi_bisnis_id', $id)->select("name","id")->get();
      }
      else {
        $ds = Distrik::where('id', $user->distrik_id)->select("name","id")->get();
      }

      return json_encode($ds);
    }

    public function myformAjax2($id)
    {
        $lokasi = Lokasi::where('distrik_id', $id)->select("name", "id")->get();

        return json_encode($lokasi);
    }

    public function ajax_draft_rkau($id_lokasi, $id_tahun)
    {
        $draft_rkau = DB::select("select distinct f.id, f.draft_versi
                                    from file_imports f
                                    join templates t on f.template_id = t.id
                                    join excel_datas e on e.file_import_id = f.id
                                    where t.jenis_id=1 and e.lokasi_id = ".$id_lokasi." and t.tahun= ".$id_tahun."
                                    group by f.id, f.draft_versi;");

        return json_encode($draft_rkau);
    }

    public function query_draft($strategi_bisnis, $id_lokasi, $id_tahun){
        $draft= DB::select("select distinct f.id, f.draft_versi
                            from file_imports f
                            join templates t on f.template_id = t.id
                            join excel_datas e on e.file_import_id = f.id
                            where t.jenis_id=1 and e.lokasi_id = ".$id_lokasi." and t.tahun= ".$id_tahun."
                            group by f.id, f.draft_versi;");
        return $draft;
    }

    // public function exportToExcel() {

    //     $data = Session::get('input');
    //     // dd($data);
    //     $input_tahun = ($data['tahun_anggaran']);
    //     $input_lokasi = ($data['lokasi']);
    //     $input_draft = ($data['draft_rkau']);
    //     $input_fase = ($data['fase']);

    //     if ($input_lokasi != NULL && $input_tahun != NULL && $input_draft != NULL) {

    //         $count = DB::table('excel_datas')->join('file_imports','file_imports.id','=','excel_datas.file_import_id')->join('sheets','sheets.id','=','excel_datas.sheet_id')->where('sheets.name', 'like', 'I-ADM')->where('excel_datas.lokasi_id', $input_lokasi)->where('excel_datas.row','>','12')->where('excel_datas.kolom','=','F')->where('excel_datas.file_import_id', $input_draft)->where('file_imports.tahun', $input_tahun)->where('file_imports.id', $input_draft)->where('file_imports.fase_id', $input_fase)->count();

    //         $number = DB::table('excel_datas')->join('file_imports','file_imports.id','=','excel_datas.file_import_id')->join('sheets','sheets.id','=','excel_datas.sheet_id')->where('sheets.name', 'like', 'I-ADM')->where('excel_datas.lokasi_id', $input_lokasi)->where('excel_datas.row','>','12')->where('excel_datas.kolom','=','D')->where('file_imports.tahun', $input_tahun)->where('file_imports.id', $input_draft)->where('file_imports.fase_id', $input_fase)->get();

    //         $noprk = DB::table('excel_datas')->join('file_imports','file_imports.id','=','excel_datas.file_import_id')->join('sheets','sheets.id','=','excel_datas.sheet_id')->where('sheets.name', 'like', 'I-ADM')->where('excel_datas.lokasi_id', $input_lokasi)->where('excel_datas.row','>','12')->where('excel_datas.kolom','=','E')->where('file_imports.tahun', $input_tahun)->where('file_imports.id', $input_draft)->where('file_imports.fase_id', $input_fase)->get();

    //         $rincian = DB::table('excel_datas')->join('file_imports','file_imports.id','=','excel_datas.file_import_id')->join('sheets','sheets.id','=','excel_datas.sheet_id')->where('sheets.name', 'like', 'I-ADM')->where('excel_datas.lokasi_id', $input_lokasi)->where('excel_datas.row','>','12')->where('excel_datas.kolom','=','F')->where('file_imports.tahun', $input_tahun)->where('file_imports.id', $input_draft)->where('file_imports.fase_id', $input_fase)->get();

    //         $estimasi2017 = DB::table('excel_datas')->join('file_imports','file_imports.id','=','excel_datas.file_import_id')->join('sheets','sheets.id','=','excel_datas.sheet_id')->where('sheets.name', 'like', 'I-ADM')->where('excel_datas.lokasi_id', $input_lokasi)->where('excel_datas.row','>','12')->where('excel_datas.kolom','=','G')->where('file_imports.tahun', $input_tahun)->where('file_imports.id', $input_draft)->where('file_imports.fase_id', $input_fase)->get();

    //         $rkap2018 = DB::table('excel_datas')->join('file_imports','file_imports.id','=','excel_datas.file_import_id')->join('sheets','sheets.id','=','excel_datas.sheet_id')->where('sheets.name', 'like', 'I-ADM')->where('excel_datas.lokasi_id', $input_lokasi)->where('excel_datas.row','>','12')->where('excel_datas.kolom','=','H')->where('file_imports.tahun', $input_tahun)->where('file_imports.id', $input_draft)->where('file_imports.fase_id', $input_fase)->get();



    //         $arrayNew = [];

    //         $tmp = [];
    //         $array1 = [' ', ' ', 'RINCIAN', 'PRAK REAL n-1', 'RKAP n'];
    //         $arrayList = [];
    //         $arrayList = ['No', 'No PRK', '1', '2', '3'];
    //         // dd($number);
    //         $tmp[0] = $array1;
    //         $tmp[1] = $arrayList;

    //         for ($i = 0 ; $i < $count ; $i++) {
    //             $tmp[] = array(
    //                 'No' => $number[$i]->value,
    //                 'NoPRK' => $noprk[$i]->value,
    //                 '1' => $rincian[$i]->value,
    //                 '2' => $estimasi2017[$i]->value,
    //                 '3' => $rkap2018[$i]->value
    //             );
    //         }
    //         // dd($tmp);

    //         Excel::create('Rincian Biaya Administrasi', function($excel) use($tmp) {
    //             $excel->setTitle('Rincian Biaya Pegawai');
    //             $excel->setCreator('Laravel')->setCompany('Profio', 'PJB');
    //             $excel->setDescription('Rincian Biaya Administrasi');

    //             $excel->sheet('Sheet1', function($sheet) use($tmp) {
    //                 $sheet->fromArray($tmp, null, 'A1', false, false);
    //             });
    //         })->download('xlsx');
    //     }
    // }

}
