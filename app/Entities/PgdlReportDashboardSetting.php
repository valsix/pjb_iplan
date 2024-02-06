<?php namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class PGDLReportDashboardSetting extends Model
{
    protected $table = "pgdl_report_dashboard_settings";

    protected $fillable = ['id', 'pgdl_report_dashboard_page_id', 'judul_kolom', 'kolom', 'sequence'];

	public function pgdl_report_dashboard_page()
    {
    	return $this->belongsTo('App\Entities\PgdlReportDashboardSetting', 'pgdl_report_dashboard_page_id');
    }
    public function pgdl_report_dashboard_source()
    {
        return $this->belongsTo('App\Entities\PgdlReportDashboardSource', 'pgdl_report_dashboard_source_id');
    }
    public function jenis()
    {
        return $this->belongsTo('App\Entities\Jenis', 'jenis_id');
    }


}
