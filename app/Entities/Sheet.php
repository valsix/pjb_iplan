<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Sheet extends Model
{
    public function excel_datas()
    {
        return $this->hasMany('App\Entities\ExcelData');
    }

    public function excel_datas_ketetapan()
    {
        return $this->hasMany('App\Entities\ExcelDataKetetapan');
    }

    public function pgdl_excel_datas_revisi()
    {
        return $this->hasMany('App\Entities\PGDLExcelDataRevisi');
    }
}
