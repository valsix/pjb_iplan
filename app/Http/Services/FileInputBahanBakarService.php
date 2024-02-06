<?php

namespace App\Http\Services;

use App\Entities\Distrik;
use App\Entities\ExcelDataInputBahanBakar;
use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;

class FileInputBahanBakarService {

    public static function process_excel($file_input_id, $filename, $tahun) {

        $sheet_true = false;
        $alphabet = range('P', 'Z');
        array_push($alphabet, 'AA');
        $datas = $distrik = $prk = $values = array();

        $reader = ReaderFactory::create(Type::XLSX);
        $reader->open($filename);

        $start_row_bahan_bakar = FileInputBahanBakarService::get_first_row_bahan_bakar($reader);
       
        if($start_row_bahan_bakar == 0) return ['error' => 'Struktur bisnis tidak sesuai di kolom C dan baris 8'];

        foreach ($reader->getSheetIterator() as $sheet) {
            if ($sheet->getName() == 'input BB bulanan') {
                $row_number = 1;
                foreach ($sheet->getRowIterator() as $row) {
                    $column_name = 'A';
                    foreach ($row as $row2) {
                        if ($row_number >= $start_row_bahan_bakar) {
                            // Cek row, apabila sudah mencapai row paling akhir, langsung break perulangan
                            if ($column_name == 'B') {
                                if ($row2 == '') break;
                            }

                            // Cek struktur bisnis harus UP
                            if ($column_name == 'C') {
                                if ($row2 != 'UP') return ['error' => 'Struktur bisnis tidak sesuai di kolom '.$column_name.' dan baris '.$row_number.'. Distrik harus "UP"'];
                            }

                            // Cek tahun di excel,
                            if ($column_name == 'H') {
                                if ($row2 != $tahun) return ['error' => 'Tahun inputan dan tahun excel tidak sesuai di kolom '.$column_name.' dan baris '.$row_number];
                            }

                            // Get PRK
                            if($column_name == 'J') {
                                if ($row2 == null) return ['error' => 'Terdapat prk kosong di kolom '.$column_name.' dan baris '.$row_number];
                                ExcelDataInputBahanBakar::where('prk', $row2)->delete();
                                $prk[] = $row2;
                            }

                            // Cek distrik di excel
                            if ($column_name == 'D') {
                                $check_distrik = Distrik::where('code1', $row2)->where('strategi_bisnis_id', 2)->first(); // 2 == UP
                                if (is_null($check_distrik)) return ['error' => 'Terdapat distrik yang tidak sesuai di kolom '.$column_name.' dan baris '.$row_number];
                                $distrik[] = $check_distrik->id;
                            }

                            // Get value bulanan
                            if (in_array($column_name, $alphabet)) {
                                $values[] = ($row2 == 0) ? 0 : intval($row2, 2);
                            }
                        }
                        $column_name++;
                    }
                    $row_number++;
                }

                $sheet_true = true;

            }
        }

        if (count($distrik) != count($prk)) return ['error' => 'Periksa kembali data distrik dan prk di dalam excel'];
        if ($sheet_true == false) return ['error' => 'Periksa kembali nama sheet excel yang di-upload. Nama sheet yang benar adalah "input BB bulanan"'];

        $value = array_chunk($values, 12);
        for($i = 0; $i < count($prk); $i++) {
            for ($month = 1; $month <= 12; $month++) {
                $datas[] = [
                    'file_input_bahan_bakar_id' => $file_input_id,
                    'distrik_id' => $distrik[$i],
                    'prk' => $prk[$i],
                    'beban_or_cashflow_id' => 1, // 1 == beban,
                    'month' => $month,
                    'value' => $value[$i][$month-1]
                ];
            }
        }
        
        foreach ($datas as $data) {
            ExcelDataInputBahanBakar::create($data);
        }

        return true;
    }

    private static function get_first_row_bahan_bakar($reader)
    {
        $start_row_bahan_bakar = array();

        foreach ($reader->getSheetIterator() as $sheet) {
            if ($sheet->getName() == 'input BB bulanan') {
                $row_number = 1;
                foreach ($sheet->getRowIterator() as $row) {
                    $column_name = 'A';
                    foreach ($row as $row2) {
                        if ($column_name == 'C' AND $row2 == 'UP' OR $row2 == 'OM') {
                            $start_row_bahan_bakar[] = $row_number;
                        }
                        $column_name++;
                    }
                    $row_number++;
                }
            }
        }
        
        return count($start_row_bahan_bakar) > 0 ? $start_row_bahan_bakar[0] : 0;
    }
}