<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Entities\Distrik;
use App\Entities\ExcelData;
use App\Entities\Fase;
use App\Entities\FileImport;
use App\Entities\FileApproval;
use App\Entities\History;
use App\Entities\Jenis;
use App\Entities\Lokasi;
use App\Entities\Sheet;
use App\Entities\SheetSetting;
use App\Entities\StrategiBisnis;
use App\Entities\Template;
use App\Entities\Version;
use App\Entities\Role;
use App\Entities\User;
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

class ImportData extends Command
{
    use ValidationExcelTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:insert {id} {sheet*}';

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
        $user_id = session('user_id');
        $user_user = User::find($user_id);
        // dd($user_user);

        $role_id = session('role_id');
        $role_user = Role::find($role_id);
        // dd($role_user->is_kantor_pusat);

        $id = $this->argument('id');

        $sheet_request = $this->argument('sheet');

        $fileimport = FileImport::find($id);
        $fileimport->status_upload_id = '2'; //sedang diupload
        $fileimport->save();

        $version_id = $fileimport->version_id;

        $version = Version::with('template')->where('id', $version_id)->first();

        $sheet_md = Sheet::where('version_id', $version_id)->get();

        $strategi_bisnis = StrategiBisnis::all();
        $distrik = Distrik::all();
        $lokasi = Lokasi::all();

        $reader = ReaderFactory::create(Type::XLSX);

        $reader->open(base_path('public'.$fileimport->file));

        $sheet_use = [];
        $sheet_id = [];
        $setting = [];
        foreach ($sheet_md as $row){
            if(in_array($row->name, $sheet_request)){
                $sheet_use[] = $row->name;
                $sheet_id[] = $row->id;
                if(in_array($version->template->jenis_id, Jenis::FORM_6_10)){
                    $setting[$row->name] = SheetSetting::with('sheet')->where('sheet_id', $row->id)
                        ->orderBy('kolom', 'asc')
                        ->get();
                } else {
                    $setting[$row->name] = SheetSetting::with('sheet')->where('sheet_id', $row->id)
                        ->orderBy('row', 'asc')
                        ->orderBy('kolom', 'asc')
                        ->get();
                }
            }
        }

        $fail_excel = ExcelData::whereIn('sheet_id', $sheet_id)->get();

        $fail_data = [];
        foreach ($fail_excel as $row){
            $fail_data[] = [
                // 'file_import_id' => $row->id,
                'file_import_id' => $row->file_import_id,
                'sheet_id' => $row->sheet_id,
                'kolom' => $row->kolom,
                'row' => $row->row,
                'value' => $row->value,
            ];
        }

