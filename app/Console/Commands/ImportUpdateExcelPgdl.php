<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Entities\Distrik;
use App\Entities\ExcelData;
use App\Entities\PGDLExcelDataRevisi;
use App\Entities\PGDLFileImportRevisi;
use App\Entities\Fase;
use App\Entities\FileImport;
use App\Entities\FileImportKetetapan;
use App\Entities\FileApproval;
use App\Entities\PgdlHistoryLog;
use App\Entities\History;
use App\Entities\Jenis;
use App\Entities\Lokasi;
use App\Entities\Sheet;
use App\Entities\PgdlSheet;
use App\Entities\SheetSetting;
use App\Entities\PgdlSheetSetting;
use App\Entities\StrategiBisnis;
use App\Entities\Template;
use App\Entities\Version;
use App\Entities\PgdlVersion;
use App\Entities\PgdlReportDashboardSetting;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\WriterFactory;
use Illuminate\Http\Request;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\ValidationExcelTrait;

class ImportUpdateExcelPgdl extends Command
{
    use ValidationExcelTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:insert_update_excel_pgdl {id} {user} {tahun} {sheet*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Data Insert';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

      $id = $this->argument('id');

      $user_id = $this->argument('user');
      $sheet_request = $this->argument('sheet');
      $tahun = $this->argument('tahun');

      $fileimport = PGDLFileImportRevisi::find($id);
      $fileimport->status_upload_id = '2'; //sedang diupload
      $fileimport->save();
      $version_id = $fileimport->pgdl_version_id;
      $version_id_nonpgdl = $fileimport->version_id;

      $version_nonpgdl = Version::with('template')->where('id', $version_id_nonpgdl)->first();
      // $sheet_nonpgdl = Sheet::where('version_id', $version_id_nonpgdl)->get();

      // foreach ($sheet_nonpgdl as $sn) {
      //   $sheet_nonpgdl = $sn;
      // }

      $version = PgdlVersion::with('pgdl_template')->where('id', $version_id)->first();

      $sheet_md = PgdlSheet::where('pgdl_version_id', $version_id)->get();

      $sheet = PgdlSheet::all();

      $strategi_bisnis = StrategiBisnis::all();
      $distrik = Distrik::all();
      $lokasi = Lokasi::all();
      // dd($sheet_request);

      $error = [];
      $jenis_id = $version->pgdl_template->jenis_id;
      // mendapatkan data file import pengendalian rkau
      if ($jenis_id == Jenis::FORM_RKAU) {
          $file_import_rkau = $fileimport;
      } elseif ($jenis_id == Jenis::FORM_6_REIMBURSE) {
          $file_import_rkau = PGDLFileImportRevisi::where('form6_reimburse_pgdl_file_import_revisi_id', $fileimport->id)->where('distrik_id', $fileimport->distrik_id)->first();
      } elseif ($jenis_id == Jenis::FORM_6_RUTIN) {
          $file_import_rkau = PGDLFileImportRevisi::where('form6_rutin_pgdl_file_import_revisi_id', $fileimport->id)->where('distrik_id', $fileimport->distrik_id)->first();
      } elseif ($jenis_id == Jenis::FORM_10_PU) {
          $file_import_rkau = PGDLFileImportRevisi::where('form10_pu_pgdl_file_import_revisi_id', $fileimport->id)->where('distrik_id', $fileimport->distrik_id)->first();
      } elseif ($jenis_id == Jenis::FORM_10_PENGUATANKIT) {
          $file_import_rkau = PGDLFileImportRevisi::where('form10_penguatankit_pgdl_file_import_revisi_id', $fileimport->id)->where('distrik_id', $fileimport->distrik_id)->first();
      } elseif ($jenis_id == Jenis::FORM_10_PLN) {
          $file_import_rkau = PGDLFileImportRevisi::where('form10_pln_pgdl_file_import_revisi_id', $fileimport->id)->where('distrik_id', $fileimport->distrik_id)->first();
      } elseif ($jenis_id == Jenis::FORM_BAHAN_BAKAR) {
          $file_import_rkau = PGDLFileImportRevisi::where('form_bahan_bakar_pgdl_file_import_revisi_id', $fileimport->id)->where('distrik_id', $fileimport->distrik_id)->first();
      } elseif ($jenis_id == Jenis::FORM_PENYUSUTAN) {
          $file_import_rkau = PGDLFileImportRevisi::where('form_penyusutan_pgdl_file_import_revisi_id', $fileimport->id)->where('distrik_id', $fileimport->distrik_id)->first();
      }
      // dd($file_import_rkau);
      // jika tidak ditemukan link terhadap file pengendalian rkau, maka cancel update I-LR dan I-CF
      if (!$file_import_rkau) {
        $error[] = 'File Import RKAU Tidak Ditemukan!';
      }

      if ($version->pgdl_template->jenis_id == 1) {
        for ($i=0; $i < count($sheet_request); $i++) {
          $all_sheet_id[] = PgdlSheet::where('name', $sheet_request[$i])->first()->id;
        }
        // $s_id = array_slice($all_sheet_id, 2);
        $sheet_request_wo_lr_cf = array_slice($sheet_request, 2);
      } else {
        $sheet_request_wo_lr_cf = $sheet_request;
      }
      // $sheet_request_wo_lr_cf = $sheet_request;

      // dd($all_sheet_id, $sheet_request);
      $kolom_yg_digunakan = array();
      for ($i=0; $i < count($sheet_request_wo_lr_cf); $i++) {
        $kolom_yg_digunakan[] = DB::table('pgdl_report_dashboard_settings')
                                ->where('pgdl_report_dashboard_page_id', 8)
                                ->where('tahun', $tahun)
                                ->where('jenis_id', $jenis_id)
                                ->where('pgdl_sheet_name', $sheet_request_wo_lr_cf[$i] )
                                ->orderBy('sequence', 'asc')->get();
      }
      // dd($kolom_yg_digunakan);
      // $mulai = 13;
      $nama_sheet = [];
      $kolom = [];
      foreach ($kolom_yg_digunakan as $k) {
        foreach ($k as $value) {
          $kolom[] = ['pgdl_sheet_name' => $value->pgdl_sheet_name ,'kolom' => $value->kolom];
          $nama_sheet[] = $value->pgdl_sheet_name;

          // $mulai++;
          if ($value->judul_kolom == 'Nomor PRK') {
            $nomor_prk = $value->kolom;
          }
        }
      }

      $reader = ReaderFactory::create(Type::XLSX);

      $reader->open(base_path('public/temp/temp.xlsx'));

      $sheet_use = [];
      $sheet_id = [];
      $setting = [];

      foreach ($sheet_md as $row){
        if(in_array($row->name, $sheet_request)) {
          $sheet_use[] = $row->name;
          $sheet_id[] = $row->id;
          if(in_array($version->pgdl_template->jenis_id, Jenis::FORM_6_10)){
              $setting[$row->name] = PgdlSheetSetting::with('pgdl_sheet')->where('pgdl_sheet_id', $row->id)
                  ->orderBy('kolom', 'asc')
                  ->get();
          } else {
              $setting[$row->name] = PgdlSheetSetting::with('pgdl_sheet')->where('pgdl_sheet_id', $row->id)
                  ->orderBy('row', 'asc')
                  ->orderBy('kolom', 'asc')
                  ->get();
          }
        }
      }

      if (count($sheet_use) != count($sheet_request) ) {
        # code...
        $error[] = 'Excel tidak sesuai dengan template';
      }

      // dd($sheet_id);
      $fail_excel = PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $id)
        ->whereIn('pgdl_sheet_id', $sheet_id)
        ->get();

