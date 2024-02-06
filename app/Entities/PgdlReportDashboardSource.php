<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class PgdlReportDashboardSource extends Model
{
    protected $table = "pgdl_report_dashboard_sources";

    public function PgdlReportDashboardSetting()
    {
        return $this->hasMany('App\Entities\PgdlReportDashboardSource', 'id');
    }
    
}
