<?php

use Illuminate\Database\Seeder;

class jenis_bahan_bakar extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('jenis_bahan_bakar')->insert([
            ['name' => 'MFO'],
            ['name' => 'HSD'],
            ['name' => 'IDO'],
            ['name' => 'BIO'],
            ['name' => 'OLEIN'],
            ['name' => 'BATU BARA'],
            ['name' => 'GAS'],
            ['name' => 'PANAS BUMI'],
            ['name' => 'SURYA'],
            ['name' => 'BIOMASS'],
            ['name' => 'AIR'],
            ['name' => 'GBB'],
            ['name' => 'LAIN-LAIN'],
        ]);
    }
}
