<?php

namespace App\Http\Controllers\Pengendalian;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Entities\StrategiBisnis;
use App\Entities\Fase;
use App\Entities\Distrik;
use App\Entities\PGDLHistoryLog;
use App\Entities\PgdlReportDashboardSetting;
use App\Entities\Lokasi;
use App\Entities\User;
use App\Entities\Template;
use App\Entities\Role;
use Illuminate\Support\Facades\Input;
use DB;
// use Input;
use Excel;
use PDF;

// History Log AO:
// - RKAU
// - Reimburse
// - Rutin
// - Bahan Bakar
// - Penyusutan

// History Log AI :
// - Pengembangan Usaha
// - Penguatan KIT
// - PLN

class HistoryLogController extends Controller
{
    public function indexai(Request $request)
    {
        $data = Input::all();
        // dd('1');
        $judul = 'AI';

        $ai = ['Form 10 - Pengembangan Usaha', 'Form 10 - Penguatan KIT', 'Form 10 - PLN'];

        $user_id = session('user_id');
        $user = User::find($user_id);
        $role_id = session('role_id');
        $role = Role::find($role_id);

        //kantor pusat
        if($role->is_kantor_pusat) {
            $sb = StrategiBisnis::all();
        }
        else {
            $sb = StrategiBisnis::where('id', $user->distrik->strategi_bisnis->id)->get();
        }

        $pgdl_history_log = DB::table('pgdl_history_logs')
        ->join('pgdl_file_imports_revisi', 'pgdl_history_logs.pgdl_file_import_revisi_id', '=', 'pgdl_file_imports_revisi.id')
        ->join('pgdl_templates', 'pgdl_file_imports_revisi.pgdl_template_id', '=', 'pgdl_templates.id')
        ->join('jenis', 'pgdl_templates.jenis_id', '=', 'jenis.id')
        ->whereIn('jenis.name', $ai)
        ->join('users', 'users.id', '=', 'pgdl_history_logs.user_id')
        ->where('pgdl_file_imports_revisi.distrik_id', $request->input('distrik'))
        ->select('pgdl_history_logs.prk', 'pgdl_history_logs.keterangan', 'pgdl_history_logs.identity_prk', 'pgdl_history_logs.deskripsi_prk_awal', 'pgdl_history_logs.deskripsi_prk_akhir', 'pgdl_history_logs.beban_awal', 'pgdl_history_logs.beban_akhir', 'pgdl_history_logs.cashflow_awal', 'pgdl_history_logs.cashflow_akhir', 'pgdl_history_logs.ijin_proses_awal', 'pgdl_history_logs.ijin_proses_akhir', 'pgdl_history_logs.user_id', 'pgdl_history_logs.created_at', 'users.name')->get();

        $fase = Fase::all();
        $tahun = Template::select('tahun')->where('jenis_id', 2)->orWhere('jenis_id',1)->orWhere('jenis_id',3)->distinct()->get();

        $input_tahun = $request->input('tahun_anggaran');
        $input_sb = $request->input('strategi_bisnis');
        $input_distrik = $request->input('distrik');
        $int_input_distrik = (int)$input_distrik;
        $input_lokasi = $request->input('lokasi');
        $int_input_lokasi = (int)$input_lokasi;
        $input_fase = $request->input('fase');
        $input_bulan = $request->input('bulan');
        $int_input_bulan = (int)$input_bulan;

        if(!$input_tahun)
        {
            return redirect()->action('PencarianReportDashboardPengendalianController@pencarian');
        }
        if($input_sb<1 || $input_sb>2)
        {
            return redirect()->action('PencarianReportDashboardPengendalianController@pencarian');
        }
        $distrik_id = Distrik::pluck('id')->toArray();
        if(!in_array($int_input_distrik,$distrik_id)){
            return redirect()->action('PencarianReportDashboardPengendalianController@pencarian');
        }
        $fase_id = Fase::pluck('id')->toArray();
        if(!in_array($input_fase,$fase_id)){
            return redirect()->action('PencarianReportDashboardPengendalianController@pencarian');
        }


        $nama_bln[0] = '';
        $nama_bln[1] = 'Januari';
        $nama_bln[2] = 'Februari';
        $nama_bln[3] = 'Maret';
        $nama_bln[4] = 'April';
        $nama_bln[5] = 'Mei';
        $nama_bln[6] = 'Juni';
        $nama_bln[7] = 'Juli';
        $nama_bln[8] = 'Agustus';
        $nama_bln[9] = 'September';
        $nama_bln[10] = 'Oktober';
        $nama_bln[11] = 'November';
        $nama_bln[12] = 'Desember';

        // $nama_bln_dipilih = $nama_bln[$int_input_bulan];
        if( $int_input_bulan >=1 && $int_input_bulan<=12){
            $nama_bln_dipilih = $nama_bln[$int_input_bulan];
        }else{
            return redirect()->action('PencarianReportDashboardPengendalianController@pencarian');
        }

        $jenis_form_yg_digunakan = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', 8)
                                ->whereNotNull('jenis_id')
                                ->where('tahun', $input_tahun)
                                ->select('jenis_id')
                                ->distinct()
                                ->get();
        $notification_failed = '';

        if ($request->input('strategi_bisnis') != NULL) {
            $input_sb = DB::table('strategi_bisnis')->select('name','id')->where('id', $request->input('strategi_bisnis'))->get()[0];
            $distrik = Distrik::select('name','id')->where('strategi_bisnis_id',$input_sb->id)->get();
        }
        if ($request->input('distrik') != NULL) {
            $input_distrik = DB::table('distrik')->select('name','id')->where('id', $request->distrik)->get()[0];
            $lokasi = Lokasi::select('name','id')->where('distrik_id',$input_distrik->id)->get();
        }
        if ($request->input('lokasi') != NULL) {
            $input_lokasi = DB::table('lokasi')->select('name','id')->where('id', $request->lokasi)->get()[0];
        }

        $input_lokasi = Lokasi::where('distrik_id', $request->distrik)->select("name", "id")->get();

        if ($request->input('fase') != NULL) {
            $input_fase = DB::table('fases')->select('name','id')->where('id', $request->fase)->get()[0];
        }

    	if($request->type){
            if($request->type=='excel'){

                Excel::create('History Log AI', function ($excel) use ($pgdl_history_log, $sb, $fase, $input_tahun, $input_sb, $input_distrik, $input_lokasi, $input_fase, $nama_bln_dipilih, $judul) {
                        $excel->setTitle('History Log AI');
                        $excel->setCreator('Laravel')->setCompany('Profio', 'PJB');
                        $excel->setDescription('History Log AI');
                        $excel->sheet('History Log', function ($sheet) use ($pgdl_history_log, $sb, $fase, $input_tahun, $input_sb, $input_distrik, $input_lokasi, $input_fase, $nama_bln_dipilih, $judul) {
                            $sheet->loadView('pengendalian_output.history_log.excel')
                                        ->with('sb', $sb)
                                        ->with('fase', $fase)
                                        ->with('input_tahun', $input_tahun)
                                        ->with('input_sb', $input_sb)
                                        ->with('input_distrik', $input_distrik)
                                        ->with('input_lokasi', $input_lokasi)
                                        ->with('input_fase', $input_fase)
                                        ->with('pgdl_history_log', $pgdl_history_log)
                                        ->with('nama_bln_dipilih', $nama_bln_dipilih)
                                        ->with('judul', $judul);
                    });
                })->download('xlsx');
            }
        }

        if(count($jenis_form_yg_digunakan) == 0){
            $notification_failed = 'Setting Report Dashboard Status History Log untuk tahun '.$input_tahun.' belum dibuat!';
            return view('pengendalian_output.history_log.index', compact('judul','pgdl_history_log','input_tahun', 'input_sb', 'input_distrik', 'input_lokasi', 'input_fase', 'nama_bln_dipilih', 'notification_failed'));
        }

        return view('pengendalian_output.history_log.index', compact('pgdl_history_log','input_tahun', 'input_sb', 'input_distrik', 'input_lokasi', 'input_fase', 'nama_bln_dipilih', 'notification_failed', 'judul'));

    }

