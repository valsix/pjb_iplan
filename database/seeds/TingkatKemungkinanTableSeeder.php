<?php

use Illuminate\Database\Seeder;

class TingkatKemungkinanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('tingkat_kemungkinan')->insert([
        	[
	            'nama_tingkat_kemungkinan' => 'Sangat Besar',
	            'no_tingkat_kemungkinan' => 'E'
            ],

            [
                'nama_tingkat_kemungkinan' => 'Besar',
	            'no_tingkat_kemungkinan' => 'D'
            ],

            [
                'nama_tingkat_kemungkinan' => 'Sedang',
	            'no_tingkat_kemungkinan' => 'C'
            ],

            [
                'nama_tingkat_kemungkinan' => 'Kecil',
	            'no_tingkat_kemungkinan' => 'B'
            ],

            [
                'nama_tingkat_kemungkinan' => 'Sangat Kecil',
	            'no_tingkat_kemungkinan' => 'A'
            ],
        ]);
    }
}
