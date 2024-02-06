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

class BiayaPemeliharaanController extends Controller
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
            $overhaul    = 'Overhaul';
            $overhaul_nilai_rutin = [];
            if($rutin) {
            $overhaul_nilai_rutin = DB::select("SELECT coalesce(sum(e.value::float),0) as
                                        value from excel_datas e
                                        join sheets s on e.sheet_id = s.id
                                        where e.file_import_id = '$rutin'
                                        and s.name like 'I-Form 6'
                                        and e.kolom like 'AN'
                                        and e.row in (
                                                select e.row 
                                            from excel_datas e
                                            join sheets s on e.sheet_id = s.id
                                            where (e.file_import_id = '$rutin') 
                                            and s.name like 'I-Form 6'
                                            and e.kolom like 'R'
                                            and value like 'Har_Overhaul'
                                        );"); 
            }
            $overhaul_nilai_reimburse = [];
            if($reimburse) {
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
                                            and e.kolom like 'R'
                                            and value like 'Har_Overhaul'
                                        );"); 
            }
            $overhaul_nilai = array_merge($overhaul_nilai_rutin, $overhaul_nilai_reimburse);

            $total = 0;
            foreach ($overhaul_nilai as $value) {
                $total = $total+$value->value;
            }
            $overhaul_nilai = $total;
        
            $inspection  = 'Inspection';
            $engineering = 'Engineering';
            $engineering_nilai_rutin = [];
            if($rutin) {
            $engineering_nilai_rutin = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
                                        value from excel_datas e
                                        join sheets s on e.sheet_id = s.id
                                        where e.file_import_id = '$rutin'
                                        and s.name like 'I-Form 6'
                                        and e.kolom like 'AN'
                                        and e.row in (
                                                select e.row 
                                            from excel_datas e
                                            join sheets s on e.sheet_id = s.id
                                            where (e.file_import_id = '$rutin') 
                                            and s.name like 'I-Form 6'
                                            and e.kolom like 'R'
                                            and value like 'Har_Project'
                                        );");
            }
            $engineering_nilai_reimburse = [];
            if($reimburse) {
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
                                            and e.kolom like 'R'
                                            and value like 'Har_Project'
                                        );");
            }
            $engineering_nilai = array_merge($engineering_nilai_rutin, $engineering_nilai_reimburse);

            $total = 0;
            foreach ($engineering_nilai as $value) {
                $total = $total+$value->value;
            }
            $engineering_nilai = $total;

            $project     = 'Project';
            $modifikasi  = 'Modifikasi';
            $non         = 'Non Instalasi';
            $nilai_non_rutin = [];
            if($rutin) {
            $nilai_non_rutin   = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
                                        value from excel_datas e
                                        join sheets s on e.sheet_id = s.id
                                        where e.file_import_id = '$rutin'
                                        and s.name like 'I-Form 6'
                                        and e.kolom like 'AN'
                                        and e.row in (
                                                select e.row 
                                            from excel_datas e
                                            join sheets s on e.sheet_id = s.id
                                            where (e.file_import_id = '$rutin') 
                                            and s.name like 'I-Form 6'
                                            and e.kolom like 'R'
                                            and value like 'Har_Non_Instalasi'
                                        );");
            }
            $nilai_non_reimburse = [];
            if($reimburse) {
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
                                            and e.kolom like 'R'
                                            and value like 'Har_Non_Instalasi'
                                        );");
            }
            $nilai_non = array_merge($nilai_non_rutin, $nilai_non_reimburse);

            $total = 0;
            foreach ($nilai_non as $value) {
                $total = $total+$value->value;
            }
            $nilai_non = $total;

            $tu          = 'TU';
            $sarana      = 'Sarana';
            $operasi     = 'Operasi Unit Pembangkitan';

            $nilai_operasi_rutin = [];
            if($rutin) {
            $nilai_operasi_rutin = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
                                        value from excel_datas e
                                        join sheets s on e.sheet_id = s.id
                                        where e.file_import_id = '$rutin' 
                                        and s.name like 'I-Form 6'
                                        and e.kolom like 'AN'
                                        and e.row in (
                                                select e.row 
                                            from excel_datas e
                                            join sheets s on e.sheet_id = s.id
                                            where (e.file_import_id = '$rutin') 
                                            and s.name like 'I-Form 6'
                                            and e.kolom like 'R'
                                            and value like 'Har_Operasi'
                                        );");
            }

            $nilai_operasi_reimburse = [];
            if($reimburse) {
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
                                            and e.kolom like 'R'
                                            and value like 'Har_Operasi'
                                        );");
            }

            $nilai_operasi = array_merge($nilai_operasi_rutin, $nilai_operasi_reimburse);

            $total = 0;
            foreach ($nilai_operasi as $value) {
                $total = $total+$value->value;
            }
            $nilai_operasi = $total;

            $kimia       = 'Kimia';
            $nilai_kimia_rutin = [];
            if($rutin) {
            $nilai_kimia_rutin = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
                                        value from excel_datas e
                                        join sheets s on e.sheet_id = s.id
                                        where e.file_import_id = '$rutin'
                                        and s.name like 'I-Form 6'
                                        and e.kolom like 'AN'
                                        and e.row in (
                                                select e.row 
                                            from excel_datas e
                                            join sheets s on e.sheet_id = s.id
                                            where (e.file_import_id = '$rutin') 
                                            and s.name like 'I-Form 6'
                                            and e.kolom like 'R'
                                            and value like 'Har_Kimia_dan_Laboratorium'
                                        );");
            }
            $nilai_kimia_reimburse = [];
            if($reimburse) {
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
                                            and e.kolom like 'R'
                                            and value like 'Har_Kimia_dan_Laboratorium'
                                        );");
            }
            $nilai_kimia = array_merge($nilai_kimia_rutin, $nilai_kimia_reimburse);

            $total = 0;
            foreach ($nilai_kimia as $value) {
                $total = $total+$value->value;
            }
            $nilai_kimia = $total;

            $lab         = 'Laboratorium';
            $k3          = 'K3';

            $nilai_k3_rutin = [];
            if($rutin) {
            $nilai_k3_rutin    = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
                                        value from excel_datas e
                                        join sheets s on e.sheet_id = s.id
                                        where e.file_import_id = '$rutin' 
                                        and s.name like 'I-Form 6'
                                        and e.kolom like 'AN'
                                        and e.row in (
                                                select e.row 
                                            from excel_datas e
                                            join sheets s on e.sheet_id = s.id
                                            where (e.file_import_id = '$rutin') 
                                            and s.name like 'I-Form 6'
                                            and e.kolom like 'R'
                                            and value like 'Har_K3'
                                        );"); 
            }
            $nilai_k3_reimburse = [];
            if($reimburse) {
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
                                            and e.kolom like 'R'
                                            and value like 'Har_K3'
                                        );"); 
            }
            $nilai_k3 = array_merge($nilai_k3_rutin, $nilai_k3_reimburse);

            $total = 0;
            foreach ($nilai_k3 as $value) {
                $total = $total+$value->value;
            }
            $nilai_k3 = $total;

            $lingkungan  = 'Lingkungan';
            $nilai_lingkungan_rutin = [];
            if($rutin) {
            $nilai_lingkungan_rutin  = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
                                        value from excel_datas e
                                        join sheets s on e.sheet_id = s.id
                                        where e.file_import_id = '$rutin'
                                        and s.name like 'I-Form 6'
                                        and e.kolom like 'AN'
                                        and e.row in (
                                                select e.row 
                                            from excel_datas e
                                            join sheets s on e.sheet_id = s.id
                                            where (e.file_import_id = '$rutin') 
                                            and s.name like 'I-Form 6'
                                            and e.kolom like 'R'
                                            and value like 'Har_Lingkungan'
                                        );"); 
            }
            $nilai_lingkungan_reimburse = [];
            if($reimburse) {
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
                                            and e.kolom like 'R'
                                            and value like 'Har_Lingkungan'
                                        );"); 
            }
            $nilai_lingkungan = array_merge($nilai_lingkungan_rutin, $nilai_lingkungan_reimburse);

            $total = 0;
            foreach ($nilai_lingkungan as $value) {
                $total = $total+$value->value;
            }
            $nilai_lingkungan = $total;

            $preventive  = 'Preventive Maintenance';
            $nilai_preventive_rutin = [];
            if($rutin) {
            $nilai_preventive_rutin = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
                                        value from excel_datas e
                                        join sheets s on e.sheet_id = s.id
                                        where e.file_import_id = '$rutin'
                                        and s.name like 'I-Form 6'
                                        and e.kolom like 'AN'
                                        and e.row in (
                                                select e.row 
                                            from excel_datas e
                                            join sheets s on e.sheet_id = s.id
                                            where (e.file_import_id = '$rutin') 
                                            and s.name like 'I-Form 6'
                                            and e.kolom like 'R'
                                            and value like 'Har_Preventive'
                                        );");
            }
            $nilai_preventive_reimburse = [];
            if($reimburse) {
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
                                            and e.kolom like 'R'
                                            and value like 'Har_Preventive'
                                        );");
            }
            $nilai_preventive = array_merge($nilai_preventive_rutin, $nilai_preventive_reimburse);

            $total = 0;
            foreach ($nilai_preventive as $value) {
                $total = $total+$value->value;
            }
            $nilai_preventive = $total;

            $predictive  = 'Predictive Maintenance';
            $nilai_predictive_rutin = [];
            if($rutin) {
            $nilai_predictive_rutin = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
                                        value from excel_datas e
                                        join sheets s on e.sheet_id = s.id
                                        where e.file_import_id = '$rutin'
                                        and s.name like 'I-Form 6'
                                        and e.kolom like 'AN'
                                        and e.row in (
                                                select e.row 
                                            from excel_datas e
                                            join sheets s on e.sheet_id = s.id
                                            where (e.file_import_id = '$rutin') 
                                            and s.name like 'I-Form 6'
                                            and e.kolom like 'R'
                                            and value like 'Har_Predictive'
                                        );");
            }
            $nilai_predictive_reimburse = [];
            if($reimburse) {
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
                                            and e.kolom like 'R'
                                            and value like 'Har_Predictive'
                                        );");
            }
            $nilai_predictive = array_merge($nilai_predictive_rutin, $nilai_predictive_reimburse);

            $total = 0;
            foreach ($nilai_predictive as $value) {
                $total = $total+$value->value;
            }
            $nilai_predictive = $total;

            $corective   = 'Corective Maintenance';
            $nilai_corective_rutin = [];
            if($rutin) {
            $nilai_corective_rutin = DB::SELECT("SELECT coalesce(sum(e.value::float),0) as
                                        value from excel_datas e
                                        join sheets s on e.sheet_id = s.id
                                        where e.file_import_id = '$rutin' 
                                        and s.name like 'I-Form 6'
                                        and e.kolom like 'AN'
                                        and e.row in (
                                                select e.row 
                                            from excel_datas e
                                            join sheets s on e.sheet_id = s.id
                                            where (e.file_import_id = '$rutin') 
                                            and s.name like 'I-Form 6'
                                            and e.kolom like 'R'
                                            and value like 'Har_Corrective'
                                        );");
            }
            $nilai_corective_reimburse = [];
            if($reimburse) {
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
                                            and e.kolom like 'R'
                                            and value like 'Har_Corrective'
                                        );");
            }
            $nilai_corective = array_merge($nilai_corective_rutin, $nilai_corective_reimburse);

            $total = 0;
            foreach ($nilai_corective as $value) {
                $total = $total+$value->value;
            }
            $nilai_corective = $total;

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

            $total = $overhaul_nilai+$engineering_nilai+$nilai_k3+$nilai_lingkungan+$nilai_preventive+$nilai_predictive+$nilai_corective+$nilai_non+$nilai_operasi+$nilai_kimia;
         
            switch ($request->download) {
                case 'pdf':
                    // return $this->downloadPDF($request, $tahun, $sb, $lokasi, $fase, $reimburse, $rutin);
                    return $this->downloadPDF($request, $input_tahun, $sb, $lokasi, $fase, $overhaul_nilai, $engineering_nilai, $nilai_non, $nilai_operasi, $nilai_kimia, $nilai_k3, $nilai_lingkungan, $nilai_preventive, $nilai_predictive, $nilai_corective, $total);
                    break;
                case 'excel':
                    return $this->downloadExcel($request, $input_tahun, $sb, $lokasi, $fase, $overhaul_nilai, $engineering_nilai, $nilai_non, $nilai_operasi, $nilai_kimia, $nilai_k3, $nilai_lingkungan, $nilai_preventive, $nilai_predictive, $nilai_corective, $total);
                    break;
                default:
                    return view('/output/biaya-pemeliharaan', compact('table', 'fs', 'tampil', 'tahun', 'lokasi', 'Sbisnis', 'input_distrik', 'distrik', 'input_sb', 'fase', 'overhaul', 'subtotal', 'inspection', 'engineering', 'project', 'modifikasi', 'non', 'tu', 'sarana', 'operasi', 'kimia', 'lab', 'k3','lingkungan','preventive','predictive','corective', 'overhaul_nilai','engineering_nilai','nilai_k3', 'nilai_lingkungan','nilai_preventive','nilai_predictive', 'nilai_corective','nilai_non', 'nilai_operasi','nilai_kimia', 'total', 'input_lokasi', 'input_fase','input_tahun', 'input_reimburse', 'input_reimburse', 'reimburse','rutin','input_rutin'));
                    break;
            }
        }  
        return view('/output/biaya-pemeliharaan', compact('Sbisnis','table', 'fs', 'tampil', 'tahun', 'lokasi', 'fase', 'input_distrik', 'distrik','input_sb', 'overhaul', 'engineering', 'project', 'modifikasi', 'non', 'tu', 'sarana', 'operasi', 'kimia', 'lab','k3','lingkungan','preventive','predictive','corective','overhaul_nilai','engineering_nilai','nilai_k3', 'nilai_lingkungan','nilai_preventive', 'nilai_predictive','nilai_corective','nilai_non','nilai_operasi','nilai_kimia', 'total','input_lokasi', 'input_fase','input_tahun','input_reimburse', 'reimburse','rutin','input_rutin'));
    }

    private function downloadPDF(Request $request, $tahun, $sb, $lokasi, $fase, $overhaul_nilai, $engineering_nilai, $nilai_non, $nilai_operasi, $nilai_kimia, $nilai_k3, $nilai_lingkungan, $nilai_preventive, $nilai_predictive, $nilai_corective, $total)
    {
        $overhaul    = 'Overhaul';
        $inspection  = 'Inspection';
        $engineering = 'Engineering';
            $project     = 'Project';
            $modifikasi  = 'Modifikasi';
            $non         = 'Non Instalasi';
            
            $tu          = 'TU';
            $sarana      = 'Sarana';
            $operasi     = 'Operasi Unit Pembangkitan';
            
            $kimia       = 'Kimia';
            
            $lab         = 'Laboratorium';
            $k3          = 'K3';
            
            $lingkungan  = 'Lingkungan';
            
            $preventive  = 'Preventive Maintenance';
            
            $predictive  = 'Predictive Maintenance';
            
            $corective   = 'Corective Maintenance';
            

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
                 'distr1'=>$distr1, 
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
                 'total'=>$total
                ];

        return view('/output/biayapemeliharaan_downloadPdf',$data);
        
   }
   private function downloadExcel(Request $request, $tahun, $sb, $lokasi, $fase, $overhaul_nilai, $engineering_nilai, $nilai_non, $nilai_operasi, $nilai_kimia, $nilai_k3, $nilai_lingkungan, $nilai_preventive, $nilai_predictive, $nilai_corective, $total){
       
       // $total1 = array_merge($overhaul_nilai,$engineering_nilai, $nilai_non, $nilai_operasi, $nilai_k3,$nilai_lingkungan,$nilai_preventive,$nilai_predictive, $nilai_corective);
       //  $semua[] = "";
       //  foreach ($total1 as $key => $value) {
       //       $jumlah = $value->value;
       //       array_push($semua, $jumlah);
       //   } 
       //  $subtotal    = array_sum($semua);
       //  $subtotal1   = Round($subtotal);
       //  $subtotal2   = number_format($subtotal1,0, ',','.');

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
        $operasi     = 'Operasi Unit Pembangkitan';
        $op = Round($nilai_operasi);

        $kimia       = 'Kimia';
        $lab         = 'Laboratorium';
        $k3          = 'K3';
        $k34 = Round($nilai_k3);

        $lingkungan  = 'Lingkungan';
        $preventive  = 'Preventive Maintenance';
        $predictive  = 'Predictive Maintenance';
        $corective   = 'Corective Maintenance';

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

        $fase = Fase::SELECT('id','name')->get();
        Excel::create('Anggaran Biaya Pemeliharaan', function($excel) use ($tahun, $sbb, $lko, $distr1, $th, $overhaul,$inspection,$engineering,$project,$modifikasi,$non,$tu,$sarana,$operasi,$kimia,$lab,$k3,$lingkungan,$preventive,$predictive,$corective, $overhaul_nilai, $engineering_nilai, $nilai_non, $nilai_operasi, $nilai_kimia, $nilai_k3, $nilai_lingkungan, $nilai_preventive, $nilai_predictive, $nilai_corective, $total){
            $excel->setTitle('ANGGARAN BIAYA PEMELIHARAAN PER AKTIVITAS');
            $excel->setCreator('Laravel-5.5')->setCompany('PJB');
            $excel->sheet('Excel sheet', function($sheet) use ($tahun, $sbb, $lko,  $distr1, $th, $overhaul,$inspection,$engineering,$project,$modifikasi,$non,$tu,$sarana,$operasi,$kimia,$lab,$k3,$lingkungan,$preventive,$predictive,$corective, $overhaul_nilai, $engineering_nilai, $nilai_non, $nilai_operasi, $nilai_kimia, $nilai_k3, $nilai_lingkungan, $nilai_preventive, $nilai_predictive, $nilai_corective, $total) {
                    $sheet->setWidth("B", 40);
                    $sheet->SetCellValue("B10", "ANGGARAN BIAYA PEMELIHARAAN PER AKTIVITAS");
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
                    $sheet->mergeCells('B10:C10');
                    $sheet->cells('B10:C10', function($cells){
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
                   
                     $sheet->cells("C6", function($cell){
                        $cell->setAlignment('center');
                    });

                $sheet->_parent->addNamedRange(
                        new \PHPExcel_NamedRange('Tahun', $sheet, 'C5'
                        )
                );
                $tahun = $sheet->getCell('C5')->getDataValidation()->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                $tahun->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $tahun->setAllowBlank(false);
                $tahun->setShowInputMessage(true);
                $tahun->setShowErrorMessage(true);
                $tahun->setShowDropDown(true);
                $tahun->setFormula1('Tahun'); //note this!

                $sheet->SetCellValue('B6', 'Strategi Bisnis');
                $sheet->SetCellValue('C6', $sbb);
                $sheet->_parent->addNamedRange(
                        new \PHPExcel_NamedRange('Bisnis', $sheet, 'C6'
                        )
                );
                $sheet->cells('C6', function($b){
                    $b->setAlignment('right');
                });
                $bis = $sheet->getCell('C6')->getDataValidation()->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                $bis->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $bis->setAllowBlank(false);
                $bis->setShowInputMessage(true);
                $bis->setShowErrorMessage(true);
                $bis->setShowDropDown(true);
                $bis->setFormula1('Bisnis');

                $sheet->SetCellValue("B7", "Distrik");
                $sheet->SetCellValue("C7",  $distr1);
                $sheet->_parent->addNamedRange(
                        new \PHPExcel_NamedRange('Distrik', $sheet, 'C7'
                        )
                );
                $dis = $sheet->getCell('C7')->getDataValidation()->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                $dis->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $dis->setAllowBlank(false);
                $dis->setShowInputMessage(true);
                $dis->setShowErrorMessage(true);
                $dis->setShowDropDown(true);
                $dis->setFormula1('Distrik');

                $sheet->SetCellValue("B8", "Lokasi");
                $sheet->SetCellValue('C8', $lko);
                $sheet->_parent->addNamedRange(
                        new \PHPExcel_NamedRange('Lokasi', $sheet, 'C8'
                        )
                );
                $lok = $sheet->getCell('C8')->getDataValidation()->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                $lok->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $lok->setAllowBlank(false);
                $lok->setShowInputMessage(true);
                $lok->setShowErrorMessage(true);
                $lok->setShowDropDown(true);
                $lok->setFormula1('Lokasi');
    
                    $sheet->setBorder('B5:C8', 'thin');
                    $sheet->cells('B5:B8', function($cell){
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
                    $sheet->mergeCells("B11:C11");
                    $sheet->SetCellValue("B11", "(Dalam Ribuan Rupiah)");
                    $sheet->cells('B11:C11', function($cells){
                        $cells->setAlignment('center');
                        $cells->setFont(array
                                            (
                                                'family'=>'Calibri',
                                                'size'=>'9'
                                            )
                                        );
                    });
                    
                    $sheet->SetCellValue("B14", "Rincian Aktifitas");
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
                    $sheet->cells('C17:C27', function($cf){
                        $cf->setAlignment('right');
                    });
                         $sheet->setWidth('C', 15);
                         $sheet->setBorder('B17:C27', 'thin');
                         $sheet->SetCellValue('B17', $overhaul.' / '.$inspection);
                         $overhaul_nilai = number_format(round($overhaul_nilai), 0,',','.');
                         $sheet->SetCellValue('C17', $overhaul_nilai);

                         $sheet->SetCellValue("B18", $engineering.' / '.$project.' / '.$modifikasi);
                         $engineering_nilai = number_format(round($engineering_nilai), 0,',','.');
                         $sheet->SetCellValue('C18', $engineering_nilai);

                         $sheet->SetCellValue("B19", $non.' / '.$tu.' / '.$sarana);
                         $nilai_non = number_format(round($nilai_non),0,',','.');
                         $sheet->SetCellValue('C19', $nilai_non);

                         $sheet->SetCellValue("B20", $operasi);
                         $nilai_operasi = number_format(round($nilai_operasi),0,',','.');
                         $sheet->SetCellValue('C20', $nilai_operasi);

                         $sheet->SetCellValue("B21", "Kimia & Laboratorium");
                         $nilai_kimia = number_format(round($nilai_kimia),0,',','.');
                         $sheet->SetCellValue('C21', $nilai_kimia);

                         $sheet->SetCellValue("B22", $k3);
                         $nilai_k3 = number_format(round($nilai_k3),0,',','.');
                         $sheet->SetCellValue('C22', $nilai_k3);

                         $sheet->SetCellValue("B23", $lingkungan);
                         $nilai_lingkungan = number_format(round($nilai_lingkungan),0,',','.');
                         $sheet->SetCellValue('C23', $nilai_lingkungan);

                         $sheet->SetCellValue("B24", $preventive);
                         $nilai_preventive = number_format(round($nilai_preventive),0,',','.');
                         $sheet->SetCellValue("C24", $nilai_preventive);
 
                         $sheet->SetCellValue("B25", $predictive);
                         $nilai_predictive = number_format(round($nilai_predictive),0,',','.');
                         $sheet->SetCellValue("C25", $nilai_predictive);

                         $sheet->SetCellValue("B26", $corective);
                         $nilai_corective = number_format(round($nilai_corective), 0,',','.');
                         $sheet->SetCellValue("C26", $nilai_corective);

                         $sheet->SetCellValue("B27", 'TOTAL  PEMELIHARAAN');
                         $total = number_format(round($total), 0,',','.');
                         $sheet->SetCellValue("C27", $total);

                         $sheet->cells("B27:C27", function($to){
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