    public function indexao(Request $request)
    {
        // - Pengembangan Usaha
        // - Penguatan KIT
        // - PLN
        $data = Input::all();
        // dd('2');
        $judul = 'AO';

        $ao = [ 'RKAU', 'Form 6 - Reimburse', 'Form 6 - Rutin', 'Form Bahan Bakar', 'Penyusutan' ];

        $user_id = session('user_id');
        $user = User::find($user_id);
        $role_id = session('role_id');
        $role = Role::find($role_id);

        //kantor pusat
        if($role->is_kantor_pusat) {
            $sb = StrategiBisnis::all();
        }
        else {
            $sb = StrategiBisnis::where('id', $user->distrik->strategi_bisnis->id)->get();
        }

        $pgdl_history_log = DB::table('pgdl_history_logs')
        ->join('pgdl_file_imports_revisi', 'pgdl_history_logs.pgdl_file_import_revisi_id', '=', 'pgdl_file_imports_revisi.id')
        ->join('pgdl_templates', 'pgdl_file_imports_revisi.pgdl_template_id', '=', 'pgdl_templates.id')
        ->join('jenis', 'pgdl_templates.jenis_id', '=', 'jenis.id')
        ->whereIn('jenis.name', $ao)
        ->join('users', 'users.id', '=', 'pgdl_history_logs.user_id')
        ->where('pgdl_file_imports_revisi.distrik_id', $request->input('distrik'))
        ->select('pgdl_history_logs.prk', 'pgdl_history_logs.keterangan', 'pgdl_history_logs.identity_prk', 'pgdl_history_logs.deskripsi_prk_awal', 'pgdl_history_logs.deskripsi_prk_akhir', 'pgdl_history_logs.beban_awal', 'pgdl_history_logs.beban_akhir', 'pgdl_history_logs.cashflow_awal', 'pgdl_history_logs.cashflow_akhir', 'pgdl_history_logs.ijin_proses_awal', 'pgdl_history_logs.ijin_proses_akhir', 'pgdl_history_logs.user_id', 'pgdl_history_logs.created_at', 'users.name')->get();

        $fase = Fase::all();
        $tahun = Template::select('tahun')->where('jenis_id', 2)->orWhere('jenis_id',1)->orWhere('jenis_id',3)->distinct()->get();

        $input_tahun = $request->input('tahun_anggaran');
        $input_sb = $request->input('strategi_bisnis');
        $input_distrik = $request->input('distrik');
        $int_input_distrik = (int)$input_distrik;
        $input_lokasi = $request->input('lokasi');
        $int_input_lokasi = (int)$input_lokasi;
        $input_fase = $request->input('fase');
        $input_bulan = $request->input('bulan');
        $int_input_bulan = (int)$input_bulan;

        if(!$input_tahun)
        {
            return redirect()->action('PencarianReportDashboardPengendalianController@pencarian');
        }
        if($input_sb<1 || $input_sb>2)
        {
            return redirect()->action('PencarianReportDashboardPengendalianController@pencarian');
        }
        $distrik_id = Distrik::pluck('id')->toArray();
        if(!in_array($int_input_distrik,$distrik_id)){
            return redirect()->action('PencarianReportDashboardPengendalianController@pencarian');
        }
        $fase_id = Fase::pluck('id')->toArray();
        if(!in_array($input_fase,$fase_id)){
            return redirect()->action('PencarianReportDashboardPengendalianController@pencarian');
        }


        $nama_bln[0] = '';
        $nama_bln[1] = 'Januari';
        $nama_bln[2] = 'Februari';
        $nama_bln[3] = 'Maret';
        $nama_bln[4] = 'April';
        $nama_bln[5] = 'Mei';
        $nama_bln[6] = 'Juni';
        $nama_bln[7] = 'Juli';
        $nama_bln[8] = 'Agustus';
        $nama_bln[9] = 'September';
        $nama_bln[10] = 'Oktober';
        $nama_bln[11] = 'November';
        $nama_bln[12] = 'Desember';

        $jenis_form_yg_digunakan = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', 8)
                                ->whereNotNull('jenis_id')
                                ->where('tahun', $input_tahun)
                                ->select('jenis_id')
                                ->distinct()
                                ->get();
        $notification_failed = '';

        // $nama_bln_dipilih = $nama_bln[$int_input_bulan];
        if( $int_input_bulan >=1 && $int_input_bulan<=12){
            $nama_bln_dipilih = $nama_bln[$int_input_bulan];
        }else{
            return redirect()->action('PencarianReportDashboardPengendalianController@pencarian');
        }

        if ($request->input('strategi_bisnis') != NULL) {
            $input_sb = DB::table('strategi_bisnis')->select('name','id')->where('id', $request->input('strategi_bisnis'))->get()[0];
            $distrik = Distrik::select('name','id')->where('strategi_bisnis_id',$input_sb->id)->get();
        }
        if ($request->input('distrik') != NULL) {
            $input_distrik = DB::table('distrik')->select('name','id')->where('id', $request->distrik)->get()[0];
            $lokasi = Lokasi::select('name','id')->where('distrik_id',$input_distrik->id)->get();
        }
        if ($request->input('lokasi') != NULL) {
            $input_lokasi = DB::table('lokasi')->select('name','id')->where('id', $request->lokasi)->get()[0];
        }

        $input_lokasi = Lokasi::where('distrik_id', $request->distrik)->select("name", "id")->get();

        if ($request->input('fase') != NULL) {
            $input_fase = DB::table('fases')->select('name','id')->where('id', $request->fase)->get()[0];
        }

        if($request->type){
            if($request->type=='excel'){

                Excel::create('History Log AO', function ($excel) use ($pgdl_history_log, $sb, $fase, $input_tahun, $input_sb, $input_distrik, $input_lokasi, $input_fase, $nama_bln_dipilih, $judul) {
                        $excel->setTitle('History Log AO');
                        $excel->setCreator('Laravel')->setCompany('Profio', 'PJB');
                        $excel->setDescription('History Log AO');
                        $excel->sheet('History Log', function ($sheet) use ($pgdl_history_log, $sb, $fase, $input_tahun, $input_sb, $input_distrik, $input_lokasi, $input_fase, $nama_bln_dipilih, $judul) {
                            $sheet->loadView('pengendalian_output.history_log.excel')
                                        ->with('sb', $sb)
                                        ->with('fase', $fase)
                                        ->with('input_tahun', $input_tahun)
                                        ->with('input_sb', $input_sb)
                                        ->with('input_distrik', $input_distrik)
                                        ->with('input_lokasi', $input_lokasi)
                                        ->with('input_fase', $input_fase)
                                        ->with('pgdl_history_log', $pgdl_history_log)
                                        ->with('nama_bln_dipilih', $nama_bln_dipilih)
                                        ->with('judul', $judul);
                    });
                })->download('xlsx');
            }
        }

        if(count($jenis_form_yg_digunakan) == 0){
            $notification_failed = 'Setting Report Dashboard Status History Log untuk tahun '.$input_tahun.' belum dibuat!';
            return view('pengendalian_output.history_log.index', compact('judul','pgdl_history_log','input_tahun', 'input_sb', 'input_distrik', 'input_lokasi', 'input_fase', 'nama_bln_dipilih', 'notification_failed'));
        }

        return view('pengendalian_output.history_log.index', compact('pgdl_history_log','input_tahun', 'input_sb', 'input_distrik', 'input_lokasi', 'input_fase', 'nama_bln_dipilih', 'notification_failed', 'judul'));

    }

}
