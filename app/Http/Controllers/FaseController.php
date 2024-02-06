<?php

namespace App\Http\Controllers;

use App\Entities\Fase;
use Illuminate\Http\Request;

class FaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fase = Fase::all();

        $data = ['fase' => $fase];

        return view('fase.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('fase.create');
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
            'name' => 'required',
        ]);

        $fase = New Fase();

        $fase->name = $request->name;

        if($fase->save()){
            $request->session()->flash('success', 'Data berhasil di buat!');
        }

        return redirect(route('fase.index'));
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
        $fase = Fase::find($id);

        $data = ['fase' => $fase];

        return view('fase.edit', $data);
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
            'name' => 'required',
        ]);

        $fase = Fase::find($id);

        $fase->name = $request->name;

        if($fase->save()){
            $request->session()->flash('success', 'Data berhasil di buat!');
        }

        return redirect(route('fase.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $fase = Fase::find($id);

        if($fase->delete()){
            $request->session()->flash('success', 'Data berhasil di delete!');
        }

        return redirect(route('admin.connection.index'));
    }
}
