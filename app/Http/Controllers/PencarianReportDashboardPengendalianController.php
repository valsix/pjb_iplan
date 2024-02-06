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

class PencarianReportDashboardPengendalianController extends Controller
{
    public function pencarian(Request $request)
    {
        $data = Input::all();

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

        $fase = Fase::whereIn('id', [3, 4])->get();
        $tahun = Template::select('tahun')->where('jenis_id', 2)->orWhere('jenis_id', 1)->orWhere('jenis_id', 3)->distinct()->get();
        $months = array('', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');

        $input_tahun = $request->input('tahun_anggaran');
        $input_bulan = $request->input('bulan');
        $input_sb = $request->input('strategi_bisnis');
        $input_distrik = $request->input('distrik');
        $int_input_distrik = (int) $input_distrik;
        $input_lokasi = $request->input('lokasi');
        $int_input_lokasi = (int) $input_lokasi;
        $input_fase = $request->input('fase');

        $input_lokasi_name = [];
        $input_draft_form_rkau = $request->input('draft_form_rkau');
        $input_draft_form_6_reimburse = $request->input('draft_form_6_reimburse');
        $input_draft_form_6_rutin = $request->input('draft_form_6_rutin');
        $input_draft_form_10_pu = $request->input('draft_form_10_pu');
        $input_draft_form_10_pk = $request->input('draft_form_10_pk');
        $input_draft_form_10_pln = $request->input('draft_form_10_pln');
        $input_draft_form_bahan_bakar = $request->input('draft_form_bahan_bakar');
        $input_draft_form_penyusutan = $request->input('draft_form_penyusutan');
        $input_draft_risk_profile = $request->input('draft_risk_profile');

        $distrik =
            $lokasi =
            $draft_form_rkau =
            $draft_form_6_reimburse =
            $draft_form_6_rutin =
            $draft_form_10_pu =
            $draft_form_10_pk =
            $draft_form_10_pln =
            $draft_form_bahan_bakar =
            $draft_form_penyusutan =
            $draft_form_risk_profile = [];

        if ($input_sb) {
            $input_sb = DB::table('strategi_bisnis')->select('name', 'id')->where('id', $input_sb)->get()[0];
            $distrik = Distrik::select('name', 'id')->where('strategi_bisnis_id', $input_sb->id)->get();
        }
        if ($input_distrik) {
            $input_distrik = DB::table('distrik')->select('name', 'id')->where('id', $request->distrik)->get()[0];
            $lokasi = Lokasi::select('name', 'id')->where('distrik_id', $input_distrik->id)->get();
        }
        if ($input_lokasi) {
            $request->lokasi = explode(',', $request->lokasi);
            $temp = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20];
            $input_lokasi = DB::table('lokasi')->select('name', 'id')->where('distrik_id', $input_distrik->id)->whereIn('name', $request->lokasi)->get();
            foreach ($input_lokasi as $key) {
                array_push($input_lokasi_name, $key->name);
            }
            $input_lokasi = implode(',', $input_lokasi_name);
        }
        if ($input_fase) {
            $input_fase = DB::table('fases')->select('name', 'id')->where('id', $request->fase)->get()[0];
        }
        if ($input_draft_form_rkau) {
            $draft_form_rkau = $this->get_drafts(1, $int_input_distrik, $input_tahun, $input_fase->id);
        }
        if ($input_draft_form_6_reimburse) {
            $draft_form_6_reimburse = $this->get_drafts(2, $int_input_distrik, $input_tahun, $input_fase->id);
        }
        if ($input_draft_form_6_rutin) {
            $draft_form_6_rutin = $this->get_drafts(3, $int_input_distrik, $input_tahun, $input_fase->id);
        }
        if ($input_draft_form_10_pu) {
            $draft_form_10_pu = $this->get_drafts(4, $int_input_distrik, $input_tahun, $input_fase->id);
        }
        if ($input_draft_form_10_pk) {
            $draft_form_10_pk = $this->get_drafts(5, $int_input_distrik, $input_tahun, $input_fase->id);
        }
        if ($input_draft_form_10_pln) {
            $draft_form_10_pln = $this->get_drafts(6, $int_input_distrik, $input_tahun, $input_fase->id);
        }
        if ($input_draft_form_bahan_bakar) {
            $draft_form_bahan_bakar = $this->get_drafts(7, $int_input_distrik, $input_tahun, $input_fase->id);
        }
        if ($input_draft_form_penyusutan) {
            $draft_form_penyusutan = $this->get_drafts(9, $int_input_distrik, $input_tahun, $input_fase->id);
        }
        if ($input_draft_risk_profile) {
            $draft_form_risk_profile = $this->get_drafts(8, $int_input_distrik, $input_tahun, $input_fase->id);
        }

        // dd(compact('sb', 'fase', 'input_tahun', 'input_sb'));
        return view('output/pencarian-pengendalian', compact(
            'sb',
            'fase',
            'input_tahun',
            'input_sb',
            'input_distrik',
            'input_lokasi',
            'input_fase',
            'input_draft_form_rkau',
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
            'input_bulan'
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

    public function ajax_draft_pencarian($id_distrik, $id_tahun, $id_fase, $jenis_id)
    {
        $draft =
            DB::select("select distinct f.id, f.file_import_id, f.draft_versi, f.name
                from file_imports_ketetapan f
                join templates t on f.template_id = t.id
                join excel_datas_ketetapan e on e.file_import_ketetapan_id = f.id
                join lokasi l on l.id = e.lokasi_id
                where f.fase_id=" . $id_fase . " and t.jenis_id=" . $jenis_id . " and l.distrik_id = " . $id_distrik . " and t.tahun= " . $id_tahun . "
                group by f.id, f.file_import_id, f.draft_versi, f.name");

        return json_encode($draft);
    }

    private function get_drafts($id_jenis, $id_distrik, $id_tahun, $id_fase)
    {
        if ($id_fase == 4) {
            return $this->get_interchange_drafts($id_jenis, $id_distrik, $id_tahun, $id_fase);
        } else {
            return $this->get_normal_drafts($id_jenis, $id_distrik, $id_tahun, $id_fase);
        }
    }

    private function get_normal_drafts($id_jenis, $id_distrik, $id_tahun, $id_fase)
    {
        $drafts =
            DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
            from file_imports f
            join templates t on f.template_id = t.id
            join excel_datas e on e.file_import_id = f.id
            join file_approval fa on fa.file_import_id = f.id
            join file_approval_status fas on fas.id = fa.file_approval_status_id
            join approval app on app.id = fa.approval_id
            join lokasi l on l.id = e.lokasi_id
            where t.jenis_id=" . $id_jenis . " and l.distrik_id = " . $id_distrik . " and t.tahun= " . $id_tahun . "
            and app.fase_id = " . $id_fase . "
            and fa.file_approval_status_id = 4
            and app.urutan = (select max(urutan) from approval where approval.fase_id = " . $id_fase . ")
            group by f.id, f.draft_versi, f.name, fas.name");

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
                    join lokasi l on l.id = e.lokasi_id
                    where t.jenis_id=" . $id_jenis . " and l.distrik_id = " . $id_distrik . " and t.tahun= " . $id_tahun . "
                    and app.fase_id = " . $id_fase . "
                    and (fa.file_approval_status_id = 1 or fa.file_approval_status_id = 2 or fa.file_approval_status_id = 4)
                    group by f.id, f.draft_versi, f.name, fas.name");

            return $drafts;
        }
    }

    private function get_interchange_drafts($id_jenis, $id_distrik, $id_tahun, $id_fase)
    {
        $drafts =
            DB::select("select distinct f.id, f.draft_versi, f.name, fas.name as status_approval
            from file_imports f
            join templates t on f.template_id = t.id
            join excel_datas e on e.file_import_id = f.id
            join file_approval fa on fa.file_import_id = f.id
            join file_approval_status fas on fas.id = fa.file_approval_status_id
            join approval app on app.id = fa.approval_id
            join lokasi l on l.id = e.lokasi_id
            where t.jenis_id=" . $id_jenis . " and l.distrik_id = " . $id_distrik . " and t.tahun= " . $id_tahun . "
            and app.fase_id = " . $id_fase . "
            and fa.file_approval_status_id = 4
            and app.urutan = (select max(urutan) from approval where approval.fase_id = " . $id_fase . ")
            group by f.id, f.draft_versi, f.name, fas.name");

        return $drafts;
    }
}