      $backup_data = [];
      foreach ($fail_excel as $row){
          $backup_data[] = [
            'pgdl_file_import_revisi_id' => $row->pgdl_file_import_revisi_id,
            'pgdl_sheet_id' => $row->pgdl_sheet_id,
            'lokasi_id' => $row->lokasi_id,
            'kolom' => $row->kolom,
            'row' => $row->row,
            'value' => $row->value,
          ];
      }
      // dump($fail_data);

      $sheet_data = [];
      $limit = 12;
      $lokasi_value = [];

        foreach ($reader->getSheetIterator() as $sheet) {
          if (in_array($sheet->getName(), $sheet_use)) {
              $i = 1;
              $lokasi_value[$sheet->getName()] = null;
              if(in_array($version->pgdl_template->jenis_id, Jenis::FORM_6_10)){
                  $i_cek = 1;
                  foreach ($sheet->getRowIterator() as $row){
                      if ($i_cek >= 13 && array_filter($row)) {
                          $limit++;
                      }
                      $i_cek++;
                  }
              }
              $break_i = false;
              foreach ($sheet->getRowIterator() as $row) {
                  $j = 'A';
                  if(in_array($version->pgdl_template->jenis_id, Jenis::FORM_6_10) && $i > $limit ){
                      break;
                  }
                  foreach ($row as $row2) {
                      if(in_array($version->pgdl_template->jenis_id, Jenis::FORM_6_10)){
                          if($i >= 13) {
                              $lokasi_value[$sheet->getName()][$i] = null;
                              if ($j == 'B') {
                                  $struktur_bisnis_value = $strategi_bisnis->where('name', $row2)->first();
                                  if(!$struktur_bisnis_value){
                                      $lokasi_value[$sheet->getName()][$i] = null;
                                      break;
                                  }
                              }
                              if ($j == 'C') {
                                  $distrik_value = $distrik->where('code1', $row2)->where('strategi_bisnis_id', $struktur_bisnis_value->id)->first();
                                  if(!$distrik_value){
                                      $lokasi_value[$sheet->getName()][$i] = null;
                                      break;
                                  }
                              }
                              if ($j == 'D') {
                                  $lokasi_cek = $lokasi->where('name', $row2)->where('distrik_id', $distrik_value->id)->first();
                                  $lokasi_value[$sheet->getName()][$i] = ($lokasi_cek)?$lokasi_cek->id:null;
                                  break;
                              }
                          }
                      } else {
                          if ($i == 3 && $j == 'C') {
                              $struktur_bisnis_value = $strategi_bisnis->where('name', $row2)->first();
                              if(!$struktur_bisnis_value){
                                  $lokasi_value[$sheet->getName()] = null;
                                  $break_i = true;
                                  break;
                              }
                          }
                          if ($i == 4 && $j == 'C') {
                              $distrik_value = $distrik->where('code1', $row2)->where('strategi_bisnis_id', $struktur_bisnis_value->id)->first();
                              if(!$distrik_value){
                                  $lokasi_value[$sheet->getName()] = null;
                                  $break_i = true;
                                  break;
                              }
                          }
                          if ($i == 5 && $j == 'C') {
                              if($distrik_value){
                                  $lokasi_cek = $lokasi->where('name', $row2)->where('distrik_id', $distrik_value->id)->first();
                                  $lokasi_value[$sheet->getName()] = ($lokasi_cek)?$lokasi_cek->id:null;
                                  $break_i = true;
                                  break;
                              }
                          }

                      }
                      $j++;
                  }
                  $i++;
                  if($break_i)
                  break;
              }
          }
      }


      $limit = 12;
      foreach ($reader->getSheetIterator() as $sheet) {
          if (in_array($sheet->getName(), $sheet_use)) {
              $i = 1;
              if(!in_array($version->pgdl_template->jenis_id, Jenis::FORM_6_10)){
                  if(!$lokasi_value[$sheet->getName()]){
                      $error[] = 'Data Sheet '.$sheet->getName().' Lokasi Tidak Sesuai!';
                      continue;
                  }
              }
              if(in_array($version->pgdl_template->jenis_id, Jenis::FORM_6_10)){
                  $i_cek = 1;
                  foreach ($sheet->getRowIterator() as $row){
                      if ($i_cek >= 13 && array_filter($row)) {
                          $limit++;
                      }
                      $i_cek++;
                  }
              }
              foreach ($sheet->getRowIterator() as $row) {
                  $j = 'A';
                  if(in_array($version->pgdl_template->jenis_id, Jenis::FORM_6_10) && $i > $limit ){
                      break;
                  }
                  if(in_array($version->pgdl_template->jenis_id, Jenis::FORM_6_10)){
                      if($i >= 13){
                          if(!$lokasi_value[$sheet->getName()][$i]){
                              $error[] = 'Data Row '.$i.' Lokasi Tidak Sesuai!';
                              $i++;
                              continue;
                          }
                      }
                  }
                  foreach ($row as $row2) {
                      if(in_array($version->pgdl_template->jenis_id, Jenis::FORM_6_10)){
                          if($i >= 13) {
                              if ($sheet_setting = ($setting[$sheet->getName()])->where('kolom', $j)->where('sequence', 0)->first()) {
                                  if (!$this->validation_pgdl($row2, $sheet_setting->validation, $sheet_setting->validation_type, $sheet_data, $sheet_setting->pgdl_sheet_id, $j)) {
                                      if($sheet_setting->validation_type == 'unique'){
                                          $error[] = 'Data Sheet ' . $sheet_setting->pgdl_sheet->name . ' ' . $sheet_setting->kolom . $i . ' Tidak Boleh Duplikasi!';
                                      } else {
                                          if($j == 'ID') {
                                              $error[] = 'Mohon Cek Kebenaran Data dan mohon untuk:<br>
                                                  a. Kolom cek tidak diubah rumusnya<br>
                                                  b. Pengisian sesuai standar pengisian Form yg berlaku<br>';
                                              break 2; //jika ada 1 data error maka langsung break
                                          }
                                          else {
                                              $error[] = 'Data Sheet ' . $sheet_setting->pgdl_sheet->name . ' ' . $sheet_setting->kolom . $i . ' Tidak Sesuai!';
                                          }
                                      }
                                  }
                                  $value = $row2;
                                  if($sheet_setting->validation_type == 'numeric'){
                                      $value = (int)$row2;
                                  }
                                  if($sheet_setting->validation_type == 'string'){
                                      $value = (string)$row2;
                                  }
                                  $sheet_data[] = [
                                      'pgdl_file_import_revisi_id' => $id,
                                      // 'sheet_id' => $sheet_nonpgdl->id,
                                      'pgdl_sheet_id' => $sheet_setting->pgdl_sheet_id,
                                      'lokasi_id' => $lokasi_value[$sheet->getName()][$i],
                                      'kolom' => $sheet_setting->kolom,
                                      'row' => $i ,
                                      'value' => $value,
                                  ];
                              }
                          }
                      } else {
                          if ($sheet_setting = ($setting[$sheet->getName()])->where('row', $i)->where('kolom', $j)->where('sequence', 0)->first()) {

                              if (!$this->validation_pgdl($row2, $sheet_setting->validation, $sheet_setting->validation_type, $sheet_data, $sheet_setting->pgdl_sheet_id, $j)) {
                                  if($sheet_setting->validation_type == 'unique'){
                                      $error[] = 'Data Sheet ' . $sheet_setting->pgdl_sheet->name . ' ' . $sheet_setting->kolom . $i . ' Tidak Boleh Duplikasi!';
                                  } else {
                                      if($j == 'ID') {
                                          $error[] = 'Mohon Cek Kebenaran Data dan mohon untuk:<br>
                                              a. Kolom cek tidak diubah rumusnya<br>
                                              b. Pengisian sesuai standar pengisian Form yg berlaku<br>';
                                          break 2; //jika ada 1 data error maka langsung break
                                      }
                                      else {
                                          $error[] = 'Data Sheet ' . $sheet_setting->pgdl_sheet->name . ' ' . $sheet_setting->kolom . $i . ' Tidak Sesuai!';
                                      }
                                  }
                              }

                              $value = $row2;
                              if($sheet_setting->validation_type == 'numeric'){
                                  $value = (int)$row2;
                              }
                              if($sheet_setting->validation_type == 'string'){
                                  $value = (string)$row2;
                              }
                              $sheet_data[] = [
                                  'pgdl_file_import_revisi_id' => $id,
                                  // 'sheet_id' => $sheet_nonpgdl->id,
                                  'pgdl_sheet_id' => $sheet_setting->pgdl_sheet_id,
                                  'lokasi_id' => $lokasi_value[$sheet->getName()],
                                  'kolom' => $sheet_setting->kolom,
                                  'row' => $sheet_setting->row,
                                  'value' => $value,
                              ];
                          }
                      }
                      $j++;
                  }
                  $i++;
              }
          }
      }
      // dd($sheet_data);
      // dd($nama_sheet, $sheet_request_wo_lr_cf);

