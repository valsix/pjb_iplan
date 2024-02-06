<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Entities\Distrik;
use App\Entities\ExcelData;
use App\Entities\PGDLExcelDataRevisi;
use App\Entities\Fase;
use App\Entities\FileImport;
use App\Entities\FileImportKetetapan;
use App\Entities\PGDLFileImportRevisi;
use App\Entities\PgdlHistoryLog;
use App\Entities\FileApproval;
use App\Entities\History;
use App\Entities\Jenis;
use App\Entities\Lokasi;
use App\Entities\Sheet;
use App\Entities\SheetSetting;
use App\Entities\StrategiBisnis;
use App\Entities\Template;
use App\Entities\Version;
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
use App\Entities\PgdlVersion;
use App\Entities\PgdlTemplate;
use App\Entities\PgdlSheet;
use App\Entities\PgdlSheetSetting;
use App\Entities\PgdlReportDashboardSetting;

class ImportAddExcelPgdl extends Command
{
    use ValidationExcelTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:insert_add_excel_pgdl {id} {user} {tahun} {sheet*}';

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
        // dd($id);
        $sheet_request = $this->argument('sheet');
        $user_id = $this->argument('user');
        $tahun = $this->argument('tahun');
        // dd($tahun);
        // dd($sheet_request);

        $fileimport = PGDLFileImportRevisi::find($id);
        $fileimport->status_upload_id = '2'; //sedang diupload
        $fileimport->save();

        $version_id = $fileimport->pgdl_version_id;
        $version_id_nonpgdl = $fileimport->version_id;
        // dd($version_id_nonpgdl);

        $version = PgdlVersion::with('pgdl_template')->where('id', $version_id)->first();
        // dd($version);
        $sheet_md = PgdlSheet::where('pgdl_version_id', $version_id)->get();
        // dd($sheet_md);
        $version_nonpgdl = Version::with('template')->where('id', $version_id_nonpgdl)->first();
        // dd($version_nonpgdl);
        $sheet_nonpgdl = Sheet::where('version_id', $version_id_nonpgdl)->first();
        // dd($sheet_nonpgdl);
        $sheet = PgdlSheet::all();

        $jenis_id = DB::table('pgdl_file_imports_revisi')
            ->join('pgdl_templates', 'pgdl_templates.id', '=', 'pgdl_file_imports_revisi.pgdl_template_id')
            ->where('pgdl_file_imports_revisi.id', $id)
            ->first()->jenis_id;

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

        $strategi_bisnis = StrategiBisnis::all();
        $distrik = Distrik::all();
        $lokasi = Lokasi::all();

        for ($i=0; $i < count($sheet_request); $i++) {
            $kolom_yg_digunakan = DB::table('pgdl_report_dashboard_settings')
                ->where('pgdl_report_dashboard_page_id', 8)
                ->where('tahun', $tahun)
                ->where('jenis_id', $jenis_id)
                ->where('pgdl_sheet_name', $sheet_request)
                ->orderBy('sequence', 'asc')->get();
        }

