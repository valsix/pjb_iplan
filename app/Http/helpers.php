<?php

class Helper
{
    public static function terbilang($angka)
    {
        // pastikan kita hanya berususan dengan tipe data numeric
        $angka = (float)$angka;

        // array bilangan
        // sepuluh dan sebelas merupakan special karena awalan 'se'
        $bilangan = array(
            '',
            'satu',
            'dua',
            'tiga',
            'empat',
            'lima',
            'enam',
            'tujuh',
            'delapan',
            'sembilan',
            'sepuluh',
            'sebelas',
        );

        // pencocokan dimulai dari satuan angka terkecil
        if ($angka < 12) {
            // mapping angka ke index array $bilangan
            return $bilangan[$angka];
        } else if ($angka < 20) {
            // bilangan 'belasan'
            // misal 18 maka 18 - 10 = 8
            return $bilangan[$angka - 10] . ' belas';
        } else if ($angka < 100) {
            // bilangan 'puluhan'
            // misal 27 maka 27 / 10 = 2.7 (integer => 2) 'dua'
            // untuk mendapatkan sisa bagi gunakan modulus
            // 27 mod 10 = 7 'tujuh'
            $hasil_bagi = (int)($angka / 10);
            $hasil_mod = $angka % 10;
            return trim(sprintf('%s puluh %s', $bilangan[$hasil_bagi], $bilangan[$hasil_mod]));
        } else if ($angka < 200) {
            // bilangan 'seratusan' (itulah indonesia knp tidak satu ratus saja? :))
            // misal 151 maka 151 = 100 = 51 (hasil berupa 'puluhan')
            // daripada menulis ulang rutin kode puluhan maka gunakan
            // saja fungsi rekursif dengan memanggil fungsi Helper::terbilang(51)
            return sprintf('seratus %s', Helper::terbilang($angka - 100));
        } else if ($angka < 1000) {
            // bilangan 'ratusan'
            // misal 467 maka 467 / 100 = 4,67 (integer => 4) 'empat'
            // sisanya 467 mod 100 = 67 (berupa puluhan jadi gunakan rekursif Helper::terbilang(67))
            $hasil_bagi = (int)($angka / 100);
            $hasil_mod = $angka % 100;
            return trim(sprintf('%s ratus %s', $bilangan[$hasil_bagi], Helper::terbilang($hasil_mod)));
        } else if ($angka < 2000) {
            // bilangan 'seribuan'
            // misal 1250 maka 1250 - 1000 = 250 (ratusan)
            // gunakan rekursif Helper::terbilang(250)
            return trim(sprintf('seribu %s', Helper::terbilang($angka - 1000)));
        } else if ($angka < 1000000) {
            // bilangan 'ribuan' (sampai ratusan ribu
            $hasil_bagi = (int)($angka / 1000); // karena hasilnya bisa ratusan jadi langsung digunakan rekursif
            $hasil_mod = $angka % 1000;
            return sprintf('%s ribu %s', Helper::terbilang($hasil_bagi), Helper::terbilang($hasil_mod));
        } else if ($angka < 1000000000) {
            // bilangan 'jutaan' (sampai ratusan juta)
            // 'satu puluh' => SALAH
            // 'satu ratus' => SALAH
            // 'satu juta' => BENAR
            // @#$%^ WT*

            // hasil bagi bisa satuan, belasan, ratusan jadi langsung kita gunakan rekursif
            $hasil_bagi = (int)($angka / 1000000);
            $hasil_mod = $angka % 1000000;
            return trim(sprintf('%s juta %s', Helper::terbilang($hasil_bagi), Helper::terbilang($hasil_mod)));
        } else if ($angka < 1000000000000) {
            // bilangan 'milyaran'
            $hasil_bagi = (int)($angka / 1000000000);
            // karena batas maksimum integer untuk 32bit sistem adalah 2147483647
            // maka kita gunakan fmod agar dapat menghandle angka yang lebih besar
            $hasil_mod = fmod($angka, 1000000000);
            return trim(sprintf('%s milyar %s', Helper::terbilang($hasil_bagi), Helper::terbilang($hasil_mod)));
        } else if ($angka < 1000000000000000) {
            // bilangan 'triliun'
            $hasil_bagi = $angka / 1000000000000;
            $hasil_mod = fmod($angka, 1000000000000);
            return trim(sprintf('%s triliun %s', Helper::terbilang($hasil_bagi), Helper::terbilang($hasil_mod)));
        } else {
            return 'Wow...';
        }
    }

    public static function shortName($name)
    {
        return "" . explode(' ', $name)[0] . " " . (count(explode(' ', $name)) > 1 ? substr(explode(' ', $name)[1], 0, 1) : '');
    }

