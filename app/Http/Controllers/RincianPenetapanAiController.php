<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\StrategiBisnis;
use App\Entities\Distrik;
use App\Entities\Fase;
use App\Entities\Lokasi;
use App\Entities\User;
use App\Entities\Role;
use DB;
use input;
use PDF;
use Excel;

class RincianPenetapanAiController extends Controller
{
    public function Rincian_Penetapan_Ai(Request $rq)
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
        $fases = Fase::get();

        $input_tahun = $rq->input('tahun_anggaran');
        $input_sb = $rq->input('strategi_bisnis');
        $input_distrik = $rq->input('distrik');
        $idistrik = $input_distrik;
        $input_lokasi = $rq->input('lokasi');
        $ilokasi = $input_lokasi;
        $idraft = $rq->input('draft1');
        // $input_fase = $rq->input('fase');

        $count = NULL;

        if ($rq->input('strategi_bisnis') != NULL) {
            $input_sb = DB::table('strategi_bisnis')->select('name')->where('id', $rq->input('strategi_bisnis'))->get()[0];
        }
        if ($rq->input('distrik') != NULL) {
            $input_distrik = DB::table('distrik')->select('name')->where('id', $rq->distrik)->get()[0];
        }
        if ($rq->input('lokasi') != NULL) {
            $input_lokasi = DB::table('lokasi')->select('name')->where('id', $rq->lokasi)->get()[0];
        }
        if ($rq->input('draft1') != NULL) {
            $draft = DB::table('file_imports')->select('draft_versi', 'name')->where('id', $rq->draft1)->get()[0];
        }
        if ($rq->input('fase') != NULL) {
            $input_fase = DB::table('fases')->select('name')->where('id', $rq->input('fase'))->get()[0];
        }


