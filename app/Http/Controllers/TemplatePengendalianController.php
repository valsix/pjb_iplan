<?php

namespace App\Http\Controllers;
// use Illuminate\Support\Facades\Redirect;
use App\Entities\Fase;
use App\Entities\FileImport;
use App\Entities\FileImportKetetapan;
use App\Entities\Jenis;
use App\Entities\Template;
use App\Entities\PgdlTemplate;
use App\Entities\PgdlVersion;
use App\Entities\PgdlSheet;
use App\Entities\Version;
use App\Entities\StatusUpload;
use App\Entities\User;
use App\Entities\Role;
use App\Entities\ExcelData;
use App\Entities\PGDLFileImportRevisi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Redirect;
use Validator;


class TemplatePengendalianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $jenis_id)
    {
        if($jenis_id>9 || $jenis_id<1 )
        {
            return redirect('pagenotfound');

        }
        // dd("dd")
        $jenis = Jenis::find($jenis_id);

        $templates = PgdlTemplate::with('pgdl_file_imports_revisi')
            ->where('jenis_id', $jenis_id);

        if($request->tahun){
            $templates = $templates->where('tahun', $request->tahun);
        }

        $templates = $templates->get();

        if (count($templates)) {

          foreach ($templates as $t) {
            $template_id = $t->id;
          }

        $version = PgdlVersion::select('id')->where('pgdl_template_id', $template_id)->first();

        } else{

          $version = null;

        }

        $data = [
            'templates' => $templates,
            'no' => 0,
            'jenis' => $jenis,
            'version' => $version,
        ];


        return view('template_pengendalian.index', $data);
    }

    public function null()
    {
        return redirect(route('templatepengendalian.index', 1));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($jenis_id)
    {
        $jenis = Jenis::find($jenis_id);
        $data = [
            'jenis' => $jenis,
            'jenis_id' => $jenis_id,
        ];

        return view('template_pengendalian.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $jenis_id)
    {
        $this->validate($request, [
            'tahun' => 'required',
            'file' => 'required|file',
        ]);

        $template = New Template();

        $template->tahun = $request->tahun;
        $template->jenis_id = $jenis_id;
        $template->active = 1;

        $file = $request->file('file');
        $destinationPath = "files";
        $filename= $file->getClientOriginalName();
        $request->file('file')->move($destinationPath, $filename);
        $template->file = $destinationPath.'/'.$filename;

        $version = New Version();

        $version->versi = '1';
        $version->file = $destinationPath.'/'.$filename;
        $version->active = 1;

        $transaction = DB::transaction(function() use ($template, $version){
            $template->save();
            $version->template_id = $template->id;
            $version->save();
        });
        if($transaction){
            $request->session()->flash('success', 'Data berhasil di buat!');
        }

        return redirect(route('templatepengendalian.index', $jenis_id));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $jenis_id, $id)
    {
        $user_id = session('user_id');
        $user = User::find($user_id);
        $role_id = session('role_id');
        $role = Role::find($role_id);
        // dd('adsa');
        $version = PGDLVersion::with(['pgdl_sheet', 'pgdl_template.jenis'])
            ->where('pgdl_template_id', $id)
            ->where('active', 1)
            ->first();
            // dd($version);
        if(!$version)
        {
            return redirect()->action(
                'TemplatePengendalianController@index', ['jenis_id' => $jenis_id]
            );
        }
        // $proses = DB::table('file_imports')->select('file_imports.id')
        // ->join('excel_datas', 'excel_datas.file_import_id', 'file_imports.id')
        // ->groupBy('file_imports.id')->count();
        //
        // $total = DB::table('file_imports')->select('file_imports.id')
        // ->join('versions', 'file_imports.version_id', '=' ,'versions.id')
        // ->join('sheets', 'versions.id', '=' ,'sheets.version_id')
        // ->join('sheet_settings', 'sheets.id', '=' ,'sheet_settings.sheet_id')
        // ->where('sheets.name', 'like', 'I-LR')
        // ->orWhere('sheets.name', 'like', 'I-CF')
        // ->orWhere('sheets.name', 'like', 'I-Rencana Kinerja')
        // ->orWhere('sheets.name', 'like', 'I-Pendapatan')
        // ->orWhere('sheets.name', 'like', 'I-PEG')
        // ->orWhere('sheets.name', 'like', 'I-ADM')
        // ->orWhere('sheets.name', 'like', 'I-Pendukung EP')
        // ->orWhere('sheets.name', 'like', 'I-BIAYA USAHA LAINNYA')
        // ->orWhere('sheets.name', 'like', 'I-DILUAR USAHA')
        // ->groupBy('file_imports.id')->count();
        // dd($version->id);
        if($role->is_kantor_pusat) {
            $fileImport = PGDLFileImportRevisi::with('fase')->where('pgdl_version_id', $version->id)->orderBy('id');
        }
        else {
            $fileImport = PGDLFileImportRevisi::with('fase')->where('pgdl_version_id', $version->id)->where('distrik_id', $user->distrik_id)->orderBy('id');
        }
        // dd($fileImport->get());
        if($request->date){
            $date = New Carbon($request->date);
            if($role->is_kantor_pusat) {
                $fileImport = $fileImport->whereRaw("draft_versi::text ILIKE '%".$date->toDateString()."%'");
            }
            else {
                $fileImport = $fileImport->whereRaw("draft_versi::text ILIKE '%".$date->toDateString()."%'")->where('distrik_id', $user->distrik_id);
            }
        }
        // dd($fileImport->get());

        $fileImport = $fileImport->get();
        // dd($fileImport);
        $data = [
            'version' => $version,
            'draft' => $fileImport,
            'no' => 0,
            // 'proses' => $proses,
            // 'total' => $total,
        ];

        // dd($data);

        return view('template_pengendalian.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($jenis_id, $id)
    {
        $id_template = PGDLTemplate::pluck('id')->toArray();
        if(!in_array($id,$id_template))
        {
            return redirect('pagenotfound');
        }

        $template = PGDLTemplate::find($id);
        $version = (new PGDLVersion())->where('pgdl_template_id', $template->id)->get();
        $data = [
            'template' => $template,
            'versions' => $version,
            'jenis_id' => $jenis_id,
        ];

        return view('template_pengendalian.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $jenis_id, $id)
    {
        $this->validate($request, [
            'tahun' => 'required',
            'file' => 'required|file',
        ]);

        // Pakai ini

        $extensions = array("xls","xlsx","xlm","xla","xlc","xlt","xlw");

        $result = array($request->file('file')->getClientOriginalExtension());

        if(in_array($result[0],$extensions)){

        // $i= 0;
        $template = PGDLTemplate::find($id);
        // dd($template);
        $template->tahun = $request->tahun;
        $template->jenis_id = $jenis_id;
        $template->active = 1;
        $template->save();

        // dd($version->file);

        // $version_data = Version::where('template_id', $id);

        // $version = New Version();

        // $version->versi = $version_data->get()->count()+1;
        // $version->file = $template->file;
        // $version->active = 1;

        // $transaction = DB::transaction(function() use ($template, $version, $version_data){
        $transaction = DB::transaction(function() use ($template, $request, $id){
          // dd('1');
          if ($request->hasFile('file') && $request->file('file')->isValid()) {
              $file = $request->file('file');
              $destinationPath = 'pgdl_files/'.$template->id;
              $filename = $file->getClientOriginalName();
              $request->file('file')->move($destinationPath, $filename);
              $template->file = $destinationPath . '/' . $filename;

              $version = PGDLVersion::where('pgdl_template_id', $id)->first();
              $version->file = $template->file;
          }
          // dd($version, $template);

          $template->save();
          $version->save();
          $i = 1;
          // $version_data->update(['active' => 0]);
          // $version->template_id = $template->id;
          // $version->save();
        });
        // if($transaction){
        //     $request->session()->flash('success', 'Data berhasil di buat!');
        // }

        return redirect(route('templatepengendalian.index', $jenis_id))->with('success', 'Data berhasil di update!');

        } else {

            return back()->with('salah', 'File Yang Di-upload Harus Excel');

        }
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

    public function setting($jenis_id, $id)
    {
      $template = PGDLTemplate::find($id);
      $version = (new PGDLVersion())->where('pgdl_template_id', $template->id)->get();
      // dd($version);
      $data = [
          'template' => $template,
          'versions' => $version,
          'jenis_id' => $jenis_id,
      ];
      return view('template_pengendalian.setting', $data);
    }

    public function store_setting(Request $request, $jenis_id, $id)
    {

    }
}
