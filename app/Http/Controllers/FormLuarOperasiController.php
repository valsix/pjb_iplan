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
Use DB;
Use PDF;
use Excel;

class FormLuarOperasiController extends Controller
{
    public function Form_luar_operasi(Request $request)
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
        $input_tahun = NULL;
        $input_sb = NULL;
        $input_distrik = NULL;
        $input_lokasi = NULL;
        $input_draft = NULL;

        $input_tahun = $request->input('tahun_anggaran');
        $input_sb = $request->input('strategi_bisnis');
        $checkInputSb = $input_sb;
        $input_distrik = $request->input('distrik');
        $checkInputDistrik = $input_distrik;
        $input_lokasi = $request->input('lokasi');
        $checkInputLokasi = $input_lokasi;
        $input_fase = $request->input('fase');
        $checkInpuTFase = $input_fase;
        $input_draft = $request->input('draft_rkau');
        $checkInputDraft = $input_draft;


        // dd($distrik);
        // dd($input_draft);

        if ($request->input('strategi_bisnis') != NULL) {
            $input_sb = DB::table('strategi_bisnis')->select('name', 'id')->where('id', $request->input('strategi_bisnis'))->get()[0];
            $distrik = Distrik::select('name','id')->where('strategi_bisnis_id',$input_sb->id)->get();
        }
        if ($request->input('distrik') != NULL) {
            $input_distrik = DB::table('distrik')->select('name', 'id')->where('id', $request->distrik)->get()[0];
            $lokasi = Lokasi::select('name','id')->where('distrik_id',$input_distrik->id)->get();
            // dd($distrik);
        }
        if ($request->input('lokasi') != NULL) {
            $input_lokasi = DB::table('lokasi')->select('name', 'id')->where('id', $request->lokasi)->get()[0];

        }

        if ($request->input('fase') != NULL) {
            $input_fase = DB::table('fases')->select('name', 'id')->where('id', $request->fase)->get()[0];
        }
        if ($request->input('draft_rkau') != NULL) {
            $input_draft = DB::table('file_imports')->select('draft_versi', 'id', 'name')->where('id', $input_draft)->get()[0];
            $drafts = $this->query_draft($input_sb->name, $input_lokasi->id, $input_tahun);
            // dd($drafts->id);
        }
        // dd($input_draft);

