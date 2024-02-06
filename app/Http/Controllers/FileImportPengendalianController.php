<?php

namespace App\Http\Controllers;

use App\Entities\Distrik;
use App\Entities\ExcelData;
use App\Entities\ExcelDataKetetapan;
use App\Entities\PGDLExcelDataRevisi;
use App\Entities\Fase;
use App\Entities\FileImport;
use App\Entities\FileImportKetetapan;
use App\Entities\PGDLFileImportRevisi;
use App\Entities\FileApproval;
use App\Entities\PgdlHistoryLog;
use App\Entities\PgdlVersion;
use App\Entities\PgdlTemplate;
use App\Entities\PgdlSheet;
use App\Entities\History;
use App\Entities\Jenis;
use App\Entities\Lokasi;
use App\Entities\Sheet;
use App\Entities\SheetSetting;
use App\Entities\StrategiBisnis;
use App\Entities\Template;
use App\Entities\Version;
use App\Entities\User;
use App\Entities\Role;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\WriterFactory;
use Illuminate\Http\Request;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\ValidationExcelTrait;
use Illuminate\Support\Facades\Artisan;
use Storage;
use Session;
use App\Entities\PgdlSheetSetting;
use App\Entities\PgdlReportDashboardSetting;

class FileImportPengendalianController extends Controller
{
    use ValidationExcelTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($version_id, $id)
    {   
        // dd('1');
        /** memilih sheet yang akan dilihat */
        if(!is_numeric($id) OR !is_numeric($version_id)){
            return redirect('pagenotfound');                
        }
        // dd('2');
        $version = PGDLVersion::with('pgdl_template.jenis')
            ->where('id', $version_id)->first();
        if (!$version) {
            return redirect('pagenotfound');
        }
        // dd('3');
        $sheet_md = PGDLSheet::with(['pgdl_excel_datas_revisi' => function ($query) use ($id) {
            $query->where('pgdl_file_import_revisi_id', $id);
        }])->where('pgdl_version_id', $version_id)->get();

        // $fileimport = FileImport::find($id);
        $fileimport = PGDLFileImportRevisi::find($id);
        // dd($id, $fileimport);
        if (!$fileimport) {
            return redirect('pagenotfound');
        }
        // dd('4');
        // dd($sheet_md);
        $data = [
            'version' => $version,
            'sheet_md' => $sheet_md,
            'fileimport' => $fileimport,
            'id' => $id,
        ];

        // dd($data);

        $user_id = session('user_id');
        $user = User::find($user_id);
        $role_id = session('role_id');
        $role = Role::find($role_id);

        //kantor pusat --- SEMENTARA dari permintaan Pak Hisyam, 27 Des 17 supaya Unit tidak upload lagi
        if($role->is_kantor_pusat) {
          return view('fileimport_pengendalian.show', $data);
        }
        else {
          return redirect('/');
        }

        // return view('fileimport_pengendalian.show', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create($version_id)
    // {
    //     $user_id = session('user_id');
    //     $user = User::find($user_id);
    //     $role_id = session('role_id');
    //     $role = Role::find($role_id);
    //
    //     $fase = Fase::all();
    //     $version = Version::with('template')->where('id', $version_id)->first();
    //     $array_rutin_id = Template::select('id')->where('jenis_id', Jenis::FORM_6_RUTIN)->get()->toArray();
    //     $template_6_rutin = Version::with(['template', 'file_imports'])
    //         ->where('active', 1)
    //         ->whereIn('template_id', $array_rutin_id)
    //         ->get();
    //     $array_reimburse_id = Template::select('id')->where('jenis_id', Jenis::FORM_6_REIMBURSE)->get()->toArray();
    //     $template_6_reimburse = Version::with(['template', 'file_imports'])
    //         ->where('active', 1)
    //         ->whereIn('template_id', $array_reimburse_id)
    //         ->get();
    //     $array_pu_id = Template::select('id')->where('jenis_id', Jenis::FORM_10_PU)->get()->toArray();
    //     $template_10_pu = Version::with(['template', 'file_imports'])
    //         ->where('active', 1)
    //         ->whereIn('template_id', $array_pu_id)
    //         ->get();
    //     $array_pln_id = Template::select('id')->where('jenis_id', Jenis::FORM_10_PLN)->get()->toArray();
    //     $template_10_pln = Version::with(['template', 'file_imports'])
    //         ->where('active', 1)
    //         ->whereIn('template_id', $array_pln_id)
    //         ->get();
    //     $array_penguatankit_id = Template::select('id')->where('jenis_id', Jenis::FORM_10_PENGUATANKIT)->get()->toArray();
    //     $template_10_penguatankit = Version::with(['template', 'file_imports'])
    //         ->where('active', 1)
    //         ->whereIn('template_id', $array_penguatankit_id)
    //         ->get();
    //     $array_bahan_bakar_id = Template::select('id')->where('jenis_id', Jenis::FORM_BAHAN_BAKAR)->get()->toArray();
    //     $template_bahan_bakar = Version::with(['template', 'file_imports'])
    //         ->where('active', 1)
    //         ->whereIn('template_id', $array_bahan_bakar_id)
    //         ->get();
    //     $array_penyusutan_id = Template::select('id')->where('jenis_id', Jenis::FORM_PENYUSUTAN)->get()->toArray();
    //     $template_penyusutan = Version::with(['template', 'file_imports'])
    //         ->where('active', 1)
    //         ->whereIn('template_id', $array_penyusutan_id)
    //         ->get();
    //
    //     $data = [
    //         'user' => $user,
    //         'role' => $role,
    //         'version' => $version,
    //         'fase' => $fase,
    //         'template_6_rutin' => $template_6_rutin,
    //         'template_6_reimburse' => $template_6_reimburse,
    //         'template_10_pu' => $template_10_pu,
    //         'template_10_pln' => $template_10_pln,
    //         'template_10_penguatankit' => $template_10_penguatankit,
    //         'template_bahan_bakar' => $template_bahan_bakar,
    //         'template_penyusutan' => $template_penyusutan,
    //     ];
    //
    //     return view('fileimport_pengendalian.create', $data);
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request, $version_id)
    // {
    //     $this->validate($request, [
    //         'tahun' => 'required',
    //         'fase_id' => 'required',
    //         'draft_versi' => 'required',
    //         'name' => 'required',
    //     ]);
    //
    //     $version = Version::with('template')->where('id',$version_id)->first();
    //
    //     $user_id = session('user_id');
    //     $user = User::find($user_id);
    //     // dd($user);
    //
    //     $file_import = New FileImport();
    //
    //     $file_import->template_id = $version->template_id;
    //     $file_import->version_id = $version->id;
    //     $file_import->fase_id = $request->fase_id;
    //     $file_import->tahun = $request->tahun;
    //     $file_import->name = $request->name;
    //     $file_import->draft_versi = date('Y-m-d H:i:s');
    //     $file_import->form6_rutin_file_import_id = $request->form6_rutin_file_import_id;
    //     $file_import->form6_reimburse_file_import_id = $request->form6_reimburse_file_import_id;
    //     $file_import->form10_pln_file_import_id = $request->form10_pln_file_import_id;
    //     $file_import->form10_pu_file_import_id = $request->form10_pu_file_import_id;
    //     $file_import->form10_penguatankit_file_import_id = $request->form10_penguatankit_file_import_id;
    //     $file_import->form_bahan_bakar_file_import_id = $request->form_bahan_bakar_file_import_id;
    //     $file_import->form_penyusutan_file_import_id = $request->form_penyusutan_file_import_id;
    //     $file_import->distrik_id = $user->distrik_id;
    //
    //     if($file_import->save()){
    //         $file_approval = New FileApproval();
    //
    //         $file_approval->tahun_anggaran = $request->tahun;
    //         $file_approval->approval_id = 1;
    //         $file_approval->file_import_id = $file_import->id;
    //         $file_approval->jenis_id = $version->template->jenis_id;
    //         $file_approval->file_approval_status_id = 1;
    //         $file_approval->approval_by = 2;
    //         // if($user->distrik_id == '9') { //UBJOM Paiton
    //         //     $file_approval->lokasi_id = 27; //Common
    //         // }
    //         // elseif($user->distrik_id == '25') { //UP Paiton
    //         //     $file_approval->lokasi_id = 43; //Common
    //         // }
    //         // else {
    //         //     $file_approval->lokasi_id = 36; //default gresik
    //         // }
    //         $file_approval->created_by = 2;
    //         if($file_approval->save()) {
    //             $request->session()->flash('success', 'Data berhasil di buat!');
    //         }
    //     }
    //
    //     return redirect(route('template.show', ['jenis_id' => $version->template->jenis_id, 'id' => $version->template_id]));
    // }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_sheet($version_id, $id, $sheet_id)
    {
        if(!is_numeric($id) OR !is_numeric($version_id) OR !is_numeric($sheet_id)){
            return redirect('pagenotfound');                
        }
        /** menampilkan isi sheet */
        $dump = array(
            "version id" => $version_id,
            "id file import"=> $id,
            "sheet id"=> $sheet_id
        );
        // dd($version_id, $id, $sheet_id);
        ini_set('max_execution_time', 0);
        $version = PgdlVersion::with('pgdl_template.jenis')
            ->where('id', $version_id)->first();
        // dd($version);
        if (!$version) {
            return redirect('pagenotfound');
        }

        /* get sheet dengan id $sheet_id dan pgdl_version_id=$version_id 
            beserta data dengan pgdl_file_improt_revisi = $id */
        $sheet_md = PgdlSheet::with(['pgdl_excel_datas_revisi' => function ($query) use ($id) {
            $query->where('pgdl_file_import_revisi_id', $id);
        }])->where('pgdl_version_id', $version_id)
            ->where('id', $sheet_id)
            ->first();
        // dd($sheet_md);
        $dump["sheet_md"] = $sheet_md;
        if (!$sheet_md) {
            return redirect('pagenotfound');
        }
        // dd($dump);

        $fileimport = PGDLFileImportRevisi::find($id);
        if (!$fileimport) {
            return redirect('pagenotfound');
        }
        // dd($sheet_md);
        $cek_sheet_data = $sheet_md->pgdl_excel_datas_revisi->count();

        $filePath = $version->file;
        // dd('1');
        $reader = ReaderFactory::create(Type::XLSX);
        try {
            $reader->open($filePath);
        } catch (\Exception $e) {
        // dd($filePath);
            return redirect('pagenotfound');
        }
        // dd($id, $sheet_id);
        $excel_data = PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $id)
            ->where('pgdl_sheet_id', $sheet_id)
            ->get();
        // dd('sukses');
            // dd($excel_data);
        // dd($sheet_md->id);
        $setting = PgdlSheetSetting::where('pgdl_sheet_id', $sheet_md->id)
            ->orderBy('row', 'asc')
            ->orderBy('kolom', 'asc')
            ->get();
                // dd($setting, $excel_data);
              
