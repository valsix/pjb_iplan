<?php

namespace App\Http\Traits;

use App\Entities\Sheet;
use App\Entities\PgdlSheet;
use App\Entities\FileImport;
use App\Entities\FileImportKetetapan;
use App\Entities\PGDLFileImportRevisi;


trait ValidationExcelTrait
{
    public function validation ($value_input, $value_setting, $operator_setting, $sheet_data, $sheet_id, $kolom)
    {
        switch ($operator_setting) {
            case '<':
                return $value_input < $value_setting;
                break;
            case '<=':
                return $value_input <= $value_setting;
                break;
            case '>':
                return $value_input > $value_setting;
                break;
            case '>=':
                return $value_input >= $value_setting;
                break;
            case '=':
                return $value_input == $value_setting;
                break;
            case '!=':
                return $value_input != $value_setting;
                break;
            case 'is_null':
                return empty($value_input);
                break;
            case 'is_not_null':
                return (!empty($value_input));
                break;
            case 'array':
                return in_array($value_input, explode(',', $value_setting));
                break;
//            case 'array_master':
//                $table = DB::table($value_setting)->select('name')->get();
//                $table_val = [];
//                foreach ($table as $row){
//                    $table_val[] = $row->name;
//                }
//                return in_array($value_input, $table_val);
//                break;
            case 'string':
                if(empty($value_input)){
                    return true;
                }

                // if(is_numeric($value_input)){
                //     return false;
                // }

                if($value_setting > 0){
                    if($value_setting != strlen($value_input)){
                        return false;
                    }
                }

                return true;
                break;
            case 'numeric':
                if(empty($value_input)){
                    return true;
                }

                if(!is_numeric($value_input)){
                    return false;
                }

                if($value_setting){
                    if($value_setting != strlen($value_input)){
                        return false;
                    }
                }

                return true;
                break;
            case 'unique':
                $data_use = [];
                foreach ($sheet_data as $data){
                    if($data['sheet_id'] == $sheet_id AND $data['kolom'] == $kolom){
                        $data_use[] = $data['value'];
                    }
                }
                return (!in_array($value_input, $data_use));
                break;
            default:
                return true;
        }
    }

    public function validation_pgdl ($value_input, $value_setting, $operator_setting, $sheet_data, $sheet_id, $kolom)
    {
        switch ($operator_setting) {
            case '<':
                return $value_input < $value_setting;
                break;
            case '<=':
                return $value_input <= $value_setting;
                break;
            case '>':
                return $value_input > $value_setting;
                break;
            case '>=':
                return $value_input >= $value_setting;
                break;
            case '=':
                return $value_input == $value_setting;
                break;
            case '!=':
                return $value_input != $value_setting;
                break;
            case 'is_null':
                return empty($value_input);
                break;
            case 'is_not_null':
                return (!empty($value_input));
                break;
            case 'array':
                return in_array($value_input, explode(',', $value_setting));
                break;
//            case 'array_master':
//                $table = DB::table($value_setting)->select('name')->get();
//                $table_val = [];
//                foreach ($table as $row){
//                    $table_val[] = $row->name;
//                }
//                return in_array($value_input, $table_val);
//                break;
            case 'string':
                if(empty($value_input)){
                    return true;
                }

                // if(is_numeric($value_input)){
                //     return false;
                // }

                if($value_setting > 0){
                    if($value_setting != strlen($value_input)){
                        return false;
                    }
                }

                return true;
                break;
            case 'numeric':
                if(empty($value_input)){
                    return true;
                }

                if(!is_numeric($value_input)){
                    return false;
                }

                if($value_setting){
                    if($value_setting != strlen($value_input)){
                        return false;
                    }
                }

                return true;
                break;
            case 'unique':
                $data_use = [];
                foreach ($sheet_data as $data){
                    if($data['pgdl_sheet_id'] == $sheet_id AND $data['kolom'] == $kolom){
                        $data_use[] = $data['value'];
                    }
                }
                return (!in_array($value_input, $data_use));
                break;
            default:
                return true;
        }
    }

