<?php

use Illuminate\Database\Seeder;

class LokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $strategi = [
            [
                'id' => 1,
                'name' => "OM"
            ],
            [
                'id' => 2,
                'name' => "UP"
            ],
        ];

        $distrik = [
            ['strategi_bisnis_id' => 1, 'id' => 1, 'code1' => 'UBRS', 'code2' => 'BR', 'name' => "UP BRANTAS"],
            ['strategi_bisnis_id' => 1, 'id' => 2, 'code1' => 'UJAR', 'code2' => 'AR', 'name' => "UBJOM ARUN"],
            ['strategi_bisnis_id' => 1, 'id' => 3, 'code1' => 'UJIN', 'code2' => 'ID', 'name' => "UBJOM INDRAMAYU"],
            ['strategi_bisnis_id' => 1, 'id' => 4, 'code1' => 'UJKT', 'code2' => 'KT', 'name' => "UBJOM KALIMANTAN TELUK"],
            ['strategi_bisnis_id' => 1, 'id' => 5, 'code1' => 'UJL2', 'code2' => 'LB', 'name' => "UBJOM LUAR JAWA -2"],
            ['strategi_bisnis_id' => 1, 'id' => 6, 'code1' => 'UJLJ', 'code2' => 'LA', 'name' => "UBJOM LUAR JAWA -1"],
            ['strategi_bisnis_id' => 1, 'id' => 7, 'code1' => 'UJPC', 'code2' => 'PC', 'name' => "UBJOM PACITAN"],
            ['strategi_bisnis_id' => 1, 'id' => 8, 'code1' => 'UJPS', 'code2' => 'PS', 'name' => "UBJOM PULANG PISAU"],
            ['strategi_bisnis_id' => 1, 'id' => 9, 'code1' => 'UJPT', 'code2' => 'PN', 'name' => "UBJOM PAITON"],
            ['strategi_bisnis_id' => 1, 'id' => 10, 'code1' => 'UJRB', 'code2' => 'RB', 'name' => "UBJOM REMBANG"],
            ['strategi_bisnis_id' => 1, 'id' => 11, 'code1' => 'UJTA', 'code2' => 'TA', 'name' => "UBJOM TANJUNG AWAR-AWAR"],
            ['strategi_bisnis_id' => 1, 'id' => 12, 'code1' => 'UJTY', 'code2' => 'TY', 'name' => "UBJOM TENAYAN"],
            ['strategi_bisnis_id' => 1, 'id' => 13, 'code1' => 'UPMK', 'code2' => 'MK', 'name' => "UP MUARA KARANG"],
            ['strategi_bisnis_id' => 1, 'id' => 14, 'code1' => 'UPMT', 'code2' => 'MT', 'name' => "UP MUARA TAWAR"],
            ['strategi_bisnis_id' => 2, 'id' => 15, 'code1' => 'BPWC', 'code2' => 'BC', 'name' => "BADAN PENGELOLA WADUK CIRATA"],
            ['strategi_bisnis_id' => 2, 'id' => 16, 'code1' => 'UBRS', 'code2' => 'BR', 'name' => "UP BRANTAS"],
            ['strategi_bisnis_id' => 2, 'id' => 17, 'code1' => 'UCRT', 'code2' => 'CR', 'name' => "UP CIRATA"],
            ['strategi_bisnis_id' => 2, 'id' => 18, 'code1' => 'SGRK', 'code2' => 'GR', 'name' => "UP GRESIK"],
            ['strategi_bisnis_id' => 2, 'id' => 19, 'code1' => 'UPHB', 'code2' => 'HB', 'name' => "UBHAR BARAT"],
            ['strategi_bisnis_id' => 2, 'id' => 20, 'code1' => 'UHAR', 'code2' => 'HT', 'name' => "UBHAR TIMUR"],
            ['strategi_bisnis_id' => 2, 'id' => 21, 'code1' => 'PJB2', 'code2' => 'KP', 'name' => "KANTOR PUSAT"],
            ['strategi_bisnis_id' => 2, 'id' => 22, 'code1' => 'UPMK', 'code2' => 'MK', 'name' => "UP MUARA KARANG"],
            ['strategi_bisnis_id' => 2, 'id' => 23, 'code1' => 'UPMT', 'code2' => 'MT', 'name' => "UP MUARA TAWAR"],
            ['strategi_bisnis_id' => 2, 'id' => 24, 'code1' => 'PJAC', 'code2' => 'PA', 'name' => "PJB ACADEMY"],
            ['strategi_bisnis_id' => 2, 'id' => 25, 'code1' => 'SPTN', 'code2' => 'PT', 'name' => "UP PAITON"],
            ['strategi_bisnis_id' => 2, 'id' => 26, 'code1' => 'UPAS', 'code2' => 'PU', 'name' => "UNIT PENGEMBANGAN USAHA"]
        ];

        $lokasi = [
            ['distrik_id' => 1, 'id' => 1, 'name' => 'Wonorejo'],
            ['distrik_id' => 1, 'id' => 2, 'name' => 'Ampelgading'],
            ['distrik_id' => 2, 'id' => 3, 'name' => 'Common'],
            ['distrik_id' => 3, 'id' => 4, 'name' => 'Common'],
            ['distrik_id' => 4, 'id' => 5, 'name' => 'Common'],
            ['distrik_id' => 5, 'id' => 6, 'name' => 'Kantor UJL2'],
            ['distrik_id' => 5, 'id' => 7, 'name' => 'Amurang'],
            ['distrik_id' => 5, 'id' => 8, 'name' => 'Ropa'],
            ['distrik_id' => 5, 'id' => 9, 'name' => 'Bolok'],
            ['distrik_id' => 5, 'id' => 10, 'name' => 'Kendari Unit 1,2'],
            ['distrik_id' => 5, 'id' => 11, 'name' => 'Kendari Unit 3'],
            ['distrik_id' => 5, 'id' => 12, 'name' => 'Gorontalo'],
            ['distrik_id' => 5, 'id' => 13, 'name' => 'Bonto'],
            ['distrik_id' => 5, 'id' => 14, 'name' => 'Berau'],
            ['distrik_id' => 5, 'id' => 15, 'name' => 'Suppa'],
            ['distrik_id' => 5, 'id' => 16, 'name' => 'Ketapang'],
            ['distrik_id' => 5, 'id' => 17, 'name' => 'Taliwang'],
            ['distrik_id' => 6, 'id' => 18, 'name' => 'Kantor UJLJ'],
            ['distrik_id' => 6, 'id' => 19, 'name' => 'Duri'],
            ['distrik_id' => 6, 'id' => 20, 'name' => 'Bangka'],
            ['distrik_id' => 6, 'id' => 21, 'name' => 'Belitung'],
            ['distrik_id' => 6, 'id' => 22, 'name' => 'Tidore'],
            ['distrik_id' => 6, 'id' => 23, 'name' => 'Tanjung Balai Karimun'],
            ['distrik_id' => 6, 'id' => 24, 'name' => 'Tembilahan'],
            ['distrik_id' => 7, 'id' => 25, 'name' => 'Common'],
            ['distrik_id' => 8, 'id' => 26, 'name' => 'Common'],
            ['distrik_id' => 9, 'id' => 27, 'name' => 'Common'],
            ['distrik_id' => 10, 'id' => 28, 'name' => 'Common'],
            ['distrik_id' => 11, 'id' => 29, 'name' => 'Common'],
            ['distrik_id' => 12, 'id' => 30, 'name' => 'Common'],
            ['distrik_id' => 13, 'id' => 31, 'name' => 'UPMK Blok 2'],
            ['distrik_id' => 14, 'id' => 32, 'name' => 'UPMT Blok 5'],
            ['distrik_id' => 15, 'id' => 33, 'name' => 'Common'],
            ['distrik_id' => 16, 'id' => 34, 'name' => 'Common'],
            ['distrik_id' => 17, 'id' => 35, 'name' => 'Common'],
            ['distrik_id' => 18, 'id' => 36, 'name' => 'Common'],
            ['distrik_id' => 19, 'id' => 37, 'name' => 'Common'],
            ['distrik_id' => 20, 'id' => 38, 'name' => 'Common'],
            ['distrik_id' => 21, 'id' => 39, 'name' => 'Common'],
            ['distrik_id' => 22, 'id' => 40, 'name' => 'Common'],
            ['distrik_id' => 23, 'id' => 41, 'name' => 'Common'],
            ['distrik_id' => 24, 'id' => 42, 'name' => 'Common'],
            ['distrik_id' => 25, 'id' => 43, 'name' => 'Common'],
            ['distrik_id' => 26, 'id' => 44, 'name' => 'Common']
        ]; 

        $entitas = [
            ['lokasi_id'=> 1, 'id' => 1, 'name' => 'PLTA Wonorejo'],
            ['lokasi_id'=> 2, 'id' => 2, 'name' => 'PLTA Ampelgading'],
            ['lokasi_id'=> 3, 'id' => 3, 'name' => 'PLTU 1,2'],
            ['lokasi_id'=> 4, 'id' => 4, 'name' => 'Unit Common'],
            ['lokasi_id'=> 4, 'id' => 5, 'name' => 'PLTU 1,2,3'],
            ['lokasi_id'=> 5, 'id' => 6, 'name' => 'Unit Common'],
            ['lokasi_id'=> 5, 'id' => 7, 'name' => 'PLTU 1,2'],
            ['lokasi_id'=> 6, 'id' => 8, 'name' => 'Unit Common'],
            ['lokasi_id'=> 7, 'id' => 9, 'name' => 'Unit Common'],
            ['lokasi_id'=> 8, 'id' => 10, 'name' => 'Unit Common'],
            ['lokasi_id'=> 9, 'id' => 11, 'name' => 'Unit Common'],
            ['lokasi_id'=> 10, 'id' => 12, 'name' => 'Unit Common'],
            ['lokasi_id'=> 11, 'id' => 13, 'name' => 'Unit Common'],
            ['lokasi_id'=> 12, 'id' => 14, 'name' => 'Unit Common'],
            ['lokasi_id'=> 13, 'id' => 15, 'name' => 'Unit Common'],
            ['lokasi_id'=> 14, 'id' => 16, 'name' => 'Unit Common'],
            ['lokasi_id'=> 15, 'id' => 17, 'name' => 'Unit Common'],
            ['lokasi_id'=> 16, 'id' => 18, 'name' => 'Unit Common'],
            ['lokasi_id'=> 17, 'id' => 19, 'name' => 'Unit Common'],
            ['lokasi_id'=> 18, 'id' => 20, 'name' => 'Unit Common'],
            ['lokasi_id'=> 19, 'id' => 21, 'name' => 'Unit Common'],
            ['lokasi_id'=> 20, 'id' => 22, 'name' => 'Unit Common'],
            ['lokasi_id'=> 21, 'id' => 23, 'name' => 'Unit Common'],
            ['lokasi_id'=> 22, 'id' => 24, 'name' => 'Unit Common'],
            ['lokasi_id'=> 23, 'id' => 25, 'name' => 'Unit Common'],
            ['lokasi_id'=> 24, 'id' => 26, 'name' => 'Unit Common'],
            ['lokasi_id'=> 25, 'id' => 27, 'name' => 'Unit Common'],
            ['lokasi_id'=> 25, 'id' => 28, 'name' => 'PLTU 1,2,3'],
            ['lokasi_id'=> 26, 'id' => 29, 'name' => 'Unit Common'],
            ['lokasi_id'=> 26, 'id' => 30, 'name' => 'PLTU 1,2'],
            ['lokasi_id'=> 27, 'id' => 31, 'name' => 'Unit Common'],
            ['lokasi_id'=> 27, 'id' => 32, 'name' => 'PLTU 9'],
            ['lokasi_id'=> 28, 'id' => 33, 'name' => 'Unit Common'],
            ['lokasi_id'=> 28, 'id' => 34, 'name' => 'PLTU 1,2'],
            ['lokasi_id'=> 29, 'id' => 35, 'name' => 'Unit Common'],
            ['lokasi_id'=> 29, 'id' => 36, 'name' => 'PLTU 1,2'],
            ['lokasi_id'=> 30, 'id' => 37, 'name' => 'Unit Common'],
            ['lokasi_id'=> 30, 'id' => 38, 'name' => 'PLTU 1,2'],
            ['lokasi_id'=> 31, 'id' => 39, 'name' => 'PLTGU Blok 2'],
            ['lokasi_id'=> 32, 'id' => 40, 'name' => 'PLTGU Blok 5'],
            ['lokasi_id'=> 33, 'id' => 41, 'name' => 'Unit Common'],
            ['lokasi_id'=> 34, 'id' => 42, 'name' => 'Unit Common'],
            ['lokasi_id'=> 34, 'id' => 43, 'name' => 'PLTA Sutami'],
            ['lokasi_id'=> 34, 'id' => 44, 'name' => 'PLTA EP'],
            ['lokasi_id'=> 34, 'id' => 45, 'name' => 'PLTA NON EP'],
            ['lokasi_id'=> 35, 'id' => 46, 'name' => 'Unit Common'],
            ['lokasi_id'=> 35, 'id' => 47, 'name' => 'PLTA Cirata'],
            ['lokasi_id'=> 36, 'id' => 48, 'name' => 'Unit Common'],
            ['lokasi_id'=> 36, 'id' => 49, 'name' => 'PLTU'],
            ['lokasi_id'=> 36, 'id' => 50, 'name' => 'PLTU 1,2'],
            ['lokasi_id'=> 36, 'id' => 51, 'name' => 'PLTU 3,4'],
            ['lokasi_id'=> 36, 'id' => 52, 'name' => 'PLTG'],
            ['lokasi_id'=> 36, 'id' => 53, 'name' => 'PLTG 1,2,3'],
            ['lokasi_id'=> 36, 'id' => 54, 'name' => 'PLTG Gilitimur'],
            ['lokasi_id'=> 36, 'id' => 55, 'name' => 'PLTGU'],
            ['lokasi_id'=> 36, 'id' => 56, 'name' => 'PLTGU Blok 1'],
            ['lokasi_id'=> 36, 'id' => 57, 'name' => 'PLTGU Blok 2'],
            ['lokasi_id'=> 36, 'id' => 58, 'name' => 'PLTGU Blok 3'],
            ['lokasi_id'=> 36, 'id' => 59, 'name' => 'PLTMG Bawean'],
            ['lokasi_id'=> 37, 'id' => 60, 'name' => 'Unit Common'],
            ['lokasi_id'=> 38, 'id' => 61, 'name' => 'Unit Common'],
            ['lokasi_id'=> 39, 'id' => 62, 'name' => 'Unit Common'],
            ['lokasi_id'=> 40, 'id' => 63, 'name' => 'Unit Common'],
            ['lokasi_id'=> 40, 'id' => 64, 'name' => 'PLTU'],
            ['lokasi_id'=> 40, 'id' => 65, 'name' => 'PLTU 1,2,3'],
            ['lokasi_id'=> 40, 'id' => 66, 'name' => 'PLTU 4,5'],
            ['lokasi_id'=> 40, 'id' => 67, 'name' => 'PLTGU'],
            ['lokasi_id'=> 40, 'id' => 68, 'name' => 'PLTGU Blok 1'],
            ['lokasi_id'=> 41, 'id' => 69, 'name' => 'Unit Common'],
            ['lokasi_id'=> 41, 'id' => 70, 'name' => 'PLTGU Blok 1'],
            ['lokasi_id'=> 41, 'id' => 71, 'name' => 'PLTG Blok 2'],
            ['lokasi_id'=> 41, 'id' => 72, 'name' => 'PLTG Blok 3'],
            ['lokasi_id'=> 41, 'id' => 73, 'name' => 'PLTG Blok 4'],
            ['lokasi_id'=> 42, 'id' => 74, 'name' => 'Unit Common'],
            ['lokasi_id'=> 43, 'id' => 75, 'name' => 'Unit Common'],
            ['lokasi_id'=> 43, 'id' => 76, 'name' => 'PLTU 1,2'],
            ['lokasi_id'=> 44, 'id' => 77, 'name' => 'Unit Common']
        ];

        $unit = [
            ['entitas_id' => 1, 'id' => 1, 'name' => 'PLTA Wonorejo'],
            ['entitas_id' => 2, 'id' => 2, 'name' => 'Common PLTA Ampelgading'],
            ['entitas_id' => 2, 'id' => 3, 'name' => 'PLTA Ampelgading 1'],
            ['entitas_id' => 2, 'id' => 4, 'name' => 'PLTA Ampelgading 2'],
            ['entitas_id' => 3, 'id' => 5, 'name' => 'Unit Common'],
            ['entitas_id' => 4, 'id' => 6, 'name' => 'Unit Common'],
            ['entitas_id' => 5, 'id' => 7, 'name' => 'PLTU 1'],
            ['entitas_id' => 5, 'id' => 8, 'name' => 'PLTU 2'],
            ['entitas_id' => 5, 'id' => 9, 'name' => 'PLTU 3'],
            ['entitas_id' => 6, 'id' => 10, 'name' => 'Unit Common'],
            ['entitas_id' => 7, 'id' => 11, 'name' => 'PLTU 1'],
            ['entitas_id' => 7, 'id' => 12, 'name' => 'PLTU 2'],
            ['entitas_id' => 8, 'id' => 13, 'name' => 'Unit Common'],
            ['entitas_id' => 9, 'id' => 14, 'name' => 'Unit Common'],
            ['entitas_id' => 10, 'id' => 15, 'name' => 'Unit Common'],
            ['entitas_id' => 11, 'id' => 16, 'name' => 'Unit Common'],
            ['entitas_id' => 12, 'id' => 17, 'name' => 'Unit Common'],
            ['entitas_id' => 13, 'id' => 18, 'name' => 'Unit Common'],
            ['entitas_id' => 14, 'id' => 19, 'name' => 'Unit Common'],
            ['entitas_id' => 15, 'id' => 20, 'name' => 'Unit Common'],
            ['entitas_id' => 16, 'id' => 21, 'name' => 'Unit Common'],
            ['entitas_id' => 17, 'id' => 22, 'name' => 'Unit Common'],
            ['entitas_id' => 18, 'id' => 23, 'name' => 'Unit Common'],
            ['entitas_id' => 19, 'id' => 24, 'name' => 'Unit Common'],
            ['entitas_id' => 20, 'id' => 25, 'name' => 'Unit Common'],
            ['entitas_id' => 21, 'id' => 26, 'name' => 'Unit Common'],
            ['entitas_id' => 22, 'id' => 27, 'name' => 'Unit Common'],
            ['entitas_id' => 23, 'id' => 28, 'name' => 'Unit Common'],
            ['entitas_id' => 24, 'id' => 29, 'name' => 'Unit Common'],
            ['entitas_id' => 25, 'id' => 30, 'name' => 'Unit Common'],
            ['entitas_id' => 26, 'id' => 31, 'name' => 'Unit Common'],
            ['entitas_id' => 27, 'id' => 32, 'name' => 'Unit Common'],
            ['entitas_id' => 28, 'id' => 33, 'name' => 'PLTU 1'],
            ['entitas_id' => 28, 'id' => 34, 'name' => 'PLTU 2'],
            ['entitas_id' => 28, 'id' => 35, 'name' => 'PLTU 3'],
            ['entitas_id' => 29, 'id' => 36, 'name' => 'Unit Common'],
            ['entitas_id' => 30, 'id' => 37, 'name' => 'PLTU 1'],
            ['entitas_id' => 30, 'id' => 38, 'name' => 'PLTU 2'],
            ['entitas_id' => 31, 'id' => 39, 'name' => 'Unit Common'],
            ['entitas_id' => 32, 'id' => 40, 'name' => 'PLTU 9'],
            ['entitas_id' => 33, 'id' => 41, 'name' => 'Unit Common'],
            ['entitas_id' => 34, 'id' => 42, 'name' => 'PLTU 1'],
            ['entitas_id' => 34, 'id' => 43, 'name' => 'PLTU 2'],
            ['entitas_id' => 35, 'id' => 44, 'name' => 'Unit Common'],
            ['entitas_id' => 36, 'id' => 45, 'name' => 'PLTU 1'],
            ['entitas_id' => 36, 'id' => 46, 'name' => 'PLTU 2'],
            ['entitas_id' => 37, 'id' => 47, 'name' => 'Unit Common'],
            ['entitas_id' => 38, 'id' => 48, 'name' => 'PLTU 1'],
            ['entitas_id' => 38, 'id' => 49, 'name' => 'PLTU 2'],
            ['entitas_id' => 39, 'id' => 50, 'name' => 'Common PLTGU Blok 2'],
            ['entitas_id' => 39, 'id' => 51, 'name' => 'PLTGU GT 21'],
            ['entitas_id' => 39, 'id' => 52, 'name' => 'PLTGU GT 22'],
            ['entitas_id' => 39, 'id' => 53, 'name' => 'PLTGU ST 21'],
            ['entitas_id' => 39, 'id' => 54, 'name' => 'PLTGU ST 22'],
            ['entitas_id' => 39, 'id' => 55, 'name' => 'PLTGU ST 23'],
            ['entitas_id' => 40, 'id' => 56, 'name' => 'Common PLTGU Blok 5'],
            ['entitas_id' => 40, 'id' => 57, 'name' => 'PLTGU GT 51'],
            ['entitas_id' => 40, 'id' => 58, 'name' => 'PLTGU GT 58'],
            ['entitas_id' => 41, 'id' => 59, 'name' => 'Unit Common'],
            ['entitas_id' => 42, 'id' => 60, 'name' => 'Unit Common'],
            ['entitas_id' => 43, 'id' => 61, 'name' => 'Common PLTA Sutami'],
            ['entitas_id' => 43, 'id' => 62, 'name' => 'PLTA Sutami 1'],
            ['entitas_id' => 43, 'id' => 63, 'name' => 'PLTA Sutami 2'],
            ['entitas_id' => 43, 'id' => 64, 'name' => 'PLTA Sutami 3'],
            ['entitas_id' => 44, 'id' => 65, 'name' => 'Common PLTA Wlingi'],
            ['entitas_id' => 44, 'id' => 66, 'name' => 'PLTA Wlingi 1'],
            ['entitas_id' => 44, 'id' => 67, 'name' => 'PLTA Wlingi 2'],
            ['entitas_id' => 44, 'id' => 68, 'name' => 'PLTA Lodoyo'],
            ['entitas_id' => 44, 'id' => 69, 'name' => 'Common PLTA Sengguruh'],
            ['entitas_id' => 44, 'id' => 70, 'name' => 'PLTA Sengguruh 1'],
            ['entitas_id' => 44, 'id' => 71, 'name' => 'PLTA Sengguruh 2'],
            ['entitas_id' => 44, 'id' => 72, 'name' => 'PLTA Selorejo'],
            ['entitas_id' => 44, 'id' => 73, 'name' => 'Common PLTA Tulungagung'],
            ['entitas_id' => 44, 'id' => 74, 'name' => 'PLTA Tulungagung 1'],
            ['entitas_id' => 44, 'id' => 75, 'name' => 'PLTA Tulungagung 2'],
            ['entitas_id' => 44, 'id' => 76, 'name' => 'Common PLTA Mandalan'],
            ['entitas_id' => 44, 'id' => 77, 'name' => 'PLTA Mandalan 1'],
            ['entitas_id' => 44, 'id' => 78, 'name' => 'PLTA Mandalan 2'],
            ['entitas_id' => 44, 'id' => 79, 'name' => 'PLTA Mandalan 3'],
            ['entitas_id' => 44, 'id' => 80, 'name' => 'PLTA Mandalan 4'],
            ['entitas_id' => 44, 'id' => 81, 'name' => 'Common PLTA Siman'],
            ['entitas_id' => 44, 'id' => 82, 'name' => 'PLTA Siman 1'],
            ['entitas_id' => 44, 'id' => 83, 'name' => 'PLTA Siman 2'],
            ['entitas_id' => 44, 'id' => 84, 'name' => 'PLTA Siman 3'],
            ['entitas_id' => 45, 'id' => 85, 'name' => 'Common PLTA Giringan'],
            ['entitas_id' => 45, 'id' => 86, 'name' => 'PLTA Giringan 1'],
            ['entitas_id' => 45, 'id' => 87, 'name' => 'PLTA Giringan 2'],
            ['entitas_id' => 45, 'id' => 88, 'name' => 'PLTA Giringan 3'],
            ['entitas_id' => 45, 'id' => 89, 'name' => 'Common PLTA Golang'],
            ['entitas_id' => 45, 'id' => 90, 'name' => 'PLTA Golang 1'],
            ['entitas_id' => 45, 'id' => 91, 'name' => 'PLTA Golang 2'],
            ['entitas_id' => 45, 'id' => 92, 'name' => 'PLTA Golang 3'],
            ['entitas_id' => 45, 'id' => 93, 'name' => 'PLTA Ngebel'],
            ['entitas_id' => 46, 'id' => 94, 'name' => 'Unit Common'],
            ['entitas_id' => 47, 'id' => 95, 'name' => 'PLTA Cirata 1'],
            ['entitas_id' => 47, 'id' => 96, 'name' => 'PLTA Cirata 2'],
            ['entitas_id' => 47, 'id' => 97, 'name' => 'PLTA Cirata 3'],
            ['entitas_id' => 47, 'id' => 98, 'name' => 'PLTA Cirata 4'],
            ['entitas_id' => 47, 'id' => 99, 'name' => 'PLTA Cirata 5'],
            ['entitas_id' => 47, 'id' => 100, 'name' => 'PLTA Cirata 6'],
            ['entitas_id' => 47, 'id' => 101, 'name' => 'PLTA Cirata 7'],
            ['entitas_id' => 47, 'id' => 102, 'name' => 'PLTA Cirata 8'],
            ['entitas_id' => 48, 'id' => 103, 'name' => 'Unit Common'],
            ['entitas_id' => 49, 'id' => 104, 'name' => 'Common PLTU'],
            ['entitas_id' => 50, 'id' => 105, 'name' => 'Common PLTU 1,2'],
            ['entitas_id' => 50, 'id' => 106, 'name' => 'PLTU 1'],
            ['entitas_id' => 50, 'id' => 107, 'name' => 'PLTU 2'],
            ['entitas_id' => 51, 'id' => 108, 'name' => 'Common PLTU 3,4'],
            ['entitas_id' => 51, 'id' => 109, 'name' => 'PLTU 3'],
            ['entitas_id' => 51, 'id' => 110, 'name' => 'PLTU 4'],
            ['entitas_id' => 52, 'id' => 111, 'name' => 'Common PLTG'],
            ['entitas_id' => 53, 'id' => 112, 'name' => 'Common PLTG 1,2,3'],
            ['entitas_id' => 53, 'id' => 113, 'name' => 'PLTG 1'],
            ['entitas_id' => 53, 'id' => 114, 'name' => 'PLTG 2'],
            ['entitas_id' => 53, 'id' => 115, 'name' => 'PLTG 3'],
            ['entitas_id' => 54, 'id' => 116, 'name' => 'Common PLTG GILITIMUR'],
            ['entitas_id' => 54, 'id' => 117, 'name' => 'PLTG GILITIMUR 1'],
            ['entitas_id' => 54, 'id' => 118, 'name' => 'PLTG GILITIMUR 2'],
            ['entitas_id' => 55, 'id' => 119, 'name' => 'Common PLTGU'],
            ['entitas_id' => 56, 'id' => 120, 'name' => 'Common PLTGU Blok 1'],
            ['entitas_id' => 56, 'id' => 121, 'name' => 'PLTGU GT 11'],
            ['entitas_id' => 56, 'id' => 122, 'name' => 'PLTGU GT 12'],
            ['entitas_id' => 56, 'id' => 123, 'name' => 'PLTGU GT 13'],
            ['entitas_id' => 56, 'id' => 124, 'name' => 'PLTGU ST 10'],
            ['entitas_id' => 57, 'id' => 125, 'name' => 'Common PLTGU Blok 2'],
            ['entitas_id' => 57, 'id' => 126, 'name' => 'PLTGU GT 21'],
            ['entitas_id' => 57, 'id' => 127, 'name' => 'PLTGU GT 22'],
            ['entitas_id' => 57, 'id' => 128, 'name' => 'PLTGU GT 23'],
            ['entitas_id' => 57, 'id' => 129, 'name' => 'PLTGU ST 20'],
            ['entitas_id' => 58, 'id' => 130, 'name' => 'Common PLTGU Blok 3'],
            ['entitas_id' => 58, 'id' => 131, 'name' => 'PLTGU GT 31'],
            ['entitas_id' => 58, 'id' => 132, 'name' => 'PLTGU GT 32'],
            ['entitas_id' => 58, 'id' => 133, 'name' => 'PLTGU GT 33'],
            ['entitas_id' => 58, 'id' => 134, 'name' => 'PLTGU ST 30'],
            ['entitas_id' => 59, 'id' => 135, 'name' => 'PLTMG Bawean'],
            ['entitas_id' => 60, 'id' => 136, 'name' => 'Unit Common'],
            ['entitas_id' => 61, 'id' => 137, 'name' => 'Unit Common'],
            ['entitas_id' => 62, 'id' => 138, 'name' => 'Unit Common'],
            ['entitas_id' => 63, 'id' => 139, 'name' => 'Unit Common'],
            ['entitas_id' => 64, 'id' => 140, 'name' => 'Common PLTU'],
            ['entitas_id' => 65, 'id' => 141, 'name' => 'Common PLTU 1,2,3'],
            ['entitas_id' => 65, 'id' => 142, 'name' => 'PLTU 1'],
            ['entitas_id' => 65, 'id' => 143, 'name' => 'PLTU 2'],
            ['entitas_id' => 65, 'id' => 144, 'name' => 'PLTU 3'],
            ['entitas_id' => 66, 'id' => 145, 'name' => 'Common PLTU 4,5'],
            ['entitas_id' => 66, 'id' => 146, 'name' => 'PLTU 4'],
            ['entitas_id' => 66, 'id' => 147, 'name' => 'PLTU 5'],
            ['entitas_id' => 67, 'id' => 148, 'name' => 'Common PLTGU'],
            ['entitas_id' => 68, 'id' => 149, 'name' => 'Common PLTGU Blok 1'],
            ['entitas_id' => 68, 'id' => 150, 'name' => 'PLTGU GT 11'],
            ['entitas_id' => 68, 'id' => 151, 'name' => 'PLTGU GT 12'],
            ['entitas_id' => 68, 'id' => 152, 'name' => 'PLTGU GT 13'],
            ['entitas_id' => 68, 'id' => 153, 'name' => 'PLTGU ST 10'],
            ['entitas_id' => 69, 'id' => 154, 'name' => 'Unit Common'],
            ['entitas_id' => 69, 'id' => 155, 'name' => 'Common PLTGU'],
            ['entitas_id' => 70, 'id' => 156, 'name' => 'Common PLTGU Blok 1'],
            ['entitas_id' => 70, 'id' => 157, 'name' => 'PLTGU GT 11'],
            ['entitas_id' => 70, 'id' => 158, 'name' => 'PLTGU GT 12'],
            ['entitas_id' => 70, 'id' => 159, 'name' => 'PLTGU GT 13'],
            ['entitas_id' => 70, 'id' => 160, 'name' => 'PLTGU ST 1'],
            ['entitas_id' => 71, 'id' => 161, 'name' => 'Common PLTG Blok 2'],
            ['entitas_id' => 71, 'id' => 162, 'name' => 'PLTG GT 21'],
            ['entitas_id' => 71, 'id' => 163, 'name' => 'PLTG GT 22'],
            ['entitas_id' => 72, 'id' => 164, 'name' => 'Common PLTG Blok 3'],
            ['entitas_id' => 72, 'id' => 165, 'name' => 'PLTG GT 31'],
            ['entitas_id' => 72, 'id' => 166, 'name' => 'PLTG GT 32'],
            ['entitas_id' => 72, 'id' => 167, 'name' => 'PLTG GT 33'],
            ['entitas_id' => 73, 'id' => 168, 'name' => 'Common PLTG Blok 4'],
            ['entitas_id' => 73, 'id' => 169, 'name' => 'PLTG GT 41'],
            ['entitas_id' => 73, 'id' => 170, 'name' => 'PLTG GT 42'],
            ['entitas_id' => 73, 'id' => 171, 'name' => 'PLTG GT 43'],
            ['entitas_id' => 74, 'id' => 172, 'name' => 'Unit Common'],
            ['entitas_id' => 75, 'id' => 173, 'name' => 'Unit Common'],
            ['entitas_id' => 76, 'id' => 174, 'name' => 'PLTU 1'],
            ['entitas_id' => 76, 'id' => 175, 'name' => 'PLTU 2'],
            ['entitas_id' => 77, 'id' => 176, 'name' => 'Unit Common']
        ];

        DB::table('strategi_bisnis')->insert($strategi);
        DB::table('distrik')->insert($distrik);
        DB::table('lokasi')->insert($lokasi);
        DB::table('entitas')->insert($entitas);
        DB::table('unit')->insert($unit);
    }
}
