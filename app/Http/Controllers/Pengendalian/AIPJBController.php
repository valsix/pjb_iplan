<?php

namespace App\Http\Controllers\Pengendalian;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Entities\StrategiBisnis;
use App\Entities\Distrik;
use App\Entities\Lokasi;
use App\Entities\Fase;
use App\Entities\Template;
use App\Entities\User;
use App\Entities\Role;
use App\Entities\FileImportKetetapan;
use App\Entities\PgdlReportDashboardSetting;
use App\Entities\PGDLExcelDataRevisi;
use App\Entities\ExcelDataKetetapan;
use App\Entities\PgdlStatusAiPjb;
use App\Entities\PgdlMasterStatusAiPjb;
Use DB;
use Illuminate\Support\Facades\Input;
use Excel;

class AIPJBController extends Controller
{
    public function index(Request $request)
    {
        $data = Input::all();
        // dd($data);

        $user_id = session('user_id');
        $user = User::find($user_id);
        $role_id = session('role_id');
        $role = Role::find($role_id);


        $input_tahun = $request->input('tahun_anggaran');
        $input_sb = $request->input('strategi_bisnis');
        $input_distrik = $request->input('distrik');
        $input_fase = $request->input('fase');
        $input_bulan = $request->input('bulan');
        $int_input_distrik = (int)$input_distrik;
        $distrik = Distrik::where('id', $int_input_distrik)->first();
        $input_lokasi = $request->input('lokasi');
        $int_input_lokasi = (int)$input_lokasi;
        $lokasi = Lokasi::where('id', $int_input_lokasi)->first();


        if(!$input_tahun)
        {
            return redirect()->action('PencarianReportDashboardPengendalianController@pencarian');
        }
        if($input_sb<1 || $input_sb>2)
        {
            return redirect()->action('PencarianReportDashboardPengendalianController@pencarian');
        }
        $distrik_id = Distrik::pluck('id')->toArray();
        if(!in_array($int_input_distrik,$distrik_id)){
            return redirect()->action('PencarianReportDashboardPengendalianController@pencarian');
        }
        $fase_id = Fase::pluck('id')->toArray();
        if(!in_array($input_fase,$fase_id)){
            return redirect()->action('PencarianReportDashboardPengendalianController@pencarian');
        }

        $int_input_bulan = (int)$input_bulan;
        $nama_bln[0] = '';
        $nama_bln[1] = 'Januari';
        $nama_bln[2] = 'Februari';
        $nama_bln[3] = 'Maret';
        $nama_bln[4] = 'April';
        $nama_bln[5] = 'Mei';
        $nama_bln[6] = 'Juni';
        $nama_bln[7] = 'Juli';
        $nama_bln[8] = 'Agustus';
        $nama_bln[9] = 'September';
        $nama_bln[10] = 'Oktober';
        $nama_bln[11] = 'November';
        $nama_bln[12] = 'Desember';

        // $nama_bln_dipilih = ($int_input_bulan < 1 || $int_input_bulan > 12 ? '' : $nama_bln[$int_input_bulan]);
        if( $int_input_bulan >=1 && $int_input_bulan<=12){
            $nama_bln_dipilih = $nama_bln[$int_input_bulan];
        }else{
            return redirect()->action('PencarianReportDashboardPengendalianController@pencarian');
        }

        $source = array();
        $ai_pjb_result = array();

        $jenis_form_yg_digunakan = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', 7)
                                ->whereNotNull('jenis_id')
                                ->where('tahun', $input_tahun)
                                ->select('jenis_id')
                                ->distinct()
                                ->get();
        $notification_failed = '';
        if(count($jenis_form_yg_digunakan) == 0){
            $notification_failed = 'Setting Report Dashboard Status AI PJB untuk tahun '.$input_tahun.' belum dibuat!';
            return view('pengendalian_output.ai_pjb.index', compact('ai_pjb_result', 'input_tahun', 'distrik', 'lokasi', 'nama_bln_dipilih','notification_failed'));
        }
        else{

            foreach ($jenis_form_yg_digunakan as $key => $jenis_form) {
                $file_imports_ketetapan = $this->get_file_id_ketetapan($jenis_form->jenis_id,$input_tahun,$int_input_distrik);
                $file_imports_pgdl = $this->get_file_id_pengendalian($jenis_form->jenis_id,$input_tahun,$int_input_distrik);

                $settings = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id',7)
                            ->where('jenis_id',$jenis_form->jenis_id)
                            ->where('tahun', $input_tahun)
                            ->orderBy('sequence')
                            ->get();
                            // dd($settings);
                if($file_imports_ketetapan != null && $file_imports_pgdl != null){
                    $prk_data = $this->get_data_pengendalian($file_imports_pgdl, $settings[0]->pgdl_sheet_name, $settings[0]->kolom);
                    $total_data = count($prk_data);

                    $result_data = array();
                    foreach($settings as $column_setting){

                        $jenis_id = $column_setting->jenis_id;
                        $column_result = array();
                        // jika dari pengendalian, cari berdasarkan kolom
                        if($column_setting->pgdl_report_dashboard_source_id == 2){
                            $column_result = $this->get_data_pengendalian($file_imports_pgdl, $column_setting->pgdl_sheet_name, $column_setting->kolom);
                        }

                        $result_data[$column_setting->judul_kolom]= $column_result;
                        // jika dari ketetapan, pjprk, akan dicari berdasarkan nomor PRK

                    }

                    foreach ($result_data['Target Terkontrak'] as $value) {
                      // code...
                      if ($value->value == 'Januari') {
                        // code...
                        $value->value  = 1;
                      } elseif ($value->value == 'Februari') {
                        // code...
                        $value->value = 2;
                      } elseif ($value->value == 'Maret') {
                        // code...
                        $value->value = 3;
                      } elseif ($value->value == 'April') {
                        // code...
                        $value->value = 4;
                      } elseif ($value->value == 'Mei') {
                        // code...
                        $value->value = 5;
                      } elseif ($value->value == 'Juni') {
                        // code...
                        $value->value = 6;
                      } elseif ($value->value == 'Juli') {
                        // code...
                        $value->value = 7;
                      } elseif ($value->value == 'Agustus') {
                        // code...
                        $value->value = 8;
                      } elseif ($value->value == 'September') {
                        // code...
                        $value->value = 9;
                      } elseif ($value->value == 'Oktober') {
                        // code...
                        $value->value = 10;
                      } elseif ($value->value == 'November') {
                        // code...
                        $value->value = 11;
                      } elseif ($value->value == 'Desember') {
                        // code...
                        $value->value = 12;
                      }
                    }

                    // dd($result_data);

                    for($i=0; $i<$total_data; $i++){
                        $temp = array();
                        $prk = $result_data['PRK'][$i]->value;
                        $jumlah_item_po = 0;
                        $item_po_data = array();
                        $pjprk_ai_data = $this->get_pjprk_ai_data('po_no', $prk, $input_bulan, $input_tahun, $int_input_distrik);
                        $jumlah_item_po = count($pjprk_ai_data);
                        // jika ada lebih dari 1 nomer PO
                        if($jumlah_item_po > 0){
                            foreach ($pjprk_ai_data as $key => $pjprk_data) {
                                $item_po = $this->get_item_po($prk, $input_bulan, $input_tahun, $pjprk_data->po_no, $pjprk_data->po_item, $pjprk_data->account_code, $int_input_distrik);
                                // dd($item_po);
                                $item_po_data[$key] = array(
                                    'po_no' => !empty($pjprk_data->po_no) ? $pjprk_data->po_no : '-',
                                    'val_required' => !empty($item_po->val_required) ? $item_po->val_required : 0,
                                    'tran_amount' => !empty($item_po->tran_amount) ? $item_po->tran_amount : 0,
                                );
                            }
                        }
                        else{
                            $item_po_data[0] = array(
                                    'po_no' => '-',
                                    'val_required' => 0,
                                    'tran_amount' => 0,
                                );
                        }
                        // dd($result_data);
                        foreach($item_po_data as $key => $item_po) {
                            if($item_po['po_no']==null) {
                                // dd('1');
                                $status_ai_pjb = PgdlStatusAiPjb::where('distrik_id', $input_distrik)->where('tahun', $input_tahun)->where('prk', $result_data['PRK'][$i]->value)->whereNull('po_no')->first();
                            } else {
                                // dd('2');
                                $status_ai_pjb = PgdlStatusAiPjb::where('distrik_id', $input_distrik)->where('tahun', $input_tahun)->where('prk', $result_data['PRK'][$i]->value)->first();
                            }
                            // dd($status_ai_pjb);
                            foreach($settings as $column_setting){
                                if($column_setting->judul_kolom == 'PRK'){
                                    $kolom_prk = $column_setting->kolom;
                                    $prk = $result_data[$column_setting->judul_kolom][$i]->value;
                                }

                                // jika dari Query pjprk
                                if($column_setting->pgdl_report_dashboard_source_id == 3){
                                    $temp[$column_setting->sequence] = $item_po[$column_setting->kolom];
                                }
                                // hardcode untuk kolom pada urutan ke x
                                elseif($column_setting->pgdl_report_dashboard_source_id == 5){
                                    if($column_setting->sequence == 21)
                                        $temp[$column_setting->sequence] = ($temp[10] == 0 ? 0 : $temp[19] / $temp[10]);
                                    elseif($column_setting->sequence == 22)
                                        $temp[$column_setting->sequence] = ($temp[11] == 0 ? 0 : $temp[20] / $temp[11]);
                                    elseif($column_setting->sequence == 23)
                                        $temp[$column_setting->sequence] = ($temp[5] == 0 ? 0 : $temp[15] / $temp[5]);
                                    // elseif($column_setting->sequence == 24)
                                    //     $temp[$column_setting->sequence] = ($temp[5] == 0 ? 0 : $temp[11] / $temp[5]);
                                    elseif($column_setting->sequence == 25)
                                        $temp[$column_setting->sequence] = ($temp[8] == 0 ? 0 : $temp[16] / $temp[8]);
                                    // elseif($column_setting->sequence == 26)
                                    //     $temp[$column_setting->sequence] = ($temp[7] == 0 ? 0 : $temp[12] / $temp[7]);
                                    elseif($column_setting->sequence == 10)
                                        $temp[$column_setting->sequence] = ($temp[4] == 0 ? 0 : 1);
                                    elseif($column_setting->sequence == 11)
                                        $temp[$column_setting->sequence] = ($temp[7] == 0 ? 0 : 1);

                                    elseif($column_setting->sequence == 12)
                                        $temp[$column_setting->sequence] = $status_ai_pjb != null ? ($status_ai_pjb->date_kontrak != null ? date('d-m-Y', strtotime($status_ai_pjb->date_kontrak)) : '-') : '-';

                                    elseif($column_setting->sequence == 13)
                                        $temp[$column_setting->sequence] = $status_ai_pjb != null ? ($status_ai_pjb->date_disburse != null ? date('d-m-Y', strtotime($status_ai_pjb->date_disburse)) : '-') : '-';

                                    elseif ($column_setting->sequence == 19) {
                                        $temp[$column_setting->sequence] = $status_ai_pjb != null ? ( $status_ai_pjb->status_kontrak_id == 1 ? 1 : 0) : '-';

                                        // $status_ai_pjb->status_kontrak_id == 1 ? 1 : 0;
                                    }

                                    elseif ($column_setting->sequence == 20) {
                                        $temp[$column_setting->sequence] = $status_ai_pjb != null ? ( $status_ai_pjb->status_disburse_id == 1 ? 1 : 0) : '-';

                                        // $temp[$column_setting->sequence] = $status_ai_pjb->status_disburse_id == 1 ? 1 : 0;
                                    }

                                    elseif($column_setting->judul_kolom == 'Status Kontrak')
                                        $temp[$column_setting->sequence] = $status_ai_pjb != null ? ($status_ai_pjb->status_kontrak!= null ? $status_ai_pjb->status_kontrak->name : '-') : '-';
                                    elseif($column_setting->judul_kolom == 'Status Disburse')
                                        $temp[$column_setting->sequence] = $status_ai_pjb != null ? ($status_ai_pjb->status_disburse != null ? $status_ai_pjb->status_disburse->name : '-') : '-';
                                }
                                // jika dari Ketetapan, cari berdasarkan nomor PRK
                                elseif($column_setting->pgdl_report_dashboard_source_id == 1){
                                    $ketetapan_result = $this->get_data_ketetapan($file_imports_ketetapan, $column_setting->pgdl_sheet_name, $column_setting->kolom, $prk, $kolom_prk);
                                    // jika tidak ditemukan, isi 0
                                    $temp[$column_setting->sequence] = !empty($ketetapan_result[0]) ? $ketetapan_result[0]->value : 0;
                                }
                                // jika dari pengendalian, isi sesuai hasil
                                else{
                                    $temp[$column_setting->sequence] = !empty($result_data[$column_setting->judul_kolom][$i]) ? $result_data[$column_setting->judul_kolom][$i]->value : 0;
                                }
                            }
                            array_push($ai_pjb_result, $temp);
                        }
                    }
                }
            }
            // dd($ai_pjb_result);
            if($request->type){
                if($request->type=='excel'){

                    Excel::create('Monitoring AI PJB', function ($excel) use($ai_pjb_result,$input_tahun,$distrik,$lokasi,$nama_bln_dipilih){
                            $excel->setTitle('Monitoring AI PJB');
                            $excel->setCreator('Laravel')->setCompany('Profio', 'PJB');
                            $excel->setDescription('Monitoring AI PJB');
                            $excel->sheet('Monitoring AI PJB', function ($sheet) use($ai_pjb_result, $input_tahun, $distrik, $lokasi, $nama_bln_dipilih){
                                $sheet->loadView('pengendalian_output.ai_pjb.excel', compact('ai_pjb_result', 'input_tahun', 'distrik', 'lokasi', 'nama_bln_dipilih'));
                        });
                    })->download('xlsx');
                }
            }
            return view('pengendalian_output.ai_pjb.index', compact('ai_pjb_result', 'input_tahun', 'distrik', 'lokasi', 'nama_bln_dipilih','notification_failed'));
        }
    }

