<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Entities\Distrik;
use App\Entities\ExcelData;
use App\Entities\Fase;
use App\Entities\FileImport;
use App\Entities\FileApproval;
use App\Entities\History;
use App\Entities\Jenis;
use App\Entities\Lokasi;
use App\Entities\Sheet;
use App\Entities\SheetSetting;
use App\Entities\StrategiBisnis;
use App\Entities\Template;
use App\Entities\Version;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\WriterFactory;
use Illuminate\Http\Request;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\ValidationExcelTrait;

class ExportData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:store  {id} {filename} {sheet*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export store to excel';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $id = $this->argument('id');
        $sheet_request = $this->argument('sheet');
        $filename = $this->argument('filename');

        // dd(["id"=>$id, "sheet_request" => $sheet_request, "filename" => $filename]);

        $fileimport = FileImport::find($id);
        $version_id = $fileimport->version_id;
        // dd($fileimport);

        $version = Version::where('id', $version_id)->first();
        // dd($version);

        // $sheet_md = Sheet::with('excel_datas')->whereIn('id', $sheet_request)->get();
        // $sheet_md = Sheet::with(['excel_datas' => function ($query) use ($id) {
        //     $query->where('file_import_id', $id);
        // }])->where('version_id', $version_id)
        //     ->whereIn('id', $sheet_request)
        //     ->get();
        $sheet_md = Sheet::where('version_id', $version_id)
            ->whereIn('id', $sheet_request)
            ->get();
        // dd($sheet_md);

        $filePath = $version->file;
        // dd($filePath);
        $filepath = "temp-" . date("Y-m-d-H-i-s") . ".xlsx";

        // $reader = ReaderFactory::create(Type::XLSX);

        // $reader->open($filePath);
        // $reader->openToFile($filePath);

        $sheet_use = [];

        // $excel_data = ExcelData::where('file_import_id', $id)
        //     ->get();
        // dd($excel_data);

        $sheet_id = [];
        $sheet_use = [];
        foreach ($sheet_md as $row){
            $sheet_use[] = $row->name;
            $sheet_id[] = $row->id;
        }
        // dd($sheet_use);

        $setting = SheetSetting::whereIn('sheet_id', $sheet_id)
            ->orderBy('row', 'asc')
            ->orderBy('kolom', 'asc')
            ->get();
        // dd($setting);

        $setting_group = $setting->groupBy('sheet_id');

        $sheet_name = [];
        $setting_set = [];
        foreach ($sheet_md as $row){
            if(!empty($setting_group[$row->id])){
                $setting_set[$row->name] = collect($setting_group[$row->id]);
                $sheet_name[$row->name] = $row->id;
            } else {
                $setting_set[$row->name] = collect([]);
                $sheet_name[$row->name] = $row->id;
            }
        }

        $sheet_data = [];
        /* tidak digunakan */
        if(false){
            foreach ($reader->getSheetIterator() as $sheet) {
                if(in_array($sheet->getName(), $sheet_use)) {
                    $i = 1;
                    $array_data = [];
                    $sheet_setting_set = $setting_set[$sheet->getName()];
                    $sheet_id_data = '';
                    foreach ($sheet_setting_set as $value){
                        $array_data[] = [$value->kolom, $value->row];
                        $sheet_id_data = $value->sheet_id;
                    }

                    $limit_row = 13;
                    $template_2_3_empty = true;
                    if (in_array($version->template->jenis_id, Jenis::FORM_6_10)) {
                        if($excel_data->max('row')){
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
                                    if($template_2_3_empty){
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
                                                    if($excel_val->value > 1000){
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
                                    }
                                }
                            }
                            $sheet_data[] = $array_value;
                        }
                        $i++;
                    }
                    if (in_array($version->template->jenis_id, Jenis::FORM_6_10)) {
                        for ($i = 13; $i <= $limit_row; $i++) {
                            $j = 'A';
                            $array_value = [];
                            for($k = 0; $k < $count_row2; $k++) {
                                if (in_array([$j, 13], $array_data)) {
                                    $excel_val = $excel_data->where('sheet_id', $sheet_id_data)->where('row', $i)->where('kolom', $j)->first();
                                    if ($excel_val) {
                                        $excel_value = $excel_val->value;
                                        if (is_numeric($excel_val->value)) {
                                            if($excel_val->value > 1000){
                                                $excel_value = number_format($excel_val->value);
                                            }
                                            if ($excel_val->value < 0) {
                                                $excel_value = "(" . number_format(abs($excel_val->value)) . ")";
                                            }
                                        }
                                        $array_value[] = $excel_value;
                                        // dump($array_value);
                                    } else {
                                        $array_value[] = 0;
                                    }
                                }
                                $j++;
                            }
                        // dump($array_value);
                            $sheet_data[$sheet->getName()][] = $array_value;
                        }
                    }
                }
            }
        }

        // dd($sheet_use);

        // $reader->close();

        // $fileExport = $version->template->jenis->name.' - '.date("d-m-Y"); //nama file yg di export
        // echo "going to create excel<br/>\n";
        // exit();

        // dd("id: $id", 
        //     "req sheet:",$sheet_request, 
        //     "version", $version,
        //     "version: $version_id", 
        //     "result sheet", $sheet_md
        // );
        /* jika sheet yang direquest kosong, buat sheet kosong */
        if(count($sheet_md) == 0) {
            $sheet_md[] = (object)array("id" => "-999", "name" => "Kosong");
        }

        Excel::create($filename, function($excel) use($sheet_md, $id) {
            foreach($sheet_md as $req_sheet) {
                /* get excel data dengan filter sheet id dan file import id */
                $excel_data = ExcelData::where("file_import_id", $id)->where("sheet_id", $req_sheet->id)->orderBy("id", "desc")->get();
                $excel->sheet($req_sheet->name, function($sheet) use($excel_data, $req_sheet){
                    $knownCell = [];
                    foreach($excel_data as $cell_data) {
                         $cellKey = $cell_data->kolom . $cell_data->row;
                        
                        /* cell belum pernah diproses */
                        if(isset($knownCell[$cellKey]) == false) {
                            $knownCell[$cellKey] = 1;
                        }
                        /* cell pernah diproses sebelumnya, cell redundant, hapus untuk menghilangkan redundancy */
                        else {
                            ExcelData::where("id", $cell_data->id)->delete();
                            continue;
                        }

                        $value = $cell_data->value;
                        $style = "";
                        $text_type_column = ['A', 'B', 'C', 'D'];
                        /* skip kolom ID */
                        if($cell_data->kolom == 'ID') {
                            continue;
                        }
                        /* jika kolom merupakan data text */
                        if(in_array($cell_data->kolom, $text_type_column)){
                            $sheet->cell($cell_data->kolom . $cell_data->row, function ($cell) use($value, $style){
                                $cell->setValue($value);
                            });
                        } 
                        /* pada kolom ini kemungkinan data berupa numeric */
                        else {
                            $sheet->cell($cell_data->kolom . $cell_data->row, function ($cell) use($value, $style){
                                $cell->setValue($value);
                            });
                            /* data berupa numeric */
                            if(is_numeric($value)) {
                                /* format cell untuk data numeric ribuan */
                                $sheet->setColumnFormat([$cell_data->kolom . $cell_data->row => "#,##0.00"]);
                            } 
                        }
                    }
                });
            }
        })->store("xlsx");

        // Excel::create($filename, function($excel) use ($sheet_use, $sheet_data, $setting_set) {
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
        // })->store('xlsx');
    }
}
