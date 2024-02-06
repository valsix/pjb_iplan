<?php

namespace App\Http\Services;

use App\Entities\PBCAO;
use App\Entities\MSF900;

class LabaRugiService
{
    public static function get_data_realisasi_msf900($sb_name, $key, $parent_code, $distrik, $tahun, $bulan, $lokasi)
    {
        # code...
        // dd($sb_name, $key, $parent_code, $distrik, $tahun, $bulan, $lokasi);
        $acount_code_material_rutin_up = ['E201','E202','E203','E204','E199','E310','E311','E313','E411','E301'];
        $acount_code_jasa_rutin_up = ['F101','F102','F104','F105','F106','F107','F108','F109','F199','F310','F312','F301','F311','F313','F411'];
        $acount_code_material_rutin_om = ['E201','E202','E203','E204','E199','E310'];
        $acount_code_material_reimburse_om = ['E311','E313','E411','E301'];
        $acount_code_jasa_rutin_om = ['F101','F102','F104','F105','F106','F107','F108','F109','F199','F310','F312'];
        $acount_code_jasa_reimburse_om = ['F301','F311','F313','F411'];

        // Catatan Row
        // $key == 14 2. PENDAPATAN USAHA
        // $key == 15 2.1 Penjualan Tenaga Listrik
        // $key == 27 3. BEBAN USAHA
        // $key == 28 // 3.1 Bahan Bakar

        $data = 0;

        if($key == 16) // 2.1.1 Komp A
            $data = $sb_name == 'OM' ? 0 : abs(LabaRugiService::get_realisasi_msf900(['A11'], $distrik, $tahun, $bulan));
        elseif($key == 17) // 2.1.2 Komp B
            $data = $sb_name == 'OM' ? 0 : abs(LabaRugiService::get_realisasi_msf900(['A12'], $distrik, $tahun, $bulan));
        elseif($key == 18) // 2.1.3 Komp C
            $data = $sb_name == 'OM' ? 0 : abs(LabaRugiService::get_realisasi_msf900(['A13'], $distrik, $tahun, $bulan));
        elseif($key == 19) // 2.1.4 Komp D
            $data = $sb_name == 'OM' ? 0 : abs(LabaRugiService::get_realisasi_msf900(['A14'], $distrik, $tahun, $bulan));
        elseif($key == 20) // 2.1.5 Komp E
            $data = $sb_name == 'OM' ? 0 : abs(LabaRugiService::get_realisasi_msf900(['A15', 'A10'], $distrik, $tahun, $bulan));
        elseif($key == 21) // 2.2 Pendapatan Jasa OM
            $data = $sb_name == 'OM' ? 0 : 0;
        elseif($key == 22) // 2.2.1 Rutin
            $data = $sb_name == 'OM' ? abs(LabaRugiService::get_realisasi_msf900(['B10'], $distrik, $tahun, $bulan)) : 0;
        elseif($key == 23) //  2.1.2 Reimburse
            $data = $sb_name == 'OM' ? abs(LabaRugiService::get_realisasi_msf900(['B301'], $distrik, $tahun, $bulan)) : 0;
        elseif($key == 24) // 2.3 Pendapatan Usaha Lainnya
            $data = abs(LabaRugiService::get_from_pbc_ao_other('UL', '01', $distrik, $tahun, $bulan)) + abs(LabaRugiService::get_from_pbc_ao(['1H'], $distrik, $tahun, $bulan));
        elseif($key == 25) //
            $data = $sb_name == 'OM' ? 0 : 0;
        elseif($key == 26) //
            $data = $sb_name == 'OM' ? 0 : 0;
        elseif($key == 29) // 3.1.1 HSD
            $data = LabaRugiService::get_realisasi_msf900(['D103', 'D109'], $distrik, $tahun, $bulan);
        elseif($key == 30) // 3.1.2 MFO
            $data = LabaRugiService::get_realisasi_msf900(['D105'], $distrik, $tahun, $bulan);
        elseif($key == 31) // 3.1.3 IDO
            $data = LabaRugiService::get_realisasi_msf900(['D104'], $distrik, $tahun, $bulan);
        elseif($key == 32) // 3.1.4 Gas Alam
            $data = LabaRugiService::get_realisasi_msf900(['D107'], $distrik, $tahun, $bulan);
        elseif($key == 33) // 3.1.5 Batu Bara
            $data = LabaRugiService::get_realisasi_msf900(['D102'], $distrik, $tahun, $bulan);
        elseif($key == 34) // 3.1.6 Minyak Pelumas
            $data = LabaRugiService::get_realisasi_msf900(['D108'], $distrik, $tahun, $bulan);
        elseif($key == 35) // 3.1.7 Kimia
            $data = LabaRugiService::get_realisasi_msf900(['D110', 'D199'], $distrik, $tahun, $bulan);
        elseif($key == 36) // 3.1.8 E & P
            $data = LabaRugiService::get_realisasi_msf900(['D101'], $distrik, $tahun, $bulan);
        elseif($key == 37) // 3.1.9 Pajak Permukaan air
            $data = LabaRugiService::get_realisasi_msf900(['D111'], $distrik, $tahun, $bulan);
        elseif($key == 38) // 3.2 Pemeliharaan
            $data = $sb_name == 'OM' ? 0 : 0;
        elseif($key == 39) // 3.2.1 Material
            $data = $sb_name == 'OM' ? 0 : 0;

        elseif($key == 40) // 3.2.1.1 Rutin Material
            $data = $sb_name == 'OM' ? LabaRugiService::get_realisasi_msf900($acount_code_material_rutin_om , $distrik, $tahun, $bulan) : LabaRugiService::get_realisasi_msf900($acount_code_material_rutin_up , $distrik, $tahun, $bulan);
        elseif($key == 41) // 3.2.1.2 Reimburse Material
            $data = $sb_name == 'OM' ? LabaRugiService::get_realisasi_msf900($acount_code_material_reimburse_om , $distrik, $tahun, $bulan) : 0;
        elseif($key == 42) // 3.2.2 Jasa Borongan
            $data = $sb_name == 'OM' ? 0 : 0;
        elseif($key == 43) // 3.2.2.1 Rutin Jasa
            $data = $sb_name == 'OM' ? LabaRugiService::get_realisasi_msf900($acount_code_jasa_rutin_om , $distrik, $tahun, $bulan) : LabaRugiService::get_realisasi_msf900($acount_code_jasa_rutin_up , $distrik, $tahun, $bulan);
        elseif($key == 44) // 3.2.2.2 Reimburse Jasa
            $data = $sb_name == 'OM' ? LabaRugiService::get_realisasi_msf900($acount_code_jasa_reimburse_om , $distrik, $tahun, $bulan) : 0;
        elseif($key == 45) // 3.3 Kepegawaian
            $data = $sb_name == 'OM' ? LabaRugiService::get_from_pbc_ao(['3Q', '5Q'], $distrik, $tahun, $bulan) : LabaRugiService::get_from_pbc_ao(['2Q'], $distrik, $tahun, $bulan);
        elseif($key == 46) // 3.4 Penyusutan
            $data = $sb_name == 'OM' ? LabaRugiService::get_from_pbc_ao(['3T', '5T'], $distrik, $tahun, $bulan) : LabaRugiService::get_from_pbc_ao(['2T'], $distrik, $tahun, $bulan);
        elseif($key == 47) // 3.5 Administrasi
            $data = $sb_name == 'OM' ? LabaRugiService::get_from_pbc_ao(['3S', '5S'], $distrik, $tahun, $bulan) : LabaRugiService::get_from_pbc_ao(['2S'], $distrik, $tahun, $bulan);
        elseif($key == 48) // 3.6 Biaya Usaha Lainnya
            $data = $sb_name == 'OM' ? abs(LabaRugiService::get_from_pbc_sum('UL', 0, 2, 10, 10, $distrik, $tahun, $bulan)) + LabaRugiService::get_from_pbc_ao(['2R'], $distrik, $tahun, $bulan)
                                        : abs(LabaRugiService::get_from_pbc_sum('UL', 0, 2, 10, 10, $distrik, $tahun, $bulan)) + LabaRugiService::get_from_pbc_ao(['3R'], $distrik, $tahun, $bulan);
        elseif($key == 49) // Sementara untuk codingan 3.7 Pembelian Tenaga Listrik, menunggu implementasi untuk tempat pastinya
            $data = $sb_name == 'OM' ? LabaRugiService::get_from_pbc_ao(['3X'], $distrik, $tahun, $bulan) : LabaRugiService::get_from_pbc_ao(['2X'], $distrik, $tahun, $bulan);
        elseif($key == 50) //
            $data = $sb_name == 'OM' ? 0 : 0;
        elseif($key == 51) //
            $data = $sb_name == 'OM' ? 0 : 0;
        elseif($key == 52) //
            $data = $sb_name == 'OM' ? 0 : 0;
        elseif($key == 53) // 5.1 Bunga Pinjaman
            $data = $sb_name == 'OM' ? abs(LabaRugiService::get_from_pbc_ao_other('3V', '09', $distrik, $tahun, $bulan)) : abs(LabaRugiService::get_from_pbc_ao_other('2V', '09', $distrik, $tahun, $bulan));
        elseif($key == 54) // 5.2 Selisih Kurs ()
            $data = $sb_name == 'OM' ? abs(LabaRugiService::get_from_pbc_ao_other('3V', '10', $distrik, $tahun, $bulan)) : abs(LabaRugiService::get_from_pbc_ao_other('2V', '10', $distrik, $tahun, $bulan));
        elseif($key == 55) // 5.3 Pendapatan
            $data = LabaRugiService::get_from_pbc_ao(['1L'], $distrik, $tahun, $bulan) * -1;
        elseif($key == 56) // 5.4 Beban
            $data = $sb_name == 'OM' ? abs(LabaRugiService::get_from_pbc_sum('3V', 0, 1, 1, 9, $distrik, $tahun, $bulan)) * -1 : abs(LabaRugiService::get_from_pbc_sum('2V', 0, 1, 1, 9, $distrik, $tahun, $bulan)) * -1;
        elseif($key == 57) // 5.5 Penyesuaian FV
            $data = $sb_name == 'OM' ? 0 : 0;
        elseif($key == 58) //
            $data = $sb_name == 'OM' ? 0 : 0;
        elseif($key == 59) // 6.1 PAJAK KINI
            $data = $sb_name == 'OM' ? 0 : 0;
        elseif($key == 60) // 6.2 PAJAK TANGGUHAN
            $data = $sb_name == 'OM' ? 0 : 0;
        elseif($key == 61) //
            $data = $sb_name == 'OM' ? 0 : 0;
        elseif($key == 62) // 8. KEPENTINGAN NON PENGENDALI
            $data = $sb_name == 'OM' ? 0 : 0;
        elseif($key == 63) // 3.1.9 Pajak Permukaan air
            $data = $sb_name == 'OM' ? 0 : 0;
        elseif($key == 64) //
            $data = $sb_name == 'OM' ? 0 : 0;
        elseif($key == 65) //
            $data = $sb_name == 'OM' ? 0 : 0;

        return $data / 1000;
    }

