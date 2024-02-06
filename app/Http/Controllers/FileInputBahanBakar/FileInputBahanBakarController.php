<?php

namespace App\Http\Controllers\FileInputBahanBakar;

use Excel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Entities\User;
use App\Http\Controllers\Controller;
use App\Entities\FileInputBahanBakar;
use App\Entities\ExcelDataInputBahanBakar;
use App\Http\Services\FileInputBahanBakarService;

class FileInputBahanBakarController extends Controller
{
    private function rules() {
        return [
            'name' => 'required|unique:file_input_bahan_bakar|max:255',
            'tahun' => 'required|numeric',
            'file' => 'required'
        ];
    }

    private function messages()
    {
        return [
            'name.required' => 'Nama wajib diisi',
            'name.unique' => 'Nama sudah terpakai dan masih aktif',
            'tahun.required' => 'Tahun wajib diisi',
            'file.required' => 'File wajib diisi'
        ];
    }

    private function checkExt($file_ext)
    {
        $valid = array( 'xls','xlsx' );
        return in_array($file_ext, $valid) ? true : false;
    }

    private function checkAccess() 
    {
        if(session('role_id') != ROLE_ID_STAFF_ANGGARAN AND session('role_id') != ROLE_ID_ADMIN) {
            return redirect()->route('noaccess');
        }

        return true;
    }

    public function index()
    {
        $this->checkAccess();

        $user = User::find(session('user_id'));
        $files = FileInputBahanBakar::with('excel_bahan_bakar')->latest()->get();

        return view('form_input_bahan_bakar.index', compact('user', 'files'));
    }

    public function create()
    {
        $this->checkAccess();

        return view('form_input_bahan_bakar.create');
    }

    public function store(Request $request)
    {
        $this->checkAccess();

        $request->validate($this->rules(), $this->messages());

        if($this->checkExt($request->file('file')->getClientOriginalExtension()) == false) {
            return redirect()->route('form_bahan_bakar.create')->with('error', 'Ekstensi file wajib xls, xlsx');
        }

        $check_version = FileInputBahanBakar::latest('version')->first();
        
        (is_null($check_version)) ? $version = 0 : $version = $check_version->version + 1;
    
        $file = $request->file('file');
        $destinationPath = 'form_input_bahan_bakar/'.$request->tahun.'/'.$version;
        $request->file('file')->move($destinationPath, $file->getClientOriginalName());
        $filename = $destinationPath.'/'.$file->getClientOriginalName();

        DB::beginTransaction();

        try {

            $file_input = FileInputBahanBakar::create([
                'tahun' => $request->tahun,
                'name' => $request->name,
                'filepath' => $filename,
                'version' => $version,
                'uploaded_by' => session('user_id')
            ]);

            $result = FileInputBahanBakarService::process_excel($file_input->id, $filename, $request->tahun);

            if (isset($result['error'])) {
                File::deleteDirectory(public_path('form_input_bahan_bakar/'.$request->tahun.'/'.$version));
                return redirect()->route('form_bahan_bakar.create')->with('error', $result['error']);
            }

        } catch (\Throwable $th) {
            
            File::deleteDirectory(public_path('form_input_bahan_bakar/'.$request->tahun.'/'.$version));
            // dd($th);

            return redirect()->route('form_bahan_bakar.create')->with('message', 'Terjadi kesalahan server');
        }

        DB::commit();

        return redirect()->route('form_bahan_bakar.index')->with('message', 'Upload file input bahan bakar berhasil');
    }

//    public function show(FileInputBahanBakar $form_bahan_bakar, $excel = false)
    public function show($id, $excel = false)
    {
        $this->checkAccess();

        $form_bahan_bakar = FileInputBahanBakar::findOrFail($id);

        $data_excel = ExcelDataInputBahanBakar::select('prk', 'month', 'value', 'distrik_id')
            ->where('file_input_bahan_bakar_id', $form_bahan_bakar->id)->get();

        $datas = $month = array();
        foreach ($data_excel as $value) {
            $datas[$value->prk] = [
                'distrik' => $value->distrik->code1,
                'prk' => $value->prk,
                'value' => []
            ];

            for($i = 1; $i <= 12; $i++) {
                if ($i == $value->month) {
                    $month[$value->prk][] = $value->value;
                }
            }

            array_push($datas[$value->prk]['value'], $month);
        }

        if ($excel) {
            return [
                'datas' => $datas,
                'form_bahan_bakar' => $form_bahan_bakar
            ];
        }

        return view('form_input_bahan_bakar.show', compact('datas', 'form_bahan_bakar'));
    }

    public function destroy(FileInputBahanBakar $form_bahan_bakar)
    {
        $this->checkAccess();

        if (File::exists($form_bahan_bakar->filepath)) {
            File::deleteDirectory(public_path('form_input_bahan_bakar/'.$form_bahan_bakar->tahun.'/'.$form_bahan_bakar->version));
        }

        $form_bahan_bakar->delete();

        return redirect()->route('form_bahan_bakar.index')->with('message', 'Hapus data dan file berhasil');

    }

    public function download($id)
    {
        $this->checkAccess();

        $document = FileInputBahanBakar::findOrFail($id);

        if (File::exists($document->filepath)) {
            return response()->download($document->filepath);
        }

        return redirect()->route('form_bahan_bakar.index')->with('error', 'File tidak ditemukan');

    }

    public function exportExcel($id)
    {
        $result = $this->show($id, true);

        $datas = $result['datas'];
        $form_bahan_bakar = $result['form_bahan_bakar'];

        Excel::create('Form Input Bahan Bakar', function ($excel) use($datas, $form_bahan_bakar) {
            $excel->setTitle('Form Input Bahan Bakar');
            $excel->setCreator('Laravel')->setCompany('Profio', 'PJB');
            $excel->setDescription('Form Input Bahan Bakar');
            $excel->sheet('input BB bulanan', function ($sheet) use($datas, $form_bahan_bakar){
                $sheet->loadView('form_input_bahan_bakar/excel')->with('datas', $datas)->with('form_bahan_bakar', $form_bahan_bakar);
            });
        })->download('xls');

    }
}