    public static function romanNumeral($input_arabic_numeral = '')
    {

        if ($input_arabic_numeral == '') {
            $input_arabic_numeral = date("Y");
        } // DEFAULT OUTPUT: THIS YEAR

        if (!ereg('[0-9]', $arabic_numeral_text)) {
            return false;
        }

        if ($arabic_numeral > 4999) {
            return false;
        }

        if ($arabic_numeral < 1) {
            return false;
        }

        if ($arabic_numeral_length > 4) {
            return false;
        }

        $roman_numeral_units = $roman_numeral_tens = $roman_numeral_hundreds = $roman_numeral_thousands = array();
        $roman_numeral_units[0] = $roman_numeral_tens[0] = $roman_numeral_hundreds[0] = $roman_numeral_thousands[0] = ''; // NO ZEROS IN ROMAN NUMERALS

        $roman_numeral_units[1] = 'I';
        $roman_numeral_units[2] = 'II';
        $roman_numeral_units[3] = 'III';
        $roman_numeral_units[4] = 'IV';
        $roman_numeral_units[5] = 'V';
        $roman_numeral_units[6] = 'VI';
        $roman_numeral_units[7] = 'VII';
        $roman_numeral_units[8] = 'VIII';
        $roman_numeral_units[9] = 'IX';

        $roman_numeral_tens[1] = 'X';
        $roman_numeral_tens[2] = 'XX';
        $roman_numeral_tens[3] = 'XXX';
        $roman_numeral_tens[4] = 'XL';
        $roman_numeral_tens[5] = 'L';
        $roman_numeral_tens[6] = 'LX';
        $roman_numeral_tens[7] = 'LXX';
        $roman_numeral_tens[8] = 'LXXX';
        $roman_numeral_tens[9] = 'XC';

        $roman_numeral_hundreds[1] = 'C';
        $roman_numeral_hundreds[2] = 'CC';
        $roman_numeral_hundreds[3] = 'CCC';
        $roman_numeral_hundreds[4] = 'CD';
        $roman_numeral_hundreds[5] = 'D';
        $roman_numeral_hundreds[6] = 'DC';
        $roman_numeral_hundreds[7] = 'DCC';
        $roman_numeral_hundreds[8] = 'DCCC';
        $roman_numeral_hundreds[9] = 'CM';

        $roman_numeral_thousands[1] = 'M';
        $roman_numeral_thousands[2] = 'MM';
        $roman_numeral_thousands[3] = 'MMM';
        $roman_numeral_thousands[4] = 'MMMM';

        if ($arabic_numeral_length == 3) {
            $arabic_numeral_text = "0" . $arabic_numeral_text;
        }
        if ($arabic_numeral_length == 2) {
            $arabic_numeral_text = "00" . $arabic_numeral_text;
        }
        if ($arabic_numeral_length == 1) {
            $arabic_numeral_text = "000" . $arabic_numeral_text;
        }

        $anu = substr($arabic_numeral_text, 3, 1);
        $anx = substr($arabic_numeral_text, 2, 1);
        $anc = substr($arabic_numeral_text, 1, 1);
        $anm = substr($arabic_numeral_text, 0, 1);

        $roman_numeral_text = $roman_numeral_thousands[$anm] . $roman_numeral_hundreds[$anc] . $roman_numeral_tens[$anx] . $roman_numeral_units[$anu];
        return ($roman_numeral_text);
    }

    public static function shorten($fullname)
    {
        $full = $fullname;
        $fixedName = '';

        $nameArr = explode(',', $fullname);
        $name = $nameArr[0];
        $title = '';
        if (count($nameArr) > 1) {
            $title = $nameArr[1];
        }

        $nameArr = explode(' ', $name);
        if (count($nameArr) > 2) {
            foreach ($nameArr as $i => $val) {
                if ($i > 1) {
                    $nameArr[$i] = strtoupper(substr($nameArr[$i], 0, 1)) . '.';
                }
            }
        }

        $name = '';
        foreach ($nameArr as $val) {
            $name = $name . ' ' . $val;
        }

        if (strlen($title) > 0) {
            $name = $name . ',' . $title;
        }
        $fixedName = $name;
        //return $fixedName;
        return $full;
    }
}

if (!function_exists('romanNumeral')) {
    function romanNumeral($integer, $upcase = true)
    {
        $table = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $return = '';
        while ($integer > 0) {
            foreach ($table as $rom => $arb) {
                if ($integer >= $arb) {
                    $integer -= $arb;
                    $return .= $rom;
                    break;
                }
            }
        }

        return $return;
    }
}

function monthIdn($month)
{

    switch ($month) {
        case '1':
            return "Januari";
        case '2':
            return "Februari";
        case '3':
            return "Maret";
        case '4':
            return "April";
        case '5':
            return "Mei";
        case '6':
            return "Juni";
        case '7':
            return "Juli";
        case '8':
            return "Agustus";
        case '9':
            return "September";
        case '10':
            return "Oktober";
        case '11':
            return "November";
        case '12':
            return "Desember";
    }
}

function dateIdn($date)
{
    $date = explode(' ', $date);
    if (count($date) < 3) {
        $date = explode('-', $date[0]);
        if (count($date < 3)) {
            $date = explode('-', '2016-01-01');
        } else {

        }
    }
    $month = monthIdn($date[1]);
    $final = $date[2] . " " . $month . " " . $date[0];
    return $final;
}

function dateIdnFromTimestamp($timestamp)
{
    $date = date('Y m d', strtotime($timestamp));
    $date = explode(' ', $date);
    if (count($date) < 3) {
        $date = explode('-', $date[0]);
        if (count($date < 3)) {
            $date = explode('-', '2016-01-01');
        } else {

        }
    }
    $month = monthIdn($date[1]);
    $final = $date[2] . " " . $month . " " . $date[0];
    return $final;
}

function reverseDateIdnToDB($dateIdn)
{

}

function oldOrDbData($old, $db)
{
    return $old != null ? $old : $db;
}