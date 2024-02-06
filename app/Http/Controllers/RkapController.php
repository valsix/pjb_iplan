<?php

namespace App\Http\Controllers;

use App\Entities\Fase;
use App\Entities\FileImport;
use App\Entities\Template;
use App\Entities\Version;
use Illuminate\Http\Request;
use DB;

class RkapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $templates = Template::with(['file_imports', 'fase'])
            ->where('jenis_id', 1);

        if($request->tahun){
            $templates = $templates->where('tahun', $request->tahun);
        }

        $templates = $templates->get();

        $data = [
            'templates' => $templates,
            'no' => 0,
        ];

        return view('rkap.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fase = Fase::all();

        $data = ['fase' => $fase];

        return view('rkap.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'fase_id' => 'required',
            'tahun' => 'required',
            'file' => 'required|file',
        ]);

        $template = New Template();

        $template->fase_id = $request->fase_id;
        $template->tahun = $request->tahun;
        $template->jenis_id = 1;
        $template->active = 1;

        $file = $request->file('file');
        $destinationPath = "files";
        $filename= str_random(10).'.'.$file->getClientOriginalExtension();
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

        return redirect(route('rkap.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $version = Version::with('sheets')
            ->where('template_id', $id)
            ->where('active', 1)
            ->first();

        $fileImport = FileImport::where('version_id', $version->id)->get();

        $data = [
            'version' => $version,
            'draft' => $fileImport,
            'no' => 0,
        ];

        return view('rkap.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $template = Template::find($id);
        $version = (new Version())->where('template_id', $template->id)->get();

        $fase = Fase::all();

        $data = [
            'fase' => $fase,
            'template' => $template,
            'versions' => $version,
        ];

        return view('rkap.edit', $data);
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
        $this->validate($request, [
            'fase_id' => 'required',
            'tahun' => 'required',
            'file' => 'required|file',
        ]);

        $template = Template::find($id);

        $template->fase_id = $request->fase_id;
        $template->tahun = $request->tahun;
        $template->jenis_id = 1;
        $template->active = 1;

        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $file = $request->file('file');
            $destinationPath = "files";
            $filename = str_random(10) . '.' . $file->getClientOriginalExtension();
            $request->file('file')->move($destinationPath, $filename);
            $template->file = $destinationPath . '/' . $filename;
        }

        $version_data = Version::where('template_id', $id);

        $version = New Version();

        $version->versi = $version_data->get()->count()+1;
        $version->file = $template->file;
        $version->active = 1;

        $transaction = DB::transaction(function() use ($template, $version, $version_data){
            $template->save();

            $version_data->update(['active' => 0]);
            $version->template_id = $template->id;
            $version->save();
        });
        if($transaction){
            $request->session()->flash('success', 'Data berhasil di buat!');
        }

        return redirect(route('rkap.index'));
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
