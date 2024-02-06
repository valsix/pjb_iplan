<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\StrategiBisnis; 
use App\Entities\Distrik; 
use App\Entities\Fase; 
use App\Entities\Jenis;
use App\Entities\Lokasi;
use App\Entities\Template;
use App\Entities\User;
use App\Entities\Role;
use DB;
use Excel;
use PDF;

class MitigasiResikoController extends Controller
{

    public function Mitigasi_Resiko(Request $request)
     {
        ini_set('max_execution_time', 300);   

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
        
         $fs            = Fase::all();
         $tahun         = Template::select('tahun')->where('jenis_id', 7)->orWhere('jenis_id',1)->distinct()->get();
         $input_tahun   = $request->input('tahun');
         $input_sb      = $request->input('strategi_bisnis');
         $input_distrik = $request->input('distrik');
         $input_lokasi  = $request->input('lokasi');
         $input_form_6_reimburse = $request->input('reimburse');
         $input_form_6_rutin = $request->input('rutin');
         $input_form_10_pu = $request->input('usaha');
         $input_form_10_pk = $request->input('kit');
         $input_form_10_pln = $request->input('pln');
         $input_form_10_register = $request->input('register');
         $reimburse     = $request->input('reimburse');
         $rutin         = $request->input('rutin');
         $usaha         = $request->input('usaha');
         $KIT           = $request->input('kit');
         $PLN           = $request->input('pln');
         $register      = $request->input('register');
         $input_fase = $fase          = $request->input('fase');

        // if ($request->input('strategi_bisnis') != NULL) {
        //     $input_tahun = DB::table("strategi_bisnis")->SELECT('name')->WHERE('id', $request->input('strategi_bisnis'))->get()[0];
        // }
        // if ($request->input('distrik') != NULL) {
        //     $input_distrik = DB::table('distrik')->SELECT('name')->WHERE('id', $request->input('distrik'))->get()[0];
        // }

         if ($request->input('strategi_bisnis') != NULL) {
            $input_sb = DB::table('strategi_bisnis')->select('name','id')->where('id', $request->input('strategi_bisnis'))->get()[0];
            $distrik = Distrik::select('name','id')->where('strategi_bisnis_id',$input_sb->id)->get();
        }
        if ($request->input('distrik') != NULL) {
            $input_distrik = DB::table('distrik')->select('name','code1','id')->where('id', $request->input('distrik'))->get()[0];
            $lokasi = Lokasi::select('name','id')->where('distrik_id',$input_distrik->id)->get();
        }
        if ($request->input('lokasi') != NULL) {
            $input_lokasi = DB::table('lokasi')->select('name','id')->where('id', $request->input('lokasi'))->get()[0];
        }
        if ($request->input('fase') != NULL) {
            $input_fase = DB::table('fases')->select('name','id')->where('id', $request->input('fase'))->get()[0];
        }
        if ($request->input('reimburse') != NULL) {
            $input_form_6_reimburse = DB::table('file_imports')->select('draft_versi','id', 'name')->where('id', $request->input('reimburse'))->get()[0];
            $drafts_form_6_reimburse = $this->query_draft(2, $input_lokasi->id, $input_tahun);
        }

        if ($request->input('rutin') != NULL) {
            $input_form_6_rutin = DB::table('file_imports')->select('draft_versi','id', 'name')->where('id', $request->input('rutin'))->get()[0];
            $drafts_form_6_rutin = $this->query_draft(3, $input_lokasi->id, $input_tahun);
        }
        if ($request->input('usaha') != NULL) {
            $input_form_10_pu = DB::table('file_imports')->select('draft_versi','id', 'name')->where('id', $request->input('usaha'))->get()[0];
            $drafts_form_10_pu = $this->query_draft(4, $input_lokasi->id, $input_tahun);
        }
        if ($request->input('kit') != NULL) {
            $input_form_10_pk = DB::table('file_imports')->select('draft_versi','id', 'name')->where('id', $request->input('kit'))->get()[0];
            $drafts_form_10_pk = $this->query_draft(5, $input_lokasi->id, $input_tahun);
        }

        if ($request->input('pln') != NULL) {
            $input_form_10_pln = DB::table('file_imports')->select('draft_versi','id', 'name')->where('id', $request->input('pln'))->get()[0];
            $drafts_form_10_pln = $this->query_draft(6, $input_lokasi->id, $input_tahun);
        }

        if ($request->input('register') != NULL) {
            $input_form_10_register = DB::table('file_imports')->select('draft_versi','id', 'name')->where('id', $request->input('register'))->get()[0];
            $drafts_form_10_register = $this->query_draft(8, $input_lokasi->id, $input_tahun);
        }


        // if ($input_tahun != NULL && $input_sb  != NULL && $input_distrik != NULL && $input_lokasi != NULL && $reimburse != NULL && $rutin != NULL && $usaha != NULL && $KIT != NULL && $PLN != NULL && $register != NULL) {
        if ($input_tahun != NULL && $input_sb  != NULL && $input_distrik != NULL && $input_lokasi != NULL) {

            $queryB = [];
            $queryC = [];
            if($register) {
            $queryB = DB::SELECT("SELECT e.kolom, e.row, e.value
                                 FROM excel_datas e 
                                 join sheets s on s.id = e.sheet_id
                                 where e.file_import_id = '$register'
                                 and s.name like 'I-Risk Register' and kolom like 'B' ");
            $queryC = DB::SELECT("SELECT e.kolom, e.row, e.value
                                 FROM excel_datas e 
                                 join sheets s on s.id = e.sheet_id
                                 where e.file_import_id = '$register'
                                 and s.name like 'I-Risk Register' and  kolom like 'C'");
            }

            $i = 0;
            foreach ($queryB as $key) {
                if ($key->kolom == "B") {
                    $isi = $key->value;

                    $query1 = [];
                    if($reimburse) {
                      $query1 = DB::SELECT("SELECT anggaran.*, desk.deskripsi from (SELECT e.file_import_id, e.lokasi_id, e.sheet_id, e.row, e.value, e.kolom
                          from excel_datas e 
                          join sheets s on s.id = e.sheet_id 
                          where e.lokasi_id = '$input_lokasi->id' and e.file_import_id = '$reimburse' and s.name like 'I-Form 6' and kolom LIKE 'AN' and row in (
                          select e.row
                          from excel_datas e 
                          join sheets s on s.id = e.sheet_id 
                          where e.lokasi_id = '$input_lokasi->id' and e.file_import_id = $reimburse and s.name like 'I-Form 6' and e.kolom like 'Z' and value like '$isi')) as anggaran 
                          join 
                          (SELECT e.row, e.value as deskripsi
                          from excel_datas e 
                          join sheets s on s.id = e.sheet_id 
                          where e.lokasi_id = '$input_lokasi->id' and e.file_import_id = '$reimburse' and s.name like 'I-Form 6' and kolom LIKE 'T' and row in (
                          select e.row
                          from excel_datas e 
                          join sheets s on s.id = e.sheet_id 
                          where e.lokasi_id = '$input_lokasi->id' and e.file_import_id = $reimburse and s.name like 'I-Form 6' and e.kolom like 'Z' and value like '$isi')) as desk
                          on desk.row = anggaran.row");
                    }

                    $query2 = [];
                    if($rutin) {
                      $query2 = DB::SELECT("SELECT anggaran.*, desk.deskripsi from (SELECT e.file_import_id, e.lokasi_id, e.sheet_id, e.row, e.value, e.kolom
                          from excel_datas e 
                          join sheets s on s.id = e.sheet_id 
                          where e.lokasi_id = '$input_lokasi->id' and e.file_import_id = '$rutin' and s.name like 'I-Form 6' and kolom LIKE 'AN' and row in (
                          select e.row
                          from excel_datas e 
                          join sheets s on s.id = e.sheet_id 
                          where e.lokasi_id = '$input_lokasi->id' and e.file_import_id = $rutin and s.name like 'I-Form 6' and e.kolom like 'Z' and value like '$isi')) anggaran
                          join 
                          (SELECT e.row, e.value as deskripsi
                          from excel_datas e 
                          join sheets s on s.id = e.sheet_id 
                          where e.lokasi_id = '$input_lokasi->id' and e.file_import_id = '$rutin' and s.name like 'I-Form 6' and kolom LIKE 'T' and row in (
                          select e.row
                          from excel_datas e 
                          join sheets s on s.id = e.sheet_id 
                          where e.lokasi_id = '$input_lokasi->id' and e.file_import_id = $rutin and s.name like 'I-Form 6' and e.kolom like 'Z' and value like '$isi')) as desk
                          on desk.row = anggaran.row");
                    }

                    $query3 = [];
                    if($usaha) {
                      $query3 = DB::SELECT("SELECT anggaran.*, desk.deskripsi from (SELECT e.file_import_id, e.lokasi_id, e.sheet_id, e.row, e.value 
                                          FROM excel_datas e 
                                          JOIN sheets s on s.id = e.sheet_id 
                                          WHERE e.lokasi_id = '$input_lokasi->id' AND e.file_import_id = '$usaha' and s.name like 'I-Form 10' and kolom LIKE 'AO' AND row in (
                                                  select e.row
                                                  from excel_datas e 
                                                  join sheets s on s.id = e.sheet_id 
                                                  WHERE e.lokasi_id = '$input_lokasi->id' AND e.file_import_id = '$usaha' AND s.name LIKE 'I-Form 10' AND e.kolom LIKE 'Z' and value LIKE '$isi')) anggaran
                                                  join 
                                                  (SELECT e.row, e.value as deskripsi
                                          FROM excel_datas e 
                                          JOIN sheets s on s.id = e.sheet_id 
                                          WHERE e.lokasi_id = '$input_lokasi->id' AND e.file_import_id = '$usaha' and s.name like 'I-Form 10' and kolom LIKE 'T' AND row in (
                                                  select e.row
                                                  from excel_datas e 
                                                  join sheets s on s.id = e.sheet_id 
                                                  WHERE e.lokasi_id = '$input_lokasi->id' AND e.file_import_id = '$usaha' AND s.name LIKE 'I-Form 10' AND e.kolom LIKE 'Z' and value LIKE '$isi')) desk on
                                                  desk.row = anggaran.row");
                    }
                    
                    $query4 = [];
                    if($KIT) {
                      $query4 = DB::SELECT("SELECT anggaran.*, desk.deskripsi from 
                                          (SELECT e.file_import_id, e.lokasi_id, e.sheet_id, e.row, e.value 
                                          FROM excel_datas e 
                                          join sheets s on s.id = e.sheet_id 
                                          WHERE e.lokasi_id = '$input_lokasi->id' and e.file_import_id = '$KIT' and s.name like 'I-Form 10' and kolom LIKE 'AN' and row in (
                                                  SELECT e.row
                                                  FROM excel_datas e 
                                                  join sheets s on s.id = e.sheet_id 
                                                  WHERE e.lokasi_id = '$input_lokasi->id' and e.file_import_id = '$KIT' 
                                                  and s.name like 'I-Form 10' and e.kolom like 'Y' and value like '$isi')) anggaran
                                          join 
                                          (SELECT e.row, e.value as deskripsi
                                          FROM excel_datas e 
                                          join sheets s on s.id = e.sheet_id 
                                          WHERE e.lokasi_id = '$input_lokasi->id' and e.file_import_id = '$KIT' and s.name like 'I-Form 10' and kolom LIKE 'S' and row in (
                                                  SELECT e.row
                                                  FROM excel_datas e 
                                                  join sheets s on s.id = e.sheet_id 
                                                  WHERE e.lokasi_id = '$input_lokasi->id' and e.file_import_id = '$KIT' 
                                                  and s.name like 'I-Form 10' and e.kolom like 'Y' and value like '$isi')) desk
                                                  on desk.row = anggaran.row");
                      }

                      $query5 = [];
                      if($PLN) {
                        $query5 = DB::SELECT("SELECT anggaran.*, desk.deskripsi from 
                                             (SELECT e.file_import_id, e.lokasi_id, e.sheet_id, e.row, e.value 
                                             FROM excel_datas e 
                                             join sheets s on s.id = e.sheet_id 
                                             WHERE e.lokasi_id = '$input_lokasi->id' and e.file_import_id = '$PLN' and s.name like 'I-Form 10' and kolom LIKE 'AP' and row in (
                                                      select e.row
                                                      from excel_datas e 
                                                      join sheets s on s.id = e.sheet_id 
                                                      where e.lokasi_id = '$input_lokasi->id' and e.file_import_id = '$PLN' and s.name like 'I-Form 10' and e.kolom like 'AA' and value like '$isi')) as anggaran
                                              join
                                              (SELECT e.row, e.value as deskripsi
                                             FROM excel_datas e 
                                             join sheets s on s.id = e.sheet_id 
                                             WHERE e.lokasi_id = '$input_lokasi->id' and e.file_import_id = '$PLN' and s.name like 'I-Form 10' and kolom LIKE 'U' and row in (
                                                      select e.row
                                                      from excel_datas e 
                                                      join sheets s on s.id = e.sheet_id 
                                                      where e.lokasi_id = '$input_lokasi->id' and e.file_import_id = '$PLN' and s.name like 'I-Form 10' and e.kolom like 'AA' and value like '$isi')) as desk
                                              on desk.row = anggaran.row");
                    }

                    $gabung = array_merge($query1, $query2, $query3, $query4, $query5); 

                    // if($isi == "GR01242") {
                    //     dump($isi, $input_lokasi, $PLN, $query1, $query2, $query3, $query4, $query5);
                    //     // dump($gabung);
                    // }                   
                }
                    $total = 0;
                    $gabung_tiap_risk_profile = [];
                    $r = 0;
                    foreach ($gabung as $d) {
                        $gabung_tiap_risk_profile[$r]['jumlah'] = $d->value;
                        if(isset($d->deskripsi)) {
                            $gabung_tiap_risk_profile[$r]['deskripsi'] = $d->deskripsi;
                        }
                        else {
                            $gabung_tiap_risk_profile[$r]['deskripsi'] = '';
                        }
                        $total = $gabung_tiap_risk_profile[$r]['jumlah'] + $total;
                        $r++;
                    }

                    $totalB[$i] = $total;
                    $total_prk_tiap_risk_profile[$i] = count($gabung_tiap_risk_profile);

                    usort($gabung_tiap_risk_profile, $this->urutkan_desc('jumlah'));
                    $detail_anggaran_tiap_risk_profile[$i] = $gabung_tiap_risk_profile;
                    $i++;
                  
            } //end foreach queryB
            // die();   

           switch ($request->unduh) {
               case 'pdf':
                    return $this->unduhPDF($request, $input_tahun);
                   break;
               case 'excel':
                    return $this->unduhExcel($request);
                   break;
               default:
                  return view('output/mitigasi-risiko', compact('sb', 'input_tahun', 'tahun', 'input_sb', 'input_distrik', 'distrik', 'lokasi', 'input_lokasi', 'fase', 'input_fase', 'fs', 'drafts_form_6_reimburse', 'input_form_6_reimburse', 'input_form_6_rutin', 'drafts_form_6_rutin', 'input_form_10_pu', 'drafts_form_10_pu', 'input_form_10_pk', 'drafts_form_10_pk', 'input_form_10_pln', 'drafts_form_10_pln', 'input_form_10_register', 'drafts_form_10_register', 'queryB', 'queryC', 'query1', 'totalB', 'total_prk_tiap_risk_profile', 'detail_anggaran_tiap_risk_profile'));
                   break;
           }
    
        }
    	return view('output/mitigasi-risiko', compact('sb', 'input_tahun', 'tahun', 'input_sb', 'distrik', 'input_distrik', 'lokasi', 'input_lokasi', 'fase', 'input_fase', 'fs', 'drafts_form_6_reimburse', 'input_form_6_reimburse', 'input_form_6_rutin', 'drafts_form_6_rutin', 'input_form_10_pu', 'drafts_form_10_pu', 'input_form_10_pk', 'drafts_form_10_pk', 'input_form_10_pln', 'drafts_form_10_pln', 'input_form_10_register', 'drafts_form_10_register', 'queryB', 'queryC', 'query1', 'totalB', 'gabung', 'total_prk_tiap_risk_profile', 'detail_anggaran_tiap_risk_profile'));
     }

    function urutkan_desc($key) {
        return function ($a, $b) use ($key) {
            // return strnatcmp($a[$key], $b[$key]);
            return ($a[$key] < $b[$key]);
        };
    }

    public function query_draft($jenis_id, $id_lokasi, $id_tahun)
    {
        $draft= DB::select("select distinct f.id, f.draft_versi 
            from file_imports f 
            join templates t on f.template_id = t.id
            join excel_datas e on e.file_import_id = f.id
            where t.jenis_id=".$jenis_id." and e.lokasi_id = ".$id_lokasi." and t.tahun= ".$id_tahun." 
            group by f.id, f.draft_versi;");
        return $draft;
    }

    private function unduhPDF(Request $request)
    {
        $register  = $request->input('register');
        $tahun     = $request->input('tahun');
        $bisnis1   = $request->input('strategi_bisnis');
        $bisnis    = DB::SELECT("SELECT * FROM strategi_bisnis WHERE id='$bisnis1'");
        $dis       = $request->input('distrik');
        $distrik   = DB::SELECT("SELECT * FROM distrik WHERE id='$dis'");
        $input_lokasi       = $request->input('lokasi');
        $lokasi    = DB::SELECT("SELECT * FROM lokasi WHERE id='$input_lokasi'");
        $fase1     = $request->input('fase');
        $fase      = DB::SELECT("SELECT * FROM fases WHERE id='$fase1'");
        $reimburse = $request->input('reimburse');
        $rutin     = $request->input('rutin');
        $usaha     = $request->input('usaha');
        $KIT       = $request->input('kit');
        $PLN       = $request->input('pln');

        $queryB    = DB::SELECT("SELECT e.kolom, e.row, e.value
                             FROM excel_datas e 
                             join sheets s on s.id = e.sheet_id
                             where e.file_import_id = '$register'
                             and s.name like 'I-Risk Register' and kolom like 'B'");
        $queryC    = DB::SELECT("SELECT e.kolom, e.row, e.value
                             FROM excel_datas e 
                             join sheets s on s.id = e.sheet_id
                             where e.file_import_id = '$register'
                             and s.name like 'I-Risk Register' and  kolom like 'C'");
        $i = 0;
        foreach ($queryB as $key) {
          if ($key->kolom == "B") {
               $isi = $key->value;
               $query1 = DB::SELECT("SELECT anggaran.*, desk.deskripsi from (SELECT e.file_import_id, e.lokasi_id, e.sheet_id, e.row, e.value, e.kolom
                        from excel_datas e 
                        join sheets s on s.id = e.sheet_id 
                        where e.lokasi_id = '$input_lokasi' and e.file_import_id = '$reimburse' and s.name like 'I-Form 6' and kolom LIKE 'AN' and row in (
                        select e.row
                        from excel_datas e 
                        join sheets s on s.id = e.sheet_id 
                        where e.lokasi_id = '$input_lokasi' and e.file_import_id = $reimburse and s.name like 'I-Form 6' and e.kolom like 'Z' and value like '$isi')) as anggaran 
                        join 
                        (SELECT e.row, e.value as deskripsi
                        from excel_datas e 
                        join sheets s on s.id = e.sheet_id 
                        where e.lokasi_id = '$input_lokasi' and e.file_import_id = '$reimburse' and s.name like 'I-Form 6' and kolom LIKE 'T' and row in (
                        select e.row
                        from excel_datas e 
                        join sheets s on s.id = e.sheet_id 
                        where e.lokasi_id = '$input_lokasi' and e.file_import_id = $reimburse and s.name like 'I-Form 6' and e.kolom like 'Z' and value like '$isi')) as desk
                        on desk.row = anggaran.row");

                    $query2 = DB::SELECT("SELECT anggaran.*, desk.deskripsi from (SELECT e.file_import_id, e.lokasi_id, e.sheet_id, e.row, e.value, e.kolom
                        from excel_datas e 
                        join sheets s on s.id = e.sheet_id 
                        where e.lokasi_id = '$input_lokasi' and e.file_import_id = '$rutin' and s.name like 'I-Form 6' and kolom LIKE 'AN' and row in (
                        select e.row
                        from excel_datas e 
                        join sheets s on s.id = e.sheet_id 
                        where e.lokasi_id = '$input_lokasi' and e.file_import_id = $rutin and s.name like 'I-Form 6' and e.kolom like 'Z' and value like '$isi')) anggaran
                        join 
                        (SELECT e.row, e.value as deskripsi
                        from excel_datas e 
                        join sheets s on s.id = e.sheet_id 
                        where e.lokasi_id = '$input_lokasi' and e.file_import_id = '$rutin' and s.name like 'I-Form 6' and kolom LIKE 'T' and row in (
                        select e.row
                        from excel_datas e 
                        join sheets s on s.id = e.sheet_id 
                        where e.lokasi_id = '$input_lokasi' and e.file_import_id = $rutin and s.name like 'I-Form 6' and e.kolom like 'Z' and value like '$isi')) as desk
                        on desk.row = anggaran.row");

                    $query3 = DB::SELECT("SELECT anggaran.*, desk.deskripsi from (SELECT e.file_import_id, e.lokasi_id, e.sheet_id, e.row, e.value 
                                        FROM excel_datas e 
                                        JOIN sheets s on s.id = e.sheet_id 
                                        WHERE e.lokasi_id = '$input_lokasi' AND e.file_import_id = '$usaha' and s.name like 'I-Form 10' and kolom LIKE 'AO' AND row in (
                                                select e.row
                                                from excel_datas e 
                                                join sheets s on s.id = e.sheet_id 
                                                WHERE e.lokasi_id = '$input_lokasi' AND e.file_import_id = '$usaha' AND s.name LIKE 'I-Form 10' AND e.kolom LIKE 'Z' and value LIKE '$isi')) anggaran
                                                join 
                                                (SELECT e.row, e.value as deskripsi
                                        FROM excel_datas e 
                                        JOIN sheets s on s.id = e.sheet_id 
                                        WHERE e.lokasi_id = '$input_lokasi' AND e.file_import_id = '$usaha' and s.name like 'I-Form 10' and kolom LIKE 'T' AND row in (
                                                select e.row
                                                from excel_datas e 
                                                join sheets s on s.id = e.sheet_id 
                                                WHERE e.lokasi_id = '$input_lokasi' AND e.file_import_id = '$usaha' AND s.name LIKE 'I-Form 10' AND e.kolom LIKE 'Z' and value LIKE '$isi')) desk on
                                                desk.row = anggaran.row");
                    
                    $query4 = DB::SELECT("SELECT anggaran.*, desk.deskripsi from 
                                        (SELECT e.file_import_id, e.lokasi_id, e.sheet_id, e.row, e.value 
                                        FROM excel_datas e 
                                        join sheets s on s.id = e.sheet_id 
                                        WHERE e.lokasi_id = '$input_lokasi' and e.file_import_id = '$KIT' and s.name like 'I-Form 10' and kolom LIKE 'AN' and row in (
                                                SELECT e.row
                                                FROM excel_datas e 
                                                join sheets s on s.id = e.sheet_id 
                                                WHERE e.lokasi_id = '$input_lokasi' and e.file_import_id = '$KIT' 
                                                and s.name like 'I-Form 10' and e.kolom like 'Y' and value like '$isi')) anggaran
                                        join 
                                        (SELECT e.row, e.value as deskripsi
                                        FROM excel_datas e 
                                        join sheets s on s.id = e.sheet_id 
                                        WHERE e.lokasi_id = '$input_lokasi' and e.file_import_id = '$KIT' and s.name like 'I-Form 10' and kolom LIKE 'S' and row in (
                                                SELECT e.row
                                                FROM excel_datas e 
                                                join sheets s on s.id = e.sheet_id 
                                                WHERE e.lokasi_id = '$input_lokasi' and e.file_import_id = '$KIT' 
                                                and s.name like 'I-Form 10' and e.kolom like 'Y' and value like '$isi')) desk
                                                on desk.row = anggaran.row");

                     $query5 = DB::SELECT("SELECT anggaran.*, desk.deskripsi from 
                                           (SELECT e.file_import_id, e.lokasi_id, e.sheet_id, e.row, e.value 
                                           FROM excel_datas e 
                                           join sheets s on s.id = e.sheet_id 
                                           WHERE e.lokasi_id = '$input_lokasi' and e.file_import_id = '$PLN' and s.name like 'I-Form 10' and kolom LIKE 'AP' and row in (
                                                    select e.row
                                                    from excel_datas e 
                                                    join sheets s on s.id = e.sheet_id 
                                                    where e.lokasi_id = '$input_lokasi' and e.file_import_id = '$PLN' and s.name like 'I-Form 10' and e.kolom like 'AA' and value like '$isi')) as anggaran
                                            join
                                            (SELECT e.row, e.value as deskripsi
                                           FROM excel_datas e 
                                           join sheets s on s.id = e.sheet_id 
                                           WHERE e.lokasi_id = '$input_lokasi' and e.file_import_id = '$PLN' and s.name like 'I-Form 10' and kolom LIKE 'U' and row in (
                                                    select e.row
                                                    from excel_datas e 
                                                    join sheets s on s.id = e.sheet_id 
                                                    where e.lokasi_id = '$input_lokasi' and e.file_import_id = '$PLN' and s.name like 'I-Form 10' and e.kolom like 'AA' and value like '$isi')) as desk
                                            on desk.row = anggaran.row");
            $gabung = array_merge($query1, $query2, $query3, $query4, $query5);
          }
            $total = 0;
            $gabung_tiap_risk_profile = [];
            $r = 0;
            foreach ($gabung as $d) {
                $gabung_tiap_risk_profile[$r]['jumlah'] = $d->value;
                if(isset($d->deskripsi)) {
                    $gabung_tiap_risk_profile[$r]['deskripsi'] = $d->deskripsi;
                }
                else {
                    $gabung_tiap_risk_profile[$r]['deskripsi'] = '';
                }
                $total = $gabung_tiap_risk_profile[$r]['jumlah'] + $total;
                $r++;
            }

            $totalB[$i] = $total;
            $total_prk_tiap_risk_profile[$i] = count($gabung_tiap_risk_profile);

            usort($gabung_tiap_risk_profile, $this->urutkan_desc('jumlah'));
            $detail_anggaran_tiap_risk_profile[$i] = $gabung_tiap_risk_profile;
            $i++;
        }
        $pdf = PDF::LoadView('output/mitigasi-risiko-pdf', compact('tahun','bisnis','distrik','lokasi','fase','queryB','queryC','totalB','detail_anggaran_tiap_risk_profile','total_prk_tiap_risk_profile'))->setPaper('A4', 'landscape');
        return $pdf->download('laporan mitigasi risiko.pdf');
    }

    // private function unduhExcel(Request $request, $queryB, $queryC, $totalB)
    private function unduhExcel(Request $request)
    {
        $register  = $request->input('register');
        $tahun     = $request->input('tahun');
        $bisnis1   = $request->input('strategi_bisnis');
        $bisnis    = DB::SELECT("SELECT * FROM strategi_bisnis WHERE id='$bisnis1'");
        $dis       = $request->input('distrik');
        $distrik   = DB::SELECT("SELECT * FROM distrik WHERE id='$dis'");
        $input_lokasi       = $request->input('lokasi');
        $lokasi    = DB::SELECT("SELECT * FROM lokasi WHERE id='$input_lokasi'");
        $fase1     = $request->input('fase');
        $fase      = DB::SELECT("SELECT * FROM fases WHERE id='$fase1'");
        $reimburse = $request->input('reimburse');
        $rutin     = $request->input('rutin');
        $usaha     = $request->input('usaha');
        $KIT       = $request->input('kit');
        $PLN       = $request->input('pln');

      $tahun  = $request->input('tahun');
      $bisnis = StrategiBisnis::all(); 
      $l      = $request->input('lokasi');
      $dis    = Distrik::all();
      $sistem = DB::TABLE("fases")->get()->toArray();
      
        $queryB    = DB::SELECT("SELECT e.kolom, e.row, e.value
                             FROM excel_datas e 
                             join sheets s on s.id = e.sheet_id
                             where e.file_import_id = '$register'
                             and s.name like 'I-Risk Register' and kolom like 'B'");
        $queryC    = DB::SELECT("SELECT e.kolom, e.row, e.value
                             FROM excel_datas e 
                             join sheets s on s.id = e.sheet_id
                             where e.file_import_id = '$register'
                             and s.name like 'I-Risk Register' and  kolom like 'C'");

        $i = 0;
        foreach ($queryB as $key) {
          if ($key->kolom == "B") {
               $isi = $key->value;
               $query1 = DB::SELECT("SELECT anggaran.*, desk.deskripsi from (SELECT e.file_import_id, e.lokasi_id, e.sheet_id, e.row, e.value, e.kolom
                        from excel_datas e 
                        join sheets s on s.id = e.sheet_id 
                        where e.lokasi_id = '$input_lokasi' and e.file_import_id = '$reimburse' and s.name like 'I-Form 6' and kolom LIKE 'AN' and row in (
                        select e.row
                        from excel_datas e 
                        join sheets s on s.id = e.sheet_id 
                        where e.lokasi_id = '$input_lokasi' and e.file_import_id = $reimburse and s.name like 'I-Form 6' and e.kolom like 'Z' and value like '$isi')) as anggaran 
                        join 
                        (SELECT e.row, e.value as deskripsi
                        from excel_datas e 
                        join sheets s on s.id = e.sheet_id 
                        where e.lokasi_id = '$input_lokasi' and e.file_import_id = '$reimburse' and s.name like 'I-Form 6' and kolom LIKE 'T' and row in (
                        select e.row
                        from excel_datas e 
                        join sheets s on s.id = e.sheet_id 
                        where e.lokasi_id = '$input_lokasi' and e.file_import_id = $reimburse and s.name like 'I-Form 6' and e.kolom like 'Z' and value like '$isi')) as desk
                        on desk.row = anggaran.row");

                    $query2 = DB::SELECT("SELECT anggaran.*, desk.deskripsi from (SELECT e.file_import_id, e.lokasi_id, e.sheet_id, e.row, e.value, e.kolom
                        from excel_datas e 
                        join sheets s on s.id = e.sheet_id 
                        where e.lokasi_id = '$input_lokasi' and e.file_import_id = '$rutin' and s.name like 'I-Form 6' and kolom LIKE 'AN' and row in (
                        select e.row
                        from excel_datas e 
                        join sheets s on s.id = e.sheet_id 
                        where e.lokasi_id = '$input_lokasi' and e.file_import_id = $rutin and s.name like 'I-Form 6' and e.kolom like 'Z' and value like '$isi')) anggaran
                        join 
                        (SELECT e.row, e.value as deskripsi
                        from excel_datas e 
                        join sheets s on s.id = e.sheet_id 
                        where e.lokasi_id = '$input_lokasi' and e.file_import_id = '$rutin' and s.name like 'I-Form 6' and kolom LIKE 'T' and row in (
                        select e.row
                        from excel_datas e 
                        join sheets s on s.id = e.sheet_id 
                        where e.lokasi_id = '$input_lokasi' and e.file_import_id = $rutin and s.name like 'I-Form 6' and e.kolom like 'Z' and value like '$isi')) as desk
                        on desk.row = anggaran.row");

                    $query3 = DB::SELECT("SELECT anggaran.*, desk.deskripsi from (SELECT e.file_import_id, e.lokasi_id, e.sheet_id, e.row, e.value 
                                        FROM excel_datas e 
                                        JOIN sheets s on s.id = e.sheet_id 
                                        WHERE e.lokasi_id = '$input_lokasi' AND e.file_import_id = '$usaha' and s.name like 'I-Form 10' and kolom LIKE 'AO' AND row in (
                                                select e.row
                                                from excel_datas e 
                                                join sheets s on s.id = e.sheet_id 
                                                WHERE e.lokasi_id = '$input_lokasi' AND e.file_import_id = '$usaha' AND s.name LIKE 'I-Form 10' AND e.kolom LIKE 'Z' and value LIKE '$isi')) anggaran
                                                join 
                                                (SELECT e.row, e.value as deskripsi
                                        FROM excel_datas e 
                                        JOIN sheets s on s.id = e.sheet_id 
                                        WHERE e.lokasi_id = '$input_lokasi' AND e.file_import_id = '$usaha' and s.name like 'I-Form 10' and kolom LIKE 'T' AND row in (
                                                select e.row
                                                from excel_datas e 
                                                join sheets s on s.id = e.sheet_id 
                                                WHERE e.lokasi_id = '$input_lokasi' AND e.file_import_id = '$usaha' AND s.name LIKE 'I-Form 10' AND e.kolom LIKE 'Z' and value LIKE '$isi')) desk on
                                                desk.row = anggaran.row");
                    
                    $query4 = DB::SELECT("SELECT anggaran.*, desk.deskripsi from 
                                        (SELECT e.file_import_id, e.lokasi_id, e.sheet_id, e.row, e.value 
                                        FROM excel_datas e 
                                        join sheets s on s.id = e.sheet_id 
                                        WHERE e.lokasi_id = '$input_lokasi' and e.file_import_id = '$KIT' and s.name like 'I-Form 10' and kolom LIKE 'AN' and row in (
                                                SELECT e.row
                                                FROM excel_datas e 
                                                join sheets s on s.id = e.sheet_id 
                                                WHERE e.lokasi_id = '$input_lokasi' and e.file_import_id = '$KIT' 
                                                and s.name like 'I-Form 10' and e.kolom like 'Y' and value like '$isi')) anggaran
                                        join 
                                        (SELECT e.row, e.value as deskripsi
                                        FROM excel_datas e 
                                        join sheets s on s.id = e.sheet_id 
                                        WHERE e.lokasi_id = '$input_lokasi' and e.file_import_id = '$KIT' and s.name like 'I-Form 10' and kolom LIKE 'S' and row in (
                                                SELECT e.row
                                                FROM excel_datas e 
                                                join sheets s on s.id = e.sheet_id 
                                                WHERE e.lokasi_id = '$input_lokasi' and e.file_import_id = '$KIT' 
                                                and s.name like 'I-Form 10' and e.kolom like 'Y' and value like '$isi')) desk
                                                on desk.row = anggaran.row");

                     $query5 = DB::SELECT("SELECT anggaran.*, desk.deskripsi from 
                                           (SELECT e.file_import_id, e.lokasi_id, e.sheet_id, e.row, e.value 
                                           FROM excel_datas e 
                                           join sheets s on s.id = e.sheet_id 
                                           WHERE e.lokasi_id = '$input_lokasi' and e.file_import_id = '$PLN' and s.name like 'I-Form 10' and kolom LIKE 'AP' and row in (
                                                    select e.row
                                                    from excel_datas e 
                                                    join sheets s on s.id = e.sheet_id 
                                                    where e.lokasi_id = '$input_lokasi' and e.file_import_id = '$PLN' and s.name like 'I-Form 10' and e.kolom like 'AA' and value like '$isi')) as anggaran
                                            join
                                            (SELECT e.row, e.value as deskripsi
                                           FROM excel_datas e 
                                           join sheets s on s.id = e.sheet_id 
                                           WHERE e.lokasi_id = '$input_lokasi' and e.file_import_id = '$PLN' and s.name like 'I-Form 10' and kolom LIKE 'U' and row in (
                                                    select e.row
                                                    from excel_datas e 
                                                    join sheets s on s.id = e.sheet_id 
                                                    where e.lokasi_id = '$input_lokasi' and e.file_import_id = '$PLN' and s.name like 'I-Form 10' and e.kolom like 'AA' and value like '$isi')) as desk
                                            on desk.row = anggaran.row");
            $gabung = array_merge($query1, $query2, $query3, $query4, $query5);
          }
            $total = 0;
            $gabung_tiap_risk_profile = [];
            $r = 0;
            foreach ($gabung as $d) {
                $gabung_tiap_risk_profile[$r]['jumlah'] = $d->value;
                if(isset($d->deskripsi)) {
                    $gabung_tiap_risk_profile[$r]['deskripsi'] = $d->deskripsi;
                }
                else {
                    $gabung_tiap_risk_profile[$r]['deskripsi'] = '';
                }
                $total = $gabung_tiap_risk_profile[$r]['jumlah'] + $total;
                $r++;
            }

            $totalB[$i] = $total;
            $total_prk_tiap_risk_profile[$i] = count($gabung_tiap_risk_profile);

            usort($gabung_tiap_risk_profile, $this->urutkan_desc('jumlah'));
            $detail_anggaran_tiap_risk_profile[$i] = $gabung_tiap_risk_profile;
            $i++;
        }


      $lokasi = DB::SELECT("SELECT * FROM lokasi WHERE id='$l'");
       Excel::create('Mitigasi Resiko', function($excel) use ($tahun, $bisnis, $lokasi, $dis, $queryB, $queryC,$totalB, $total_prk_tiap_risk_profile, $detail_anggaran_tiap_risk_profile) {
            $excel->setTitle('Mitigasi Resiko');
            $excel->setCreator('Laravel-5.5')->setCompany('PJB');
            $excel->sheet('Excel sheet', function($sheet) use ($tahun, $bisnis, $lokasi, $dis, $queryB, $queryC,$totalB, $total_prk_tiap_risk_profile, $detail_anggaran_tiap_risk_profile) {
               $sheet->setWidth("B", 17);
               $sheet->setWidth("C", 25);
               $sheet->setWidth("D", 59);
               $sheet->setWidth("E", 15);
                    $sheet->SetCellValue('A1', "Daftar Isi");
                    $sheet->cells('A1', function($f) {
                        $f->setBackground("#159E9C");
                        $f->setFont(array
                                        (
                                            'family'=>'Calibri',
                                            'size'=>'12',
                                            'bold'=> true
                                        )
                                    );
                        $f->setFontColor('#ffffff');
                    });
                $sheet->cells('B4:B7', function($cell){
                        $cell->setBackground("#159E9C");
                        $cell->setFont(array
                                            (
                                                'family'=>'Calibri',
                                                'size'=>'12',
                                                'bold'=> true
                                            )
                                        );
                        $cell->setFontColor('#ffffff');
                    }); 
                $sheet->setBorder("B4:B7","thin");
                $sheet->SetCellValue('B4', 'Tahun Anggaran');
                $sheet->SetCellValue("C4", $tahun);

                $sheet->_parent->addNamedRange(
                        new \PHPExcel_NamedRange('Tahun', $sheet, 'C4'
                        )
                );
                $tahun = $sheet->getCell('C4')->getDataValidation()->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                $tahun->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $tahun->setAllowBlank(false);
                $tahun->setShowInputMessage(true);
                $tahun->setShowErrorMessage(true);
                $tahun->setShowDropDown(true);
                $tahun->setFormula1('Tahun'); //note this! 

                $sheet->SetCellValue('B5', 'Struktur Bisnis');
                foreach ($bisnis as $key) {
                  $st = $key->name;
                }
                $sheet->SetCellValue('C5', $st);
                $sheet->_parent->addNamedRange(
                        new \PHPExcel_NamedRange('Bisnis', $sheet, 'C5')
                  );
                $loksi = $sheet->getCell('C5')->getDataValidation()->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                $loksi->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $loksi->setAllowBlank(false);   
                $loksi->setShowErrorMessage(true);
                $loksi->setShowErrorMessage(true);
                $loksi->setShowDropDown(true);
                $loksi->setFormula1('Bisnis');
                foreach ($dis as $key) {
                  $distik = $key->name;
                } 
                $sheet->SetCellValue('B6', 'Distrik');
                $sheet->SetCellValue('C6', $distik);
                $sheet->_parent->addNamedRange(
                        new \PHPExcel_NamedRange('Distrik1', $sheet, 'C6')
                  );
                $loksi = $sheet->getCell('C6')->getDataValidation()->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                $loksi->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $loksi->setAllowBlank(false);   
                $loksi->setShowErrorMessage(true);
                $loksi->setShowErrorMessage(true);
                $loksi->setShowDropDown(true);
                $loksi->setFormula1('Distrik1');

                foreach ($lokasi as $lok ) {
                  $loksia = $lok->name;
                }
                $sheet->SetCellValue('B7', 'Lokasi');
                $sheet->SetCellValue('C7', $loksia);
                $sheet->_parent->addNamedRange(
                        new \PHPExcel_NamedRange('Lokasi', $sheet, 'C7')
                  );
                $loksi = $sheet->getCell('C7')->getDataValidation()->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                $loksi->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $loksi->setAllowBlank(false);   
                $loksi->setShowErrorMessage(true);
                $loksi->setShowErrorMessage(true);
                $loksi->setShowDropDown(true);
                $loksi->setFormula1('Lokasi');

                 $sheet->setBorder('B10:E10', 'thin', 'solid');
                    $sheet->cells('B10:E10', function($cells){
                        $cells->setBackground("#4ECDC4");
                        $cells->setAlignment('left');
                        $cells->setFont(array
                                            (
                                                'family'=>'Calibri',
                                                'size'=>'12'
                                        ));
                    });
                    $sheet->SetCellValue("B10", "Risk Tag");
                    $sheet->SetCellValue("C10", "Risk Event");
                    $sheet->SetCellValue("D10", "Rencana program penanganan Risiko");
                    $sheet->SetCellValue("E10", "Nilai Anggaran");

                    $no = 10;
                    $i  = 0;
                    foreach ($queryB as $key) {
                        $no++;
                        $sheet->setBorder("B".$no.":E".$no, 'thin');
                        $sheet->SetCellValue("B".$no, $key->value);
                        foreach($queryC as $q) {
                            if($key->row == $q->row) {
                                $sheet->SetCellValue("C".$no, $q->value);
                            }
                        }
                        $sheet->SetCellValue("D".$no, 'Total PRK: '.$total_prk_tiap_risk_profile[$i]);
                        // $sheet->SetCellValue("E".$no, number_format(round($totalB[$i]),0));
                        $sheet->SetCellValue("E".$no, round($totalB[$i]));
                        // $d = 1;
                        
                        foreach($detail_anggaran_tiap_risk_profile[$i] as $detail_risk_profile) {
                            $no++;
                            $sheet->setBorder("B".$no.":E".$no, 'thin');
                            // if($d<=5) {
                                $sheet->SetCellValue("B".$no, '');
                                $sheet->SetCellValue("C".$no, '');
                                $sheet->SetCellValue("D".$no, $detail_risk_profile['deskripsi']);
                                // $sheet->SetCellValue("E".$no, number_format(round($detail_risk_profile['jumlah']),0));
                                $sheet->SetCellValue("E".$no, $detail_risk_profile['jumlah']);
                            // }
                            // $d++;
                        }
                        $i++;
                    }
            });
         })->download('xlsx');
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

    public function AJaxJenis()
    {
        $jenis = Jenis::all();

        return json_encode($jenis);
    }

    public function ajax_draft_form_6_reimburse($id_lokasi, $id_tahun)
    {
        $draft_form_6_reimburse = DB::select("SELECT distinct f.id, f.draft_versi 
                                              FROM file_imports f 
                                              JOIN templates t ON f.template_id = t.id
                                              JOIN excel_datas e ON e.file_import_id = f.id
                                              WHERE t.jenis_id=2 AND e.lokasi_id = '$id_lokasi' AND t.tahun= '$id_tahun'
                                              GROUP BY f.id, f.draft_versi");

        return json_encode($draft_form_6_reimburse);
    }

    public function ajax_draft_form_6_rutin($id_lokasi, $id_tahun)
    {
        $draft_form_6_rutin = DB::select("SELECT distinct f.id, f.draft_versi 
                                          FROM file_imports f 
                                          JOIN templates t on f.template_id = t.id
                                          JOIN excel_datas e on e.file_import_id = f.id
                                          WHERE t.jenis_id=3 and e.lokasi_id ='$id_lokasi' and t.tahun='$id_tahun'
                                          group by f.id, f.draft_versi");

        return json_encode($draft_form_6_rutin);
    }

    public function ajax_draft_form_10_pengembangan_usaha($id_lokasi, $id_tahun)
  {
    $draft_form_10_pu = DB::select("select distinct f.id, f.draft_versi
    from file_imports f
    join templates t on f.template_id = t.id
    join excel_datas e on e.file_import_id = f.id
    where t.jenis_id=4 and e.lokasi_id = ".$id_lokasi." and t.tahun= ".$id_tahun."
    group by f.id, f.draft_versi;");

    return json_encode($draft_form_10_pu);
  }

  public function ajax_draft_form_10_penguatan_kit($id_lokasi, $id_tahun)
  {
     $draft_form_10_usaha = DB::select("select distinct f.id, f.draft_versi
    from file_imports f
    join templates t on f.template_id = t.id
    join excel_datas e on e.file_import_id = f.id
    where t.jenis_id=5 and e.lokasi_id = ".$id_lokasi." and t.tahun= ".$id_tahun."
    group by f.id, f.draft_versi;");

    return json_encode($draft_form_10_usaha);
  }

  public function ajax_draft_form_10_pln($id_lokasi, $id_tahun)
  {
    $draft_form_10_pln = DB::select("select distinct f.id, f.draft_versi
    from file_imports f
    join templates t on f.template_id = t.id
    join excel_datas e on e.file_import_id = f.id
    where t.jenis_id=6 and e.lokasi_id = ".$id_lokasi." and t.tahun= ".$id_tahun."
    group by f.id, f.draft_versi;");

    return json_encode($draft_form_10_pln);
  }

  public function ajax_draft_form_risk_register($id_lokasi, $id_tahun)
  {
    $sql = DB::SELECT("SELECT distinct f.id, f.draft_versi
                       FROM file_imports f 
                       join templates t on f.template_id = t.id
                       join excel_datas e on e.file_import_id = f.id
                       where t.jenis_id=8 and e.lokasi_id = ".$id_lokasi." and f.tahun = ".$id_tahun."");
    return json_encode($sql);
  }

}