      if (count(array_unique($nama_sheet)) != count($sheet_request_wo_lr_cf)) {

        $error[] = 'Setting Report Dashboard Histroy Log untuk tahun '.$tahun.' belum dibuat!';

      } else {

      $no_ok = array();
      $baris_updated = array();
      $data_fir = $id;
      if ($version->pgdl_template->jenis_id == '1' ) {

        $jml_ok = 1;
        foreach ($sheet_data as $sd) {
          if (in_array($sd['pgdl_sheet_id'], $all_sheet_id)) {
            if ($sd['value'] == 'OK' && $sd['kolom'] == 'C' || $sd['value'] == 'OK' && $sd['kolom'] == 'AR') {
              $no_ok[$jml_ok] = [ 'pgdl_sheet_id' => $sd['pgdl_sheet_id'],
                                'row' => $sd['row']];

              $jml_ok++;
            }

            $last_row[$sd['pgdl_sheet_id']] = $sd['row'];
          }
        }
        // dd($no_ok);
        if ($no_ok != null) {
            # code...
          foreach ($no_ok as $no) {
            $excel_data[] = PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $data_fir)->where('pgdl_sheet_id', $no['pgdl_sheet_id'])->where('row', $no['row'])->orderby('kolom')->get();

            $last_row_db[$no['pgdl_sheet_id']] =  PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $data_fir)->where('pgdl_sheet_id', $no['pgdl_sheet_id'])->orderby('row', 'DESC')->first()->row;
          }
          // dd($excel_data);
          // foreach ($excel_data as $ed) {
          //   $data_from_db = $ed;
          // }

          // if (count($data_from_db)) {
              $collect_data = [];
              foreach ($excel_data as $ed) {
                foreach ($ed as $e) {
                  $collect_data[] = ['pgdl_sheet_id' => $e['pgdl_sheet_id'], 'value' => $e['value'], 'row' => $e['row'], 'kolom' => $e['kolom']];

              

                }
              }
              // dd($last_row_db, $last_row);
              $same_last_row = 0;
              foreach ($last_row_db as $key1 => $value1) {
                # code...
                foreach ($last_row as $key2 => $value2) {
                  # code...
                  if ($key1 == $key2 && $value1 == $value2) {
                    # code...
                    $same_last_row = 1;
                  } 
                }
              }
              // die();
              // dd($same_last_row);
              $result_db = array();
              foreach($collect_data as $k => $v) {
                $pgdl_db = trim($v['pgdl_sheet_id']);
                $row_db = trim($v['row']);

                $result_db[ $pgdl_db ][ $row_db ][] = $v;
              }

              foreach ($no_ok as $nook) {
                foreach ($sheet_data as $sd) {
                  if ($sd['pgdl_sheet_id'] == $nook['pgdl_sheet_id'] && $sd['row'] == $nook['row']) {
                    $input_data_excel[] = ['pgdl_sheet_id' => $sd['pgdl_sheet_id'], 'kolom' => $sd['kolom'], 'row' => $sd['row'], 'value' => $sd['value'], 'row' => $sd['row']];
                  }
                }
              }

              $collect_data_input = [];
              foreach ($input_data_excel as $vl) {
                $collect_data_input[] = ['pgdl_sheet_id' => $vl['pgdl_sheet_id'], 'value' => $vl['value'], 'kolom' => $vl['kolom'], 'row' => $vl['row']];
              }

              // dd($collect_data_input);
              $baris_updated = [];
              $start = 13;
              foreach ($no_ok as $nk) {
                $baris_updated[$start] = $nk['row'];
                // $pgdl_sheet_id_choosen[$start] = $nk['pgdl_sheet_id'];

                $start++;
              }

              if (!count($error)) {
                # code...
                // $end = 13+$jml_ok-1;
                // for($k=13;$k<$end;$k++) {
                //     if(isset($baris_updated)) {
                //     // PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $data_fir)->where('pgdl_sheet_id', $pgdl_sheet_id_choosen[$k])->where('row', $baris_updated[$k])->delete();
                //   }
                // }
                // dd($sheet_id);
                PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $id)->whereIn('pgdl_sheet_id', $sheet_id)->delete();

                $sheet_chunk = array_chunk($sheet_data, 10000);
                foreach ($sheet_chunk as $chunk_data) {
                  foreach ($chunk_data as $cd) {
                    // if ($cd['kolom'] != 'C' && $cd['kolom'] != 'AR' ) {
                      foreach ($no_ok as $nomor_ok_pgdl) {
                        if ($cd['pgdl_sheet_id'] == $nomor_ok_pgdl['pgdl_sheet_id'] && $cd['row'] == $nomor_ok_pgdl['row'] ) {
                          if(isset($nomor_ok_pgdl['row']) && $cd['row'] > 12) {
                            $end = 13+$jml_ok-1;
                            for($start=13;$start<$end;$start++) {
                              if($cd['row'] == $start) {
                                $cd['row'] = $nomor_ok_pgdl['row'];
                                $cd['pgdl_file_import_revisi_id'] = $data_fir;
                              }
                              if ($cd['kolom'] == 'C' && $cd['value'] == 'OK' || $cd['kolom'] != 'AR' && $cd['value'] == 'OK') {
                                // code...
                                $cd['value'] = '';
                              }
                            }
                              // PGDLExcelDataRevisi::insert($cd);
                          }
                        }
                      }

                      if ($cd['kolom'] != 'C' && $cd['kolom'] != 'AR') {
                        # code..
                        if ($cd['value'] == '') {
                          # code...
                          $cd['value'] = 0;
                        }
                      }

                      PGDLExcelDataRevisi::insert($cd);
                    // }
                  }
                }
                // die();
              }
          // } else {
            // $error[] = 'Data tidak ditemukan';
          // }

          } else {
              $error[] = 'Tidak Ada Baris yang ditandai';
          }
      } else { // Tutup if $version->pgdl_template->jenis_id == '1'

        // SELAIN RKAU
      $jml_prk = 1;
      foreach ($sheet_data as $sd) {
        if ($version->pgdl_template->jenis_id == '7') { // Khusus Bahan Bakar
          // dump($sd);
          if ($sd['kolom'] == $nomor_prk || $sd['kolom'] == 'H' || $sd['kolom'] == 'S' || $sd['kolom'] == 'AF') {
            $row = trim($sd['row']);
            $no_ok[$jml_prk][$row] = $sd['value'];
            $jml_prk++;

          }
        } else { // Selain Bahan Bakar
          if ($sd['kolom'] == $nomor_prk) {
            $no_ok[$jml_prk] = $sd['value'];

            $jml_prk++;
          }
        }
      }
      // dd($no_ok);
      // dd('3');
      if ($no_ok[1] != null) {

        if ($version->pgdl_template->jenis_id == '7') {

          $n = 1;
          foreach ($no_ok as $row) {
            // code...
            foreach ($row as $key => $value) {
              // code...
              $array[$n] = PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $data_fir)->where('row', $key)->where('value', $value)->first();
              $n++;
            }
          }

          foreach($array as $value) {
            if(!empty($value)) {
              $out[] = $value;
            }
          }

          $data_updated = $out;

        } else {

          for($n=1;$n<$jml_prk;$n++) {
            $data_updated[$n] = PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $data_fir)->where('value', $no_ok[$n])->first();
          }
        }

        // dd($data_updated);
        if ($data_updated[1] != null) {
          // dd('5');
          $baris_updated = [];
          $start = 13;
          if ($version->pgdl_template->jenis_id == '7') {
            // code...
            for($n=1;$n<count($data_updated);$n++) {
              $baris_updated_data[$start] = $data_updated[$n]->row;

              $no_ok[$start] = [ 'pgdl_sheet_id' => $data_updated[$n]->pgdl_sheet_id,
                                  'row' => $data_updated[$n]->row];

              $start++;
            }

            $baris_update_unique = $baris_updated_data;
            $unique = array_unique($baris_update_unique);
            // dd($unique);
            $unique_merge = array_merge($unique);
            // dd($unique_merge);
            $m = 13;
            for ($i=0; $i < count($unique_merge); $i++) {
              // code...
              $baris_updated[$m] = $unique_merge[$i];
              $m++;
            }
          } else {
            for($n=1;$n<$jml_prk;$n++) {
              $baris_updated[$start] = $data_updated[$n]->row;

              $no_ok[$start] = [ 'pgdl_sheet_id' => $data_updated[$n]->pgdl_sheet_id,
                                  'row' => $data_updated[$n]->row];

              $start++;
            }
          }

          // dd($baris_updated, $data_fir);
          $excel_data = array();
          if ($version->pgdl_template->jenis_id == '7') {
            // code...
            $end = 13+count($baris_updated);
            for($k=13;$k<$end;$k++) {
              if(isset($baris_updated)) {
                $excel_data[$k] = PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $data_fir)->where('row', $baris_updated[$k])->get();
              }
            }
          } else {
            // code...
            $end = 13+$jml_prk-1;
            for($k=13;$k<$end;$k++) {
              if(isset($baris_updated)) {
                $excel_data[$k] = PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $data_fir)->where('row', $baris_updated[$k])->get();
              }
            }
          }

          // dd($error);
          // dd($excel_data);
          if (!count($error)) {
            # code...
            // dd('2');
            if ($version->pgdl_template->jenis_id == '7') {
              // code...
              $end = 13+count($baris_updated);
              for($n=13;$n<$end;$n++) {
                if(isset($baris_updated)) {
                    PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $data_fir)->where('row', $baris_updated[$n])->delete();
                }
              }
            } else {
              // code...
              $end = 13+$jml_prk-1;
              for($n=13;$n<$end;$n++) {
                if(isset($baris_updated)) {
                    PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $data_fir)->where('row', $baris_updated[$n])->delete();
                }
              }
            }

            $sheet_chunk = array_chunk($sheet_data, 10000);
            $user_id = session('user_id');
            foreach ($sheet_chunk as $chunk_data) {
              foreach ($chunk_data as $cd) {
                if(isset($baris_updated) && $cd['row'] > 12) {
                  if ($version->pgdl_template->jenis_id == '7') {
                    // code...
                    $end = 13+count($baris_updated);
                  } else {
                    // code...
                    $end = 13+$jml_prk-1;
                  }
                  for($start=13;$start<$end;$start++) {
                  // dump($start);
                      if($cd['row'] == $start) {
                          // dump($start);
                          $cd['row'] = $baris_updated[$start];
                          $cd['pgdl_file_import_revisi_id'] = $data_fir;
                      }
                  }
                  $cd['created_at'] = date('Y-m-d H:i:s');
                  $cd['created_by'] = $user_id;
                  // dd($cd);
                  PGDLExcelDataRevisi::insert($cd);
                }
              }
            }
            $error = $this->update_icf_ilr_rkau($version, $fileimport, $backup_data, $sheet_id, $error, $file_import_rkau);
          }
          // dd('3');
          $setting_log = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', 8)
          ->where('jenis_id', $jenis_id)
          ->where('tahun', $tahun)
          ->get();

          $data_log = [];
          foreach ($sheet_data as $cd) {
            if ($cd['kolom'] == $setting_log->where('judul_kolom', 'Nomor PRK')->first()->kolom) {
              $data_log[$cd['row']]['prk'] = $cd['value'];
              $data_log[$cd['row']]['user_id'] = $user_id;
              $data_log[$cd['row']]['pgdl_file_import_revisi_id'] = $data_fir;
              $data_log[$cd['row']]['created_at'] = date('Y-m-d H:i:s');
            } elseif ($cd['kolom'] == $setting_log->where('judul_kolom', 'Identity PRK')->first()->kolom && $cd['kolom'] == $setting_log->where('judul_kolom', 'Deskripsi PRK')->first()->kolom) {
              $data_log[$cd['row']]['identity_prk'] = $data_log[$cd['row']]['deskripsi_prk_akhir'] = $cd['value'];
            } elseif (($setting_log->where('judul_kolom', 'Beban')->first() && $cd['kolom'] == $setting_log->where('judul_kolom', 'Beban')->first()->kolom) || ($setting_log->where('judul_kolom', 'Anggaran Proyek')->first()
            && $cd['kolom'] == $setting_log->where('judul_kolom', 'Anggaran Proyek')->first()->kolom)) {
              $data_log[$cd['row']]['beban_akhir'] = $cd['value'];
            } elseif (($setting_log->where('judul_kolom', 'Cashflow')->first() && $cd['kolom'] == $setting_log->where('judul_kolom', 'Cashflow')->first()->kolom) || ($setting_log->where('judul_kolom', 'Anggaran Investasi')->first()
            && $cd['kolom'] == $setting_log->where('judul_kolom', 'Anggaran Investasi')->first()->kolom)) {
              $data_log[$cd['row']]['cashflow_akhir'] = $cd['value'];
            } elseif (($setting_log->where('judul_kolom', 'Ijin Proses')->first() && $cd['kolom'] == $setting_log->where('judul_kolom', 'Ijin Proses')->first()->kolom) || ($setting_log->where('judul_kolom', 'Disburse')->first()
            && $cd['kolom'] == $setting_log->where('judul_kolom', 'Disburse')->first()->kolom)) {
              $data_log[$cd['row']]['ijin_proses_akhir'] = $cd['value'];
            }
          }

          $start = 13;
          foreach ($excel_data as $ed) {
            foreach ($ed as $d) {
              if ($d['kolom'] == $setting_log->where('judul_kolom', 'Nomor PRK')->first()->kolom) {
                $data_log[$start]['prk_awal'] = $d['value'];
                $data_log[$start]['user_id_awal'] = $user_id;
                $data_log[$start]['pgdl_file_import_revisi_id_awal'] = $data_fir;
                $data_log[$start]['created_at_awal'] = date('Y-m-d H:i:s');
              } elseif
               ($d['kolom'] == $setting_log->where('judul_kolom', 'Deskripsi PRK')->first()->kolom) {
                  $data_log[$start]['deskripsi_prk_awal'] = $d['value'];
              } elseif (($setting_log->where('judul_kolom', 'Beban')->first() && $d['kolom'] == $setting_log->where('judul_kolom', 'Beban')->first()->kolom) || ($setting_log->where('judul_kolom', 'Anggaran Proyek')->first()
              && $d['kolom'] == $setting_log->where('judul_kolom', 'Anggaran Proyek')->first()->kolom)) {
                  $data_log[$start]['beban_awal'] = $d['value'];
              } elseif (($setting_log->where('judul_kolom', 'Cashflow')->first() && $d['kolom'] == $setting_log->where('judul_kolom', 'Cashflow')->first()->kolom) || ($setting_log->where('judul_kolom', 'Anggaran Investasi')->first()
              && $d['kolom'] == $setting_log->where('judul_kolom', 'Anggaran Investasi')->first()->kolom)) {
                  $data_log[$start]['cashflow_awal'] = $d['value'];
                  // dd('1');
              } elseif (($setting_log->where('judul_kolom', 'Ijin Proses')->first() && $d['kolom'] == $setting_log->where('judul_kolom', 'Ijin Proses')->first()->kolom) || ($setting_log->where('judul_kolom', 'Disburse')->first()
              && $d['kolom'] == $setting_log->where('judul_kolom', 'Disburse')->first()->kolom)) {
                  $data_log[$start]['ijin_proses_awal'] = $d['value'];
              }
            }
            $start++;
          }

          // dd($data_log);
          if (!count($error)) {
            foreach ($data_log as $dl) {
            $diff = array_diff($dl, array_diff_assoc($dl, array_unique($dl)));
            $pgdl_history_log = New PgdlHistoryLog;
            if ($diff) {
              $pgdl_history_log->prk = $dl['prk'];

              if ($version->pgdl_template->jenis_id == '9') {
                # code...
                $pgdl_history_log->identity_prk = 'Penyusutan';
                $pgdl_history_log->deskripsi_prk_awal = 'Penyusutan';
                $pgdl_history_log->deskripsi_prk_akhir = 'Penyusutan';

              } else {

                $pgdl_history_log->identity_prk = $dl['identity_prk'];

                if (array_key_exists('deskripsi_prk_awal', $dl) || array_key_exists('deskripsi_prk_akhir', $dl)) {
                  // if ($dl['deskripsi_prk_awal'] != $dl['deskripsi_prk_akhir']) {
                    $pgdl_history_log->deskripsi_prk_awal = $dl['deskripsi_prk_awal'];
                    $pgdl_history_log->deskripsi_prk_akhir = $dl['deskripsi_prk_akhir'];
                  // }
                }
              }
              if (array_key_exists('beban_awal', $dl) || array_key_exists('beban_akhir', $dl)) {
              // if ($dl['beban_awal'] != $dl['beban_akhir']) {
                $pgdl_history_log->beban_awal = $dl['beban_awal'];
                $pgdl_history_log->beban_akhir = $dl['beban_akhir'];
              // }
              }
              if (array_key_exists('cashflow_awal', $dl) || array_key_exists('cashflow_akhir', $dl)) {
              // if ($dl['cashflow_awal'] != $dl['cashflow_akhir']) {
                $pgdl_history_log->cashflow_awal = $dl['cashflow_awal'];
                $pgdl_history_log->cashflow_akhir = $dl['cashflow_akhir'];
              // }
              }
              if (array_key_exists('ijin_proses_awal', $dl) || array_key_exists('ijin_proses_akhir', $dl)) {
              // if ($dl['ijin_proses_awal'] != $dl['ijin_proses_akhir']) {
                $pgdl_history_log->ijin_proses_awal = $dl['ijin_proses_awal'];
                $pgdl_history_log->ijin_proses_akhir = $dl['ijin_proses_akhir'];
              // }
              }

              $pgdl_history_log->user_id = $dl['user_id'];
              $pgdl_history_log->pgdl_file_import_revisi_id = $data_fir;
              $pgdl_history_log->created_at = date('Y-m-d H:i:s');
              // dump($pgdl_history_log);
              $pgdl_history_log->save();
            }
          }
          // die();
        }
      } // Tutup if kurung $data_updated[1] != null
          else {
            $error[] = 'No PRK Tidak Ditemukan';
          }
        } // Tutup if kurung $no_prk[1] != null
          else {
            $error[] = 'Terdapat No PRK yang kosong di Data Excel';
          }
      } // tutup kurung else jenis = 1

    $setting_merge = collect();
    foreach ($setting as $row) {
      $setting_merge = $setting_merge->merge($row);
    }

    $input_rumus = [];
    foreach ($setting_merge->where('sequence', '>', 0)->sortBy('sequence')->groupBy('sequence') as $row) {
      $sheet_data = [];
      foreach ($row as $value) {
        if (in_array($version->pgdl_template->jenis_id, Jenis::FORM_6_10)) {
          for ($i = 13; $i < $limit; $i++) {
            if (!empty($value->query_value)) {
              $query_str = $this->sql_replace_pgdl($value->query_value, $id, $value->pgdl_sheet_id, $i, $value->kolom);
              $query = DB::select($query_str);
              $value_data = (!empty($query[0])) ? $query[0]->value : '';
              if (!$this->validation($value_data, $value->validation, $value->validation_type, $sheet_data, $value->pgdl_sheet_id, $value->kolom)) {
                if($value->validation_type == 'unique'){
                  $error[] = 'Data sheet ' . $value->pgdl_sheet->name . ' ' . $value->kolom . $i . ' Tidak Boleh Duplikasi!';
                } else {
                    $error[] = 'Data sheet ' . $value->pgdl_sheet->name . ' ' . $value->kolom . $i . ' Tidak Sesuai!';
                  }
              }
              $value_insert = $value_data;
              if($value->validation_type == 'numeric'){
                $value_insert = (int)$value_data;
              }
              if($value->validation_type == 'string'){
                $value_insert = (string)$value_data;
              }
              $sheet_data[] = [
                'pgdl_file_import_revisi_id' => $id,
                'sheet_id' => $value->sheet_id,
                'pgdl_sheet_id' => $value->pgdl_sheet_id,
                'lokasi_id' => $lokasi_value[$value->pgdl_sheet->name][$i],
                'kolom' => $value->kolom,
                'row' => $i,
                'value' => $value_insert,
              ];
            }
          }
        } else {
          if (!empty($value->query_value)) {
              $query_str = $this->sql_replace_pgdl($value->query_value, $id, $value->pgdl_sheet_id, $value->row, $value->kolom);
              $query = DB::select($query_str);
              $value_data = (!empty($query[0])) ? $query[0]->value : '';
              if (!$this->validation_pgdl($value_data, $value->validation, $value->validation_type, $sheet_data, $value->pgdl_sheet_id, $value->kolom)) {
                if($value->validation_type == 'unique'){
                    $error[] = 'Data sheet ' . $value->pgdl_sheet->name . ' ' . $value->kolom . $value->row . ' Tidak Boleh Duplikasi!';
                } else {
                    $error[] = 'Data sheet ' . $value->pgdl_sheet->name . ' ' . $value->kolom . $value->row . ' Tidak Sesuai!';
                }
              }
              $value_insert = $value_data;
              if($value->validation_type == 'numeric'){
                  $value_insert = (int)$value_data;
              }
              if($value->validation_type == 'string'){
                  $value_insert = (string)$value_data;
              }
              $sheet_data[] = [
                  'pgdl_file_import_revisi_id' => $id,
                  'pgdl_sheet_id' => $value->pgdl_sheet_id,
                  'kolom' => $value->kolom,
                  'row' => $value->row,
                  'lokasi_id' => $fileimport->lokasi_id,
                  'value' => $value_insert,
                  'created_by' => $user_id,
                  'created_at' => date('Y-m-d H:i:s')
              ];
            }
          }
        }

        foreach ($no_ok as $nook) {
          foreach ($sheet_data as $sd) {
            if ($sd['pgdl_sheet_id'] == $nook['pgdl_sheet_id'] && $sd['row'] == $nook['row']) {
              $input_rumus[] = ['pgdl_sheet_id' => $sd['pgdl_sheet_id'], 'kolom' => $sd['kolom'], 'row' => $sd['row'], 'value' => $sd['value']];
            }

            PGDLExcelDataRevisi::insert($sd);

          }
        }
        // die();
      }
    } // tutup if (count(array_unique($nama_sheet)) != count($sheet_request_wo_lr_cf))
      // dd($input_rumus);
      if(!count($error)) {
        // DB::transaction(function () use ($input_rumus, $sheet_id, $error, $sheet_data, $id, $version, $kolom, $baris_updated, $no_ok, $jml_ok, $user_id, $data_fir, $result_db, $jenis_id, $tahun, $fileimport, $collect_data_input, $sheet_request, $sheet_request_wo_lr_cf) {

          $setting_log = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', 8)
              ->where('jenis_id', $jenis_id)
              ->where('tahun', $tahun)
              ->whereIn('pgdl_sheet_name', $sheet_request)
              ->get();

          // dd($setting_log->where('judul_kolom', 'Nomor PRK')->where('pgdl_sheet_name', 'I-PEG')->first()->kolom);

          if ($version->pgdl_template->jenis_id == '1' ) {

            $collect_data_input_rumus = array();
            foreach ($input_rumus as $vl) {
              foreach ($kolom as $kev) {
                $sheet_id_from_name = PgdlSheet::where('name', $kev['pgdl_sheet_name'])->get();
                foreach ($sheet_id_from_name as $n) {
                  if ($vl['kolom'] == $kev['kolom'] && $vl['pgdl_sheet_id'] == $n->id) {
                    $collect_data_input_rumus[] = ['pgdl_sheet_id' => $vl['pgdl_sheet_id'], 'value' => $vl['value'], 'kolom' => $kev['kolom'], 'row' => $vl['row'] ];
                  }
                }
              }
            }
            // dd($collect_data_input_rumus);
            $collect_data_input_rumus_merge = array_merge($collect_data_input, $input_rumus);
            // dd($collect_data_input_rumus_merge);
            $result = array();
            foreach($collect_data_input_rumus_merge as $k => $v) {
              $pgdl = trim($v['pgdl_sheet_id']);
              $row = trim($v['row']);
              $result[ $pgdl ][ $row ][] = $v;
            }
            // dd($setting_log);
            if (count($setting_log)) {
              // code...
              $data_log = [];
              foreach ($result as $r) {
                foreach ($r as $c) {
                  foreach ($c as $cd) {
                    $sheets_name = PgdlSheet::where('id', $cd['pgdl_sheet_id'])->get();
                    // dd($sheets_name);
                    foreach ($sheets_name as $srwlc) {
                      if ($srwlc->name != 'I-LR' && $srwlc->name != 'I-CF') {
                        if ($srwlc->id == $cd['pgdl_sheet_id']) {
                          $data_log[$cd['row']]['sheet_name_akhir'] = $srwlc->name;
                        }

                        if ($cd['kolom'] == $setting_log->where('judul_kolom', 'Nomor PRK')->where('pgdl_sheet_name', $srwlc->name)->first()->kolom) {
                        $data_log[$cd['row']]['prk'] = $cd['value'];
                        $data_log[$cd['row']]['user_id'] = $user_id;
                        $data_log[$cd['row']]['pgdl_file_import_revisi_id'] = $data_fir;
                        $data_log[$cd['row']]['created_at'] = date('Y-m-d H:i:s');
                        }
                        elseif ($cd['kolom'] == $setting_log->where('judul_kolom', 'Identity PRK')->where('pgdl_sheet_name', $srwlc->name)->first()->kolom && $cd['kolom'] == $setting_log->where('judul_kolom', 'Deskripsi PRK')->where('pgdl_sheet_name', $srwlc->name)->first()->kolom) {
                          $data_log[$cd['row']]['identity_prk'] = $data_log[$cd['row']]['deskripsi_prk_akhir'] = $cd['value'];
                        }
                        elseif (($setting_log->where('judul_kolom', 'Beban')->where('pgdl_sheet_name', $srwlc->name)->first() && $cd['kolom'] == $setting_log->where('judul_kolom', 'Beban')->where('pgdl_sheet_name', $srwlc->name)->first()->kolom) || ($setting_log->where('judul_kolom', 'Anggaran Proyek')->where('pgdl_sheet_name', $srwlc->name)->first() && $cd['kolom'] == $setting_log->where('judul_kolom', 'Anggaran Proyek')->where('pgdl_sheet_name', $srwlc->name)->first()->kolom)) {
                          $data_log[$cd['row']]['beban_akhir'] = $cd['value'];
                        }
                        elseif (($setting_log->where('judul_kolom', 'Ijin Proses')->where('pgdl_sheet_name', $srwlc->name)->first() && $cd['kolom'] == $setting_log->where('judul_kolom', 'Ijin Proses')->where('pgdl_sheet_name', $srwlc->name)->first()->kolom) || ($setting_log->where('judul_kolom', 'Disburse')->where('pgdl_sheet_name', $srwlc->name)->first() && $cd['kolom'] == $setting_log->where('judul_kolom', 'Disburse')->where('pgdl_sheet_name', $srwlc->name)->first()->kolom)) {
                          $data_log[$cd['row']]['ijin_proses_akhir'] = $cd['value'];
                        }
                        elseif (($setting_log->where('judul_kolom', 'Cashflow')->where('pgdl_sheet_name', $srwlc->name)->first() && $cd['kolom'] == $setting_log->where('judul_kolom', 'Cashflow')->where('pgdl_sheet_name', $srwlc->name)->first()->kolom) || ($setting_log->where('judul_kolom', 'Anggaran Investasi')->where('pgdl_sheet_name', $srwlc->name)->first() && $cd['kolom'] == $setting_log->where('judul_kolom', 'Anggaran Investasi')->where('pgdl_sheet_name', $srwlc->name)->first()->kolom)) {
                            $data_log[$cd['row']]['cashflow_akhir'] = $cd['value'];
                        }
                        elseif ($cd['kolom'] == 'J') {
                          # code...
                          $data_log[$cd['row']]['cashflow_akhir_j'] = $cd['value'];
                        }
                        elseif ($cd['kolom'] == 'K') {
                          # code...
                          $data_log[$cd['row']]['cashflow_akhir_k'] = $cd['value'];
                        }
                      }
                    }
                  }
                }
              }
              // dd($data_log);
              foreach ($result_db as $rdb) {
                foreach ($rdb as $rd) {
                  foreach ($rd as $d) {
                    $sheets_name = PgdlSheet::where('id', $d['pgdl_sheet_id'])->get();
                    foreach ($sheets_name as $sr) {
                        if ($sr->name != 'I-LR' && $sr->name != 'I-CF') {
                          // code...
                          if ($sr->id == $d['pgdl_sheet_id']) {
                            $data_log[$d['row']]['sheet_name_akhir'] = $sr->name;
                          }

                          if ($d['kolom'] == $setting_log->where('judul_kolom', 'Nomor PRK')->where('pgdl_sheet_name', $sr->name)->first()->kolom) {
                            $data_log[$d['row']]['prk_awal'] = $d['value'];
                            $data_log[$d['row']]['user_id_awal'] = $user_id;
                            $data_log[$d['row']]['pgdl_file_import_revisi_id_awal'] = $data_fir;
                            $data_log[$d['row']]['created_at_awal'] = date('Y-m-d H:i:s');
                          }
                          elseif ($d['kolom'] == $setting_log->where('judul_kolom', 'Deskripsi PRK')->where('pgdl_sheet_name', $sr->name)->first()->kolom) {
                             $data_log[$d['row']]['deskripsi_prk_awal'] = $d['value'];
                          }
                          elseif (($setting_log->where('judul_kolom', 'Beban')->where('pgdl_sheet_name', $sr->name)->first() && $d['kolom'] == $setting_log->where('judul_kolom', 'Beban')->where('pgdl_sheet_name', $sr->name)->first()->kolom) || ($setting_log->where('judul_kolom', 'Anggaran Proyek')->where('pgdl_sheet_name', $sr->name)->first()
                          && $d['kolom'] == $setting_log->where('judul_kolom', 'Anggaran Proyek')->where('pgdl_sheet_name', $sr->name)->first()->kolom)) {
                             $data_log[$d['row']]['beban_awal'] = $d['value'];
                          }
                          elseif (($setting_log->where('judul_kolom', 'Ijin Proses')->where('pgdl_sheet_name', $sr->name)->first() && $d['kolom'] == $setting_log->where('judul_kolom', 'Ijin Proses')->where('pgdl_sheet_name', $sr->name)->first()->kolom) || ($setting_log->where('judul_kolom', 'Disburse')->where('pgdl_sheet_name', $sr->name)->first()
                          && $d['kolom'] == $setting_log->where('judul_kolom', 'Disburse')->where('pgdl_sheet_name', $sr->name)->first()->kolom)) {
                             $data_log[$d['row']]['ijin_proses_awal'] = $d['value'];
                          }
                          elseif (($setting_log->where('judul_kolom', 'Cashflow')->where('pgdl_sheet_name', $sr->name)->first() && $d['kolom'] == $setting_log->where('judul_kolom', 'Cashflow')->where('pgdl_sheet_name', $sr->name)->first()->kolom) || ($setting_log->where('judul_kolom', 'Anggaran Investasi')->where('pgdl_sheet_name', $sr->name)->first()
                            && $d['kolom'] == $setting_log->where('judul_kolom', 'Anggaran Investasi')->where('pgdl_sheet_name', $sr->name)->first()->kolom)) {
                              $data_log[$d['row']]['cashflow_awal'] = $d['value'];
                          }
                          elseif ($d['kolom'] == 'J') {
                            # code...
                            $data_log[$d['row']]['cashflow_awal_j'] = $d['value'];
                          }
                          elseif ($d['kolom'] == 'K') {
                            # code...
                            $data_log[$d['row']]['cashflow_awal_k'] = $d['value'];

                          }
                        }
                      }
                    }
                  }
                }
                // dd($data_log);
                if (!count($error)) {
                  foreach ($data_log as $dl) {
                    if ($dl['sheet_name_akhir'] == 'I-PEG' || $dl['sheet_name_akhir'] == 'I-ADM' || $dl['sheet_name_akhir'] == 'I-BIAYA USAHA LAINNYA' || $dl['sheet_name_akhir'] == 'I-DILUAR USAHA') {
                      if (array_key_exists('cashflow_awal_j', $dl) || array_key_exists('cashflow_awal_k', $dl)) {
                        # code...
                        $dl['cashflow_awal'] = $dl['cashflow_awal_j'] + $dl['cashflow_awal_k'];
                      }
                      if (array_key_exists('cashflow_akhir_j', $dl) || array_key_exists('cashflow_akhir_k', $dl)) {
                        # code...
                        $dl['cashflow_akhir'] = $dl['cashflow_akhir_j'] + $dl['cashflow_akhir_k'];
                      }
                    }

                  $diff = array_diff($dl, array_diff_assoc($dl, array_unique($dl)));
                  $pgdl_history_log = New PgdlHistoryLog;
                  if ($diff) {
                    $pgdl_history_log->prk = $dl['prk'];
                    $pgdl_history_log->identity_prk = $dl['identity_prk'];

                    if (array_key_exists('deskripsi_prk_awal', $dl)) {
                      $pgdl_history_log->deskripsi_prk_awal = $dl['deskripsi_prk_awal'];
                    }
                    if (array_key_exists('deskripsi_prk_akhir', $dl)) {
                      $pgdl_history_log->deskripsi_prk_akhir = $dl['deskripsi_prk_akhir'];
                    }
                    if (array_key_exists('beban_awal', $dl)) {
                      // if ($dl['beban_awal'] != $dl['beban_akhir']) {
                      $pgdl_history_log->beban_awal = $dl['beban_awal'];
                    }
                    if (array_key_exists('beban_akhir', $dl)) {
                      # code...
                      $pgdl_history_log->beban_akhir = $dl['beban_akhir'];
                    }
                    if (array_key_exists('cashflow_awal', $dl)) {
                      // if ($dl['cashflow_awal'] != $dl['cashflow_akhir']) {
                      $pgdl_history_log->cashflow_awal = $dl['cashflow_awal'];
                    }
                    if (array_key_exists('cashflow_akhir', $dl)) {
                      $pgdl_history_log->cashflow_akhir = $dl['cashflow_akhir'];
                    }
                    if (array_key_exists('ijin_proses_awal', $dl)) {
                      // if ($dl['ijin_proses_awal'] != $dl['ijin_proses_akhir']) {
                      $pgdl_history_log->ijin_proses_awal = $dl['ijin_proses_awal'];
                    }
                    if (array_key_exists('ijin_proses_akhir', $dl)) {
                      $pgdl_history_log->ijin_proses_akhir = $dl['ijin_proses_akhir'];
                    }
                    $pgdl_history_log->user_id = $dl['user_id'];
                    $pgdl_history_log->pgdl_file_import_revisi_id = $data_fir;
                    $pgdl_history_log->created_at = date('Y-m-d H:i:s');
                    // dump($pgdl_history_log);
                    if ($same_last_row == 0) {
                      # code...
                      $pgdl_history_log->deskripsi_prk_awal = '';
                      $pgdl_history_log->beban_awal = '';
                      $pgdl_history_log->cashflow_awal = '';
                      $pgdl_history_log->ijin_proses_awal = '';
                    }

                    $pgdl_history_log->save();
                  }
                }
                // die();
              }
            }
          } // Tutup kurung if $version->pgdl_template->jenis_id == '1' rumus
        // }); // Tutup kurung DB::transaction
      // } // tutup kurung foreach dibawah count error
        // dd('1');
        $fileimport->error = '';
        $fileimport->status_upload_id = '3'; //sukses
        $fileimport->save();
        // $request->session()->flash('success', 'Data berhasil di import!');
        $this->info('Data berhasil di import!');
        // return redirect(route('fileimport.show', ['version_id' => $version_id, 'id' => $id]));
    } else {
      // dd('2');

      if (count(array_unique($nama_sheet)) == count($sheet_request_wo_lr_cf)) {
        if ($no_ok != null) {
          # code...
          DB::transaction(function() use ($error, $id, $backup_data, $sheet_id) {

            PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $id)
            ->whereIn('pgdl_sheet_id', $sheet_id)
            ->delete();

            $sheet_chunk = array_chunk($backup_data, 1000);

            foreach ($sheet_chunk as $sc) {
              foreach ($sc as $sd) {
              # code...
                PGDLExcelDataRevisi::insert($sd);
              // dump($sd);
              }
            }

        });
        }
      }

      $this->error('Error: '. implode(', ', $error));
      $fileimport->error = implode('<br> ', $error);
      $fileimport->status_upload_id = '4'; //gagal
      $fileimport->save();
    }
  } // tutup function

  private function update_icf_ilr_rkau($version, $file_import, $backup_data, $sheet_id, $error, $file_import_rkau)
    {
        // dd('Masuk fungsi update RKAU I-LR I-CF');
        $user_id = session('user_id');
        // mendapatkan sheet I-LR dan I-CF yang akan diupdate
        $sheets_rkau = PgdlSheet::where(function($query) use ($file_import_rkau) {
            $query->where('pgdl_version_id', $file_import_rkau->pgdl_version_id)->where('name', 'I-LR');
        })->orWhere(function($query) use ($file_import_rkau) {
            $query->where('pgdl_version_id', $file_import_rkau->pgdl_version_id)->where('name', 'I-CF');
        })->get();
        if(!count($sheets_rkau) == 2) {
          $error[] = 'Sheet I-CF/I-LR Tidak Ditemukan!';
          return $error;
        }

        // melakukan back up data pada sheet I-LR dan I-CF
        $backup_data_rkau = [];
        // $array_data_ori = [];
        foreach ($sheets_rkau as $sheet) {
            $setting = PgdlSheetSetting::where('pgdl_sheet_id', $sheet->id)->get();
            $setting_anomali = $setting->where('sequence', '>', 0)->sortBy('sequence')->groupBy('sequence');
            // dd($setting_anomali);
            foreach ($setting_anomali as $row){
                foreach ($row as $value) {
                    if (!empty($value->query_value)) {
                        $rkau_data = PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $file_import_rkau->id)
                            ->where('pgdl_sheet_id', $value->pgdl_sheet_id)
                            ->where('row', $value->row)
                            ->where('kolom', $value->kolom)
                            ->where('lokasi_id', $file_import_rkau->lokasi_id)
                            ->first();
                        if(!$rkau_data) {
                            $error[] = 'Data RKAU Sheet ' . $value->pgdl_sheet->name . ' Kolom ' . $value->kolom . ' Row ' . $value->row . ' Tidak Ditemukan!';
                        } else {
                            $backup_data_rkau[] = [
                                'pgdl_file_import_revisi_id' => $file_import_rkau->id,
                                'pgdl_sheet_id' => $rkau_data->pgdl_sheet_id,
                                'lokasi_id' => $rkau_data->lokasi_id,
                                'kolom' => $rkau_data->kolom,
                                'row' => $rkau_data->row,
                                'value' => $rkau_data->value,
                                'created_by' => $user_id,
                                'created_at' => date('Y-m-d H:i:s')
                            ];
                        }
                    }
                }
            }
        }
        // dd($backup_data_rkau);
        if (count($error)) {
          return $error;
        }

        $sheet_data_anomali = [];
        // melakukan perulangan per sheet untuk menghitung data sheet rkau dan disimpan di sheet_data_anomali
        foreach ($sheets_rkau as $sheet) {
            $setting = PgdlSheetSetting::where('pgdl_sheet_id', $sheet->id)->get();
            $setting_anomali = $setting->where('sequence', '>', 0)->sortBy('sequence')->groupBy('sequence');
            foreach ($setting_anomali as $row){
                // dd($row);
                foreach ($row as $value) {
                    if (!empty($value->query_value)) {
                        // dd($file_import_rkau->id);
                        $query_str = $this->sql_replace_pgdl($value->query_value, $file_import_rkau->id, $value->pgdl_sheet_id, $value->row, $value->kolom);
                        // dd($value->query_value, $query_str);
                        try {
                            $query = DB::select($query_str);
                            $value_data = (!empty($query[0])) ? $query[0]->value : '';
                            $value_insert = (string)$value_data;
                            $sheet_data_anomali[] = [
                                'pgdl_file_import_revisi_id' => $file_import_rkau->id,
                                'pgdl_sheet_id' => $value->pgdl_sheet_id,
                                'kolom' => $value->kolom,
                                'row' => $value->row,
                                'lokasi_id' => $file_import_rkau->lokasi_id,
                                'value' => $value_insert,
                                'created_by' => $user_id,
                                'updated_by' => $user_id,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s')
                            ];
                        } catch (Exception $e) {
                            $error[] = 'Rumus RKAU Sheet ' . $value->pgdl_sheet->name . ' Kolom ' . $value->kolom . ' Row ' . $value->row . ' Tidak Dapat Dieksekusi!';
                        }
                    }
                }
            }
        }
        // dd($backup_data_rkau, $sheet_data_anomali);
        // dd('oke');
        $c_del = 0; $c_ins = 0;
        if (count($error)) {
            // mengembalikan data form 6_10 seperti semula
          DB::transaction(function() use ($file_import, $backup_data, $sheet_id) {
                // mengembalikan data form 6_10 seperti semula
                PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $file_import->id)
                    ->whereIn('pgdl_sheet_id', $sheet_id)
                    ->delete();
                $sheet_chunk = array_chunk($backup_data, 1000);
                foreach ($sheet_chunk as $chunk_data) {
                    PGDLExcelDataRevisi::insert($chunk_data);
                }
            });
        } else {
          // dd('Masuk');
            // melakukan hapus data RKAU yang akan diupdate
            foreach ($sheets_rkau as $sheet) {
                $setting = PgdlSheetSetting::where('pgdl_sheet_id', $sheet->id)->get();
                $setting_anomali = $setting->where('sequence', '>', 0)->sortBy('sequence')->groupBy('sequence');
                foreach ($setting_anomali as $row){
                    foreach ($row as $value) {
                        if (!empty($value->query_value)) { 
                            // menghapus data rkau yang memiliki rumus query
                            PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $file_import_rkau->id)
                                ->where('pgdl_sheet_id', $value->pgdl_sheet_id)
                                ->where('row', $value->row)
                                ->where('kolom', $value->kolom)
                                ->where('lokasi_id', $file_import_rkau->lokasi_id)
                                ->delete();
                            $c_del++;
                        }
                    }
                }
            }
            // dd($array_data_ori, $array_data, $array_result);
            // insert data rkau baru yang telah dihitung
            DB::transaction(function() use ($sheet_data_anomali) {
                //dipecah per 1000
                $sheet_chunk_rkau = array_chunk($sheet_data_anomali, 1000);
                foreach ($sheet_chunk_rkau as $chunk_data_rkau) {
                    PGDLExcelDataRevisi::insert($chunk_data_rkau);
                }
            });
        }
        // dd($c_del);
        return $error;
    }
} // Tutup class
