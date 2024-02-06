<?php

namespace App\Http\Controllers\Pengendalian\LabaRugi;

use App\Http\Services\LabaRugiService;

class LabaRugiRKAUV2
{
  public static function index($file_imports_pgdl, $input_tahun, $input_lokasi, $int_input_bulan, $input_bulan, $strategi_bisnis, $distrik, $settings, $result_data, $result_data_ketetapan)
  {
    if (empty($result_data['Keterangan'])) {
      if ($distrik->name == 'UBJOM LUAR JAWA -1' || $distrik->name == 'UBJOM LUAR JAWA -2') {
        $notification_failed = 'File RKAU untuk distrik ' . $distrik->name . ' tahun ' . $input_tahun . ' belum diunggah!';
      } else {
        $notification_failed = 'File RKAU untuk distrik ' . $distrik->name . ' lokasi ' . $input_lokasi->name . ' tahun ' . $input_tahun . ' belum diunggah!';
      }

      return view('pengendalian_output.lr.index', compact('input_tahun', 'distrik', 'lokasi', 'nama_bln_dipilih', 'notification_failed', 'input_lokasi'));
    }

    $no_utama = $no_sub_1 = $no_sub_2 = $no_sub_3 = 0;

    foreach ($result_data['Keterangan'] as $key => $value) {
      $temp = array();

      // remove numerics and dots from keterangan
      $words = preg_replace('/[0-9]+/', '', $value);
      $words = str_replace('.', '', $words);
      $words = trim($words);
      //get the parent code of keterangan
      // dump($words);
      $parent_code = LabaRugiQuery::get_parent_code($words, $strategi_bisnis);
      // dump($parent_code);
      //get keterangan indentation & number
      $number_indentation = LabaRugiQuery::get_keterangan_number_indentation($key, $no_utama, $no_sub_1, $no_sub_2, $no_sub_3);
      // dump($number_indentation);
      $no_utama = $number_indentation['no_utama'];
      $no_sub_1 = $number_indentation['no_sub_1'];
      $no_sub_2 = $number_indentation['no_sub_2'];
      $no_sub_3 = $number_indentation['no_sub_3'];

      $result_data['Keterangan'][$key] = $number_indentation['number_indentation'] . $words;

      // $realisasi = $parent_code != '' ? LabaRugiQuery::get_realisasi($parent_code, $distrik, $input_tahun, $int_input_bulan) : 0;
      // dd('6');
      if ($distrik->name == 'UBJOM LUAR JAWA -1' || $distrik->name == 'UBJOM LUAR JAWA -2') {
        foreach ($settings as $key_setting => $column_setting) {
          // kolom yg ambil dari excel_data_revisi
          if ($column_setting->pgdl_report_dashboard_source_id == 2)
            $temp[$column_setting->judul_kolom] = !empty($result_data[$column_setting->judul_kolom][$key]) ? $result_data[$column_setting->judul_kolom][$key] : 0;

          // kolom yg ambil dari hasil query pjprk_ao
          elseif ($column_setting->pgdl_report_dashboard_source_id == 4) {
            if ($key == 66)
              $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? 0 : LabaRugiQuery::get_realisasi_produksi_penjualan($input_tahun, $input_bulan, $distrik, 5);
            elseif ($key == 67)
              $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? 0 : LabaRugiQuery::get_realisasi_produksi_penjualan($input_tahun, $input_bulan, $distrik, 6);
            elseif ($key == 68)
              $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? 0 : LabaRugiQuery::get_realisasi_produksi_penjualan($input_tahun, $input_bulan, $distrik, 7);
            elseif ($key == 69)
              $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? 0 : LabaRugiQuery::get_realisasi_produksi_penjualan($input_tahun, $input_bulan, $distrik, 8);
            else
              $temp[$column_setting->judul_kolom] = $parent_code != '' ? LabaRugiQuery::get_realisasi($parent_code, $distrik, $input_tahun, $int_input_bulan) : 0;
          }

          // hardcode
          elseif ($column_setting->pgdl_report_dashboard_source_id == 5) {

            // Hardcode untuk kolom RKAP s.d Bulan karena tiap baris rumusnya berbeda
            if ($column_setting->judul_kolom == 'RKAP s.d Bulan') {
              if ($key == 16)
                $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 13);
              elseif ($key == 17)
                $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 14);
              elseif ($key == 18)
                $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 15);
              elseif ($key == 19)
                $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 16);
              elseif ($key == 20)
                $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 17);
              elseif ($key == 22)
                $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 20);
              elseif ($key == 23)
                $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 21);
              elseif ($key == 29)
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? LabaRugiQuery::get_rkap_sd_bulan($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 13) : LabaRugiQuery::get_rkap_sd_bulan_per_bahan_bakar($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AO', 'C', 'D', 'AF', 'G', 'H', $distrik, 'HSD') + LabaRugiQuery::get_rkap_sd_bulan_per_bahan_bakar($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AQ', 'C', 'D', 'AF', 'G', 'H', $distrik, 'HSD');
              elseif ($key == 30)
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? 0 : LabaRugiQuery::get_rkap_sd_bulan_per_bahan_bakar($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AO', 'C', 'D', 'AF', 'G', 'H', $distrik, 'MFO') + LabaRugiQuery::get_rkap_sd_bulan_per_bahan_bakar($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AQ', 'C', 'D', 'AF', 'G', 'H', $distrik, 'MFO');
              elseif ($key == 31)
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? 0 : LabaRugiQuery::get_rkap_sd_bulan_per_bahan_bakar($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AO', 'C', 'D', 'AF', 'G', 'H', $distrik, 'IDO') + LabaRugiQuery::get_rkap_sd_bulan_per_bahan_bakar($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AQ', 'C', 'D', 'AF', 'G', 'H', $distrik, 'IDO');
              elseif ($key == 32)
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? LabaRugiQuery::get_rkap_sd_bulan($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 14) : LabaRugiQuery::get_rkap_sd_bulan_per_bahan_bakar($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AO', 'C', 'D', 'AF', 'G', 'H', $distrik, 'GAS ALAM') + LabaRugiQuery::get_rkap_sd_bulan_per_bahan_bakar($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AQ', 'C', 'D', 'AF', 'G', 'H', $distrik, 'GAS ALAM');
              elseif ($key == 33) {
                if ($strategi_bisnis->name == 'OM') {
                  $temp[$column_setting->judul_kolom] = 0;
                  for ($i = 15; $i <= 32; $i++) {
                    $temp[$column_setting->judul_kolom] += LabaRugiQuery::get_rkap_sd_bulan($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', $i);
                  }
                } else {
                  $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan_per_bahan_bakar($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AO', 'C', 'D', 'AF', 'G', 'H', $distrik, 'BATUBARA') + LabaRugiQuery::get_rkap_sd_bulan_per_bahan_bakar($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AQ', 'C', 'D', 'AF', 'G', 'H', $distrik, 'BATUBARA');
                }
              } elseif ($key == 34) {
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? LabaRugiQuery::get_rkap_sd_bulan($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 33) : LabaRugiQuery::get_rkap_sd_bulan_bahan_bakar($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AS', 'C', 'D', 'G', 'H', $distrik);
              } elseif ($key == 35)
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? LabaRugiQuery::get_rkap_sd_bulan($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 34) : LabaRugiQuery::get_rkap_sd_bulan_bahan_bakar($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AT', 'C', 'D', 'G', 'H', $distrik);
              elseif ($key == 36)
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? 0 : LabaRugiQuery::get_rkap_sd_bulan_bahan_bakar($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AO', 'C', 'D', 'G', 'H', $distrik) - LabaRugiQuery::get_rkap_sd_bulan_bahan_bakar($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AN', 'C', 'D', 'G', 'H', $distrik);
              elseif ($key == 37)
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? 0 : LabaRugiQuery::get_rkap_sd_bulan_bahan_bakar($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AN', 'C', 'D', 'G', 'H', $distrik);
              elseif ($key == 40)
                $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan_form_6_10($file_imports_pgdl, $input_tahun, $input_bulan, 'I-LR', 'I-Form 6', 'BA', 'C', 'C',  4, 'D', 'C', 5, 2, $distrik);
              elseif ($key == 41)
                $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan_form_6_10($file_imports_pgdl, $input_tahun, $input_bulan, 'I-LR', 'I-Form 6', 'BA', 'C', 'C',  4, 'D', 'C', 5, 3, $distrik) + LabaRugiQuery::get_rkap_sd_bulan_form_6_10($file_imports_pgdl, $input_tahun, $input_bulan, 'I-LR', 'I-Form 10', 'BB', 'C', 'C',  4, 'D', 'C', 5, 6, $distrik);
              elseif ($key == 43)
                $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan_form_6_10($file_imports_pgdl, $input_tahun, $input_bulan, 'I-LR', 'I-Form 6', 'BB', 'C', 'C',  4, 'D', 'C', 5, 2, $distrik);
              elseif ($key == 44)
                $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan_form_6_10($file_imports_pgdl, $input_tahun, $input_bulan, 'I-LR', 'I-Form 6', 'BB', 'C', 'C',  4, 'D', 'C', 5, 3, $distrik);
              elseif ($key == 45)
                $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan($file_imports_pgdl, $input_tahun, $input_bulan, 'I-PEG', 'M', 74);
              elseif ($key == 47)
                $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan($file_imports_pgdl, $input_tahun, $input_bulan, 'I-ADM', 'M', 34);
              elseif ($key == 48)
                $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan_biaya_usaha_lainnya($file_imports_pgdl, $input_tahun, $input_bulan, 'I-BIAYA USAHA LAINNYA', 'M', 'AN', 'C', 7);
              elseif ($key == 55)
                $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan($file_imports_pgdl, $input_tahun, $input_bulan, 'I-DILUAR USAHA', 'M', 39);
              elseif ($key == 56)
                $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan($file_imports_pgdl, $input_tahun, $input_bulan, 'I-DILUAR USAHA', 'M', 51);

              else
                $temp[$column_setting->judul_kolom] = 0;
            } else
              $temp[$column_setting->judul_kolom] = 0;
          }
        }
      } else {
        foreach ($settings as $key_setting => $column_setting) {
          // kolom yg ambil dari excel_data_revisi
          if ($column_setting->pgdl_report_dashboard_source_id == 1) {
            // dd($file_imports_pgdl, $input_tahun, $input_bulan, $column_setting->kolom, $input_lokasi->id);
            $temp[$column_setting->judul_kolom] = !empty($result_data_ketetapan[$column_setting->judul_kolom][$key]) ? $result_data_ketetapan[$column_setting->judul_kolom][$key] : 0;
          } elseif ($column_setting->pgdl_report_dashboard_source_id == 2) {
            $temp[$column_setting->judul_kolom] = !empty($result_data[$column_setting->judul_kolom][$key]) ? $result_data[$column_setting->judul_kolom][$key] : 0;
          }

          // kolom yg ambil dari hasil query pjprk_ao
          elseif ($column_setting->pgdl_report_dashboard_source_id == 4) {
            // dd('8');
            if ($key == 66)
              $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? 0 : LabaRugiQuery::get_realisasi_produksi_penjualan_non_luar_jawa($input_tahun, $input_bulan, $distrik, 5, $input_lokasi->id);
            elseif ($key == 67)
              $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? 0 : LabaRugiQuery::get_realisasi_produksi_penjualan_non_luar_jawa($input_tahun, $input_bulan, $distrik, 6, $input_lokasi->id);
            elseif ($key == 68)
              $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? 0 : LabaRugiQuery::get_realisasi_produksi_penjualan_non_luar_jawa($input_tahun, $input_bulan, $distrik, 7, $input_lokasi->id);
            elseif ($key == 69)
              $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? 0 : LabaRugiQuery::get_realisasi_produksi_penjualan_non_luar_jawa($input_tahun, $input_bulan, $distrik, 8, $input_lokasi->id);
            else
              // change request laba rugi ambil data dari plj prk ai pln
              if ($column_setting->judul_kolom == 'REALISASI s.d Bulan') {
                // $temp[$column_setting->judul_kolom] = $parent_code != '' ? LabaRugiQuery::get_realisasi_non_luar_jawa($parent_code, $distrik, $input_tahun, $int_input_bulan, $input_lokasi->id) : 0;
                $temp[$column_setting->judul_kolom] = LabaRugiService::get_data_realisasi_msf900($strategi_bisnis->name, $key, $parent_code, $distrik, $input_tahun, $int_input_bulan, $input_lokasi->id);
              }
            // end change request laba rugi ambil data dari plj prk ai pln
          }

          // hardcode
          elseif ($column_setting->pgdl_report_dashboard_source_id == 5) {
            // Hardcode untuk kolom RKAP s.d Bulan karena tiap baris rumusnya berbeda
            // dd('10');
            if ($column_setting->judul_kolom == 'RKAP s.d Bulan') {
              if ($key == 16)
                $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 13, $distrik, $input_lokasi->id);
              elseif ($key == 17)
                $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 14, $distrik, $input_lokasi->id);
              elseif ($key == 18)
                $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 15, $distrik, $input_lokasi->id);
              elseif ($key == 19)
                $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 16, $distrik, $input_lokasi->id);
              elseif ($key == 20)
                $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 17, $distrik, $input_lokasi->id);
              elseif ($key == 22)
                $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 20, $distrik, $input_lokasi->id);
              elseif ($key == 23)
                $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 21, $distrik, $input_lokasi->id);
              elseif ($key == 28)
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? LabaRugiQuery::get_bahan_bakar_om($file_imports_pgdl, $input_tahun, $input_bulan, 'I-PENDUKUNG EP', 'K', 37, $distrik, $input_lokasi->id) : 0;
              elseif ($key == 29) // change request september 2020 poin bahan bakar s/d bulan sesuai prefix nilainya belum keluar, kodingan lama dikomen aja, terus diganti dengan kodingan baru
                // $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? LabaRugiQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 13, $distrik, $input_lokasi->id) : LabaRugiQuery::get_rkap_sd_bulan_per_bahan_bakar_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AO', 'C', 'D', 'AF', 'G', 'H', $distrik, 'HSD', $input_lokasi->id) + LabaRugiQuery::get_rkap_sd_bulan_per_bahan_bakar_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AQ', 'C', 'D', 'AF', 'G', 'H', $distrik, 'HSD', $input_lokasi->id);
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? LabaRugiQuery::get_bahan_bakar_om_by_prefix($file_imports_pgdl, $input_tahun, $input_bulan, 'I-PENDUKUNG EP', 'K', 13, $distrik, $input_lokasi->id, ['3A0001', '5A0001']) : LabaRugiQuery::form_input_bahan_bakar($distrik, $input_tahun, $input_bulan, ['2A0001']);
              elseif ($key == 30)
                // $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? 0 : LabaRugiQuery::get_rkap_sd_bulan_per_bahan_bakar_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AO', 'C', 'D', 'AF', 'G', 'H', $distrik, 'MFO', $input_lokasi->id) + LabaRugiQuery::get_rkap_sd_bulan_per_bahan_bakar_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AQ', 'C', 'D', 'AF', 'G', 'H', $distrik, 'MFO', $input_lokasi->id);
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? LabaRugiQuery::get_bahan_bakar_om_by_prefix($file_imports_pgdl, $input_tahun, $input_bulan, 'I-PENDUKUNG EP', 'K', 13, $distrik, $input_lokasi->id, ['3A0002', '5A0002']) : LabaRugiQuery::form_input_bahan_bakar($distrik, $input_tahun, $input_bulan, ['2A0002']);
              elseif ($key == 31)
                // $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? 0 : LabaRugiQuery::get_rkap_sd_bulan_per_bahan_bakar_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AO', 'C', 'D', 'AF', 'G', 'H', $distrik, 'IDO', $input_lokasi->id) + LabaRugiQuery::get_rkap_sd_bulan_per_bahan_bakar_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AQ', 'C', 'D', 'AF', 'G', 'H', $distrik, 'IDO', $input_lokasi->id);
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? LabaRugiQuery::get_bahan_bakar_om_by_prefix($file_imports_pgdl, $input_tahun, $input_bulan, 'I-PENDUKUNG EP', 'K', 13, $distrik, $input_lokasi->id, ['3A0003', '5A0005']) : LabaRugiQuery::form_input_bahan_bakar($distrik, $input_tahun, $input_bulan, ['2A0003']);
              elseif ($key == 32)
                // $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? LabaRugiQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 14, $distrik, $input_lokasi->id) : LabaRugiQuery::get_rkap_sd_bulan_per_bahan_bakar_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AO', 'C', 'D', 'AF', 'G', 'H', $distrik, 'GAS ALAM', $input_lokasi->id) + LabaRugiQuery::get_rkap_sd_bulan_per_bahan_bakar_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AQ', 'C', 'D', 'AF', 'G', 'H', $distrik, 'GAS ALAM', $input_lokasi->id);
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? LabaRugiQuery::get_bahan_bakar_om_by_prefix($file_imports_pgdl, $input_tahun, $input_bulan, 'I-PENDUKUNG EP', 'K', 13, $distrik, $input_lokasi->id, ['3B', '5B']) : LabaRugiQuery::form_input_bahan_bakar($distrik, $input_tahun, $input_bulan, ['2B']);
              elseif ($key == 33) {
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? LabaRugiQuery::get_bahan_bakar_om_by_prefix($file_imports_pgdl, $input_tahun, $input_bulan, 'I-PENDUKUNG EP', 'K', 13, $distrik, $input_lokasi->id, ['3C', '5C']) : LabaRugiQuery::form_input_bahan_bakar($distrik, $input_tahun, $input_bulan, ['2C']);
                // if($strategi_bisnis->name == 'OM'){
                //     $temp[$column_setting->judul_kolom] = 0;
                //     for($i = 15; $i<=32; $i++){
                //         $temp[$column_setting->judul_kolom] += LabaRugiQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', $i, $distrik, $input_lokasi->id);
                //     }
                // }
                // else{
                //     $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan_per_bahan_bakar_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AO', 'C', 'D', 'AF', 'G', 'H', $distrik, 'BATUBARA', $input_lokasi->id) + LabaRugiQuery::get_rkap_sd_bulan_per_bahan_bakar_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AQ', 'C', 'D', 'AF', 'G', 'H', $distrik, 'BATUBARA', $input_lokasi->id);
                // }
              } elseif ($key == 34) {
                // $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? LabaRugiQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 33, $distrik, $input_lokasi->id) : LabaRugiQuery::get_rkap_sd_bulan_bahan_bakar_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AS', 'C', 'D', 'G', 'H', $distrik, $input_lokasi->id);
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? LabaRugiQuery::get_bahan_bakar_om_by_prefix($file_imports_pgdl, $input_tahun, $input_bulan, 'I-PENDUKUNG EP', 'K', 13, $distrik, $input_lokasi->id, ['3D', '5D']) : LabaRugiQuery::form_input_bahan_bakar($distrik, $input_tahun, $input_bulan, ['2D']);
              } elseif ($key == 35)
                // $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? LabaRugiQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 34, $distrik, $input_lokasi->id) : LabaRugiQuery::get_rkap_sd_bulan_bahan_bakar_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AT', 'C', 'D', 'G', 'H', $distrik, $input_lokasi->id);
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? LabaRugiQuery::get_bahan_bakar_om_by_prefix($file_imports_pgdl, $input_tahun, $input_bulan, 'I-PENDUKUNG EP', 'K', 13, $distrik, $input_lokasi->id, ['3E', '5E']) : LabaRugiQuery::form_input_bahan_bakar($distrik, $input_tahun, $input_bulan, ['2E']);
              elseif ($key == 36)
                // $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? 0 : LabaRugiQuery::get_rkap_sd_bulan_bahan_bakar_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AO', 'C', 'D', 'G', 'H', $distrik, $input_lokasi->id) - LabaRugiQuery::get_rkap_sd_bulan_bahan_bakar_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AN', 'C', 'D', 'G', 'H', $distrik, $input_lokasi->id);
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? LabaRugiQuery::get_bahan_bakar_om_by_prefix($file_imports_pgdl, $input_tahun, $input_bulan, 'I-PENDUKUNG EP', 'K', 13, $distrik, $input_lokasi->id, ['3F0002', '5F0002', '3F0003', '5F0003']) : LabaRugiQuery::form_input_bahan_bakar($distrik, $input_tahun, $input_bulan, ['2F0002', '2F0003']);
              elseif ($key == 37) // pajak permukaan air , END bahan bakar sampai dengan bulan sesuai prefix
                // $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? 0 : LabaRugiQuery::get_rkap_sd_bulan_bahan_bakar_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AN', 'C', 'D', 'G', 'H', $distrik, $input_lokasi->id);
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? LabaRugiQuery::get_bahan_bakar_om_by_prefix($file_imports_pgdl, $input_tahun, $input_bulan, 'I-PENDUKUNG EP', 'K', 13, $distrik, $input_lokasi->id, ['3F0001', '5F0001']) : LabaRugiQuery::form_input_bahan_bakar($distrik, $input_tahun, $input_bulan, ['2F0001']);
              elseif ($key == 40)
                $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan_form_6_10_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-LR', 'I-Form 6', 'BA', 'C', 'C',  4, 'D', 'C', 5, 2, $distrik, $input_lokasi->id);
              elseif ($key == 41)
                // $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan_form_6_10_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-LR', 'I-Form 6', 'BA', 'C', 'C',  4, 'D', 'C', 5, 3, $distrik, $input_lokasi->id) + LabaRugiQuery::get_rkap_sd_bulan_form_6_10_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-LR', 'I-Form 10', 'AX', 'C', 'C',  4, 'D', 'C', 5, 6, $distrik, $input_lokasi->id);
                $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan_form_6_10_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-LR', 'I-Form 6', 'BA', 'C', 'C',  4, 'D', 'C', 5, 3, $distrik, $input_lokasi->id) + LabaRugiQuery::get_form_10_ai_pln($file_imports_pgdl, $input_tahun, $input_bulan, 'I-LR', 'I-Form 10', 'AX', 'C', 'C',  4, 'D', 'C', 5, 6, $distrik, $input_lokasi->id);
              elseif ($key == 43)
                $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan_form_6_10_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-LR', 'I-Form 6', 'BB', 'C', 'C',  4, 'D', 'C', 5, 2, $distrik, $input_lokasi->id);
              elseif ($key == 44)
                $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan_form_6_10_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-LR', 'I-Form 6', 'BB', 'C', 'C',  4, 'D', 'C', 5, 3, $distrik, $input_lokasi->id); // end pemeliharaan
              elseif ($key == 46) // bug fixing change request september 2020 poin penyusutan rekap s/d bulan nilainya masih belum sesuai,
                $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_penyusutan_sd_bulan($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Penyusutan', 'Q', 13, $distrik, $input_lokasi->id); // END
              elseif ($key == 45) // bug fixing change request september 2020 poin kepegawaian rekap s/d bulan nilainya masih belum sesuai,
                // $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-PEG', 'M', 74, $distrik, $input_lokasi->id); // END
                $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan_ipeg($file_imports_pgdl, $input_tahun, $input_bulan, 'I-PEG', 'M', 75, $distrik, $input_lokasi->id); // END
              elseif ($key == 47)
                $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-ADM', 'M', 34, $distrik, $input_lokasi->id);
              elseif ($key == 48)
                $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan_biaya_usaha_lainnya_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-BIAYA USAHA LAINNYA', 'M', 'AN', 'C', 7, $distrik, $input_lokasi->id);
              elseif ($key == 49) // tambahan untuk Pembelian tenaga listrik
                $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 22, $distrik, $input_lokasi->id);
              elseif ($key == 56)
                $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-DILUAR USAHA', 'M', 39, $distrik, $input_lokasi->id);
              elseif ($key == 57)
                $temp[$column_setting->judul_kolom] = LabaRugiQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-DILUAR USAHA', 'M', 51, $distrik, $input_lokasi->id);
              else
                $temp[$column_setting->judul_kolom] = 0;

              // DEBUG:
              // if ($key == 46) {
              //     dd(384, $column_setting->judul_kolom, $temp[$column_setting->judul_kolom],
              //         compact('temp', 'file_imports_pgdl', 'input_tahun', 'input_bulan', 'distrik', 'input_lokasi')
              //     );
              // }

            } else
              $temp[$column_setting->judul_kolom] = 0;
          }
        }
      }
      $lr_result[$key] = $temp;
    }

    foreach ($settings as $key => $column_setting) {
      if ($column_setting->judul_kolom != 'Keterangan' && $column_setting->judul_kolom != 'PENCAPAIAN s.d Bulan' && $column_setting->judul_kolom != 'PENCAPAIAN n update' && $column_setting->judul_kolom != 'RKAP n' && $column_setting->judul_kolom != 'RKAP n update') {

        // penjumlahan
        for ($i = 16; $i <= 20; $i++) {
          $lr_result[15][$column_setting->judul_kolom] += $lr_result[$i][$column_setting->judul_kolom];
        }
        $lr_result[21][$column_setting->judul_kolom] = $lr_result[22][$column_setting->judul_kolom] + $lr_result[23][$column_setting->judul_kolom];
        $lr_result[14][$column_setting->judul_kolom] = $lr_result[15][$column_setting->judul_kolom] + $lr_result[21][$column_setting->judul_kolom] + $lr_result[24][$column_setting->judul_kolom];

        for ($i = 29; $i <= 37; $i++) {
          $lr_result[28][$column_setting->judul_kolom] += $lr_result[$i][$column_setting->judul_kolom];
        }
        $lr_result[42][$column_setting->judul_kolom] = $lr_result[43][$column_setting->judul_kolom] + $lr_result[44][$column_setting->judul_kolom];
        $lr_result[39][$column_setting->judul_kolom] = $lr_result[40][$column_setting->judul_kolom] + $lr_result[41][$column_setting->judul_kolom];
        $lr_result[38][$column_setting->judul_kolom] = $lr_result[39][$column_setting->judul_kolom] + $lr_result[42][$column_setting->judul_kolom];

        $lr_result[26][$column_setting->judul_kolom] = $lr_result[28][$column_setting->judul_kolom] + $lr_result[38][$column_setting->judul_kolom] + $lr_result[45][$column_setting->judul_kolom] + $lr_result[46][$column_setting->judul_kolom] + $lr_result[47][$column_setting->judul_kolom] + $lr_result[48][$column_setting->judul_kolom];
        $lr_result[51][$column_setting->judul_kolom] = $lr_result[14][$column_setting->judul_kolom] - $lr_result[26][$column_setting->judul_kolom];
        for ($i = 54; $i <= 58; $i++) {
          $lr_result[53][$column_setting->judul_kolom] += $lr_result[$i][$column_setting->judul_kolom];
        }
        $lr_result[59][$column_setting->judul_kolom] = $lr_result[51][$column_setting->judul_kolom] + $lr_result[53][$column_setting->judul_kolom];
        for ($i = 59; $i <= 61; $i++) {
          $lr_result[62][$column_setting->judul_kolom] += $lr_result[$i][$column_setting->judul_kolom];
        }
        $lr_result[64][$column_setting->judul_kolom] = $lr_result[62][$column_setting->judul_kolom] - $lr_result[63][$column_setting->judul_kolom];

        // khusus baris 68 & 69, bukan hasil pembagian dari baris lain
        if ($column_setting->judul_kolom != 'REALISASI s.d Bulan') {
          $lr_result[69][$column_setting->judul_kolom] = $lr_result[68][$column_setting->judul_kolom] == 0 ? 0 : $lr_result[15][$column_setting->judul_kolom] / $lr_result[68][$column_setting->judul_kolom];
          $lr_result[70][$column_setting->judul_kolom] = $lr_result[68][$column_setting->judul_kolom] == 0 ? 0 : ($lr_result[26][$column_setting->judul_kolom] - $lr_result[48][$column_setting->judul_kolom]) / $lr_result[68][$column_setting->judul_kolom];
        }
      }
    }
    // dd($lr_result);
    foreach ($lr_result as $key => $value) {
      // hardcode untuk kolom pencapaian sd Bulan dan Pencapaian n update
      $lr_result[$key]['PENCAPAIAN s.d Bulan'] = $lr_result[$key]['RKAP s.d Bulan'] == 0 ? 0 : ceil(($lr_result[$key]['REALISASI s.d Bulan'] / $lr_result[$key]['RKAP s.d Bulan']) * 100);

      $lr_result[$key]['PENCAPAIAN n update'] = $lr_result[$key]['RKAP n update'] == 0 ? 0 : ceil(($lr_result[$key]['REALISASI s.d Bulan'] / $lr_result[$key]['RKAP n update']) * 100);
    }

    return $lr_result;
  }
}
