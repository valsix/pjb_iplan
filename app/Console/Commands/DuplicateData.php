<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Entities\FileImport;
use App\Entities\Template;
use App\Entities\ExcelData;
use App\Entities\FileImportKetetapan;
use App\Entities\PGDLFileImportRevisi;
use App\Entities\PgdlTemplate;
use App\Entities\PgdlVersion;
use App\Entities\Version;
use App\Entities\Sheet;
use App\Entities\SheetSetting;
use App\Entities\PgdlSheet;
use App\Entities\PgdlSheetSetting;
use App\Entities\ExcelDataKetetapan;
use App\Entities\PgdlExcelDataRevisi;
use DB;
use File;

class DuplicateData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'duplicate:data {data_fifk}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Duplikat data Ketetapan ke Pengendalian';

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
        $data_fifk = $this->argument('data_fifk');
        $data_fik = FileImport::where('id', $data_fifk)->first();
        // dd($data_fik->form6_rutin_file_import_id);
        $data_id = array( $data_fik->form6_rutin_file_import_id, $data_fik->form6_reimburse_file_import_id, $data_fik->form10_pln_file_import_id, $data_fik->form10_pu_file_import_id, $data_fik->form10_penguatankit_file_import_id, $data_fik->form_bahan_bakar_file_import_id, $data_fik->form_penyusutan_file_import_id
        );

        // $data_id = ['form6_rutin_file_import_id' => $data_fik->form6_rutin_file_import_id,
        //   'form6_reimburse_file_import_id' => $data_fik->form6_reimburse_file_import_id,
        //   'form10_pln_file_import_id' => $data_fik->form10_pln_file_import_id,
        //   'form10_pu_file_import_id' => $data_fik->form10_pu_file_import_id,
        //   'form10_penguatankit_file_import_id' => $data_fik->form10_penguatankit_file_import_id,
        //   'form_bahan_bakar_file_import_id' => $data_fik->form_bahan_bakar_file_import_id,
        //   'form_penyusutan_file_import_id' => $data_fik->form_penyusutan_file_import_id
        // ];

        for ($i=0; $i < count($data_id); $i++) { 
          # code...
          $data_fik_id[] = FileImportKetetapan::select('id')->where('file_import_id', $data_id[$i])->first();
        }
        // dd($data_fik_id);
        foreach ($data_fik_id as $dfi) {
          # code...s
          if ($dfi['id'] == null) {
            # code...
              $dfi['id'] = 0;
          }

          $d[] =$dfi['id'];
          // }
          
        }
        // dd($d);
        
        $template_data = Template::where('id', $data_fik->template_id)->first();
        // dd($template_data->jenis_id);
        // dd($template_data->file);
        $version_id = Version::where('template_id', $template_data->id)->first();
        // dd($version_id->id);
        $filename = basename($template_data->file);
        // dd($tes );

        $pgdl_template = PgdlTemplate::where('template_id', $data_fik->template_id)->first();
        // dd($pgdl_template);

        if (empty($pgdl_template)) {
        // dd('data'); 
          $template = New PgdlTemplate();

          $template->template_id = $template_data->id;
          $template->tahun = $template_data->tahun;
          $template->jenis_id = $template_data->jenis_id;
          $template->active = $template_data->active;
          // dd($pgdl_template);
        $pgdl_version = PgdlVersion::where('pgdl_template_id', $template->id)->first();

        if (empty($pgdl_version)) {
          # code...
          $version = New PgdlVersion();

          $transaction = DB::transaction(function() use ($template, $version, $filename, $template_data){
            $template->save();

            if (file_exists($template_data->file)) {
              // code...
              $destinationPath = 'pgdl_files/'.$template->id;
              $template->file = $destinationPath.'/'.$filename;
              $template->save();

              $destinationPath_pgdl = 'pgdl_files/'.$template->id;
              File::makeDirectory($destinationPath_pgdl, $mode = 0777, true, true);

              $copy = copy($template_data->file, $destinationPath_pgdl.'/'.$filename);
              $version->file = $destinationPath.'/'.$filename;

            }

            $version->versi = '1';
            $version->active = 1;

            $version->pgdl_template_id = $template->id;
            $version->save();
          }); 
        }
        // }
        
        // dd('2');
        // dd($template->templates->jenis_id);
        // dd($template->id, $version->id);
        // $data_sheet = PgdlSheet::where('pgdl_version_id', $version_id->id)->first();
        // dd($data_sheet);
        $sheet = Sheet::where('version_id', $version_id->id)->get();
        // dd($sheet);
        
        // die();
        // if (empty($data_sheet)) {
          # code...
          foreach ($sheet as $key) {
          # code...
            $pgdlsheet = New PgdlSheet();
            // dd($version->id);
            $pgdlsheet->pgdl_version_id = $version->id;
            $pgdlsheet->name = $key->name;
            // dump($pgdlsheet);
            $pgdlsheet->save();
          }
          // die();   
        // }
        // dd($data_sheet->id);

        foreach ($sheet as $value) {
          # code...
            $id_sheet[] = $value->id;
            // dump($id_sheet);
          }   
          // dd($id_sheet);
        for ($k=0; $k < count($sheet); $k++) { 
          # code...
          $setting[] = SheetSetting::where('sheet_id', $id_sheet[$k])->get();
        }
        // dd($setting);
       
      // dd($data_s);
      // $data_setting = SheetSetting::where('sheet_id', '66')->get();
      // dd($pgdlsheet->id);
        $data_setting = PgdlSheetSetting::where('pgdl_sheet_id', $pgdlsheet->id)->first();

        $pgdl_sheet = PgdlSheet::select('id')->where('pgdl_version_id', $version->id)->get();
        // dd($data_setting);
        foreach ($pgdl_sheet as $pgdl_s) {
          # code...
          $pgdl_sheet_id[] = $pgdl_s->id;
          // dump($pgdl_s);
        }
        // dd($pgdl_sheet_id);
        if (empty($data_setting)) {
            # code...

          foreach ($setting as $s) {
          # code...
            foreach ($s as $ds) {
            # code...
              $pgdlsheet_setting = New PgdlSheetSetting();

              $pgdlsheet_setting->pgdl_sheet_id = $ds->sheet_id;
              $pgdlsheet_setting->kolom = $ds->kolom;
              $pgdlsheet_setting->row = $ds->row;
              $pgdlsheet_setting->validation_type = $ds->validation_type;
              $pgdlsheet_setting->color = $ds->color;
              $pgdlsheet_setting->validation = $ds->validation;
              $pgdlsheet_setting->query_value = $ds->query_value;
              $pgdlsheet_setting->sequence = $ds->sequence;
              $pgdlsheet_setting->editable = $ds->editable;
              // dump($pgdlsheet_setting);

              $pgdlsheet_setting->save();
              // dump($ds->sheet_id);
          }
        }

        for ($i=0; $i < count($pgdl_sheet_id) ; $i++) { 
          # code...
          PgdlSheetSetting::where('pgdl_sheet_id', $id_sheet[$i])
          ->update(['pgdl_sheet_id' => $pgdl_sheet_id[$i]]);
        }
      }
    }

      // dd('1');
      // Awal Proses Duplicate Data
      // dd($data_fik->template_id);
      $pgdl_template = PgdlTemplate::where('template_id', $data_fik->template_id)->first();
      // dd($pgdl_template);

      $pgdl_version = PgdlVersion::where('pgdl_template_id', $pgdl_template->id)->first();

      $pgdl_sheet = PgdlSheet::select('id')->where('pgdl_version_id', $pgdl_version->id)->get();

      foreach ($pgdl_sheet as $ps) {
        # code...
        $data_ps[] = $ps->id;
      }
      // dd($data_ps);

      // Insert data file_import ke tabel file_imports_ketetapan
      DB::table('file_imports_ketetapan')->insert(
        ['file_import_id' => $data_fik->id,
        'template_id' => $data_fik->template_id,
        'version_id' => $data_fik->version_id,
        'pgdl_template_id' => $pgdl_template->id,
        'pgdl_version_id' => $pgdl_version->id,
        'fase_id' => $data_fik->fase_id,
        'tahun' => $data_fik->tahun,
        'file' => $data_fik->file,
        'status_upload_id' => $data_fik->status_upload_id,
        'error' => $data_fik->error,
        'draft_versi' => $data_fik->draft_versi,
        'form6_rutin_file_import_ketetapan_id' => $d[0],
        'form6_reimburse_file_import_ketetapan_id' => $d[1],
        'form10_pln_file_import_ketetapan_id' => $d[2],
        'form10_pu_file_import_ketetapan_id' => $d[3],
        'form10_penguatankit_file_import_ketetapan_id' => $d[4],
        'form_bahan_bakar_file_import_ketetapan_id' => $d[5],
        'form_penyusutan_file_import_ketetapan_id' => $d[6],
        'created_at' => $data_fik->created_at,
        'updated_at' => $data_fik->updated_at,
        'distrik_id' => $data_fik->distrik_id,
        'lokasi_id' => $data_fik->lokasi_id,
        'name' => $data_fik->name,
        'created_by' => $data_fik->created_by,
        'updated_by' => $data_fik->updated_by,
        'uploaded_by' => $data_fik->uploaded_by,
        ]
      );
      // dd('1');
      // Mengambil data file_import_ketetapan untuk di insert ke tabel pgdl_file_imports_revisi
      $file_import_ketetapan = FileImportKetetapan::where('file_import_id', $data_fifk)->first();
      // dd( $file_import_ketetapan);
      $data_ketetapan_id = array( $file_import_ketetapan->form6_rutin_file_import_ketetapan_id, $file_import_ketetapan->form6_reimburse_file_import_ketetapan_id, $file_import_ketetapan->form10_pln_file_import_ketetapan_id, $file_import_ketetapan->form10_pu_file_import_ketetapan_id, $file_import_ketetapan->form10_penguatankit_file_import_ketetapan_id, $file_import_ketetapan->form_bahan_bakar_file_import_ketetapan_id, $file_import_ketetapan->form_penyusutan_file_import_ketetapan_id
        );

      // dd($data_ketetapan_id);

        for ($i=0; $i < count($data_id); $i++) { 
          # code...
          $data_fik_ketetapan_id[] = PGDLFileImportRevisi::select('id')->where('file_import_ketetapan_id', $data_ketetapan_id[$i])->first();
        }
        // dd($data_fik_ketetapan_id);
        foreach ($data_fik_ketetapan_id as $dfki) {
          # code...s
          if ($dfki['id'] == null) {
            # code...
            $dfki['id'] = 0;
          }
          
          $dki[] = $dfki['id'];
        }
        // dd($dki);

      // Insert data ke tabel pgdl_file_imports_revisi (copy dari file_import_keteatapan)
      DB::table('pgdl_file_imports_revisi')->insert(
        ['file_import_ketetapan_id' => $file_import_ketetapan->id,
        'template_id' => $file_import_ketetapan->template_id,
        'version_id' => $file_import_ketetapan->version_id,
        'pgdl_template_id' => $pgdl_template->id,
        'pgdl_version_id' => $pgdl_version->id,
        'fase_id' => $file_import_ketetapan->fase_id,
        'tahun' => $file_import_ketetapan->tahun,
        'file' => $file_import_ketetapan->file,
        'status_upload_id' => $file_import_ketetapan->status_upload_id,
        'error' => $file_import_ketetapan->error,
        'draft_versi' => $file_import_ketetapan->draft_versi,
        'form6_rutin_pgdl_file_import_revisi_id' => $dki[0],
        'form6_reimburse_pgdl_file_import_revisi_id' => $dki[1],
        'form10_pln_pgdl_file_import_revisi_id' => $dki[2],
        'form10_pu_pgdl_file_import_revisi_id' => $dki[3],
        'form10_penguatankit_pgdl_file_import_revisi_id' => $dki[4],
        'form_bahan_bakar_pgdl_file_import_revisi_id' => $dki[5],
        'form_penyusutan_pgdl_file_import_revisi_id' => $dki[6],
        'created_at' => $file_import_ketetapan->created_at,
        'updated_at' => $file_import_ketetapan->updated_at,
        'distrik_id' => $file_import_ketetapan->distrik_id,
        'lokasi_id' => $file_import_ketetapan->lokasi_id,
        'name' => $file_import_ketetapan->name,
        'created_by' => $file_import_ketetapan->created_by,
        'updated_by' => $file_import_ketetapan->updated_by,
        'uploaded_by' => $file_import_ketetapan->uploaded_by,
        ]
      );
      // dd('1');
      // Mengambil data dari tabel excel_datas dan di insert ke tabel excel_datas_ketetapan, di tambah id dari file_import_ketetapan
      $excel_data_ketetapan = ExcelData::where('file_import_id', $data_fifk)->get();
      foreach ($excel_data_ketetapan as $edk) {
        $data_edk = $edk;
        $data_sheet_id[] = $data_edk->sheet_id;
          // dump($data_edk);
        DB::table('excel_datas_ketetapan')->insert(
          ['file_import_ketetapan_id' => $file_import_ketetapan->id,
          'sheet_id' => $data_edk->sheet_id,
          'lokasi_id' => $data_edk->lokasi_id,
          'kolom' => $data_edk->kolom,
          'row' => $data_edk->row,
          'value' => $data_edk->value,
          'created_by' => $data_edk->created_by,
          'updated_by' => $data_edk->updated_by,
          'created_at' => $data_edk->created_at,
         // 'updated_at' => $data_edk->updated_at,
          ]
        );
      }
      // dd($data_sheet_id);
      $pgdl_file_imports_revisi_id = PGDLFileImportRevisi::where('file_import_ketetapan_id', $file_import_ketetapan->id)->get();

      foreach ($pgdl_file_imports_revisi_id as $pdiri) {
        $data_pdiri = $pdiri;
          // dump ($data_pdiri->id);
      }

      $data_unique = array_unique($data_sheet_id);
      $data_reset = array_merge($data_unique);

      // dd( $data_reset);
      for ($o=0; $o < count($data_reset) ; $o++) { 
        # code...
        $sheet_name[$data_reset[$o]] = Sheet::where('id', $data_reset[$o])->first()->name;
      }

      // dd($sheet_name);
     
      // for ($h=0; $h < count($sheet_name) ; $h++) { 
        # code...
      foreach ($sheet_name as $key => $value) {
        # code...
        $pgdl_sheet_id_data[$key] = DB::table('pgdl_sheets')->select('pgdl_sheets.id')
        ->join('pgdl_versions', 'pgdl_sheets.pgdl_version_id', '=', 'pgdl_versions.id')
        ->join('pgdl_templates', 'pgdl_templates.id', '=', 'pgdl_versions.pgdl_template_id')
        ->where('pgdl_sheets.name', $value)
        ->where('pgdl_templates.jenis_id', $pgdl_template->jenis_id)
        ->where('pgdl_templates.tahun', $pgdl_template->tahun)
        ->first();
      }
      // }
      // select ps.id from pgdl_sheets ps join pgdl_versions pv on ps.pgdl_version_id = pv.id
      // join pgdl_templates pt on pt.id = pv.pgdl_template_id
      // join templates t on t.id = pt.template_id
      // join versions v on v.template_id = t.id
      // join sheets s on s.version_id = v.id
      // where s.id = 18
      // die();
      // dd($pgdl_file_imports_revisi_id);

      $excel_data_ketetapan = ExcelData::where('file_import_id', $data_fifk)->get();
      foreach ($excel_data_ketetapan as $edk) {
          $data_edk = $edk;
          foreach ($pgdl_sheet_id_data as $keys => $valu) {
            # code...
            if ($data_edk->sheet_id == $keys) {
              # code...
               DB::table('pgdl_excel_datas_revisi')->insert(
                ['pgdl_file_import_revisi_id' => $data_pdiri->id,
                'sheet_id' => $data_edk->sheet_id,
                // 'pgdl_sheet_id' => $data_edk->sheet_id,
                'pgdl_sheet_id' => $valu->id,
                'lokasi_id' => $data_edk->lokasi_id,
                'kolom' => $data_edk->kolom,
                'row' => $data_edk->row,
                'value' => $data_edk->value,
                'created_by' => $data_edk->created_by,
                'updated_by' => $data_edk->updated_by,
                'created_at' => $data_edk->created_at,
                'updated_at' => $data_edk->updated_at,
                ]
              );
            }
          }
          // $data_sheet_id[] = $data_edk->sheet_id;
          // dump($data_edk);
          // dump($ds->sheet_id);
      }
      // dd($data_sheet_id);

      
      // dd($data_reset,$data_ps);
      // if ($template_data->jenis_id == 1) {
      //   # code...
      //   $data_diff = array_diff($data_ps, $data_reset);
      //   dd($data_diff, $data_ps);
      //   foreach ($data_ps as $key => $dps) {
      //     # code...
      //     foreach ($data_diff as $value) {
      //       # code...
      //       if ($dps == $value) {
      //       # code...
      //         unset($data_ps[$key]);
      //       }
      //     }
      //   }
      //   // $data_ps = array_slice(array, offset);
      //   // dd( $data_diff , $data_reset, $data_ps);
      //   // dd($data_reset, $data_ps);
      // }
      // dd($data_ps, $data_reset);
      // dd('1');
      // for ($i=0; $i < count($data_ps) ; $i++) { 
      //     # code...
      //     PgdlExcelDataRevisi::where('sheet_id', $data_reset[$i])
      //     ->update(['pgdl_sheet_id' => $data_ps[$i]]);
      //   }
      // die();

      // End Proccess Copy Data di Excel Data Perencanaan ke Pengendalian

      //Proses copy setting perencanaan ke pengendalian
    }
}