    public static function get_realisasi_msf900($prefix, $distrik, $tahun, $bulan){

        for ($i=0; $i < count($prefix); $i++) {
            $data[] = MSF900::
                        selectRaw('dstrct_code, sum(tran_amount) as value')
                        ->where('dstrct_code', $distrik->code1)
                        // ->where('full_period', $tahun)
                        ->where('expense_element', 'like', $prefix[$i]."%")
                        ->groupBy('dstrct_code')
                        ->first();
        }

        if (empty($data)) {
            return 0;
        } else {
            $result = 0;
            foreach ($data as $key => $value) {
                # code...
                $result += $value['value'];
            }
            return $result;
        }
        // $value = !empty($data) ? $data->value : 0;
        // return $value;
    }

    public static function get_from_pbc_sum($prefix, $start_a, $start_b, $prefix_start, $prefix_finish, $distrik, $tahun, $bulan)
    {
        # code...
        $prk_kegiatan = substr($tahun, -2).''.$prefix;
        for($a = $start_a; $a < $prefix_start; $a++){
            for($b = $start_b; $b < $prefix_finish; $b++){
                $data[] = PBCAO::selectRaw('dstrct_code, sum(actuals) as value')
                ->where('dstrct_code', $distrik->code1)
                ->where('prk_kegiatan', 'like', $prk_kegiatan."%".$a.$b)
                ->groupBy('dstrct_code')
                ->first();
            }
        }

        if (empty($data)) {
            return 0;
        } else {
            $result = 0;
            foreach ($data as $key => $value) {
                # code...
                $result += $value['value'];
            }
            return $result;
        }
    }