        if ($input_tahun != NULL && $input_lokasi != NULL && $input_fase != NULL && $input_draft != NULL) {
            $blankSpace = '';
            $number = DB::table('excel_datas')->select('excel_datas.value', 'excel_datas.row', 'excel_datas.kolom')->join('file_imports','file_imports.id','=','excel_datas.file_import_id')->join('sheets','sheets.id','=','excel_datas.sheet_id')->where('sheets.name', 'like', 'I-DILUAR USAHA')->where('excel_datas.lokasi_id', $request->input('lokasi'))->where('excel_datas.row','>','12')->where('excel_datas.kolom','=','D')->where('file_imports.tahun', $request->input('tahun_anggaran'))->where('file_imports.id', $request->input('draft_rkau'))->orderBy('excel_datas.row', 'ASC')->get();
            //total data = 36
            // dd($number);
            $numberExcel = [];
            foreach ($number as $key => $value) {
                array_push($numberExcel, $value->value);
            }
            array_splice($numberExcel, 0, 0, $blankSpace);
            array_splice($numberExcel, 27, 0, $blankSpace);
            array_splice($numberExcel, 28, 0, $blankSpace);
            array_splice($numberExcel, 29, 0, $blankSpace);
            array_splice($numberExcel, 40, 0, $blankSpace);
            array_splice($numberExcel, 41, 0, $blankSpace);
            array_splice($numberExcel, 42, 0, $blankSpace);
            // dd($numberExcel);

            $noprk = DB::table('excel_datas')->select('excel_datas.value')->join('file_imports','file_imports.id','=','excel_datas.file_import_id')->join('sheets','sheets.id','=','excel_datas.sheet_id')->where('sheets.name', 'like', 'I-DILUAR USAHA')->where('excel_datas.lokasi_id', $request->input('lokasi'))->where('excel_datas.row','>','12')->where('excel_datas.kolom','=','E')->where('file_imports.tahun', $request->input('tahun_anggaran'))->where('file_imports.id', $request->input('draft_rkau'))->orderBy('excel_datas.row', 'ASC')->get();
            // dd($noprk);
            //total data = 36
            $noprkExcel = [];
            foreach ($noprk as $key => $value) {
                array_push($noprkExcel, $value->value);
            }
            array_splice($noprkExcel, 0, 0, $blankSpace);
            array_splice($noprkExcel, 27, 0, $blankSpace);
            array_splice($noprkExcel, 28, 0, $blankSpace);
            array_splice($noprkExcel, 29, 0, $blankSpace);
            array_splice($noprkExcel, 40, 0, $blankSpace);
            array_splice($noprkExcel, 41, 0, $blankSpace);
            array_splice($noprkExcel, 42, 0, $blankSpace);
            // dd($noprkExcel);

            $rincian = DB::table('excel_datas')->select('excel_datas.value', 'excel_datas.row', 'excel_datas.kolom')->join('file_imports','file_imports.id','=','excel_datas.file_import_id')->join('sheets','sheets.id','=','excel_datas.sheet_id')->where('sheets.name', 'like', 'I-DILUAR USAHA')->where('excel_datas.lokasi_id', $request->input('lokasi'))->where('excel_datas.row','>','12')->where('excel_datas.kolom','=','F')->where('file_imports.tahun', $request->input('tahun_anggaran'))->where('file_imports.id', $request->input('draft_rkau'))->orderBy('excel_datas.row', 'ASC')->get();
            // dd($rincian);
            //total data = 39
            //array ke 26 & 37 & 38, row 39, 51, 52 harusnya ga perlu ada
            // dd($rincian);
            $newRincian = [];
            $checkForRkap = [];
            $countPendapatan = 0;
            $test = [];
            $point = 1;
            for ($i = 0 ; $i < count($rincian) ; $i) {
                if ($rincian[$i]->value == "JUMLAH PENDAPATAN" || $rincian[$i]->value == "JUMLAH  PENDAPATAN" || $rincian[$i]->value == "T O T A L" || $rincian[$i]->value == "JUMLAH BEBAN" || $rincian[$i]->value == "JUMLAH  BEBAN") {
                    array_push($checkForRkap, $rincian[$i]->row);
                    array_push($test, $i);
                    $i++;
                }

                else {
                    if (strpos($rincian[$i]->value, 'Beban') !== false && $point == 1) {
                        $countPendapatan = $i;
                        $point = 2;
                        // $i++;
                    }

                    else {
                        array_push($newRincian, $rincian[$i]->value);
                        $i++;
                    }
                }
            }
            // dd($newRincian);
            $rincianExcel = [];
            foreach ($rincian as $key => $value) {
                array_push($rincianExcel, $value->value);
            }
            array_splice($rincianExcel, 0, 0, "PENDAPATAN");
            array_splice($rincianExcel, 28, 0, $blankSpace);
            array_splice($rincianExcel, 29, 0, "BEBAN");
            array_splice($rincianExcel, 41, 0, $blankSpace);

            // dd($rincianExcel);

            $rkap2018 = DB::table('excel_datas')->select('excel_datas.value', 'excel_datas.row', 'excel_datas.kolom')->join('file_imports','file_imports.id','=','excel_datas.file_import_id')->join('sheets','sheets.id','=','excel_datas.sheet_id')->where('sheets.name', 'like', 'I-DILUAR USAHA')->where('excel_datas.lokasi_id', $request->input('lokasi'))->where('excel_datas.row','>','12')->where('excel_datas.kolom','=','H')->where('file_imports.tahun', $request->input('tahun_anggaran'))->where('file_imports.id', $request->input('draft_rkau'))->orderBy('excel_datas.row', 'ASC')->get();
            //total data = 39
            $newRkap = [];
            for ($i = 0 ; $i < count($rkap2018) ; $i) {
                if ($rkap2018[$i]->row == $checkForRkap[0] || $rkap2018[$i]->row == $checkForRkap[1] || $rkap2018[$i]->row == $checkForRkap[2]) {
                    $i++;
                }
                else {
                    array_push($newRkap, $rkap2018[$i]->value);
                    $i++;
                }
            }
            // dd($newRkap);
            $rkapExcel = [];
            $rkapExcelFix = [];
            foreach ($rkap2018 as $key => $value) {
                array_push($rkapExcel, $value->value);
            }
            array_splice($rkapExcel, 0, 0, $blankSpace);
            array_splice($rkapExcel, 28, 0, $blankSpace);
            array_splice($rkapExcel, 29, 0, $blankSpace);
            array_splice($rkapExcel, 41, 0, $blankSpace);
            // dd($rkapExcel);
            // $rkapExcel = number_format(round($rkapExcel));
            foreach ($rkapExcel as $key => $value) {
                $jon = number_format(round($value),0,".",".");
                if ($jon == 0) {
                    $jon = ' ';
                }
                array_push($rkapExcelFix, $jon);
            }
            // dd($rkapExcelFix);

            $count = DB::table('excel_datas')->select('excel_datas.value')->join('file_imports','file_imports.id','=','excel_datas.file_import_id')->join('sheets','sheets.id','=','excel_datas.sheet_id')->where('sheets.name', 'like', 'I-DILUAR USAHA')->where('excel_datas.lokasi_id', $request->input('lokasi'))->where('excel_datas.row','>','12')->where('excel_datas.kolom','=','F')->where('file_imports.tahun', $request->input('tahun_anggaran'))->where('file_imports.id', $request->input('draft_rkau'))->count();

            // dd($count-12);

            if($request->download && $request->type){
                $judul='';
                if($request->type=='pdf'){
                    $fill = [];
                    $fill=array($request->get('tahun_anggaran'), $input_sb->name, $input_distrik->name, $input_lokasi->name, $input_fase->name ,$input_draft->draft_versi);
                    $judul='Form Luar Operasi';

                    $pdf=PDF::loadView('output/form-luar-operasi-pdf', compact('sb', 'fase', 'input_sb', 'input_distrik', 'input_lokasi', 'input_tahun','input_fase', 'input_draft', 'noprk', 'rincian', 'rkap2018', 'number', 'count', 'judul', 'fill', 'newRincian', 'newRkap', 'countPendapatan'));

                    return $pdf->download('Report Form Luar Operasi ' .$input_tahun.'.pdf');
                }
                else if($request->type=='excel'){
                    $array['judul']='Form Luar Operasi';
                    $array['judult1']='Form Luar Operasi';
                    $array['tahun']=[' ','Tahun', $input_tahun];
                    $array['struktur_bisnis']=[' ','Struktur Bisnis', $input_sb->name];
                    $array['distrik']=[' ','Distrik', $input_distrik->name];
                    $array['lokasi']=[' ','Lokasi', $input_lokasi->name];
                    $array['fase']=[' ','Fase', $input_fase->name];
                    $array['jenis']=[' ','Jenis Draft', $input_fase->name];
                    $array['draft']=[' ','Draft', $input_draft->draft_versi];

                    $arrayNew = [];

                    $tmp = [];
                    $array1 = [' ', ' ', 'RINCIAN', 'RKAP 2018'];
                    $arrayList = [];
                    $arrayList = ['No', 'No PRK', '1', '2'];
                    // dd($number);
                    $tmp[0] = $array1;
                    $tmp[1] = $arrayList;

                    for ($i = 0 ; $i < count($numberExcel) ; $i++) {
                        $tmp[] = array(
                            'No' => $numberExcel[$i],
                            'NoPRK' => $noprkExcel[$i],
                            '1' => $rincianExcel[$i],
                            '2' => $rkapExcelFix[$i]
                        );
                    }
                    $array['tmp']=$tmp;
                    // dd($array);

                    Excel::create('Report Form Luar Operasi', function($excel) use($array) {
                        $excel->setTitle('Report Form Luar Operasi');
                        $excel->setCreator('Laravel')->setCompany('Profio', 'PJB');
                        $excel->setDescription('Rincian Biaya Pegawai');

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
                return view('output/form-luar-operasi', compact('sb', 'fase', 'draft', 'input_tahun', 'input_sb', 'input_distrik', 'input_lokasi', 'input_fase', 'input_draft', 'noprk', 'rincian', 'rkap2018', 'count', 'number', 'newRincian', 'newRkap', 'countPendapatan', 'tahun', 'checkInputSb', 'checkInputDistrik', 'checkInputLokasi', 'checkInpuTFase', 'checkInputDraft', 'distrik', 'lokasi', 'drafts'));
            }
        }

    	return view('output/form-luar-operasi', compact('sb', 'fase', 'draft', 'input_tahun', 'input_sb', 'input_distrik', 'input_lokasi', 'input_fase', 'input_draft', 'noprk', 'rincian', 'rkap2018', 'count', 'number', 'newRincian', 'newRkap', 'countPendapatan', 'tahun', 'checkInputSb', 'checkInputDistrik', 'checkInputLokasi', 'checkInpuTFase', 'checkInputDraft', 'distrik', 'lokasi', 'drafts'));
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
}
