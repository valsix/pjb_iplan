<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\StrategiBisnis;
use App\Entities\Fase;
use App\Entities\Distrik;
use App\Entities\Lokasi;
use App\Entities\Jenis;
use App\Entities\Template;
use App\Entities\User;
use App\Entities\Role;
use Illuminate\Support\Facades\DB;
use Excel;
use PDF;
use Illuminate\Support\Facades\Input;

class PencarianReportDashboardController extends Controller
{
    public function pencarian(Request $request)
    {
        $data = Input::all();
        // dd($data);

        $user_id = session('user_id');
        $user = User::find($user_id);
        $role_id = session('role_id');
        $role = Role::find($role_id);

        //kantor pusat
        if ($role->is_kantor_pusat) {
            $sb = StrategiBisnis::all();
        } else {
            $sb = StrategiBisnis::where('id', $user->distrik->strategi_bisnis->id)->get();
        }

        $fase = Fase::all();
        $tahun = Template::select('tahun')->where('jenis_id', 2)->orWhere('jenis_id', 1)->orWhere('jenis_id', 3)->distinct()->get();
        $months = array('Kumulatif', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');

        $input_tahun = $request->input('tahun_anggaran');
        $input_bulan = $request->input('bulan');
        $input_sb = $request->input('strategi_bisnis');
        $input_distrik = $request->input('distrik');
        $input_lokasi = $request->input('lokasi');
        $int_input_lokasi = (int) $input_lokasi;
        $input_fase = $request->input('fase');

        $input_draft_rkau = $request->input('draft_rkau');
        $input_draft_form_6_reimburse = $request->input('draft_form_6_reimburse');
        $input_draft_form_6_rutin = $request->input('draft_form_6_rutin');
        $input_draft_form_10_pu = $request->input('draft_form_10_pu');
        $input_draft_form_10_pk = $request->input('draft_form_10_pk');
        $input_draft_form_10_pln = $request->input('draft_form_10_pln');
        $input_draft_form_bahan_bakar = $request->input('draft_form_bahan_bakar');
        $input_draft_form_penyusutan = $request->input('draft_form_penyusutan');
        $input_draft_risk_profile = $request->input('draft_risk_profile');

        if ($request->input('strategi_bisnis') != NULL) {
            $input_sb = DB::table('strategi_bisnis')->select('name', 'id')->where('id', $request->input('strategi_bisnis'))->get()[0];
            $distrik = Distrik::select('name', 'id')->where('strategi_bisnis_id', $input_sb->id)->get();
        }
        if ($request->input('distrik') != NULL) {
            $input_distrik = DB::table('distrik')->select('name', 'id')->where('id', $request->distrik)->get()[0];
            $lokasi = Lokasi::select('name', 'id')->where('distrik_id', $input_distrik->id)->get();
        }
        if ($request->input('lokasi') != NULL) {
            $input_lokasi = DB::table('lokasi')->select('name', 'id')->where('id', $request->lokasi)->get()[0];
        }
        if ($request->input('fase') != NULL) {
            $input_fase = DB::table('fases')->select('name', 'id')->where('id', $request->fase)->get()[0];
        }
        if ($request->input('draft_rkau') != NULL) {
            $input_draft_rkau = DB::table('file_imports')->select('draft_versi', 'id')->where('id', $request->draft_rkau)->get()[0];
            $draft_form_rkau = $this->get_drafts(1, $int_input_lokasi, $input_tahun, $input_fase->id);
        }
        if ($request->input('draft_form_6_reimburse') != NULL) {
            $input_draft_form_6_reimburse = DB::table('file_imports')->select('draft_versi', 'id')->where('id', $request->draft_form_6_reimburse)->get()[0];
            $draft_form_6_reimburse = $this->get_drafts(2, $int_input_lokasi, $input_tahun, $input_fase->id);
        }
        if ($request->input('draft_form_6_rutin') != NULL) {
            $input_draft_form_6_rutin = DB::table('file_imports')->select('draft_versi', 'id')->where('id', $request->draft_form_6_rutin)->get()[0];
            $draft_form_6_rutin = $this->get_drafts(3, $int_input_lokasi, $input_tahun, $input_fase->id);
        }
        if ($request->input('draft_form_10_pu') != NULL) {
            $input_draft_form_10_pu = DB::table('file_imports')->select('draft_versi', 'id')->where('id', $request->draft_form_10_pu)->get()[0];
            $draft_form_10_pu = $this->get_drafts(4, $int_input_lokasi, $input_tahun, $input_fase->id);
        }
        if ($request->input('draft_form_10_pk') != NULL) {
            $input_draft_form_10_pk = DB::table('file_imports')->select('draft_versi', 'id')->where('id', $request->draft_form_10_pk)->get()[0];
            $draft_form_10_pk = $this->get_drafts(5, $int_input_lokasi, $input_tahun, $input_fase->id);
        }
        if ($request->input('draft_form_10_pln') != NULL) {
            $input_draft_form_10_pln = DB::table('file_imports')->select('draft_versi', 'id')->where('id', $request->draft_form_10_pln)->get()[0];
            $draft_form_10_pln = $this->get_drafts(6, $int_input_lokasi, $input_tahun, $input_fase->id);
        }
        if ($request->input('draft_form_bahan_bakar') != NULL) {
            $input_draft_form_bahan_bakar = DB::table('file_imports')->select('draft_versi', 'id')->where('id', $request->draft_form_bahan_bakar)->get()[0];
            $draft_form_bahan_bakar = $this->get_drafts(7, $int_input_lokasi, $input_tahun, $input_fase->id);
        }
        if ($request->input('draft_form_penyusutan') != NULL) {
            $input_draft_form_penyusutan = DB::table('file_imports')->select('draft_versi', 'id')->where('id', $request->draft_form_penyusutan)->get()[0];
            $draft_form_penyusutan = $this->get_drafts(9, $int_input_lokasi, $input_tahun, $input_fase->id);
        }
        if ($request->input('draft_risk_profile') != NULL) {
            $input_draft_risk_profile = DB::table('file_imports')->select('draft_versi', 'id')->where('id', $request->draft_risk_profile)->get()[0];
            $draft_form_risk_profile = $this->get_drafts(8, $int_input_lokasi, $input_tahun, $input_fase->id);
        }

        $input_draft_request_rkau = $request->input('draft_rkau');
        $input_draft_request_form_6_reimburse = $request->input('draft_form_6_reimburse');
        $input_draft_request_form_6_rutin = $request->input('draft_form_6_rutin');
        $input_draft_request_form_10_pu = $request->input('draft_form_10_pu');
        $input_draft_request_form_10_pk = $request->input('draft_form_10_pk');
        $input_draft_request_form_10_pln = $request->input('draft_form_10_pln');
        $input_draft_request_form_bahan_bakar = $request->input('draft_form_bahan_bakar');
        $input_draft_request_form_penyusutan = $request->input('draft_form_penyusutan');
        $input_draft_request_risk_profile = $request->input('draft_risk_profile');

        return view('output/pencarian', compact(
            'sb',
            'fase',
            'input_tahun',
            'input_sb',
            'input_distrik',
            'input_lokasi',
            'input_fase',
            'input_draft_rkau',
            'input_draft_form_6_reimburse',
            'input_draft_form_6_rutin',
            'input_draft_form_10_pk',
            'input_draft_form_10_pu',
            'input_draft_form_10_pln',
            'input_draft_form_penyusutan',
            'input_draft_form_bahan_bakar',
            'input_draft_risk_profile',
            'distrik',
            'lokasi',
            'tahun',
            'draft_form_rkau',
            'draft_form_penyusutan',
            'draft_form_10_pln',
            'draft_form_10_pu',
            'draft_form_10_pk',
            'draft_form_6_reimburse',
            'draft_form_6_rutin',
            'draft_form_bahan_bakar',
            'draft_form_risk_profile',
            'months',
            'input_bulan',
            'input_draft_request_rkau',
            'input_draft_request_form_6_reimburse',
            'input_draft_request_form_6_rutin',
            'input_draft_request_form_10_pu',
            'input_draft_request_form_10_pk',
            'input_draft_request_form_10_pln',
            'input_draft_request_form_bahan_bakar',
            'input_draft_request_form_penyusutan',
            'input_draft_request_risk_profile'
        ));
    }

    public function Ajax($id)
    {
        $user_id = session('user_id');
        $user = User::find($user_id);
        $role_id = session('role_id');
        $role = Role::find($role_id);

        //kantor pusat
        if ($role->is_kantor_pusat) {
            $ds = Distrik::where('strategi_bisnis_id', $id)->select("name", "id")->get();
        } else {
            $ds = Distrik::where('id', $user->distrik_id)->select("name", "id")->get();
        }

        return json_encode($ds);
    }

    public function myformAjax2($id)
    {
        $lokasi = Lokasi::where('distrik_id', $id)->select("name", "id")->get();

        return json_encode($lokasi);
    }

    public function ajax_fase()
    {
        $fase = Fase::get();

        return json_encode($fase);
    }

    public function ajax_draft_rkau($id_lokasi, $id_tahun, $id_fase)
    {
        if ($id_fase == 4) { // 4 == interchange 
            $draft_rkau = DB::select("select distinct f.id, f.draft_versi, f.name, f.name 
            from file_imports f 
            join templates t on f.template_id = t.id
            join excel_datas e on e.file_import_id = f.id
            where f.fase_id= " . $id_fase . " and t.jenis_id=1 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
            group by f.id, f.draft_versi, f.name, f.name;");

            return json_encode($draft_rkau);
        }

        $draft_rkau_final =
            DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
            from file_imports f 
            join templates t on f.template_id = t.id
            join excel_datas e on e.file_import_id = f.id
            join file_approval fa on fa.file_import_id = f.id
            join file_approval_status fas on fas.id = fa.file_approval_status_id
            join approval app on app.id = fa.approval_id
            where t.jenis_id=1 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
            and app.fase_id = 3
            and fa.file_approval_status_id = 4
            and app.urutan = (select max(urutan) from approval where approval.fase_id = " . $id_fase . ")
            group by f.id, f.draft_versi, f.name, fas.name");

        $draft_rkau =
            DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
            from file_imports f 
            join templates t on f.template_id = t.id
            join excel_datas e on e.file_import_id = f.id
            join file_approval fa on fa.file_import_id = f.id
            join file_approval_status fas on fas.id = fa.file_approval_status_id
            join approval app on app.id = fa.approval_id
            where t.jenis_id=1 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
            and app.fase_id = " . $id_fase . "
            and fa.file_approval_status_id = 4
            and app.urutan = (select max(urutan) from approval where approval.fase_id = " . $id_fase . ")
            group by f.id, f.draft_versi, f.name, fas.name");

        //jika ada draft yg sudah final
        if ($draft_rkau_final) {
            return json_encode($draft_rkau_final);
        } else {
            //jika ada draft yg sudah diapproved oleh urutan terakhir pada fase tsb (misal GM/Kadiv Anggaran)
            if ($draft_rkau) {
                return json_encode($draft_rkau);
            } else {
                // ambil draft yg status nya draft/submitted/approved (tetapi belum urutan terakhir)
                $draft_rkau =
                    DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
                        from file_imports f 
                        join templates t on f.template_id = t.id
                        join excel_datas e on e.file_import_id = f.id
                        join file_approval fa on fa.file_import_id = f.id
                        join file_approval_status fas on fas.id = fa.file_approval_status_id
                        join approval app on app.id = fa.approval_id
                        where t.jenis_id=1 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
                        and app.fase_id = " . $id_fase . "
                        group by f.id, f.draft_versi, f.name, fas.name");

                return json_encode($draft_rkau);
            }
        }
    }

    public function ajax_draft_form_6_reimburse($id_lokasi, $id_tahun, $id_fase)
    {
        if ($id_fase == 4) { // 4 == interchange 
            $draft_form_6_reimburse = DB::select("select distinct f.id, f.draft_versi, f.name 
            from file_imports f 
            join templates t on f.template_id = t.id
            join excel_datas e on e.file_import_id = f.id
            where f.fase_id= " . $id_fase . " and t.jenis_id=2 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
            group by f.id, f.draft_versi, f.name;");

            return json_encode($draft_form_6_reimburse);
        }

        $draft_form_6_reimburse_final =
            DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
            from file_imports f 
            join templates t on f.template_id = t.id
            join excel_datas e on e.file_import_id = f.id
            join file_approval fa on fa.file_import_id = f.id
            join file_approval_status fas on fas.id = fa.file_approval_status_id
            join approval app on app.id = fa.approval_id
            where t.jenis_id=2 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
            and app.fase_id = 3
            and fa.file_approval_status_id = 4
            and app.urutan = (select max(urutan) from approval where approval.fase_id = " . $id_fase . ")
            group by f.id, f.draft_versi, f.name, fas.name");

        $draft_form_6_reimburse =
            DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
            from file_imports f 
            join templates t on f.template_id = t.id
            join excel_datas e on e.file_import_id = f.id
            join file_approval fa on fa.file_import_id = f.id
            join file_approval_status fas on fas.id = fa.file_approval_status_id
            join approval app on app.id = fa.approval_id
            where t.jenis_id=2 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
            and app.fase_id = " . $id_fase . "
            and fa.file_approval_status_id = 4
            and app.urutan = (select max(urutan) from approval where approval.fase_id = " . $id_fase . ")
            group by f.id, f.draft_versi, f.name, fas.name");

        //jika ada draft yg sudah final
        if ($draft_form_6_reimburse_final) {
            return json_encode($draft_form_6_reimburse_final);
        } else {
            //jika ada draft yg sudah diapproved oleh urutan terakhir pada fase tsb (misal GM/Kadiv Anggaran)
            if ($draft_form_6_reimburse) {
                return json_encode($draft_form_6_reimburse);
            } else {
                // ambil draft yg status nya draft/submitted/approved (tetapi belum urutan terakhir)
                $draft_form_6_reimburse =
                    DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
                        from file_imports f 
                        join templates t on f.template_id = t.id
                        join excel_datas e on e.file_import_id = f.id
                        join file_approval fa on fa.file_import_id = f.id
                        join file_approval_status fas on fas.id = fa.file_approval_status_id
                        join approval app on app.id = fa.approval_id
                        where t.jenis_id=2 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
                        and app.fase_id = " . $id_fase . "
                        group by f.id, f.draft_versi, f.name, fas.name");

                return json_encode($draft_form_6_reimburse);
            }
        }
    }

    public function ajax_draft_form_6_rutin($id_lokasi, $id_tahun, $id_fase)
    {
        if ($id_fase == 4) { // 4 == interchange 
            $draft_form_6_rutin = DB::select("select distinct f.id, f.draft_versi, f.name 
            from file_imports f 
            join templates t on f.template_id = t.id
            join excel_datas e on e.file_import_id = f.id
            where f.fase_id= " . $id_fase . " and t.jenis_id=3 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
            group by f.id, f.draft_versi, f.name;");

            return json_encode($draft_form_6_rutin);
        }

        $draft_form_6_rutin_final =
            DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
            from file_imports f 
            join templates t on f.template_id = t.id
            join excel_datas e on e.file_import_id = f.id
            join file_approval fa on fa.file_import_id = f.id
            join file_approval_status fas on fas.id = fa.file_approval_status_id
            join approval app on app.id = fa.approval_id
            where t.jenis_id=3 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
            and app.fase_id = 3
            and fa.file_approval_status_id = 4
            and app.urutan = (select max(urutan) from approval where approval.fase_id = " . $id_fase . ")
            group by f.id, f.draft_versi, f.name, fas.name");

        $draft_form_6_rutin =
            DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
            from file_imports f 
            join templates t on f.template_id = t.id
            join excel_datas e on e.file_import_id = f.id
            join file_approval fa on fa.file_import_id = f.id
            join file_approval_status fas on fas.id = fa.file_approval_status_id
            join approval app on app.id = fa.approval_id
            where t.jenis_id=3 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
            and app.fase_id = " . $id_fase . "
            and fa.file_approval_status_id = 4
            and app.urutan = (select max(urutan) from approval where approval.fase_id = " . $id_fase . ")
            group by f.id, f.draft_versi, f.name, fas.name");

        //jika ada draft yg sudah final
        if ($draft_form_6_rutin_final) {
            return json_encode($draft_form_6_rutin_final);
        } else {
            //jika ada draft yg sudah diapproved oleh urutan terakhir pada fase tsb (misal GM/Kadiv Anggaran)
            if ($draft_form_6_rutin) {
                return json_encode($draft_form_6_rutin);
            } else {
                // ambil draft yg status nya draft/submitted/approved (tetapi belum urutan terakhir)
                $draft_form_6_rutin =
                    DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
                        from file_imports f 
                        join templates t on f.template_id = t.id
                        join excel_datas e on e.file_import_id = f.id
                        join file_approval fa on fa.file_import_id = f.id
                        join file_approval_status fas on fas.id = fa.file_approval_status_id
                        join approval app on app.id = fa.approval_id
                        where t.jenis_id=3 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
                        and app.fase_id = " . $id_fase . "
                        group by f.id, f.draft_versi, f.name, fas.name");

                return json_encode($draft_form_6_rutin);
            }
        }

        // return json_encode($draft_form_6_rutin);
    }

    public function ajax_draft_form_10_pengembangan_usaha($id_lokasi, $id_tahun, $id_fase)
    {
        if ($id_fase == 4) { // 4 == interchange 
            $draft_form_10_pu = DB::select("select distinct f.id, f.draft_versi, f.name 
            from file_imports f 
            join templates t on f.template_id = t.id
            join excel_datas e on e.file_import_id = f.id
            where f.fase_id= " . $id_fase . " and  t.jenis_id=4 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
            group by f.id, f.draft_versi, f.name;");

            return json_encode($draft_form_10_pu);
        }

        $draft_form_10_pu_final =
            DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
            from file_imports f 
            join templates t on f.template_id = t.id
            join excel_datas e on e.file_import_id = f.id
            join file_approval fa on fa.file_import_id = f.id
            join file_approval_status fas on fas.id = fa.file_approval_status_id
            join approval app on app.id = fa.approval_id
            where t.jenis_id=4 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
            and app.fase_id = 3
            and fa.file_approval_status_id = 4
            and app.urutan = (select max(urutan) from approval where approval.fase_id = " . $id_fase . ")
            group by f.id, f.draft_versi, f.name, fas.name");

        $draft_form_10_pu =
            DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
            from file_imports f 
            join templates t on f.template_id = t.id
            join excel_datas e on e.file_import_id = f.id
            join file_approval fa on fa.file_import_id = f.id
            join file_approval_status fas on fas.id = fa.file_approval_status_id
            join approval app on app.id = fa.approval_id
            where t.jenis_id=4 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
            and app.fase_id = " . $id_fase . "
            and fa.file_approval_status_id = 4
            and app.urutan = (select max(urutan) from approval where approval.fase_id = " . $id_fase . ")
            group by f.id, f.draft_versi, f.name, fas.name");

        //jika ada draft yg sudah final
        if ($draft_form_10_pu_final) {
            return json_encode($draft_form_10_pu_final);
        } else {
            //jika ada draft yg sudah diapproved oleh urutan terakhir pada fase tsb (misal GM/Kadiv Anggaran)
            if ($draft_form_10_pu) {
                return json_encode($draft_form_10_pu);
            } else {
                // ambil draft yg status nya draft/submitted/approved (tetapi belum urutan terakhir)
                $draft_form_10_pu =
                    DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
                        from file_imports f 
                        join templates t on f.template_id = t.id
                        join excel_datas e on e.file_import_id = f.id
                        join file_approval fa on fa.file_import_id = f.id
                        join file_approval_status fas on fas.id = fa.file_approval_status_id
                        join approval app on app.id = fa.approval_id
                        where t.jenis_id=4 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
                        and app.fase_id = " . $id_fase . "
                        group by f.id, f.draft_versi, f.name, fas.name");

                return json_encode($draft_form_10_pu);
            }
        }
    }

    public function ajax_draft_form_10_penguatan_kit($id_lokasi, $id_tahun, $id_fase)
    {
        if ($id_fase == 4) { // 4 == interchange 
            $draft_form_10_pk = DB::select("select distinct f.id, f.draft_versi, f.name 
            from file_imports f 
            join templates t on f.template_id = t.id
            join excel_datas e on e.file_import_id = f.id
            where f.fase_id= " . $id_fase . " and t.jenis_id= 5 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
            group by f.id, f.draft_versi, f.name;");

            return json_encode($draft_form_10_pk);
        }

        $draft_form_10_pk_final =
            DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
            from file_imports f 
            join templates t on f.template_id = t.id
            join excel_datas e on e.file_import_id = f.id
            join file_approval fa on fa.file_import_id = f.id
            join file_approval_status fas on fas.id = fa.file_approval_status_id
            join approval app on app.id = fa.approval_id
            where t.jenis_id=5 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
            and app.fase_id = 3
            and fa.file_approval_status_id = 4
            and app.urutan = (select max(urutan) from approval where approval.fase_id = " . $id_fase . ")
            group by f.id, f.draft_versi, f.name, fas.name");

        $draft_form_10_pk =
            DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
            from file_imports f 
            join templates t on f.template_id = t.id
            join excel_datas e on e.file_import_id = f.id
            join file_approval fa on fa.file_import_id = f.id
            join file_approval_status fas on fas.id = fa.file_approval_status_id
            join approval app on app.id = fa.approval_id
            where t.jenis_id=5 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
            and app.fase_id = " . $id_fase . "
            and fa.file_approval_status_id = 4
            and app.urutan = (select max(urutan) from approval where approval.fase_id = " . $id_fase . ")
            group by f.id, f.draft_versi, f.name, fas.name");

        //jika ada draft yg sudah final
        if ($draft_form_10_pk_final) {
            return json_encode($draft_form_10_pk_final);
        } else {
            //jika ada draft yg sudah diapproved oleh urutan terakhir pada fase tsb (misal GM/Kadiv Anggaran)
            if ($draft_form_10_pk) {
                return json_encode($draft_form_10_pk);
            } else {
                // ambil draft yg status nya draft/submitted/approved (tetapi belum urutan terakhir)
                $draft_form_10_pk =
                    DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
                        from file_imports f 
                        join templates t on f.template_id = t.id
                        join excel_datas e on e.file_import_id = f.id
                        join file_approval fa on fa.file_import_id = f.id
                        join file_approval_status fas on fas.id = fa.file_approval_status_id
                        join approval app on app.id = fa.approval_id
                        where t.jenis_id=5 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
                        and app.fase_id = " . $id_fase . "
                        group by f.id, f.draft_versi, f.name, fas.name");

                return json_encode($draft_form_10_pk);
            }
        }
    }

    public function ajax_draft_form_10_pln($id_lokasi, $id_tahun, $id_fase)
    {
        if ($id_fase == 4) { // 4 == interchange 
            $draft_form_10_pln = DB::select("select distinct f.id, f.draft_versi, f.name 
            from file_imports f 
            join templates t on f.template_id = t.id
            join excel_datas e on e.file_import_id = f.id
            where f.fase_id= " . $id_fase . " and t.jenis_id=6 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
            group by f.id, f.draft_versi, f.name;");

            return json_encode($draft_form_10_pln);
        }

        $draft_form_10_pln_final =
            DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
            from file_imports f 
            join templates t on f.template_id = t.id
            join excel_datas e on e.file_import_id = f.id
            join file_approval fa on fa.file_import_id = f.id
            join file_approval_status fas on fas.id = fa.file_approval_status_id
            join approval app on app.id = fa.approval_id
            where t.jenis_id=6 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
            and app.fase_id = 3
            and fa.file_approval_status_id = 4
            and app.urutan = (select max(urutan) from approval where approval.fase_id = " . $id_fase . ")
            group by f.id, f.draft_versi, f.name, fas.name");

        $draft_form_10_pln =
            DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
            from file_imports f 
            join templates t on f.template_id = t.id
            join excel_datas e on e.file_import_id = f.id
            join file_approval fa on fa.file_import_id = f.id
            join file_approval_status fas on fas.id = fa.file_approval_status_id
            join approval app on app.id = fa.approval_id
            where t.jenis_id=6 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
            and app.fase_id = " . $id_fase . "
            and fa.file_approval_status_id = 4
            and app.urutan = (select max(urutan) from approval where approval.fase_id = " . $id_fase . ")
            group by f.id, f.draft_versi, f.name, fas.name");

        //jika ada draft yg sudah final
        if ($draft_form_10_pln_final) {
            return json_encode($draft_form_10_pln_final);
        } else {
            //jika ada draft yg sudah diapproved oleh urutan terakhir pada fase tsb (misal GM/Kadiv Anggaran)
            if ($draft_form_10_pln) {
                return json_encode($draft_form_10_pln);
            } else {
                // ambil draft yg status nya draft/submitted/approved (tetapi belum urutan terakhir)
                $draft_form_10_pln =
                    DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
                        from file_imports f 
                        join templates t on f.template_id = t.id
                        join excel_datas e on e.file_import_id = f.id
                        join file_approval fa on fa.file_import_id = f.id
                        join file_approval_status fas on fas.id = fa.file_approval_status_id
                        join approval app on app.id = fa.approval_id
                        where t.jenis_id=6 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
                        and app.fase_id = " . $id_fase . "
                        group by f.id, f.draft_versi, f.name, fas.name");

                return json_encode($draft_form_10_pln);
            }
        }
    }

    public function ajax_draft_form_bahan_bakar($id_lokasi, $id_tahun, $id_fase)
    {
        if ($id_fase == 4) { // 4 == interchange 
            $draft_form_bahan_bakar = DB::select("select distinct f.id, f.draft_versi, f.name 
            from file_imports f 
            join templates t on f.template_id = t.id
            join excel_datas e on e.file_import_id = f.id
            where f.fase_id= " . $id_fase . " and t.jenis_id=7 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
            group by f.id, f.draft_versi, f.name;");

            return json_encode($draft_form_bahan_bakar);
        }

        $draft_form_bahan_bakar_final =
            DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
            from file_imports f 
            join templates t on f.template_id = t.id
            join excel_datas e on e.file_import_id = f.id
            join file_approval fa on fa.file_import_id = f.id
            join file_approval_status fas on fas.id = fa.file_approval_status_id
            join approval app on app.id = fa.approval_id
            where t.jenis_id=7 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
            and app.fase_id = 3
            and fa.file_approval_status_id = 4
            and app.urutan = (select max(urutan) from approval where approval.fase_id = " . $id_fase . ")
            group by f.id, f.draft_versi, f.name, fas.name");

        $draft_form_bahan_bakar =
            DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
            from file_imports f 
            join templates t on f.template_id = t.id
            join excel_datas e on e.file_import_id = f.id
            join file_approval fa on fa.file_import_id = f.id
            join file_approval_status fas on fas.id = fa.file_approval_status_id
            join approval app on app.id = fa.approval_id
            where t.jenis_id=7 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
            and app.fase_id = " . $id_fase . "
            and fa.file_approval_status_id = 4
            and app.urutan = (select max(urutan) from approval where approval.fase_id = " . $id_fase . ")
            group by f.id, f.draft_versi, f.name, fas.name");

        //jika ada draft yg sudah final
        if ($draft_form_bahan_bakar_final) {
            return json_encode($draft_form_bahan_bakar_final);
        } else {
            //jika ada draft yg sudah diapproved oleh urutan terakhir pada fase tsb (misal GM/Kadiv Anggaran)
            if ($draft_form_bahan_bakar) {
                return json_encode($draft_form_bahan_bakar);
            } else {
                // ambil draft yg status nya draft/submitted/approved (tetapi belum urutan terakhir)
                $draft_form_bahan_bakar =
                    DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
                        from file_imports f 
                        join templates t on f.template_id = t.id
                        join excel_datas e on e.file_import_id = f.id
                        join file_approval fa on fa.file_import_id = f.id
                        join file_approval_status fas on fas.id = fa.file_approval_status_id
                        join approval app on app.id = fa.approval_id
                        where t.jenis_id=7 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
                        and app.fase_id = " . $id_fase . "
                        group by f.id, f.draft_versi, f.name, fas.name");

                return json_encode($draft_form_bahan_bakar);
            }
        }
    }

    public function ajax_draft_form_penyusutan($id_lokasi, $id_tahun, $id_fase)
    {
        if ($id_fase == 4) { // 4 == interchange 
            $draft_form_penyusutan = DB::select("select distinct f.id, f.draft_versi, f.name 
            from file_imports f 
            join templates t on f.template_id = t.id
            join excel_datas e on e.file_import_id = f.id
            where f.fase_id= " . $id_fase . " and t.jenis_id=9 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
            group by f.id, f.draft_versi, f.name;");

            return json_encode($draft_form_penyusutan);
        }

        $draft_form_penyusutan_final =
            DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
            from file_imports f 
            join templates t on f.template_id = t.id
            join excel_datas e on e.file_import_id = f.id
            join file_approval fa on fa.file_import_id = f.id
            join file_approval_status fas on fas.id = fa.file_approval_status_id
            join approval app on app.id = fa.approval_id
            where t.jenis_id=9 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
            and app.fase_id = 3
            and fa.file_approval_status_id = 4
            and app.urutan = (select max(urutan) from approval where approval.fase_id = " . $id_fase . ")
            group by f.id, f.draft_versi, f.name, fas.name");

        $draft_form_penyusutan =
            DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
            from file_imports f 
            join templates t on f.template_id = t.id
            join excel_datas e on e.file_import_id = f.id
            join file_approval fa on fa.file_import_id = f.id
            join file_approval_status fas on fas.id = fa.file_approval_status_id
            join approval app on app.id = fa.approval_id
            where t.jenis_id=9 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
            and app.fase_id = " . $id_fase . "
            and fa.file_approval_status_id = 4
            and app.urutan = (select max(urutan) from approval where approval.fase_id = " . $id_fase . ")
            group by f.id, f.draft_versi, f.name, fas.name");

        //jika ada draft yg sudah final
        if ($draft_form_penyusutan_final) {
            return json_encode($draft_form_penyusutan_final);
        } else {
            //jika ada draft yg sudah diapproved oleh urutan terakhir pada fase tsb (misal GM/Kadiv Anggaran)
            if ($draft_form_penyusutan) {
                return json_encode($draft_form_penyusutan);
            } else {
                // ambil draft yg status nya draft/submitted/approved (tetapi belum urutan terakhir)
                $draft_form_penyusutan =
                    DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
                        from file_imports f 
                        join templates t on f.template_id = t.id
                        join excel_datas e on e.file_import_id = f.id
                        join file_approval fa on fa.file_import_id = f.id
                        join file_approval_status fas on fas.id = fa.file_approval_status_id
                        join approval app on app.id = fa.approval_id
                        where t.jenis_id=9 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
                        and app.fase_id = " . $id_fase . "
                        group by f.id, f.draft_versi, f.name, fas.name");

                return json_encode($draft_form_penyusutan);
            }
        }
    }

    public function ajax_draft_risk_profile($id_lokasi, $id_tahun, $id_fase)
    {
        if ($id_fase == 4) { // 4 == interchange 
            $draft_form_risk_profile = DB::select("select distinct f.id, f.draft_versi, f.name 
            from file_imports f 
            join templates t on f.template_id = t.id
            join excel_datas e on e.file_import_id = f.id
            where f.fase_id= " . $id_fase . " and t.jenis_id=8 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
            group by f.id, f.draft_versi, f.name;");

            return json_encode($draft_form_risk_profile);
        }

        $draft_form_risk_profile_final =
            DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
            from file_imports f 
            join templates t on f.template_id = t.id
            join excel_datas e on e.file_import_id = f.id
            join file_approval fa on fa.file_import_id = f.id
            join file_approval_status fas on fas.id = fa.file_approval_status_id
            join approval app on app.id = fa.approval_id
            where t.jenis_id=8 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
            and app.fase_id = 3
            and fa.file_approval_status_id = 4
            and app.urutan = (select max(urutan) from approval where approval.fase_id = " . $id_fase . ")
            group by f.id, f.draft_versi, f.name, fas.name");

        $draft_form_risk_profile =
            DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
            from file_imports f 
            join templates t on f.template_id = t.id
            join excel_datas e on e.file_import_id = f.id
            join file_approval fa on fa.file_import_id = f.id
            join file_approval_status fas on fas.id = fa.file_approval_status_id
            join approval app on app.id = fa.approval_id
            where t.jenis_id=8 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
            and app.fase_id = " . $id_fase . "
            and fa.file_approval_status_id = 4
            and app.urutan = (select max(urutan) from approval where approval.fase_id = " . $id_fase . ")
            group by f.id, f.draft_versi, f.name, fas.name");

        //jika ada draft yg sudah final
        if ($draft_form_risk_profile_final) {
            return json_encode($draft_form_risk_profile_final);
        } else {
            //jika ada draft yg sudah diapproved oleh urutan terakhir pada fase tsb (misal GM/Kadiv Anggaran)
            if ($draft_form_risk_profile) {
                return json_encode($draft_form_risk_profile);
            } else {
                // ambil draft yg status nya draft/submitted/approved (tetapi belum urutan terakhir)
                $draft_form_risk_profile =
                    DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
                        from file_imports f 
                        join templates t on f.template_id = t.id
                        join excel_datas e on e.file_import_id = f.id
                        join file_approval fa on fa.file_import_id = f.id
                        join file_approval_status fas on fas.id = fa.file_approval_status_id
                        join approval app on app.id = fa.approval_id
                        where t.jenis_id=8 and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
                        and app.fase_id = " . $id_fase . "
                        group by f.id, f.draft_versi, f.name, fas.name");

                return json_encode($draft_form_risk_profile);
            }
        }
    }

    function get_drafts($id_jenis, $id_lokasi, $id_tahun, $id_fase)
    {
        // $drafts = DB::select("select distinct f.id, f.draft_versi, f.name 
        //                             from file_imports f 
        //                             join templates t on f.template_id = t.id
        //                             join excel_datas e on e.file_import_id = f.id
        //                             where t.jenis_id=".$id_jenis." and e.lokasi_id = ".$id_lokasi." and t.tahun= ".$id_tahun."
        //                             group by f.id, f.draft_versi, f.name;");
        // return $drafts;

        $drafts_final =
            DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
            from file_imports f 
            join templates t on f.template_id = t.id
            join excel_datas e on e.file_import_id = f.id
            join file_approval fa on fa.file_import_id = f.id
            join file_approval_status fas on fas.id = fa.file_approval_status_id
            join approval app on app.id = fa.approval_id
            where t.jenis_id=" . $id_jenis . " and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
            and app.fase_id = 3
            and fa.file_approval_status_id = 4
            and app.urutan = (select max(urutan) from approval where approval.fase_id = " . $id_fase . ")
            group by f.id, f.draft_versi, f.name, fas.name");

        $drafts =
            DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
            from file_imports f 
            join templates t on f.template_id = t.id
            join excel_datas e on e.file_import_id = f.id
            join file_approval fa on fa.file_import_id = f.id
            join file_approval_status fas on fas.id = fa.file_approval_status_id
            join approval app on app.id = fa.approval_id
            where t.jenis_id=" . $id_jenis . " and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
            and app.fase_id = " . $id_fase . "
            and fa.file_approval_status_id = 4
            and app.urutan = (select max(urutan) from approval where approval.fase_id = " . $id_fase . ")
            group by f.id, f.draft_versi, f.name, fas.name");

        //jika ada draft yg sudah final
        if ($drafts_final) {
            return $drafts_final;
        } else {
            //jika ada draft yg sudah diapproved oleh urutan terakhir pada fase tsb (misal GM/Kadiv Anggaran)
            if ($drafts) {
                return $drafts;
            } else {
                // ambil draft yg status nya draft/submitted/approved (tetapi belum urutan terakhir)
                $drafts =
                    DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
                        from file_imports f 
                        join templates t on f.template_id = t.id
                        join excel_datas e on e.file_import_id = f.id
                        join file_approval fa on fa.file_import_id = f.id
                        join file_approval_status fas on fas.id = fa.file_approval_status_id
                        join approval app on app.id = fa.approval_id
                        where t.jenis_id=" . $id_jenis . " and e.lokasi_id = " . $id_lokasi . " and t.tahun= " . $id_tahun . "
                        and app.fase_id = " . $id_fase . "
                        and (fa.file_approval_status_id = 1 or fa.file_approval_status_id = 2 or fa.file_approval_status_id = 4)
                        group by f.id, f.draft_versi, f.name, fas.name");

                return $drafts;
            }
        }
    }
}
