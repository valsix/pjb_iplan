<?php

use Illuminate\Database\Seeder;

class FaseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('fases')->insert([
            [
                'name' => 'Usulan Unit'
            ],
            [
                'name' => 'Pembahasan Teknis'
            ],
            [
                'name' => 'Ketetapan'
            ],
        ]);
    }
}