        $sheet_data = [];
        $error = [];
        $limit = 12;
        $lokasi_value = [];
        foreach ($reader->getSheetIterator() as $sheet) {
            if (in_array($sheet->getName(), $sheet_use)) {
                $i = 1;
                $lokasi_value[$sheet->getName()] = null;
                if(in_array($version->template->jenis_id, Jenis::FORM_6_10)){
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
                    if(in_array($version->template->jenis_id, Jenis::FORM_6_10) && $i > $limit ){
                        break;
                    }
                    foreach ($row as $row2) {
                        if(in_array($version->template->jenis_id, Jenis::FORM_6_10)){
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
                if(!in_array($version->template->jenis_id, Jenis::FORM_6_10)){
                    // if(!$lokasi_value[$sheet->getName()]){
                    //     $error[] = 'Data Sheet '.$sheet->getName().' Lokasi Tidak Sesuai!';
                    //     continue;
                    // }

                    $distrik_cek = Lokasi::select('distrik_id')->where('id', $lokasi_value[$sheet->getName()])->first();

                    if ($role_user->is_kantor_pusat) {
                        if(!$lokasi_value[$sheet->getName()]){
                            $error[] = 'Data Sheet '.$sheet->getName().' Lokasi Tidak Sesuai!';
                            continue;
                        }
                    } else {
                        if(!$lokasi_value[$sheet->getName()] || $distrik_cek->distrik_id != $user_user->distrik_id) {
                            $error[] = 'Data Sheet '.$sheet->getName().' Lokasi Tidak Sesuai!';
                            continue;
                        }
                    }
                }
                if(in_array($version->template->jenis_id, Jenis::FORM_6_10)){
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
                    if(in_array($version->template->jenis_id, Jenis::FORM_6_10) && $i > $limit ){
                        break;
                    }
                    if(in_array($version->template->jenis_id, Jenis::FORM_6_10)){
                        if($i >= 13){                            
                          $distrik_cek = Lokasi::select('distrik_id')->where('id', $lokasi_value[$sheet->getName()][$i])->first();

                          if ($role_user->is_kantor_pusat) {
                            if(!$lokasi_value[$sheet->getName()][$i]) {
                                $error[] = 'Data Row '.$i.' Lokasi Tidak Sesuai!';
                                $i++;
                                continue;
                            }
                          } else {
                            if(!$lokasi_value[$sheet->getName()][$i] || $distrik_cek->distrik_id != $user_user->distrik_id) {
                                $error[] = 'Data Row '.$i.' Lokasi Tidak Sesuai!';
                                $i++;
                                continue;
                            }
                          }

                        }
                    }
                    foreach ($row as $row2) {
                        if(in_array($version->template->jenis_id, Jenis::FORM_6_10)){
                            if($i >= 13) {
                                if ($sheet_setting = ($setting[$sheet->getName()])->where('kolom', $j)->where('sequence', 0)->first()) {
                                    if (!$this->validation($row2, $sheet_setting->validation, $sheet_setting->validation_type, $sheet_data, $sheet_setting->sheet_id, $j)) {
                                        if($sheet_setting->validation_type == 'unique'){
                                            $error[] = 'Data Sheet ' . $sheet_setting->sheet->name . ' ' . $sheet_setting->kolom . $i . ' Tidak Boleh Duplikasi!';
                                        } else {
                                            if($j == 'ID') {
                                                $error[] = 'Mohon Cek Kebenaran Data dan mohon untuk:<br>
                                                    a. Kolom cek tidak diubah rumusnya<br>
                                                    b. Pengisian sesuai standar pengisian Form yg berlaku<br>
                                                    <br>
                                                    Jika masih terdapat error, mohon file excel dapat dikirimkan ke calenia.letitia@ptpjb.com (UP) atau febrian.aditiya@ptpjb.com (UBJOM). Terima kasih';
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
                                        'file_import_id' => $id,
                                        'sheet_id' => $sheet_setting->sheet_id,
                                        'lokasi_id' => $lokasi_value[$sheet->getName()][$i],
                                        'kolom' => $sheet_setting->kolom,
                                        'row' => $i,
                                        'value' => $value,
                                        'created_by' => $user_id,
                                    ];
                                }
                            }
                        } else {
                            if ($sheet_setting = ($setting[$sheet->getName()])->where('row', $i)->where('kolom', $j)->where('sequence', 0)->first()) {

                                if (!$this->validation($row2, $sheet_setting->validation, $sheet_setting->validation_type, $sheet_data, $sheet_setting->sheet_id, $j)) {
                                    if($sheet_setting->validation_type == 'unique'){
                                        $error[] = 'Data Sheet ' . $sheet_setting->sheet->name . ' ' . $sheet_setting->kolom . $i . ' Tidak Boleh Duplikasi!';
                                    } else {
                                        if($sheet_setting->kolom=='ID') {
                                            $error[] = 'Data sheet ' . $sheet_setting->sheet->name . ' ' . 'baris ' . $i . ' Tidak Sesuai!'.'<br>Mohon Cek Kebenaran Data dan mohon untuk:<br>
                                                    a. Kolom cek tidak diubah rumusnya<br>
                                                    b. Pengisian sesuai standar pengisian Form yg berlaku<br>
                                                    <br>
                                                    Jika masih terdapat error, mohon file excel dapat dikirimkan ke calenia.letitia@ptpjb.com (UP) atau dindha.yanne@ptpjb.com (UBJOM). Terima kasih';
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
                                    'file_import_id' => $id,
                                    'sheet_id' => $sheet_setting->sheet_id,
                                    'lokasi_id' => $lokasi_value[$sheet->getName()],
                                    'kolom' => $sheet_setting->kolom,
                                    'row' => $sheet_setting->row,
                                    'value' => $value,
                                    'created_by' => $user_id,
                                ];
                            }
                        }
                        $j++;
                    }
                    $i++;
                }
            }
        }

        DB::transaction(function() use ($sheet_id, $sheet_data, $id, $version) {
            // ExcelData::whereIn('sheet_id', $sheet_id)->delete();
            // ExcelData::where('file_import_id', $id)->where('sheet_id', $sheet_id)->delete();
            ExcelData::where('file_import_id', $id)->delete();

            //dipecah per 10000

            if ($version->template->jenis->id == 1) {
              # code...
              $sheet_chunk = array_chunk($sheet_data, 10000);

              foreach ($sheet_chunk as $chunk_data) {
                  ExcelData::insert($chunk_data);
              }

            } else {

              foreach ($sheet_data as $chunk_data) {
                  ExcelData::insert($chunk_data);

                }
                // ExcelData::insert($sheet_data);
            }

                //update lokasi_id di File Approval
                if($sheet_data) {
                FileApproval::where('file_import_id',$id)
                            ->update(['lokasi_id' => $sheet_data[0]['lokasi_id']]);

                FileImport::where('id',$id)
                            ->update(['lokasi_id' => $sheet_data[0]['lokasi_id']]);

                FileImport::where('id',$id)
                            ->update(['uploaded_by' => session('user_id')]);
                }
        });

        $setting_merge = collect();
        foreach ($setting as $row) {
            $setting_merge = $setting_merge->merge($row);
        }

        if(!count($error)) {
            foreach ($setting_merge->where('sequence', '>', 0)->sortBy('sequence')->groupBy('sequence') as $row) {
                $sheet_data = [];
                foreach ($row as $value) {
                    if (in_array($version->template->jenis_id, Jenis::FORM_6_10)) {
                        for ($i = 13; $i < $limit; $i++) {
                            if (!empty($value->query_value)) {
                                $query_str = $this->sql_replace($value->query_value, $id, $value->sheet_id, $i, $value->kolom);
                                $query = DB::select($query_str);
                                $value_data = (!empty($query[0])) ? $query[0]->value : '';
                                if (!$this->validation($value_data, $value->validation, $value->validation_type, $sheet_data, $value->sheet_id, $value->kolom)) {
                                    if($value->validation_type == 'unique'){
                                        $error[] = 'Data sheet ' . $value->sheet->name . ' ' . $value->kolom . $i . ' Tidak Boleh Duplikasi!';
                                    } else {
                                        if($value->kolom=='ID') {
                                            $error[] = 'Data sheet ' . $value->sheet->name . ' ' . 'baris ' . $i . ' Tidak Sesuai!'.'<br>Mohon Cek Kebenaran Data dan mohon untuk:<br>
                                                    a. Kolom cek tidak diubah rumusnya<br>
                                                    b. Pengisian sesuai standar pengisian Form yg berlaku<br>
                                                    <br>
                                                    Jika masih terdapat error, mohon file excel dapat dikirimkan ke calenia.letitia@ptpjb.com (UP) atau dindha.yanne@ptpjb.com (UBJOM). Terima kasih';
                                        }
                                        else {
                                            $error[] = 'Data sheet ' . $value->sheet->name . ' ' . $value->kolom . $i . ' Tidak Sesuai!';
                                        }
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
                                    'file_import_id' => $id,
                                    'sheet_id' => $value->sheet_id,
                                    'lokasi_id' => $lokasi_value[$value->sheet->name][$i],
                                    'kolom' => $value->kolom,
                                    'row' => $i,
                                    'value' => $value_insert,
                                ];
                            }
                        }
                    } else {
                        if (!empty($value->query_value)) {
                            $query_str = $this->sql_replace($value->query_value, $id, $value->sheet_id, $value->row, $value->kolom);
                            $query = DB::select($query_str);
                            $value_data = (!empty($query[0])) ? $query[0]->value : '';
                            if (!$this->validation($value_data, $value->validation, $value->validation_type, $sheet_data, $value->sheet_id, $value->kolom)) {
                                if($value->validation_type == 'unique'){
                                    $error[] = 'Data sheet ' . $value->sheet->name . ' ' . $value->kolom . $value->row . ' Tidak Boleh Duplikasi!';
                                } else {
                                    if($value->kolom=='ID') {
                                        $error[] = 'Data sheet ' . $value->sheet->name . ' ' . 'baris '  . $value->row . ' Tidak Sesuai!'.'<br>Mohon Cek Kebenaran Data dan mohon untuk:<br>
                                                a. Kolom cek tidak diubah rumusnya<br>
                                                b. Pengisian sesuai standar pengisian Form yg berlaku<br>
                                                <br>
                                                Jika masih terdapat error, mohon file excel dapat dikirimkan ke calenia.letitia@ptpjb.com (UP) atau dindha.yanne@ptpjb.com (UBJOM). Terima kasih';
                                    }
                                    else {
                                        $error[] = 'Data sheet ' . $value->sheet->name . ' ' . $value->kolom . $value->row . ' Tidak Sesuai!';
                                    }
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
                                'file_import_id' => $id,
                                'sheet_id' => $value->sheet_id,
                                'lokasi_id' => $lokasi_value[$value->sheet->name],
                                'kolom' => $value->kolom,
                                'row' => $value->row,
                                'value' => $value_insert,
                            ];
                        }
                    }
                }

                DB::transaction(function () use ($sheet_data, $id) {
                    //dipecah per 10000
                    $sheet_chunk = array_chunk($sheet_data, 10000);

                    foreach ($sheet_chunk as $chunk_data) {
                        ExcelData::insert($chunk_data);
                    }

                    // ExcelData::insert($sheet_data);

                    //update lokasi_id di File Approval
                    // FileApproval::where('file_import_id',$id)
                    //     ->update(['lokasi_id' => $sheet_data[0]['lokasi_id']]);
                });

            }
        }

        if(count($error)){
            DB::transaction(function() use ($sheet_id, $fail_data, $id) {
                // ExcelData::whereIn('sheet_id', $sheet_id)->delete();
                ExcelData::where('file_import_id', $id)->delete();

                // Tidak perlu insert data fail
                // //dipecah per 10000
                // $sheet_chunk = array_chunk($fail_data, 10000);

                // foreach ($sheet_chunk as $chunk_data) {
                //     ExcelData::insert($chunk_data);
                // }

                // ExcelData::insert($fail_data);
            });

            // $request->session()->flash('error', $error);
            $this->error('Error: '. implode(', ', $error));
            $fileimport->error = implode('<br> ', $error);
            $fileimport->lokasi_id = NULL; //reset lokasi
            $fileimport->status_upload_id = '4'; //gagal
            $fileimport->save();
            // return redirect(route('fileimport.show', ['version_id' => $version_id, 'id' => $id]));
        } else {
            $fileimport->error = '';
            $fileimport->status_upload_id = '3'; //sukses
            $fileimport->draft_versi = date("Y-m-d H:i:s"); //draft versi
            $fileimport->save();
            // $request->session()->flash('success', 'Data berhasil di import!');
            $this->info('Data berhasil di import!');

        }
    }
}
