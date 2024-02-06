<?php namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class PGDLReportDashboardPage extends Model
{
    protected $table = "pgdl_report_dashboard_pages";

    protected $fillable = ['id', 'name'];

	public function pgdl_report_dashboard_setting()
    {
    	return $this->hasMany('App\Entities\PgdlReportDashboardSetting');
    }

}