    public static function get_from_pbc_ao_other($prefix1, $prefix2, $distrik, $tahun, $bulan)
    {
        # code...
        $prk_kegiatan = substr($tahun, -2).''.$prefix1;
        $data[] = PBCAO::selectRaw('dstrct_code, sum(actuals) as value')
                ->where('dstrct_code', $distrik->code1)
                ->where('prk_kegiatan', 'like', "%".$prk_kegiatan."%".$prefix2)
                ->groupBy('dstrct_code')
                ->first();

        if (empty($data)) {
            return 0;
        } else {
            $result = 0;
            foreach ($data as $key => $value) {
                # code...
                $result += $value['value'];
            }
            return $result;
        }
    }

    public static function get_from_pbc_ao($prefix, $distrik, $tahun, $bulan)
    {
        # code...
        for ($i=0; $i < count($prefix); $i++) {
            $prk_kegiatan = substr($tahun, -2).''.$prefix[$i];
            $data[] = PBCAO::selectRaw('dstrct_code, sum(actuals) as value')
                    ->where('dstrct_code', $distrik->code1)
                    ->where('prk_kegiatan', 'like', $prk_kegiatan."%")
                    ->groupBy('dstrct_code')
                    ->first();
        }

        if (empty($data)) {
            return 0;
        } else {
            $result = 0;
            foreach ($data as $key => $value) {
                # code...
                $result += $value['value'];
            }
            return $result;
        }
    }
}
