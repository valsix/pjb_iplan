<?php

namespace App\Http\Controllers\Pengendalian\LabaRugi;

use DB;
use App\Entities\PgdlLRParentCode;
use App\Entities\PBCAO;
use App\Entities\PgdlPljPrkAo;
use App\Entities\PGDLFileImportRevisi;
use App\Entities\PgdlRealisasiProduksiPenjualan;
use App\Entities\FileImport;

class LabaRugiQuery
{

  // change request Mei 2021 Form input bahan bakar
  public static function form_input_bahan_bakar($distrik, $tahun, $bulan, $prefix)
  {
    for ($i = 0; $i < count($prefix); $i++) {
      $data = DB::select("select e.value from excel_data_input_bahan_bakar e join 
                                file_input_bahan_bakar f on f.id = e.file_input_bahan_bakar_id 
                                where f.tahun = '" . $tahun . "'
                                and e.prk like '%" . $prefix[$i] . "%'
                                and e.distrik_id = " . $distrik->id . " 
                                and e.month <= " . $bulan . "
                             ");
    }

    if (empty($data)) {
      return 0;
    } else {
      $result = 0;
      foreach ($data as $key => $value) {
        $result += $value->value;
      }

      return $result;
    }
  }

  // change request september 2020
  static function get_rkap_sd_bulan_ipeg($pgdl_file_import_revisi_id, $tahun, $bulan, $sheet_name, $start_kolom, $row, $distrik, $lokasi_id)
  {
    $columns = LabaRugiQuery::get_column_array($start_kolom, $bulan, 1);

    $data = DB::select("select e.row, coalesce(sum(CASE WHEN value <> '' THEN value::float ELSE 0 END),0) as value
            from pgdl_excel_datas_revisi e
            join pgdl_sheets s on e.pgdl_sheet_id = s.id
            where e.pgdl_file_import_revisi_id in " . $pgdl_file_import_revisi_id . "
            and e.lokasi_id = " . $lokasi_id . "
            and e.row = " . $row . "
            and e.kolom in " . $columns . "
            and s.name like '" . $sheet_name . "'
            group by e.row
            order by e.row;");

    // dd($pgdl_file_import_revisi_id, $lokasi_id, $row, $columns, $sheet_name);
    // if ($sheet_name == 'I-Penyusutan') {
    //     dd(compact('data'), "select e.row, coalesce(sum(CASE WHEN value <> '' THEN value::float ELSE 0 END),0) as value
    //         from pgdl_excel_datas_revisi e
    //         join pgdl_sheets s on e.pgdl_sheet_id = s.id
    //         where e.pgdl_file_import_revisi_id in ".$pgdl_file_import->form_penyusutan_pgdl_file_import_revisi_id."
    //         and e.lokasi_id = ".$lokasi_id."
    //         and e.row = ".$row."
    //         and e.kolom in ".$columns."
    //         and s.name like '".$sheet_name."'
    //         group by e.row
    //         order by e.row;");
    // }

    $value = !empty($data) ? $data[0]->value : 0;
    return $value;
  } // end change request september 2020
  // change request september 2020
  static function get_form_10_ai_pln($pgdl_file_import_revisi_id, $tahun, $bulan, $sheet_name_rkau, $sheet_name_form, $start_kolom, $search_column1, $search_cell_column_1, $search_cell_row_1, $search_column2, $search_cell_column_2, $search_cell_row_2, $jenis_id, $distrik, $lokasi_id)
  {

    $columns = LabaRugiQuery::get_column_array_non_luar_jawa($start_kolom, $bulan, 1);
    if ($jenis_id == 2)
      $form_id = LabaRugiQuery::get_file_import_revisi_non_luar_jawa($pgdl_file_import_revisi_id, 'form6_rutin_pgdl_file_import_revisi_id');
    elseif ($jenis_id == 3)
      $form_id = LabaRugiQuery::get_file_import_revisi_non_luar_jawa($pgdl_file_import_revisi_id, 'form6_reimburse_pgdl_file_import_revisi_id');
    // elseif($jenis_id == 6)
    else
      $form_id = LabaRugiQuery::get_file_import_revisi_non_luar_jawa($pgdl_file_import_revisi_id, 'form10_pln_pgdl_file_import_revisi_id');

    // if($jenis_id == 6) $columns = "('AX','AY','AZ','BA','BB','BC','BD','BE','BF','BH','BI')";

    if ($form_id == NULL)
      return 0;

    $data = DB::select("select COALESCE(sum(CASE WHEN value <> '' THEN value::float ELSE 0 END),0) AS value
                            FROM pgdl_excel_datas_revisi e
                            JOIN pgdl_sheets s on s.id = e.pgdl_sheet_id
                            WHERE pgdl_file_import_revisi_id in " . $form_id . "
                            AND s.name like '" . $sheet_name_form . "'
                            AND e.lokasi_id = " . $lokasi_id . "
                            AND kolom in " . $columns . "
                            AND row in (
                                SELECT row
                                FROM pgdl_excel_datas_revisi e
                                JOIN pgdl_sheets s on s.id = e.pgdl_sheet_id
                                WHERE pgdl_file_import_revisi_id in " . $form_id . "
                                AND s.name like '" . $sheet_name_form . "'
                                AND e.lokasi_id = " . $lokasi_id . "
                                AND kolom like '" . $search_column1 . "'
                                AND value like '" . $distrik->code1 . "'
                                AND row in (
                                    SELECT row
                                    FROM pgdl_excel_datas_revisi e
                                    JOIN pgdl_sheets s on s.id = e.pgdl_sheet_id
                                    WHERE pgdl_file_import_revisi_id in " . $form_id . "
                                    AND s.name like '" . $sheet_name_form . "'
                                    AND e.lokasi_id = " . $lokasi_id . "
                                    AND kolom like '" . $search_column2 . "'
                                    AND value like (
                                        SELECT value FROM pgdl_excel_datas_revisi e
                                        JOIN pgdl_sheets s on s.id = e.pgdl_sheet_id
                                        WHERE pgdl_file_import_revisi_id in " . $pgdl_file_import_revisi_id . "
                                        AND s.name like '" . $sheet_name_rkau . "'
                                        AND e.lokasi_id = " . $lokasi_id . "
                                        AND kolom like '" . $search_cell_column_2 . "'
                                        AND row = " . $search_cell_row_2 . "
                                    )
                                )
                            )");

    $value = !empty($data) ? $data[0]->value : 0;
    return $value;
  }
  // end change request september 2020
  // change request september 2020
  public static function get_bahan_bakar_om_by_prefix($pgdl_file_import_revisi_id, $input_tahun, $bulan, $sheet_name, $start_kolom, $row, $distrik, $lokasi_id, $prefix)
  {
    # code...
    $prk_kolom = 'C';
    $columns = LabaRugiQuery::get_column_array($start_kolom, $bulan, 1);

    for ($i = 0; $i < count($prefix); $i++) {
      # code...
      $data[] = DB::select("select e.value from pgdl_excel_datas_revisi e
                            join pgdl_sheets s on e.pgdl_sheet_id = s.id
                            where e.pgdl_file_import_revisi_id in (" . $pgdl_file_import_revisi_id . ")
                            and e.lokasi_id = " . $lokasi_id . "
                            and s.name like '" . $sheet_name . "'
                            and e.kolom in " . $columns . "
                            and row in
                                (
                                    select e.row from pgdl_excel_datas_revisi e
                                    join pgdl_sheets s on e.pgdl_sheet_id = s.id
                                    where e.pgdl_file_import_revisi_id in (" . $pgdl_file_import_revisi_id . ")
                                    and e.lokasi_id = " . $lokasi_id . "
                                    and s.name like '" . $sheet_name . "'
                                    and e.kolom = '" . $prk_kolom . "'
                                    and e.value like '%" . $prefix[$i] . "%'
                                )
                        ");
    }

    if (empty($data)) {
      return 0;
    } else {
      $result = 0;
      foreach ($data as $key => $value) {
        # code...
        foreach ($value as $key => $item) {
          # code...
          $result += $item->value;
        }
      }

      return $result;
      // return collect($data)->sum('value');
    }

    // $value = !empty($data) ? $data[0]->value : 0;
    // return $value;
  }
  // end change request september 2020

  public static function get_bahan_bakar_om($pgdl_file_import_revisi_id, $input_tahun, $bulan, $sheet_name, $start_kolom, $row, $distrik, $lokasi_id)
  {
    # code...
    $columns = LabaRugiQuery::get_column_array($start_kolom, $bulan, 1);

    // $pgdl_file_import = DB::table("pgdl_file_imports_revisi")->whereRaw("id in ".$pgdl_file_import_revisi_id)->first();

    // if (empty($pgdl_file_import)) {
    //     return 0;
    // }

    $data = DB::select("select e.row, coalesce(sum(CASE WHEN value <> '' THEN value::float ELSE 0 END),0) as value
            from pgdl_excel_datas_revisi e
            join pgdl_sheets s on e.pgdl_sheet_id = s.id
            where e.pgdl_file_import_revisi_id in (" . $pgdl_file_import_revisi_id . ")
            and e.lokasi_id = " . $lokasi_id . "
            and e.row = " . $row . "
            and e.kolom in " . $columns . "
            and s.name like '" . $sheet_name . "'
            group by e.row
            order by e.row;");

    if (empty($data)) {
      return 0;
    } else {
      return collect($data)->sum('value');
    }

    $value = !empty($data) ? $data[0]->value : 0;
    return $value;
  }

  static function get_data_ketetapan($pgdl_file_import_revisi_id, $tahun, $bulan, $sheet_name, $start_kolom, $row, $lokasi_id)
  {
    // dd($pgdl_file_import_revisi_id);
    $id = DB::select("select * from pgdl_file_imports_revisi e
                where e.id in " . $pgdl_file_import_revisi_id . "
                ;");

    $datas = DB::select("select e.row, e.value
                    from excel_datas_ketetapan e
                    join sheets s on s.id = e.sheet_id
                    where s.name = '" . $sheet_name . "'
                    and e.file_import_ketetapan_id = " . $id[0]->file_import_ketetapan_id . "
                    and e.kolom = '" . $start_kolom . "'
                    and e.lokasi_id = " . $lokasi_id . "
                    and e.row > 12
                    order by e.row;");
    // dd($datas);
    if ($datas) {
      $results = [];
      foreach ($datas as $key => $value) {
        $results[$value->row] = $value->value;
      }
      // dd($results);
      return $results;
    }
    return $datas;
    // $value = !empty($data) ? $data[0]->value : 0;
    // return $value;
  }

  static function get_file_id_pengendalian_non_luar_jawa($jenis_id, $tahun_anggaran, $distrik_id, $lokasi_id)
  {
    $files = DB::select("select p.id
                        from pgdl_file_imports_revisi p
                        join pgdl_templates t on t.id = p.pgdl_template_id
                        where t.jenis_id = " . $jenis_id . "
                        and p.tahun=" . $tahun_anggaran . "
                        and p.distrik_id = " . $distrik_id . "
                        and p.lokasi_id=" . $lokasi_id . ";");

    if ($files) {
      $res = [];
      $i = 0;
      foreach ($files as $key => $value) {
        $res[$i] = $value->id;
        $i++;
      }
      $res = implode(",", $res);
      //dd(var_dump($new));
      $res = "(" . $res . ")";
      return $res;
    }
    return $files;
  }

  static function get_data_pengendalian_non_luar_jawa($file_id, $sheet_name, $kolom, $lokasi_id)
  {

    $datas = DB::select("select e.row, e.value
                            from pgdl_excel_datas_revisi e
                            join pgdl_sheets s on s.id = e.pgdl_sheet_id
                            where s.name = '" . $sheet_name . "'
                            and e.pgdl_file_import_revisi_id in " . $file_id . "
                            and e.kolom = '" . $kolom . "'
                            and e.lokasi_id = " . $lokasi_id . "
                            and e.row > 12
                            order by e.row;");

    if ($datas) {
      $results = [];
      foreach ($datas as $key => $value) {
        $results[$value->row] = $value->value;
      }
      return $results;
    }
    return $datas;
  }

  static function get_parent_code_non_luar_jawa($keterangan, $strategi_bisnis)
  {
    if ($strategi_bisnis->name == 'OM') {
      $data = PgdlLRParentCode::where('keterangan', 'like', "%" . $keterangan . "%")
        ->whereNotIn('kode_parent_om', ['#', '-'])
        ->first();
    } else {
      $data = PgdlLRParentCode::where('keterangan', 'like', "%" . $keterangan . "%")
        ->whereNotIn('kode_parent_up', ['#', '-'])
        ->first();
    }
    // dd($data);
    $parent_code = !empty($data) ? $data->kode_parent_up : '';
    // dd( $parent_code);
    return $parent_code;
  }

  static function get_realisasi_non_luar_jawa($parent, $distrik, $year, $month, $lokasi_id)
  {

    $data = PgdlPljPrkAo::selectRaw('dstrct_code, sum(tran_amount) as value')
      ->where('dstrct_code', $distrik->code1)
      ->where('years', $year)
      ->where('months', '<=', $month)
      ->where('project_no', 'like', "%" . $parent . "%")
      ->groupBy('dstrct_code')
      ->first();

    $value = !empty($data) ? $data->value : 0;
    return $value;
  }

  static function get_rkap_sd_bulan_non_luar_jawa($pgdl_file_import_revisi_id, $tahun, $bulan, $sheet_name, $start_kolom, $row, $distrik, $lokasi_id)
  {
    $columns = LabaRugiQuery::get_column_array($start_kolom, $bulan, 1);

    $data = DB::select("select e.row, coalesce(sum(CASE WHEN value <> '' THEN value::float ELSE 0 END),0) as value
            from pgdl_excel_datas_revisi e
            join pgdl_sheets s on e.pgdl_sheet_id = s.id
            where e.pgdl_file_import_revisi_id in " . $pgdl_file_import_revisi_id . "
            and e.lokasi_id = " . $lokasi_id . "
            and e.row = " . $row . "
            and e.kolom in " . $columns . "
            and s.name like '" . $sheet_name . "'
            group by e.row
            order by e.row;");

    // if ($sheet_name == 'I-Penyusutan') {
    //     dd(compact('data'), "select e.row, coalesce(sum(CASE WHEN value <> '' THEN value::float ELSE 0 END),0) as value
    //         from pgdl_excel_datas_revisi e
    //         join pgdl_sheets s on e.pgdl_sheet_id = s.id
    //         where e.pgdl_file_import_revisi_id in ".$pgdl_file_import->form_penyusutan_pgdl_file_import_revisi_id."
    //         and e.lokasi_id = ".$lokasi_id."
    //         and e.row = ".$row."
    //         and e.kolom in ".$columns."
    //         and s.name like '".$sheet_name."'
    //         group by e.row
    //         order by e.row;");
    // }

    $value = !empty($data) ? $data[0]->value : 0;
    return $value;
  }
  // change request september 2020
  static function get_penyusutan_sd_bulan($pgdl_file_import_revisi_id, $tahun, $bulan, $sheet_name, $start_kolom, $row, $distrik, $lokasi_id)
  {
    $columns = LabaRugiQuery::get_column_array($start_kolom, $bulan, 1);

    $pgdl_file_import = DB::table("pgdl_file_imports_revisi")->whereRaw("id in " . $pgdl_file_import_revisi_id)->first();

    if (empty($pgdl_file_import)) {
      return 0;
    }

    $data = DB::select("select e.row, coalesce(sum(CASE WHEN value <> '' THEN value::float ELSE 0 END),0) as value
            from pgdl_excel_datas_revisi e
            join pgdl_sheets s on e.pgdl_sheet_id = s.id
            where e.pgdl_file_import_revisi_id in (" . $pgdl_file_import->form_penyusutan_pgdl_file_import_revisi_id . ")
            and e.lokasi_id = " . $lokasi_id . "
            and e.row = " . $row . "
            and e.kolom in " . $columns . "
            and s.name like '" . $sheet_name . "'
            group by e.row
            order by e.row;");

    if (empty($data)) {
      return 0;
    } else {
      return collect($data)->sum('value');
    }

    $value = !empty($data) ? $data[0]->value : 0;
    return $value;
  }
  // end change request september 2020

  static function get_rkap_sd_bulan_biaya_usaha_lainnya_non_luar_jawa($pgdl_file_import_revisi_id, $tahun, $bulan, $sheet_name, $start_kolom, $search_column, $search_cell_column, $search_cell_row, $distrik, $lokasi_id)
  {
    $columns = LabaRugiQuery::get_column_array($start_kolom, $bulan, 1);

    $data = DB::select("select COALESCE(sum(CASE WHEN value <> '' THEN value::float ELSE 0 END),0) as value
                from pgdl_excel_datas_revisi e
                join pgdl_sheets s on e.pgdl_sheet_id = s.id
                where pgdl_file_import_revisi_id IN " . $pgdl_file_import_revisi_id . "
                and s.name = '" . $sheet_name . "'
                and e.lokasi_id = " . $lokasi_id . "
                and kolom in " . $columns . "
                and row in (
                    select row
                    from pgdl_excel_datas_revisi e
                    join pgdl_sheets s on e.pgdl_sheet_id = s.id
                    where pgdl_file_import_revisi_id IN " . $pgdl_file_import_revisi_id . "
                    and s.name = '" . $sheet_name . "'
                    and e.lokasi_id = " . $lokasi_id . "
                    and kolom like '" . $search_column . "'
                    and value in (
                        select value
                        from pgdl_excel_datas_revisi e
                        join pgdl_sheets s on e.pgdl_sheet_id = s.id
                        where pgdl_file_import_revisi_id IN " . $pgdl_file_import_revisi_id . "
                        and s.name = '" . $sheet_name . "'
                        and e.lokasi_id = " . $lokasi_id . "
                        and kolom like '" . $search_cell_column . "'
                        and row = " . $search_cell_row . "
                    )
                )");
    $value = !empty($data) ? $data[0]->value : 0;
    return $value;
  }

  static function get_rkap_sd_bulan_form_6_10_non_luar_jawa($pgdl_file_import_revisi_id, $tahun, $bulan, $sheet_name_rkau, $sheet_name_form, $start_kolom, $search_column1, $search_cell_column_1, $search_cell_row_1, $search_column2, $search_cell_column_2, $search_cell_row_2, $jenis_id, $distrik, $lokasi_id)
  {

    $columns = LabaRugiQuery::get_column_array_non_luar_jawa($start_kolom, $bulan, 2);
    if ($jenis_id == 2)
      $form_id = LabaRugiQuery::get_file_import_revisi_non_luar_jawa($pgdl_file_import_revisi_id, 'form6_rutin_pgdl_file_import_revisi_id');
    elseif ($jenis_id == 3)
      $form_id = LabaRugiQuery::get_file_import_revisi_non_luar_jawa($pgdl_file_import_revisi_id, 'form6_reimburse_pgdl_file_import_revisi_id');
    // elseif($jenis_id == 6)
    else
      $form_id = LabaRugiQuery::get_file_import_revisi_non_luar_jawa($pgdl_file_import_revisi_id, 'form10_pln_pgdl_file_import_revisi_id');

    if ($form_id == NULL)
      return 0;

    $data = DB::select("select COALESCE(sum(CASE WHEN value <> '' THEN value::float ELSE 0 END),0) AS value
                            FROM pgdl_excel_datas_revisi e
                            JOIN pgdl_sheets s on s.id = e.pgdl_sheet_id
                            WHERE pgdl_file_import_revisi_id in " . $form_id . "
                            AND s.name like '" . $sheet_name_form . "'
                            AND e.lokasi_id = " . $lokasi_id . "
                            AND kolom in " . $columns . "
                            AND row in (
                                SELECT row
                                FROM pgdl_excel_datas_revisi e
                                JOIN pgdl_sheets s on s.id = e.pgdl_sheet_id
                                WHERE pgdl_file_import_revisi_id in " . $form_id . "
                                AND s.name like '" . $sheet_name_form . "'
                                AND e.lokasi_id = " . $lokasi_id . "
                                AND kolom like '" . $search_column1 . "'
                                AND value like '" . $distrik->code1 . "'
                                AND row in (
                                    SELECT row
                                    FROM pgdl_excel_datas_revisi e
                                    JOIN pgdl_sheets s on s.id = e.pgdl_sheet_id
                                    WHERE pgdl_file_import_revisi_id in " . $form_id . "
                                    AND s.name like '" . $sheet_name_form . "'
                                    AND e.lokasi_id = " . $lokasi_id . "
                                    AND kolom like '" . $search_column2 . "'
                                    AND value like (
                                        SELECT value FROM pgdl_excel_datas_revisi e
                                        JOIN pgdl_sheets s on s.id = e.pgdl_sheet_id
                                        WHERE pgdl_file_import_revisi_id in " . $pgdl_file_import_revisi_id . "
                                        AND s.name like '" . $sheet_name_rkau . "'
                                        AND e.lokasi_id = " . $lokasi_id . "
                                        AND kolom like '" . $search_cell_column_2 . "'
                                        AND row = " . $search_cell_row_2 . "
                                    )
                                )
                            )");
    // change request september 2020 baris 905

    $value = !empty($data) ? $data[0]->value : 0;
    return $value;
  }

  static function get_rkap_sd_bulan_bahan_bakar_non_luar_jawa($pgdl_file_import_revisi_id, $tahun, $bulan, $sheet_name_form, $start_kolom, $search_column1, $search_column2, $search_column3, $search_column4, $distrik, $lokasi_id)
  {
    // dd($lokasi_id);
    $array_bulan = LabaRugiQuery::get_bulan_array_non_luar_jawa($bulan);
    $array_lokasi = LabaRugiQuery::get_location_array_non_luar_jawa($distrik->id, 'name', $lokasi_id);
    $form_id = LabaRugiQuery::get_file_import_revisi_non_luar_jawa($pgdl_file_import_revisi_id, 'form_bahan_bakar_pgdl_file_import_revisi_id');

    if ($form_id == NULL)
      return 0;

    $data = DB::select("
            SELECT sum(value::float) AS value
            FROM pgdl_excel_datas_revisi e
            JOIN pgdl_sheets s on s.id = e.pgdl_sheet_id
            WHERE pgdl_file_import_revisi_id in " . $form_id . "
            AND s.name like '" . $sheet_name_form . "'
            AND e.lokasi_id = " . $lokasi_id . "
            AND kolom like '" . $start_kolom . "'
            AND row in (
                SELECT row FROM pgdl_excel_datas_revisi e
                JOIN pgdl_sheets s on s.id = e.pgdl_sheet_id
                WHERE pgdl_file_import_revisi_id in " . $form_id . "
                AND s.name like '" . $sheet_name_form . "'
                AND e.lokasi_id = " . $lokasi_id . "
                AND kolom like '" . $search_column1 . "'
                AND value like '" . $distrik->code1 . "'

                AND row in (
                    SELECT row FROM pgdl_excel_datas_revisi e
                    JOIN pgdl_sheets s on s.id = e.pgdl_sheet_id
                    WHERE pgdl_file_import_revisi_id in " . $form_id . "
                    AND s.name like '" . $sheet_name_form . "'
                    AND e.lokasi_id = " . $lokasi_id . "
                    AND kolom like '" . $search_column2 . "'
                    AND value in " . $array_lokasi . "

                AND row in (
                    SELECT row FROM pgdl_excel_datas_revisi e
                    JOIN pgdl_sheets s on s.id = e.pgdl_sheet_id
                    WHERE pgdl_file_import_revisi_id in " . $form_id . "
                    AND s.name like '" . $sheet_name_form . "'
                    AND e.lokasi_id = " . $lokasi_id . "
                    AND kolom like '" . $search_column3 . "'
                    AND value like '" . $tahun . "'
                    AND row in (
                        SELECT row FROM pgdl_excel_datas_revisi e
                        JOIN pgdl_sheets s on s.id = e.pgdl_sheet_id
                        WHERE pgdl_file_import_revisi_id in " . $form_id . "
                        AND s.name like '" . $sheet_name_form . "'
                        AND e.lokasi_id = " . $lokasi_id . "
                        AND kolom like '" . $search_column4 . "'
                        AND value in " . $array_bulan . "
                    )
                )
            )
        )");
    $value = !empty($data) ? ($data[0]->value != null ? $data[0]->value : 0) : 0;
    return $value;
  }

  static function get_rkap_sd_bulan_per_bahan_bakar_non_luar_jawa($pgdl_file_import_revisi_id, $tahun, $bulan, $sheet_name_form, $start_kolom, $search_column1, $search_column2, $search_column3, $search_column4, $search_column5, $distrik, $jenis_bahan_bakar, $lokasi_id)
  {
    $array_bulan = LabaRugiQuery::get_bulan_array_non_luar_jawa($bulan);
    $array_lokasi = LabaRugiQuery::get_location_array_non_luar_jawa($distrik->id, 'name', $lokasi_id);
    $form_id = LabaRugiQuery::get_file_import_revisi_non_luar_jawa($pgdl_file_import_revisi_id, 'form_bahan_bakar_pgdl_file_import_revisi_id');

    if ($form_id == NULL)
      return 0;

    $data = DB::select("SELECT COALESCE(sum(CASE WHEN value <> '' THEN value::float ELSE 0 END),0) AS value
            FROM pgdl_excel_datas_revisi e
            JOIN pgdl_sheets s on s.id = e.pgdl_sheet_id
            WHERE pgdl_file_import_revisi_id in " . $form_id . "
            AND s.name like '" . $sheet_name_form . "'
            AND e.lokasi_id = " . $lokasi_id . "
            AND kolom like '" . $start_kolom . "'
            AND row in (
                SELECT row
                FROM pgdl_excel_datas_revisi e
                JOIN pgdl_sheets s on s.id = e.pgdl_sheet_id
                WHERE pgdl_file_import_revisi_id in " . $form_id . "
                AND s.name like '" . $sheet_name_form . "'
                AND e.lokasi_id = " . $lokasi_id . "
                AND kolom like '" . $search_column1 . "'
                AND value like '" . $distrik->code1 . "'
                AND row in (
                    SELECT row
                    FROM pgdl_excel_datas_revisi e
                    JOIN pgdl_sheets s on s.id = e.pgdl_sheet_id
                    WHERE pgdl_file_import_revisi_id in " . $form_id . "
                    AND s.name like '" . $sheet_name_form . "'
                    AND e.lokasi_id = " . $lokasi_id . "
                    AND kolom like '" . $search_column2 . "'
                    AND value in " . $array_lokasi . "
                    AND row in (
                        SELECT row
                        FROM pgdl_excel_datas_revisi e
                        JOIN pgdl_sheets s on s.id = e.pgdl_sheet_id
                        WHERE pgdl_file_import_revisi_id in " . $form_id . "
                        AND s.name like '" . $sheet_name_form . "'
                        AND e.lokasi_id = " . $lokasi_id . "
                        AND kolom like '" . $search_column3 . "'
                        AND value like '" . $jenis_bahan_bakar . "'
                        AND row in (
                            SELECT row
                            FROM pgdl_excel_datas_revisi e
                            JOIN pgdl_sheets s on s.id = e.pgdl_sheet_id
                            WHERE pgdl_file_import_revisi_id in " . $form_id . "
                            AND s.name like '" . $sheet_name_form . "'
                            AND e.lokasi_id = " . $lokasi_id . "
                            AND kolom like '" . $search_column4 . "'
                            AND value like '" . $tahun . "'
                            AND row in (
                                SELECT row
                                FROM pgdl_excel_datas_revisi e
                                JOIN pgdl_sheets s on s.id = e.pgdl_sheet_id
                                WHERE pgdl_file_import_revisi_id in " . $form_id . "
                                AND s.name like '" . $sheet_name_form . "'
                                AND e.lokasi_id = " . $lokasi_id . "
                                AND kolom like '" . $search_column5 . "'
                                AND value in " . $array_bulan . ")
                        )
                    )
                )
            )");

    $value = !empty($data) ? $data[0]->value : 0;
    return $value;
  }

  static function get_realisasi_produksi_penjualan_non_luar_jawa($tahun, $bulan, $distrik, $produksi_penjualan_id, $lokasi_id)
  {
    // $realisasi = PgdlRealisasiProduksiPenjualan::where('tahun_realisasi', $tahun)->where('bulan_realisasi', $bulan)->whereIn('lokasi_id', $locations)->where('produksi_penjualan_id', $produksi_penjualan_id)->get();
    $realisasi = DB::select("select realisasi from pgdl_realisasi_produksi_penjualan
        where lokasi_id = " . $lokasi_id . "
        and tahun_realisasi = " . $tahun . "
        and bulan_realisasi <= " . $bulan . "
        and produksi_penjualan_id = " . $produksi_penjualan_id);
    // dd($realisasi);
    $realisasi = !empty($realisasi) ? $realisasi[0]->realisasi : 0;
    return $realisasi;
  }

  static function get_bulan_array_non_luar_jawa($bulan)
  {
    $array_bulan = array();
    for ($i = 1; $i <= $bulan; $i++) {
      $array_bulan[$i] = "'" . $i . "'";
    }
    $array_bulan = implode(',', $array_bulan);
    $array_bulan = "(" . $array_bulan . ")";
    return $array_bulan;
  }

  static function get_column_array_non_luar_jawa($start_kolom, $bulan, $increment)
  {
    $columns = array();
    for ($i = 1; $i <= $bulan; $i++) {
      $columns[$i] = "'" . $start_kolom . "'";
      // untuk menentukan kolom bulan sesuai increment. harus penambahan manual karena start_kolom adalah string
      // jika langsung ditambahkan sesuai increment, akan berubah menjadi angka
      for ($incr = 1; $incr <= $increment; $incr++)
        $start_kolom++;
    }
    $columns = implode(',', $columns);
    $columns = "(" . $columns . ")";
    return $columns;
  }

  static function get_location_array_non_luar_jawa($distrik_id, $column, $lokasi_id)
  {
    $locations = array();
    // dump($distrik_id, $column, $lokasi_id);
    $loc = Lokasi::where('id', $lokasi_id)->get();
    // dd($distrik_id, $column, $lokasi_id, $loc);
    foreach ($loc as $key => $value) {
      if ($column == 'name')
        $locations[$key] = "'" . $value[$column] . "'";
      else
        $locations[$key] = $value[$column];
    }
    $locations = implode(',', $locations);
    $locations = "(" . $locations . ")";

    return $locations;
  }

  static function get_file_import_revisi_non_luar_jawa($pgdl_file_import_revisi_id, $form_name)
  {
    $file_import_revisi = DB::select("select * from pgdl_file_imports_revisi where id IN " . $pgdl_file_import_revisi_id . " and " . $form_name . " IS NOT NULL and " . $form_name . " <> 0 ");
    // PGDLFileImportRevisi::whereIn('id', $pgdl_file_import_revisi_id)->get();

    if ($file_import_revisi) {
      $res = [];
      $i = 0;
      foreach ($file_import_revisi as $key => $value) {
        $res[$key] = $value->$form_name;
        $i++;
      }
      $res = implode(",", $res);
      $res = "(" . $res . ")";
      return $res;
    }
    return NULL;
  }

  static function get_keterangan_number_indentation_non_luar_jawa($i, $no_utama, $no_sub_1, $no_sub_2, $no_sub_3)
  {
    // untuk menampilkan indentasi atau tidak
    $row_utama = [14, 26, 50, 52, 58, 61, 62, 63, 65];
    $row_sub_1 = [15, 21, 24, 28, 38, 45, 46, 47, 48, 53, 54, 55, 56, 57, 59, 60, 66, 67, 68, 69];
    $row_sub_2 = [16, 17, 18, 19, 20, 22, 23, 29, 30, 31, 32, 33, 34, 35, 36, 37, 39, 42];
    $text_result = '';

    // khusus baris 14, nomor dimulai dari 2 ;
    $no_utama = ($i == 14 ? $no_utama = 1 : ($i == 65 ? $no_utama = 0 : $no_utama));

    // khusus baris 22 & 23, nomor +1;
    $no_sub_1 = ($i == 23 ? 1 : ($i == 24 ? 2 : $no_sub_1));

    if (in_array($i, $row_utama, true)) {
      //tanpa indentasi
      $text_result = ++$no_utama . ". ";
      $no_sub_1 = $no_sub_2 = $no_sub_3 = 0;
    } elseif (in_array($i, $row_sub_1, true)) {
      //sub 1, indentasi
      $no_sub_2 = $no_sub_3 = 0;
      $text_result = "&nbsp;&nbsp;&nbsp;&nbsp;" . $no_utama . "." . ++$no_sub_1 . " ";
    } elseif (in_array($i, $row_sub_2, true)) {
      //sub 2, indentasi
      $no_sub_3 = 0;

      $text_result = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $no_utama . "." . $no_sub_1 . "." . ++$no_sub_2 . " ";
    } else {
      $text_result = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $no_utama . "." . $no_sub_1 . "." . $no_sub_2 . "." . ++$no_sub_3 . " ";
    }
    $result = array(
      'number_indentation' => $text_result,
      'no_utama' => $no_utama,
      'no_sub_1' => $no_sub_1,
      'no_sub_2' => $no_sub_2,
      'no_sub_3' => $no_sub_3
    );

    return $result;
  }

  static function get_file_id_pengendalian($jenis_id, $tahun_anggaran, $distrik_id)
  {
    $files = DB::select("select p.id
                        from pgdl_file_imports_revisi p
                        join pgdl_templates t on t.id = p.pgdl_template_id
                        where t.jenis_id = " . $jenis_id . "
                        and p.tahun=" . $tahun_anggaran . "
                        and p.distrik_id = " . $distrik_id . ";");

    if ($files) {
      $res = [];
      $i = 0;
      foreach ($files as $key => $value) {
        $res[$i] = $value->id;
        $i++;
      }
      $res = implode(",", $res);
      //dd(var_dump($new));
      $res = "(" . $res . ")";
      return $res;
    }
    return $files;
  }

  static function get_data_pengendalian($file_id, $sheet_name, $kolom)
  {
    $datas = DB::select("select e.row, e.value
                            from pgdl_excel_datas_revisi e
                            join pgdl_sheets s on s.id = e.pgdl_sheet_id
                            where s.name = '" . $sheet_name . "'
                            and e.pgdl_file_import_revisi_id in " . $file_id . "
                            and e.kolom = '" . $kolom . "'
                            and e.row > 12
                            order by e.row;");
    if ($datas) {
      $results = [];
      foreach ($datas as $key => $value) {
        $results[$value->row] = $value->value;
      }
      return $results;
    }
    return $datas;
  }

  static function get_parent_code($keterangan, $strategi_bisnis)
  {
    if ($strategi_bisnis->name == 'OM') {
      $data = PgdlLRParentCode::where('keterangan', 'like', "%" . $keterangan . "%")
        ->whereNotIn('kode_parent_om', ['#', '-'])
        ->first();
      // dump($data);
      $parent_code = !empty($data) ? $data->kode_parent_om : '';
    } else {
      $data = PgdlLRParentCode::where('keterangan', 'like', "%" . $keterangan . "%")
        ->whereNotIn('kode_parent_up', ['#', '-'])
        ->first();

      $parent_code = !empty($data) ? $data->kode_parent_up : '';
    }
    // dump($data);
    // dump($parent_code);
    return $parent_code;
  }

  // function get_realisasi($parent, $distrik, $year, $month){

  //     $data = PgdlPljPrkAo::
  //             selectRaw('dstrct_code, sum(tran_amount) as value')
  //             ->where('dstrct_code', $distrik->code1)
  //             ->where('years', $year)
  //             ->where('months','<=',$month)
  //             ->where('project_no', 'ilike', "%".$parent."%")
  //             ->groupBy('dstrct_code')
  //             ->first();

  //     $value = !empty($data) ? $data->value : 0;
  //     return $value;
  // }

  static function get_rkap_sd_bulan($pgdl_file_import_revisi_id, $tahun, $bulan, $sheet_name, $start_kolom, $row)
  {
    $columns = LabaRugiQuery::get_column_array($start_kolom, $bulan, 1);

    $data = DB::select("select e.row, coalesce(sum(CASE WHEN value <> '' THEN value::float ELSE 0 END),0) as value
                from pgdl_excel_datas_revisi e
                join pgdl_sheets s on e.pgdl_sheet_id = s.id
                where e.pgdl_file_import_revisi_id in " . $pgdl_file_import_revisi_id . "
                and e.row = " . $row . "
                and e.kolom in " . $columns . "
                and s.name like '" . $sheet_name . "'
                group by e.row
                order by e.row;");

    $value = !empty($data) ? $data[0]->value : 0;
    return $value;
  }

  static function get_rkap_sd_bulan_biaya_usaha_lainnya($pgdl_file_import_revisi_id, $tahun, $bulan, $sheet_name, $start_kolom, $search_column, $search_cell_column, $search_cell_row)
  {
    $columns = LabaRugiQuery::get_column_array($start_kolom, $bulan, 1);

    $data = DB::select("select COALESCE(sum(CASE WHEN value <> '' THEN value::float ELSE 0 END),0) as value
                from pgdl_excel_datas_revisi e
                join pgdl_sheets s on e.pgdl_sheet_id = s.id
                where pgdl_file_import_revisi_id IN " . $pgdl_file_import_revisi_id . "
                and s.name = '" . $sheet_name . "'
                and kolom in " . $columns . "
                and row in (
                    select row
                    from pgdl_excel_datas_revisi e
                    join pgdl_sheets s on e.pgdl_sheet_id = s.id
                    where pgdl_file_import_revisi_id IN " . $pgdl_file_import_revisi_id . "
                    and s.name = '" . $sheet_name . "'
                    and kolom like '" . $search_column . "'
                    and value in (
                        select value
                        from pgdl_excel_datas_revisi e
                        join pgdl_sheets s on e.pgdl_sheet_id = s.id
                        where pgdl_file_import_revisi_id IN " . $pgdl_file_import_revisi_id . "
                        and s.name = '" . $sheet_name . "'
                        and kolom like '" . $search_cell_column . "'
                        and row = " . $search_cell_row . "
                    )
                )");
    $value = !empty($data) ? $data[0]->value : 0;
    return $value;
  }

  static function get_rkap_sd_bulan_form_6_10($pgdl_file_import_revisi_id, $tahun, $bulan, $sheet_name_rkau, $sheet_name_form, $start_kolom, $search_column1, $search_cell_column_1, $search_cell_row_1, $search_column2, $search_cell_column_2, $search_cell_row_2, $jenis_id, $distrik)
  {

    $columns = LabaRugiQuery::get_column_array($start_kolom, $bulan, 2);
    if ($jenis_id == 2)
      $form_id = LabaRugiQuery::get_file_import_revisi($pgdl_file_import_revisi_id, 'form6_rutin_pgdl_file_import_revisi_id');
    elseif ($jenis_id == 3)
      $form_id = LabaRugiQuery::get_file_import_revisi($pgdl_file_import_revisi_id, 'form6_reimburse_pgdl_file_import_revisi_id');
    // elseif($jenis_id == 6)
    else
      $form_id = LabaRugiQuery::get_file_import_revisi($pgdl_file_import_revisi_id, 'form10_pln_pgdl_file_import_revisi_id');

    if ($form_id == NULL)
      return 0;

    $data = DB::select("SELECT COALESCE(sum(CASE WHEN value <> '' THEN value::float ELSE 0 END),0) AS value
                            FROM pgdl_excel_datas_revisi e
                            JOIN pgdl_sheets s on s.id = e.pgdl_sheet_id
                            WHERE pgdl_file_import_revisi_id in " . $form_id . "
                            AND s.name like '" . $sheet_name_form . "'
                            AND kolom in " . $columns . "
                            AND row in (
                                SELECT row
                                FROM pgdl_excel_datas_revisi e
                                JOIN pgdl_sheets s on s.id = e.pgdl_sheet_id
                                WHERE pgdl_file_import_revisi_id in " . $form_id . "
                                AND s.name like '" . $sheet_name_form . "'
                                AND kolom like '" . $search_column1 . "'
                                AND value like '" . $distrik->code1 . "'
                                AND row in (
                                    SELECT row
                                    FROM pgdl_excel_datas_revisi e
                                    JOIN pgdl_sheets s on s.id = e.pgdl_sheet_id
                                    WHERE pgdl_file_import_revisi_id in " . $form_id . "
                                    AND s.name like '" . $sheet_name_form . "'
                                    AND kolom like '" . $search_column2 . "'
                                    AND value like (
                                        SELECT value FROM pgdl_excel_datas_revisi e
                                        WHERE pgdl_file_import_revisi_id in " . $pgdl_file_import_revisi_id . "
                                        AND s.name like '" . $sheet_name_rkau . "'
                                        AND kolom like '" . $search_cell_column_2 . "'
                                        AND row = " . $search_cell_row_2 . "
                                    )
                                )
                            )");

    $value = !empty($data) ? $data[0]->value : 0;
    return $value;
  }

  static function get_rkap_sd_bulan_bahan_bakar($pgdl_file_import_revisi_id, $tahun, $bulan, $sheet_name_form, $start_kolom, $search_column1, $search_column2, $search_column3, $search_column4, $distrik)
  {
    $array_bulan = LabaRugiQuery::get_bulan_array($bulan);
    $array_lokasi = LabaRugiQuery::get_location_array($distrik->id, 'name');
    $form_id = LabaRugiQuery::get_file_import_revisi($pgdl_file_import_revisi_id, 'form_bahan_bakar_pgdl_file_import_revisi_id');

    if ($form_id == NULL)
      return 0;

    $data = DB::select("
            SELECT sum(value::float) AS value
            FROM pgdl_excel_datas_revisi e
            JOIN pgdl_sheets s on s.id = e.pgdl_sheet_id
            WHERE pgdl_file_import_revisi_id in " . $form_id . "
            AND s.name like '" . $sheet_name_form . "'
            AND kolom like '" . $start_kolom . "'
            AND row in (
                SELECT row FROM pgdl_excel_datas_revisi e
                JOIN pgdl_sheets s on s.id = e.pgdl_sheet_id
                WHERE pgdl_file_import_revisi_id in " . $form_id . "
                AND s.name like '" . $sheet_name_form . "'
                AND kolom like '" . $search_column1 . "'
                AND value like '" . $distrik->code1 . "'

                AND row in (
                    SELECT row FROM pgdl_excel_datas_revisi e
                    JOIN pgdl_sheets s on s.id = e.pgdl_sheet_id
                    WHERE pgdl_file_import_revisi_id in " . $form_id . "
                    AND s.name like '" . $sheet_name_form . "'
                    AND kolom like '" . $search_column2 . "'
                    AND value in " . $array_lokasi . "

                AND row in (
                    SELECT row FROM pgdl_excel_datas_revisi e
                    JOIN pgdl_sheets s on s.id = e.pgdl_sheet_id WHERE pgdl_file_import_revisi_id in " . $form_id . "
                    AND s.name like '" . $sheet_name_form . "'
                    AND kolom like '" . $search_column3 . "'
                    AND value like '" . $tahun . "'
                    AND row in (
                        SELECT row FROM pgdl_excel_datas_revisi e
                        JOIN pgdl_sheets s on s.id = e.pgdl_sheet_id
                        WHERE pgdl_file_import_revisi_id in " . $form_id . "
                        AND s.name like '" . $sheet_name_form . "'
                        AND kolom like '" . $search_column4 . "'
                        AND value in " . $array_bulan . "
                    )
                )
            )
        )");
    $value = !empty($data) ? ($data[0]->value != null ? $data[0]->value : 0) : 0;
    return $value;
  }

  static function get_rkap_sd_bulan_per_bahan_bakar($pgdl_file_import_revisi_id, $tahun, $bulan, $sheet_name_form, $start_kolom, $search_column1, $search_column2, $search_column3, $search_column4, $search_column5, $distrik, $jenis_bahan_bakar)
  {
    $array_bulan = LabaRugiQuery::get_bulan_array($bulan);
    $array_lokasi = LabaRugiQuery::get_location_array($distrik->id, 'name');
    $form_id = LabaRugiQuery::get_file_import_revisi($pgdl_file_import_revisi_id, 'form_bahan_bakar_pgdl_file_import_revisi_id');

    if ($form_id == NULL)
      return 0;

    $data = DB::select("SELECT COALESCE(sum(CASE WHEN value <> '' THEN value::float ELSE 0 END),0) AS value
            FROM pgdl_excel_datas_revisi e
            JOIN pgdl_sheets s on s.id = e.pgdl_sheet_id
            WHERE pgdl_file_import_revisi_id in " . $form_id . "
            AND s.name like '" . $sheet_name_form . "'
            AND kolom like '" . $start_kolom . "'
            AND row in (
                SELECT row
                FROM pgdl_excel_datas_revisi e
                JOIN pgdl_sheets s on s.id = e.pgdl_sheet_id
                WHERE pgdl_file_import_revisi_id in " . $form_id . "
                AND s.name like '" . $sheet_name_form . "'
                AND kolom like '" . $search_column1 . "'
                AND value like '" . $distrik->code1 . "'
                AND row in (
                    SELECT row
                    FROM pgdl_excel_datas_revisi e
                    JOIN pgdl_sheets s on s.id = e.pgdl_sheet_id
                    WHERE pgdl_file_import_revisi_id in " . $form_id . "
                    AND s.name like '" . $sheet_name_form . "'
                    AND kolom like '" . $search_column2 . "'
                    AND value in " . $array_lokasi . "
                    AND row in (
                        SELECT row
                        FROM pgdl_excel_datas_revisi e
                        JOIN pgdl_sheets s on s.id = e.pgdl_sheet_id
                        WHERE pgdl_file_import_revisi_id in " . $form_id . "
                        AND s.name like '" . $sheet_name_form . "'
                        AND kolom like '" . $search_column3 . "'
                        AND value like '" . $jenis_bahan_bakar . "'
                        AND row in (
                            SELECT row
                            FROM pgdl_excel_datas_revisi e
                            JOIN pgdl_sheets s on s.id = e.pgdl_sheet_id
                            WHERE pgdl_file_import_revisi_id in " . $form_id . "
                            AND s.name like '" . $sheet_name_form . "'
                            AND kolom like '" . $search_column4 . "'
                            AND value like '" . $tahun . "'
                            AND row in (
                                SELECT row
                                FROM pgdl_excel_datas_revisi e
                                JOIN pgdl_sheets s on s.id = e.pgdl_sheet_id
                                WHERE pgdl_file_import_revisi_id in " . $form_id . "
                                AND s.name like '" . $sheet_name_form . "'
                                AND kolom like '" . $search_column5 . "'
                                AND value in " . $array_bulan . ")
                        )
                    )
                )
            )");

    $value = !empty($data) ? $data[0]->value : 0;
    return $value;
  }

  static function get_realisasi_produksi_penjualan($tahun, $bulan, $distrik, $produksi_penjualan_id)
  {
    $locations = LabaRugiQuery::get_location_array($distrik->id, 'id');
    // $realisasi = PgdlRealisasiProduksiPenjualan::where('tahun_realisasi', $tahun)->where('bulan_realisasi', $bulan)->whereIn('lokasi_id', $locations)->where('produksi_penjualan_id', $produksi_penjualan_id)->get();
    $realisasi = DB::select("select realisasi from pgdl_realisasi_produksi_penjualan where lokasi_id IN " . $locations . " and tahun_realisasi = " . $tahun . " and bulan_realisasi = " . $bulan . " and produksi_penjualan_id = " . $produksi_penjualan_id);

    $realisasi = !empty($realisasi) ? $realisasi[0]->realisasi : 0;
    return $realisasi;
  }

  static function get_bulan_array($bulan)
  {
    $array_bulan = array();
    for ($i = 1; $i <= $bulan; $i++) {
      $array_bulan[$i] = "'" . $i . "'";
    }
    $array_bulan = implode(',', $array_bulan);
    $array_bulan = "(" . $array_bulan . ")";
    return $array_bulan;
  }

  static function get_column_array($start_kolom, $bulan, $increment)
  {
    $columns = array();
    for ($i = 1; $i <= $bulan; $i++) {
      $columns[$i] = "'" . $start_kolom . "'";
      // untuk menentukan kolom bulan sesuai increment. harus penambahan manual karena start_kolom adalah string
      // jika langsung ditambahkan sesuai increment, akan berubah menjadi angka
      for ($incr = 1; $incr <= $increment; $incr++)
        $start_kolom++;
    }
    $columns = implode(',', $columns);
    $columns = "(" . $columns . ")";
    return $columns;
  }

  static function get_location_array($distrik_id, $column)
  {
    $locations = array();
    $loc = Lokasi::where('distrik_id', $distrik_id)->get();
    foreach ($loc as $key => $value) {
      if ($column == 'name')
        $locations[$key] = "'" . $value[$column] . "'";
      else
        $locations[$key] = $value[$column];
    }
    $locations = implode(',', $locations);
    $locations = "(" . $locations . ")";

    return $locations;
  }

  static function get_file_import_revisi($pgdl_file_import_revisi_id, $form_name)
  {
    $file_import_revisi = DB::select("select * from pgdl_file_imports_revisi where id IN " . $pgdl_file_import_revisi_id . " and " . $form_name . " IS NOT NULL and " . $form_name . " <> 0 ");
    // PGDLFileImportRevisi::whereIn('id', $pgdl_file_import_revisi_id)->get();

    if ($file_import_revisi) {
      $res = [];
      $i = 0;
      foreach ($file_import_revisi as $key => $value) {
        $res[$key] = $value->$form_name;
        $i++;
      }
      $res = implode(",", $res);
      $res = "(" . $res . ")";
      return $res;
    }
    return NULL;
  }

  static function get_keterangan_number_indentation($i, $no_utama, $no_sub_1, $no_sub_2, $no_sub_3)
  {
    // untuk menampilkan indentasi atau tidak
    $row_utama = [14, 26, 50, 52, 58, 61, 62, 63, 65];
    $row_sub_1 = [15, 21, 24, 28, 38, 45, 46, 47, 48, 53, 54, 55, 56, 57, 59, 60, 66, 67, 68, 69];
    $row_sub_2 = [16, 17, 18, 19, 20, 22, 23, 29, 30, 31, 32, 33, 34, 35, 36, 37, 39, 42];
    $text_result = '';

    // khusus baris 14, nomor dimulai dari 2 ;
    $no_utama = ($i == 14 ? $no_utama = 1 : ($i == 65 ? $no_utama = 0 : $no_utama));

    // khusus baris 22 & 23, nomor +1;
    $no_sub_1 = ($i == 23 ? 1 : ($i == 24 ? 2 : $no_sub_1));

    if (in_array($i, $row_utama, true)) {
      //tanpa indentasi
      $text_result = ++$no_utama . ". ";
      $no_sub_1 = $no_sub_2 = $no_sub_3 = 0;
    } elseif (in_array($i, $row_sub_1, true)) {
      //sub 1, indentasi
      $no_sub_2 = $no_sub_3 = 0;
      $text_result = "&nbsp;&nbsp;&nbsp;&nbsp;" . $no_utama . "." . ++$no_sub_1 . " ";
    } elseif (in_array($i, $row_sub_2, true)) {
      //sub 2, indentasi
      $no_sub_3 = 0;

      $text_result = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $no_utama . "." . $no_sub_1 . "." . ++$no_sub_2 . " ";
    } else {
      $text_result = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $no_utama . "." . $no_sub_1 . "." . $no_sub_2 . "." . ++$no_sub_3 . " ";
    }
    $result = array(
      'number_indentation' => $text_result,
      'no_utama' => $no_utama,
      'no_sub_1' => $no_sub_1,
      'no_sub_2' => $no_sub_2,
      'no_sub_3' => $no_sub_3
    );

    return $result;
  }
}