    public function sql_replace($str, $fileimport, $sheet, $row, $kolom)
    {
        $fileimport_model = FileImport::find($fileimport);

        $sheet_model = Sheet::where('version_id', $fileimport_model->version_id)->get();

        $sheet_array = [];
        foreach ($sheet_model as $value){
            $sheet_array['{{ '.$value->name.' }}'] = "sheet_id = ".$value->id;
        }

        $str = strtr($str, $sheet_array);

        $str = str_replace("{{ fileimport }}", "file_import_id = '".$fileimport."'", $str);
        $str = str_replace("{{ form_6_rutin }}", "file_import_id = '".$fileimport_model->form6_rutin_file_import_id."'", $str);
        $str = str_replace("{{ form_6_reimburse }}", "file_import_id = '".$fileimport_model->form6_reimburse_file_import_id."'", $str);
        $str = str_replace("{{ form_10_pu }}", "file_import_id = '".$fileimport_model->form10_pu_file_import_id."'", $str);
        $str = str_replace("{{ form_10_penguatan_kit }}", "file_import_id = '".$fileimport_model->form10_penguatankit_file_import_id."'", $str);
        $str = str_replace("{{ form_10_pln }}", "file_import_id = '".$fileimport_model->form10_pln_file_import_id."'", $str);
        $str = str_replace("{{ form_bahan_bakar }}", "file_import_id = '".$fileimport_model->form_bahan_bakar_file_import_id."'", $str);
        $str = str_replace("{{ form_penyusutan }}", "file_import_id = '".$fileimport_model->form_penyusutan_file_import_id."'", $str);

        $str = str_replace("{{ sheet }}", "sheet_id = '".$sheet."'", $str);
        $str = str_replace("{{ row }}", "row = '".$row."'", $str);
        $str = str_replace("{{ kolom }}", "kolom = '".$kolom."'", $str);

        return $str;
    }

    public function sql_replace_pgdl($str, $fileimport, $sheet, $row, $kolom)
    {
        // dd($fileimport);
        $fileimport_model = PGDLFileImportRevisi::find($fileimport);
        // $fileimport_model = FileImportKetetapan::find($fileimport);
        $sheet_model = PgdlSheet::where('pgdl_version_id', $fileimport_model->pgdl_version_id)->get();
        // dd($fileimport_model, $sheet_model);

        $sheet_array = [];
        foreach ($sheet_model as $value){
            $sheet_array['{{ '.$value->name.' }}'] = "pgdl_sheet_id = ".$value->id;
        }
        // dd( $sheet_array);
        $str = strtr($str, $sheet_array);
        
        $str = str_replace("{{ fileimport }}", "pgdl_file_import_revisi_id = '".$fileimport."'", $str);
        $str = str_replace("{{ form_6_rutin }}", "pgdl_file_import_revisi_id = '".$fileimport_model->form6_rutin_pgdl_file_import_revisi_id."'", $str);
        $str = str_replace("{{ form_6_reimburse }}", "pgdl_file_import_revisi_id = '".$fileimport_model->form6_reimburse_pgdl_file_import_revisi_id."'", $str);
        $str = str_replace("{{ form_10_pu }}", "pgdl_file_import_revisi_id = '".$fileimport_model->form10_pu_pgdl_file_import_revisi_id."'", $str);
        $str = str_replace("{{ form_10_penguatan_kit }}", "pgdl_file_import_revisi_id = '".$fileimport_model->form10_penguatankit_pgdl_file_import_revisi_id."'", $str);
        $str = str_replace("{{ form_10_pln }}", "pgdl_file_import_revisi_id = '".$fileimport_model->form10_pln_pgdl_file_import_revisi_id."'", $str);
        $str = str_replace("{{ form_bahan_bakar }}", "pgdl_file_import_revisi_id = '".$fileimport_model->form_bahan_bakar_pgdl_file_import_revisi_id."'", $str);
        $str = str_replace("{{ form_penyusutan }}", "pgdl_file_import_revisi_id = '".$fileimport_model->form_penyusutan_pgdl_file_import_revisi_id."'", $str);

        $str = str_replace("{{ sheet }}", "pgdl_sheet_id = '".$sheet."'", $str);
        $str = str_replace("{{ row }}", "row = '".$row."'", $str);
        $str = str_replace("{{ kolom }}", "kolom = '".$kolom."'", $str);

        return $str;
    }
}
