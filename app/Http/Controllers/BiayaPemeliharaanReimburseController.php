<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Entities\StrategiBisnis;
use App\Entities\Fase;  
use App\Entities\Distrik;
use App\Entities\Lokasi;
use App\Entities\Jenis;
use App\Entities\User;
use App\Entities\Role;
use Illuminate\Support\Facades\DB;
use Excel;
use PDF;

class BiayaPemeliharaanReimburseController extends Controller
{
    public function Biaya_Pemeliharaan(Request $request)
    {
        $user_id = session('user_id');
        $user = User::find($user_id);
        $role_id = session('role_id');
        $role = Role::find($role_id);

        //kantor pusat
        if($role->is_kantor_pusat) {
          $Sbisnis = StrategiBisnis::all();
        }
        else {
          $Sbisnis = StrategiBisnis::where('id', $user->distrik->strategi_bisnis->id)->get();  
        }
        
        $fs            = Fase::all();
        $input_distrik = $request->input('distrik');
        $lokasi        = $request->input('lokasi');
        $fase          = $request->input('fase');
        $reimburse     = $request->input('reimburse');
        $rutin         = $request->input('rutin');
        $input_tahun   = $request->input('tahun');
        $sb            = $request->input('strategi_bisnis');
 
        if ($request->input('strategi_bisnis') != NULL) {
            $input_sb = DB::table('strategi_bisnis')->select('name')->where('id', $request->input('strategi_bisnis'))->get()[0];
            }
        if ($request->input('tahun') != NULL) {
            $tahun     = $request->input('tahun');
        }
        if ($request->input('distrik') != NULL) {
            $distrik = DB::table('distrik')->select('name')->where('id', $request->distrik)->get()[0];
        }
        if ($request->input('lokasi') != NULL) {
            $input_lokasi = DB::table('lokasi')->select('name')->where('id', $request->lokasi)->get()[0];
        }
        if ($request->input('fase') != NULL) {
            $input_fase = DB::table('fases')->select('name')->where('id', $request->input('fase'))->get()[0];
        }
        if ($request->input('reimburse') != NULL) {
            $input_reimburse = DB::table('file_imports')->select('draft_versi', 'name')->where('id', $request->input('reimburse'))->get()[0];
        }
        if ($request->input('rutin') != NULL) {
            $input_rutin = DB::table('file_imports')->select('draft_versi', 'name')->where('id', $request->input('rutin'))->get()[0];
        }



        // if ($input_tahun != NULL && $lokasi != NULL && $reimburse != NULL && $rutin != NULL && $fase != NULL) {
        if ($input_tahun != NULL && $lokasi != NULL && $fase != NULL) {
            $tampil  = DB::table('file_imports')->distinct('file_imports.id', 
                'file_imports.draft_versi')->join('templates', 
                'file_imports.id','=','templates.id')->join('excel_datas',
                'excel_datas.file_import_id','=','file_imports.id');
            $tampil = $tampil->where('file_imports.tahun', $tahun, '&')->where('excel_datas.lokasi_id', $lokasi,'&')->where('templates.jenis_id', $fase)->get();

            $oj    = 'Operasi Jasa';
            // $oj_nilai_rutin = [];
            // if($rutin) {
            //     $oj_nilai_rutin = DB::select("SELECT coalesce(sum(e.value::float),0) as
            //                                 value from excel_datas e
            //                                 join sheets s on e.sheet_id = s.id
            //                                 where e.file_import_id = '$rutin'
            //                                 and s.name like 'I-Form 6'
            //                                 and e.kolom like 'AN'
            //                                 and e.row in (
            //                                         select e.row 
            //                                     from excel_datas e
            //                                     join sheets s on e.sheet_id = s.id
            //                                     where (e.file_import_id = '$rutin') 
            //                                     and s.name like 'I-Form 6'
            //                                     and e.kolom like 'H'
            //                                     and value like '10'
            //                                 );"); 
            // }
            $oj_nilai_reimburse = [];
            if($reimburse) {
                $oj_nilai_reimburse = DB::select("SELECT coalesce(sum(e.value::float),0) as
                                        value from excel_datas e
                                        join sheets s on e.sheet_id = s.id
                                        where e.file_import_id = '$reimburse' 
                                        and s.name like 'I-Form 6'
                                        and e.kolom like 'AN'
                                        and e.row in (
                                                select e.row 
                                            from excel_datas e
                                            join sheets s on e.sheet_id = s.id
                                            where (e.file_import_id = '$reimburse') 
                                            and s.name like 'I-Form 6'
                                            and e.kolom like 'H'
                                            and value like '10'
                                        );"); 
            }
            // $oj_nilai = array_merge($oj_nilai_rutin, $oj_nilai_reimburse);
            $oj_nilai = array_merge($oj_nilai_reimburse);

            $total = 0;
            foreach ($oj_nilai as $value) {
                $total = $total+$value->value;
            }
            $oj_nilai = $total;

            $overhaul    = 'Overhaul';
            // $overhaul_nilai_rutin = [];
            // if($rutin) {
            //     // $overhaul_nilai_rutin = DB::select("SELECT coalesce(sum(e.value::float),0) as
            //     //                             value from excel_datas e
            //     //                             join sheets s on e.sheet_id = s.id
            //     //                             where e.file_import_id = '$rutin'
            //     //                             and s.name like 'I-Form 6'
            //     //                             and e.kolom like 'AN'
            //     //                             and e.row in (
            //     //                                     select e.row 
            //     //                                 from excel_datas e
            //     //                                 join sheets s on e.sheet_id = s.id
            //     //                                 where (e.file_import_id = '$rutin') 
            //     //                                 and s.name like 'I-Form 6'
            //     //                                 and e.kolom like 'R'
            //     //                                 and value like 'Har_Overhaul'
            //     //                             );"); 
            //     $overhaul_nilai_rutin = DB::select("SELECT coalesce(sum(e.value::float),0) as
            //                                 value from excel_datas e
            //                                 join sheets s on e.sheet_id = s.id
            //                                 where e.file_import_id = '$rutin'
            //                                 and s.name like 'I-Form 6'
            //                                 and e.kolom like 'AN'
            //                                 and e.row in (
            //                                         select e.row 
            //                                     from excel_datas e
            //                                     join sheets s on e.sheet_id = s.id
            //                                     where (e.file_import_id = '$rutin') 
            //                                     and s.name like 'I-Form 6'
            //                                     and e.kolom like 'H'
            //                                     and value like '24'
            //                                 );"); 
            // }
            $overhaul_nilai_reimburse = [];
            if($reimburse) {
                // $overhaul_nilai_reimburse = DB::select("SELECT coalesce(sum(e.value::float),0) as
                //                             value from excel_datas e
                //                             join sheets s on e.sheet_id = s.id
                //                             where e.file_import_id = '$reimburse' 
                //                             and s.name like 'I-Form 6'
                //                             and e.kolom like 'AN'
                //                             and e.row in (
                //                                     select e.row 
                //                                 from excel_datas e
                //                                 join sheets s on e.sheet_id = s.id
                //                                 where (e.file_import_id = '$reimburse') 
                //                                 and s.name like 'I-Form 6'
                //                                 and e.kolom like 'R'
                //                                 and value like 'Har_Overhaul'
                //                             );"); 
                $overhaul_nilai_reimburse = DB::select("SELECT coalesce(sum(e.value::float),0) as
                                        value from excel_datas e
                                        join sheets s on e.sheet_id = s.id
                                        where e.file_import_id = '$reimburse' 
                                        and s.name like 'I-Form 6'
                                        and e.kolom like 'AN'
                                        and e.row in (
                                                select e.row 
                                            from excel_datas e
                                            join sheets s on e.sheet_id = s.id
                                            where (e.file_import_id = '$reimburse') 
                                            and s.name like 'I-Form 6'
                                            and e.kolom like 'H'
                                            and value like '24'
                                        );"); 
            }
            // $overhaul_nilai = array_merge($overhaul_nilai_rutin, $overhaul_nilai_reimburse);
            $overhaul_nilai = array_merge($overhaul_nilai_reimburse);

            $total = 0;
            foreach ($overhaul_nilai as $value) {
                $total = $total+$value->value;
            }
            $overhaul_nilai = $total;
        
            $inspection  = 'Inspection';
            $engineering = 'Engineering';
            // $engineering_nilai_rutin = [];
            // if($rutin) {
            //     // $engineering_nilai_rutin = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
            //     //                             value from excel_datas e
            //     //                             join sheets s on e.sheet_id = s.id
            //     //                             where e.file_import_id = '$rutin'
            //     //                             and s.name like 'I-Form 6'
            //     //                             and e.kolom like 'AN'
            //     //                             and e.row in (
            //     //                                     select e.row 
            //     //                                 from excel_datas e
            //     //                                 join sheets s on e.sheet_id = s.id
            //     //                                 where (e.file_import_id = '$rutin') 
            //     //                                 and s.name like 'I-Form 6'
            //     //                                 and e.kolom like 'R'
            //     //                                 and value like 'Har_Project'
            //     //                             );");
            //     $engineering_nilai_rutin = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
            //                                 value from excel_datas e
            //                                 join sheets s on e.sheet_id = s.id
            //                                 where e.file_import_id = '$rutin'
            //                                 and s.name like 'I-Form 6'
            //                                 and e.kolom like 'AN'
            //                                 and e.row in (
            //                                         select e.row 
            //                                     from excel_datas e
            //                                     join sheets s on e.sheet_id = s.id
            //                                     where (e.file_import_id = '$rutin') 
            //                                     and s.name like 'I-Form 6'
            //                                     and e.kolom like 'H'
            //                                     and value like '26'
            //                                 );");
            // }
            $engineering_nilai_reimburse = [];
            if($reimburse) {
                // $engineering_nilai_reimburse = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
                //                         value from excel_datas e
                //                         join sheets s on e.sheet_id = s.id
                //                         where e.file_import_id = '$reimburse' 
                //                         and s.name like 'I-Form 6'
                //                         and e.kolom like 'AN'
                //                         and e.row in (
                //                                 select e.row 
                //                             from excel_datas e
                //                             join sheets s on e.sheet_id = s.id
                //                             where (e.file_import_id = '$reimburse') 
                //                             and s.name like 'I-Form 6'
                //                             and e.kolom like 'R'
                //                             and value like 'Har_Project'
                //                         );");
                $engineering_nilai_reimburse = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
                                        value from excel_datas e
                                        join sheets s on e.sheet_id = s.id
                                        where e.file_import_id = '$reimburse' 
                                        and s.name like 'I-Form 6'
                                        and e.kolom like 'AN'
                                        and e.row in (
                                                select e.row 
                                            from excel_datas e
                                            join sheets s on e.sheet_id = s.id
                                            where (e.file_import_id = '$reimburse') 
                                            and s.name like 'I-Form 6'
                                            and e.kolom like 'H'
                                            and value like '26'
                                        );");
            }
            // $engineering_nilai = array_merge($engineering_nilai_rutin, $engineering_nilai_reimburse);
            $engineering_nilai = array_merge($engineering_nilai_reimburse);

            $total = 0;
            foreach ($engineering_nilai as $value) {
                $total = $total+$value->value;
            }
            $engineering_nilai = $total;

            $project     = 'Project';
            $modifikasi  = 'Modifikasi';
            $non         = 'Non Instalasi';
            // $nilai_non_rutin = [];
            // if($rutin) {
            //     // $nilai_non_rutin   = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
            //     //                             value from excel_datas e
            //     //                             join sheets s on e.sheet_id = s.id
            //     //                             where e.file_import_id = '$rutin'
            //     //                             and s.name like 'I-Form 6'
            //     //                             and e.kolom like 'AN'
            //     //                             and e.row in (
            //     //                                     select e.row 
            //     //                                 from excel_datas e
            //     //                                 join sheets s on e.sheet_id = s.id
            //     //                                 where (e.file_import_id = '$rutin') 
            //     //                                 and s.name like 'I-Form 6'
            //     //                                 and e.kolom like 'R'
            //     //                                 and value like 'Har_Non_Instalasi'
            //     //                             );");
            //     $nilai_non_rutin   = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
            //                                 value from excel_datas e
            //                                 join sheets s on e.sheet_id = s.id
            //                                 where e.file_import_id = '$rutin'
            //                                 and s.name like 'I-Form 6'
            //                                 and e.kolom like 'AN'
            //                                 and e.row in (
            //                                         select e.row 
            //                                     from excel_datas e
            //                                     join sheets s on e.sheet_id = s.id
            //                                     where (e.file_import_id = '$rutin') 
            //                                     and s.name like 'I-Form 6'
            //                                     and e.kolom like 'H'
            //                                     and value like '60'
            //                                 );");
            // }
            $nilai_non_reimburse = [];
            if($reimburse) {
                // $nilai_non_reimburse   = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
                //                         value from excel_datas e
                //                         join sheets s on e.sheet_id = s.id
                //                         where e.file_import_id = '$reimburse'
                //                         and s.name like 'I-Form 6'
                //                         and e.kolom like 'AN'
                //                         and e.row in (
                //                                 select e.row 
                //                             from excel_datas e
                //                             join sheets s on e.sheet_id = s.id
                //                             where (e.file_import_id = '$reimburse') 
                //                             and s.name like 'I-Form 6'
                //                             and e.kolom like 'R'
                //                             and value like 'Har_Non_Instalasi'
                //                         );");
                $nilai_non_reimburse   = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
                                        value from excel_datas e
                                        join sheets s on e.sheet_id = s.id
                                        where e.file_import_id = '$reimburse'
                                        and s.name like 'I-Form 6'
                                        and e.kolom like 'AN'
                                        and e.row in (
                                                select e.row 
                                            from excel_datas e
                                            join sheets s on e.sheet_id = s.id
                                            where (e.file_import_id = '$reimburse') 
                                            and s.name like 'I-Form 6'
                                            and e.kolom like 'H'
                                            and value like '60'
                                        );");
            }
            // $nilai_non = array_merge($nilai_non_rutin, $nilai_non_reimburse);
            $nilai_non = array_merge($nilai_non_reimburse);

            $total = 0;
            foreach ($nilai_non as $value) {
                $total = $total+$value->value;
            }
            $nilai_non = $total;

            $tu          = 'TU';
            $sarana      = 'Sarana';
            // $operasi     = 'Operasi Unit Pembangkitan';
            $operasi     = 'Operasi Jasa O&M';

            // $nilai_operasi_rutin = [];
            // if($rutin) {
            //     // $nilai_operasi_rutin = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
            //     //                             value from excel_datas e
            //     //                             join sheets s on e.sheet_id = s.id
            //     //                             where e.file_import_id = '$rutin' 
            //     //                             and s.name like 'I-Form 6'
            //     //                             and e.kolom like 'AN'
            //     //                             and e.row in (
            //     //                                     select e.row 
            //     //                                 from excel_datas e
            //     //                                 join sheets s on e.sheet_id = s.id
            //     //                                 where (e.file_import_id = '$rutin') 
            //     //                                 and s.name like 'I-Form 6'
            //     //                                 and e.kolom like 'R'
            //     //                                 and value like 'Har_Operasi'
            //     //                             );");
            //     $nilai_operasi_rutin = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
            //                                 value from excel_datas e
            //                                 join sheets s on e.sheet_id = s.id
            //                                 where e.file_import_id = '$rutin' 
            //                                 and s.name like 'I-Form 6'
            //                                 and e.kolom like 'AN'
            //                                 and e.row in (
            //                                         select e.row 
            //                                     from excel_datas e
            //                                     join sheets s on e.sheet_id = s.id
            //                                     where (e.file_import_id = '$rutin') 
            //                                     and s.name like 'I-Form 6'
            //                                     and e.kolom like 'H'
            //                                     and value like '11'
            //                                 );");
            // }

            $nilai_operasi_reimburse = [];
            if($reimburse) {
                // $nilai_operasi_reimburse = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
                //                         value from excel_datas e
                //                         join sheets s on e.sheet_id = s.id
                //                         where e.file_import_id = '$reimburse' 
                //                         and s.name like 'I-Form 6'
                //                         and e.kolom like 'AN'
                //                         and e.row in (
                //                                 select e.row 
                //                             from excel_datas e
                //                             join sheets s on e.sheet_id = s.id
                //                             where (e.file_import_id = '$reimburse') 
                //                             and s.name like 'I-Form 6'
                //                             and e.kolom like 'R'
                //                             and value like 'Har_Operasi'
                //                         );");
                $nilai_operasi_reimburse = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
                                        value from excel_datas e
                                        join sheets s on e.sheet_id = s.id
                                        where e.file_import_id = '$reimburse' 
                                        and s.name like 'I-Form 6'
                                        and e.kolom like 'AN'
                                        and e.row in (
                                                select e.row 
                                            from excel_datas e
                                            join sheets s on e.sheet_id = s.id
                                            where (e.file_import_id = '$reimburse') 
                                            and s.name like 'I-Form 6'
                                            and e.kolom like 'H'
                                            and value like '11'
                                        );");
            }

            // $nilai_operasi = array_merge($nilai_operasi_rutin, $nilai_operasi_reimburse);
            $nilai_operasi = array_merge($nilai_operasi_reimburse);

            $total = 0;
            foreach ($nilai_operasi as $value) {
                $total = $total+$value->value;
            }
            $nilai_operasi = $total;

            $kimia       = 'Kimia';
            // $nilai_kimia_rutin = [];
            // if($rutin) {
            //     // $nilai_kimia_rutin = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
            //     //                             value from excel_datas e
            //     //                             join sheets s on e.sheet_id = s.id
            //     //                             where e.file_import_id = '$rutin'
            //     //                             and s.name like 'I-Form 6'
            //     //                             and e.kolom like 'AN'
            //     //                             and e.row in (
            //     //                                     select e.row 
            //     //                                 from excel_datas e
            //     //                                 join sheets s on e.sheet_id = s.id
            //     //                                 where (e.file_import_id = '$rutin') 
            //     //                                 and s.name like 'I-Form 6'
            //     //                                 and e.kolom like 'R'
            //     //                                 and value like 'Har_Kimia_dan_Laboratorium'
            //     //                             );");
            //     $nilai_kimia_rutin = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
            //                                 value from excel_datas e
            //                                 join sheets s on e.sheet_id = s.id
            //                                 where e.file_import_id = '$rutin'
            //                                 and s.name like 'I-Form 6'
            //                                 and e.kolom like 'AN'
            //                                 and e.row in (
            //                                         select e.row 
            //                                     from excel_datas e
            //                                     join sheets s on e.sheet_id = s.id
            //                                     where (e.file_import_id = '$rutin') 
            //                                     and s.name like 'I-Form 6'
            //                                     and e.kolom like 'H'
            //                                     and value like '17'
            //                                 );");
            // }
            $nilai_kimia_reimburse = [];
            if($reimburse) {
                // $nilai_kimia_reimburse = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
                //                         value from excel_datas e
                //                         join sheets s on e.sheet_id = s.id
                //                         where e.file_import_id = '$reimburse' 
                //                         and s.name like 'I-Form 6'
                //                         and e.kolom like 'AN'
                //                         and e.row in (
                //                                 select e.row 
                //                             from excel_datas e
                //                             join sheets s on e.sheet_id = s.id
                //                             where (e.file_import_id = '$reimburse') 
                //                             and s.name like 'I-Form 6'
                //                             and e.kolom like 'R'
                //                             and value like 'Har_Kimia_dan_Laboratorium'
                //                         );");
                $nilai_kimia_reimburse = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
                                        value from excel_datas e
                                        join sheets s on e.sheet_id = s.id
                                        where e.file_import_id = '$reimburse' 
                                        and s.name like 'I-Form 6'
                                        and e.kolom like 'AN'
                                        and e.row in (
                                                select e.row 
                                            from excel_datas e
                                            join sheets s on e.sheet_id = s.id
                                            where (e.file_import_id = '$reimburse') 
                                            and s.name like 'I-Form 6'
                                            and e.kolom like 'H'
                                            and value like '17'
                                        );");
            }
            // $nilai_kimia = array_merge($nilai_kimia_rutin, $nilai_kimia_reimburse);
            $nilai_kimia = array_merge($nilai_kimia_reimburse);

            $total = 0;
            foreach ($nilai_kimia as $value) {
                $total = $total+$value->value;
            }
            $nilai_kimia = $total;

            $lab         = 'Laboratorium';
            $k3          = 'K3';

            // $nilai_k3_rutin = [];
            // if($rutin) {
            //     // $nilai_k3_rutin    = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
            //     //                         value from excel_datas e
            //     //                         join sheets s on e.sheet_id = s.id
            //     //                         where e.file_import_id = '$rutin' 
            //     //                         and s.name like 'I-Form 6'
            //     //                         and e.kolom like 'AN'
            //     //                         and e.row in (
            //     //                                 select e.row 
            //     //                             from excel_datas e
            //     //                             join sheets s on e.sheet_id = s.id
            //     //                             where (e.file_import_id = '$rutin') 
            //     //                             and s.name like 'I-Form 6'
            //     //                             and e.kolom like 'R'
            //     //                             and value like 'Har_K3'
            //     //                         );"); 
            //     $nilai_k3_rutin    = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
            //                             value from excel_datas e
            //                             join sheets s on e.sheet_id = s.id
            //                             where e.file_import_id = '$rutin' 
            //                             and s.name like 'I-Form 6'
            //                             and e.kolom like 'AN'
            //                             and e.row in (
            //                                     select e.row 
            //                                 from excel_datas e
            //                                 join sheets s on e.sheet_id = s.id
            //                                 where (e.file_import_id = '$rutin') 
            //                                 and s.name like 'I-Form 6'
            //                                 and e.kolom like 'H'
            //                                 and value like '18'
            //                             );"); 
            // }
            $nilai_k3_reimburse = [];
            if($reimburse) {
                // $nilai_k3_reimburse    = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
                //                         value from excel_datas e
                //                         join sheets s on e.sheet_id = s.id
                //                         where e.file_import_id = '$reimburse' 
                //                         and s.name like 'I-Form 6'
                //                         and e.kolom like 'AN'
                //                         and e.row in (
                //                                 select e.row 
                //                             from excel_datas e
                //                             join sheets s on e.sheet_id = s.id
                //                             where (e.file_import_id = '$reimburse') 
                //                             and s.name like 'I-Form 6'
                //                             and e.kolom like 'R'
                //                             and value like 'Har_K3'
                //                         );"); 
                $nilai_k3_reimburse    = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
                                        value from excel_datas e
                                        join sheets s on e.sheet_id = s.id
                                        where e.file_import_id = '$reimburse' 
                                        and s.name like 'I-Form 6'
                                        and e.kolom like 'AN'
                                        and e.row in (
                                                select e.row 
                                            from excel_datas e
                                            join sheets s on e.sheet_id = s.id
                                            where (e.file_import_id = '$reimburse') 
                                            and s.name like 'I-Form 6'
                                            and e.kolom like 'H'
                                            and value like '18'
                                        );"); 
            }
            // $nilai_k3 = array_merge($nilai_k3_rutin, $nilai_k3_reimburse);
            $nilai_k3 = array_merge($nilai_k3_reimburse);

            $total = 0;
            foreach ($nilai_k3 as $value) {
                $total = $total+$value->value;
            }
            $nilai_k3 = $total;

            $lingkungan  = 'Lingkungan';
            // $nilai_lingkungan_rutin = [];
            // if($rutin) {
            //     // $nilai_lingkungan_rutin  = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
            //     //                         value from excel_datas e
            //     //                         join sheets s on e.sheet_id = s.id
            //     //                         where e.file_import_id = '$rutin'
            //     //                         and s.name like 'I-Form 6'
            //     //                         and e.kolom like 'AN'
            //     //                         and e.row in (
            //     //                                 select e.row 
            //     //                             from excel_datas e
            //     //                             join sheets s on e.sheet_id = s.id
            //     //                             where (e.file_import_id = '$rutin') 
            //     //                             and s.name like 'I-Form 6'
            //     //                             and e.kolom like 'R'
            //     //                             and value like 'Har_Lingkungan'
            //     //                         );"); 
            //     $nilai_lingkungan_rutin  = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
            //                             value from excel_datas e
            //                             join sheets s on e.sheet_id = s.id
            //                             where e.file_import_id = '$rutin'
            //                             and s.name like 'I-Form 6'
            //                             and e.kolom like 'AN'
            //                             and e.row in (
            //                                     select e.row 
            //                                 from excel_datas e
            //                                 join sheets s on e.sheet_id = s.id
            //                                 where (e.file_import_id = '$rutin') 
            //                                 and s.name like 'I-Form 6'
            //                                 and e.kolom like 'H'
            //                                 and value like '19'
            //                             );"); 
            // }
            $nilai_lingkungan_reimburse = [];
            if($reimburse) {
                // $nilai_lingkungan_reimburse  = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
                //                         value from excel_datas e
                //                         join sheets s on e.sheet_id = s.id
                //                         where e.file_import_id = '$reimburse' 
                //                         and s.name like 'I-Form 6'
                //                         and e.kolom like 'AN'
                //                         and e.row in (
                //                                 select e.row 
                //                             from excel_datas e
                //                             join sheets s on e.sheet_id = s.id
                //                             where (e.file_import_id = '$reimburse') 
                //                             and s.name like 'I-Form 6'
                //                             and e.kolom like 'R'
                //                             and value like 'Har_Lingkungan'
                //                         );"); 
                $nilai_lingkungan_reimburse  = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
                                        value from excel_datas e
                                        join sheets s on e.sheet_id = s.id
                                        where e.file_import_id = '$reimburse' 
                                        and s.name like 'I-Form 6'
                                        and e.kolom like 'AN'
                                        and e.row in (
                                                select e.row 
                                            from excel_datas e
                                            join sheets s on e.sheet_id = s.id
                                            where (e.file_import_id = '$reimburse') 
                                            and s.name like 'I-Form 6'
                                            and e.kolom like 'H'
                                            and value like '19'
                                        );"); 
            }
            // $nilai_lingkungan = array_merge($nilai_lingkungan_rutin, $nilai_lingkungan_reimburse);
            $nilai_lingkungan = array_merge($nilai_lingkungan_reimburse);

            $total = 0;
            foreach ($nilai_lingkungan as $value) {
                $total = $total+$value->value;
            }
            $nilai_lingkungan = $total;

            $preventive  = 'Preventive Maintenance';
            // $nilai_preventive_rutin = [];
            // if($rutin) {
            //     // $nilai_preventive_rutin = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
            //     //                         value from excel_datas e
            //     //                         join sheets s on e.sheet_id = s.id
            //     //                         where e.file_import_id = '$rutin'
            //     //                         and s.name like 'I-Form 6'
            //     //                         and e.kolom like 'AN'
            //     //                         and e.row in (
            //     //                                 select e.row 
            //     //                             from excel_datas e
            //     //                             join sheets s on e.sheet_id = s.id
            //     //                             where (e.file_import_id = '$rutin') 
            //     //                             and s.name like 'I-Form 6'
            //     //                             and e.kolom like 'R'
            //     //                             and value like 'Har_Preventive'
            //     //                         );");
            //     $nilai_preventive_rutin = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
            //                             value from excel_datas e
            //                             join sheets s on e.sheet_id = s.id
            //                             where e.file_import_id = '$rutin'
            //                             and s.name like 'I-Form 6'
            //                             and e.kolom like 'AN'
            //                             and e.row in (
            //                                     select e.row 
            //                                 from excel_datas e
            //                                 join sheets s on e.sheet_id = s.id
            //                                 where (e.file_import_id = '$rutin') 
            //                                 and s.name like 'I-Form 6'
            //                                 and e.kolom like 'H'
            //                                 and value like '20'
            //                             );");
            // }
            $nilai_preventive_reimburse = [];
            if($reimburse) {
                // $nilai_preventive_reimburse = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
                //                         value from excel_datas e
                //                         join sheets s on e.sheet_id = s.id
                //                         where e.file_import_id = '$reimburse'
                //                         and s.name like 'I-Form 6'
                //                         and e.kolom like 'AN'
                //                         and e.row in (
                //                                 select e.row 
                //                             from excel_datas e
                //                             join sheets s on e.sheet_id = s.id
                //                             where (e.file_import_id = '$reimburse') 
                //                             and s.name like 'I-Form 6'
                //                             and e.kolom like 'R'
                //                             and value like 'Har_Preventive'
                //                         );");
                $nilai_preventive_reimburse = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
                                        value from excel_datas e
                                        join sheets s on e.sheet_id = s.id
                                        where e.file_import_id = '$reimburse'
                                        and s.name like 'I-Form 6'
                                        and e.kolom like 'AN'
                                        and e.row in (
                                                select e.row 
                                            from excel_datas e
                                            join sheets s on e.sheet_id = s.id
                                            where (e.file_import_id = '$reimburse') 
                                            and s.name like 'I-Form 6'
                                            and e.kolom like 'H'
                                            and value like '20'
                                        );");
            }
            // $nilai_preventive = array_merge($nilai_preventive_rutin, $nilai_preventive_reimburse);
            $nilai_preventive = array_merge($nilai_preventive_reimburse);

            $total = 0;
            foreach ($nilai_preventive as $value) {
                $total = $total+$value->value;
            }
            $nilai_preventive = $total;

            $predictive  = 'Predictive Maintenance';
            // $nilai_predictive_rutin = [];
            // if($rutin) {
            //     // $nilai_predictive_rutin = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
            //     //                         value from excel_datas e
            //     //                         join sheets s on e.sheet_id = s.id
            //     //                         where e.file_import_id = '$rutin'
            //     //                         and s.name like 'I-Form 6'
            //     //                         and e.kolom like 'AN'
            //     //                         and e.row in (
            //     //                                 select e.row 
            //     //                             from excel_datas e
            //     //                             join sheets s on e.sheet_id = s.id
            //     //                             where (e.file_import_id = '$rutin') 
            //     //                             and s.name like 'I-Form 6'
            //     //                             and e.kolom like 'R'
            //     //                             and value like 'Har_Predictive'
            //     //                         );");
            //     $nilai_predictive_rutin = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
            //                             value from excel_datas e
            //                             join sheets s on e.sheet_id = s.id
            //                             where e.file_import_id = '$rutin'
            //                             and s.name like 'I-Form 6'
            //                             and e.kolom like 'AN'
            //                             and e.row in (
            //                                     select e.row 
            //                                 from excel_datas e
            //                                 join sheets s on e.sheet_id = s.id
            //                                 where (e.file_import_id = '$rutin') 
            //                                 and s.name like 'I-Form 6'
            //                                 and e.kolom like 'H'
            //                                 and value like '21'
            //                             );");
            // }
            $nilai_predictive_reimburse = [];
            if($reimburse) {
                // $nilai_predictive_reimburse = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
                //                         value from excel_datas e
                //                         join sheets s on e.sheet_id = s.id
                //                         where e.file_import_id = '$reimburse'
                //                         and s.name like 'I-Form 6'
                //                         and e.kolom like 'AN'
                //                         and e.row in (
                //                                 select e.row 
                //                             from excel_datas e
                //                             join sheets s on e.sheet_id = s.id
                //                             where (e.file_import_id = '$reimburse') 
                //                             and s.name like 'I-Form 6'
                //                             and e.kolom like 'R'
                //                             and value like 'Har_Predictive'
                //                         );");
                $nilai_predictive_reimburse = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
                                        value from excel_datas e
                                        join sheets s on e.sheet_id = s.id
                                        where e.file_import_id = '$reimburse'
                                        and s.name like 'I-Form 6'
                                        and e.kolom like 'AN'
                                        and e.row in (
                                                select e.row 
                                            from excel_datas e
                                            join sheets s on e.sheet_id = s.id
                                            where (e.file_import_id = '$reimburse') 
                                            and s.name like 'I-Form 6'
                                            and e.kolom like 'H'
                                            and value like '21'
                                        );");
            }
            // $nilai_predictive = array_merge($nilai_predictive_rutin, $nilai_predictive_reimburse);
            $nilai_predictive = array_merge($nilai_predictive_reimburse);

            $total = 0;
            foreach ($nilai_predictive as $value) {
                $total = $total+$value->value;
            }
            $nilai_predictive = $total;

            $corective   = 'Corective Maintenance';
            // $nilai_corective_rutin = [];
            // if($rutin) {
            //     // $nilai_corective_rutin = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
            //     //                         value from excel_datas e
            //     //                         join sheets s on e.sheet_id = s.id
            //     //                         where e.file_import_id = '$rutin' 
            //     //                         and s.name like 'I-Form 6'
            //     //                         and e.kolom like 'AN'
            //     //                         and e.row in (
            //     //                                 select e.row 
            //     //                             from excel_datas e
            //     //                             join sheets s on e.sheet_id = s.id
            //     //                             where (e.file_import_id = '$rutin') 
            //     //                             and s.name like 'I-Form 6'
            //     //                             and e.kolom like 'R'
            //     //                             and value like 'Har_Corrective'
            //     //                         );");
            //     $nilai_corective_rutin = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
            //                             value from excel_datas e
            //                             join sheets s on e.sheet_id = s.id
            //                             where e.file_import_id = '$rutin' 
            //                             and s.name like 'I-Form 6'
            //                             and e.kolom like 'AN'
            //                             and e.row in (
            //                                     select e.row 
            //                                 from excel_datas e
            //                                 join sheets s on e.sheet_id = s.id
            //                                 where (e.file_import_id = '$rutin') 
            //                                 and s.name like 'I-Form 6'
            //                                 and e.kolom like 'H'
            //                                 and value like '22'
            //                             );");
            // }
            $nilai_corective_reimburse = [];
            if($reimburse) {
                // $nilai_corective_reimburse = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
                //                         value from excel_datas e
                //                         join sheets s on e.sheet_id = s.id
                //                         where e.file_import_id = '$reimburse'
                //                         and s.name like 'I-Form 6'
                //                         and e.kolom like 'AN'
                //                         and e.row in (
                //                                 select e.row 
                //                             from excel_datas e
                //                             join sheets s on e.sheet_id = s.id
                //                             where (e.file_import_id = '$reimburse') 
                //                             and s.name like 'I-Form 6'
                //                             and e.kolom like 'R'
                //                             and value like 'Har_Corrective'
                //                         );");
                $nilai_corective_reimburse = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
                                        value from excel_datas e
                                        join sheets s on e.sheet_id = s.id
                                        where e.file_import_id = '$reimburse'
                                        and s.name like 'I-Form 6'
                                        and e.kolom like 'AN'
                                        and e.row in (
                                                select e.row 
                                            from excel_datas e
                                            join sheets s on e.sheet_id = s.id
                                            where (e.file_import_id = '$reimburse') 
                                            and s.name like 'I-Form 6'
                                            and e.kolom like 'H'
                                            and value like '22'
                                        );");
            }
            // $nilai_corective = array_merge($nilai_corective_rutin, $nilai_corective_reimburse);
            $nilai_corective = array_merge($nilai_corective_reimburse);

            $total = 0;
            foreach ($nilai_corective as $value) {
                $total = $total+$value->value;
            }
            $nilai_corective = $total;

            $emergency   = 'Emergency Maintenance';
            // $nilai_emergency_rutin = [];
            // if($rutin) {
            //     // $nilai_emergency_rutin = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
            //     //                         value from excel_datas e
            //     //                         join sheets s on e.sheet_id = s.id
            //     //                         where e.file_import_id = '$rutin' 
            //     //                         and s.name like 'I-Form 6'
            //     //                         and e.kolom like 'AN'
            //     //                         and e.row in (
            //     //                                 select e.row 
            //     //                             from excel_datas e
            //     //                             join sheets s on e.sheet_id = s.id
            //     //                             where (e.file_import_id = '$rutin') 
            //     //                             and s.name like 'I-Form 6'
            //     //                             and e.kolom like 'R'
            //     //                             and value like 'Har_Corrective'
            //     //                         );");
            //     $nilai_emergency_rutin = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
            //                             value from excel_datas e
            //                             join sheets s on e.sheet_id = s.id
            //                             where e.file_import_id = '$rutin' 
            //                             and s.name like 'I-Form 6'
            //                             and e.kolom like 'AN'
            //                             and e.row in (
            //                                     select e.row 
            //                                 from excel_datas e
            //                                 join sheets s on e.sheet_id = s.id
            //                                 where (e.file_import_id = '$rutin') 
            //                                 and s.name like 'I-Form 6'
            //                                 and e.kolom like 'H'
            //                                 and value like '23'
            //                             );");
            // }
            $nilai_emergency_reimburse = [];
            if($reimburse) {
                // $nilai_emergency_reimburse = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
                //                         value from excel_datas e
                //                         join sheets s on e.sheet_id = s.id
                //                         where e.file_import_id = '$reimburse'
                //                         and s.name like 'I-Form 6'
                //                         and e.kolom like 'AN'
                //                         and e.row in (
                //                                 select e.row 
                //                             from excel_datas e
                //                             join sheets s on e.sheet_id = s.id
                //                             where (e.file_import_id = '$reimburse') 
                //                             and s.name like 'I-Form 6'
                //                             and e.kolom like 'R'
                //                             and value like 'Har_Corrective'
                //                         );");
                $nilai_emergency_reimburse = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
                                        value from excel_datas e
                                        join sheets s on e.sheet_id = s.id
                                        where e.file_import_id = '$reimburse'
                                        and s.name like 'I-Form 6'
                                        and e.kolom like 'AN'
                                        and e.row in (
                                                select e.row 
                                            from excel_datas e
                                            join sheets s on e.sheet_id = s.id
                                            where (e.file_import_id = '$reimburse') 
                                            and s.name like 'I-Form 6'
                                            and e.kolom like 'H'
                                            and value like '23'
                                        );");
            }
            // $nilai_emergency = array_merge($nilai_emergency_rutin, $nilai_emergency_reimburse);
            $nilai_emergency = array_merge($nilai_emergency_reimburse);

            $total = 0;
            foreach ($nilai_emergency as $value) {
                $total = $total+$value->value;
            }
            $nilai_emergency = $total;

            $breakdown   = 'Breakdown Maintenance';
            // $nilai_breakdown_rutin = [];
            // if($rutin) {
            //     // $nilai_breakdown_rutin = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
            //     //                         value from excel_datas e
            //     //                         join sheets s on e.sheet_id = s.id
            //     //                         where e.file_import_id = '$rutin' 
            //     //                         and s.name like 'I-Form 6'
            //     //                         and e.kolom like 'AN'
            //     //                         and e.row in (
            //     //                                 select e.row 
            //     //                             from excel_datas e
            //     //                             join sheets s on e.sheet_id = s.id
            //     //                             where (e.file_import_id = '$rutin') 
            //     //                             and s.name like 'I-Form 6'
            //     //                             and e.kolom like 'R'
            //     //                             and value like 'Har_Corrective'
            //     //                         );");
            //     $nilai_breakdown_rutin = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
            //                             value from excel_datas e
            //                             join sheets s on e.sheet_id = s.id
            //                             where e.file_import_id = '$rutin' 
            //                             and s.name like 'I-Form 6'
            //                             and e.kolom like 'AN'
            //                             and e.row in (
            //                                     select e.row 
            //                                 from excel_datas e
            //                                 join sheets s on e.sheet_id = s.id
            //                                 where (e.file_import_id = '$rutin') 
            //                                 and s.name like 'I-Form 6'
            //                                 and e.kolom like 'H'
            //                                 and value like '25'
            //                             );");
            // }
            $nilai_breakdown_reimburse = [];
            if($reimburse) {
                // $nilai_breakdown_reimburse = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
                //                         value from excel_datas e
                //                         join sheets s on e.sheet_id = s.id
                //                         where e.file_import_id = '$reimburse'
                //                         and s.name like 'I-Form 6'
                //                         and e.kolom like 'AN'
                //                         and e.row in (
                //                                 select e.row 
                //                             from excel_datas e
                //                             join sheets s on e.sheet_id = s.id
                //                             where (e.file_import_id = '$reimburse') 
                //                             and s.name like 'I-Form 6'
                //                             and e.kolom like 'R'
                //                             and value like 'Har_Corrective'
                //                         );");
                $nilai_breakdown_reimburse = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
                                        value from excel_datas e
                                        join sheets s on e.sheet_id = s.id
                                        where e.file_import_id = '$reimburse'
                                        and s.name like 'I-Form 6'
                                        and e.kolom like 'AN'
                                        and e.row in (
                                                select e.row 
                                            from excel_datas e
                                            join sheets s on e.sheet_id = s.id
                                            where (e.file_import_id = '$reimburse') 
                                            and s.name like 'I-Form 6'
                                            and e.kolom like 'H'
                                            and value like '25'
                                        );");
            }
            // $nilai_breakdown = array_merge($nilai_breakdown_rutin, $nilai_breakdown_reimburse);
            $nilai_breakdown = array_merge($nilai_breakdown_reimburse);

            $total = 0;
            foreach ($nilai_breakdown as $value) {
                $total = $total+$value->value;
            }
            $nilai_breakdown = $total;

            // $total = array_merge($overhaul_nilai, $engineering_nilai, $nilai_k3, $nilai_lingkungan,$nilai_preventive,$nilai_predictive, $nilai_corective,$nilai_non, $nilai_operasi,$nilai_kimia);
    
            // $semua[] = "";
            // foreach ($total as $key => $value) {
            //      $jumlah = $value->value;
            //      array_push($semua, $jumlah);
            //  } 
            //   $subtotal = array_sum($semua);

            //  $nilai_semua = array_merge($nilai_k3, $nilai_lingkungan,$nilai_preventive,$nilai_predictive, $nilai_corective,$nilai_non, $nilai_operasi,$nilai_kimia);
            // $totalsf[] = "";
            // foreach ($nilai_semua as $q) {
            //     $totalq = $q->value;
            //     array_push($totalsf, $totalq);
            // }
            // $hasil = array_sum($totalsf);

            $total = $oj_nilai+$overhaul_nilai+$engineering_nilai+$nilai_k3+$nilai_lingkungan+$nilai_preventive+$nilai_predictive+$nilai_corective+$nilai_non+$nilai_operasi+$nilai_kimia+$nilai_emergency+$nilai_breakdown;
         
            switch ($request->download) {
                case 'pdf':
                    // return $this->downloadPDF($request, $tahun, $sb, $lokasi, $fase, $reimburse, $rutin);
                    return $this->downloadPDF($request, $input_tahun, $sb, $lokasi, $fase, $input_fase, $input_reimburse, $oj_nilai, $overhaul_nilai, $engineering_nilai, $nilai_non, $nilai_operasi, $nilai_kimia, $nilai_k3, $nilai_lingkungan, $nilai_preventive, $nilai_predictive, $nilai_corective, $nilai_emergency, $nilai_breakdown, $total);
                    break;
                case 'excel':
                    return $this->downloadExcel($request, $input_tahun, $sb, $lokasi, $input_fase, $input_reimburse, $oj_nilai, $overhaul_nilai, $engineering_nilai, $nilai_non, $nilai_operasi, $nilai_kimia, $nilai_k3, $nilai_lingkungan, $nilai_preventive, $nilai_predictive, $nilai_corective, $nilai_emergency, $nilai_breakdown, $total);
                    break;
                default:
                    return view('/output/biaya-pemeliharaan-reimburse', compact('table', 'fs', 'tampil', 'tahun', 'lokasi', 'Sbisnis', 'input_distrik', 'distrik', 'input_sb', 'fase', 'oj', 'overhaul', 'subtotal', 'inspection', 'engineering', 'project', 'modifikasi', 'non', 'tu', 'sarana', 'operasi', 'kimia', 'lab', 'k3','lingkungan','preventive','predictive','corective', 'oj_nilai', 'overhaul_nilai','engineering_nilai','nilai_k3', 'nilai_lingkungan','nilai_preventive','nilai_predictive', 'nilai_corective','nilai_non', 'nilai_operasi','nilai_kimia', 'total', 'input_lokasi', 'input_fase','input_tahun', 'input_reimburse', 'input_reimburse', 'reimburse','rutin','input_rutin', 'emergency', 'nilai_emergency', 'breakdown', 'nilai_breakdown'));
                    break;
            }
        }  
        return view('/output/biaya-pemeliharaan-reimburse', compact('Sbisnis','table', 'fs', 'tampil', 'tahun', 'lokasi', 'fase', 'input_distrik', 'distrik','input_sb', 'oj', 'overhaul', 'engineering', 'project', 'modifikasi', 'non', 'tu', 'sarana', 'operasi', 'kimia', 'lab','k3','lingkungan','preventive','predictive','corective', 'oj_nilai', 'overhaul_nilai','engineering_nilai','nilai_k3', 'nilai_lingkungan','nilai_preventive', 'nilai_predictive','nilai_corective','nilai_non','nilai_operasi','nilai_kimia', 'total','input_lokasi', 'input_fase','input_tahun','input_reimburse', 'reimburse','rutin','input_rutin', 'emergency', 'nilai_emergency', 'breakdown', 'nilai_breakdown'));
    }

    private function downloadPDF(Request $request, $tahun, $sb, $lokasi, $fase, $input_fase, $input_reimburse, $oj_nilai, $overhaul_nilai, $engineering_nilai, $nilai_non, $nilai_operasi, $nilai_kimia, $nilai_k3, $nilai_lingkungan, $nilai_preventive, $nilai_predictive, $nilai_corective, $nilai_emergency, $nilai_breakdown, $total)
    {
        $oj    = 'Operasi Jasa';
        $overhaul    = 'Overhaul';
        $inspection  = 'Inspection';
        $engineering = 'Engineering';
            $project     = 'Project';
            $modifikasi  = 'Modifikasi';
            $non         = 'Non Instalasi';
            
            $tu          = 'TU';
            $sarana      = 'Sarana';
            $operasi     = 'Operasi Jasa O&M';
            
            $kimia       = 'Kimia';
            
            $lab         = 'Laboratorium';
            $k3          = 'K3';
            
            $lingkungan  = 'Lingkungan';
            
            $preventive  = 'Preventive Maintenance';
            
            $predictive  = 'Predictive Maintenance';
            
            $corective   = 'Corective Maintenance';
            $emergency   = 'Emergency Maintenance';
            $breakdown   = 'Breakdown Maintenance';
            

        $id_s   = $request->input('strategi_bisnis');
        $sbb    ='';
        $bisnis = StrategiBisnis::where('id', $id_s)->first();
        $distr  = $request->input('distrik');
        $dist = DB::select("SELECT id, name FROM distrik WHERE id = '$distr'");
        $distr1 = '';
        foreach ($dist as $k) {
            if ($k->id ==$distr) {
                $distr1 = $k->name;
            }
        }

        $lokasi1 = DB::SELECT("SELECT * FROM lokasi WHERE id = '$lokasi'");
        $l = '';
        foreach ($lokasi1 as $lm) {
            if ($lm->id == $lokasi) {
                $l = $lm->name;
            }
        }

        $fse = DB::SELECT("SELECT * FROM fases WHERE id = '$fase'");
        $fs  = '';
        foreach ($fse as $m) {
            if ($m->id == $fase) {
                $fs = $m->name;
            }
        }
        $data = [
                 'bisnis'=>$bisnis,
                 'tahun'=>$tahun,
                 'lokasi'=>$l, 
                 'sbb'=>$bisnis, 
                 'fase'=>$fs, 
                 'input_reimburse'=>$input_reimburse, 
                 'distr1'=>$distr1, 
                 'oj'=>$oj,
                 'overhaul'=>$overhaul,
                 'inspection'=>$inspection,
                 'operasi'=>$operasi,
                 'engineering'=>$engineering,
                 'project'=>$project,
                 'modifikasi'=>$modifikasi,
                 'non'=>$non, 
                 'tu'=>$tu,
                 'sarana'=>$sarana,
                 'kimia'=>$kimia,
                 'lab'=>$lab,
                 'k3'=>$k3,
                 'lingkungan'=>$lingkungan,
                 'preventive'=>$preventive,
                 'predictive'=>$predictive,
                 'corective'=>$corective,
                 'emergency'=>$emergency,
                 'breakdown'=>$breakdown,
                 'oj_nilai'=>$oj_nilai,
                 'overhaul_nilai'=>$overhaul_nilai,
                 'engineering_nilai'=>$engineering_nilai,
                 'nilai_non'=>$nilai_non, 
                 'nilai_operasi'=>$nilai_operasi,
                 'nilai_kimia'=>$nilai_kimia,
                 'nilai_k3'=>$nilai_k3, 
                 'nilai_lingkungan'=>$nilai_lingkungan,
                 'nilai_preventive'=>$nilai_preventive,
                 'nilai_predictive'=>$nilai_predictive, 
                 'nilai_corective'=>$nilai_corective,
                 'nilai_emergency'=>$nilai_emergency,
                 'nilai_breakdown'=>$nilai_breakdown,
                 'total'=>$total
                ];

        return view('/output/biayapemeliharaanreimburse_downloadPdf',$data);
        
   }
   private function downloadExcel(Request $request, $tahun, $sb, $lokasi, $input_fase, $input_reimburse, $oj_nilai, $overhaul_nilai, $engineering_nilai, $nilai_non, $nilai_operasi, $nilai_kimia, $nilai_k3, $nilai_lingkungan, $nilai_preventive, $nilai_predictive, $nilai_corective, $nilai_emergency, $nilai_breakdown, $total){
       
       // $total1 = array_merge($overhaul_nilai,$engineering_nilai, $nilai_non, $nilai_operasi, $nilai_k3,$nilai_lingkungan,$nilai_preventive,$nilai_predictive, $nilai_corective);
       //  $semua[] = "";
       //  foreach ($total1 as $key => $value) {
       //       $jumlah = $value->value;
       //       array_push($semua, $jumlah);
       //   } 
       //  $subtotal    = array_sum($semua);
       //  $subtotal1   = Round($subtotal);
       //  $subtotal2   = number_format($subtotal1,0, ',','.');

        $oj    = 'Operasi Jasa';
        $nilai_oj = Round($oj_nilai);

        $overhaul    = 'Overhaul';
        $nilai_over = Round($overhaul_nilai);

        $inspection  = 'Inspection';
        $engineering = 'Engineering';
        $engin   = Round($engineering_nilai);

        $project     = 'Project';
        $modifikasi  = 'Modifikasi';
        $non         = 'Non Instalasi';
        $none1  = Round($nilai_non);

        $tu          = 'TU';
        $sarana      = 'Sarana';
        $operasi     = 'Operasi Jasa O&M';
        $op = Round($nilai_operasi);

        $kimia       = 'Kimia';
        $lab         = 'Laboratorium';
        $k3          = 'K3';
        $k34 = Round($nilai_k3);

        $lingkungan  = 'Lingkungan';
        $preventive  = 'Preventive Maintenance';
        $predictive  = 'Predictive Maintenance';
        $corective   = 'Corective Maintenance';
        $emergency   = 'Emergency Maintenance';
        $breakdown   = 'Breakdown Maintenance';

        $th = $request->input('tahun');
        $distr = $request->input('distrik');
        $sbb='';
        $bisnis = StrategiBisnis::All();
        foreach ($bisnis as $key) {
            if($key->id==$sb){
                $sbb=$key->name;
            }
        }
        $dist = DB::select("select id, name from distrik where id = '$distr'");
        $distr1 = '';
        foreach ($dist as $k) {
            if ($k->id ==$distr) {
                $distr1 = $k->name;
            }
        }
        $lokass = DB::select("select id, name from lokasi where id='$lokasi'");
        $lko = '';
        foreach ($lokass as $l) {
            if ($l->id == $lokasi) {
                $lko = $l->name;
            }
        } 

        // $fase = Fase::SELECT('id','name')->get();
        Excel::create('Anggaran Biaya Pemeliharaan Reimburse', function($excel) use ($tahun, $sbb, $lko, $distr1, $th, $input_fase, $input_reimburse, $oj, $overhaul,$inspection,$engineering,$project,$modifikasi,$non,$tu,$sarana,$operasi,$kimia,$lab,$k3,$lingkungan,$preventive,$predictive,$corective, $emergency, $breakdown, $nilai_emergency, $nilai_breakdown, $oj_nilai, $overhaul_nilai, $engineering_nilai, $nilai_non, $nilai_operasi, $nilai_kimia, $nilai_k3, $nilai_lingkungan, $nilai_preventive, $nilai_predictive, $nilai_corective, $total){
            $excel->setTitle('ANGGARAN BIAYA PEMELIHARAAN REIMBURSE PER AKTIVITAS');
            $excel->setCreator('Laravel-5.5')->setCompany('PJB');
            $excel->sheet('Sheet1', function($sheet) use ($tahun, $sbb, $lko,  $distr1, $th, $input_fase, $input_reimburse, $oj, $overhaul,$inspection,$engineering,$project,$modifikasi,$non,$tu,$sarana,$operasi,$kimia,$lab,$k3,$lingkungan,$preventive,$predictive,$corective, $emergency, $breakdown, $nilai_emergency, $nilai_breakdown, $oj_nilai, $overhaul_nilai, $engineering_nilai, $nilai_non, $nilai_operasi, $nilai_kimia, $nilai_k3, $nilai_lingkungan, $nilai_preventive, $nilai_predictive, $nilai_corective, $total) {
                    $sheet->setWidth("B", 40);
                    $sheet->SetCellValue("B12", "ANGGARAN BIAYA PEMELIHARAAN REIMBURSE PER AKTIVITAS");
                    // $sheet->SetCellValue('A1', "Daftar Isi");
                    // $sheet->cells('A1', function($f) {
                    //     $f->setBackground("#1BBC9B");
                    //     $f->setFont(array
                    //                     (
                    //                         'family'=>'Calibri',
                    //                         'size'=>'11',
                    //                         'bold'=> true
                    //                     )
                    //                 );
                    //     $f->setFontColor('#ffffff');
                    // });
                    $sheet->mergeCells('B12:C12');
                    $sheet->cells('B12:C12', function($cells){
                        $cells->setAlignment('center');
                        $cells->setFont(array
                                            (
                                                'family'=>'Calibri',
                                                'size'=>'9',
                                                'bold'=> true
                                            )
                                        );
                        $cells->setBorder('none','none','none','none');
                    });
                    //TAHUN ANGGARAN DROPDOWN
                    // $sheet->SetCellValue("B6", "Stuktur Bisnis");
                    $sheet->SetCellValue("B5", "Tahun Anggaran");
                    $sheet->SetCellValue("C5", $tahun);
                   
                    $sheet->cells("C5", function($cell){
                        $cell->setAlignment('left');
                    });

                // $sheet->_parent->addNamedRange(
                //         new \PHPExcel_NamedRange('Tahun', $sheet, 'C5'
                //         )
                // );
                // $tahun = $sheet->getCell('C5')->getDataValidation()->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                // $tahun->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                // $tahun->setAllowBlank(false);
                // $tahun->setShowInputMessage(true);
                // $tahun->setShowErrorMessage(true);
                // $tahun->setShowDropDown(true);
                // $tahun->setFormula1('Tahun'); //note this!

                $sheet->SetCellValue('B6', 'Strategi Bisnis');
                $sheet->SetCellValue('C6', $sbb);
                // $sheet->_parent->addNamedRange(
                //         new \PHPExcel_NamedRange('Bisnis', $sheet, 'C6'
                //         )
                // );
                // $sheet->cells('C6', function($b){
                //     $b->setAlignment('right');
                // });
                // $bis = $sheet->getCell('C6')->getDataValidation()->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                // $bis->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                // $bis->setAllowBlank(false);
                // $bis->setShowInputMessage(true);
                // $bis->setShowErrorMessage(true);
                // $bis->setShowDropDown(true);
                // $bis->setFormula1('Bisnis');

                $sheet->SetCellValue("B7", "Distrik");
                $sheet->SetCellValue("C7",  $distr1);
                // $sheet->cells('C7', function($b){
                //     $b->setAlignment('right');
                // });
                // $sheet->_parent->addNamedRange(
                //         new \PHPExcel_NamedRange('Distrik', $sheet, 'C7'
                //         )
                // );
                // $dis = $sheet->getCell('C7')->getDataValidation()->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                // $dis->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                // $dis->setAllowBlank(false);
                // $dis->setShowInputMessage(true);
                // $dis->setShowErrorMessage(true);
                // $dis->setShowDropDown(true);
                // $dis->setFormula1('Distrik');

                $sheet->SetCellValue("B8", "Lokasi");
                $sheet->SetCellValue('C8', $lko);
                // $sheet->cells('C8', function($b){
                //     $b->setAlignment('right');
                // });
                // $sheet->_parent->addNamedRange(
                //         new \PHPExcel_NamedRange('Lokasi', $sheet, 'C8'
                //         )
                // );
                // $lok = $sheet->getCell('C8')->getDataValidation()->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                // $lok->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                // $lok->setAllowBlank(false);
                // $lok->setShowInputMessage(true);
                // $lok->setShowErrorMessage(true);
                // $lok->setShowDropDown(true);
                // $lok->setFormula1('Lokasi');

                $sheet->SetCellValue("B9", "Fase");
                isset($input_fase) ? $sheet->SetCellValue('C9', $input_fase->name) : $sheet->SetCellValue('C9', '');
                // $sheet->cells('C9', function($b){
                //     $b->setAlignment('right');
                // });

                $sheet->SetCellValue("B10", "Form 6 - Reimburse");
                isset($input_reimburse) ? $sheet->SetCellValue('C10', $input_reimburse->draft_versi.' - '.$input_reimburse->name) : $sheet->SetCellValue('C10', '');
                // $sheet->cells('C10', function($b){
                //     $b->setAlignment('right');
                // });
    
                    $sheet->setBorder('B5:C10', 'thin');

                    $sheet->cells('B5:B10', function($cell){
                        $cell->setBackground("#1BBC9B");
                        $cell->setFont(array
                                            (
                                                'family'=>'Calibri',
                                                'size'=>'12',
                                                'bold'=> true
                                            )
                                        );
                        $cell->setFontColor('#ffffff');
                    });
                    
                    $sheet->mergeCells("B13:C13");
                    $sheet->SetCellValue("B13", "(Dalam Ribuan Rupiah)");
                    $sheet->cells('B13:C13', function($cells){
                        $cells->setAlignment('center');
                        $cells->setFont(array
                                            (
                                                'family'=>'Calibri',
                                                'size'=>'9'
                                            )
                                        );
                    });
                    
                    $sheet->SetCellValue("B14", "Rincian Aktivitas");
                    $sheet->SetCellValue("C14", "RKAP");
                    $sheet->mergeCells("B14:B15");
                    $sheet->SetCellValue("C15",  $th);
                    $sheet->SetCellValue("B16",  "1");
                    $sheet->SetCellValue("C16",  "2");
                    $sheet->setBorder('B14:C16', 'thin', 'solid');
                    $sheet->cells('B14:C16', function($cells){
                        $cells->setBackground("#4ECDC4");
                        $cells->setAlignment('center');
                        $cells->setFont(array
                                            (
                                                'family'=>'Calibri',
                                                'size'=>'16',
                                                'bold'=> true
                                        ));
                        $cells->setFontColor('#ffffff');
                    });
                    $sheet->cells('C17:C30', function($cf){
                        $cf->setAlignment('right');
                    });

                    $sheet->setWidth('C', 15);
                    $sheet->setBorder('B17:C30', 'thin');
                    $sheet->SetCellValue("B17", $oj);
                    $oj_nilai = number_format(round($oj_nilai),0,',','.');
                    $sheet->SetCellValue('C17', $oj_nilai);

                    $sheet->SetCellValue("B18", $operasi);
                    $nilai_operasi = number_format(round($nilai_operasi),0,',','.');
                    $sheet->SetCellValue('C18', $nilai_operasi);

                    $sheet->SetCellValue("B19", "Kimia & Laboratorium");
                    $nilai_kimia = number_format(round($nilai_kimia),0,',','.');
                    $sheet->SetCellValue('C19', $nilai_kimia);

                    $sheet->SetCellValue("B20", $k3);
                    $nilai_k3 = number_format(round($nilai_k3),0,',','.');
                    $sheet->SetCellValue('C20', $nilai_k3);

                    $sheet->SetCellValue("B21", $lingkungan);
                    $nilai_lingkungan = number_format(round($nilai_lingkungan),0,',','.');
                    $sheet->SetCellValue('C21', $nilai_lingkungan);

                    $sheet->SetCellValue("B22", $preventive);
                    $nilai_preventive = number_format(round($nilai_preventive),0,',','.');
                    $sheet->SetCellValue("C22", $nilai_preventive);

                    $sheet->SetCellValue("B23", $predictive);
                    $nilai_predictive = number_format(round($nilai_predictive),0,',','.');
                    $sheet->SetCellValue("C23", $nilai_predictive);

                    $sheet->SetCellValue("B24", $corective);
                    $nilai_corective = number_format(round($nilai_corective), 0,',','.');
                    $sheet->SetCellValue("C24", $nilai_corective);

                    $sheet->SetCellValue('B25', $emergency);
                    $nilai_emergency = number_format(round($nilai_emergency), 0,',','.');
                    $sheet->SetCellValue('C25', $nilai_emergency);

                    $sheet->SetCellValue('B26', $overhaul.' / '.$inspection);
                    $overhaul_nilai = number_format(round($overhaul_nilai), 0,',','.');
                    $sheet->SetCellValue('C26', $overhaul_nilai);

                    $sheet->SetCellValue('B27', $breakdown);
                    $nilai_breakdown = number_format(round($nilai_breakdown), 0,',','.');
                    $sheet->SetCellValue('C27', $nilai_breakdown);

                    $sheet->SetCellValue("B28", $engineering.' / '.$project.' / '.$modifikasi);
                    $engineering_nilai = number_format(round($engineering_nilai), 0,',','.');
                    $sheet->SetCellValue('C28', $engineering_nilai);

                    $sheet->SetCellValue("B29", $non.' / '.$tu.' / '.$sarana);
                    $nilai_non = number_format(round($nilai_non),0,',','.');
                    $sheet->SetCellValue('C29', $nilai_non);

                    $sheet->SetCellValue("B30", 'TOTAL  PEMELIHARAAN');
                    $total = number_format(round($total), 0,',','.');
                    $sheet->SetCellValue("C30", $total);

                    $sheet->cells("B30:C30", function($to){
                    $to->setBackground("#4ECDC4");
                    $to->setFont(array
                                    (
                                        'family'=>'Calibri',
                                        'size'=>'12',
                                        'bold'=> true
                                    )
                                );
                    });
            });
         })->export('xlsx');
        return redirect()->back();
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

    public function faseAjax($id)
    {
        $js = DB::table('jenis')->select('name','id')->where('id',$id)->get();
        return json_encode($js);
    }

    public function ajax_draft_form_6_reimburse($id_lokasi, $id_tahun)
  {
    $draft_form_6_reimburse = DB::select("select distinct f.id, f.draft_versi
    from file_imports f
    join templates t on f.template_id = t.id
    join excel_datas e on e.file_import_id = f.id
    where t.jenis_id=2 and e.lokasi_id = ".$id_lokasi." and t.tahun= ".$id_tahun."
    group by f.id, f.draft_versi;");

    return json_encode($draft_form_6_reimburse);
  }

  public function ajax_draft_form_6_rutin($id_lokasi, $id_tahun)
  {
    $draft_form_6_rutin = DB::select("select distinct f.id, f.draft_versi
    from file_imports f
    join templates t on f.template_id = t.id
    join excel_datas e on e.file_import_id = f.id
    where t.jenis_id=3 and e.lokasi_id = ".$id_lokasi." and t.tahun= ".$id_tahun."
    group by f.id, f.draft_versi;");

    return json_encode($draft_form_6_rutin);
  }

}
