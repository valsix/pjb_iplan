<?php

use Illuminate\Database\Seeder;

class JenisTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('jenis')->insert([
            [
                'id' => 1,
                'name' => 'RKAU'
            ],
            [
                'id' => 2,
                'name' => 'Form 6 - Reimburse'
            ],
            [
                'id' => 3,
                'name' => 'Form 6 - Rutin'
            ],
            [
                'id' => 4,
                'name' => 'Form 10 - Pengembangan Usaha'
            ],
            [
                'id' => 5,
                'name' => 'Form 10 - Penguatan KIT'
            ],
            [
                'id' => 6,
                'name' => 'Form 10 - PLN'
            ],
            [
                'id' => 7,
                'name' => 'Form Bahan Bakar'
            ],
            [
                'id' => 8,
                'name' => 'Risk Profile'
            ],
            [
                'id' => 9,
                'name' => 'Penyusutan'
            ],
        ]);
    }
}
