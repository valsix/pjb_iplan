<?php

namespace App\Http\Controllers\Pengendalian;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request; 
use App\Entities\StrategiBisnis; 
use App\Entities\Fase; 
use App\Entities\Distrik;
use App\Entities\Lokasi;
use App\Entities\PgdlReportDashboardPage;
use App\Entities\PgdlReportDashboardSetting;
use App\Entities\PgdlReportDashboardSource;
use App\Entities\PgdlVersion;
use App\Entities\PgdlSheet;
use App\Entities\User;
use App\Entities\Jenis;
use App\Entities\Template;
use App\Entities\Role;
use DB;
use Input;
use Excel;
use PDF;

class ReportDashboardDinamisController extends Controller
{
    public function index(Request $request) 
    {
        $data['tahun'] = Template::orderBy('tahun','ASC')->distinct()->get(['tahun']);
        $data['page'] = PgdlReportDashboardPage::all();
        return view('pengendalian_input.report_dashboard_dinamis.index',$data);
    }

    public function storeKolom(Request $request)
    {
        $kolom  = $request->kolom;
        $nama_kolom_id = $request->nama_kolom_id;
        foreach ($nama_kolom_id as $idx => $nk_id) {
            
            $prds = PgdlReportDashboardSetting::find($nk_id);
            if($prds)
            {
                if($kolom[$idx]){$prds->kolom = $kolom[$idx];}
                else{
                    $prds->kolom = null;
                }
                $prds->save();
            }
        }
        $data = "sukses";
        return $data;

        
    }
    public function page(Request $request)
    {
        $id=$request->page_id;
        $page = $request->page;
        $per_page = $request->per_page;
        $thn = $request->tahun;
        $paket = DB::table('pgdl_report_dashboard_settings')
                ->join('pgdl_report_dashboard_sources','pgdl_report_dashboard_settings.pgdl_report_dashboard_source_id','=','pgdl_report_dashboard_sources.id')
                ->where('pgdl_report_dashboard_settings.pgdl_report_dashboard_page_id',$id)
                ->where('pgdl_report_dashboard_settings.tahun',$thn)
                ->select('pgdl_report_dashboard_settings.id','pgdl_report_dashboard_settings.jenis_id','pgdl_report_dashboard_settings.pgdl_report_dashboard_source_id as source_id','pgdl_report_dashboard_settings.sequence','pgdl_report_dashboard_settings.judul_kolom','pgdl_report_dashboard_settings.pgdl_sheet_name as nama_sheet','pgdl_report_dashboard_settings.kolom','pgdl_report_dashboard_sources.name as nama_source')
                ->orderBy('pgdl_report_dashboard_settings.jenis_id','ASC')
                ->orderBy('pgdl_report_dashboard_settings.sequence','ASC')
                ->paginate($per_page);
            $i=1;
            if($page == 1 ){
                $nilai_awal = 0;
            }else{
                $nilai_awal = $per_page*($page-1);
            }
            foreach ($paket as $pkt) {
                if($pkt->source_id<3){
                $pkt->nama_jenis = Jenis::where('id',$pkt->jenis_id)->pluck('name');
                
                }
                else{
                $pkt->nama_jenis = [];
                $pkt->source_id = 0;
                }

                $pkt->nomor = $nilai_awal+$i;
                $i++; 

            }

            return $paket;
    
        // ,'jenis.name as nama_jenis'
        // ->join('jenis','pgdl_report_dashboard_settings.jenis_id','=','jenis.id')
        
    }
    public function source()
    {
        $src = PgdlReportDashboardSource::all();
        return $src;
    }

    public function jenis()
    {
        $jns = Jenis::all();
        return $jns;

    }

    public function macamsheet($id)
    {
        $datas =  DB::table('pgdl_sheets')
                ->join('pgdl_versions','pgdl_versions.id','=','pgdl_sheets.pgdl_version_id')
                ->join('templates','pgdl_versions.pgdl_template_id','=','templates.id')
                ->join('jenis','templates.jenis_id','=','jenis.id')
                ->whereIn('pgdl_version_id',function($query){
                    $query->select('id')->from('pgdl_versions')
                    ->whereIn('pgdl_template_id',function($query2){
                        $query2->select('id')->from('templates');
                    });
                })
                ->where('jenis.id',$id)
                ->select('pgdl_sheets.id','pgdl_sheets.name')->get();

        return $datas;
    }
    
    public function copyTahun(Request $request)
    {
        $tahun = $request->tahun;
        $tahun = (int)$tahun;
        $tahun_prev = $tahun - 1;
        $data_now = PgdlReportDashboardSetting::where('tahun',$tahun)->count();
        $data_prev = PgdlReportDashboardSetting::where('tahun',$tahun_prev)->get();
        
        if($data_now>0)
        {   $data['copy'] = false;
            $data['pesan'] = "Data tahun ".$tahun." sudah ada";
        }
        else
        {    
            foreach ($data_prev as $dp) {
                $data_new = new PgdlReportDashboardSetting;
                $data_new->pgdl_report_dashboard_page_id = $dp->pgdl_report_dashboard_page_id;
                $data_new->judul_kolom = $dp->judul_kolom;
                $data_new->pgdl_report_dashboard_source_id = $dp->pgdl_report_dashboard_source_id;
                $data_new->jenis_id = $dp->jenis_id;
                $data_new->pgdl_sheet_name = $dp->pgdl_sheet_name;
                $data_new->kolom = $dp->kolom;
                $data_new->sequence = $dp->sequence;
                $data_new->tahun = $tahun;
                $data_new->save();
            }
            $data['copy'] = true;
            $data['pesan'] = "Berhasil kopi data tahun ".$tahun_prev." ke tahun ".$tahun;
        }
        
        return $data;

    }

}