        $backup_data = [];
        $sheet_id = [];
        // dd($kolom_yg_digunakan);
        if (count($kolom_yg_digunakan)) {
            foreach ($kolom_yg_digunakan as $k) {
                if ($k->judul_kolom == 'Nomor PRK') {
                    $nomor_prk = $k->kolom;
                }
            // dump($k);
            }
            // dd($nomor_prk);
            $reader = ReaderFactory::create(Type::XLSX);

            $reader->open(base_path('public/temp/temp.xlsx'));

            $sheet_use = [];
            $setting = [];
            foreach ($sheet_md as $row){
                if(in_array($row->name, $sheet_request)){
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

            $fail_excel = PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $id)
                ->whereIn('pgdl_sheet_id', $sheet_id)
                ->get();

            foreach ($fail_excel as $row){
                $backup_data[] = [
                'pgdl_file_import_revisi_id' => $row->id,
                'pgdl_sheet_id' => $row->pgdl_sheet_id,
                'lokasi_id' => $row->lokasi_id,
                'kolom' => $row->kolom,
                'row' => $row->row,
                'value' => $row->value,
                ];
            }

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
                                                    $error[] = 'Data Sheet ' . $sheet_setting->sheet->name . ' ' . $sheet_setting->kolom . $i . ' Tidak Sesuai!';
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
                                            'sheet_id' => $sheet_nonpgdl->id,
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
                                            $error[] = 'Data Sheet ' . $sheet_setting->sheet->name . ' ' . $sheet_setting->kolom . $i . ' Tidak Boleh Duplikasi!';
                                        } else {
                                            $error[] = 'Data Sheet ' . $sheet_setting->sheet->name . ' ' . $sheet_setting->kolom . $i . ' Tidak Sesuai!';
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
                                        'sheet_id' => $sheet_nonpgdl->id,
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

            $jumlah = 0;
            $data_fir = $id;

            $jml_prk = 1;
            foreach ($sheet_data as $sd) {
                if ($sd['kolom'] == $nomor_prk) {
                    $no_prk[$jml_prk] = $sd['value'];
                    $jml_prk++;
                }
            }
            // dd($no_prk);
            for($n=1;$n<$jml_prk;$n++) {
                $unique_value[$n] = PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $data_fir)->where('value', $no_prk[$n])->get();
            }
            // dd($unique_value, $no_prk);
            if ($no_prk[1] == null) {
                // dump('1');
                $error[] = 'Terdapat No PRK yang kosong di Data Excel';
            } else {
                if ($unique_value[1]->isEmpty()) {
                    // dump('2');
                    // die();
                    $jml_row = 1;
                    foreach ($sheet_data as $sd) {
                        $no_row[$jml_row] = $sd['row'];
                        $jml_row++;
                    }

                    $last_data = PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $data_fir)->orderBy('row', 'desc')->first()->row;
                    // dd($last_data);

                    $start = '13';
                    // Mengurutkan data yang akan di insert, mulai dari baris terakhir+1
                    for ($i=1; $i <= count($no_row); $i++) {
                        $no_row[$i] = $last_data + ($no_row[$i] - $start + 1);
                        // dump($no_row[$i], $i);
                    }
                } else {
                    $k=1;
                    foreach ($unique_value as $uv) {
                        foreach ($uv as $key_unique) {
                            $error[$k] = 'No PRK Tidak Boleh Duplikasi! : ' . $key_unique->value ;
                            $k++;
                        }
                    }
                }
            }
        } else {
            $error[] = 'Setting Report Dashboard Histroy Log untuk tahun '.$tahun.' belum dibuat!';
        }

        $setting_merge = collect();
        foreach ($setting as $row) {
            $setting_merge = $setting_merge->merge($row);
        }
        // dd($error);
        foreach ($setting_merge->where('sequence', '>', 0)->sortBy('sequence')->groupBy('sequence') as $row) {
            $sheet_data = [];
            foreach ($row as $value) {
                if (in_array($version->pgdl_template->jenis_id, Jenis::FORM_6_10)) {
                    for ($i = 13; $i < $limit; $i++) {
                        if (!empty($value->query_value)) {
                            $query_str = $this->sql_replace_pgdl($value->query_value, $id, $value->pgdl_sheet_id, $i, $value->kolom);
                            $query = DB::select($query_str);
                            $value_data = (!empty($query[0])) ? $query[0]->value : '';
                            if (!$this->validation_pgdl($value_data, $value->validation, $value->validation_type, $sheet_data, $value->pgdl_sheet_id, $value->kolom)) {
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
                            'sheet_id' => $value->sheet_id,
                            'pgdl_sheet_id' => $value->pgdl_sheet_id,
                            'lokasi_id' => $lokasi_value[$value->pgdl_sheet->name],
                            'kolom' => $value->kolom,
                            'row' => $value->row,
                            'value' => $value_insert,
                        ];
                    }
                }
            }
              // foreach ($sheet_data as $sd) {
              //     PGDLExcelDataRevisi::insert($sd);
              //     // dump($sd);
              // }
        }

        $data_log = [];
        // DB::transaction(function () use ($data_log, $error, $sheet_data, $id, $jenis_id, $tahun, $sheet_request, $no_row, $data_fir, $user_id, $sheet_id, $backup_data, $version, $fileimport) {
            //dipecah per 10000
        $setting_log = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', 8)
            ->where('jenis_id', $jenis_id)
            ->where('tahun', $tahun)
            ->whereIn('pgdl_sheet_name', $sheet_request)
            ->get();

        // $sheet_chunk = array_chunk($sheet_data, 10000);
        $j = 1;
        foreach ($sheet_data as $cd) {
          // foreach ($chunk_data as $cd) {
            if ($cd['kolom'] == $setting_log->where('judul_kolom', 'Nomor PRK')->first()->kolom) {
              $data_log[$cd['row']]['prk'] = $cd['value'];
              $data_log[$cd['row']]['user_id'] = $user_id;
              $data_log[$cd['row']]['pgdl_file_import_revisi_id'] = $data_fir;
              $data_log[$cd['row']]['created_at'] = date('Y-m-d H:i:s');
            } if ($cd['kolom'] == $setting_log->where('judul_kolom', 'Identity PRK')->first()->kolom && $cd['kolom'] == $setting_log->where('judul_kolom', 'Deskripsi PRK')->first()->kolom) {
              $data_log[$cd['row']]['identity_prk'] = $data_log[$cd['row']]['deskripsi_prk_akhir'] = $cd['value'];
            } if (($setting_log->where('judul_kolom', 'Beban')->first() && $cd['kolom'] == $setting_log->where('judul_kolom', 'Beban')->first()->kolom) || ($setting_log->where('judul_kolom', 'Anggaran Proyek')->first()
            && $cd['kolom'] == $setting_log->where('judul_kolom', 'Anggaran Proyek')->first()->kolom)) {
              $data_log[$cd['row']]['beban_akhir'] = $cd['value'];
            } if (($setting_log->where('judul_kolom', 'Cashflow')->first() && $cd['kolom'] == $setting_log->where('judul_kolom', 'Cashflow')->first()->kolom) || ($setting_log->where('judul_kolom', 'Anggaran Investasi')->first()
            && $cd['kolom'] == $setting_log->where('judul_kolom', 'Anggaran Investasi')->first()->kolom)) {
              $data_log[$cd['row']]['cashflow_akhir'] = $cd['value'];
            } if (($setting_log->where('judul_kolom', 'Ijin Proses')->first() && $cd['kolom'] == $setting_log->where('judul_kolom', 'Ijin Proses')->first()->kolom) || ($setting_log->where('judul_kolom', 'Disburse')->first()
            && $cd['kolom'] == $setting_log->where('judul_kolom', 'Disburse')->first()->kolom)) {
              $data_log[$cd['row']]['ijin_proses_akhir'] = $cd['value'];
            }
            $cd['pgdl_file_import_revisi_id'] = $data_fir;
            // dd('1');
            if (!count($error)) {
                $cd['row'] = $no_row[$j];

                PGDLExcelDataRevisi::insert($cd);
                // dump($cd);
            }
            $j++;
        }
            // die();
            // }

        // });

        // CODE UPDATE OTOMATIS I-CF dan I-LR
        if (!count($error) && $version->pgdl_template->jenis_id != '1') {
            $error = $this->update_icf_ilr_rkau($version, $fileimport, $backup_data, $sheet_id, $error, $file_import_rkau);
            // dd($error);
        }
        // END CODE UPDATE OTOMATIS I-CF dan I-LR
        // dd($data_log);
        if (!count($error)) {
            PgdlHistoryLog::insert($data_log);
            // dd($data_log);
        }

        // dd($error);
        if(count($error)){

            DB::transaction(function() use ($jml_prk, $data_fir, $no_prk) {
                for($n=1;$n<$jml_prk;$n++) {
             //       PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $data_fir)->where('value', $no_prk[$n])->delete();
                }
            // dd($unique_value);
            //   PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $id)->whereIn('pgdl_sheet_id', $sheet_id)->delete();

            //     $sheet_chunk = array_chunk($fail_data, 10000);

            //     foreach ($sheet_chunk as $chunk_data) {
            //       PGDLExcelDataRevisi::insert($chunk_data);
            //     }
            });

            // $request->session()->flash('error', $error);
            $this->error('Error: '. implode(', ', $error));
            $fileimport->error = implode('<br> ', $error);
            $fileimport->status_upload_id = '4'; //gagal
            $fileimport->save();
            // return redirect(route('fileimport.show', ['version_id' => $version_id, 'id' => $id]));
        } else {
            $fileimport->error = '';
            $fileimport->status_upload_id = '3'; //sukses
            $fileimport->save();
            // $request->session()->flash('success', 'Data berhasil di import!');
            $this->info('Data berhasil di import!');
        }
    }

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
        // if(!count($sheets_rkau) == 2) {
        //   // $error[] = 'Sheet I-CF/I-LR Tidak Ditemukan!';
        //   return $error;
        // }

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
}