        $sheet_data = [];
        $header_data = [];
        $max_kolom = 0;
        foreach ($reader->getSheetIterator() as $sheet) {
            if ($sheet->getName() == $sheet_md->name) {
                $i = 1;
                $array_data = [];
                $sheet_id_data = '';
                foreach ($setting as $value) {
                    $array_data[] = [$value->kolom, $value->row];

                    $sheet_id_data = $value->pgdl_sheet_id;
                }
                $limit_row = 13;
                $template_2_3_empty = true;
                // dd("jenis id", $version->pgdl_template->jenis_id);
                if (in_array($version->pgdl_template->jenis_id, Jenis::FORM_6_10)) {
                    if($excel_data->max('row')){
                        $limit_row = $excel_data->max('row');
                        $template_2_3_empty = false;
                    }
                }
                // dd("template empty", $template_2_3_empty);
                $count_row2 = 0;
                foreach ($sheet->getRowIterator() as $row) {
                    if ((in_array($version->pgdl_template->jenis_id, Jenis::FORM_6_10)) && $i > 13) {
                        break;
                    }
                    $array_value = [];
                    if ($i >= 8 && array_filter($row)) {
                        $j = 'A';
                        if ($i >= 13) {
                            if (in_array($version->pgdl_template->jenis_id, Jenis::FORM_6_10)) {
                                if($template_2_3_empty){
                                    foreach ($row as $row2) {
                                        if (in_array([$j, 13], $array_data)) {
                                            $array_value[] = '?';
                                        }
                                        $j++;
                                    }
                                }
                                $count_row2 = count($row);
                            } else {
                                foreach ($row as $row2) {
                                    if (in_array([$j, $i], $array_data)) {
                                        $excel_val = $excel_data->where('pgdl_sheet_id', $sheet_id_data)->where('row', $i)->where('kolom', $j)->first();
                                        if ($excel_val) {
                                            $excel_value = $excel_val->value;
                                            if (is_numeric($excel_val->value)) {
                                                if($excel_val->value > 1000){
                                                    $excel_value = number_format($excel_val->value);
                                                }
                                                if ($excel_val->value < 0) {
                                                    $excel_value = "(" . number_format(abs($excel_val->value)) . ")";
                                                }
                                            }
                                            $array_value[] = $excel_value;
                                        } else {
                                            $array_value[] = '?';
                                        }
                                    } else {
                                        $array_value[] = $row2;
                                    }
                                    $j++;
                                    if($max_kolom <= $j){
                                        $max_kolom = $j;
                                    }
                                }
                            }
                        } else {
                            $header_data[] = $row;
                        }
                        $sheet_data[] = $array_value;
                    }
                    $i++;
                }
                if (in_array($version->pgdl_template->jenis_id, Jenis::FORM_6_10)) {
                    for ($i = 13; $i <= $limit_row; $i++) {
                        $j = 'A';
                        $array_value = [];
                        for($k = 0; $k < $count_row2; $k++) {
                            if (in_array([$j, 13], $array_data)) {
                                $excel_val = $excel_data->where('pgdl_sheet_id', $sheet_id_data)->where('row', $i)->where('kolom', $j)->first();
                                if ($excel_val) {
                                    $excel_value = $excel_val->value;
                                    if (is_numeric($excel_val->value)) {
                                        if($excel_val->value > 1000){
                                            $excel_value = number_format($excel_val->value);
                                        }
                                        if ($excel_val->value < 0) {
                                            $excel_value = "(" . number_format(abs($excel_val->value)) . ")";
                                        }
                                    }
                                    $array_value[] = $excel_value;
                                } else {
                                    $array_value[] = 0;
                                }
                            }
                            $j++;
                            if($max_kolom <= $j){
                                $max_kolom = $j;
                            }
                        }
                        $sheet_data[] = $array_value;
                    }
                }
            }
        }

        // dd($sheet_data);

        $blak_kolom = [];
        for($i='A'; $i <= $max_kolom; $i++){
            $blak_kolom[] = '';
        }

        $header_data[] = $blak_kolom;
        // dd($header_data);
        $data = [
            'version' => $version,
            'cek_sheet_data' => $cek_sheet_data,
            'sheet' => $sheet_data,
            'sheet_header' => $header_data,
            'sheet_md' => $sheet_md,
            'fileimport' => $fileimport,
            'id' => $id,
        ];

        $reader->close();

