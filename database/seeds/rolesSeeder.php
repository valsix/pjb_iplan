<?php

use Illuminate\Database\Seeder;

class rolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $data = [
            [
                'name' => 'administrator',
                'display_name' => 'administrator',
                'description' => 'administrator',
                'is_kantor_pusat' => 1,
            ],
            [
                'name' => 'Staff Unit',
                'display_name' => 'Staff Unit',
                'description' => 'Staff Unit',
                'is_kantor_pusat' => 0,
            ],
            [
                'name' => 'Manager Unit',
                'display_name' => 'Manager Unit',
                'description' => 'Manager Unit',
                'is_kantor_pusat' => 0,                
            ],
            [
                'name' => 'GM',
                'display_name' => 'GM',
                'description' => 'GM',
                'is_kantor_pusat' => 0,
            ],
            [
                'name' => 'Staff Anggaran',
                'display_name' => 'Staff Anggaran',
                'description' => 'Staff Anggaran',
                'is_kantor_pusat' => 1,
            ],
            [
                'name' => 'Manager Anggaran',
                'display_name' => 'Manager Anggaran',
                'description' => 'Manager Anggaran',
                'is_kantor_pusat' => 1,
            ],
            [
                'name' => 'Kadiv​ Anggaran',
                'display_name' => 'Kadiv​ Anggaran',
                'description' => 'Kadiv​ Anggaran',
                'is_kantor_pusat' => 1,
            ],
            [
                'name' => 'Tim​ ​RKAP',
                'display_name' => 'Tim​ ​RKAP',
                'description' => 'Tim​ ​RKAP',
                'is_kantor_pusat' => 1,
            ],
            [
                'name' => 'STek',
                'display_name' => 'STek',
                'description' => 'STek',
                'is_kantor_pusat' => 1,
            ],
            [
                'name' => 'Kabid Teknologi',
                'display_name' => 'Kabid Teknologi',
                'description' => 'Kabid Teknologi',
                'is_kantor_pusat' => 1,
            ]
        ];
        DB::table('roles')->insert($data);
    }
}
