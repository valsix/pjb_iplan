<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Jenis extends Model
{
    const FORM_RKAU = 1;
    const FORM_6_REIMBURSE = 2;
    const FORM_6_RUTIN = 3;
    const FORM_10_PU = 4;
    const FORM_10_PENGUATANKIT = 5;
    const FORM_10_PLN = 6;
    const FORM_BAHAN_BAKAR = 7;
    const FORM_PENYUSUTAN = 9;
    const FORM_6 = [2, 3];
    const FORM_10 = [4, 5, 6];
    const FORM_6_10 = [2, 3, 4, 5, 6, 7, 9]; //bahan bakar & penyusutan masuk sini (biar ga banyak ngubah codingan di branch template_excel)

    public function lokasijenis()
    {
    	return 
    	$this->hasMany('App\Entities\LokasiJenis');
    }
    public function pgdl_report_dashboard_setting()
    {
        return $this->hasMany('App\Entities\PgdlReportDashboardSetting');
    }
}
