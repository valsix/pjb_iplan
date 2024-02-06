<?php

namespace App\Http\Controllers\Pengendalian;

use App\Http\Controllers\Controller;

use Excel;

use Illuminate\Http\Request;

use App\Entities\StrategiBisnis;
use App\Entities\Fase;
use App\Entities\Distrik;
use App\Entities\Lokasi;
use App\Entities\User;
use App\Entities\Role;
use App\Entities\PgdlReportDashboardSetting;

use App\Http\Controllers\Pengendalian\LabaRugi\LabaRugiQuery;
use App\Http\Controllers\Pengendalian\LabaRugi\LabaRugiRKAUV1;
use App\Http\Controllers\Pengendalian\LabaRugi\LabaRugiRKAUV2;


class LRController extends Controller
{

    public function index(Request $request)
    {
        $user_id = session('user_id');
        $user = User::find($user_id);
        $role_id = session('role_id');
        $role = Role::find($role_id);

        $input_tahun = $request->input('tahun_anggaran');

        $input_sb = $request->input('strategi_bisnis');
        $strategi_bisnis = StrategiBisnis::where('name', $input_sb)->first();
        $int_input_sb = $strategi_bisnis->id;

        $input_distrik = $request->input('distrik');
        $distrik = Distrik::where('name', $input_distrik)->first();
        $int_input_distrik = $distrik->id;

        $input_fase = $request->input('fase');
        $fase = Fase::where('name', $input_fase)->first();
        $int_input_fase = $fase->id;

        $input_bulan = $request->input('bulan');

        $lokasi = Lokasi::where('distrik_id', $distrik->id)->get();
        $input_lokasi = $request->input('lokasi');

        if (!$input_tahun) {
            return redirect()->action('PencarianReportDashboardPengendalianController@pencarian');
        }
        if ($int_input_sb < 1 || $int_input_sb > 2) {
            return redirect()->action('PencarianReportDashboardPengendalianController@pencarian');
        }
        $distrik_id = Distrik::pluck('id')->toArray();
        if (!in_array($int_input_distrik, $distrik_id)) {
            return redirect()->action('PencarianReportDashboardPengendalianController@pencarian');
        }
        $fase_id = Fase::pluck('id')->toArray();
        if (!in_array($int_input_fase, $fase_id)) {
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

        foreach ($nama_bln as $key => $value) {
            if ($value == $input_bulan) {
                $int_input_bulan = $input_bulan = $key;
                break;
            }
        }

        if ($int_input_bulan >= 1 && $int_input_bulan <= 12) {
            $nama_bln_dipilih = $nama_bln[$int_input_bulan];
        } else {
            return redirect()->action('PencarianReportDashboardPengendalianController@pencarian');
        }

        if ($distrik->name != 'UBJOM LUAR JAWA -1' && $distrik->name != 'UBJOM LUAR JAWA -2') {
            if (!$input_lokasi) {
                $input_lokasi = Lokasi::where('distrik_id', $distrik->id)->first();
            } else {
                $input_lokasi = Lokasi::where('distrik_id', $distrik->id)->where('name', $input_lokasi)->first();
            }
        }

        $source = array();

        $jenis_form_yg_digunakan = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', 6)
            ->whereNotNull('jenis_id')
            ->where('tahun', $input_tahun)
            ->select('jenis_id')
            ->distinct()
            ->get();

        $notification_failed = '';
        if (count($jenis_form_yg_digunakan) == 0) {
            $notification_failed = 'Setting Report Dashboard Laba Rugi untuk tahun ' . $input_tahun . ' belum dibuat!';
            return view('pengendalian_output.lr.index', compact('input_tahun', 'distrik', 'lokasi', 'nama_bln_dipilih', 'notification_failed', 'input_lokasi'));
        } else {
            $jenis_form = $jenis_form_yg_digunakan[0];

            if ($distrik->name == 'UBJOM LUAR JAWA -1' || $distrik->name == 'UBJOM LUAR JAWA -2') {
                $file_imports_pgdl = LabaRugiQuery::get_file_id_pengendalian($jenis_form->jenis_id, $input_tahun, $int_input_distrik);
            } else {
                $file_imports_pgdl = LabaRugiQuery::get_file_id_pengendalian_non_luar_jawa($jenis_form->jenis_id, $input_tahun, $int_input_distrik, $input_lokasi->id);
            }

            $settings = PgdlReportDashboardSetting::where('pgdl_report_dashboard_page_id', 6)
                ->where('jenis_id', $jenis_form->jenis_id)
                ->where('tahun', $input_tahun)
                ->orderBy('sequence')
                ->get();

            $lr_result = array();
            $result_data = array();
            if ($file_imports_pgdl != null) {
                $result_data = array();
                foreach ($settings as $column_setting) {
                    $jenis_id = $column_setting->jenis_id;
                    $column_result = array();
                    $column_result_ketetapan = array();
                    // jika dari pengendalian, cari berdasarkan kolom
                    if ($column_setting->pgdl_report_dashboard_source_id == 2) {
                        if ($distrik->name == 'UBJOM LUAR JAWA -1' || $distrik->name == 'UBJOM LUAR JAWA -2') {
                            $column_result = LabaRugiQuery::get_data_pengendalian($file_imports_pgdl, $column_setting->pgdl_sheet_name, $column_setting->kolom);
                        } else {
                            $column_result = LabaRugiQuery::get_data_pengendalian_non_luar_jawa($file_imports_pgdl, $column_setting->pgdl_sheet_name, $column_setting->kolom, $input_lokasi->id);
                        }
                    } elseif ($column_setting->pgdl_report_dashboard_source_id == 1) {
                        // if ($distrik->name == 'UBJOM LUAR JAWA -1' || $distrik->name == 'UBJOM LUAR JAWA -2') {
                        //        $column_result_ketetapan = LabaRugiQuery::get_data_ketetapan($file_imports_pgdl, $input_tahun, $input_bulan, 'I-LR' ,$column_setting->kolom, 13);
                        // } else {
                        $column_result_ketetapan = LabaRugiQuery::get_data_ketetapan($file_imports_pgdl, $input_tahun, $input_bulan, 'I-LR', $column_setting->kolom, 13, $input_lokasi->id);
                        // }
                    }
                    $result_data[$column_setting->judul_kolom] = $column_result;
                    $result_data_ketetapan[$column_setting->judul_kolom] = $column_result_ketetapan;
                    // jika dari ketetapan, pjprk, akan dicari berdasarkan nomor PRK
                }
            }
            // dd($result_data);
            // dd($result_data_ketetapan);

            if ($request->tahun_anggaran > '2020') {
                $lr_result = LabaRugiRKAUV2::index($file_imports_pgdl, $input_tahun, $input_lokasi, $int_input_bulan, $input_bulan, $strategi_bisnis, $distrik, $settings, $result_data, $result_data_ketetapan);
            } else {
                $lr_result = LabaRugiRKAUV1::index($file_imports_pgdl, $input_tahun, $input_lokasi, $int_input_bulan, $input_bulan, $strategi_bisnis, $distrik, $settings, $result_data, $result_data_ketetapan);
            }

            if ($request->type) {
                if ($request->type == 'excel') {
                    if ($distrik->name == 'UBJOM LUAR JAWA -1' || $distrik->name == 'UBJOM LUAR JAWA -2') {
                        Excel::create('Laba Rugi', function ($excel) use ($lr_result, $input_tahun, $distrik, $lokasi, $nama_bln_dipilih, $settings, $strategi_bisnis) {
                            $excel->setTitle('Laba Rugi');
                            $excel->setCreator('Laravel')->setCompany('Profio', 'PJB');
                            $excel->setDescription('Laba Rugi');
                            $excel->sheet('Laba Rugi', function ($sheet) use ($lr_result, $input_tahun, $distrik, $lokasi, $nama_bln_dipilih, $settings, $strategi_bisnis) {
                                $sheet->loadView('pengendalian_output.lr.excel', compact('lr_result', 'input_tahun', 'distrik', 'lokasi', 'nama_bln_dipilih', 'settings', 'strategi_bisnis'));
                            });
                        })->download('xlsx');
                    } else {
                        Excel::create('Laba Rugi', function ($excel) use ($lr_result, $input_tahun, $distrik, $lokasi, $nama_bln_dipilih, $settings, $strategi_bisnis, $input_lokasi) {
                            $excel->setTitle('Laba Rugi');
                            $excel->setCreator('Laravel')->setCompany('Profio', 'PJB');
                            $excel->setDescription('Laba Rugi');
                            $excel->sheet('Laba Rugi', function ($sheet) use ($lr_result, $input_tahun, $distrik, $lokasi, $nama_bln_dipilih, $settings, $strategi_bisnis, $input_lokasi) {
                                $sheet->loadView('pengendalian_output.lr.excel', compact('lr_result', 'input_tahun', 'distrik', 'lokasi', 'nama_bln_dipilih', 'settings', 'strategi_bisnis', 'input_lokasi'));
                            });
                        })->download('xlsx');
                    }
                }
            }
            return view('pengendalian_output.lr.index', compact('lr_result', 'input_tahun', 'distrik', 'lokasi', 'nama_bln_dipilih', 'settings', 'row_utama', 'row_sub_1', 'row_sub_2', 'input_lokasi'));
        }
    }
}
