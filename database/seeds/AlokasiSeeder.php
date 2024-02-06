<?php

use Illuminate\Database\Seeder;

class AlokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alokasi')->insert([
            ['name' => 'UNIT'],
            ['name' => 'UHAR'],
            ['name' => 'UPHB'],
            ['name' => 'PJAC'],
            ['name' => 'PJB2'],
        ]);
    }
}
