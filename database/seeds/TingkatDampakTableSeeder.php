<?php

use Illuminate\Database\Seeder;

class TingkatDampakTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('tingkat_dampak')->insert([
            [
                'nama_tingkat_dampak' => 'Tidak Signifikan',
                'no_tingkat_dampak' => '1'
            ],

            [
                'nama_tingkat_dampak' => 'Minor',
                'no_tingkat_dampak' => '2'
            ],

            [
                'nama_tingkat_dampak' => 'Medium',
                'no_tingkat_dampak' => '3'
            ],

            [
                'nama_tingkat_dampak' => 'Signifikan',
                'no_tingkat_dampak' => '4'
            ],

        	[
	            'nama_tingkat_dampak' => 'Malapetaka',
	            'no_tingkat_dampak' => '5'
            ],

        ]);
    }
}
