<?php

namespace App\Http\Controllers;

use App\Entities\Fase;
use App\Entities\FileImport;
use App\Entities\Jenis;
use App\Entities\Template;
use App\Entities\Version;
use App\Entities\StatusUpload;
use App\Entities\User;
use App\Entities\Role;
use App\Entities\ExcelData;
use App\Entities\Lokasi;
use App\Entities\LokasiJenis;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $jenis_id)
    {
        $user_id = session('user_id');
        $user = User::find($user_id);
        $role_id = session('role_id');
        $role = Role::find($role_id);

        $jenis = Jenis::find($jenis_id);
        $templates = Template::with('file_imports')
            ->where('jenis_id', $jenis_id);

        if($request->tahun){
            $templates = $templates->where('tahun', $request->tahun);
        }

        //sementara, dari Pak Hisyam, 5 Maret 2018, karena tahun anggaran 2018 belum selesai approval, jadi yang ditampilkan hanya tahun anggaran 2019.
        if(!$role->is_kantor_pusat) {
            $templates->where('tahun', '>=', '2019');
        }

        $templates = $templates->get();

        $data = [
            'templates' => $templates,
            'no' => 0,
            'jenis' => $jenis,
        ];

        return view('template.index', $data);
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

        return view('template.create', $data);
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

         $cektahun = Template::where('jenis_id', $jenis_id)->where('tahun', $request->tahun)->get();

         if (count($cektahun)) {
             return back()->with('message', 'Tahun anggaran sudah ada. ');
         }
         else {
           $template = New Template();

           $template->tahun = $request->tahun;
           $template->jenis_id = $jenis_id;
           $template->active = 1;
           $template->file = 'temp';

           $version = New Version();

           $transaction = DB::transaction(function() use ($template, $request, $version){
               $template->save();

               $file = $request->file('file');
               $destinationPath = 'files/'.$template->id;
               $filename= $file->getClientOriginalName();
               $request->file('file')->move($destinationPath, $filename);
               $template->file = $destinationPath.'/'.$filename;
               $template->save();

               $version->versi = '1';
               $version->file = $destinationPath.'/'.$filename;
               $version->active = 1;

               $version->template_id = $template->id;
               $version->save();
           });


           if($transaction){
               $request->session()->flash('success', 'Data berhasil di buat!');
           }

           return redirect(route('template.index', $jenis_id));
         }
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

        $lokasi = Lokasi::all()->where('distrik_id', $user->distrik_id);
        $print = 0;
        if(count($lokasi) == 1)
        {
            $lokasi = Lokasi::all()->where('distrik_id', $user->distrik_id)->first();
            $formslists =  LokasiJenis::all()->where('lokasi_id', $lokasi->id);
            foreach ($formslists as $fl) {
                if($fl->jenis_id ==  $jenis_id)
                {
                    $print = 1;
                    break;
                }
            }
        }
        else
        {
            $print = 1;
        }

        $version = Version::with(['sheets', 'template.jenis'])
            ->where('template_id', $id)
            ->where('active', 1)
            ->first();

        $proses = DB::table('file_imports')->select('file_imports.id')
        ->join('excel_datas', 'excel_datas.file_import_id', 'file_imports.id')
        ->groupBy('file_imports.id')->count();

        $total = DB::table('file_imports')->select('file_imports.id')
        ->join('versions', 'file_imports.version_id', '=' ,'versions.id')
        ->join('sheets', 'versions.id', '=' ,'sheets.version_id')
        ->join('sheet_settings', 'sheets.id', '=' ,'sheet_settings.sheet_id')
        ->where('sheets.name', 'like', 'I-LR')
        ->orWhere('sheets.name', 'like', 'I-CF')
        ->orWhere('sheets.name', 'like', 'I-Rencana Kinerja')
        ->orWhere('sheets.name', 'like', 'I-Pendapatan')
        ->orWhere('sheets.name', 'like', 'I-PEG')
        ->orWhere('sheets.name', 'like', 'I-ADM')
        ->orWhere('sheets.name', 'like', 'I-Pendukung EP')
        ->orWhere('sheets.name', 'like', 'I-BIAYA USAHA LAINNYA')
        ->orWhere('sheets.name', 'like', 'I-DILUAR USAHA')
        ->groupBy('file_imports.id')->count();

        if($role->is_kantor_pusat) {
            $fileImport = FileImport::with('fase')->where('version_id', $version->id)->orderBy('id');
        }
        else {
            // $fileImport = FileImport::with('fase')->where('version_id', $version->id)->where('distrik_id', $user->distrik_id)->orderBy('id');

            //sementara, dari Pak Hisyam, 5 Maret 2018, karena tahun anggaran 2018 belum selesai approval, jadi yang ditampilkan hanya tahun anggaran 2019.
            $fileImport = FileImport::with('fase')
			->where('fase_id', '!=',  4)  //<!--CHANGE 20210921-->
			->where('version_id', $version->id)
			->where('distrik_id', $user->distrik_id)
			->where('tahun', '>=', '2019')->orderBy('id');
        }
        if($request->date){
            $date = New Carbon($request->date);
            if($role->is_kantor_pusat) {
                $fileImport = $fileImport->whereRaw("draft_versi::text ILIKE '%".$date->toDateString()."%'");
            }
            else {
                // $fileImport = $fileImport->whereRaw("draft_versi::text ILIKE '%".$date->toDateString()."%'")->where('distrik_id', $user->distrik_id);

                //sementara, dari Pak Hisyam, 5 Maret 2018, karena tahun anggaran 2018 belum selesai approval, jadi yang ditampilkan hanya tahun anggaran 2019.
                $fileImport = $fileImport->whereRaw("draft_versi::text ILIKE '%".$date->toDateString()."%'")->where('distrik_id', $user->distrik_id)->where('tahun', '>=', '2019');
            }
        }

        $fileImport = $fileImport->get();

        $data = [
            'version' => $version,
            'draft' => $fileImport,
            'no' => 0,
            'proses' => $proses,
            'total' => $total,
            'print' => $print,
        ];

        return view('template.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($jenis_id, $id)
    {
        $template = Template::find($id);
        $version = (new Version())->where('template_id', $template->id)->get();

        $data = [
            'template' => $template,
            'versions' => $version,
            'jenis_id' => $jenis_id,
        ];

        return view('template.edit', $data);
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

        $template = Template::find($id);

        $template->tahun = $request->tahun;
        $template->jenis_id = $jenis_id;
        $template->active = 1;

        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $file = $request->file('file');
            $destinationPath = "files";
            $filename = $file->getClientOriginalName();
            $request->file('file')->move($destinationPath, $filename);
            $template->file = $destinationPath . '/' . $filename;
        }

        // $version_data = Version::where('template_id', $id);

        // $version = New Version();

        // $version->versi = $version_data->get()->count()+1;
        // $version->file = $template->file;
        // $version->active = 1;

        // $transaction = DB::transaction(function() use ($template, $version, $version_data){
        $transaction = DB::transaction(function() use ($template){
            $template->save();

            // $version_data->update(['active' => 0]);
            // $version->template_id = $template->id;
            // $version->save();
        });
        if($transaction){
            $request->session()->flash('success', 'Data berhasil di buat!');
        }

        return redirect(route('template.index', $jenis_id));
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
}