        if($input_lokasi != NULL && $input_tahun != NULL && $idraft != NULL){
            $query = DB::select("select e.* from excel_datas e
                                join sheets s on s.id = e.sheet_id
                                join file_imports f on f.id = e.file_import_id
                                where s.name like 'I-Form 10'
                                and e.file_import_id = ".$rq->draft1."
                                and e.lokasi_id = ".$rq->lokasi."
                                and (e.kolom = 'H' or e.kolom = 'S' or e.kolom = 'AL' or e.kolom = 'AM' or e.kolom = 'AN' or e.kolom = 'AY' or e.kolom = 'AZ' or e.kolom = 'BA' or e.kolom = 'AX' or e.kolom = 'BB' or e.kolom = 'AT')");

            $kodePRK = DB::select("select e.* from excel_datas e
                                join sheets s on s.id = e.sheet_id
                                join file_imports f on f.id = e.file_import_id
                                where s.name like 'I-Form 10'
                                and e.file_import_id = ".$rq->draft1."
                                and e.lokasi_id = ".$rq->lokasi."
                                and e.kolom = 'H'
                                order by e.row, e.kolom");

            $deskPRK = DB::select("select e.* from excel_datas e
                                join sheets s on s.id = e.sheet_id
                                join file_imports f on f.id = e.file_import_id
                                where s.name like 'I-Form 10'
                                and e.file_import_id = ".$rq->draft1."
                                and e.lokasi_id = ".$rq->lokasi."
                                and e.kolom = 'S'
                                order by e.row, e.kolom");

            $anggaranIL = DB::select("select e.* from excel_datas e
                                join sheets s on s.id = e.sheet_id
                                join file_imports f on f.id = e.file_import_id
                                where s.name like 'I-Form 10'
                                and e.file_import_id = ".$rq->draft1."
                                and e.lokasi_id = ".$rq->lokasi."
                                and e.kolom = 'AL'
                                order by e.row, e.kolom");

            $anggaranIM = DB::select("select e.* from excel_datas e
                                join sheets s on s.id = e.sheet_id
                                join file_imports f on f.id = e.file_import_id
                                where s.name like 'I-Form 10'
                                and e.file_import_id = ".$rq->draft1."
                                and e.lokasi_id = ".$rq->lokasi."
                                and e.kolom = 'AM'
                                order by e.row, e.kolom");

            $totalAnggaranInvest = DB::select("select e.* from excel_datas e
                                join sheets s on s.id = e.sheet_id
                                join file_imports f on f.id = e.file_import_id
                                where s.name like 'I-Form 10'
                                and e.file_import_id = ".$rq->draft1."
                                and e.lokasi_id = ".$rq->lokasi."
                                and e.kolom = 'AN'
                                order by e.row, e.kolom");

            $targetBulan = DB::select("select e.value from excel_datas e
                                join sheets s on s.id = e.sheet_id
                                join file_imports f on f.id = e.file_import_id
                                where s.name like 'I-Form 10'
                                and e.file_import_id = ".$rq->draft1."
                                and e.lokasi_id = ".$rq->lokasi."
                                and e.kolom = 'AY'
                                order by e.row, e.kolom");

            $targetTahun = DB::select("select e.* from excel_datas e
                                join sheets s on s.id = e.sheet_id
                                join file_imports f on f.id = e.file_import_id
                                where s.name like 'I-Form 10'
                                and e.file_import_id = ".$rq->draft1."
                                and e.lokasi_id = ".$rq->lokasi."
                                and e.kolom = 'AZ'
                                order by e.row, e.kolom");

            $levering = DB::select("select e.* from excel_datas e
                                join sheets s on s.id = e.sheet_id
                                join file_imports f on f.id = e.file_import_id
                                where s.name like 'I-Form 10'
                                and e.file_import_id = ".$rq->draft1."
                                and e.lokasi_id = ".$rq->lokasi."
                                and e.kolom = 'BA'
                                order by e.row, e.kolom");

            $pengadaanPusat = DB::select("select e.* from excel_datas e
                                join sheets s on s.id = e.sheet_id
                                join file_imports f on f.id = e.file_import_id
                                where s.name like 'I-Form 10'
                                and e.file_import_id = ".$rq->draft1."
                                and e.lokasi_id = ".$rq->lokasi."
                                and e.kolom = 'AX'
                                order by e.row, e.kolom");

            $disburseBulan = DB::select("select e.* from excel_datas e
                                join sheets s on s.id = e.sheet_id
                                join file_imports f on f.id = e.file_import_id
                                where s.name like 'I-Form 10'
                                and e.file_import_id = ".$rq->draft1."
                                and e.lokasi_id = ".$rq->lokasi."
                                and e.kolom = 'BB'
                                order by e.row, e.kolom");

            $disburseNilai = DB::select("select e.* from excel_datas e
                                join sheets s on s.id = e.sheet_id
                                join file_imports f on f.id = e.file_import_id
                                where s.name like 'I-Form 10'
                                and e.file_import_id = ".$rq->draft1."
                                and e.lokasi_id = ".$rq->lokasi."
                                and e.kolom = 'AT'
                                order by e.row, e.kolom");

            // if($rq->download && $rq->type){
            //     if ($rq->type='pdf') {
            //         $fill=[];
            //         $fill=array($rq->get('thn-form1'), $rq->get('sb-form1'), $rq->get('d-form1'), $rq->get('lok-form1'), $rq->get('fas-form1'),$rq->get('dr-form1'));
            //         $judul='Tabel Rincian Penetapan Anggaran Pengembangan Usaha';
            //     }
            // }

            // $count = DB::table("excel_datas")->join("sheets","sheets.id","excel_datas.sheet_id")->where("sheets.name","like","I-Form 6")->where("excel_datas.file_import_id","=","4")->where("excel_datas.lokasi_id", $rq->lokasi)->where("excel_datas.kolom", "=", "AM")->count();

            $count = DB::table("excel_datas")->join("sheets", "sheets.id", "excel_datas.sheet_id")->join("file_imports", "file_imports.id", "excel_datas.file_import_id")->where("sheets.name","like","I-Form 10")->where("excel_datas.file_import_id","=", $rq->draft1)->where("excel_datas.lokasi_id", $rq->lokasi)->where("excel_datas.kolom", "=", "H")->count();

            $bulan = [
                "Januari" => 0,
                "Februari" => 1,
                "Maret" => 2,
                "April" => 3,
                "Mei" => 4,
                "Juni" => 5,
                "Juli" => 6,
                "Agustus" => 7,
                "September" => 8,
                "Oktober" => 9,
                "November" => 10,
                "Desember" => 11
            ];

            $bul = [
                0 => "Januari" ,
                1 => "Februari" ,
                2 => "Maret" ,
                3 => "April" ,
                4 => "Mei" ,
                5 => "Juni" ,
                6 => "Juli" ,
                7 => "Agustus" ,
                8 => "September" ,
                9 => "Oktober" ,
                10 => "November",
                11 => "Desember"
            ];

            //grafik 1
            $totalSumByMonth1 = array();

            for ($i = 0; $i<$count; $i++){
                $currentMonth = $targetBulan[$i]->value;

                if (!array_key_exists($currentMonth, $totalSumByMonth1)) {
                    $totalSumByMonth1[$currentMonth]["value"] = $disburseNilai[$i]->value/1000;
                    $totalSumByMonth1[$currentMonth]["bulan"] = $currentMonth;
                    $totalSumByMonth1[$currentMonth]["ordering"] = $bulan[$currentMonth];
                }
                else $totalSumByMonth1[$currentMonth]["value"] += $disburseNilai[$i]->value/1000;
            }

            usort($totalSumByMonth1, function($a, $b){
                return $a["ordering"]-$b["ordering"];
            });

            //Key By
            $keys = array_keys($bul);
            $tempTSBM1 = array();

            foreach ($totalSumByMonth1 as $data) {
                $idx = $data["ordering"];
                $tempTSBM1[$idx] = $data;
            }

            $totalSumByMonth1 = $tempTSBM1;

            for ($i = 0; $i < count($keys); $i++) {
                if (!array_key_exists($keys[$i], $totalSumByMonth1)) {
                    $totalSumByMonth1[$keys[$i]] = [ "value" => 0, "bulan" => $bul[$keys[$i]], "ordering" => $keys[$i]];
                }
            }

            for ($i = 1; $i < count($keys); $i++) {
                $totalSumByMonth1[$keys[$i]]["value"] += $totalSumByMonth1[$keys[$i]-1]["value"];
            }

            ksort($totalSumByMonth1);


            //grafik 2
            $totalSumByMonth2 = array();

            for ($i = 0; $i<$count; $i++){
                $currentMonth = $targetBulan[$i]->value;

                if (!array_key_exists($currentMonth, $totalSumByMonth2)) {
                    $totalSumByMonth2[$currentMonth]["value"] = $totalAnggaranInvest[$i]->value/1000;
                    $totalSumByMonth2[$currentMonth]["bulan"] = $currentMonth;
                    $totalSumByMonth2[$currentMonth]["ordering"] = $bulan[$currentMonth];
                }
                else $totalSumByMonth2[$currentMonth]["value"] += $totalAnggaranInvest[$i]->value/1000;
            }

            usort($totalSumByMonth2, function($a, $b){
                return $a["ordering"]-$b["ordering"];
            });

            $tempTSBM2 = array();

            foreach ($totalSumByMonth2 as $data) {
                $idx = $data["ordering"];
                $tempTSBM2[$idx] = $data;
            }

            $totalSumByMonth2 = $tempTSBM2;

            for ($i = 0; $i < count($keys); $i++) {
                if (!array_key_exists($keys[$i], $totalSumByMonth2)) {
                    $totalSumByMonth2[$keys[$i]] = [ "value" => 0, "bulan" => $bul[$keys[$i]], "ordering" => $keys[$i]];
                }
            }

            for ($i = 1; $i < count($keys); $i++) {
                $totalSumByMonth2[$keys[$i]]["value"] += $totalSumByMonth2[$keys[$i]-1]["value"];
            }

            ksort($totalSumByMonth2);

            // $keys = array_keys($totalSumByMonth2);

            // for ($i = 1; $i < count($keys); $i++) {
            //     $totalSumByMonth2[$keys[$i]]["value"] += $totalSumByMonth2[$keys[$i]-1]["value"];
            // }



            //download
            if($rq->download && $rq->type){
                $judul='';
                switch ($rq->type){
                    case 'pdf':
                        $fill = [];
                        $judul='Rincian Penetapan Anggaran Investasi PJB';
                        if($rq->download=='rincian-penetapan-ai')
                        {
                            $fill=array($input_tahun, $input_sb, $input_distrik, $input_lokasi, $input_fase, $idraft);
                            $judul='Report Rincian Penetapan Anggaran Investasi PJB';
                        }

                        // $pdf=PDF::loadView('output/rincian-penetapan-ai-pdf', compact('sb', 'fs', 'sb', 'fs', 'query', 'kodePRK', 'deskPRK', 'anggaranIL', 'anggaranIM', 'totalAnggaranInvest', 'targetBulan', 'targetTahun', 'levering', 'pengadaanPusat', 'disburseBulan', 'disburseNilai', 'count', 'judul', 'fill'));
                        //return $pdf->download();
                        return view('output/rincian-penetapan-ai-pdf2', compact('sb', 'fases', 'query', 'kodePRK', 'deskPRK', 'anggaranIL', 'anggaranIM', 'totalAnggaranInvest', 'targetBulan', 'targetTahun', 'levering', 'pengadaanPusat', 'disburseBulan', 'disburseNilai', 'count', 'judul', 'fill', 'totalSumByMonth1', 'totalSumByMonth2', 'input_distrik', 'input_tahun', 'input_lokasi', 'idraft', 'input_fase', 'input_sb'));
                    break;
                    case 'excel':
                        $array['judul']='Rincian Biaya Administrasi';
                        $array['tahun_anggaran']=[' ','Tahun', $input_tahun];
                        $array['struktur_bisnis']=[' ','Struktur Bisnis', $input_sb->name];
                        $array['distrik']=[' ','Distrik', $input_distrik->name];
                        $array['lokasi']=[' ','Lokasi', $input_lokasi->name];
                        $array['fase']=[' ','Fase', $input_fase->name];
                        $array['jenis']=[' ','Jenis Draft', $input_fase->name];
                        $array['draft1']=[' ','Draft', $draft->draft_versi];

                        //dd($array);

                        $arrayNew = [];

                        $tmp = [];
                        $arrayList = ['Kode PRK', 'Deskripsi PRK Kegiatan', 'Anggaran Investasi Luncuran', 'Anggaran Investasi Murni', 'Total Anggaran Investasi', 'Target Terkontrak', ' ', 'Levering', 'Pengadaan Pusat/Unit', 'Disburse Tahun Ke-n', ' '];
                        $array1 = [' ', ' ', ' ', ' ', ' ', 'Bulan', 'Tahun', ' ', ' ', 'Bulan', 'Nilai'];
                        // $arrayList = ['Kode PRK', 'Deskripsi PRK Kegiatan', 'Anggaran Investasi Luncuran', 'Anggaran Investasi Murni', 'Total Anggaran Investasi', 'Bulan', 'Tahun', 'Levering', 'Pengadaan Pusat/Unit', 'Bulan', 'Nilai'];
                        // dd($number);
                        $tmp[0] = $arrayList;
                        $tmp[1] = $array1;

                        //dd($disburseBulan);

                        for ($i = 0 ; $i < $count ; $i++) {
                            $tmp[] = array(
                                'Kode PRK' => $kodePRK[$i]->value,
                                'Deskripsi PRK Kegiatan' => $deskPRK[$i]->value,
                                'Anggaran Investasi Luncuran' => $anggaranIL[$i]->value,
                                'Anggaran Investasi Murni' => $anggaranIM[$i]->value,
                                'Total Anggaran Investasi' => $totalAnggaranInvest[$i]->value,
                                'Target Terkontrak' => $targetBulan[$i]->value,
                                'Tahun' => $targetTahun[$i]->value,
                                'Levering' => $levering[$i]->value,
                                'Pengadaan Pusat/Unit' => $pengadaanPusat[$i]->value,
                                'Disburse Tahun Ke-n' => $disburseBulan[$i] ->value,
                                'Nilai' => $disburseNilai[$i] ->value
                            );
                        }
                        //dd($tmp);
                        $array['tmp'] = $tmp;

                        Excel::create('Rincian Penetapan Anggaran Investasi PJB', function($excel) use($array) {
                        $excel->setTitle('Table Rincian Penetapan Anggaran Pengembangan Usaha');
                        $excel->setCreator('Laravel')->setCompany('Profio', 'PJB');
                        $excel->setDescription('Table Rincian Penetapan Anggaran Investasi PJB');

                        $excel->sheet('Sheet1', function($sheet) use($array) {
                            $sheet->row(1, $array['tahun_anggaran']);
                            $sheet->row(2, $array['struktur_bisnis']);
                            $sheet->row(3, $array['distrik']);
                            $sheet->row(4, $array['lokasi']);
                            $sheet->row(5, $array['fase']);
                            $sheet->row(6, $array['jenis']);
                            $sheet->row(7, $array['draft1']);

                            $sheet->row(8, array('', '',''));
                            $sheet->row(9, array('', '',''));

                            foreach ($array['tmp'] as $key => $value) {
                                $sheet->row($key+10, $value);
                            }

                            // $sheet->fromArray($tmp, null, 'A1', false, false);
                        });
                        })->download('xlsx');
                    break;
                    default:
                        return redirect($rq->url());
                        break;

                }

            }
            else{
                return view('output/rincian-penetapan-ai', compact('sb', 'fases', 'draft', 'query', 'kodePRK', 'deskPRK', 'anggaranIL', 'anggaranIM', 'totalAnggaranInvest', 'targetBulan', 'targetTahun', 'levering', 'pengadaanPusat', 'disburseBulan', 'disburseNilai', 'count', 'totalSumByMonth1', 'totalSumByMonth2', 'input_distrik', 'input_tahun', 'input_lokasi', 'idraft', 'input_fase', 'input_sb', 'idistrik', 'ilokasi'));
            }
        }
        else {
    	   return view('output/rincian-penetapan-ai', compact('sb', 'input_tahun', 'input_sb', 'input_distrik', 'input_lokasi', 'fases', 'count', 'input_fase', 'idraft', 'idistrik', 'ilokasi', 'draft'));
        }

        //return view('output/rincian-penetapan-ai', compact('sb', 'input_tahun', 'input_sb', 'input_distrik', 'input_lokasi', 'fs'));
    }

    public function OrderBulan($a, $b)
    {
        return $a["ordering"]-$b["ordering"];

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

    public function myformAjax3($lokasi_id, $tahun)
    {
        $draft=DB::select("SELECT DISTINCT f.id, f.draft_versi
                          from file_imports f
                          join templates t on f.template_id = t.id
                          join excel_datas e on e.file_import_id = f.id
                          where t.jenis_id = 5 and e.lokasi_id = ".$lokasi_id." and t.tahun=".$tahun."
                          group by f.id, f.draft_versi");

        // $draft = DB::select("select distinct f.id, f.draft_versi
        //                     from file_imports f
        //                     join templates t on f.template_id = t.id
        //                     join excel_datas e on e.file_import_id = f.id
        //                     where t.jenis_id = 5 and e.lokasi_id = ".$lokasi_id." and t.tahun= ".$tahun."
        //                     group by f.id, f.draft_versi");
        /*$array=array(
                array('id'=>1,'draft_versi'=>'2017-10-29'),
                array('id'=>2,'draft_versi'=>'2017-10-30'),
                array('id'=>3,'draft_versi'=>'2017-10-31'),
            );*/
        return json_encode($draft);
    }
}