        return view('fileimport_pengendalian.show_sheet', $data);
    }

    public function edit_import($version_id, $id, $sheet_id)
    {
        // dd($version_id, $id, $sheet_id); 
        if (!is_numeric($version_id) OR !is_numeric($id) OR !is_numeric($sheet_id)) {
            return redirect('pagenotfound');
        }
        ini_set('max_execution_time', -1);
        $version = PgdlVersion::with('pgdl_template')->where('id', $version_id)->first();
        if (!$version) {
            return redirect('pagenotfound');
        }
        // dd($version);
        $sheet_md = PgdlSheet::where('id', $sheet_id)->first();
        if (!$sheet_md) {
            return redirect('pagenotfound');
        }
        // dd($sheet_md);
        $filePath = $version->file;
        $reader = ReaderFactory::create(Type::XLSX);
        $reader->open($filePath);
        // dd($filePath);
        $file_import = PGDLFileImportRevisi::find($id);
        if (!$file_import) {
            return redirect('pagenotfound');
        }
        $excel_data = PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $id)
            ->where('pgdl_sheet_id', $sheet_id)
            ->where('lokasi_id', $file_import->lokasi_id)
            ->get();
        if (!$excel_data) {
            return redirect('pagenotfound');
        }

        $jenis_id = $version->pgdl_template->jenis_id;
        // mendapatkan data file import pengendalian rkau
        if ($jenis_id == Jenis::FORM_RKAU) {    
            $file_import_rkau = $file_import;
        } elseif ($jenis_id == Jenis::FORM_6_REIMBURSE) {
            $file_import_rkau = PGDLFileImportRevisi::where('form6_reimburse_pgdl_file_import_revisi_id', $file_import->id)->where('distrik_id', $file_import->distrik_id)->first();
        } elseif ($jenis_id == Jenis::FORM_6_RUTIN) {
            $file_import_rkau = PGDLFileImportRevisi::where('form6_rutin_pgdl_file_import_revisi_id', $file_import->id)->where('distrik_id', $file_import->distrik_id)->first();
        } elseif ($jenis_id == Jenis::FORM_10_PU) {
            $file_import_rkau = PGDLFileImportRevisi::where('form10_pu_pgdl_file_import_revisi_id', $file_import->id)->where('distrik_id', $file_import->distrik_id)->first();
        } elseif ($jenis_id == Jenis::FORM_10_PENGUATANKIT) {
            $file_import_rkau = PGDLFileImportRevisi::where('form10_penguatankit_pgdl_file_import_revisi_id', $file_import->id)->where('distrik_id', $file_import->distrik_id)->first();
        } elseif ($jenis_id == Jenis::FORM_10_PLN) {
            $file_import_rkau = PGDLFileImportRevisi::where('form10_pln_pgdl_file_import_revisi_id', $file_import->id)->where('distrik_id', $file_import->distrik_id)->first();
        } elseif ($jenis_id == Jenis::FORM_BAHAN_BAKAR) {
            $file_import_rkau = PGDLFileImportRevisi::where('form_bahan_bakar_pgdl_file_import_revisi_id', $file_import->id)->where('distrik_id', $file_import->distrik_id)->first();
        } elseif ($jenis_id == Jenis::FORM_PENYUSUTAN) {
            $file_import_rkau = PGDLFileImportRevisi::where('form_penyusutan_pgdl_file_import_revisi_id', $file_import->id)->where('distrik_id', $file_import->distrik_id)->first();
        }
        if (!$file_import_rkau) {
            $error[] = 'File Import RKAU Tidak Ditemukan!';
            Session::flash('error', $error);
            return redirect(route('fileimportpengendalian.show', ['version_id' => $version_id, 'id' => $id]));
        }

        $sheets_rkau = PgdlSheet::where(function($query) use ($file_import_rkau) {
            $query->where('pgdl_version_id', $file_import_rkau->pgdl_version_id)->where('name', 'I-LR');
        })->orWhere(function($query) use ($file_import_rkau) {
            $query->where('pgdl_version_id', $file_import_rkau->pgdl_version_id)->where('name', 'I-CF');
        })->get();

        $start_row = PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $id)
            ->where('pgdl_sheet_id', $sheet_id)
            ->where('lokasi_id', $file_import->lokasi_id)
            ->where('kolom', 'ID')
            ->where('row', 2)
            ->first();
        $start_kolom = PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $id)
            ->where('pgdl_sheet_id', $sheet_id)
            ->where('lokasi_id', $file_import->lokasi_id)
            ->where('kolom', 'ID')
            ->where('row', 4)
            ->first();
        $end_kolom = PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $id)
            ->where('pgdl_sheet_id', $sheet_id)
            ->where('lokasi_id', $file_import->lokasi_id)
            ->where('kolom', 'ID')
            ->where('row', 6)
            ->first();

        if (!$start_row OR !$start_kolom OR !$end_kolom) {
            $error[] = 'Mohon untuk melakukan setting pada Sheet '.$sheet_md->name.' Kolom ID Baris 2, 4, dan 6!';
            Session::flash('error', $error);
            return redirect(route('fileimportpengendalian.show', ['version_id' => $version_id, 'id' => $id]));
        }
        // dd($start_row, $start_kolom, $end_kolom);
        $start_row = $start_row->value;
        $start_kolom = $start_kolom->value;
        $end_kolom = $end_kolom->value;

        $array_kolom = [];
        $current_kolom = $start_kolom;
        while ($current_kolom != $end_kolom) {
            $array_kolom[] = $current_kolom;
            $current_kolom++;
        }
        $array_kolom[] = $current_kolom;
        // dd($array_kolom);
        $setting = PgdlSheetSetting::where('pgdl_sheet_id', $sheet_md->id)
            ->whereIn('kolom', $array_kolom)
            ->where('row', '>=', $start_row)
            ->get();
        
        // dd($setting);
        $error = [];
        $sheet_data = [];
        $array_updatable = [];
        $array_updatable_id = [];
        $k = 1;
        $end_row = $excel_data->max('row');
        // $count = 0;
        // $count_rom_kolom = [];

        if ($jenis_id == Jenis::FORM_RKAU) {
            for ($current_row = $start_row ; $current_row <= $end_row ; $current_row++ ) { 
                $current_kolom = $start_kolom;
                $array_value = [];
                while ($current_kolom != $end_kolom) {
                    if ($sheet_setting = $setting->where('row', $current_row)->where('kolom', $current_kolom)->first()) {
                        $excel_val = $excel_data->where('pgdl_sheet_id', $sheet_setting->pgdl_sheet_id)->where('row', $current_row)->where('kolom', $current_kolom)->first();
                        if (is_null($excel_val)) {
                            $array_value[] = '';
                        }
                        else {
                            if (empty($sheet_setting->query_value) AND $sheet_setting->editable == 1) {
                                $array_value[] = $excel_val->value;
                                $array_updatable[] = $k;
                                $array_updatable_id[$k] = $excel_val->id;
                            } else {
                                $excel_value = $excel_val->value;
                                if (is_numeric($excel_val->value)) {
                                    $excel_value = number_format($excel_val->value);
                                }
                                $array_value[] = $excel_value;
                            }
                        }
                    } else {
                        // $count++;
                        // $count_rom_kolom[] = ['kolom' => $current_kolom, 'row' => $current_row];
                        $array_value[] = '';
                    }

                    $current_kolom++;
                    $k++;
                }

                if ($sheet_setting = $setting->where('row', $current_row)->where('kolom', $current_kolom)->first()) {
                    $excel_val = $excel_data->where('pgdl_sheet_id', $sheet_setting->pgdl_sheet_id)->where('row', $current_row)->where('kolom', $current_kolom)->first();
                    if (is_null($excel_val)) {
                        $array_value[] = '';
                    }
                    else {
                        if (empty($sheet_setting->query_value) AND $sheet_setting->editable == 1) {
                            $array_value[] = $excel_val->value;
                            $array_updatable[] = $k;
                            $array_updatable_id[$k] = $excel_val->id;
                        } else {
                            $excel_value = $excel_val->value;
                            if (is_numeric($excel_val->value)) {
                                $excel_value = number_format($excel_val->value);
                            }
                            $array_value[] = $excel_value;
                        }
                    }
                } else {
                    // $count++;
                    // $count_rom_kolom[] = ['kolom' => $current_kolom, 'row' => $current_row];
                    $array_value[] = '';
                }

                $k++;
                $sheet_data[] = $array_value;
            }
        } else {
            for ($current_row = $start_row ; $current_row <= $end_row ; $current_row++ ) { 
                $current_kolom = $start_kolom;
                $array_value = [];
                if ($current_row < 13) {
                    $used_row = $current_row;
                } else {
                    $used_row = 13;
                }
                while ($current_kolom != $end_kolom) {
                    if ($sheet_setting = $setting->where('row', $used_row)->where('kolom', $current_kolom)->first()) {
                        $excel_val = $excel_data->where('pgdl_sheet_id', $sheet_setting->pgdl_sheet_id)->where('row', $current_row)->where('kolom', $current_kolom)->first();
                        if (is_null($excel_val)) {
                            $array_value[] = '';
                        }
                        else {
                            if (empty($sheet_setting->query_value) AND $sheet_setting->editable == 1) {
                                $array_value[] = $excel_val->value;
                                $array_updatable[] = $k;
                                $array_updatable_id[$k] = $excel_val->id;
                            } else {
                                $excel_value = $excel_val->value;
                                if (is_numeric($excel_val->value)) {
                                    $excel_value = number_format($excel_val->value);
                                }
                                $array_value[] = $excel_value;
                            }
                        }
                    } else {
                        // $count++;
                        // $count_rom_kolom[] = ['kolom' => $current_kolom, 'row' => $current_row];
                        $array_value[] = '';
                    }

                    $current_kolom++;
                    $k++;
                }

                if ($sheet_setting = $setting->where('row', $used_row)->where('kolom', $current_kolom)->first()) {
                    $excel_val = $excel_data->where('pgdl_sheet_id', $sheet_setting->pgdl_sheet_id)->where('row', $current_row)->where('kolom', $current_kolom)->first();
                    if (is_null($excel_val)) {
                        $array_value[] = '';
                    }
                    else {
                        if (empty($sheet_setting->query_value) AND $sheet_setting->editable == 1) {
                            $array_value[] = $excel_val->value;
                            $array_updatable[] = $k;
                            $array_updatable_id[$k] = $excel_val->id;
                        } else {
                            $excel_value = $excel_val->value;
                            if (is_numeric($excel_val->value)) {
                                $excel_value = number_format($excel_val->value);
                            }
                            $array_value[] = $excel_value;
                        }
                    }
                } else {
                    // $count++;
                    // $count_rom_kolom[] = ['kolom' => $current_kolom, 'row' => $current_row];
                    $array_value[] = '';
                }

                $k++;
                $sheet_data[] = $array_value;
            }
        }
        
        
        // dd($sheet_data);
        // dd($array_updatable, $array_updatable_id);
        // if (count($error)) {
        //     $error_message = implode('<br> ', $error);
        //     Session::flash('error_message', $error_message);
        //     // dd($version_id, $id, $error_message);
        //     return redirect(route('fileimportpengendalian.show', ['version_id' => $version_id, 'id' => $id]));
        // }

        $updatable = [
            'updatable' => $array_updatable,
            'updatable_id' => $array_updatable_id,
        ];
        // dd($sheet_md);
        // dd($updatable);
        $data = [
            'version' => $version,
            'sheet_md' => $sheet_md,
            'sheet' => $sheet_data,
            'updatable' => $updatable,
            'id' => $id,
            'sheet_id' => $sheet_id,
            'start_row' => $start_row,
            'end_row' => $end_row,
            '$start_kolom' => $start_kolom,
            'end_kolom' => $end_kolom
        ];

        $reader->close();

        // dd($data['sheet']);

        return view('fileimport_pengendalian.edit_import', $data);
    }

    public function import_use(Request $request, $version_id, $id)
    {
        $reader = ReaderFactory::create(Type::XLSX);

        // $file = $request->file('file');
        // $destinationPath = "temp";
        // $filename= 'temp.'.$file->getClientOriginalExtension();
        // $request->file('file')->move($destinationPath, $filename);
        //
        // $reader->open($destinationPath.'/'.$filename);
        //
        // $sheet_data = [];
        // foreach ($reader->getSheetIterator() as $sheet) {
        //     $sheet_data[] = $sheet->getName();
        // }
        // $reader->close();
        //
        // $destinationPath2 = 'entry data/'.$id;
        // $filename2= $file->getClientOriginalName();;
        //
        // $fileimport = FileImport::find($id);
        // $fileimport->file = ('/'.$destinationPath2.'/'.$filename2);
        // $fileimport->save();

        $file = $request->file('file');
        $destinationPath = 'entry data/'.$id;
        $filename= $file->getClientOriginalName();
        $fileimport = PGDLFileImportRevisi::find($id);
        $fileimport->file = ('/'.$destinationPath.'/'.$filename);
        $request->file('file')->move($destinationPath, $filename);
        $fileimport->save();


        $destinationPath_temp = "temp";
        $filename_temp = 'temp.'.$file->getClientOriginalExtension();
        $copy = copy($destinationPath.'/'.$filename, $destinationPath_temp.'/'.$filename_temp);

        $reader->open($destinationPath_temp.'/'.$filename_temp);

        $sheet_data = [];
        foreach ($reader->getSheetIterator() as $sheet) {
            $sheet_data[] = $sheet->getName();
        }

        $data = [
            'version' => $version_id,
            'sheet' => $sheet_data,
            'id' => $id,
        ];

        return view('fileimport_pengendalian.use', $data);
    }

    public function import(Request $request, $version_id, $id)
    {
        ini_set('max_execution_time', ((20*(60*60))));

        $validator = Validator::make($request->all(), [
            'sheet' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route('fileimport_pengendalian.show', ['version_id' => $version_id, 'id' => $id]))
                ->withErrors($validator)
                ->withInput();
        }

        $run = Artisan::call('import:insert_pgdl', [
            'id' => $id, 'sheet' => $request->sheet
        ]);

        // return $run;

        // $version = Version::with('template')->where('id', $version_id)->first();

        // $sheet_md = Sheet::where('version_id', $version_id)->get();

        // $strategi_bisnis = StrategiBisnis::all();
        // $distrik = Distrik::all();
        // $lokasi = Lokasi::all();

        // $reader = ReaderFactory::create(Type::XLSX);

        // $reader->open('temp/temp.xlsx');

        // $sheet_use = [];
        // $sheet_id = [];
        // $setting = [];
        // foreach ($sheet_md as $row){
        //     if(in_array($row->name, $request->sheet)){
        //         $sheet_use[] = $row->name;
        //         $sheet_id[] = $row->id;
        //         if(in_array($version->template->jenis_id, Jenis::FORM_6_10)){
        //             $setting[$row->name] = SheetSetting::with('sheet')->where('sheet_id', $row->id)
        //                 ->orderBy('kolom', 'asc')
        //                 ->get();
        //         } else {
        //             $setting[$row->name] = SheetSetting::with('sheet')->where('sheet_id', $row->id)
        //                 ->orderBy('row', 'asc')
        //                 ->orderBy('kolom', 'asc')
        //                 ->get();
        //         }
        //     }
        // }

        // $fail_excel = ExcelData::whereIn('sheet_id', $sheet_id)->get();

        // $fail_data = [];
        // foreach ($fail_excel as $row){
        //     $fail_data[] = [
        //         'file_import_id' => $row->id,
        //         'sheet_id' => $row->sheet_id,
        //         'kolom' => $row->kolom,
        //         'row' => $row->row,
        //         'value' => $row->value,
        //     ];
        // }

        // $sheet_data = [];
        // $error = [];
        // $limit = 12;
        // $lokasi_value = [];
        // foreach ($reader->getSheetIterator() as $sheet) {
        //     if (in_array($sheet->getName(), $sheet_use)) {
        //         $i = 1;
        //         $lokasi_value[$sheet->getName()] = null;
        //         if(in_array($version->template->jenis_id, Jenis::FORM_6_10)){
        //             $i_cek = 1;
        //             foreach ($sheet->getRowIterator() as $row){
        //                 if ($i_cek >= 13 && array_filter($row)) {
        //                     $limit++;
        //                 }
        //                 $i_cek++;
        //             }
        //         }
        //         $break_i = false;
        //         foreach ($sheet->getRowIterator() as $row) {
        //             $j = 'A';
        //             if(in_array($version->template->jenis_id, Jenis::FORM_6_10) && $i > $limit ){
        //                 break;
        //             }
        //             foreach ($row as $row2) {
        //                 if(in_array($version->template->jenis_id, Jenis::FORM_6_10)){
        //                     if($i >= 13) {
        //                         $lokasi_value[$sheet->getName()][$i] = null;
        //                         if ($j == 'B') {
        //                             $struktur_bisnis_value = $strategi_bisnis->where('name', $row2)->first();
        //                             if(!$struktur_bisnis_value){
        //                                 $lokasi_value[$sheet->getName()][$i] = null;
        //                                 break;
        //                             }
        //                         }
        //                         if ($j == 'C') {
        //                             $distrik_value = $distrik->where('code1', $row2)->where('strategi_bisnis_id', $struktur_bisnis_value->id)->first();
        //                             if(!$distrik_value){
        //                                 $lokasi_value[$sheet->getName()][$i] = null;
        //                                 break;
        //                             }
        //                         }
        //                         if ($j == 'D') {
        //                             $lokasi_cek = $lokasi->where('name', $row2)->where('distrik_id', $distrik_value->id)->first();
        //                             $lokasi_value[$sheet->getName()][$i] = ($lokasi_cek)?$lokasi_cek->id:null;
        //                             break;
        //                         }
        //                     }
        //                 } else {
        //                     if ($i == 3 && $j == 'C') {
        //                         $struktur_bisnis_value = $strategi_bisnis->where('name', $row2)->first();
        //                         if(!$struktur_bisnis_value){
        //                             $lokasi_value[$sheet->getName()] = null;
        //                             $break_i = true;
        //                             break;
        //                         }
        //                     }
        //                     if ($i == 4 && $j == 'C') {
        //                         $distrik_value = $distrik->where('code1', $row2)->where('strategi_bisnis_id', $struktur_bisnis_value->id)->first();
        //                         if(!$distrik_value){
        //                             $lokasi_value[$sheet->getName()] = null;
        //                             $break_i = true;
        //                             break;
        //                         }
        //                     }
        //                     if ($i == 5 && $j == 'C') {
        //                         if($distrik_value){
        //                             $lokasi_cek = $lokasi->where('name', $row2)->where('distrik_id', $distrik_value->id)->first();
        //                             $lokasi_value[$sheet->getName()] = ($lokasi_cek)?$lokasi_cek->id:null;
        //                             $break_i = true;
        //                             break;
        //                         }
        //                     }
        //                 }
        //                 $j++;
        //             }
        //             $i++;
        //             if($break_i)
        //             break;
        //         }
        //     }
        // }

        // $limit = 12;
        // foreach ($reader->getSheetIterator() as $sheet) {
        //     if (in_array($sheet->getName(), $sheet_use)) {
        //         $i = 1;
        //         if(!in_array($version->template->jenis_id, Jenis::FORM_6_10)){
        //             if(!$lokasi_value[$sheet->getName()]){
        //                 $error[] = 'Data Sheet '.$sheet->getName().' Lokasi Tidak Sesuai!';
        //                 continue;
        //             }
        //         }
        //         if(in_array($version->template->jenis_id, Jenis::FORM_6_10)){
        //             $i_cek = 1;
        //             foreach ($sheet->getRowIterator() as $row){
        //                 if ($i_cek >= 13 && array_filter($row)) {
        //                     $limit++;
        //                 }
        //                 $i_cek++;
        //             }
        //         }
        //         foreach ($sheet->getRowIterator() as $row) {
        //             $j = 'A';
        //             if(in_array($version->template->jenis_id, Jenis::FORM_6_10) && $i > $limit ){
        //                 break;
        //             }
        //             if(in_array($version->template->jenis_id, Jenis::FORM_6_10)){
        //                 if($i >= 13){
        //                     if(!$lokasi_value[$sheet->getName()][$i]){
        //                         $error[] = 'Data Row '.$i.' Lokasi Tidak Sesuai!';
        //                         $i++;
        //                         continue;
        //                     }
        //                 }
        //             }
        //             foreach ($row as $row2) {
        //                 if(in_array($version->template->jenis_id, Jenis::FORM_6_10)){
        //                     if($i >= 13) {
        //                         if ($sheet_setting = ($setting[$sheet->getName()])->where('kolom', $j)->where('sequence', 0)->first()) {
        //                             if (!$this->validation($row2, $sheet_setting->validation, $sheet_setting->validation_type, $sheet_data, $sheet_setting->sheet_id, $j)) {
        //                                 if($sheet_setting->validation_type == 'unique'){
        //                                     $error[] = 'Data Sheet ' . $sheet_setting->sheet->name . ' ' . $sheet_setting->kolom . $i . ' Tidak Boleh Duplikasi!';
        //                                 } else {
        //                                     $error[] = 'Data Sheet ' . $sheet_setting->sheet->name . ' ' . $sheet_setting->kolom . $i . ' Tidak Sesuai!';
        //                                 }
        //                             }
        //                             $value = $row2;
        //                             if($sheet_setting->validation_type == 'numeric'){
        //                                 $value = (int)$row2;
        //                             }
        //                             if($sheet_setting->validation_type == 'string'){
        //                                 $value = (string)$row2;
        //                             }
        //                             $sheet_data[] = [
        //                                 'file_import_id' => $id,
        //                                 'sheet_id' => $sheet_setting->sheet_id,
        //                                 'lokasi_id' => $lokasi_value[$sheet->getName()][$i],
        //                                 'kolom' => $sheet_setting->kolom,
        //                                 'row' => $i,
        //                                 'value' => $value,
        //                             ];
        //                         }
        //                     }
        //                 } else {
        //                     if ($sheet_setting = ($setting[$sheet->getName()])->where('row', $i)->where('kolom', $j)->where('sequence', 0)->first()) {

        //                         if (!$this->validation($row2, $sheet_setting->validation, $sheet_setting->validation_type, $sheet_data, $sheet_setting->sheet_id, $j)) {
        //                             if($sheet_setting->validation_type == 'unique'){
        //                                 $error[] = 'Data Sheet ' . $sheet_setting->sheet->name . ' ' . $sheet_setting->kolom . $i . ' Tidak Boleh Duplikasi!';
        //                             } else {
        //                                 $error[] = 'Data Sheet ' . $sheet_setting->sheet->name . ' ' . $sheet_setting->kolom . $i . ' Tidak Sesuai!';
        //                             }
        //                         }

        //                         $value = $row2;

        //                         if($version->template->jenis_id==1) {
        //                             if($sheet->getName() == 'I-LR') {
        //                                 if($i == 39 && $j == "F") {
        //                                     $fileimport_model = FileImport::find($id);
        //                                     $query_str = "select sum(e.value::float) as value from excel_datas e join sheets s on e.sheet_id = s.id where e.file_import_id = ".$fileimport_model->form6_rutin_file_import_id." and s.name like 'I-Form 6' and lokasi_id = ".$lokasi_value[$sheet->getName()]." and (e.kolom like 'AK' or e.kolom like 'AL') and e.value <> ''";
        //                                     $query = DB::select($query_str);
        //                                     $value_data = (!empty($query[0]->value)) ? $query[0]->value : '';
        //                                     $value = $value_data;
        //                                 }

        //                                 if($i == 40 && $j == "F") {
        //                                     $fileimport_model = FileImport::find($id);
        //                                     $query_str = "select (rei.value::float + pln.value::float) as value from ( select sum(e.value::float) as value from excel_datas e join sheets s on e.sheet_id = s.id where e.file_import_id = ".$fileimport_model->form6_reimburse_file_import_id." and s.name like 'I-Form 6' and (e.kolom like 'AK' or e.kolom like 'AL') and lokasi_id = ".$lokasi_value[$sheet->getName()]." and e.value <> '') as rei, (select COALESCE(sum(e.value::float), 0) as value from excel_datas e join sheets s on e.sheet_id = s.id where e.file_import_id = ".$fileimport_model->form10_pln_file_import_id." and s.name like 'I-Form 10' and e.kolom like 'AR' and lokasi_id = ".$lokasi_value[$sheet->getName()]." and e.value <> '') as pln";

        //                                     $query = DB::select($query_str);
        //                                     $value_data = (!empty($query[0]->value)) ? $query[0]->value : '';
        //                                     $value = $value_data;
        //                                 }

        //                                 if($i == 42 && $j == "F") {
        //                                     $fileimport_model = FileImport::find($id);
        //                                     $query_str = "select sum(e.value::float) as value from excel_datas e join sheets s on e.sheet_id = s.id where e.file_import_id = ".$fileimport_model->form6_rutin_file_import_id." and s.name like 'I-Form 6' and e.kolom like 'AM' and lokasi_id = ".$lokasi_value[$sheet->getName()]." and e.value <> ''";

        //                                     $query = DB::select($query_str);
        //                                     $value_data = (!empty($query[0]->value)) ? $query[0]->value : '';
        //                                     $value = $value_data;
        //                                 }

        //                                 if($i == 43 && $j == "F") {
        //                                     $fileimport_model = FileImport::find($id);
        //                                     $query_str = "select sum(e.value::float) as value from excel_datas e join sheets s on e.sheet_id = s.id where e.file_import_id = ".$fileimport_model->form6_reimburse_file_import_id." and s.name like 'I-Form 6' and e.kolom like 'AM' and lokasi_id = ".$lokasi_value[$sheet->getName()]." and e.value <> ''";

        //                                     $query = DB::select($query_str);
        //                                     $value_data = (!empty($query[0]->value)) ? $query[0]->value : '';
        //                                     $value = $value_data;
        //                                 }
        //                             }
        //                             // if($sheet->getName() == 'I-CF') {
        //                             //     if($i == 32 && $j == "H") {
        //                             //         $fileimport_model = FileImport::find($id);
        //                             //         $query_str = "select sum(e.value::float) * -1 as value from excel_datas e join sheets s on e.sheet_id = s.id where e.file_import_id = ".$fileimport_model->form6_reimburse_file_import_id." and s.name like 'I-Form 6' and lokasi_id = ".$lokasi_value[$sheet->getName()]." and e.kolom like 'AS' and e.value <> ''";

        //                             //         $query = DB::select($query_str);
        //                             //         $value_data = (!empty($query[0]->value)) ? $query[0]->value : '';
        //                             //         $value = $value_data;
        //                             //     }
        //                             //     if($i == 33 && $j == "H") {
        //                             //         $fileimport_model = FileImport::find($id);
        //                             //         $query_str = "select (rei.value::float + pln.value::float) * -1 as value
        //                             //                 from
        //                             //                 (
        //                             //                     select sum(e.value::float) as value
        //                             //                     from excel_datas e
        //                             //                     join sheets s on e.sheet_id = s.id
        //                             //                     where e.file_import_id = ".$fileimport_model->form6_reimburse_file_import_id." and s.name like 'I-Form 6' and e.kolom like 'AS'
        //                             //                     and lokasi_id = ".$lokasi_value[$sheet->getName()]."
        //                             //                     and e.value <> ''
        //                             //                 ) as rei,
        //                             //                 (
        //                             //                     select COALESCE(sum(e.value::float), 0) as value
        //                             //                     from excel_datas e
        //                             //                     join sheets s on e.sheet_id = s.id
        //                             //                     where e.file_import_id = ".$fileimport_model->form10_pln_file_import_id." and s.name like 'I-Form 10' and e.kolom like 'AQ'
        //                             //                     and lokasi_id = ".$lokasi_value[$sheet->getName()]."
        //                             //                     and e.value <> ''
        //                             //                 ) as pln";

        //                             //         $query = DB::select($query_str);
        //                             //         $value_data = (!empty($query[0]->value)) ? $query[0]->value : '';
        //                             //         $value = $value_data;
        //                             //     }
        //                             //     if($i == 35 && $j == "H") {
        //                             //         $fileimport_model = FileImport::find($id);
        //                             //         $query_str = "select sum(e.value::float)  * -1 as value
        //                             //             from excel_datas e
        //                             //             join sheets s on e.sheet_id = s.id
        //                             //             where e.file_import_id = ".$fileimport_model->form6_rutin_file_import_id." and s.name like 'I-Form 6'
        //                             //             and lokasi_id = ".$lokasi_value[$sheet->getName()]."
        //                             //             and e.kolom like 'AU' and e.value <> ''";

        //                             //         $query = DB::select($query_str);
        //                             //         $value_data = (!empty($query[0]->value)) ? $query[0]->value : '';
        //                             //         $value = $value_data;
        //                             //     }
        //                             //     if($i == 36 && $j == "H") {
        //                             //         $fileimport_model = FileImport::find($id);
        //                             //         $query_str = "select sum(e.value::float)  * -1 as value
        //                             //             from excel_datas e
        //                             //             join sheets s on e.sheet_id = s.id
        //                             //             where e.file_import_id = ".$fileimport_model->form6_reimburse_file_import_id." and s.name like 'I-Form 6'
        //                             //             and lokasi_id = ".$lokasi_value[$sheet->getName()]."
        //                             //             and e.kolom like 'AU' and e.value <> ''";

        //                             //         $query = DB::select($query_str);
        //                             //         $value_data = (!empty($query[0]->value)) ? $query[0]->value : '';
        //                             //         $value = $value_data;
        //                             //     }
        //                             //     if($i == 47 && $j == "H") {
        //                             //         $fileimport_model = FileImport::find($id);
        //                             //         $query_str = "select (pu_ap.value - pu_ao.value - pk_ap.value + pk_aq.value) as value
        //                             //                 from
        //                             //                 (select COALESCE(sum(e.value::float), 0) as value
        //                             //                 from excel_datas e
        //                             //                 join sheets s on e.sheet_id = s.id
        //                             //                 where e.file_import_id = ".$fileimport_model->form10_pu_file_import_id." and s.name like 'I-Form 6'
        //                             //                 and lokasi_id = ".$lokasi_value[$sheet->getName()]."
        //                             //                 and e.kolom like 'AO' and e.value <> '') as pu_ao,
        //                             //                 (select COALESCE(sum(e.value::float), 0) as value
        //                             //                 from excel_datas e
        //                             //                 join sheets s on e.sheet_id = s.id
        //                             //                 where e.file_import_id = ".$fileimport_model->form10_pu_file_import_id." and s.name like 'I-Form 6'
        //                             //                 and lokasi_id = ".$lokasi_value[$sheet->getName()]."
        //                             //                 and e.kolom like 'AP' and e.value <> '') as pu_ap,
        //                             //                 (select COALESCE(sum(e.value::float), 0) as value
        //                             //                 from excel_datas e
        //                             //                 join sheets s on e.sheet_id = s.id
        //                             //                 where e.file_import_id = ".$fileimport_model->form10_penguatankit_file_import_id." and s.name like 'I-Form 6'
        //                             //                 and lokasi_id = ".$lokasi_value[$sheet->getName()]."
        //                             //                 and e.kolom like 'AP' and e.value <> '') as pk_ap,
        //                             //                 (select COALESCE(sum(e.value::float), 0) as value
        //                             //                 from excel_datas e
        //                             //                 join sheets s on e.sheet_id = s.id
        //                             //                 where e.file_import_id = ".$fileimport_model->form10_penguatankit_file_import_id." and s.name like 'I-Form 6'
        //                             //                 and lokasi_id = ".$lokasi_value[$sheet->getName()]."
        //                             //                 and e.kolom like 'AQ' and e.value <> '') as pk_aq"
        //                             //                 ;

        //                             //         $query = DB::select($query_str);
        //                             //         $value_data = (!empty($query[0]->value)) ? $query[0]->value : '';
        //                             //         $value = $value_data;
        //                             //     }
        //                             //     if($i == 49 && $j == "H") {
        //                             //         $fileimport_model = FileImport::find($id);
        //                             //         $query_str = "select (-(pu_aq.value + pu_ar.value) - (pk_ar.value + pk_as.value) - h1.value) as value
        //                             //                 from
        //                             //                 (
        //                             //                     select COALESCE(sum(e.value::float), 0) as value
        //                             //                     from excel_datas e
        //                             //                     join sheets s on e.sheet_id = s.id
        //                             //                     where e.file_import_id = ".$fileimport_model->form10_pu_file_import_id." and s.name like 'I-Form 6'
        //                             //                     and lokasi_id = ".$lokasi_value[$sheet->getName()]." and e.kolom like 'AQ' and e.value <> ''
        //                             //                 ) as pu_aq,
        //                             //                 (
        //                             //                     select COALESCE(sum(e.value::float), 0) as value
        //                             //                     from excel_datas e
        //                             //                     join sheets s on e.sheet_id = s.id
        //                             //                     where e.file_import_id = ".$fileimport_model->form10_pu_file_import_id." and s.name like 'I-Form 6'
        //                             //                     and lokasi_id = ".$lokasi_value[$sheet->getName()]." and e.kolom like 'AR' and e.value <> ''
        //                             //                 ) as pu_ar,
        //                             //                 (
        //                             //                     select COALESCE(sum(e.value::float), 0) as value
        //                             //                     from excel_datas e
        //                             //                     join sheets s on e.sheet_id = s.id
        //                             //                     where e.file_import_id = ".$fileimport_model->form10_penguatankit_file_import_id." and s.name like 'I-Form 6'
        //                             //                     and lokasi_id = ".$lokasi_value[$sheet->getName()]." and e.kolom like 'AR' and e.value <> ''
        //                             //                 ) as pk_ar,
        //                             //                 (
        //                             //                     select COALESCE(sum(e.value::float), 0) as value
        //                             //                     from excel_datas e
        //                             //                     join sheets s on e.sheet_id = s.id
        //                             //                     where e.file_import_id = ".$fileimport_model->form10_penguatankit_file_import_id." and s.name like 'I-Form 6'
        //                             //                     and lokasi_id = ".$lokasi_value[$sheet->getName()]." and e.kolom like 'AS' and e.value <> ''
        //                             //                 ) as pk_as,
        //                             //                 (
        //                             //                     select value::float as value from excel_datas where file_import_id = 1 and sheet_id = 2 and kolom like 'H' and row = 51
        //                             //                 ) as h1

        //                             //                 ";

        //                             //         $query = DB::select($query_str);
        //                             //         $value_data = (!empty($query[0]->value)) ? $query[0]->value : '';
        //                             //         $value = $value_data;
        //                             //     }
        //                             //     if($i == 50 && $j == "H") {
        //                             //         $fileimport_model = FileImport::find($id);
        //                             //         $query_str = "select (pu.value::float + pk.value::float) * -1 as value
        //                             //             from
        //                             //             (select sum(e.value::float) as value
        //                             //             from excel_datas e
        //                             //             join sheets s on e.sheet_id = s.id
        //                             //             where e.file_import_id = ".$fileimport_model->form10_pu_file_import_id." and s.name like 'I-Form 10' and e.kolom like 'AS' and lokasi_id = ".$lokasi_value[$sheet->getName()]."
        //                             //             and e.value <> '') as pu,
        //                             //             (select sum(e.value::float) as value
        //                             //             from excel_datas e
        //                             //             join sheets s on e.sheet_id = s.id
        //                             //             where e.file_import_id = ".$fileimport_model->form10_penguatankit_file_import_id." and s.name like 'I-Form 10' and e.kolom like 'AT'
        //                             //             and lokasi_id = ".$lokasi_value[$sheet->getName()]."
        //                             //             and e.value <> '' ) as pk";

        //                             //         $query = DB::select($query_str);
        //                             //         $value_data = (!empty($query[0]->value)) ? $query[0]->value : '';
        //                             //         $value = $value_data;
        //                             //     }
        //                             // }
        //                         }

        //                         if($sheet_setting->validation_type == 'numeric'){
        //                             $value = (int)$row2;
        //                         }
        //                         if($sheet_setting->validation_type == 'string'){
        //                             $value = (string)$row2;
        //                         }
        //                         $sheet_data[] = [
        //                             'file_import_id' => $id,
        //                             'sheet_id' => $sheet_setting->sheet_id,
        //                             'lokasi_id' => $lokasi_value[$sheet->getName()],
        //                             'kolom' => $sheet_setting->kolom,
        //                             'row' => $sheet_setting->row,
        //                             'value' => $value,
        //                         ];
        //                     }
        //                 }
        //                 $j++;
        //             }
        //             $i++;
        //         }
        //     }
        // }

        // DB::transaction(function() use ($sheet_id, $sheet_data, $id) {
            // ExcelData::whereIn('sheet_id', $sheet_id)->delete();
            // ExcelData::where('file_import_id', $id)->delete();

        //     //dipecah per 1000
        //     $sheet_chunk = array_chunk($sheet_data, 1000);

        //     foreach ($sheet_chunk as $chunk_data) {
        //         ExcelData::insert($chunk_data);
        //     }

        //     // ExcelData::insert($sheet_data);

        //     //update lokasi_id di File Approval
        //     // FileApproval::where('file_import_id',$id)
        //     //             ->update(['lokasi_id' => $sheet_data[0]['lokasi_id']]);
        // });

        // $setting_merge = collect();
        // foreach ($setting as $row) {
        //     $setting_merge = $setting_merge->merge($row);
        // }

        // if(!count($error)) {
        //     foreach ($setting_merge->where('sequence', '>', 0)->sortBy('sequence')->groupBy('sequence') as $row) {
        //         $sheet_data = [];
        //         foreach ($row as $value) {
        //             if (in_array($version->template->jenis_id, Jenis::FORM_6_10)) {
        //                 for ($i = 13; $i < $limit; $i++) {
        //                     if (!empty($value->query_value)) {
        //                         $query_str = $this->sql_replace($value->query_value, $id, $value->sheet_id, $i, $value->kolom);
        //                         $query = DB::select($query_str);
        //                         $value_data = (!empty($query[0])) ? $query[0]->value : '';
        //                         if (!$this->validation($value_data, $value->validation, $value->validation_type, $sheet_data, $value->sheet_id, $value->kolom)) {
        //                             if($value->validation_type == 'unique'){
        //                                 $error[] = 'Data sheet ' . $value->sheet->name . ' ' . $value->kolom . $i . ' Tidak Boleh Duplikasi!';
        //                             } else {
        //                                 $error[] = 'Data sheet ' . $value->sheet->name . ' ' . $value->kolom . $i . ' Tidak Sesuai!';
        //                             }
        //                         }
        //                         $value_insert = $value_data;
        //                         if($value->validation_type == 'numeric'){
        //                             $value_insert = (int)$value_data;
        //                         }
        //                         if($value->validation_type == 'string'){
        //                             $value_insert = (string)$value_data;
        //                         }
        //                         $sheet_data[] = [
        //                             'file_import_id' => $id,
        //                             'sheet_id' => $value->sheet_id,
        //                             'lokasi_id' => $lokasi_value[$value->sheet->name][$i],
        //                             'kolom' => $value->kolom,
        //                             'row' => $i,
        //                             'value' => $value_insert,
        //                         ];
        //                     }
        //                 }
        //             } else {
        //                 if (!empty($value->query_value)) {
        //                     $query_str = $this->sql_replace($value->query_value, $id, $value->sheet_id, $value->row, $value->kolom);
        //                     $query = DB::select($query_str);
        //                     $value_data = (!empty($query[0])) ? $query[0]->value : '';
        //                     if (!$this->validation($value_data, $value->validation, $value->validation_type, $sheet_data, $value->sheet_id, $value->kolom)) {
        //                         if($value->validation_type == 'unique'){
        //                             $error[] = 'Data sheet ' . $value->sheet->name . ' ' . $value->kolom . $value->row . ' Tidak Boleh Duplikasi!';
        //                         } else {
        //                             $error[] = 'Data sheet ' . $value->sheet->name . ' ' . $value->kolom . $value->row . ' Tidak Sesuai!';
        //                         }
        //                     }
        //                     $value_insert = $value_data;
        //                     if($value->validation_type == 'numeric'){
        //                         $value_insert = (int)$value_data;
        //                     }
        //                     if($value->validation_type == 'string'){
        //                         $value_insert = (string)$value_data;
        //                     }
        //                     $sheet_data[] = [
        //                         'file_import_id' => $id,
        //                         'sheet_id' => $value->sheet_id,
        //                         'lokasi_id' => $lokasi_value[$value->sheet->name],
        //                         'kolom' => $value->kolom,
        //                         'row' => $value->row,
        //                         'value' => $value_insert,
        //                     ];
        //                 }
        //             }
        //         }

        //         DB::transaction(function () use ($sheet_data, $id) {
        //             //dipecah per 1000
        //             $sheet_chunk = array_chunk($sheet_data, 1000);

        //             foreach ($sheet_chunk as $chunk_data) {
        //                 ExcelData::insert($chunk_data);
        //             }

        //             // ExcelData::insert($sheet_data);

        //             //update lokasi_id di File Approval
        //             // FileApproval::where('file_import_id',$id)
        //             //     ->update(['lokasi_id' => $sheet_data[0]['lokasi_id']]);
        //         });

        //     }
        // }

        // if(count($error)){
            // DB::transaction(function() use ($sheet_id, $fail_data, $id) {
                // ExcelData::whereIn('sheet_id', $sheet_id)->delete();
                // ExcelData::where('file_import_id', $id)->delete();

        //         //dipecah per 1000
        //         $sheet_chunk = array_chunk($fail_data, 1000);

        //         foreach ($sheet_chunk as $chunk_data) {
        //             ExcelData::insert($chunk_data);
        //         }

        //         // ExcelData::insert($fail_data);
        //     });

        //     $request->session()->flash('error', $error);
        //     return redirect(route('fileimport.show', ['version_id' => $version_id, 'id' => $id]));
        // } else {
        //     $request->session()->flash('success', 'Data berhasil di import!');
        // }

        // return redirect(route('fileimport.show', ['version_id' => $version_id, 'id' => $id]));

        $fileimport = PGDLFileImportRevisi::find($id);
        $template_id = $fileimport->template_id;
        $template = Template::find($template_id);
        $jenis_id = $template->jenis_id;
        return redirect(route('templatepengendalian.show', ['jenis_id' => $jenis_id, 'id' => $template_id]));
    }

    public function import_update(Request $request, $version_id, $id, $sheet_id)
    {
        // Ini coding untuk update online
        // ini_set('max_execution_time', 3000);
        // ini_set('max_input_vars', 100000);
        // dd($id, $sheet_id);
        $user_id = session('user_id');
        // $this->validate($request, [
        //     'keterangan' => 'required',
        // ]);
        $file_import = PGDLFileImportRevisi::find($id);
        $version = PgdlVersion::with('pgdl_template')->where('id', $version_id)->first();
        // dd($version->template->jenis_id);

        $update = $request->update;
        $checkbox = $request->change;
        // dd($checkbox);
        if (!$checkbox) {
            $request->session()->flash('fail', 'Tidak Ada Perubahan Data!');
            return redirect(route('fileimportpengendalian.show', ['version_id' => $version_id, 'id' => $id]));
        }

        $jenis_id = $version->pgdl_template->jenis_id;

        // mendapatkan data file import pengendalian rkau
        if ($jenis_id == Jenis::FORM_RKAU) {
            $file_import_rkau = $file_import;
        } elseif ($jenis_id == Jenis::FORM_6_REIMBURSE) {
            $file_import_rkau = PGDLFileImportRevisi::where('form6_reimburse_pgdl_file_import_revisi_id', $file_import->id)->where('distrik_id', $file_import->distrik_id)->first();
        } elseif ($jenis_id == Jenis::FORM_6_RUTIN) {
            $file_import_rkau = PGDLFileImportRevisi::where('form6_rutin_pgdl_file_import_revisi_id', $file_import->id)->where('distrik_id', $file_import->distrik_id)->first();
        } elseif ($jenis_id == Jenis::FORM_10_PU) {
            $file_import_rkau = PGDLFileImportRevisi::where('form10_pu_pgdl_file_import_revisi_id', $file_import->id)->where('distrik_id', $file_import->distrik_id)->first();
        } elseif ($jenis_id == Jenis::FORM_10_PENGUATANKIT) {
            $file_import_rkau = PGDLFileImportRevisi::where('form10_penguatankit_pgdl_file_import_revisi_id', $file_import->id)->where('distrik_id', $file_import->distrik_id)->first();
        } elseif ($jenis_id == Jenis::FORM_10_PLN) {
            $file_import_rkau = PGDLFileImportRevisi::where('form10_pln_pgdl_file_import_revisi_id', $file_import->id)->where('distrik_id', $file_import->distrik_id)->first();
        } elseif ($jenis_id == Jenis::FORM_BAHAN_BAKAR) {
            $file_import_rkau = PGDLFileImportRevisi::where('form_bahan_bakar_pgdl_file_import_revisi_id', $file_import->id)->where('distrik_id', $file_import->distrik_id)->first();
        } elseif ($jenis_id == Jenis::FORM_PENYUSUTAN) {
            $file_import_rkau = PGDLFileImportRevisi::where('form_penyusutan_pgdl_file_import_revisi_id', $file_import->id)->where('distrik_id', $file_import->distrik_id)->first();
        }
        // jika tidak ditemukan link terhadap file pengendalian rkau, maka cancel update I-LR dan I-CF
        if (!$file_import_rkau) {
            $error[] = 'File Import RKAU Tidak Ditemukan!';
            $request->session()->flash('error', $error);
            return redirect(route('fileimportpengendalian.show', ['version_id' => $version_id, 'id' => $id]));
        }

        $start_kolom = PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $id)
            ->where('pgdl_sheet_id', $sheet_id)
            ->where('lokasi_id', $file_import->lokasi_id)
            ->where('kolom', 'ID')
            ->where('row', 4)
            ->first()->value;
        $end_kolom = PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $id)
            ->where('pgdl_sheet_id', $sheet_id)
            ->where('lokasi_id', $file_import->lokasi_id)
            ->where('kolom', 'ID')
            ->where('row', 6)
            ->first()->value;

        $array_kolom = [];
        $current_kolom = $start_kolom;
        while ($current_kolom != $end_kolom) {
            $array_kolom[] = $current_kolom;
            $current_kolom++;
        }
        $array_kolom[] = $current_kolom;
        // mengambil data dari db sesuai dengan row yang telah dicentang
        $excel_data = PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $id)
            ->where('pgdl_sheet_id', $sheet_id)
            ->whereIn('row', $checkbox)
            ->whereIn('kolom', $array_kolom)
            ->get();
        // dd($excel_data->where('row', 20));
        $all_data = PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $id)
            ->where('pgdl_sheet_id', $sheet_id)
            ->where('row', '>=', 13)
            ->whereIn('kolom', $array_kolom)
            ->get();

        // // ambil data backup untuk jaga2 jika terjadi error
        // $backup_data = PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $id)
        //     ->where('pgdl_sheet_id', $sheet_id)
        //     ->get();

        $backup_data = [];
        foreach ($all_data as $row){
            $backup_data[] = [
                'pgdl_file_import_revisi_id' => $row->pgdl_file_import_revisi_id,
                'pgdl_sheet_id' => $row->pgdl_sheet_id,
                'lokasi_id' => $row->lokasi_id,
                'kolom' => $row->kolom,
                'row' => $row->row,
                'value' => $row->value,
            ];
        }
        // setting file pengendalian non RKAU
        $setting = PgdlSheetSetting::where('pgdl_sheet_id', $sheet_id)
            ->where('row', '>=', 13)
            ->whereIn('kolom', $array_kolom)
            ->get();
        // dd($setting);
        // inisialisasi sheet_data, history_log, dan error pengendalian
        $sheet_data = [];
        $history_log = [];
        $error = [];

        // membuat sheet data untuk data yang sequence nya = 0
        foreach ($excel_data as $row){
            if(in_array($version->pgdl_template->jenis_id, Jenis::FORM_6_10)) {
                if ($sheet_setting = $setting->where('row', 13)->where('kolom', $row->kolom)->where('sequence', 0)->first()) {
                    if ($this->validation_pgdl($update[$row['id']], $sheet_setting->validation, $sheet_setting->validation_type, $sheet_data, $row->pgdl_sheet_id, $row->kolom)) {
                        $value = $update[$row['id']];
                        if($sheet_setting->validation_type == 'numeric'){
                            $value = (int)$update[$row['id']];
                        }
                        if($sheet_setting->validation_type == 'string'){
                            $value = (string)$update[$row['id']];
                        }
                        $sheet_data[] = [
                            'pgdl_file_import_revisi_id' => $row->pgdl_file_import_revisi_id,
                            'pgdl_sheet_id' => $row->pgdl_sheet_id,
                            'lokasi_id' => $row->lokasi_id,
                            'kolom' => $row->kolom,
                            'row' => $row->row,
                            'value' => $value,
                            'created_by' => $user_id,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                    } else {
                        if($sheet_setting->validation_type == 'unique'){
                            $error[] = 'Data ' . $row->kolom . $row->row . ' Tidak boleh Duplikasi!';
                        } else {
                            $error[] = 'Data ' . $row->kolom . $row->row . ' Tidak Sesuai!';
                        }
                    }
                }
            } else {
                if ($sheet_setting = $setting->where('row', $row->row)->where('kolom', $row->kolom)->where('sequence', 0)->first()) {
                    if ($this->validation_pgdl($update[$row['id']], $sheet_setting->validation, $sheet_setting->validation_type, $sheet_data, $row->pgdl_sheet_id, $row->kolom)) {
                        $value = $update[$row['id']];
                        if($sheet_setting->validation_type == 'numeric'){
                            $value = (int)$update[$row['id']];
                        }
                        if($sheet_setting->validation_type == 'string'){
                            $value = (string)$update[$row['id']];
                        }
                        $sheet_data[] = [
                            'pgdl_file_import_revisi_id' => $row->pgdl_file_import_revisi_id,
                            'pgdl_sheet_id' => $row->pgdl_sheet_id,
                            'lokasi_id' => $row->lokasi_id,
                            'kolom' => $row->kolom,
                            'row' => $row->row,
                            'value' => $value,
                            'created_by' => $user_id,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                    } else {
                        if($sheet_setting->validation_type == 'unique'){
                            $error[] = 'Data ' . $row->kolom . $row->row . ' Tidak boleh Duplikasi!';
                        } else {
                            $error[] = 'Data ' . $row->kolom . $row->row . ' Tidak Sesuai!';
                        }
                    }
                }
            }
        }

        if (count($error)) {
            $request->session()->flash('error', $error);
            return redirect(route('fileimportpengendalian.editimport', ['version_id' => $version_id, 'id' => $id, 'sheet_id' => $sheet_id]));
        }
        // update data pada db yang sequence nya = 0
        DB::transaction(function() use ($id, $sheet_id, $sheet_data, $checkbox, $array_kolom) {
            // menghapus semua data pada db
            PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $id)
                ->where('pgdl_sheet_id', $sheet_id)
                ->whereIn('row', $checkbox)
                ->whereIn('kolom', $array_kolom)
                ->delete();

            //dipecah per 1000
            $sheet_chunk = array_chunk($sheet_data, 1000);

            foreach ($sheet_chunk as $chunk_data) {
                PGDLExcelDataRevisi::insert($chunk_data);
            }
        });
        
        // membuat sheet data untuk data yang sequence nya > 0
        $sheet_data_anomali = [];
        foreach ($setting->where('sequence', '>', 0)->sortBy('sequence')->groupBy('sequence') as $row){
            foreach ($row as $value){
                if(in_array($version->pgdl_template->jenis_id, Jenis::FORM_6_10)) {
                    for ($i = 13; $i <= $all_data->max('row'); $i++) {
                        if (!empty($value->query_value)) {
                            $query_str = $this->sql_replace_pgdl($value->query_value, $id, $value->pgdl_sheet_id, $i, $value->kolom);
                            $query = DB::select($query_str);
                            $value_data = (!empty($query[0])) ? $query[0]->value : '';
                            if (!$this->validation_pgdl($value_data, $value->validation, $value->validation_type, $sheet_data_anomali, $value->pgdl_sheet_id, $value->kolom)) {
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
                            $sheet_data_anomali[] = [
                                'pgdl_file_import_revisi_id' => $id,
                                'pgdl_sheet_id' => $value->pgdl_sheet_id,
                                'kolom' => $value->kolom,
                                'row' => $i,
                                'lokasi_id' => $file_import->lokasi_id,
                                'value' => $value_insert,
                                'created_by' => $user_id,
                                'created_at' => date('Y-m-d H:i:s')
                            ];
                        }
                    }
                } else {
                    if (!empty($value->query_value)) {
                        $query_str = $this->sql_replace_pgdl($value->query_value, $id, $value->pgdl_sheet_id, $value->row, $value->kolom);
                        $query = DB::select($query_str);
                        $value_data = (!empty($query[0])) ? $query[0]->value : '';
                        // $lokasi_data = (!empty($query[0])) ? $query[0]->value : '';
                        if (!$this->validation_pgdl($value_data, $value->validation, $value->validation_type, $sheet_data_anomali, $value->pgdl_sheet_id, $value->kolom)) {
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
                        $sheet_data_anomali[] = [
                            'pgdl_file_import_revisi_id' => $id,
                            'pgdl_sheet_id' => $value->pgdl_sheet_id,
                            'kolom' => $value->kolom,
                            'row' => $value->row,
                            'lokasi_id' => $file_import->lokasi_id,
                            'value' => $value_insert,
                            'created_by' => $user_id,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                    }
                }
            }
        }

        if (count($error)) {
            DB::transaction(function() use ($id, $backup_data, $sheet_id, $checkbox, $array_kolom) {
                // PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $id)->delete();
                PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $id)
                    ->where('pgdl_sheet_id', $sheet_id)
                    ->whereIn('row', '>=', 13)
                    ->whereIn('kolom', $array_kolom)
                    ->delete();
                //dipecah per 1000
                $sheet_chunk = array_chunk($backup_data, 1000);

                foreach ($sheet_chunk as $chunk_data) {
                    PGDLExcelDataRevisi::insert($chunk_data);
                }
            });

            $request->session()->flash('error', $error);
            return redirect(route('fileimportpengendalian.editimport', ['version_id' => $version_id, 'id' => $id, 'sheet_id' => $sheet_id]));
        }

        DB::transaction(function() use ($sheet_data_anomali) {
            //dipecah per 1000
            $sheet_chunk = array_chunk($sheet_data_anomali, 1000);

            foreach ($sheet_chunk as $chunk_data) {
                PGDLExcelDataRevisi::insert($chunk_data);
            }
        });

        // update data form RKAU sheet I-LR dan I-CF
        $error = $this->update_icf_ilr_rkau($file_import_rkau);
        // jika error, backup data yang diedit seperti semula
        if (count($error)) {
            DB::transaction(function() use ($id, $backup_data, $sheet_id, $checkbox, $array_kolom) {
                // PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $id)->delete();
                PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $id)
                    ->where('pgdl_sheet_id', $sheet_id)
                    ->where('row', '>=', 13)
                    ->whereIn('kolom', $array_kolom)
                    ->delete();
                //dipecah per 1000
                $sheet_chunk = array_chunk($backup_data, 1000);

                foreach ($sheet_chunk as $chunk_data) {
                    PGDLExcelDataRevisi::insert($chunk_data);
                }
            });

            $request->session()->flash('error', $error);
            return redirect(route('fileimportpengendalian.show', ['version_id' => $version_id, 'id' => $id]));
        } 
        // ambil data yang telah diupdate
        $new_data = PGDLExcelDataRevisi::where('pgdl_file_import_revisi_id', $id)
            ->where('pgdl_sheet_id', $sheet_id)
            ->whereIn('row', $checkbox)
            ->whereIn('kolom', $array_kolom)
            ->get();

        $history_log = $this->get_history_log($new_data, $excel_data, $version->pgdl_template->jenis_id, $id, $checkbox);
        // dd($history_log);
        // insert data history_log ke database pgdl_history_log
        DB::transaction(function() use ($history_log) {
            PgdlHistoryLog::insert($history_log);
        });

        if ($jenis_id == Jenis::FORM_RKAU) {
            $sheet_md = PGDLSheet::find($sheet_id);
            $request->session()->flash('success', 'Data RKAU Sheet '.$sheet_md->name.' berhasil di update!');
        } else {                    
            $request->session()->flash('success', 'Data '.$version->pgdl_template->jenis->name.' berhasil di update!');
        }
        return redirect(route('fileimportpengendalian.show', ['version_id' => $version_id, 'id' => $id]));
    }

    private function update_icf_ilr_rkau($file_import_rkau)
    {
        $error = [];
        $user_id = session('user_id');
        // mendapatkan sheet I-LR dan I-CF yang akan diupdate
        $sheets_rkau = PgdlSheet::where(function($query) use ($file_import_rkau) {
            $query->where('pgdl_version_id', $file_import_rkau->pgdl_version_id)->where('name', 'I-LR');
        })->orWhere(function($query) use ($file_import_rkau) {
            $query->where('pgdl_version_id', $file_import_rkau->pgdl_version_id)->where('name', 'I-CF');
        })->get();
        // dd($file_import_rkau, $sheets_rkau);
        // melakukan pengecekan apakah data pada sheet I-LR dan I-CF lengkap
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
                            $error[] = 'Tidak menemukan data RKAU pada Sheet '.$value->pgdl_sheet->name.' Baris '.$value->row.' Kolom '.$value->kolom;
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
                foreach ($row as $value) {
                    if (!empty($value->query_value)) {
                        $query_str = $this->sql_replace_pgdl($value->query_value, $file_import_rkau->id, $value->pgdl_sheet_id, $value->row, $value->kolom);
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
                            $error[] = 'Kesalahan Rumus RKAU pada Sheet '.$value->pgdl_sheet->name.' Baris '.$value->row.' Kolom '.$value->kolom;
                        }
                    }
                }
            }
        }
        if (count($error)) {
            return $error;
        }
        // menghapus data RKAU yang akan diupdate
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
                    }
                }
            }
        }
        // insert data rkau baru yang telah dihitung
        DB::transaction(function() use ($sheet_data_anomali) {
            //dipecah per 1000
            $sheet_chunk_rkau = array_chunk($sheet_data_anomali, 1000);
            foreach ($sheet_chunk_rkau as $chunk_data_rkau) {
                PGDLExcelDataRevisi::insert($chunk_data_rkau);
            }
        });
        return $error;
    }

    private function get_history_log($new_data, $excel_data, $jenis_id, $file_import_id, $checkbox)
    {
        $user_id = session('user_id');

        $tahun_anggaran = PGDLFileImportRevisi::find($file_import_id)->tahun;

        $setting_log = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', 8)
            ->where('jenis_id', $jenis_id)
            ->where('tahun', $tahun_anggaran)
            ->get();

        $history_log = [];

        foreach ($new_data as $data) {
            if ($data->kolom == $setting_log->where('judul_kolom', 'Nomor PRK')->first()->kolom) {
                $history_log[$data->row]['prk'] = $data->value;
                $history_log[$data->row]['user_id'] = $user_id;
                $history_log[$data->row]['pgdl_file_import_revisi_id'] = $file_import_id;
                $history_log[$data->row]['created_at'] = date('Y-m-d H:i:s');
            // } elseif ($data->kolom == $kolom_keterangan) {
            //     $history_log[$data->row]['keterangan'] = $data->value;
            } elseif ($data->kolom == $setting_log->where('judul_kolom', 'Identity PRK')->first()->kolom && $data->kolom == $setting_log->where('judul_kolom', 'Deskripsi PRK')->first()->kolom) {
                $history_log[$data->row]['identity_prk'] = $history_log[$data->row]['deskripsi_prk_akhir'] = $data->value;
            } elseif (($setting_log->where('judul_kolom', 'Beban')->first() && $data->kolom == $setting_log->where('judul_kolom', 'Beban')->first()->kolom) || ($setting_log->where('judul_kolom', 'Anggaran Proyek')->first() && $data->kolom == $setting_log->where('judul_kolom', 'Anggaran Proyek')->first()->kolom)) {
                $history_log[$data->row]['beban_akhir'] = $data->value;
            } elseif (($setting_log->where('judul_kolom', 'Cashflow')->first() && $data->kolom == $setting_log->where('judul_kolom', 'Cashflow')->first()->kolom) || ($setting_log->where('judul_kolom', 'Anggaran Investasi')->first() && $data->kolom == $setting_log->where('judul_kolom', 'Anggaran Investasi')->first()->kolom)) {
                $history_log[$data->row]['cashflow_akhir'] = $data->value;
            } elseif (($setting_log->where('judul_kolom', 'Ijin Proses')->first() && $data->kolom == $setting_log->where('judul_kolom', 'Ijin Proses')->first()->kolom) || ($setting_log->where('judul_kolom', 'Disburse')->first() && $data->kolom == $setting_log->where('judul_kolom', 'Disburse')->first()->kolom)) {
                $history_log[$data->row]['ijin_proses_akhir'] = $data->value;
            }
        }

        foreach ($excel_data as $data) {
            if ($data->kolom == $setting_log->where('judul_kolom', 'Deskripsi PRK')->first()->kolom) {
                $history_log[$data->row]['deskripsi_prk_awal'] = $data->value;
                if ($jenis_id == 9) {
                    $history_log[$data->row]['identity_prk'] = $history_log[$data->row]['deskripsi_prk_awal'] = $history_log[$data->row]['deskripsi_prk_akhir'] = 'Penyusutan';
                }
            } elseif (($setting_log->where('judul_kolom', 'Beban')->first() && $data->kolom == $setting_log->where('judul_kolom', 'Beban')->first()->kolom) || ($setting_log->where('judul_kolom', 'Anggaran Proyek')->first() && $data->kolom == $setting_log->where('judul_kolom', 'Anggaran Proyek')->first()->kolom)) {
                $history_log[$data->row]['beban_awal'] = $data->value;
            } elseif (($setting_log->where('judul_kolom', 'Cashflow')->first() && $data->kolom == $setting_log->where('judul_kolom', 'Cashflow')->first()->kolom) || ($setting_log->where('judul_kolom', 'Anggaran Investasi')->first() && $data->kolom == $setting_log->where('judul_kolom', 'Anggaran Investasi')->first()->kolom)) {
                $history_log[$data->row]['cashflow_awal'] = $data->value;
            } elseif (($setting_log->where('judul_kolom', 'Ijin Proses')->first() && $data->kolom == $setting_log->where('judul_kolom', 'Ijin Proses')->first()->kolom) || ($setting_log->where('judul_kolom', 'Disburse')->first() && $data->kolom == $setting_log->where('judul_kolom', 'Disburse')->first()->kolom)) {
                $history_log[$data->row]['ijin_proses_awal'] = $data->value;
            } 
        }
        array_multisort($history_log);
        return $history_log;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($version_id, $id)
    {
        $fileImport = PGDLFileImportRevisi::find($id);
        $fase = Fase::all();
        $version = Version::with('template')->where('id', $version_id)->first();
        $array_rutin_id = Template::select('id')->where('jenis_id', Jenis::FORM_6_RUTIN)->get()->toArray();
        $template_6_rutin = Version::with(['template', 'file_imports'])
            ->where('active', 1)
            ->whereIn('template_id', $array_rutin_id)
            ->get();
        $array_reimburse_id = Template::select('id')->where('jenis_id', Jenis::FORM_6_REIMBURSE)->get()->toArray();
        $template_6_reimburse = Version::with(['template', 'file_imports'])
            ->where('active', 1)
            ->whereIn('template_id', $array_reimburse_id)
            ->get();
        $array_pu_id = Template::select('id')->where('jenis_id', Jenis::FORM_10_PU)->get()->toArray();
        $template_10_pu = Version::with(['template', 'file_imports'])
            ->where('active', 1)
            ->whereIn('template_id', $array_pu_id)
            ->get();
        $array_pln_id = Template::select('id')->where('jenis_id', Jenis::FORM_10_PLN)->get()->toArray();
        $template_10_pln = Version::with(['template', 'file_imports'])
            ->where('active', 1)
            ->whereIn('template_id', $array_pln_id)
            ->get();
        $array_penguatankit_id = Template::select('id')->where('jenis_id', Jenis::FORM_10_PENGUATANKIT)->get()->toArray();
        $template_10_penguatankit = Version::with(['template', 'file_imports'])
            ->where('active', 1)
            ->whereIn('template_id', $array_penguatankit_id)
            ->get();
        $array_bahan_bakar_id = Template::select('id')->where('jenis_id', Jenis::FORM_BAHAN_BAKAR)->get()->toArray();
        $template_bahan_bakar = Version::with(['template', 'file_imports'])
            ->where('active', 1)
            ->whereIn('template_id', $array_bahan_bakar_id)
            ->get();
        $array_penyusutan_id = Template::select('id')->where('jenis_id', Jenis::FORM_PENYUSUTAN)->get()->toArray();
        $template_penyusutan = Version::with(['template', 'file_imports'])
            ->where('active', 1)
            ->whereIn('template_id', $array_penyusutan_id)
            ->get();

        $data = [
            'version' => $version,
            'draft' => $fileImport,
            'fase' => $fase,
            'template_6_rutin' => $template_6_rutin,
            'template_6_reimburse' => $template_6_reimburse,
            'template_10_pu' => $template_10_pu,
            'template_10_pln' => $template_10_pln,
            'template_10_penguatankit' => $template_10_penguatankit,
            'template_bahan_bakar' => $template_bahan_bakar,
            'template_penyusutan' => $template_penyusutan,
        ];

        return view('fileimport_pengendalian.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $version_id, $id)
    {
        $this->validate($request, [
            'tahun' => 'required',
            'fase_id' => 'required',
            'draft_versi' => 'required',
        ]);

        $version = Version::find($version_id);

        $file_import = PGDLFileImportRevisi::find($id);

        $file_import->template_id = $version->template_id;
        $file_import->version_id = $version->id;
        $file_import->fase_id = $request->fase_id;
        $file_import->tahun = $request->tahun;
        // $file_import->draft_versi = $request->draft_versi;
        $file_import->form6_rutin_file_import_id = $request->form6_rutin_file_import_id;
        $file_import->form6_reimburse_file_import_id = $request->form6_reimburse_file_import_id;
        $file_import->form10_pln_file_import_id = $request->form10_pln_file_import_id;
        $file_import->form10_pu_file_import_id = $request->form10_pu_file_import_id;
        $file_import->form10_penguatankit_file_import_id = $request->form10_penguatankit_file_import_id;
        $file_import->form_bahan_bakar_file_import_id = $request->form_bahan_bakar_file_import_id;
        $file_import->form_penyusutan_file_import_id = $request->form_penyusutan_file_import_id;

        if($file_import->save()){
            $request->session()->flash('success', 'Data berhasil di update!');
        }

        return redirect(route('template.show', ['jenis_id' => $version->template->jenis_id, 'id' => $version->template_id]));
    }

    public function export_use($version_id, $id)
    {
        if ( !is_numeric($version_id)||!is_numeric($id)) {
            # code...
            Session::flash('failed', 'versi sheet pengendalian tidak ditemukan');
            return redirect('pagenotfound');  
            // return redirect()->back();  
        }
        // die;
        $sheet_md = PgdlSheet::where('pgdl_version_id', $version_id)->get();
       
        if (count($sheet_md)<1) {
            # code...
            Session::flash('failed', 'versi sheet pengendalian tidak ditemukan');
            // session(['message' => 'Sheet Version tidak ditemukan']);
            // return redirect()->back();  
            return redirect('pagenotfound');  
            
        }
        // dd($sheet_md);
        $data = [
            'version' => $version_id,
            'sheet' => $sheet_md,
            'id' => $id,
        ];
        // dd("asa");

        return view('fileimport_pengendalian.use_export', $data);
    }

    public function export(Request $request, $version_id, $id)
    {
        ini_set('max_execution_time', (20*(60*60)));

        $this->validate($request, [
            'sheet' => 'required',
        ]);
        // dd("halo");
        /* parameter id file import atau parameter id version tidak berupa number, redirect ke halaman not found*/
        if(is_numeric($id) == false || is_numeric($version_id) == false) {
            return redirect('pagenotfound');
        }

        /* check file import apakah ada di db */
        $fileImport = PGDLFileImportRevisi::find($id);

        /* file import tidak ditemukan di db, redirect ke page not found */
        if($fileImport == null) {
            return redirect('pagenotfound');
        }

        /* check versi apakah ada di db */
        $version = PgdlVersion::where('id', $version_id)->first();
        
        /* version tidak ditemukan di db, redirect ke page not found */
        if($version == null) {
            return redirect('pagenotfound');
        }

        /* casting list id sheet yang direquest ke datatype integer */
        $sheet = $request->sheet;
        foreach ($sheet as $key => $id_sheet) {
            $sheet[$key] = intval($id_sheet);
        }
        // dd($sheet);

        $templateId = $version->pgdl_template_id;
        $template = PgdlTemplate::where("id", $templateId)->first();
        // dd($template);
        $jenisId = $template->jenis_id;
        $jenis = Jenis::where("id", $jenisId)->first();
        // dd($jenis);

        $fileExport = $jenis->name.' - '.date("d-m-Y"); //nama file yg di export

        $run = Artisan::call('export_pgdl:store', [
            'id' => $id, 'filename' => $fileExport, 'sheet' => $sheet
        ]);

        // $contents = Storage::get();

        return response()->download(storage_path('exports/'.$fileExport.'.xlsx'));

        // $version = Version::where('id', $version_id)->first();

        // $sheet_md = Sheet::with('excel_datas')->whereIn('id', $request->sheet)->get();

        // $filePath = $version->file;

        // $reader = ReaderFactory::create(Type::XLSX);

        // $reader->open($filePath);

        // $sheet_use = [];

        // $excel_data = ExcelData::where('file_import_id', $id)
        //     ->get();

        // foreach ($sheet_md as $row){
        //     $sheet_use[] = $row->name;
        //     $sheet_id[] = $row->id;
        // }

        // $setting = SheetSetting::whereIn('sheet_id', $sheet_id)
        //     ->orderBy('row', 'asc')
        //     ->orderBy('kolom', 'asc')
        //     ->get();

        // $setting_group = $setting->groupBy('sheet_id');

        // $sheet_name = [];
        // $setting_set = [];
        // foreach ($sheet_md as $row){
        //     if(!empty($setting_group[$row->id])){
        //         $setting_set[$row->name] = collect($setting_group[$row->id]);
        //         $sheet_name[$row->name] = $row->id;
        //     } else {
        //         $setting_set[$row->name] = collect([]);
        //         $sheet_name[$row->name] = $row->id;
        //     }
        // }

        // $sheet_data = [];
        // foreach ($reader->getSheetIterator() as $sheet) {
        //     if(in_array($sheet->getName(), $sheet_use)) {
        //         $i = 1;
        //         $array_data = [];
        //         $sheet_setting_set = $setting_set[$sheet->getName()];
        //         $sheet_id_data = '';
        //         foreach ($sheet_setting_set as $value){
        //             $array_data[] = [$value->kolom, $value->row];

        //             $sheet_id_data = $value->sheet_id;
        //         }

        //         foreach ($sheet->getRowIterator() as $row) {
        //             $array_value = [];
        //             $j = 'A';
        //             foreach ($row as $row2){
        //                 if(in_array([$j, $i], $array_data)) {
        //                     $excel_val = $excel_data->where('sheet_id', $sheet_id_data)->where('row', $i)->where('kolom', $j)->first();
        //                     if($excel_val){
        //                         $excel_value = $excel_val->value;
        //                         if(is_numeric($excel_val->value)){
        //                             if($excel_val->value > 1000){
        //                                 $excel_value = (float)$excel_val->value;
        //                             }
        //                         }
        //                         $array_value[] = $excel_value;
        //                     } else {
        //                         $array_value[] = '?';
        //                     }
        //                 } else {
        //                     $array_value[] = $row2;
        //                 }

        //                 $j++;
        //             }
        //             $sheet_data[$sheet->getName()][] = $array_value;
        //             $i++;
        //         }
        //     }
        // }

        // $reader->close();

        // $fileExport = $version->template->jenis->name.' - '.date("d-m-Y"); //nama file yg di export

        // Excel::create($fileExport, function($excel) use ($sheet_use, $sheet_data, $setting_set) {
        //     foreach ($sheet_use as $value){
        //         $setting_cell = $setting_set[$value];
        //         $sheet_data_use = $sheet_data[$value];
        //         $excel->sheet($value, function($sheet) use ($sheet_data_use, $setting_cell) {
        //             $i = 1;
        //             foreach ($sheet_data_use as $row){
        //                 $j = 'A';
        //                 foreach ($row as $kolom){
        //                     $setting_cell_color = $setting_cell->where('row', $i)->where('kolom', $j)->first();
        //                     $sheet->cell($j.$i, function($cell) use ($kolom, $setting_cell_color) {
        //                         $cell->setValue($kolom);
        //                         if($setting_cell_color){
        //                             $cell->setBackground('#'.$setting_cell_color->color);
        //                         }
        //                     });
        //                     $j++;
        //                 }
        //                 $i++;
        //             }
        //         });
        //     }
        // })->export('xlsx');

        // return redirect(route('fileimport.export.use', ['version_id' => $version->id, 'id' => $id]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function multiexplode ($string)
    {
        $delimiters = ['+', '-', '*', '/','(',')'];
        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    }

    public function getValue($cell, $data)
    {
        $numbers = preg_replace('/[^0-9]/', '', $cell);
        $letters = preg_replace('/[^a-zA-Z]/', '', $cell);
        $data = $data->where('kolom', $letters)->where('row', $numbers)->first();
        return ($data['value'])?:$cell;
    }

    public function download(Request $request, $version_id, $id)
    {
      $version = Version::where('id', $version_id)->first();

      return response()->download('coba');
    }

    // Controller Pengendalian

    // Untuk Update Excel

    public function updatedata($version_id, $id)
    {
        $version = PGDLVersion::with('pgdl_template.jenis')
            ->where('id', $version_id)->first();


        $sheet_md = PGDLSheet::with(['pgdl_excel_datas_revisi' => function ($query) use ($id) {
            $query->where('pgdl_file_import_revisi_id', $id);
        }])->where('pgdl_version_id', $version_id)->get();

        if(!$version || !$sheet_md ){
            return redirect('pagenotfound');
        }


        $fileimport = PGDLFileImportRevisi::find($id);

        $data = [
            'version' => $version,
            'sheet_md' => $sheet_md,
            'fileimport' => $fileimport,
            'id' => $id,
        ];

        $user_id = session('user_id');
        $user = User::find($user_id);
        $role_id = session('role_id');
        $role = Role::find($role_id);

        //kantor pusat --- SEMENTARA dari permintaan Pak Hisyam, 27 Des 17 supaya Unit tidak upload lagi
        if($role->is_kantor_pusat) {
          return view('fileimport_pengendalian.update_show', $data);
        }
        else {
          return redirect('/');
        }
    }

    public function import_use_update_excel(Request $request, $version_id, $id)
    {
        // dd($id);
        $validator = Validator::make($request->all(), [
            'file'  => 'required|in:xlsx,xls',
        ]);

        $extensions = array("xls","xlsx","xlm","xla","xlc","xlt","xlw");

        $result = array($request->file('file')->getClientOriginalExtension());

        if(in_array($result[0],$extensions)){

        // dd('1');

        $reader = ReaderFactory::create(Type::XLSX);

        $file = $request->file('file');
        $destinationPath = 'update data pgdl/'.$id;
        $filename= $file->getClientOriginalName();
        $fileimport = PGDLFileImportRevisi::find($id);
        // dd($fileimport);
        $fileimport->file_update = ('/'.$destinationPath.'/'.$filename);
        $request->file('file')->move($destinationPath, $filename);
        $fileimport->save();


        $destinationPath_temp = "temp";
        $filename_temp = 'temp.'.$file->getClientOriginalExtension();
        $copy = copy($destinationPath.'/'.$filename, $destinationPath_temp.'/'.$filename_temp);

        $reader->open($destinationPath_temp.'/'.$filename_temp);

        $sheet_data = [];
        foreach ($reader->getSheetIterator() as $sheet) {
            $sheet_data[] = $sheet->getName();
        }

        $data = [
            'version' => $version_id,
            'sheet' => $sheet_data,
            'id' => $id,
        ];

        return view('fileimport_pengendalian.update_use', $data);
        
        } else {
            return back()->with('salah', 'File Yang Di-upload Harus Excel');
        }
    }

    public function import_update_excel(Request $request, $version_id, $id)
    {
        // dd($id);
        $user_id = session('user_id');

        ini_set('max_execution_time', ((20*(60*60))));

        $validator = Validator::make($request->all(), [
            'sheet' => 'required',
        ]);

        $tahun = DB::table('pgdl_versions')
            ->join('pgdl_templates', 'pgdl_templates.id', '=', 'pgdl_versions.pgdl_template_id')
            ->where('pgdl_versions.id', $version_id )
            ->first();

        // dd($tahun);


        $run = Artisan::call('import:insert_update_excel_pgdl', [
            'id' => $id, 'sheet' => $request->sheet, 'user' => $user_id, 'tahun' => $tahun->tahun
        ]);

        // $fileimport = PGDLFileImportRevisi::find($id);
        // $template_id = $fileimport->pgdl_template_id;
        // $template = PGDLTemplate::find($template_id);
        // $jenis_id = $template->jenis_id;
        $fileimport = PGDLFileImportRevisi::find($id);
        // dd($fileimport);
        $template_id = $fileimport->template_id;
        // dd($template_id);
        $template = PGDLTemplate::where('template_id', $template_id)->first();
        // dd($template);
        $jenis_id = $template->jenis_id;
        return redirect(route('templatepengendalian.show', ['jenis_id' => $jenis_id, 'id' => $template->id]))->with('success', 'Berhasil Dirubah');
    }

    // Untuk Add Data ke excel

    public function addatanull(){
        return redirect('pagenotfound');
    }

    public function adddata($version_id, $id)
    {
        

        
        $version = PGDLVersion::with('pgdl_template.jenis')
            ->where('id', $version_id)->first();

        $sheet_md = PGDLSheet::with(['pgdl_excel_datas_revisi' => function ($query) use ($id) {
            $query->where('pgdl_file_import_revisi_id', $id);
        }])->where('pgdl_version_id', $version_id)->get();

        if(!$version || !$sheet_md){
            return redirect('pagenotfound');
        }

        $fileimport = PGDLFileImportRevisi::find($id);

        $data = [
            'version' => $version,
            'sheet_md' => $sheet_md,
            'fileimport' => $fileimport,
            'id' => $id,
        ];

        $user_id = session('user_id');
        $user = User::find($user_id);
        $role_id = session('role_id');
        $role = Role::find($role_id);

        //kantor pusat --- SEMENTARA dari permintaan Pak Hisyam, 27 Des 17 supaya Unit tidak upload lagi
        if($role->is_kantor_pusat) {
          return view('fileimport_pengendalian.add_show', $data);
        }
        else {
          return redirect('/');
        }
    }

    public function import_use_add_excel(Request $request, $version_id, $id)
    {
        $validator = Validator::make($request->all(), [
            'file'  => 'required|in:xlsx,xls',
        ]);

        $extensions = array("xls","xlsx","xlm","xla","xlc","xlt","xlw");

        $result = array($request->file('file')->getClientOriginalExtension());

        if(in_array($result[0],$extensions)){

        $reader = ReaderFactory::create(Type::XLSX);

        $file = $request->file('file');
        $destinationPath = 'add data pgdl/'.$id;
        $filename= $file->getClientOriginalName();
        $fileimport = PGDLFileImportRevisi::find($id);
        $fileimport->file_add = ('/'.$destinationPath.'/'.$filename);
        $request->file('file')->move($destinationPath, $filename);
        $fileimport->save();


        $destinationPath_temp = "temp";
        $filename_temp = 'temp.'.$file->getClientOriginalExtension();
        $copy = copy($destinationPath.'/'.$filename, $destinationPath_temp.'/'.$filename_temp);

        $reader->open($destinationPath_temp.'/'.$filename_temp);

        $sheet_data = [];
        foreach ($reader->getSheetIterator() as $sheet) {
            $sheet_data[] = $sheet->getName();
        }

        $data = [
            'version' => $version_id,
            'sheet' => $sheet_data,
            'id' => $id,
        ];

        return view('fileimport_pengendalian.add_use', $data);

        } else {
            
            return back()->with('salah', 'File Yang Di-upload Harus Excel');

        }
    }

    public function import_add_excel(Request $request, $version_id, $id)
    {
      $user_id = session('user_id');

      ini_set('max_execution_time', ((20*(60*60))));

      $validator = Validator::make($request->all(), [
            'sheet' => 'required',
      ]);

      $tahun = DB::table('pgdl_versions')
            ->join('pgdl_templates', 'pgdl_templates.id', '=', 'pgdl_versions.pgdl_template_id')
            ->where('pgdl_versions.id', $version_id )
            ->first();

      $run = Artisan::call('import:insert_add_excel_pgdl', [
          'id' => $id,
          'sheet' => $request->sheet,
          'user' => $user_id,
          'tahun' => $tahun->tahun
      ]);

      $fileimport = PGDLFileImportRevisi::find($id);
      // dd($fileimport);
      $template_id = $fileimport->template_id;
      // dd($template_id);
      $template = PGDLTemplate::where('template_id', $template_id)->first();
      // dd($template);
      $jenis_id = $template->jenis_id;
      return redirect(route('templatepengendalian.show', ['jenis_id' => $jenis_id, 'id' => $template->id]))->with('success', 'Berhasil Ditambah');;
    }



}
