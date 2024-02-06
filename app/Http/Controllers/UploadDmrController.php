<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadDmrController extends Controller
{
    public function index()
    {
    	return view('dmr.daftar');
    }

    public function create()
    {
    	return view('dmr.tambah');
    }

    public function update()
    {
    	return view('dmr.edit');
    }

    public function detail()
    {
        return view('dmr.detail');
    }


    public function delete()
    {
    	return view('dmr.daftar');
    }
}
