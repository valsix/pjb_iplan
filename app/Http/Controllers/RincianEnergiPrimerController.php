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
Use Excel;
use PDF;

class RincianEnergiPrimerController extends Controller
{
    public function Rincian_Energi_Primer(Request $request)
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
        $months = array('','Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
        // dd($tahun);
        $input_tahun = $request->input('tahun_anggaran');
        $input_bulan = $request->input('bulan');
        $input_sb = $request->input('strategi_bisnis');
        $input_distrik = $request->input('distrik');
        $input_lokasi = $request->input('lokasi');
        $input_fase = $request->input('fase');
        $input_draft = $request->input('draft_id');
        // $input_draft_bahan_bakar = $request->input('draft_bahan_bakar');

        if ($input_lokasi == NULL) {
            $int_input_lokasi = NULL;
        }
        else {
            $int_input_lokasi = (int)$input_lokasi;
        }

        if ($request->input('strategi_bisnis') != NULL) {
            $input_sb = DB::table('strategi_bisnis')->select('name','id')->where('id', $request->input('strategi_bisnis'))->get()[0];
            $distrik = Distrik::select('name','id')->where('strategi_bisnis_id',$input_sb->id)->get();
        }
        if ($request->input('distrik') != NULL) {
            $input_distrik = DB::table('distrik')->select('name','code1','id')->where('id', $request->distrik)->get()[0];
            $lokasi = Lokasi::select('name','id')->where('distrik_id',$input_distrik->id)->get();
        }
        if ($request->input('lokasi') != NULL) {
            $input_lokasi = DB::table('lokasi')->select('name','id')->where('id', $request->lokasi)->get()[0];
        }
        if ($request->input('fase') != NULL) {
            $input_fase = DB::table('fases')->select('name','id')->where('id', $request->fase)->get()[0];
        }
        if ($request->input('draft_id') != NULL) {
            $input_draft = DB::table('file_imports')->select('draft_versi','id', 'name')->where('id', $request->draft_id)->get()[0];
            $drafts = $this->query_draft($input_sb->name, $input_lokasi->id, $input_tahun);
        }

        $data = array();
        $subtotal_bbm = array();
        $subtotal_nonbbm = array();
        $total_up = array();

        if($request->input('draft_id') != NULL){
            if ($input_sb->name == 'OM') {

                $rowtotal = DB::select("select e.row from excel_datas e join sheets s on e.sheet_id = s.id
                    where e.file_import_id = ".$request->input('draft_id')." and s.name like 'I-PENDUKUNG EP' and kolom like 'D' and value like 'TOTAL'");
                $row = $rowtotal[0]->row;

                $columnC = $this->query_om_per_column($request->input('draft_id'), $row, 'C');
                $columnD = $this->query_om_per_column($request->input('draft_id'), $row, 'D');
                $columnE = $this->query_om_per_column($request->input('draft_id'), $row, 'E');
                $columnF = $this->query_om_per_column($request->input('draft_id'), $row, 'F');
                $columnG = $this->query_om_per_column($request->input('draft_id'), $row, 'G');
                $columnH = $this->query_om_per_column($request->input('draft_id'), $row, 'H');
                $columnI = $this->query_om_per_column($request->input('draft_id'), $row, 'I');
                $columnJ = $this->query_om_per_column($request->input('draft_id'), $row, 'J');

                $totalE = DB::select("select e.value from excel_datas e join sheets s on e.sheet_id = s.id
                    where e.file_import_id = ".$request->input('draft_id')." and s.name like 'I-PENDUKUNG EP' and row = ".$row." and kolom like 'E'");
                $totalF = DB::select("select e.value from excel_datas e join sheets s on e.sheet_id = s.id
                    where e.file_import_id = ".$request->input('draft_id')." and s.name like 'I-PENDUKUNG EP' and row = ".$row." and kolom like 'F'");
                $totalH = DB::select("select e.value from excel_datas e join sheets s on e.sheet_id = s.id
                    where e.file_import_id = ".$request->input('draft_id')." and s.name like 'I-PENDUKUNG EP' and row = ".$row." and kolom like 'H'");

                $data = array('C'=> $columnC,
                                'D'=> $columnD,
                                'E'=> $columnE,
                                'F'=> $columnF,
                                'G'=> $columnG,
                                'H'=> $columnH,
                                'I'=> $columnI,
                                'J'=> $columnJ,
                                'totalE'=> $totalE,
                                'totalF'=> $totalF,
                                'totalH'=> $totalH,
                );
            }
            else if ($input_sb->name == 'UP' && $request->input('bulan')!= null) {
                $jenis_bahan_bakar = array ("HSD", "MFO", "IDO", "GAS ALAM", "BATUBARA", "EP", "RETRIBUSI", "SURYA", "LAIN-LAIN","Pelumas", "Bahan Kimia & Campuran");
                $satuan = array("Liter","Liter","Liter","Mmbtu","Kg","Rp/kWh","Rp/kWh","","","Rp","Rp");
                $subtotal_bbm = array( 'title'=> 'Total BBM',
                        'produksi' => array(0,0,0),
                        'total_produksi' => 0,
                        'kebutuhan_ep' => array(0,0,0),
                        'total_kebutuhan_ep' => 0,
                        'satuan' => '',
                        'jumlah_biaya_bahan_bakar' => 0,
                        'satuan_biaya_bahan_bakar' => '',
                        'jumlah_ongkos_angkut' => 0,
                        'ratarata_ongkos_angkut' => '',
                        'jumlah_biaya_pendukung' => 0,
                        'total_biaya' => 0,
                );
                $subtotal_nonbbm = array( 'title'=> 'Total Non BBM',
                        'produksi' => array(0,0,0),
                        'total_produksi' => 0,
                        'kebutuhan_ep' => array(0,0,0),
                        'total_kebutuhan_ep' => 0,
                        'satuan' => '',
                        'jumlah_biaya_bahan_bakar' => 0,
                        'satuan_biaya_bahan_bakar' => '',
                        'jumlah_ongkos_angkut' => 0,
                        'ratarata_ongkos_angkut' => '',
                        'jumlah_biaya_pendukung' => 0,
                        'total_biaya' => 0,
                );
                $total_up = array( 'title'=> 'Total',
                        'produksi' => array(0,0,0),
                        'total_produksi' => 0,
                        'kebutuhan_ep' => array(0,0,0),
                        'total_kebutuhan_ep' => 0,
                        'satuan' => '',
                        'jumlah_biaya_bahan_bakar' => 0,
                        'satuan_biaya_bahan_bakar' => '',
                        'jumlah_ongkos_angkut' => 0,
                        'ratarata_ongkos_angkut' => '',
                        'jumlah_biaya_pendukung' => 0,
                        'total_biaya' => 0,
                );

                foreach ($jenis_bahan_bakar as $key => $jenis) {
                    if($key<9){
                        $prod = $this->query_produksi($input_tahun, $request->input('draft_id'), $jenis, $input_distrik->code1, $input_bulan);
                        $kebutuhan_ep = $this->query_kebutuhan_ep($input_tahun, $request->input('draft_id'), $jenis, $input_distrik->code1, $input_bulan);

                        if($jenis == "RETRIBUSI")
                            $jumlah_biaya_bahan_bakar = $this->query_biaya_bahan_bakar_retribusi($input_tahun, $request->input('draft_id'), $jenis, $input_distrik->code1, $input_bulan);

                        else if($jenis == "EP"){
                            $jumlah_biaya_bb = $this->query_biaya_bahan_bakar($input_tahun, $request->input('draft_id'), $jenis, $input_distrik->code1, $input_bulan);
                            $jumlah_biaya_retribusi = $this->query_biaya_bahan_bakar_retribusi($input_tahun, $request->input('draft_id'), $jenis, $input_distrik->code1, $input_bulan);
                            $jumlah_biaya_bahan_bakar = $jumlah_biaya_bb - $jumlah_biaya_retribusi;
                        }

                        else
                            $jumlah_biaya_bahan_bakar = $this->query_biaya_bahan_bakar($input_tahun, $request->input('draft_id'), $jenis, $input_distrik->code1, $input_bulan);

                        $jumlah_ongkos_angkut = $this->query_ongkos_angkut($input_tahun, $request->input('draft_id'), $jenis, $input_distrik->code1, $input_bulan);
                        $jumlah_biaya_pendukung = $this->query_biaya_pendukung($input_tahun, $request->input('draft_id'), $jenis, $input_distrik->code1, $input_bulan);
                    }
                    else{
                        $prod['result'][0]->value = 0;
                        $prod['result'][1]->value = 0;
                        $prod['result'][2]->value = 0;
                        $prod['total'] = 0;
                        $kebutuhan_ep['result'][0]->value = 0;
                        $kebutuhan_ep['result'][1]->value = 0;
                        $kebutuhan_ep['result'][2]->value = 0;
                        $kebutuhan_ep['total'] = 0;
                        $jumlah_biaya_bahan_bakar = 0;
                        $jumlah_ongkos_angkut = 0;
                        if($key == 9)
                            $jumlah_biaya_pendukung = $this->query_biaya_pendukung_($input_tahun, $request->input('draft_id'), $jenis, $input_distrik->code1, $input_bulan,'AS');
                        else
                            $jumlah_biaya_pendukung = $this->query_biaya_pendukung_($input_tahun, $request->input('draft_id'), $jenis, $input_distrik->code1, $input_bulan,'AT');
                    }
                    $d = array('jenis' => $jenis,
                        'produksi' => $prod['result'],
                        'total_produksi' => $prod['total'],
                        'kebutuhan_ep' => $kebutuhan_ep['result'],
                        'total_kebutuhan_ep' => $kebutuhan_ep['total'],
                        'satuan' => $satuan[$key],
                        'jumlah_biaya_bahan_bakar' => $jumlah_biaya_bahan_bakar,
                        'satuan_biaya_bahan_bakar' => ($kebutuhan_ep['total'] == 0 ? 0 : ($jumlah_biaya_bahan_bakar/$kebutuhan_ep['total'] * 1000)),
                        'jumlah_ongkos_angkut' => $jumlah_ongkos_angkut,
                        'ratarata_ongkos_angkut' => ($kebutuhan_ep['total'] == 0 ? 0 : ($jumlah_ongkos_angkut/$kebutuhan_ep['total'] * 1000)),
                        'jumlah_biaya_pendukung' => $jumlah_biaya_pendukung,
                        'total_biaya' => $jumlah_biaya_bahan_bakar + $jumlah_ongkos_angkut + $jumlah_biaya_pendukung,
                        );
                    array_push($data, $d);

                    if($key<3){
                        $subtotal_bbm['produksi'][0] += $d['produksi'][0]->value;
                        $subtotal_bbm['produksi'][1] += $d['produksi'][1]->value;
                        $subtotal_bbm['produksi'][2] += $d['produksi'][2]->value;
                        $subtotal_bbm['total_produksi'] += $d['total_produksi'];
                        $subtotal_bbm['kebutuhan_ep'][0] += $d['kebutuhan_ep'][0]->value;
                        $subtotal_bbm['kebutuhan_ep'][1] += $d['kebutuhan_ep'][1]->value;
                        $subtotal_bbm['kebutuhan_ep'][2] += $d['kebutuhan_ep'][2]->value;
                        $subtotal_bbm['total_kebutuhan_ep'] += $d['total_kebutuhan_ep'];
                        $subtotal_bbm['jumlah_biaya_bahan_bakar'] += $d['jumlah_biaya_bahan_bakar'];
                        $subtotal_bbm['jumlah_ongkos_angkut'] += $d['jumlah_ongkos_angkut'];
                        $subtotal_bbm['jumlah_biaya_pendukung'] += $d['jumlah_biaya_pendukung'];
                        $subtotal_bbm['total_biaya'] += $d['total_biaya'];
                    }
                    else if($key>=3 && $key <9){
                        $subtotal_nonbbm['produksi'][0] += $d['produksi'][0]->value;
                        $subtotal_nonbbm['produksi'][1] += $d['produksi'][1]->value;
                        $subtotal_nonbbm['produksi'][2] += $d['produksi'][2]->value;
                        $subtotal_nonbbm['total_produksi'] += $d['total_produksi'];
                        $subtotal_nonbbm['kebutuhan_ep'][0] += $d['kebutuhan_ep'][0]->value;
                        $subtotal_nonbbm['kebutuhan_ep'][1] += $d['kebutuhan_ep'][1]->value;
                        $subtotal_nonbbm['kebutuhan_ep'][2] += $d['kebutuhan_ep'][2]->value;
                        $subtotal_nonbbm['total_kebutuhan_ep'] += $d['total_kebutuhan_ep'];
                        $subtotal_nonbbm['jumlah_biaya_bahan_bakar'] += $d['jumlah_biaya_bahan_bakar'];
                        $subtotal_nonbbm['jumlah_ongkos_angkut'] += $d['jumlah_ongkos_angkut'];
                        $subtotal_nonbbm['jumlah_biaya_pendukung'] += $d['jumlah_biaya_pendukung'];
                        $subtotal_nonbbm['total_biaya'] += $d['total_biaya'];
                    }

                    $total_up['produksi'][0] += $d['produksi'][0]->value;
                    $total_up['produksi'][1] += $d['produksi'][1]->value;
                    $total_up['produksi'][2] += $d['produksi'][2]->value;
                    $total_up['total_produksi'] += $d['total_produksi'];
                    $total_up['kebutuhan_ep'][0] += $d['kebutuhan_ep'][0]->value;
                    $total_up['kebutuhan_ep'][1] += $d['kebutuhan_ep'][1]->value;
                    $total_up['kebutuhan_ep'][2] += $d['kebutuhan_ep'][2]->value;
                    $total_up['total_kebutuhan_ep'] += $d['total_kebutuhan_ep'];
                    $total_up['jumlah_biaya_bahan_bakar'] += $d['jumlah_biaya_bahan_bakar'];
                    $total_up['jumlah_ongkos_angkut'] += $d['jumlah_ongkos_angkut'];
                    $total_up['jumlah_biaya_pendukung'] += $d['jumlah_biaya_pendukung'];
                    $total_up['total_biaya'] += $d['total_biaya'];
                }
            }
        }
            if($request->download && $request->type){
                $judul='';
                if($request->type=='pdf'){
                    // $fill = [];
                    // $fill=array($request->get('tahun_anggaran'), $request->get('strategi_bisnis'), $request->get('distrik'), $request->get('lokasi'), $request->get('fase'),$request->get('draft_rkau'));
                    $judul='Rincian Energi Primer';
                    if($input_sb->name == 'OM'){
                        $pdf=PDF::loadView('output/rincian-energi-primer-pdf', compact('input_sb', 'judul','input_lokasi','input_distrik','input_tahun', 'data', 'input_fase','input_draft'))->setPaper('a4','landscape');
                        return $pdf->download();
                    }
                    else if($input_sb->name == 'UP'){
                        return view('output/rincian-energi-primer-up-pdf', compact('input_sb', 'judul','input_lokasi','input_distrik','input_tahun', 'data', 'subtotal_bbm', 'subtotal_nonbbm', 'total_up', 'input_bulan', 'months','drafts','input_bulan', 'input_fase', 'input_draft'));
                    }

                }
                else if($request->type=='excel'){
                    $tmp = [];
                    $arrayList = [];

                    if($input_sb->name == 'OM'){
                        Excel::create('Rincian Energi Primer', function ($excel) use($data, $input_sb, $input_tahun, $input_distrik, $input_lokasi) {
                            $excel->setTitle('Rincian Energi Primer');
                            $excel->setCreator('Laravel')->setCompany('Profio', 'PJB');
                            $excel->setDescription('Rincian EP');
                            $excel->sheet('Rincian EP', function ($sheet) use($data, $input_sb, $input_tahun, $input_distrik, $input_lokasi){
                                $sheet->loadView('output/rincian-energi-primer-om-excel')->with('data', $data)->with('input_sb', $input_sb)->with('input_tahun', $input_tahun)->with('input_distrik', $input_distrik)->with('input_lokasi', $input_lokasi);
                            });
                        })->download('xlsx');
                    }
                    else if($input_sb->name == 'UP'){
                        // dd($data);
                        Excel::create('Rincian Energi Primer', function ($excel) use($subtotal_bbm, $data, $subtotal_nonbbm, $total_up, $input_sb, $input_tahun, $input_distrik, $input_lokasi, $input_bulan, $months) {
                            $excel->setTitle('Rincian Energi Primer');
                            $excel->setCreator('Laravel')->setCompany('Profio', 'PJB');
                            $excel->setDescription('Rincian EP');
                            $excel->sheet('Rincian EP', function ($sheet) use($subtotal_bbm, $data, $subtotal_nonbbm, $total_up, $input_sb, $input_tahun, $input_distrik, $input_lokasi, $input_bulan, $months){
                                $sheet->loadView('output/rincian-energi-primer-up-excel')->with('subtotal_bbm', $subtotal_bbm)->with('data', $data)->with('subtotal_nonbbm',$subtotal_nonbbm)->with('total_up',$total_up)->with('input_sb', $input_sb)->with('input_tahun', $input_tahun)->with('input_distrik', $input_distrik)->with('input_lokasi', $input_lokasi)->with('input_bulan', $input_bulan)->with('months', $months);
                            });
                        })->download('xlsx');
                    }
                }
            }
        else
           return view('output/rincian-energi-primer', compact('sb', 'fase', 'tahun', 'input_tahun', 'input_sb', 'input_distrik', 'input_lokasi', 'input_fase', 'input_draft', 'data', 'subtotal_nonbbm', 'subtotal_bbm', 'total_up', 'distrik', 'lokasi', 'drafts', 'months', 'input_bulan'));
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

    public function ajax_draft($id_strategi_bisnis, $id_lokasi, $id_tahun){
        $strategi_bisnis = StrategiBisnis::where('id',$id_strategi_bisnis)->first();
        if($strategi_bisnis->name == "OM")
            $jenis_id = 1; //jika OM, ambil dari RKAU
        else
            $jenis_id = 7; //jika UP, ambil dari Bahan Bakar
        $draft= DB::select("select distinct f.id, f.draft_versi
                            from file_imports f
                            join templates t on f.template_id = t.id
                            join excel_datas e on e.file_import_id = f.id
                            where t.jenis_id=".$jenis_id." and e.lokasi_id = ".$id_lokasi." and t.tahun= ".$id_tahun."
                            group by f.id, f.draft_versi;");
        //dapet nlai dari tabel file import kolom id

        return json_encode($draft);
    }

    public function query_draft($strategi_bisnis, $id_lokasi, $id_tahun){
        if($strategi_bisnis == "OM")
            $jenis_id = 1; //jika OM, ambil dari RKAU
        else
            $jenis_id = 7; //jika UP, ambil dari Bahan Bakar
        $draft= DB::select("select distinct f.id, f.draft_versi
                            from file_imports f
                            join templates t on f.template_id = t.id
                            join excel_datas e on e.file_import_id = f.id
                            where t.jenis_id=".$jenis_id." and e.lokasi_id = ".$id_lokasi." and t.tahun= ".$id_tahun."
                            group by f.id, f.draft_versi;");
        return $draft;
    }

    public function query_om_per_column($draft_rkau, $rowtotal,$column){
        $result = DB::select("select e.row, e.value from excel_datas e join sheets s on e.sheet_id = s.id
                    where e.file_import_id = ".$draft_rkau." and s.name like 'I-PENDUKUNG EP' and row between 13 and ".($rowtotal-1)." and kolom like '".$column."' order by e.row");
        return $result;
    }

    public function query_produksi($tahun, $draft_bahan_bakar, $jenis, $distrik, $bulan){
        $status_milik = array("PRODUKSI SENDIRI", "SEWA","BELI IPP' OR value like 'BELI EXCESS POWER");
        $query_result = array();
        $total = 0;

        // // filter bulan jika desember, ambil yg bulan $tahun aja
        // if($bulan == 12){
        // filter bulan jika kumulatif, ambil yg bulan $tahun aja
        if($bulan == 0){
            $operator = "=";
            $bulan = $tahun;
        }
        else
            $operator = "<=";

        foreach ($status_milik as $key => $status) {
            $query_result[$key] = collect(\DB::select("SELECT sum(e.value::float) as value FROM excel_datas e
                    join sheets s on s.id = e.sheet_id
                    where file_import_id = ".$draft_bahan_bakar."
                    and s.name like 'Database KIT (P+S+I)'
                    and kolom like 'AB' and row in (
                    SELECT e.row FROM excel_datas e
                    join sheets s on s.id = e.sheet_id
                    where file_import_id = ".$draft_bahan_bakar."
                    and s.name like 'Database KIT (P+S+I)'
                    and kolom like 'AF' and value like '".$jenis."' and row in (
                        SELECT e.row FROM excel_datas e
                        join sheets s on s.id = e.sheet_id
                        where file_import_id = ".$draft_bahan_bakar."
                        and s.name like 'Database KIT (P+S+I)'
                        and kolom like 'T' and (value like '".$status."') AND row in(
                            SELECT e.row FROM excel_datas e
                            join sheets s on s.id = e.sheet_id
                            where file_import_id = ".$draft_bahan_bakar."
                            and s.name like 'Database KIT (P+S+I)'
                            and kolom like 'G' and value like '".$tahun."' and row in(
                                SELECT e.row FROM excel_datas e
                                join sheets s on s.id = e.sheet_id
                                where file_import_id = ".$draft_bahan_bakar."
                                and s.name like 'Database KIT (P+S+I)'
                                and kolom like 'H' and value::float ".$operator." ".$bulan." and row in(
                                    SELECT e.row FROM excel_datas e
                                    join sheets s on s.id = e.sheet_id
                                    where file_import_id = ".$draft_bahan_bakar."
                                    and s.name like 'Database KIT (P+S+I)'
                                    and kolom like 'C' and value like '".$distrik."'
                                )
                            )
                        )
                    )
                )"))->first();
                if($query_result[$key]== null)
                    $query_result[$key]->value = 0;
                $total += (float)$query_result[$key]->value;
            }
        $result = array('total' => $total, 'result' => $query_result);

        return $result;
    }

    public function query_kebutuhan_ep($tahun, $draft_bahan_bakar, $jenis, $distrik, $bulan){
        $status_milik = array("PRODUKSI SENDIRI", "SEWA","BELI IPP' OR value like 'BELI EXCESS POWER");
        $query_result = array();
        $total = 0;

        // // filter bulan jika desember, ambil yg bulan $tahun aja
        // if($bulan == 12){
        // filter bulan jika kumulatif, ambil yg bulan $tahun aja
        if($bulan == 0){
            $operator = "=";
            $bulan = $tahun;
        }
        else
            $operator = "<=";

        foreach ($status_milik as $key => $status) {
            $query_result[$key] = collect(\DB::select("SELECT sum(e.value::float) as value FROM excel_datas e
                    join sheets s on s.id = e.sheet_id
                    where file_import_id = ".$draft_bahan_bakar."
                    and s.name like 'Database KIT (P+S+I)'
                    and kolom like 'AH' and row in (
                    SELECT e.row FROM excel_datas e
                    join sheets s on s.id = e.sheet_id
                    where file_import_id = ".$draft_bahan_bakar."
                    and s.name like 'Database KIT (P+S+I)'
                    and kolom like 'AF' and value like '".$jenis."' and row in (
                        SELECT e.row FROM excel_datas e
                        join sheets s on s.id = e.sheet_id
                        where file_import_id = ".$draft_bahan_bakar."
                        and s.name like 'Database KIT (P+S+I)'
                        and kolom like 'T' and (value like '".$status."') AND row in(
                            SELECT e.row FROM excel_datas e
                            join sheets s on s.id = e.sheet_id
                            where file_import_id = ".$draft_bahan_bakar."
                            and s.name like 'Database KIT (P+S+I)'
                            and kolom like 'G' and value like '".$tahun."' and row in(
                                SELECT e.row FROM excel_datas e
                                join sheets s on s.id = e.sheet_id
                                where file_import_id = ".$draft_bahan_bakar."
                                and s.name like 'Database KIT (P+S+I)'
                                and kolom like 'H' and value::float ".$operator." ".$bulan." and row in(
                                    SELECT e.row FROM excel_datas e
                                    join sheets s on s.id = e.sheet_id
                                    where file_import_id = ".$draft_bahan_bakar."
                                    and s.name like 'Database KIT (P+S+I)'
                                    and kolom like 'C' and value like '".$distrik."'
                                )
                            )
                        )
                    )
                )"))->first();
                if($query_result[$key]== null)
                    $query_result[$key]->value = 0;
                $total += (float)$query_result[$key]->value;
            }
        $result = array('total' => $total, 'result' => $query_result);

        return $result;
    }

    public function query_biaya_bahan_bakar($tahun, $draft_bahan_bakar, $jenis, $distrik, $bulan){
        // // filter bulan jika desember, ambil yg bulan $tahun aja
        // if($bulan == 12){
        // filter bulan jika kumulatif, ambil yg bulan $tahun aja
        if($bulan == 0){
            $operator = "=";
            $bulan = $tahun;
        }
        else
            $operator = "<=";

        $query_result = collect(\DB::select("SELECT sum(e.value::float) as value FROM excel_datas e
                join sheets s on s.id = e.sheet_id
                where file_import_id = ".$draft_bahan_bakar."
                and s.name like 'Database KIT (P+S+I)'
                and kolom like 'AO' and row in (
                SELECT e.row FROM excel_datas e
                join sheets s on s.id = e.sheet_id
                where file_import_id = ".$draft_bahan_bakar."
                and s.name like 'Database KIT (P+S+I)'
                and kolom like 'AF' and value like '".$jenis."' and row in (
                    SELECT e.row FROM excel_datas e
                    join sheets s on s.id = e.sheet_id
                    where file_import_id = ".$draft_bahan_bakar."
                    and s.name like 'Database KIT (P+S+I)'
                    and kolom like 'G' and value like '".$tahun."' and row in(
                        SELECT e.row FROM excel_datas e
                        join sheets s on s.id = e.sheet_id
                        where file_import_id = ".$draft_bahan_bakar."
                        and s.name like 'Database KIT (P+S+I)'
                        and kolom like 'H' and value::float ".$operator." ".$bulan." and row in(
                            SELECT e.row FROM excel_datas e
                            join sheets s on s.id = e.sheet_id
                            where file_import_id = ".$draft_bahan_bakar."
                            and s.name like 'Database KIT (P+S+I)'
                            and kolom like 'C' and value like '".$distrik."'
                        )
                    )
                )
        )"))->first();
        if($query_result== null)
            $query_result->value = 0;

        // $result = array('total' => $total, 'result' => $query_result);

        return $query_result->value;
    }

    public function query_ongkos_angkut($tahun, $draft_bahan_bakar, $jenis, $distrik, $bulan){
        // // filter bulan jika desember, ambil yg bulan $tahun aja
        // if($bulan == 12){
        // filter bulan jika kumulatif, ambil yg bulan $tahun aja
        if($bulan == 0){
            $operator = "=";
            $bulan = $tahun;
        }
        else
            $operator = "<=";

        $query_result = collect(\DB::select("SELECT sum(e.value::float) as value FROM excel_datas e
                join sheets s on s.id = e.sheet_id
                where file_import_id = ".$draft_bahan_bakar."
                and s.name like 'Database KIT (P+S+I)'
                and kolom like 'AQ' and row in (
                SELECT e.row FROM excel_datas e
                join sheets s on s.id = e.sheet_id
                where file_import_id = ".$draft_bahan_bakar."
                and s.name like 'Database KIT (P+S+I)'
                and kolom like 'AF' and value like '".$jenis."' and row in (
                    SELECT e.row FROM excel_datas e
                    join sheets s on s.id = e.sheet_id
                    where file_import_id = ".$draft_bahan_bakar."
                    and s.name like 'Database KIT (P+S+I)'
                    and kolom like 'G' and value like '".$tahun."' and row in(
                        SELECT e.row FROM excel_datas e
                        join sheets s on s.id = e.sheet_id
                        where file_import_id = ".$draft_bahan_bakar."
                        and s.name like 'Database KIT (P+S+I)'
                        and kolom like 'H' and value::float ".$operator." ".$bulan." and row in(
                            SELECT e.row FROM excel_datas e
                            join sheets s on s.id = e.sheet_id
                            where file_import_id = ".$draft_bahan_bakar."
                            and s.name like 'Database KIT (P+S+I)'
                            and kolom like 'C' and value like '".$distrik."'
                        )
                    )
                )
        )"))->first();
        if($query_result== null)
            $query_result->value = 0;

        // $result = array('total' => $total, 'result' => $query_result);

        return $query_result->value;
    }

    public function query_biaya_pendukung($tahun, $draft_bahan_bakar, $jenis, $distrik, $bulan){

        // // filter bulan jika desember, ambil yg bulan $tahun aja
        // if($bulan == 12){
        // filter bulan jika kumulatif, ambil yg bulan $tahun aja
        if($bulan == 0){
            $operator = "=";
            $bulan = $tahun;
        }
        else
            $operator = "<=";

        $query_result = collect(\DB::select("SELECT sum(e.value::float) as value FROM excel_datas e
                join sheets s on s.id = e.sheet_id
                where file_import_id = ".$draft_bahan_bakar."
                and s.name like 'Database KIT (P+S+I)'
                and kolom like 'AR' and row in (
                SELECT e.row FROM excel_datas e
                join sheets s on s.id = e.sheet_id
                where file_import_id = ".$draft_bahan_bakar."
                and s.name like 'Database KIT (P+S+I)'
                and kolom like 'AF' and value like '".$jenis."' and row in (
                    SELECT e.row FROM excel_datas e
                    join sheets s on s.id = e.sheet_id
                    where file_import_id = ".$draft_bahan_bakar."
                    and s.name like 'Database KIT (P+S+I)'
                    and kolom like 'G' and value like '".$tahun."' and row in(
                        SELECT e.row FROM excel_datas e
                        join sheets s on s.id = e.sheet_id
                        where file_import_id = ".$draft_bahan_bakar."
                        and s.name like 'Database KIT (P+S+I)'
                        and kolom like 'H' and value::float ".$operator." ".$bulan." and row in(
                            SELECT e.row FROM excel_datas e
                            join sheets s on s.id = e.sheet_id
                            where file_import_id = ".$draft_bahan_bakar."
                            and s.name like 'Database KIT (P+S+I)'
                            and kolom like 'C' and value like '".$distrik."'
                        )
                    )
                )
        )"))->first();
        if($query_result== null)
            $query_result->value = 0;

        // $result = array('total' => $total, 'result' => $query_result);

        return $query_result->value;
    }

    public function query_biaya_bahan_bakar_retribusi($tahun, $draft_bahan_bakar, $jenis, $distrik, $bulan){
        // // filter bulan jika desember, ambil yg bulan $tahun aja
        // if($bulan == 12){
        // filter bulan jika kumulatif, ambil yg bulan $tahun aja
        if($bulan == 0){
            $operator = "=";
            $bulan = $tahun;
        }
        else
            $operator = "<=";

        $query_result = collect(\DB::select("SELECT sum(e.value::float) as value FROM excel_datas e
                join sheets s on s.id = e.sheet_id
                where file_import_id = ".$draft_bahan_bakar."
                and s.name like 'Database KIT (P+S+I)'
                and kolom like 'AN' and row in (
                    SELECT e.row FROM excel_datas e
                    join sheets s on s.id = e.sheet_id
                    where file_import_id = ".$draft_bahan_bakar."
                    and s.name like 'Database KIT (P+S+I)'
                    and kolom like 'G' and value like '".$tahun."' and row in(
                        SELECT e.row FROM excel_datas e
                        join sheets s on s.id = e.sheet_id
                        where file_import_id = ".$draft_bahan_bakar."
                        and s.name like 'Database KIT (P+S+I)'
                        and kolom like 'H' and value::float ".$operator." ".$bulan." and row in(
                            SELECT e.row FROM excel_datas e
                            join sheets s on s.id = e.sheet_id
                            where file_import_id = ".$draft_bahan_bakar."
                            and s.name like 'Database KIT (P+S+I)'
                            and kolom like 'C' and value like '".$distrik."'
                        )
                    )
                )"))->first();
        if($query_result== null)
            $query_result->value = 0;

        // $result = array('total' => $total, 'result' => $query_result);

        return $query_result->value;
    }

    public function query_biaya_pendukung_($tahun, $draft_bahan_bakar, $jenis, $distrik, $bulan, $kolom){
        // // filter bulan jika desember, ambil yg bulan $tahun aja
        // if($bulan == 12){
        // filter bulan jika kumulatif, ambil yg bulan $tahun aja
        if($bulan == 0){
            $operator = "=";
            $bulan = $tahun;
        }
        else
            $operator = "<=";

        $query_result = collect(\DB::select("SELECT sum(e.value::float) as value FROM excel_datas e
                join sheets s on s.id = e.sheet_id
                where file_import_id = ".$draft_bahan_bakar."
                and s.name like 'Database KIT (P+S+I)'
                and kolom like '".$kolom."' and row in (
                    SELECT e.row FROM excel_datas e
                    join sheets s on s.id = e.sheet_id
                    where file_import_id = ".$draft_bahan_bakar."
                    and s.name like 'Database KIT (P+S+I)'
                    and kolom like 'G' and value like '".$tahun."' and row in(
                        SELECT e.row FROM excel_datas e
                        join sheets s on s.id = e.sheet_id
                        where file_import_id = ".$draft_bahan_bakar."
                        and s.name like 'Database KIT (P+S+I)'
                        and kolom like 'H' and value::float ".$operator." ".$bulan." and row in(
                            SELECT e.row FROM excel_datas e
                            join sheets s on s.id = e.sheet_id
                            where file_import_id = ".$draft_bahan_bakar."
                            and s.name like 'Database KIT (P+S+I)'
                            and kolom like 'C' and value like '".$distrik."'
                        )
                    )
                )"))->first();
        if($query_result== null)
            $query_result->value = 0;

        // $result = array('total' => $total, 'result' => $query_result);

        return $query_result->value;
    }
}
