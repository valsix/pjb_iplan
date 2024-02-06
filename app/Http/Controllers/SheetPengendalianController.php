<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\PgdlTemplate;
use App\Entities\PgdlVersion;
use App\Entities\PgdlSheet;
use App\Entities\PgdlSheetSetting;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use App\Entities\Jenis;
use Validator;
use DB;

class SheetPengendalianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($version_id)
    {   
        // dd($version_id);
        //
        ini_set('max_execution_time', 900);
        $id_version = PgdlVersion::pluck('id')->toArray();
        if(!in_array($version_id,$id_version)){
            return redirect('pagenotfound');
        } 
        $version = PgdlVersion::with('pgdl_template.jenis')->where('id', $version_id)->first();
        
        
        $sheet_md = PgdlSheet::where('pgdl_version_id', $version_id)->get();
        // dd($sheet_md);

        $filePath = $version->file;

        $reader = ReaderFactory::create(Type::XLSX);

        $reader->open($filePath);

        if(!$sheet_md->count()) {
            $sheet_data = [];
            foreach ($reader->getSheetIterator() as $sheet) {
                $sheet_data[] = $sheet->getName();
            }

            $reader->close();

            $data = [
                'version' => $version,
                'sheet' => $sheet_data,
            ];

            return view('pengendalian_sheet.use', $data);
        }

        $sheet_use = [];
        foreach ($sheet_md as $row){
            $sheet_use[] = $row->name;
        }

        $sheet_data = [];
        foreach ($reader->getSheetIterator() as $sheet) {
            if(in_array($sheet->getName(), $sheet_use)) {
                $i = 1;
                $sheet_data[$sheet->getName()] = [];
                foreach ($sheet->getRowIterator() as $row) {
                    if ($i >= 8 && array_filter($row)){
                        if (in_array($version->pgdl_template->jenis_id, Jenis::FORM_6_10)) {
                            if ($i >= 13) {
                                $sheet_data[$sheet->getName()][] = $row;
                                break;
                            }
                        } else {
                            $sheet_data[$sheet->getName()][] = $row;
                        }
                    }
                    $i++;
                }
            }
        }

        $data = [
            'version' => $version,
            'sheet_md' => $sheet_md,
            'sheet' => $sheet_data,
        ];

        $reader->close();



        return view('pengendalian_sheet.index', $data);
    }

    public function import(Request $request, $version_id)
    {
        // dd('import');
        ini_set('max_execution_time', 900);
        $this->validate($request, [
            'file' => 'required|file',
        ]);
        
        $extensions = array("xls","xlsx","xlm","xla","xlc","xlt","xlw");

        $result = array($request->file('file')->getClientOriginalExtension());

        if(in_array($result[0],$extensions)){
         // Do something when Succeeded 
        $sheet_md = PgdlSheet::where('pgdl_version_id', $version_id)->get();
        $v = PgdlVersion::select('pgdl_template_id')->where('id', $version_id)->first();
        // dd($v->pgdl_template_id);
        $sheet_array = [];
        $sheet_use = [];
        foreach ($sheet_md as $row){
            $sheet_array[$row->name] = $row->id;
            $sheet_use[] = $row->name;
        }
        // dd( $request->file('file'));
        $file = $request->file('file');
        $destinationPath = "pgdl_sheet_setting/".$row->id;
        // $filename= 'pgdl_sheet_setting.'.$file->getClientOriginalExtension();
        $filename = $file->getClientOriginalName();
        $request->file('file')->move($destinationPath, $filename);

        PgdlTemplate::where('id', $v->pgdl_template_id)
          ->update(['setting_filepath' => $destinationPath.'/'.$filename]);
        // $template = PgdlTemplate::findorFail($v);
        // $template->setting_filepath = $destinationPath.'/'.$filename;
        // dd($template);
        // $template->save();

        $destinationPath_temp = "temp";
        $filename_temp = 'tempsetting.'.$file->getClientOriginalExtension();
        $copy = copy($destinationPath.'/'.$filename, $destinationPath_temp.'/'.$filename_temp);

        // $file = $request->file('file');
        // $destinationPath = "temp";
        // $filename= 'tempsetting.'.$file->getClientOriginalExtension();
        // $request->file('file')->move($destinationPath, $filename);

        $reader = ReaderFactory::create(Type::XLSX);

        $reader->open($destinationPath.'/'.$filename);

        $sheet_data = [];
        $error = [];
        foreach ($reader->getSheetIterator() as $sheet) {
            $i = 1;
            foreach ($sheet->getRowIterator() as $row) {
                if(in_array($sheet->getName(), $sheet_use)) {
                    $j = 'A';
                    foreach ($row as $row2) {
                        if (!empty($row2) && $row2 != 'blank') {
                            $explode = explode('#', $row2);
                            $sheet_id = $sheet_array[$sheet->getName()];
                            $sheet_data[] = [
                                'pgdl_sheet_id' => $sheet_id,
                                'kolom' => $j,
                                'row' => $i,
                                'validation_type' => preg_replace('/\s+/', '', $explode[0]),
                                'validation' => (!empty($explode[1])) ? $explode[1] : '',
                                'editable' => (!empty($explode[2])) ? (int)preg_replace('/\s+/', '', $explode[2]):1,
                                'color' => (!empty($explode[3])) ? preg_replace('/\s+/', '', $explode[3]):'0070C0',
                                'query_value' => (!empty($explode[4])) ? $explode[4] : '',
                                'sequence' => (!empty($explode[5])) ? $explode[5] : 0,
                            ];
                        }
                        $j++;
                    }
                    $i++;
                }
            }
        }
        // dd($sheet_data);
        $transaction = DB::transaction(function() use ($sheet_array, $sheet_data) {
            PgdlSheetSetting::whereIn('pgdl_sheet_id', $sheet_array)
                ->delete();

            $sheet_chunk = array_chunk($sheet_data, 1000);

            foreach ($sheet_chunk as $sc) {
                # code...
                PgdlSheetSetting::insert($sc);

            }
        });

        $request->session()->flash('success', 'Data berhasil di import!');

        return redirect(route('sheetpengendalian.index', $version_id));

        }else{
       // Do something when it fails
             return back()->with('salah', 'File Yang Di-upload Harus Excel');
        }

    }

     public function sheet_save(Request $request, $version_id)
    {
        $sheet_input = $request->sheet;

        $version = PgdlVersion::find($version_id);
        // dd( $sheet_input , $version_id, $version);

        $filePath = $version->file;

        $reader = ReaderFactory::create(Type::XLSX);

        $reader->open($filePath);

        $sheet_data = [];
        foreach ($reader->getSheetIterator() as $sheet) {
            $sheet_data[] = $sheet->getName();
        }

        $reader->close();

        $data = [];
        foreach($sheet_input as $row){
            $data[] = ['pgdl_version_id' => $version_id, 'name' => $sheet_data[$row]];
        }

        PgdlSheet::insert($data); // Eloquent

        return redirect(route('sheetpengendalian.index', $version_id));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($jenis_id, $id)
    {
        //
      $template = PgdlTemplate::find($id);
      $version = (new PgdlVersion())->where('pgdl_template_id', $template->id)->get();
      // dd($version);
      $data = [
          'template' => $template,
          'versions' => $version,
          'jenis_id' => $jenis_id,
      ];
      return view('template_pengendalian.setting', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $template)
    {
        //
        // dd($id, $template);
        ini_set('max_execution_time', 900);
        $this->validate($request, [
            'file' => 'required|file',
        ]);

        $version_id = PgdlVersion::where('pgdl_template_id', $template)->first();
        // dd($version_id);
        $sheet_md = PgdlSheet::where('pgdl_version_id', $version_id->id)->get();
        // dd($sheet_md);
        // dd($version_id, $sheet_md);

        $sheet_array = [];
        $sheet_use = [];
        foreach ($sheet_md as $row){
            $sheet_array[$row->name] = $row->id;
            $sheet_use[] = $row->name;
        }
        // dd($row->id);
        $file = $request->file('file');
        $destinationPath = "pgdl_sheet_setting/".$row->id;
        $filename= 'pgdl_sheet_setting.'.$file->getClientOriginalExtension();
        $request->file('file')->move($destinationPath, $filename);
        // dd('1');
        $template = PgdlTemplate::findorFail($template);
        $template->setting_filepath = $destinationPath.'/'.$filename;
        $template->save();
        // dd('1');
        $reader = ReaderFactory::create(Type::XLSX);

        $reader->open($destinationPath.'/'.$filename);

        $sheet_data = [];
        $error = [];
        foreach ($reader->getSheetIterator() as $sheet) {
            $i = 1;
            foreach ($sheet->getRowIterator() as $row) {
                if(in_array($sheet->getName(), $sheet_use)) {
                    $j = 'A';
                    foreach ($row as $row2) {
                        if (!empty($row2) && $row2 != 'blank') {
                            $explode = explode('#', $row2);
                            $sheet_id = $sheet_array[$sheet->getName()];
                            $sheet_data[] = [
                                'pgdl_sheet_id' => $sheet_id,
                                'kolom' => $j,
                                'row' => $i,
                                'validation_type' => preg_replace('/\s+/', '', $explode[0]),
                                'validation' => (!empty($explode[1])) ? $explode[1] : '',
                                'editable' => (!empty($explode[2])) ? (int)preg_replace('/\s+/', '', $explode[2]):1,
                                'color' => (!empty($explode[3])) ? preg_replace('/\s+/', '', $explode[3]):'0070C0',
                                'query_value' => (!empty($explode[4])) ? $explode[4] : '',
                                'sequence' => (!empty($explode[5])) ? $explode[5] : 0,
                            ];
                        }
                        $j++;
                    }
                    $i++;
                }
            }
        }
        // dd($sheet_data);
        $transaction = DB::transaction(function() use ($sheet_array, $sheet_data) {
          // dd($sheet_array);
            // dd($sheet_array);
            PgdlSheetSetting::whereIn('pgdl_sheet_id', $sheet_array)
              ->delete();

            // dd($sheet_data );
            PgdlSheetSetting::insert($sheet_data);
        });
        // dd($sheet_data);


        // $request->session()->flash('success', 'Data berhasil di import!');

        // return redirect(route('templatepengendalian.index', $jenis_id));
        return redirect(route('templatepengendalian.index', $id))->with('success', 'Data berhasil di update!');

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
