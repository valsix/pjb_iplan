<?php

namespace App\Http\Controllers;

use App\Entities\Distrik;
use App\Entities\ExcelData;
use App\Entities\Fase;
use App\Entities\FileImport;
use App\Entities\FileImportKetetapan;
use App\Entities\PGDLFileImportRevisi;
use App\Entities\FileApproval;
use App\Entities\Approval;
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

class FileImportController extends Controller
{
    use ValidationExcelTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($version_id, $id)
    {
        $version = Version::with('template.jenis')
            ->where('id', $version_id)->first();

        $sheet_md = Sheet::with(['excel_datas' => function ($query) use ($id) {
            $query->where('file_import_id', $id);
        }])->where('version_id', $version_id)->get();

        $fileimport = FileImport::find($id);

        $user_id = session('user_id');
        $user = User::find($user_id);
        $role_id = $user->current_id_roles;
        $role = Role::find($role_id);

        $cek_distrik = $fileimport->distrik_id;
        $cek_tahun = $fileimport->tahun;

        $fa = FileApproval::where('file_import_id', $fileimport->id)->first();
        $cek_fase = Approval::find($fa->approval_id)->fase_id;

        //cek jika ada draft yg sudah final
        $draft_final =
            DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
            from file_imports f
            join templates t on f.template_id = t.id
            join excel_datas e on e.file_import_id = f.id
            join file_approval fa on fa.file_import_id = f.id
            join file_approval_status fas on fas.id = fa.file_approval_status_id
            join approval app on app.id = fa.approval_id
            where t.jenis_id= " . $version->template->jenis_id . "
            and f.distrik_id = " . $cek_distrik . " and t.tahun= " . $cek_tahun . "
            and app.fase_id = 3
            and fa.file_approval_status_id = 4
            and app.urutan = (select max(urutan) from approval where approval.fase_id = 3)
            group by f.id, f.draft_versi, f.name, fas.name");

        //jika ada draft yg sudah diapproved pada fase tsb
        $draft_approved_selected_fase =
            DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
            from file_imports f
            join templates t on f.template_id = t.id
            join excel_datas e on e.file_import_id = f.id
            join file_approval fa on fa.file_import_id = f.id
            join file_approval_status fas on fas.id = fa.file_approval_status_id
            join approval app on app.id = fa.approval_id
            where t.jenis_id= " . $version->template->jenis_id . "
            and f.distrik_id = " . $cek_distrik . " and t.tahun= " . $cek_tahun . "
            and app.fase_id = " . $cek_fase . "
            and (fa.file_approval_status_id = 3 or fa.file_approval_status_id = 4)
            group by f.id, f.draft_versi, f.name, fas.name");

        if ($draft_approved_selected_fase) {
            $draft_is_approved = true;
        } else {
            $draft_is_approved = false;
        }

        // default disable upload
        $tombol_upload = false;

        if (empty($draft_final)) {
            //fase 1, yang boleh upload hanya Staff Unit
            if ($cek_fase == 1) {
                if ($role_id == 2) {
                    if (!$draft_is_approved) {
                        $tombol_upload = true;
                    }
                }
            }

            //fase 2, yang boleh upload hanya Staff Anggaran & Uploader Pembahasan Teknis
            else if ($cek_fase == 2) {
                if ($role_id == 5 || $role_id == 20) {
                    if (!$draft_is_approved) {
                        $tombol_upload = true;
                    }
                }
            }

            //fase 3, yang boleh upload hanya Staff Anggaran & Uploader Ketetapan
            else if ($cek_fase == 3) {
                if ($role_id == 5 || $role_id == 21) {
                    if (!$draft_is_approved) {
                        $tombol_upload = true;
                    }
                }
            }

            //fase interchange, Staff Anggaran selalu bisa upload selama draft pada alur normal belum final
            else if ($cek_fase == 4) {
                if ($role_id == 5) {
                    $tombol_upload = true;
                }
            }
        }

        $data = [
            'version' => $version,
            'sheet_md' => $sheet_md,
            'fileimport' => $fileimport,
            'id' => $id,
            'tombol_upload' => $tombol_upload,
        ];

        return view('fileimport.show', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($version_id)
    {
        $user_id = session('user_id');
        $user = User::find($user_id);
        $role_id = session('role_id');
        $role = Role::find($role_id);

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
        
        //kantor pusat
        if ($role->is_kantor_pusat) {
            $sb = StrategiBisnis::all();
        } else {
            $sb = StrategiBisnis::where('id', $user->distrik->strategi_bisnis->id)->get();
        }

        $data = [
            'user' => $user,
            'role' => $role,
            'version' => $version,
            'sb' => $sb,
            'fase' => $fase,
            'template_6_rutin' => $template_6_rutin,
            'template_6_reimburse' => $template_6_reimburse,
            'template_10_pu' => $template_10_pu,
            'template_10_pln' => $template_10_pln,
            'template_10_penguatankit' => $template_10_penguatankit,
            'template_bahan_bakar' => $template_bahan_bakar,
            'template_penyusutan' => $template_penyusutan,
        ];

        return view('fileimport.create', $data);
    }

    public function ajax_distrik($id)
    {
        $user_id = session('user_id');
        $user = User::find($user_id);
        $role_id = session('role_id');
        $role = Role::find($role_id);

        //kantor pusat
        if ($role->is_kantor_pusat) {
            $ds = Distrik::where('strategi_bisnis_id', $id)->select("name", "id")->get();
        } else {
            $ds = Distrik::where('id', $user->distrik_id)->select("name", "id")->get();
        }

        return json_encode($ds);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $version_id)
    {
        $user_id = session('user_id');
        $user = User::find($user_id);
        $role_id = session('role_id');
        $role = Role::find($role_id);

        //kantor pusat
        if ($role->is_kantor_pusat) {
            $this->validate($request, [
                'tahun' => 'required',
                'fase_id' => 'required',
                'draft_versi' => 'required',
                'name' => 'required',
                'strategi_bisnis' => 'required',
                'distrik' => 'required',
            ]);
        } else {
            $this->validate($request, [
                'tahun' => 'required',
                'fase_id' => 'required',
                'draft_versi' => 'required',
                'name' => 'required',
            ]);
        }

        $version = Version::with('template')->where('id', $version_id)->first();

        if ($role->is_kantor_pusat) {
            $cek_distrik = $request->distrik;
        } else {
            $cek_distrik = $user->distrik_id;
        }

        //cek jika ada draft yg sudah final
        $draft_final =
            DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
            from file_imports f 
            join templates t on f.template_id = t.id
            join excel_datas e on e.file_import_id = f.id
            join file_approval fa on fa.file_import_id = f.id
            join file_approval_status fas on fas.id = fa.file_approval_status_id
            join approval app on app.id = fa.approval_id
            where t.jenis_id= " . $version->template->jenis_id . " 
            and f.distrik_id = " . $cek_distrik . " and t.tahun= " . $request->tahun . "
            and app.fase_id = 3
            and fa.file_approval_status_id = 4
            and app.urutan = (select max(urutan) from approval where approval.fase_id = 3)
            group by f.id, f.draft_versi, f.name, fas.name");

        //jika ada draft yg sudah diapproved pada fase tsb
        if ($request->fase_id == 4) {
            //// Jika fase interchange, walaupun sudah ada yg di-approve masih bisa submit

            $draft_approved_selected_fase = [];
        } else {
            //// Untuk alur normal, nilai file_approval_status_id = (3, 4)

            $draft_approved_selected_fase =
                DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
                from file_imports f
                join templates t on f.template_id = t.id
                join excel_datas e on e.file_import_id = f.id
                join file_approval fa on fa.file_import_id = f.id
                join file_approval_status fas on fas.id = fa.file_approval_status_id
                join approval app on app.id = fa.approval_id
                where t.jenis_id= " . $version->template->jenis_id . "
                and f.distrik_id = " . $cek_distrik . " and t.tahun= " . $request->tahun . "
                and app.fase_id = " . $request->fase_id . "
                and (fa.file_approval_status_id = 3 or fa.file_approval_status_id = 4)
                group by f.id, f.draft_versi, f.name, fas.name");
        }

        //tidak boleh tambah draft
        if ($draft_final || $draft_approved_selected_fase) {
            return redirect()->back()->withErrors('Maaf, Anda tidak diperkenankan menambahkan draft karena sudah ada draft yang disetujui.');
        } else {
            $file_import = new FileImport();

            $file_import->template_id = $version->template_id;
            $file_import->version_id = $version->id;
            $file_import->fase_id = $request->fase_id;
            $file_import->tahun = $request->tahun;
            $file_import->name = $request->name;
            $file_import->draft_versi = date('Y-m-d H:i:s');
            $file_import->form6_rutin_file_import_id = $request->form6_rutin_file_import_id;
            $file_import->form6_reimburse_file_import_id = $request->form6_reimburse_file_import_id;
            $file_import->form10_pln_file_import_id = $request->form10_pln_file_import_id;
            $file_import->form10_pu_file_import_id = $request->form10_pu_file_import_id;
            $file_import->form10_penguatankit_file_import_id = $request->form10_penguatankit_file_import_id;
            $file_import->form_bahan_bakar_file_import_id = $request->form_bahan_bakar_file_import_id;
            $file_import->form_penyusutan_file_import_id = $request->form_penyusutan_file_import_id;
            if ($role->is_kantor_pusat) {
                $file_import->distrik_id = $request->distrik;
            } else {
                $file_import->distrik_id = $user->distrik_id;
            }
            $file_import->created_by = $user_id;

            if ($file_import->save()) {
                $file_approval = new FileApproval();

                $file_approval->tahun_anggaran = $request->tahun;

                $approval = Approval::where('fase_id', $request->fase_id)->orderBy('urutan', 'asc')->first();
                //approval id = approval pertama pada fase tersebut.
                $file_approval->approval_id = $approval->id;
                //approval by = di approved oleh grup id ... pada fase pertama
                $file_approval->approval_by = $approval->role_id;

                $file_approval->file_import_id = $file_import->id;
                $file_approval->jenis_id = $version->template->jenis_id;
                $file_approval->file_approval_status_id = 1; //drafted

                //created by= dibuat oleh user id ...
                $file_approval->created_by = $user_id;
                if ($file_approval->save()) {
                    $request->session()->flash('success', 'Data berhasil di buat!');
                }
            }

            return redirect(route('template.show', ['jenis_id' => $version->template->jenis_id, 'id' => $version->template_id]));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_sheet($version_id, $id, $sheet_id)
    {
        if (!is_numeric($id) or !is_numeric($version_id) or !is_numeric($sheet_id)) {
            return redirect('pagenotfound');
        }
        ini_set('max_execution_time', 300);
        $version = Version::with('template.jenis')
            ->where('id', $version_id)->first();
        if (!$version) {
            return redirect('pagenotfound');
        }

        $sheet_md = Sheet::with(['excel_datas' => function ($query) use ($id) {
            $query->where('file_import_id', $id);
        }])->where('version_id', $version_id)
            ->where('id', $sheet_id)
            ->first();
        if (!$sheet_md) {
            return redirect('pagenotfound');
        }

        $fileimport = FileImport::find($id);
        if (!$fileimport) {
            return redirect('pagenotfound');
        }

        $cek_sheet_data = $sheet_md->excel_datas->count();

        $filePath = $version->file;

        $reader = ReaderFactory::create(Type::XLSX);

        $reader->open($filePath);

        $excel_data = ExcelData::where('file_import_id', $id)
            ->where('sheet_id', $sheet_id)
            ->get();

        $setting = SheetSetting::where('sheet_id', $sheet_md->id)
            ->orderBy('row', 'asc')
            ->orderBy('kolom', 'asc')
            ->get();

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

                    $sheet_id_data = $value->sheet_id;
                }
                $limit_row = 13;
                $template_2_3_empty = true;
                if (in_array($version->template->jenis_id, Jenis::FORM_6_10)) {
                    if ($excel_data->max('row')) {
                        $limit_row = $excel_data->max('row');
                        $template_2_3_empty = false;
                    }
                }
                $count_row2 = 0;
                foreach ($sheet->getRowIterator() as $row) {
                    if ((in_array($version->template->jenis_id, Jenis::FORM_6_10)) && $i > 13) {
                        break;
                    }
                    $array_value = [];
                    if ($i >= 8 && array_filter($row)) {
                        $j = 'A';
                        if ($i >= 13) {
                            if (in_array($version->template->jenis_id, Jenis::FORM_6_10)) {
                                if ($template_2_3_empty) {
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
                                        $excel_val = $excel_data->where('sheet_id', $sheet_id_data)->where('row', $i)->where('kolom', $j)->first();
                                        if ($excel_val) {
                                            $excel_value = $excel_val->value;
                                            if (is_numeric($excel_val->value)) {
                                                if ($excel_val->value > 1000) {
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
                                    if ($max_kolom <= $j) {
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
                if (in_array($version->template->jenis_id, Jenis::FORM_6_10)) {
                    for ($i = 13; $i <= $limit_row; $i++) {
                        $j = 'A';
                        $array_value = [];
                        for ($k = 0; $k < $count_row2; $k++) {
                            if (in_array([$j, 13], $array_data)) {

                                $excel_val = $excel_data->where('sheet_id', $sheet_id_data)->where('row', $i)->where('kolom', $j)->first();
                                if ($excel_val) {
                                    $excel_value = $excel_val->value;
                                    if (is_numeric($excel_val->value)) {
                                        if ($excel_val->value > 1000) {
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
                            //20190315 by FFR - perbaikan error geser EP firman
                            else {
                                $array_value[] = '';
                            }
                            $j++;
                            if ($max_kolom <= $j) {
                                $max_kolom = $j;
                            }
                        }
                        $sheet_data[] = $array_value;
                    }
                }
            }
        }

        $blak_kolom = [];
        for ($i = 'A'; $i <= $max_kolom; $i++) {
            $blak_kolom[] = '';
        }

        $header_data[] = $blak_kolom;

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

        return view('fileimport.show_sheet', $data);
    }

    public function edit_import($version_id, $id, $sheet_id)
    {
        if (!is_numeric($version_id) or !is_numeric($id) or !is_numeric($sheet_id)) {
            return redirect('pagenotfound');
        }
        ini_set('max_execution_time', -1);
        $version = Version::with('template')->where('id', $version_id)->first();
        if (!$version) {
            return redirect('pagenotfound');
        }

        $sheet_md = Sheet::where('id', $sheet_id)->first();
        if (!$sheet_md) {
            return redirect('pagenotfound');
        }

        $filePath = $version->file;

        $reader = ReaderFactory::create(Type::XLSX);

        $reader->open($filePath);

        $excel_data = ExcelData::where('file_import_id', $id)
            ->where('sheet_id', $sheet_id)
            ->get();
        if (!$excel_data) {
            return redirect('pagenotfound');
        }

        $setting = SheetSetting::where('sheet_id', $sheet_md->id)
            ->orderBy('row', 'asc')
            ->orderBy('kolom', 'asc')
            ->get();

        $sheet_data = [];
        $array_updatable = [];
        $array_updatable_id = [];
        $k = 1;
        foreach ($reader->getSheetIterator() as $sheet) {
            if ($sheet->getName() == $sheet_md->name) {
                $i = 1;
                $limit_row = 13;
                if (in_array($version->template->jenis_id, Jenis::FORM_6_10)) {
                    if ($excel_data->max('row')) {
                        $limit_row = $excel_data->max('row');
                    }
                }
                $count_row2 = 0;
                foreach ($sheet->getRowIterator() as $row) {
                    if ((in_array($version->template->jenis_id, Jenis::FORM_6_10)) && $i > 13) {
                        break;
                    }
                    $array_value = [];
                    if ($i >= 8 && array_filter($row)) {
                        $j = 'A';
                        if ($i >= 13) {
                            if (in_array($version->template->jenis_id, Jenis::FORM_6_10)) {
                                $count_row2 = count($row);
                            } else {
                                foreach ($row as $row2) {
                                    if ($sheet_setting = $setting->where('row', $i)->where('kolom', $j)->first()) {
                                        $excel_val = $excel_data->where('sheet_id', $sheet_setting->sheet_id)->where('row', $i)->where('kolom', $j)->first();
                                        if (empty($sheet_setting->query_value) && ($sheet_setting->editable == 1 || Auth::user()->role == 'admin')) {
                                            if ($excel_val) {
                                                $array_value[] = $excel_val->value;
                                            } else {
                                                $array_value[] = '?';
                                            }
                                            $array_updatable[] = $k;
                                            $array_updatable_id[$k] = $excel_val->id;
                                        } else {
                                            $excel_value = $excel_val->value;
                                            if (is_numeric($excel_val->value)) {
                                                $excel_value = number_format($excel_val->value);
                                                if ($excel_val->value < 0) {
                                                    $excel_value = "(" . number_format(abs($excel_val->value)) . ")";
                                                }
                                            }
                                            $array_value[] = $excel_value;
                                        }
                                    } else {
                                        $array_value[] = $row2;
                                    }
                                    $k++;
                                    $j++;
                                }
                            }
                        } else {
                            $array_value = $row;
                            $k += count($row);
                        }
                    }
                    $sheet_data[] = $array_value;
                    $i++;
                }

                if (in_array($version->template->jenis_id, Jenis::FORM_6_10)) {
                    for ($i = 13; $i <= $limit_row; $i++) {
                        $j = 'A';
                        $array_value = [];
                        for ($l = 0; $l < $count_row2; $l++) {
                            if ($sheet_setting = $setting->where('row', 13)->where('kolom', $j)->first()) {
                                $excel_val = $excel_data->where('sheet_id', $sheet_setting->sheet_id)->where('row', $i)->where('kolom', $j)->first();
                                if (empty($sheet_setting->query_value)) {
                                    if ($excel_val) {
                                        $array_value[] = $excel_val->value;
                                    } else {
                                        $array_value[] = '?';
                                    }
                                    $array_updatable[] = $k;
                                    $array_updatable_id[$k] = $excel_val->id;
                                } else {
                                    $array_value[] = $sheet_setting->query_value;
                                }
                            } else {
                                $array_value[] = '';
                            }
                            $j++;
                            $k++;
                        }
                        $sheet_data[] = $array_value;
                    }
                }
            }
        }

        $updatable = [
            'updatable' => $array_updatable,
            'updatable_id' => $array_updatable_id,
        ];

        $data = [
            'version' => $version,
            'sheet_md' => $sheet_md,
            'sheet' => $sheet_data,
            'updatable' => $updatable,
            'id' => $id,
            'sheet_id' => $sheet_id,
        ];

        $reader->close();

        return view('fileimport.edit_import', $data);
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
        $destinationPath = 'entry data/' . $id;
        $filename = $file->getClientOriginalName();
        $fileimport = FileImport::find($id);
        $fileimport->file = ('/' . $destinationPath . '/' . $filename);
        $request->file('file')->move($destinationPath, $filename);
        $fileimport->save();


        $destinationPath_temp = "temp";
        $filename_temp = 'temp.' . $file->getClientOriginalExtension();
        $copy = copy($destinationPath . '/' . $filename, $destinationPath_temp . '/' . $filename_temp);

        $reader->open($destinationPath_temp . '/' . $filename_temp);

        $sheet_data = [];
        foreach ($reader->getSheetIterator() as $sheet) {
            $sheet_data[] = $sheet->getName();
        }

        $data = [
            'version' => $version_id,
            'sheet' => $sheet_data,
            'id' => $id,
        ];

        return view('fileimport.use', $data);
    }

    public function import(Request $request, $version_id, $id)
    {
        ini_set('max_execution_time', ((20 * (60 * 60))));

        $fileimport = FileImport::find($id);

        $validator = Validator::make($request->all(), [
            'sheet' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route('fileimport.show', ['version_id' => $version_id, 'id' => $id]))
                ->withErrors($validator)
                ->withInput();
        }

        // cleanup untuk interchage
        if ($fileimport->fase_id == 4) {
            $this->cleanup_interchange_excel_data($id);
        }

        $run = Artisan::call('import:insert', [
            'id' => $id, 'sheet' => $request->sheet
        ]);

        if ($fileimport->lokasi_id) {
            $fileimport->distrik_id = $fileimport->lokasi->distrik->id;
            $fileimport->save();
        }

        $template_id = $fileimport->template_id;
        $template = Template::find($template_id);
        $jenis_id = $template->jenis_id;
        return redirect(route('template.show', ['jenis_id' => $jenis_id, 'id' => $template_id]));
    }

    private function cleanup_interchange_excel_data($file_import_id)
    {
        DB::transaction(function () use ($file_import_id) {
            //// Cleanup dari operasi duplicate:data
            // - pgdl_excel_datas_revisi
            // - excel_datas_ketetapan
            // - pgdl_file_imports_revisi
            // - file_imports_ketetapan

            $fik_id = FileImportKetetapan::where('file_import_id', $file_import_id)->get()->pluck('id');
            $pgdl_fi_id = PGDLFileImportRevisi::whereIn('file_import_ketetapan_id', $fik_id)->get()->pluck('id');

            if ($fik_id) {
                DB::table('excel_datas_ketetapan')
                    ->whereIn('file_import_ketetapan_id', $fik_id)
                    ->delete();

                FileImportKetetapan::whereIn('id', $fik_id)->delete();
            }

            if ($pgdl_fi_id) {
                DB::table('pgdl_excel_datas_revisi')
                    ->whereIn('pgdl_file_import_revisi_id', $pgdl_fi_id)
                    ->delete();

                PGDLFileImportRevisi::whereIn('id', $pgdl_fi_id)->delete();
            }

            //// Cleanup dari operasi import:insert
            ExcelData::where('file_import_id', $file_import_id)->delete();


            //// Reset approval menjadi drafted
            $first_approval = Approval::where('fase_id', 4)->orderBy('urutan', 'asc')->first();
            $first_file_approval = FileApproval::where('file_import_id', $file_import_id)
                ->orderBy('created_at', 'asc')
                ->first();
            // reset file_approval pertama sebagai draft
            $first_file_approval->approval_id = $first_approval->id;
            $first_file_approval->approval_by = $first_approval->role_id;
            $first_file_approval->file_approval_status_id = 1;
            $first_file_approval->save();

            // delete all file approval but the first one
            FileApproval::where('file_import_id', $file_import_id)
                ->where('approval_id', '!=', $first_approval->id)
                ->delete();
            // reset latest_approval_id pada file_approval yang disisakan
            FileApproval::where('file_import_id', $file_import_id)
                ->update(['latest_approval_id' => null]);
        });
    }

    public function import_update(Request $request, $version_id, $id, $sheet_id)
    {
        ini_set('max_execution_time', -1);
        $this->validate($request, [
            'keterangan' => 'required',
        ]);
        $user_id = session('user_id');
        // $file_import = FileImport::find($id);
        $version = Version::with('template')->where('id', $version_id)->first();

        $update = $request->update;
        $checkbox = $request->change;
        if (!$checkbox) {
            $request->session()->flash('fail', 'Tidak Ada Perubahan Data!');
            return redirect(route('fileimport.show', ['version_id' => $version_id, 'id' => $id]));
        }

        $excel_data = ExcelData::where('file_import_id', $id)
            ->where('sheet_id', $sheet_id)
            ->whereIn('row', $checkbox)
            ->get();

        $backup_data = [];
        foreach ($excel_data as $row) {
            $backup_data[] = [
                'file_import_id' => $row->id,
                'sheet_id' => $row->sheet_id,
                'lokasi_id' => $row->lokasi_id,
                'kolom' => $row->kolom,
                'row' => $row->row,
                'value' => $row->value,
            ];
        }

        $setting = SheetSetting::where('sheet_id', $sheet_id)->get();

        $sheet_data = [];
        $error = [];

        foreach ($excel_data as $row) {
            if (in_array($version->template->jenis_id, Jenis::FORM_6_10)) {
                if ($sheet_setting = $setting->where('row', 13)->where('kolom', $row->kolom)->where('sequence', 0)->first()) {
                    if ($this->validation($update[$row['id']], $sheet_setting->validation, $sheet_setting->validation_type, $sheet_data, $row->sheet_id, $row->kolom)) {
                        $value = $update[$row['id']];
                        if ($sheet_setting->validation_type == 'numeric') {
                            $value = (int) $update[$row['id']];
                        }
                        if ($sheet_setting->validation_type == 'string') {
                            $value = (string) $update[$row['id']];
                        }
                        $sheet_data[] = [
                            'file_import_id' => $row->file_import_id,
                            'sheet_id' => $row->sheet_id,
                            'lokasi_id' => $row->lokasi_id,
                            'kolom' => $row->kolom,
                            'row' => $row->row,
                            'value' => $value,
                            'created_by' => $user_id,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                    } else {
                        if ($sheet_setting->validation_type == 'unique') {
                            $error[] = 'Data ' . $row->kolom . $row->row . ' Tidak boleh Duplikasi!';
                        } else {
                            $error[] = 'Data ' . $row->kolom . $row->row . ' Tidak Sesuai!';
                        }
                    }
                }
            } else {
                if ($sheet_setting = $setting->where('row', $row->row)->where('kolom', $row->kolom)->where('sequence', 0)->first()) {
                    if ($this->validation($update[$row['id']], $sheet_setting->validation, $sheet_setting->validation_type, $sheet_data, $row->sheet_id, $row->kolom)) {
                        $value = $update[$row['id']];
                        if ($sheet_setting->validation_type == 'numeric') {
                            $value = (int) $update[$row['id']];
                        }
                        if ($sheet_setting->validation_type == 'string') {
                            $value = (string) $update[$row['id']];
                        }
                        $sheet_data[] = [
                            'file_import_id' => $row->file_import_id,
                            'sheet_id' => $row->sheet_id,
                            'lokasi_id' => $row->lokasi_id,
                            'kolom' => $row->kolom,
                            'row' => $row->row,
                            'value' => $value,
                            'created_by' => $user_id,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                    } else {
                        if ($sheet_setting->validation_type == 'unique') {
                            $error[] = 'Data ' . $row->kolom . $row->row . ' Tidak boleh Duplikasi!';
                        } else {
                            $error[] = 'Data ' . $row->kolom . $row->row . ' Tidak Sesuai!';
                        }
                    }
                }
            }
        }

        DB::transaction(function () use ($id, $sheet_id, $sheet_data, $checkbox) {
            ExcelData::where('file_import_id', $id)
                ->where('sheet_id', $sheet_id)
                ->whereIn('row', $checkbox)
                ->delete();

            //dipecah per 1000
            $sheet_chunk = array_chunk($sheet_data, 1000);

            foreach ($sheet_chunk as $chunk_data) {
                ExcelData::insert($chunk_data);
            }

            // ExcelData::insert($sheet_data);
        });

        $sheet_data_anomali = [];
        foreach ($setting->where('sequence', '>', 0)->sortBy('sequence')->groupBy('sequence') as $row) {
            $sheet_data = [];
            foreach ($row as $value) {
                if (in_array($version->template->jenis_id, Jenis::FORM_6_10)) {
                    for ($i = 13; $i <= $excel_data->max('row'); $i++) {
                        if (!empty($value->query_value)) {
                            $query_str = $this->sql_replace($value->query_value, $id, $value->sheet_id, $i, $value->kolom);
                            $query = DB::select($query_str);
                            $value_data = (!empty($query[0])) ? $query[0]->value : '';
                            if (!$this->validation($value_data, $value->validation, $value->validation_type, $sheet_data_anomali, $value->sheet_id, $value->kolom)) {
                                if ($value->validation_type == 'unique') {
                                    $error[] = 'Data sheet ' . $value->sheet->name . ' ' . $value->kolom . $i . ' Tidak Boleh Duplikasi!';
                                } else {
                                    $error[] = 'Data sheet ' . $value->sheet->name . ' ' . $value->kolom . $i . ' Tidak Sesuai!';
                                }
                            }
                            $value_insert = $value_data;
                            if ($value->validation_type == 'numeric') {
                                $value_insert = (int) $value_data;
                            }
                            if ($value->validation_type == 'string') {
                                $value_insert = (string) $value_data;
                            }
                            $sheet_data_anomali[] = [
                                'file_import_id' => $id,
                                'sheet_id' => $value->sheet_id,
                                'kolom' => $value->kolom,
                                'row' => $i,
                                'lokasi_id' => $excel_data->where('file_import_id', $id)
                                    ->where('sheet_id', $value->sheet_id)
                                    ->where('row', $i)
                                    ->where('kolom', $value->kolom)
                                    ->first()->lokasi_id,
                                'value' => $value_insert,
                                'created_by' => $user_id,
                                'created_at' => date('Y-m-d H:i:s')
                            ];
                        }
                    }
                } else {
                    if (!empty($value->query_value)) {
                        $query_str = $this->sql_replace($value->query_value, $id, $value->sheet_id, $value->row, $value->kolom);
                        $query = DB::select($query_str);
                        $value_data = (!empty($query[0])) ? $query[0]->value : '';
                        if (!$this->validation($value_data, $value->validation, $value->validation_type, $sheet_data_anomali, $value->sheet_id, $value->kolom)) {
                            if ($value->validation_type == 'unique') {
                                $error[] = 'Data sheet ' . $value->sheet->name . ' ' . $value->kolom . $value->row . ' Tidak Boleh Duplikasi!';
                            } else {
                                $error[] = 'Data sheet ' . $value->sheet->name . ' ' . $value->kolom . $value->row . ' Tidak Sesuai!';
                            }
                        }
                        $value_insert = $value_data;
                        if ($value->validation_type == 'numeric') {
                            $value_insert = (int) $value_data;
                        }
                        if ($value->validation_type == 'string') {
                            $value_insert = (string) $value_data;
                        }
                        $sheet_data_anomali[] = [
                            'file_import_id' => $id,
                            'sheet_id' => $value->sheet_id,
                            'kolom' => $value->kolom,
                            'row' => $value->row,
                            'lokasi_id' => $excel_data->where('file_import_id', $id)
                                ->where('sheet_id', $value->sheet_id)
                                ->where('row', $value->row)
                                ->where('kolom', $value->kolom)
                                ->first()->lokasi_id,
                            'value' => $value_insert,
                            'created_by' => $user_id,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                    }
                }
            }

            DB::transaction(function () use ($sheet_data_anomali) {
                //dipecah per 1000
                $sheet_chunk = array_chunk($sheet_data_anomali, 1000);

                foreach ($sheet_chunk as $chunk_data) {
                    ExcelData::insert($chunk_data);
                }
            });
        }

        if (count($error)) {

            DB::transaction(function () use ($id, $backup_data, $sheet_id, $checkbox) {
                ExcelData::where('file_import_id', $id)
                    ->where('sheet_id', $sheet_id)
                    ->whereIn('row', $checkbox)
                    ->delete();

                //dipecah per 1000
                $sheet_chunk = array_chunk($backup_data, 1000);

                foreach ($sheet_chunk as $chunk_data) {
                    ExcelData::insert($chunk_data);
                }

                // ExcelData::insert($fail_data);
            });

            $request->session()->flash('error', $error);
            return redirect(route('fileimport.editimport', ['version_id' => $version_id, 'id' => $id, 'sheet_id' => $sheet_id]));
        } else {
            // $this->update_icf_ilr_rkau($version->template->jenis_id, $file_import);

            $history = new History();
            $history->file_import_id = $id;
            $history->sheet_id = $sheet_id;
            $history->user_id = $user_id;
            $history->keterangan = $request->keterangan;
            $history->save();

            $request->session()->flash('success', 'Data berhasil di update!');
        }

        return redirect(route('fileimport.show', ['version_id' => $version_id, 'id' => $id]));
    }

    private function update_icf_ilr_rkau($jenis_id, $file_import)
    {
        // mendapatkan data file import pengendalian rkau
        if ($jenis_id == Jenis::FORM_6_REIMBURSE) {
            $file_import_rkau = FileImport::where('form6_reimburse_file_import_id', $file_import->id)->where('distrik_id', $file_import->distrik_id)->first();
        } elseif ($jenis_id == Jenis::FORM_6_RUTIN) {
            $file_import_rkau = FileImport::where('form6_rutin_file_import_id', $file_import->id)->where('distrik_id', $file_import->distrik_id)->first();
        } elseif ($jenis_id == Jenis::FORM_10_PU) {
            $file_import_rkau = FileImport::where('form10_pu_file_import_id', $file_import->id)->where('distrik_id', $file_import->distrik_id)->first();
        } elseif ($jenis_id == Jenis::FORM_10_PENGUATANKIT) {
            $file_import_rkau = FileImport::where('form10_penguatankit_file_import_id', $file_import->id)->where('distrik_id', $file_import->distrik_id)->first();
        } elseif ($jenis_id == Jenis::FORM_10_PLN) {
            $file_import_rkau = FileImport::where('form10_pln_file_import_id', $file_import->id)->where('distrik_id', $file_import->distrik_id)->first();
        } elseif ($jenis_id == Jenis::FORM_BAHAN_BAKAR) {
            $file_import_rkau = FileImport::where('form_bahan_bakar_file_import_id', $file_import->id)->where('distrik_id', $file_import->distrik_id)->first();
        } elseif ($jenis_id == Jenis::FORM_PENYUSUTAN) {
            $file_import_rkau = FileImport::where('form_penyusutan_file_import_id', $file_import->id)->where('distrik_id', $file_import->distrik_id)->first();
        }

        if (!$file_import_rkau) {
            return;
        }

        // mendapatkan sheet I-LR dan I-CF yang akan diupdate
        $sheets_rkau = Sheet::where(function ($query) use ($file_import_rkau) {
            $query->where('version_id', $file_import_rkau->version_id)->where('name', 'I-LR');
        })->orWhere(function ($query) use ($file_import_rkau) {
            $query->where('version_id', $file_import_rkau->version_id)->where('name', 'I-CF');
        })->get();
        // dd($file_import_rkau, $sheets_rkau);
        // melakukan perulangan per sheet untuk update data sheet
        foreach ($sheets_rkau as $sheet) {
            $setting = SheetSetting::where('sheet_id', $sheet->id)->get();
            $sheet_data_anomali = [];
            foreach ($setting->where('sequence', '>', 0)->sortBy('sequence')->groupBy('sequence') as $row) {
                // dd($row);
                foreach ($row as $value) {
                    if (!empty($value->query_value)) {
                        // dd($file_import_rkau->id);
                        $query_str = $this->sql_replace($value->query_value, $file_import_rkau->id, $value->sheet_id, $value->row, $value->kolom);
                        // dd($value->query_value, $query_str);
                        $query = DB::select($query_str);
                        // dd($query_str, $query);
                        $value_data = (!empty($query[0])) ? $query[0]->value : '';
                        // $lokasi_data = (!empty($query[0])) ? $query[0]->value : '';
                        if (!$this->validation($value_data, $value->validation, $value->validation_type, $sheet_data_anomali, $value->sheet_id, $value->kolom)) {
                            if ($value->validation_type == 'unique') {
                                $error[] = 'Data sheet ' . $value->sheet->name . ' ' . $value->kolom . $value->row . ' Tidak Boleh Duplikasi!';
                            } else {
                                $error[] = 'Data sheet ' . $value->sheet->name . ' ' . $value->kolom . $value->row . ' Tidak Sesuai!';
                            }
                        }
                        $value_insert = $value_data;
                        if ($value->validation_type == 'numeric') {
                            $value_insert = (int) $value_data;
                        }
                        if ($value->validation_type == 'string') {
                            $value_insert = (string) $value_data;
                        }
                        $lokasi_id = ExcelData::where('file_import_id', $file_import_rkau->id)
                            ->where('sheet_id', $value->sheet_id)
                            ->where('row', $value->row)
                            ->where('kolom', $value->kolom)
                            ->first()->lokasi_id;
                        ExcelData::where('file_import_id', $file_import_rkau->id)
                            ->where('sheet_id', $value->sheet_id)
                            ->where('row', $value->row)
                            ->where('kolom', $value->kolom)
                            ->where('lokasi_id', $lokasi_id)
                            ->update([
                                'value' => $value_insert,
                                'updated_by' => Auth::id(),
                                'updated_at' => date('Y-m-d H:i:s')
                            ]);
                        $sheet_data_anomali[] = [
                            'file_import_id' => $file_import_rkau->id,
                            'sheet_id' => $value->sheet_id,
                            'kolom' => $value->kolom,
                            'row' => $value->row,
                            'lokasi_id' => $lokasi_id,
                            'value' => $value_insert,
                        ];
                    }
                }
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($version_id, $id)
    {
        $user_id = session('user_id');
        $user = User::find($user_id);
        $role_id = session('role_id');
        $role = Role::find($role_id);

        $fileImport = FileImport::find($id);
        $fase = Fase::all();
        $version = Version::with('template')->where('id', $version_id)->first();

        // Form 6 Unit untuk di view File Import Edit
        $form6_rutin_file_import_id = FileImport::where('id', $fileImport->form6_rutin_file_import_id)->get();
        if ($form6_rutin_file_import_id->isEmpty()) {
            $template_6_rutin = '-';
        } else {
            foreach ($form6_rutin_file_import_id as $template_6_rutin) {
                $template_6_rutin = $template_6_rutin->distrik->name . ' - ' . $template_6_rutin->draft_versi . ' - ' . $template_6_rutin->name;
            }
        }

        // Form 6 Reimburse untuk di view File Import Edit
        $form6_reimburse_file_import_id = FileImport::where('id', $fileImport->form6_reimburse_file_import_id)->get();
        if ($form6_reimburse_file_import_id->isEmpty()) {
            $template_6_reimburse = '-';
        } else {
            foreach ($form6_reimburse_file_import_id as $template_6_reimburse) {
                $template_6_reimburse = $template_6_reimburse->distrik->name . ' - ' . $template_6_reimburse->draft_versi . ' - ' . $template_6_reimburse->name;
            }
        }

        // Form 10 PU untuk di view File Import Edit
        $form10_pu_file_import_id = FileImport::where('id', $fileImport->form10_pu_file_import_id)->get();
        if ($form10_pu_file_import_id->isEmpty()) {
            $template_10_pu = '-';
        } else {
            foreach ($form10_pu_file_import_id as $template_10_pu) {
                $template_10_pu = $template_10_pu->distrik->name . ' - ' . $template_10_pu->draft_versi . ' - ' . $template_10_pu->name;
            }
        }

        // $array_pu_id = Template::select('id')->where('jenis_id', Jenis::FORM_10_PU)->get()->toArray();
        // $template_10_pu = Version::with(['template', 'file_imports'])
        //     ->where('active', 1)
        //     ->whereIn('template_id', $array_pu_id)
        //     ->get();

        // Form 10 PLN untuk di view File Import Edit
        $form10_pln_file_import_id = FileImport::where('id', $fileImport->form10_pln_file_import_id)->get();
        if ($form10_pln_file_import_id->isEmpty()) {
            $template_10_pln = '-';
        } else {
            foreach ($form10_pln_file_import_id as $template_10_pln) {
                $template_10_pln = $template_10_pln->distrik->name . ' - ' . $template_10_pln->draft_versi . ' - ' . $template_10_pln->name;
            }
        }

        // $array_pln_id = Template::select('id')->where('jenis_id', Jenis::FORM_10_PLN)->get()->toArray();
        // $template_10_pln = Version::with(['template', 'file_imports'])
        //     ->where('active', 1)
        //     ->whereIn('template_id', $array_pln_id)
        //     ->get();

        // Form 10 Penguatan Kit untuk di view File Import Edit
        $form10_penguatankit_file_import_id = FileImport::where('id', $fileImport->form10_penguatankit_file_import_id)->get();
        if ($form10_penguatankit_file_import_id->isEmpty()) {
            $template_10_penguatankit = '-';
        } else {
            foreach ($form10_penguatankit_file_import_id as $template_10_penguatankit) {
                $template_10_penguatankit = $template_10_penguatankit->distrik->name . ' - ' . $template_10_penguatankit->draft_versi . ' - ' . $template_10_penguatankit->name;
            }
        }

        // $array_penguatankit_id = Template::select('id')->where('jenis_id', Jenis::FORM_10_PENGUATANKIT)->get()->toArray();
        // $template_10_penguatankit = Version::with(['template', 'file_imports'])
        //     ->where('active', 1)
        //     ->whereIn('template_id', $array_penguatankit_id)
        //     ->get();

        // Form Bahan Bakar untuk di view File Import Edit
        $form_bahan_bakar_file_import_id = FileImport::where('id', $fileImport->form_bahan_bakar_file_import_id)->get();
        if ($form_bahan_bakar_file_import_id->isEmpty()) {
            $template_bahan_bakar = '-';
        } else {
            foreach ($form_bahan_bakar_file_import_id as $template_bahan_bakar) {
                $template_bahan_bakar = $template_bahan_bakar->distrik->name . ' - ' . $template_bahan_bakar->draft_versi . ' - ' . $template_bahan_bakar->name;
            }
        }

        // $array_bahan_bakar_id = Template::select('id')->where('jenis_id', Jenis::FORM_BAHAN_BAKAR)->get()->toArray();
        // $template_bahan_bakar = Version::with(['template', 'file_imports'])
        //     ->where('active', 1)
        //     ->whereIn('template_id', $array_bahan_bakar_id)
        //     ->get();

        // Form Penyusutan untuk di view File Import Edit
        $form_penyusutan_file_import_id = FileImport::where('id', $fileImport->form_penyusutan_file_import_id)->get();
        if ($form_penyusutan_file_import_id->isEmpty()) {
            $template_penyusutan = '-';
        } else {
            foreach ($form_penyusutan_file_import_id as $template_penyusutan) {
                $template_penyusutan = $template_penyusutan->distrik->name . ' - ' . $template_penyusutan->draft_versi . ' - ' . $template_penyusutan->name;
            }
        }

        // $array_penyusutan_id = Template::select('id')->where('jenis_id', Jenis::FORM_PENYUSUTAN)->get()->toArray();
        // $template_penyusutan = Version::with(['template', 'file_imports'])
        //     ->where('active', 1)
        //     ->whereIn('template_id', $array_penyusutan_id)
        //     ->get();

        //kantor pusat
        if ($role->is_kantor_pusat) {
            $sb = StrategiBisnis::all();
        } else {
            $sb = StrategiBisnis::where('id', $user->distrik->strategi_bisnis->id)->get();
        }

        $data = [
            'user' => $user,
            'role' => $role,
            'version' => $version,
            'draft' => $fileImport,
            'sb' => $sb,
            'fase' => $fase,
            'template_6_rutin' => $template_6_rutin,
            'template_6_reimburse' => $template_6_reimburse,
            'template_10_pu' => $template_10_pu,
            'template_10_pln' => $template_10_pln,
            'template_10_penguatankit' => $template_10_penguatankit,
            'template_bahan_bakar' => $template_bahan_bakar,
            'template_penyusutan' => $template_penyusutan,
        ];

        return view('fileimport.edit', $data);
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

        $file_import = FileImport::find($id);

        $file_import->name = $request->name;
        // $file_import->template_id = $version->template_id;
        // $file_import->version_id = $version->id;
        // $file_import->fase_id = $request->fase_id;
        // $file_import->tahun = $request->tahun;
        // // $file_import->draft_versi = $request->draft_versi;
        // $file_import->form6_rutin_file_import_id = $request->form6_rutin_file_import_id;
        // $file_import->form6_reimburse_file_import_id = $request->form6_reimburse_file_import_id;
        // $file_import->form10_pln_file_import_id = $request->form10_pln_file_import_id;
        // $file_import->form10_pu_file_import_id = $request->form10_pu_file_import_id;
        // $file_import->form10_penguatankit_file_import_id = $request->form10_penguatankit_file_import_id;
        // $file_import->form_bahan_bakar_file_import_id = $request->form_bahan_bakar_file_import_id;
        // $file_import->form_penyusutan_file_import_id = $request->form_penyusutan_file_import_id;

        if ($file_import->save()) {
            $request->session()->flash('success', 'Data berhasil di update!');
        }

        return redirect(route('template.show', ['jenis_id' => $version->template->jenis_id, 'id' => $version->template_id]));
    }
    public function export_use_null()
    {
        return redirect('pagenotfound');
    }

    public function export_use($version_id, $id)
    {
        /* version id: 8, id: 5 */
        $sheet_md = Sheet::where('version_id', $version_id)->get();
        if (!$sheet_md) {
            return redirect('pagenotfound');
        }
        $data = [
            'version' => $version_id,
            'sheet' => $sheet_md,
            'id' => $id,
        ];

        return view('fileimport.use_export', $data);
    }

    public function export(Request $request, $version_id, $id)
    {
        // echo "sheet: <br/>\n";
        // print_r($request->sheet);
        // exit();
        ini_set('max_execution_time', (20 * (60 * 60)));

        $this->validate($request, [
            'sheet' => 'required',
        ]);

        /* jika parameter request tidak sesuai dengan ketentuan, redirect ke halaman page not found */
        if (is_numeric($id) == false || is_numeric($version_id) == false) {
            return redirect("pagenotfound");
        }

        /* check apakah file import ada di db */
        $fileImport = FileImport::find($id);
        if ($fileImport == null) {
            /* file import tidak ada di db */
            return redirect("pagenotfound");
        }

        /* check apakah version ada di db */
        $version = Version::where('id', $version_id)->first();
        if ($version == null) {
            /* version tidak ada di db */
            return redirect("pagenotfound");
        }

        /*casting id sheet yang direquest ke datatype integer*/
        $sheet = $request->sheet;
        if (is_array($sheet) == false) {
            $sheet = (array) $sheet;
        }
        foreach ($sheet as $key => $id_sheet) {
            $sheet[$key] = intval($id_sheet);
        }

        $fileExport = $version->template->jenis->name . ' - ' . date("d-m-Y"); //nama file yg di export

        $run = Artisan::call('export:store', [
            'id' => $id, 'filename' => $fileExport, 'sheet' => $sheet
        ]);

        // dd($run);
        // $contents = Storage::get();

        return response()->download(storage_path('exports/' . $fileExport . '.xlsx'));

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

    public function multiexplode($string)
    {
        $delimiters = ['+', '-', '*', '/', '(', ')'];
        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    }

    public function getValue($cell, $data)
    {
        $numbers = preg_replace('/[^0-9]/', '', $cell);
        $letters = preg_replace('/[^a-zA-Z]/', '', $cell);
        $data = $data->where('kolom', $letters)->where('row', $numbers)->first();
        return ($data['value']) ?: $cell;
    }

    public function download(Request $request, $version_id, $id)
    {
        $version = Version::where('id', $version_id)->first();

        return response()->download('coba');
    }
}
