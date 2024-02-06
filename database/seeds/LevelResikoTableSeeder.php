<?php

use Illuminate\Database\Seeder;

class LevelResikoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('level_resiko')->insert([
        	//Sangat Besar
        	[
	            'tingkat_kemungkinan_id' => '1',
	            'tingkat_dampak_id' => '1',
	            'nama_level_resiko' => 'Moderat',
	            'warna_level_resiko' => '0000FF'
            ],

            [
                'tingkat_kemungkinan_id' => '1',
	            'tingkat_dampak_id' => '2',
	            'nama_level_resiko' => 'Moderat',
	            'warna_level_resiko' => '0000FF'
            ],

            [
                'tingkat_kemungkinan_id' => '1',
	            'tingkat_dampak_id' => '3',
	            'nama_level_resiko' => 'Tinggi',
	            'warna_level_resiko' => 'FFFF00'
            ],

            [
               'tingkat_kemungkinan_id' => '1',
	            'tingkat_dampak_id' => '4',
	            'nama_level_resiko' => 'Ekstrem',
	            'warna_level_resiko' => 'FF0000'
            ],

            [
                'tingkat_kemungkinan_id' => '1',
	            'tingkat_dampak_id' => '5',
	            'nama_level_resiko' => 'Ekstrem',
	            'warna_level_resiko' => 'FF0000'
            ],

            //Besar
            [
	            'tingkat_kemungkinan_id' => '2',
	            'tingkat_dampak_id' => '1',
	            'nama_level_resiko' => 'Rendah',
	            'warna_level_resiko' => '1bd51b'
            ],

            [
                'tingkat_kemungkinan_id' => '2',
	            'tingkat_dampak_id' => '2',
	            'nama_level_resiko' => 'Moderat',
	            'warna_level_resiko' => '0000FF'
            ],

            [
                'tingkat_kemungkinan_id' => '2',
	            'tingkat_dampak_id' => '3',
	            'nama_level_resiko' => 'Tinggi',
	            'warna_level_resiko' => 'FFFF00'
            ],

            [
               'tingkat_kemungkinan_id' => '2',
	            'tingkat_dampak_id' => '4',
	            'nama_level_resiko' => 'Ekstrem',
	            'warna_level_resiko' => 'FF0000'
            ],

            [
                'tingkat_kemungkinan_id' => '2',
	            'tingkat_dampak_id' => '5',
	            'nama_level_resiko' => 'Ekstrem',
	            'warna_level_resiko' => 'FF0000'
            ],

            //Sedang
            [
	            'tingkat_kemungkinan_id' => '3',
	            'tingkat_dampak_id' => '1',
	            'nama_level_resiko' => 'Rendah',
	            'warna_level_resiko' => '1bd51b'
            ],

            [
                'tingkat_kemungkinan_id' => '3',
	            'tingkat_dampak_id' => '2',
	            'nama_level_resiko' => 'Moderat',
	            'warna_level_resiko' => '0000FF'
            ],

            [
                'tingkat_kemungkinan_id' => '3',
	            'tingkat_dampak_id' => '3',
	            'nama_level_resiko' => 'Tinggi',
	            'warna_level_resiko' => 'FFFF00'
            ],

            [
               'tingkat_kemungkinan_id' => '3',
	            'tingkat_dampak_id' => '4',
	            'nama_level_resiko' => 'Tinggi',
	            'warna_level_resiko' => 'FFFF00'
            ],

            [
                'tingkat_kemungkinan_id' => '3',
	            'tingkat_dampak_id' => '5',
	            'nama_level_resiko' => 'Ekstrem',
	            'warna_level_resiko' => 'FF0000'
            ],

            //Kecil
            [
	            'tingkat_kemungkinan_id' => '4',
	            'tingkat_dampak_id' => '1',
	            'nama_level_resiko' => 'Rendah',
	            'warna_level_resiko' => '1bd51b'
            ],

            [
                'tingkat_kemungkinan_id' => '4',
	            'tingkat_dampak_id' => '2',
	            'nama_level_resiko' => 'Rendah',
	            'warna_level_resiko' => '1bd51b'
            ],

            [
                'tingkat_kemungkinan_id' => '4',
	            'tingkat_dampak_id' => '3',
	            'nama_level_resiko' => 'Moderat',
	            'warna_level_resiko' => '0000FF'
            ],

            [
               'tingkat_kemungkinan_id' => '4',
	            'tingkat_dampak_id' => '4',
	            'nama_level_resiko' => 'Tinggi',
	            'warna_level_resiko' => 'FFFF00'
            ],

            [
                'tingkat_kemungkinan_id' => '4',
	            'tingkat_dampak_id' => '5',
	            'nama_level_resiko' => 'Ekstrem',
	            'warna_level_resiko' => 'FF0000'
            ],

            //Sangat Kecil
            [
	            'tingkat_kemungkinan_id' => '5',
	            'tingkat_dampak_id' => '1',
	            'nama_level_resiko' => 'Rendah',
	            'warna_level_resiko' => '1bd51b'
            ],

            [
                'tingkat_kemungkinan_id' => '5',
	            'tingkat_dampak_id' => '2',
	            'nama_level_resiko' => 'Rendah',
	            'warna_level_resiko' => '1bd51b'
            ],

            [
                'tingkat_kemungkinan_id' => '5',
	            'tingkat_dampak_id' => '3',
	            'nama_level_resiko' => 'Moderat',
	            'warna_level_resiko' => '0000FF'
            ],

            [
               'tingkat_kemungkinan_id' => '5',
	            'tingkat_dampak_id' => '4',
	            'nama_level_resiko' => 'Tinggi',
	            'warna_level_resiko' => 'FFFF00'
            ],

            [
                'tingkat_kemungkinan_id' => '5',
	            'tingkat_dampak_id' => '5',
	            'nama_level_resiko' => 'Ekstrem',
	            'warna_level_resiko' => 'FF0000'
            ],
        ]);
    }
}
