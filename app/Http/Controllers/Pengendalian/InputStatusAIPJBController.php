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
use App\Entities\PgdlMasterStatusAiPjb;
use App\Entities\PgdlStatusAiPjb;

Use DB;
use Illuminate\Support\Facades\Input;

class InputStatusAIPJBController extends Controller
{
    public function index(Request $request)
    {
        $sb = StrategiBisnis::all();

        $fase = Fase::all();
        $tahun = Template::select('tahun')->where('jenis_id', 2)->orWhere('jenis_id',1)->orWhere('jenis_id',3)->distinct()->get();
        $months = array('','Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');

        $user_id = session('user_id');
        $user = User::find($user_id);
        $role_id = session('role_id');
        $role = Role::find($role_id);


        $input_tahun = $request->input('tahun_anggaran');
        $input_sb = $request->input('strategi_bisnis');
        $input_distrik = $request->input('distrik');

        $int_input_distrik = (int)$input_distrik;
        $distrik = Distrik::where('id', $int_input_distrik)->first();

        $input_lokasi = $request->input('lokasi');
        $int_input_lokasi = (int)$input_lokasi;
        $lokasi = Lokasi::where('id', $int_input_lokasi)->first();

        if ($request->input('strategi_bisnis') != NULL) {
            $strategi_bisnis_dipilih = DB::table('strategi_bisnis')->select('name','id')->where('id', $request->input('strategi_bisnis'))->get();
            if(count($strategi_bisnis_dipilih) == 0)
                return redirect('pagenotfound');
            $strategi_bisnis_dipilih = $strategi_bisnis_dipilih[0];
            $distrik = Distrik::select('name','id')->where('strategi_bisnis_id',$strategi_bisnis_dipilih->id)->get();
        }
        if ($request->input('distrik') != NULL) {
            $distrik_dipilih = DB::table('distrik')->select('name','id')->where('id', $request->distrik)->get();
            if(count($distrik_dipilih) == 0)
                return redirect('pagenotfound');
            $distrik_dipilih = $distrik_dipilih[0];
            $lokasi = Lokasi::select('name','id')->where('distrik_id',$distrik_dipilih->id)->get();
        }

        $input_fase = $request->input('fase');
        $input_bulan = $request->input('bulan');
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

        $nama_bln_dipilih = ($int_input_bulan < 1 || $int_input_bulan > 12 ? '' : $nama_bln[$int_input_bulan]);

        $source = array();
        $ai_pjb_result = array();

        if($input_distrik != null && $input_tahun != null && $input_bulan){
            $jenis_form_yg_digunakan = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', 7)
                                    ->whereNotNull('jenis_id')
                                    ->where('tahun', $input_tahun)
                                    ->select('jenis_id')
                                    ->distinct()
                                    ->get();
            if(count($jenis_form_yg_digunakan) == 0)
                $notification_failed = 'Setting Report Dashboard Status AI PJB untuk tahun '.$input_tahun.' belum dibuat!';

            foreach ($jenis_form_yg_digunakan as $key => $jenis_form) {
                $file_imports_ketetapan = $this->get_file_id_ketetapan($jenis_form->jenis_id,$input_tahun,$int_input_distrik);
                $file_imports_pgdl = $this->get_file_id_pengendalian($jenis_form->jenis_id,$input_tahun,$int_input_distrik);

                $settings = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id',7)
                            ->where('jenis_id',$jenis_form->jenis_id)
                            ->where('sequence', '<>', 24)
                            ->where('sequence', '<', 26)
                            ->orderBy('sequence')
                            ->get();
                            // dd($settings);
                if($file_imports_ketetapan != null && $file_imports_pgdl != null){
                    $prk_data = $this->get_data_pengendalian($file_imports_pgdl, $settings[0]->pgdl_sheet_name, $settings[0]->kolom);
                    $file_import_revisi_ids = $this->get_file_import_revisi_id($file_imports_pgdl, $settings[0]->pgdl_sheet_name, $settings[0]->kolom);
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


                    for($i=0; $i<$total_data; $i++){
                        $temp = array();
                        $temp['pgdl_file_import_revisi_id'] = $file_import_revisi_ids[$i]->pgdl_file_import_revisi_id;

                        $prk = $result_data['PRK'][$i]->value;
                        $jumlah_item_po = 0;
                        $item_po_data = array();
                        $pjprk_ai_data = $this->get_pjprk_ai_data('po_no', $prk, $input_bulan, $input_tahun);
                        $jumlah_item_po = count($pjprk_ai_data);
                        // jika ada lebih dari 1 nomer PO
                        if($jumlah_item_po > 0){
                            foreach ($pjprk_ai_data as $key => $pjprk_data) {
                                $item_po = $this->get_item_po($prk, $input_bulan, $input_tahun, $pjprk_data->po_no, $pjprk_data->po_item, $pjprk_data->account_code);
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
                        foreach($item_po_data as $key => $item_po){
                            if($item_po['po_no']==null)
                                $status_ai_pjb = PgdlStatusAiPjb::where('distrik_id', $input_distrik)->where('tahun', $input_tahun)->where('prk', $result_data['PRK'][$i]->value)->whereNull('po_no')->first();
                            else
                                $status_ai_pjb = PgdlStatusAiPjb::where('distrik_id', $input_distrik)->where('tahun', $input_tahun)->where('prk', $result_data['PRK'][$i]->value)->where('po_no', $item_po['po_no'])->first();
                            // dd($status_ai_pjb);

                            foreach($settings as $column_setting){
                                $temp[$column_setting->sequence] = array();
                                $temp[$column_setting->sequence]['title'] = trim($column_setting->judul_kolom);
                                if($column_setting->judul_kolom == 'PRK'){
                                    $kolom_prk = $column_setting->kolom;
                                    $prk = $result_data[$column_setting->judul_kolom][$i]->value;
                                }

                                // jika dari Query pjprk
                                if($column_setting->pgdl_report_dashboard_source_id == 3){
                                    $temp[$column_setting->sequence]['value'] = $item_po[$column_setting->kolom];;
                                }
                                // hardcode untuk kolom pada urutan ke x
                                elseif($column_setting->pgdl_report_dashboard_source_id == 5){
                                    if($column_setting->sequence == 21)
                                        $temp[$column_setting->sequence]['value'] = (!is_numeric($temp[10]['value']) || $temp[10]['value'] == 0 ? 0 : $temp[19]['value'] / $temp[10]['value']);
                                    elseif($column_setting->sequence == 22)
                                        $temp[$column_setting->sequence]['value'] = (!is_numeric($temp[11]['value']) || $temp[11]['value'] == 0 ? 0 : $temp[20]['value'] / $temp[11]['value']);
                                    elseif($column_setting->sequence == 23)
                                        $temp[$column_setting->sequence]['value'] = (!is_numeric($temp[5]['value']) || $temp[5]['value'] == 0 ? 0 : $temp[15]['value'] / $temp[5]['value']);
                                    // elseif($column_setting->sequence == 24)
                                    //     $temp[$column_setting->sequence]['value'] = (!is_numeric($temp[5]['value']) || $temp[5]['value'] == 0);
                                    // elseif($column_setting->sequence == 24)
                                    //     $temp[$column_setting->sequence]['value'] = (!is_numeric($temp[5]['value']) || $temp[5]['value'] == 0 ? 0 : $temp[11]['value'] / $temp[5]['value']);
                                    elseif($column_setting->sequence == 25)
                                        $temp[$column_setting->sequence]['value'] = (!is_numeric($temp[8]['value']) || $temp[8]['value'] == 0 ? 0 : $temp[16]['value'] / $temp[8]['value']);
                                    // elseif($column_setting->sequence == 26)
                                    //     $temp[$column_setting->sequence]['value'] = (!is_numeric($temp[7]['value']) || $temp[7]['value'] == 0);
                                    // elseif($column_setting->sequence == 26)
                                    //     $temp[$column_setting->sequence]['value'] = (!is_numeric($temp[7]['value']) || $temp[7]['value'] == 0 ? 0 : $temp[12]['value'] / $temp[7]['value']);
                                    elseif($column_setting->sequence == 10)
                                        $temp[$column_setting->sequence]['value'] = ($temp[4]['value'] == 0 ? 0 : 1);
                                    elseif($column_setting->sequence == 11)
                                        $temp[$column_setting->sequence]['value'] = ($temp[7]['value'] == 0 ? 0 : 1);

                                     elseif($column_setting->sequence == 12)
                                        $temp[$column_setting->sequence]['value'] = $status_ai_pjb != null ? ($status_ai_pjb->date_kontrak != null ? date('d-m-Y', strtotime($status_ai_pjb->date_kontrak)) : '-') : '-';

                                    elseif($column_setting->sequence == 13)
                                        $temp[$column_setting->sequence]['value'] = $status_ai_pjb != null ? ($status_ai_pjb->date_disburse != null ? date('d-m-Y', strtotime($status_ai_pjb->date_disburse)) : '-') : '-';

                                    elseif ($column_setting->sequence == 19) {
                                        $temp[$column_setting->sequence]['value'] = $status_ai_pjb != null ? ( $status_ai_pjb->status_kontrak_id == 1 ? 1 : 0) : '-';
                                        // $status_ai_pjb->status_kontrak_id == 1 ? 1 : 0;
                                    }
                                    elseif ($column_setting->sequence == 20) {
                                        $temp[$column_setting->sequence]['value'] = $status_ai_pjb != null ? ( $status_ai_pjb->status_disburse_id == 1 ? 1 : 0) : '-';
                                        // $status_ai_pjb->status_disburse_id == 1 ? 1 : 0;
                                    }

                                    // elseif ($column_setting->sequence == 19) {
                                    //     $temp[$column_setting->sequence]['value'] = $status_ai_pjb->status_kontrak_id == 1 ? 1 : 0;
                                    // }
                                    // elseif ($column_setting->sequence == 20) {
                                    //     $temp[$column_setting->sequence]['value'] = $status_ai_pjb->status_disburse_id == 1 ? 1 : 0;
                                    // }

                                    elseif($column_setting->judul_kolom == 'Status Kontrak')
                                        $temp[$column_setting->sequence]['value'] = !empty($status_ai_pjb) ? $status_ai_pjb->status_kontrak_id : '-';
                                    elseif($column_setting->judul_kolom == 'Status Disburse')
                                        $temp[$column_setting->sequence]['value'] = !empty($status_ai_pjb) ? $status_ai_pjb->status_disburse_id : '-';
                                }
                                // jika dari Ketetapan, cari berdasarkan nomor PRK
                                elseif($column_setting->pgdl_report_dashboard_source_id == 1){
                                    $ketetapan_result = $this->get_data_ketetapan($file_imports_ketetapan, $column_setting->pgdl_sheet_name, $column_setting->kolom, $prk, $kolom_prk);
                                    // jika tidak ditemukan, isi 0
                                    $temp[$column_setting->sequence]['value'] = !empty($ketetapan_result[0]) ? $ketetapan_result[0]->value : 0;
                                }
                                else{
                                    // if($i==0)
                                        // dd($result_data[$column_setting->judul_kolom][$i]->value);
                                    $temp[$column_setting->sequence]['value'] = (empty($result_data[$column_setting->judul_kolom])? 0 : $result_data[$column_setting->judul_kolom][$i]->value);
                                }
                            }
                            array_push($ai_pjb_result, $temp);
                        }
                    }
                }
            }
        }
        $master_status_ai_pjb = PgdlMasterStatusAiPjb::all();

        // dd($ai_pjb_result);

        return view('pengendalian_input.status_ai_pjb.index', compact('ai_pjb_result', 'sb', 'fase', 'input_tahun', 'input_sb', 'input_distrik','input_lokasi', 'input_fase', 'months', 'input_bulan', 'tahun', 'distrik', 'master_status_ai_pjb','notification_failed', 'date_kontrak', 'date_disburse'));
    }

    public function store(Request $request){
        // dd($request->all());
        $distrik_id = $request->input('distrik_id');
        $strategi_bisnis = $request->input('strategi_bisnis_id');
        $bulan = $request->input('bulan');
        $tahun_anggaran = $request->input('tahun_anggaran');
        $po_no = $request->input('po_no');
        $prk = $request->input('prk');
        $status_kontrak = $request->input('status_kontrak');
        $status_disburse = $request->input('status_disburse');
        $file_import_revisi_id = $request->input('file_import_revisi_id');
        $date_kontrak = $request->input('date_kontrak');
        $date_disburse = $request->input('date_disburse');

        // error handling jika salah satu parameter tidak ditemukan
        if(empty($distrik_id) && empty($strategi_bisnis) && empty($tahun_anggaran) && empty($po_no) && empty($prk) && empty($status_kontrak) && empty($status_disburse) && empty($file_import_revisi_id))
            return redirect('pengendalian/input_status_ai_pjb');
        // dd($prk, $po_no);
        foreach ($prk as $key => $value) {
            if(array_key_exists($key, $po_no))
                // $po_no[$key] != null
                $status = PgdlStatusAiPjb::where('distrik_id', $distrik_id)->where('tahun', $tahun_anggaran)->where('prk', $value)->where('po_no',$po_no[$key])->first();
            else
                $status = PgdlStatusAiPjb::where('distrik_id', $distrik_id)->where('tahun', $tahun_anggaran)->where('prk', $value)->whereNull('po_no')->first();

            // cek jika ada, update
            // dd($status);
            $data = array();
            if(!empty($status)){
                $data['status_kontrak_id'] = $status_kontrak[$key];
                $data['status_disburse_id'] = $status_disburse[$key];
                $data['date_kontrak'] = $date_kontrak[$key];
                $data['date_disburse'] = $date_disburse[$key];

                $status->update($data);
            }

            // jika nggak ada tambah baru
            else{
                $pgdl_status_ai_pjb = New PgdlStatusAiPjb();
                $pgdl_status_ai_pjb->distrik_id = $distrik_id;
                $pgdl_status_ai_pjb->tahun = $tahun_anggaran;
                $pgdl_status_ai_pjb->prk = $value;
                $pgdl_status_ai_pjb->po_no = $po_no[$key];
                $pgdl_status_ai_pjb->file_import_revisi_id = $file_import_revisi_id[$key];
                $pgdl_status_ai_pjb->status_kontrak_id = $status_kontrak[$key];
                $pgdl_status_ai_pjb->status_disburse_id = $status_disburse[$key];
                $pgdl_status_ai_pjb->date_kontrak = $date_kontrak[$key];
                $pgdl_status_ai_pjb->date_disburse = $date_disburse[$key];
                $pgdl_status_ai_pjb->save();
            }
        }
        $request->session()->flash('success', 'Data Status AI PJB berhasil diupdate!');
        return redirect('pengendalian/input_status_ai_pjb?tahun_anggaran='.$tahun_anggaran.'&strategi_bisnis='.$strategi_bisnis.'&distrik='.$distrik_id.'&bulan='.$bulan.'');

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
                            join sheets s on s.id = e.sheet_id
                            where s.name = '".$sheet_name."'
                            and e.pgdl_file_import_revisi_id in ".$file_id."
                            and e.kolom = '".$kolom."';");
        return $datas;
    }

    function get_pjprk_ai_data($kolom, $no_prk, $month, $year){

        // disburse

        if($kolom == 'po_no'){
            $res = DB::select("select distinct po_no, account_code, po_item
                              from pgdl_pljprk_ai
                              where project_no = '".substr($no_prk,2)."'
                              and months between 1 and ".$month."
                              and years = ".$year."
                              order by po_no asc;");
        }
        else{

        }
        return $res;
    }

    function get_item_po($no_prk, $month, $year, $po_no, $po_item, $account_code){
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
                              group by val_required");

        return $res;
    }

    function get_file_import_revisi_id($file_id, $sheet_name, $kolom){
        $datas = DB::select("select e.pgdl_file_import_revisi_id
                            from pgdl_excel_datas_revisi e
                            join sheets s on s.id = e.sheet_id
                            where s.name = '".$sheet_name."'
                            and e.pgdl_file_import_revisi_id in ".$file_id."
                            and e.kolom = '".$kolom."';");
        return $datas;
    }
}
