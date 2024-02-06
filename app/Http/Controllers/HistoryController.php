<?php

namespace App\Http\Controllers;

use App\Entities\FileImport;
use App\Entities\History;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index($id)
    {
        $history = History::with('sheet')->where('file_import_id', $id)->get();
        $file_import = FileImport::find($id);

        $data = [
            'history' => $history,
            'id' => $id,
            'version' => $file_import->version_id,
        ];

        return view('history.index', $data);
    }
}