    function get_file_id_ketetapan($jenis_id, $tahun_anggaran, $distrik_id){
        $files = DB::select("select p.id
                            from file_imports_ketetapan p
                            join templates t on t.id = p.template_id
                            where t.jenis_id = ".$jenis_id."
                            and p.tahun=".$tahun_anggaran."
                            and p.distrik_id = ".$distrik_id.";");
        if($files){
            $res = [];
            $i=0;
            foreach ($files as $key => $value) {
              $res[$i] = $value->id;
              $i++;
            }
            $res = implode(",", $res);
            //dd(var_dump($new));
            $res = "(".$res.")";
            return $res;
        }
        return $files;
    }

    function get_file_id_pengendalian($jenis_id, $tahun_anggaran, $distrik_id){
        $files = DB::select("select p.id
                            from pgdl_file_imports_revisi p
                            join templates t on t.id = p.template_id
                            where t.jenis_id = ".$jenis_id."
                            and p.tahun=".$tahun_anggaran."
                            and p.distrik_id = ".$distrik_id.";");
        if($files){
            $res = [];
            $i=0;
            foreach ($files as $key => $value) {
              $res[$i] = $value->id;
              $i++;
            }
            $res = implode(",", $res);
            //dd(var_dump($new));
            $res = "(".$res.")";
            return $res;
        }
        return $files;
    }

    function get_data_ketetapan($file_id, $sheet_name, $kolom, $prk, $kolom_prk){
        $datas = DB::select("select e.row, e.value
                            from excel_datas_ketetapan e
                            join sheets s on s.id = e.sheet_id
                            where s.name = '".$sheet_name."'
                            and e.file_import_ketetapan_id in ".$file_id."
                            and e.kolom = '".$kolom."'
                            and e.row = (
                                select row
                                from excel_datas_ketetapan
                                where file_import_ketetapan_id in ".$file_id."
                                and kolom = '".$kolom_prk."'
                                and value = '".$prk."'
                                order by row ASC
                                limit 1
                            );");
        return $datas;
    }

    function get_data_pengendalian($file_id, $sheet_name, $kolom){
        $datas = DB::select("select e.row, e.value
                            from pgdl_excel_datas_revisi e
                            join pgdl_sheets s on s.id = e.pgdl_sheet_id
                            where s.name = '".$sheet_name."'
                            and e.pgdl_file_import_revisi_id in ".$file_id."
                            and e.kolom = '".$kolom."';");
        return $datas;
    }

    function get_pjprk_ai_data($kolom, $no_prk, $month, $year, $int_input_distrik){

        // disburse
        $name_distrik = Distrik::where('id', $int_input_distrik)->first()->code1;

        if($kolom == 'po_no'){
            $res = DB::select("select distinct po_no, account_code, po_item
                              from pgdl_pljprk_ai
                              where project_no = '".substr($no_prk,2)."'
                              and months between 1 and ".$month."
                              and years = ".$year."
                              and dstrct_code = '".$name_distrik."'
                              order by po_no asc;");
        }
        else{

        }
        return $res;
    }

    function get_item_po($no_prk, $month, $year, $po_no, $po_item, $account_code, $int_input_distrik){

        $name_distrik = Distrik::where('id', $int_input_distrik)->first()->code1;

        $query_po_no = "= '".$po_no."'";
        if($po_no==NULL) {
            $query_po_no = 'IS NULL';
        }

        $query_po_item = "= '".$po_item."'";
        if($po_item==NULL) {
            $query_po_item = 'IS NULL';
        }

        $res = DB::select(" select val_required, SUM(tran_amount) as tran_amount
                              from pgdl_pljprk_ai
                              where project_no = '".substr($no_prk,2)."'
                                and po_no ".$query_po_no."
                                and po_item ".$query_po_item."
                                and account_code = '".$account_code."'
                                and months between 1 and ".$month."
                                and dstrct_code = '".$name_distrik."'
                              group by val_required");

        return $res;
    }
}
