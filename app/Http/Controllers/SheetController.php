<?php

namespace App\Http\Controllers;

use App\Entities\ExcelData;
use App\Entities\Jenis;
use App\Entities\Sheet;
use App\Entities\SheetSetting;
use App\Entities\Version;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use Illuminate\Http\Request;
use DB;

class SheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($version_id)
    {
        ini_set('max_execution_time', 900);
        $version = Version::with('template.jenis')->where('id', $version_id)->first();

        $sheet_md = Sheet::where('version_id', $version_id)->get();

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

            return view('sheet.use', $data);
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
                        if (in_array($version->template->jenis_id, Jenis::FORM_6_10)) {
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

        return view('sheet.index', $data);

    }

    public function sheet_save(Request $request, $version_id)
    {
        $sheet_input = $request->sheet;

        $version = Version::find($version_id);

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
            $data[] = ['version_id' => $version_id, 'name' => $sheet_data[$row]];
        }

        Sheet::insert($data); // Eloquent

        return redirect(route('sheet.index', $version_id));
    }

    public function import(Request $request, $version_id)
    {
        ini_set('max_execution_time', 900);
        $this->validate($request, [
            'file' => 'required|file',
        ]);

        $sheet_md = Sheet::where('version_id', $version_id)->get();

        $sheet_array = [];
        $sheet_use = [];
        foreach ($sheet_md as $row){
            $sheet_array[$row->name] = $row->id;
            $sheet_use[] = $row->name;
        }

        $file = $request->file('file');
        $destinationPath = "temp";
        $filename= 'tempsetting.'.$file->getClientOriginalExtension();
        $request->file('file')->move($destinationPath, $filename);

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
                                'sheet_id' => $sheet_id,
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

        $transaction = DB::transaction(function() use ($sheet_array, $sheet_data) {
            SheetSetting::whereIn('sheet_id', $sheet_array)
                ->delete();

            	//20190315 by FFR - Tambahan ketika ada laporan mba dinda tidak bisa upload setting RKAU
		$sheet_chunk = array_chunk($sheet_data, 1000);

            foreach ($sheet_chunk as $value) {
                # code...
                // dump($value);
                SheetSetting::insert($value);
            }
			// SheetSetting::insert($sheet_data);
        });

        $request->session()->flash('success', 'Data berhasil di import!');

        return redirect(route('sheet.index', $version_id));
    }

    public function setting($version_id, $id)
    {
        $sheet = Sheet::find($id);

        $sheet_setting = SheetSetting::where('sheet_id', $id)
            ->orderBy('row', 'asc')
            ->orderBy('kolom', 'asc')
            ->get();

        $data = [
            'sheet' => $sheet,
            'setting' => $sheet_setting,
        ];

        return view('sheet.setting', $data);
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
        $sheet = New SheetSetting();

        $sheet->sheet_id = $request->id;
        $sheet->row = $request->row;
        $sheet->kolom = $request->kolom;
        $sheet->validation_type = $request->validation_type;
        $sheet->validation = $request->validation;
        $sheet->query_value = $request->query_value;
        $sheet->sequence = $request->sequence;

        $sheet->save();

        return response()->json($sheet);
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($version_id, $id)
    {
        $sheet = SheetSetting::find($id);

        $sheet->delete();

        return response()->json(['success' => 1]);
    }
}
