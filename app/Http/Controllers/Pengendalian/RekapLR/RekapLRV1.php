<?php

namespace App\Http\Controllers\Pengendalian\RekapLR;


class RekapLRV1
{
  public static function index($file_imports_pgdl, $input_tahun, $input_lokasi, $int_input_bulan, $input_bulan, $strategi_bisnis, $distrik, $settings, $result_data, $result_data_ketetapan)
  {
    if (empty($result_data['Keterangan'])) {
      if ($distrik->name == 'UBJOM LUAR JAWA -1' || $distrik->name == 'UBJOM LUAR JAWA -2') {
        $notification_failed = 'File RKAU untuk distrik ' . $distrik->name . ' tahun ' . $input_tahun . ' belum diunggah!';
      } else {
        $notification_failed = 'File RKAU untuk distrik ' . $distrik->name . ' lokasi ' . $input_lokasi->name . ' tahun ' . $input_tahun . ' belum diunggah!';
      }

      return view('pengendalian_output.rekap_lr.index', compact('input_tahun', 'distrik', 'lokasi', 'nama_bln_dipilih', 'notification_failed', 'input_lokasi'));
    }

    $no_utama = $no_sub_1 = $no_sub_2 = $no_sub_3 = 0;

    foreach ($result_data['Keterangan'] as $key => $value) {
      $temp = array();

      // remove numerics and dots from keterangan
      $words = preg_replace('/[0-9]+/', '', $value);
      $words = str_replace('.', '', $words);
      $words = trim($words);
      //get the parent code of keterangan
      $parent_code = RekapLRQuery::get_parent_code($words, $strategi_bisnis);

      //get keterangan indentation & number
      $number_indentation = RekapLRQuery::get_keterangan_number_indentation($key, $no_utama, $no_sub_1, $no_sub_2, $no_sub_3);
      $no_utama = $number_indentation['no_utama'];
      $no_sub_1 = $number_indentation['no_sub_1'];
      $no_sub_2 = $number_indentation['no_sub_2'];
      $no_sub_3 = $number_indentation['no_sub_3'];

      $result_data['Keterangan'][$key] = $number_indentation['number_indentation'] . $words;
      if ($distrik->name == 'UBJOM LUAR JAWA -1' || $distrik->name == 'UBJOM LUAR JAWA -2') {
        // pengisian tiap kolom
        foreach ($settings as $key_setting => $column_setting) {
          // kolom yg ambil dari excel_data_revisi
          if ($column_setting->pgdl_report_dashboard_source_id == 2)
            $temp[$column_setting->judul_kolom] = !empty($result_data[$column_setting->judul_kolom][$key]) ? $result_data[$column_setting->judul_kolom][$key] : 0;

          // kolom yg ambil dari hasil query pjprk_ao
          elseif ($column_setting->pgdl_report_dashboard_source_id == 4)
            $temp[$column_setting->judul_kolom] = $parent_code != '' ? RekapLRQuery::get_realisasi($parent_code, $distrik, $input_tahun, $int_input_bulan) : 0;

          // hardcode
          elseif ($column_setting->pgdl_report_dashboard_source_id == 5) {
            $rkap_bulan = array('', 'RKAP Jan', 'RKAP Feb', 'RKAP Mar', 'RKAP Apr', 'RKAP May', 'RKAP Jun', 'RKAP Jul', 'RKAP Aug', 'RKAP Sep', 'RKAP Oct', 'RKAP Nov', 'RKAP Dec');
            $kolom_bulan = RekapLRQuery::get_bulan_by_judul($column_setting->judul_kolom);

            // Hardcode untuk kolom RKAP s.d Bulan karena tiap baris rumusnya berbeda
            if ($kolom_bulan) {
              if ($key == 16)
                $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 13);
              elseif ($key == 17)
                $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 14);
              elseif ($key == 18)
                $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 15);
              elseif ($key == 19)
                $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 16);
              elseif ($key == 20)
                $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 17);
              elseif ($key == 22)
                $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 20);
              elseif ($key == 23)
                $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 21);
              elseif ($key == 29)
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? RekapLRQuery::get_rkap_sd_bulan($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 13) : RekapLRQuery::get_rkap_sd_bulan_per_bahan_bakar($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AO', 'C', 'D', 'AF', 'G', 'H', $distrik, 'HSD') + RekapLRQuery::get_rkap_sd_bulan_per_bahan_bakar($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AQ', 'C', 'D', 'AF', 'G', 'H', $distrik, 'HSD');
              elseif ($key == 30)
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? 0 : RekapLRQuery::get_rkap_sd_bulan_per_bahan_bakar($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AO', 'C', 'D', 'AF', 'G', 'H', $distrik, 'MFO') + RekapLRQuery::get_rkap_sd_bulan_per_bahan_bakar($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AQ', 'C', 'D', 'AF', 'G', 'H', $distrik, 'MFO');
              elseif ($key == 31)
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? 0 : RekapLRQuery::get_rkap_sd_bulan_per_bahan_bakar($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AO', 'C', 'D', 'AF', 'G', 'H', $distrik, 'IDO') + RekapLRQuery::get_rkap_sd_bulan_per_bahan_bakar($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AQ', 'C', 'D', 'AF', 'G', 'H', $distrik, 'IDO');
              elseif ($key == 32)
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? RekapLRQuery::get_rkap_sd_bulan($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 14) : RekapLRQuery::get_rkap_sd_bulan_per_bahan_bakar($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AO', 'C', 'D', 'AF', 'G', 'H', $distrik, 'GAS ALAM') + RekapLRQuery::get_rkap_sd_bulan_per_bahan_bakar($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AQ', 'C', 'D', 'AF', 'G', 'H', $distrik, 'GAS ALAM');
              elseif ($key == 33) {
                if ($strategi_bisnis->name == 'OM') {
                  $temp[$column_setting->judul_kolom] = 0;
                  for ($i = 15; $i <= 32; $i++) {
                    $temp[$column_setting->judul_kolom] += RekapLRQuery::get_rkap_sd_bulan($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', $i);
                  }
                } else {
                  $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan_per_bahan_bakar($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AO', 'C', 'D', 'AF', 'G', 'H', $distrik, 'BATUBARA') + RekapLRQuery::get_rkap_sd_bulan_per_bahan_bakar($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AQ', 'C', 'D', 'AF', 'G', 'H', $distrik, 'BATUBARA');
                }
              } elseif ($key == 34) {
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? RekapLRQuery::get_rkap_sd_bulan($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 33) : RekapLRQuery::get_rkap_sd_bulan_bahan_bakar($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AS', 'C', 'D', 'G', 'H', $distrik);
              } elseif ($key == 35)
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? RekapLRQuery::get_rkap_sd_bulan($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 34) : RekapLRQuery::get_rkap_sd_bulan_bahan_bakar($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AT', 'C', 'D', 'G', 'H', $distrik);
              elseif ($key == 36)
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? 0 : RekapLRQuery::get_rkap_sd_bulan_bahan_bakar($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AO', 'C', 'D', 'G', 'H', $distrik) - RekapLRQuery::get_rkap_sd_bulan_bahan_bakar($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AN', 'C', 'D', 'G', 'H', $distrik);
              elseif ($key == 37)
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? 0 : RekapLRQuery::get_rkap_sd_bulan_bahan_bakar($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AN', 'C', 'D', 'G', 'H', $distrik);
              elseif ($key == 40)
                $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan_form_6_10($file_imports_pgdl, $input_tahun, $input_bulan, 'I-LR', 'I-Form 6', 'BA', 'C', 'C',  4, 'D', 'C', 5, 2, $distrik);
              elseif ($key == 41)
                $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan_form_6_10($file_imports_pgdl, $input_tahun, $input_bulan, 'I-LR', 'I-Form 6', 'BA', 'C', 'C',  4, 'D', 'C', 5, 3, $distrik) + RekapLRQuery::get_rkap_sd_bulan_form_6_10($file_imports_pgdl, $input_tahun, $input_bulan, 'I-LR', 'I-Form 10', 'BB', 'C', 'C',  4, 'D', 'C', 5, 6, $distrik);
              elseif ($key == 43)
                $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan_form_6_10($file_imports_pgdl, $input_tahun, $input_bulan, 'I-LR', 'I-Form 6', 'BB', 'C', 'C',  4, 'D', 'C', 5, 2, $distrik);
              elseif ($key == 44)
                $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan_form_6_10($file_imports_pgdl, $input_tahun, $input_bulan, 'I-LR', 'I-Form 6', 'BB', 'C', 'C',  4, 'D', 'C', 5, 3, $distrik);
              elseif ($key == 45)
                $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan($file_imports_pgdl, $input_tahun, $input_bulan, 'I-PEG', 'M', 74);
              elseif ($key == 47)
                $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan($file_imports_pgdl, $input_tahun, $input_bulan, 'I-ADM', 'M', 34);
              elseif ($key == 48)
                $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan_biaya_usaha_lainnya($file_imports_pgdl, $input_tahun, $input_bulan, 'I-BIAYA USAHA LAINNYA', 'M', 'AN', 'C', 7);
              elseif ($key == 55)
                $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan($file_imports_pgdl, $input_tahun, $input_bulan, 'I-DILUAR USAHA', 'M', 39);
              elseif ($key == 56)
                $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan($file_imports_pgdl, $input_tahun, $input_bulan, 'I-DILUAR USAHA', 'M', 51);
              elseif ($key == 66)
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? 0 : RekapLRQuery::get_rkap_sd_bulan_bahan_bakar($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AB', 'C', 'D', 'G', 'H', $distrik);
              elseif ($key == 67)
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? 0 : RekapLRQuery::get_rkap_sd_bulan_bahan_bakar($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AE', 'C', 'D', 'G', 'H', $distrik);
              // elseif($key == 68)
              //     $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? 0 : RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, $input_bulan, $distrik, 7);
              // elseif($key == 69)
              //     $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? 0 : RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, $input_bulan, $distrik, 8);
              else
                $temp[$column_setting->judul_kolom] = 0;
            } else
              $temp[$column_setting->judul_kolom] = 0;
          }
        }
      } else {

        // pengisian tiap kolom
        // change request september 2020
        $increment_month = 1;
        // end change request september 2020

        foreach ($settings as $key_setting => $column_setting) {

          if ($column_setting->pgdl_report_dashboard_source_id == 1)
            // dd($file_imports_pgdl, $input_tahun, $input_bulan, $column_setting->kolom, $input_lokasi->id);
            $temp[$column_setting->judul_kolom] = !empty($result_data_ketetapan[$column_setting->judul_kolom][$key]) ? $result_data_ketetapan[$column_setting->judul_kolom][$key] : 0;

          // kolom yg ambil dari excel_data_revisi
          elseif ($column_setting->pgdl_report_dashboard_source_id == 2)
            $temp[$column_setting->judul_kolom] = !empty($result_data[$column_setting->judul_kolom][$key]) ? $result_data[$column_setting->judul_kolom][$key] : 0;

          // kolom yg ambil dari hasil query pjprk_ao
          elseif ($column_setting->pgdl_report_dashboard_source_id == 4)
            $temp[$column_setting->judul_kolom] = $parent_code != '' ? RekapLRQuery::get_realisasi_produksi_penjualan_non_luar_jawa($parent_code, $distrik, $input_tahun, $int_input_bulan, $input_lokasi->id) : 0;

          // hardcode
          elseif ($column_setting->pgdl_report_dashboard_source_id == 5) {
            $rkap_bulan = array('', 'RKAP Jan', 'RKAP Feb', 'RKAP Mar', 'RKAP Apr', 'RKAP May', 'RKAP Jun', 'RKAP Jul', 'RKAP Aug', 'RKAP Sep', 'RKAP Oct', 'RKAP Nov', 'RKAP Dec');
            $kolom_bulan = RekapLRQuery::get_bulan_by_judul_non_luar_jawa($column_setting->judul_kolom);

            //dd($rkap_bulan);
            // Hardcode untuk kolom RKAP s.d Bulan karena tiap baris rumusnya berbeda
            if ($kolom_bulan) {
              if ($key == 16)
                // change request september 2020
                // $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 13, $input_lokasi->id);
                $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $increment_month, 'I-Pendapatan', 'M', 13, $input_lokasi->id);
              elseif ($key == 17)
                // $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 14, $input_lokasi->id);
                $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $increment_month, 'I-Pendapatan', 'M', 14, $input_lokasi->id);
              elseif ($key == 18)
                // $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 15, $input_lokasi->id);
                $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $increment_month, 'I-Pendapatan', 'M', 15, $input_lokasi->id);
              elseif ($key == 19)
                // $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 16, $input_lokasi->id);
                $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $increment_month, 'I-Pendapatan', 'M', 16, $input_lokasi->id);
              elseif ($key == 20)
                // $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 17, $input_lokasi->id);
                $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $increment_month, 'I-Pendapatan', 'M', 17, $input_lokasi->id);
              //end change request september 2020
              elseif ($key == 22)
                $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $increment_month, 'I-Pendapatan', 'M', 20, $input_lokasi->id); // rutin
              elseif ($key == 23)
                $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $increment_month, 'I-Pendapatan', 'M', 21, $input_lokasi->id); // reimburse
              elseif ($key == 29) // bahan bakar HSD
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? RekapLRQuery::get_bahan_bakar_om_by_prefix($file_imports_pgdl, $input_tahun, $increment_month, 'I-PENDUKUNG EP', 'K', 13, $distrik, $input_lokasi->id, ['3A0001', '5A0001']) : RekapLRQuery::form_input_bahan_bakar($distrik, $input_tahun, $increment_month, ['2A0001']);
              // $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? RekapLRQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 13, $input_lokasi->id) : RekapLRQuery::get_rkap_sd_bulan_per_bahan_bakar_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AO', 'C', 'D', 'AF', 'G', 'H', $distrik, 'HSD', $input_lokasi->id) + RekapLRQuery::get_rkap_sd_bulan_per_bahan_bakar_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AQ', 'C', 'D', 'AF', 'G', 'H', $distrik, 'HSD', $input_lokasi->id);
              elseif ($key == 30)
                // $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? 0 : RekapLRQuery::get_rkap_sd_bulan_per_bahan_bakar_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AO', 'C', 'D', 'AF', 'G', 'H', $distrik, 'MFO', $input_lokasi->id) + RekapLRQuery::get_rkap_sd_bulan_per_bahan_bakar_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AQ', 'C', 'D', 'AF', 'G', 'H', $distrik, 'MFO', $input_lokasi->id);
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? RekapLRQuery::get_bahan_bakar_om_by_prefix($file_imports_pgdl, $input_tahun, $increment_month, 'I-PENDUKUNG EP', 'K', 13, $distrik, $input_lokasi->id, ['3A0002', '5A0002']) : RekapLRQuery::form_input_bahan_bakar($distrik, $input_tahun, $increment_month, ['2A0002']);
              elseif ($key == 31)
                // $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? 0 : RekapLRQuery::get_rkap_sd_bulan_per_bahan_bakar_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AO', 'C', 'D', 'AF', 'G', 'H', $distrik, 'IDO', $input_lokasi->id) + RekapLRQuery::get_rkap_sd_bulan_per_bahan_bakar_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AQ', 'C', 'D', 'AF', 'G', 'H', $distrik, 'IDO', $input_lokasi->id);
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? RekapLRQuery::get_bahan_bakar_om_by_prefix($file_imports_pgdl, $input_tahun, $increment_month, 'I-PENDUKUNG EP', 'K', 13, $distrik, $input_lokasi->id, ['3A0003', '5A0005']) : RekapLRQuery::form_input_bahan_bakar($distrik, $input_tahun, $increment_month, ['2A0003']);
              elseif ($key == 32)
                // $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? RekapLRQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 14, $input_lokasi->id) : RekapLRQuery::get_rkap_sd_bulan_per_bahan_bakar_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AO', 'C', 'D', 'AF', 'G', 'H', $distrik, 'GAS ALAM', $input_lokasi->id) + RekapLRQuery::get_rkap_sd_bulan_per_bahan_bakar_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AQ', 'C', 'D', 'AF', 'G', 'H', $distrik, 'GAS ALAM', $input_lokasi->id);
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ?  RekapLRQuery::get_bahan_bakar_om_by_prefix($file_imports_pgdl, $input_tahun, $increment_month, 'I-PENDUKUNG EP', 'K', 13, $distrik, $input_lokasi->id, ['3B', '5B']) : RekapLRQuery::form_input_bahan_bakar($distrik, $input_tahun, $increment_month, ['2B']);
              elseif ($key == 33) { // bahan bakar batu bara
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? RekapLRQuery::get_bahan_bakar_om_by_prefix($file_imports_pgdl, $input_tahun, $increment_month, 'I-PENDUKUNG EP', 'K', 13, $distrik, $input_lokasi->id, ['3C', '5C']) : RekapLRQuery::form_input_bahan_bakar($distrik, $input_tahun, $increment_month, ['2C']);
                // if($strategi_bisnis->name == 'OM'){
                //     $temp[$column_setting->judul_kolom] = 0;
                //     for($i = 15; $i<=32; $i++){
                //         $temp[$column_setting->judul_kolom] += RekapLRQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', $i, $input_lokasi->id);
                //     }
                // }
                // else{
                //     $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan_per_bahan_bakar_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AO', 'C', 'D', 'AF', 'G', 'H', $distrik, 'BATUBARA', $input_lokasi->id) + RekapLRQuery::get_rkap_sd_bulan_per_bahan_bakar_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AQ', 'C', 'D', 'AF', 'G', 'H', $distrik, 'BATUBARA', $input_lokasi->id);
                // }
              } elseif ($key == 34) {
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? RekapLRQuery::get_bahan_bakar_om_by_prefix($file_imports_pgdl, $input_tahun, $increment_month, 'I-PENDUKUNG EP', 'K', 13, $distrik, $input_lokasi->id, ['3D', '5D']) : RekapLRQuery::form_input_bahan_bakar($distrik, $input_tahun, $increment_month, ['2D']);
                // $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? RekapLRQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 33, $input_lokasi->id) : RekapLRQuery::get_rkap_sd_bulan_bahan_bakar_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AS', 'C', 'D', 'G', 'H', $distrik, $input_lokasi->id);
              } elseif ($key == 35)
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? RekapLRQuery::get_bahan_bakar_om_by_prefix($file_imports_pgdl, $input_tahun, $increment_month, 'I-PENDUKUNG EP', 'K', 13, $distrik, $input_lokasi->id, ['3E', '5E']) : RekapLRQuery::form_input_bahan_bakar($distrik, $input_tahun, $increment_month, ['2E']);
              // $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? RekapLRQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-Pendapatan', 'M', 34, $input_lokasi->id) : RekapLRQuery::get_rkap_sd_bulan_bahan_bakar_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AT', 'C', 'D', 'G', 'H', $distrik, $input_lokasi->id);
              elseif ($key == 36)
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? RekapLRQuery::get_bahan_bakar_om_by_prefix($file_imports_pgdl, $input_tahun, $increment_month, 'I-PENDUKUNG EP', 'K', 13, $distrik, $input_lokasi->id, ['3F0002', '5F0002', '3F0003', '5F0003']) : RekapLRQuery::form_input_bahan_bakar($distrik, $input_tahun, $increment_month, ['2F0002', '2F0003']);
              // $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? 0 : RekapLRQuery::get_rkap_sd_bulan_bahan_bakar_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AO', 'C', 'D', 'G', 'H', $distrik, $input_lokasi->id) - RekapLRQuery::get_rkap_sd_bulan_bahan_bakar_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AN', 'C', 'D', 'G', 'H', $distrik, $input_lokasi->id);
              elseif ($key == 37)
                $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? RekapLRQuery::get_bahan_bakar_om_by_prefix($file_imports_pgdl, $input_tahun, $increment_month, 'I-PENDUKUNG EP', 'K', 13, $distrik, $input_lokasi->id, ['3F0001', '5F0001']) : RekapLRQuery::form_input_bahan_bakar($distrik, $input_tahun, $increment_month, ['2F0001']);
              // $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? 0 : RekapLRQuery::get_rkap_sd_bulan_bahan_bakar_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AN', 'C', 'D', 'G', 'H', $distrik, $input_lokasi->id);
              elseif ($key == 40)
                $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan_form_6_10($file_imports_pgdl, $input_tahun, $increment_month, 'I-LR', 'I-Form 6', 'BA', 'C', 'C',  4, 'D', 'C', 5, 2, $distrik);
              elseif ($key == 41)
                $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan_form_6_10($file_imports_pgdl, $input_tahun, $increment_month, 'I-LR', 'I-Form 6', 'BA', 'C', 'C',  4, 'D', 'C', 5, 3, $distrik) + RekapLRQuery::get_form_10_ai_pln($file_imports_pgdl, $input_tahun, $increment_month, 'I-LR', 'I-Form 10', 'AX', 'C', 'C',  4, 'D', 'C', 5, 6, $distrik);
              elseif ($key == 43)
                $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan_form_6_10($file_imports_pgdl, $input_tahun, $increment_month, 'I-LR', 'I-Form 6', 'BB', 'C', 'C',  4, 'D', 'C', 5, 2, $distrik);
              elseif ($key == 44)
                $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan_form_6_10($file_imports_pgdl, $input_tahun, $increment_month, 'I-LR', 'I-Form 6', 'BB', 'C', 'C',  4, 'D', 'C', 5, 3, $distrik);
              elseif ($key == 45)
                // change request september 2020
                // CHANGE C-066842 - 20200512
                // $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-PEG', 'M', 74, $input_lokasi->id);
                $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $increment_month, 'I-PEG', 'M', 74, $input_lokasi->id);
              elseif ($key == 46)
                $temp[$column_setting->judul_kolom] = RekapLRQuery::get_penyusutan_sd_bulan($file_imports_pgdl, $input_tahun, $increment_month, 'I-Penyusutan', 'Q', 13, $distrik, $input_lokasi->id);
              elseif ($key == 47)
                $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $increment_month, 'I-ADM', 'M', 34, $input_lokasi->id);
              // change request september 2020
              elseif ($key == 48)
                $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan_biaya_usaha_lainnya($file_imports_pgdl, $input_tahun, $input_bulan, 'I-BIAYA USAHA LAINNYA', 'M', 'AN', 'C', 7);
              elseif ($key == 55)
                $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-DILUAR USAHA', 'M', 39, $input_lokasi->id);
              elseif ($key == 56)
                $temp[$column_setting->judul_kolom] = RekapLRQuery::get_rkap_sd_bulan_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'I-DILUAR USAHA', 'M', 51, $input_lokasi->id);
              // elseif($key == 66)
              //     $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? 0 : RekapLRQuery::get_rkap_sd_bulan_bahan_bakar_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AB', 'C', 'D', 'G', 'H', $distrik, $input_lokasi->id);
              // elseif($key == 67)
              //     $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? 0 : RekapLRQuery::get_rkap_sd_bulan_bahan_bakar_non_luar_jawa($file_imports_pgdl, $input_tahun, $input_bulan, 'Database KIT (P+S+I)', 'AE', 'C', 'D', 'G', 'H', $distrik, $input_lokasi->id);
              elseif ($key == 66)
                if ($strategi_bisnis->name != 'OM') {
                  // code...
                  if ($column_setting->judul_kolom == 'RKAP Jan') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 1, $distrik, 5);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Feb') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 2, $distrik, 5);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Mar') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 3, $distrik, 5);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Apr') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 4, $distrik, 5);
                  }
                  if ($column_setting->judul_kolom == 'RKAP May') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 5, $distrik, 5);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Jun') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 6, $distrik, 5);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Jul') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 7, $distrik, 5);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Aug') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 8, $distrik, 5);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Sep') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 9, $distrik, 5);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Oct') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 10, $distrik, 5);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Nov') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 11, $distrik, 5);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Dec') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 12, $distrik, 5);
                  }
                } else {
                  $temp[$column_setting->judul_kolom] = 0;
                }
              // $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? 0 : RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, $input_bulan, $distrik, 5);
              elseif ($key == 67)
                if ($strategi_bisnis->name != 'OM') {
                  // code...
                  if ($column_setting->judul_kolom == 'RKAP Jan') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 1, $distrik, 6);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Feb') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 2, $distrik, 6);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Mar') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 3, $distrik, 6);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Apr') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 4, $distrik, 6);
                  }
                  if ($column_setting->judul_kolom == 'RKAP May') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 5, $distrik, 6);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Jun') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 6, $distrik, 6);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Jul') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 7, $distrik, 6);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Aug') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 8, $distrik, 6);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Sep') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 9, $distrik, 6);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Oct') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 10, $distrik, 6);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Nov') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 11, $distrik, 6);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Dec') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 12, $distrik, 6);
                  }
                } else {
                  $temp[$column_setting->judul_kolom] = 0;
                }
              // $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? 0 : RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, $input_bulan, $distrik, 6);
              elseif ($key == 68)
                if ($strategi_bisnis->name != 'OM') {
                  // code...
                  if ($column_setting->judul_kolom == 'RKAP Jan') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 1, $distrik, 7);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Feb') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 2, $distrik, 7);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Mar') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 3, $distrik, 7);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Apr') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 4, $distrik, 7);
                  }
                  if ($column_setting->judul_kolom == 'RKAP May') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 5, $distrik, 7);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Jun') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 6, $distrik, 7);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Jul') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 7, $distrik, 7);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Aug') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 8, $distrik, 7);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Sep') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 9, $distrik, 7);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Oct') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 10, $distrik, 7);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Nov') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 11, $distrik, 7);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Dec') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 12, $distrik, 7);
                  }
                } else {
                  $temp[$column_setting->judul_kolom] = 0;
                }
              // $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? 0 : RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, $input_bulan, $distrik, 7);
              elseif ($key == 69)
                if ($strategi_bisnis->name != 'OM') {
                  // code...
                  if ($column_setting->judul_kolom == 'RKAP Jan') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 1, $distrik, 8);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Feb') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 2, $distrik, 8);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Mar') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 3, $distrik, 8);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Apr') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 4, $distrik, 8);
                  }
                  if ($column_setting->judul_kolom == 'RKAP May') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 5, $distrik, 8);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Jun') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 6, $distrik, 8);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Jul') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 7, $distrik, 8);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Aug') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 8, $distrik, 8);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Sep') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 9, $distrik, 8);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Oct') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 10, $distrik, 8);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Nov') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 11, $distrik, 8);
                  }
                  if ($column_setting->judul_kolom == 'RKAP Dec') {
                    // code...
                    $temp[$column_setting->judul_kolom] = RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 12, $distrik, 8);
                  }
                } else {
                  $temp[$column_setting->judul_kolom] = 0;
                }
              // $temp[$column_setting->judul_kolom] = $strategi_bisnis->name == 'OM' ? 0 : RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, $input_bulan, $distrik, 8);
              else
                $temp[$column_setting->judul_kolom] = 0;

              // change request september 2020
              if ($increment_month <= 12) {
                # code...
                $increment_month++;
              }
              // end change request september 2020
            } else
              $temp[$column_setting->judul_kolom] = 0;
          }
        }
      }
      $lr_result[$key] = $temp;
    }
    // FFR keterangan dari $dd sini sudah diketahui nilainya perbulan
    // dd($lr_result);
    // die();
    // hardcode untuk baris yg harus dijumlahkan
    foreach ($settings as $key => $column_setting) {
      if ($column_setting->judul_kolom != 'Keterangan' && $column_setting->judul_kolom != 'RKAP n' && $column_setting->judul_kolom != 'RKAP n Update') {

        // penjumlahan
        for ($i = 16; $i <= 20; $i++) {
          $lr_result[15][$column_setting->judul_kolom] += $lr_result[$i][$column_setting->judul_kolom];
        }
        $lr_result[21][$column_setting->judul_kolom] = $lr_result[22][$column_setting->judul_kolom] + $lr_result[23][$column_setting->judul_kolom];
        $lr_result[14][$column_setting->judul_kolom] = $lr_result[15][$column_setting->judul_kolom] + $lr_result[21][$column_setting->judul_kolom] + $lr_result[24][$column_setting->judul_kolom];

        for ($i = 29; $i <= 37; $i++) {
          $lr_result[28][$column_setting->judul_kolom] += $lr_result[$i][$column_setting->judul_kolom]; // 28 == 3.1 Bahan bakar
        }
        $lr_result[42][$column_setting->judul_kolom] = $lr_result[43][$column_setting->judul_kolom] + $lr_result[44][$column_setting->judul_kolom];
        $lr_result[39][$column_setting->judul_kolom] = $lr_result[40][$column_setting->judul_kolom] + $lr_result[41][$column_setting->judul_kolom];
        $lr_result[38][$column_setting->judul_kolom] = $lr_result[39][$column_setting->judul_kolom] + $lr_result[42][$column_setting->judul_kolom];

        $lr_result[26][$column_setting->judul_kolom] = $lr_result[28][$column_setting->judul_kolom] + $lr_result[38][$column_setting->judul_kolom] + $lr_result[45][$column_setting->judul_kolom] + $lr_result[46][$column_setting->judul_kolom] + $lr_result[47][$column_setting->judul_kolom] + $lr_result[48][$column_setting->judul_kolom];
        // $lr_result[26][$column_setting->judul_kolom] = $lr_result[28][$column_setting->judul_kolom] + $lr_result[38][$column_setting->judul_kolom] + $lr_result[45][$column_setting->judul_kolom] + $lr_result[46][$column_setting->judul_kolom] + $lr_result[47][$column_setting->judul_kolom] + $lr_result[48][$column_setting->judul_kolom];
        $lr_result[50][$column_setting->judul_kolom] = $lr_result[14][$column_setting->judul_kolom] - $lr_result[26][$column_setting->judul_kolom];
        for ($i = 53; $i <= 57; $i++) {
          $lr_result[52][$column_setting->judul_kolom] += $lr_result[$i][$column_setting->judul_kolom];
        }
        $lr_result[58][$column_setting->judul_kolom] = $lr_result[50][$column_setting->judul_kolom] + $lr_result[52][$column_setting->judul_kolom];
        for ($i = 58; $i <= 60; $i++) {
          $lr_result[61][$column_setting->judul_kolom] += $lr_result[$i][$column_setting->judul_kolom];
        }
        $lr_result[63][$column_setting->judul_kolom] = $lr_result[61][$column_setting->judul_kolom] - $lr_result[62][$column_setting->judul_kolom];

        // khusus baris 68 & 69, bukan hasil pembagian dari baris lain
        if ($column_setting->judul_kolom != 'REALISASI s.d Bulan') {
          // if ($column_setting->judul_kolom == 'RKAP Feb') {
          //   // code...
          //   $lr_result[66]["RKAP Feb"] = $lr_result[66]["RKAP Jan"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 2, $distrik, 5);
          //   $lr_result[67]["RKAP Feb"] = $lr_result[67]["RKAP Jan"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 2, $distrik, 6);
          //   $lr_result[68]["RKAP Feb"] = $lr_result[68]["RKAP Jan"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 2, $distrik, 7);
          //   $lr_result[69]["RKAP Feb"] = $lr_result[69]["RKAP Jan"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 2, $distrik, 8);
          //
          // }
          // if ($column_setting->judul_kolom == 'RKAP Mar') {
          //   // code...
          //   $lr_result[66]["RKAP Mar"] = $lr_result[66]["RKAP Feb"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 3, $distrik, 5);
          //   $lr_result[67]["RKAP Mar"] = $lr_result[67]["RKAP Feb"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 3, $distrik, 6);
          //   $lr_result[68]["RKAP Mar"] = $lr_result[68]["RKAP Feb"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 3, $distrik, 7);
          //   $lr_result[69]["RKAP Mar"] = $lr_result[69]["RKAP Feb"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 3, $distrik, 8);
          // }
          // if ($column_setting->judul_kolom == 'RKAP Apr') {
          //   // code...
          //   $lr_result[66]["RKAP Apr"] = $lr_result[66]["RKAP Mar"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 4, $distrik, 5);
          //   $lr_result[67]["RKAP Apr"] = $lr_result[67]["RKAP Mar"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 4, $distrik, 6);
          //   $lr_result[68]["RKAP Apr"] = $lr_result[68]["RKAP Mar"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 4, $distrik, 7);
          //   $lr_result[69]["RKAP Apr"] = $lr_result[69]["RKAP Mar"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 4, $distrik, 8);
          // }
          // if ($column_setting->judul_kolom == 'RKAP May') {
          //   // code...
          //   $lr_result[66]["RKAP May"] = $lr_result[66]["RKAP Apr"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 5, $distrik, 5);
          //   $lr_result[67]["RKAP May"] = $lr_result[67]["RKAP Apr"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 5, $distrik, 6);
          //   $lr_result[68]["RKAP May"] = $lr_result[68]["RKAP Apr"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 5, $distrik, 7);
          //   $lr_result[69]["RKAP May"] = $lr_result[69]["RKAP Apr"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 5, $distrik, 8);
          // }
          // if ($column_setting->judul_kolom == 'RKAP Jun') {
          //   // code...
          //   $lr_result[66]["RKAP Jun"] = $lr_result[66]["RKAP May"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 6, $distrik, 5);
          //   $lr_result[67]["RKAP Jun"] = $lr_result[67]["RKAP May"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 6, $distrik, 6);
          //   $lr_result[68]["RKAP Jun"] = $lr_result[68]["RKAP May"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 6, $distrik, 7);
          //   $lr_result[69]["RKAP Jun"] = $lr_result[69]["RKAP May"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 6, $distrik, 8);
          // }
          // if ($column_setting->judul_kolom == 'RKAP Jul') {
          //   // code...
          //   $lr_result[66]["RKAP Jul"] = $lr_result[66]["RKAP Jun"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 7, $distrik, 5);
          //   $lr_result[67]["RKAP Jul"] = $lr_result[67]["RKAP Jun"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 7, $distrik, 6);
          //   $lr_result[68]["RKAP Jul"] = $lr_result[68]["RKAP Jun"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 7, $distrik, 7);
          //   $lr_result[69]["RKAP Jul"] = $lr_result[69]["RKAP Jun"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 7, $distrik, 8);
          // }
          // if ($column_setting->judul_kolom == 'RKAP Aug') {
          //   // code...
          //   $lr_result[66]["RKAP Aug"] = $lr_result[66]["RKAP Jul"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 8, $distrik, 5);
          //   $lr_result[67]["RKAP Aug"] = $lr_result[67]["RKAP Jul"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 8, $distrik, 6);
          //   $lr_result[68]["RKAP Aug"] = $lr_result[68]["RKAP Jul"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 8, $distrik, 7);
          //   $lr_result[69]["RKAP Aug"] = $lr_result[69]["RKAP Jul"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 8, $distrik, 8);
          // }
          // if ($column_setting->judul_kolom == 'RKAP Sep') {
          //   // code...
          //   $lr_result[66]["RKAP Sep"] = $lr_result[66]["RKAP Aug"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 9, $distrik, 5);
          //   $lr_result[67]["RKAP Sep"] = $lr_result[67]["RKAP Aug"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 9, $distrik, 6);
          //   $lr_result[68]["RKAP Sep"] = $lr_result[68]["RKAP Aug"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 9, $distrik, 7);
          //   $lr_result[69]["RKAP Sep"] = $lr_result[69]["RKAP Aug"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 9, $distrik, 8);
          // }
          // if ($column_setting->judul_kolom == 'RKAP Oct') {
          //   // code...
          //   $lr_result[66]["RKAP Oct"] = $lr_result[66]["RKAP Sep"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 10, $distrik, 5);
          //   $lr_result[67]["RKAP Oct"] = $lr_result[67]["RKAP Sep"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 10, $distrik, 6);
          //   $lr_result[68]["RKAP Oct"] = $lr_result[68]["RKAP Sep"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 10, $distrik, 7);
          //   $lr_result[69]["RKAP Oct"] = $lr_result[69]["RKAP Sep"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 10, $distrik, 8);
          // }
          // if ($column_setting->judul_kolom == 'RKAP Nov') {
          //   // code...
          //   $lr_result[66]["RKAP Nov"] = $lr_result[66]["RKAP Oct"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 11, $distrik, 5);
          //   $lr_result[67]["RKAP Nov"] = $lr_result[67]["RKAP Oct"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 11, $distrik, 6);
          //   $lr_result[68]["RKAP Nov"] = $lr_result[68]["RKAP Oct"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 11, $distrik, 7);
          //   $lr_result[69]["RKAP Nov"] = $lr_result[69]["RKAP Oct"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 11, $distrik, 8);
          // }
          // if ($column_setting->judul_kolom == 'RKAP Dec') {
          //   // code...
          //   $lr_result[66]["RKAP Dec"] = $lr_result[66]["RKAP Nov"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 12, $distrik, 5);
          //   $lr_result[67]["RKAP Dec"] = $lr_result[67]["RKAP Nov"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 12, $distrik, 6);
          //   $lr_result[68]["RKAP Dec"] = $lr_result[68]["RKAP Nov"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 12, $distrik, 7);
          //   $lr_result[69]["RKAP Dec"] = $lr_result[69]["RKAP Nov"] + RekapLRQuery::get_realisasi_produksi_penjualan($input_tahun, 12, $distrik, 8);
          // }
          // $lr_result[68][$column_setting->judul_kolom] = $lr_result[67][$column_setting->judul_kolom] == 0 ? 0 : $lr_result[15][$column_setting->judul_kolom] / $lr_result[67][$column_setting->judul_kolom];
          // $lr_result[69][$column_setting->judul_kolom] = $lr_result[67][$column_setting->judul_kolom] == 0 ? 0 : ($lr_result[26][$column_setting->judul_kolom] - $lr_result[48][$column_setting->judul_kolom]) / $lr_result[67][$column_setting->judul_kolom];
        }
      }
    }

    return $lr_result;
  }
}
