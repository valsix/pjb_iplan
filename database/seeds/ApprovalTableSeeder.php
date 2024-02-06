<?php

use Illuminate\Database\Seeder;

class ApprovalTableSeeder extends Seeder
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
                'fase_id' => '1',
                'role_id' => '2',
                'urutan' => '1',
                'enabled' => '1',
            ],
            [
                'fase_id' => '1',
                'role_id' => '3',
                'urutan' => '2',
                'enabled' => '1',
            ],
            [
                'fase_id' => '1',
                'role_id' => '4',
                'urutan' => '3',
                'enabled' => '1',
            ],
            [
                'fase_id' => '2',
                'role_id' => '5',
                'urutan' => '1',
                'enabled' => '1',
            ],
            [
                'fase_id' => '2',
                'role_id' => '6',
                'urutan' => '2',
                'enabled' => '1',
            ],
            [
                'fase_id' => '2',
                'role_id' => '7',
                'urutan' => '3',
                'enabled' => '1',
            ],
            [
                'fase_id' => '3',
                'role_id' => '5',
                'urutan' => '1',
                'enabled' => '1',
            ],
            [
                'fase_id' => '3',
                'role_id' => '6',
                'urutan' => '2',
                'enabled' => '1',
            ],
            [
                'fase_id' => '3',
                'role_id' => '7',
                'urutan' => '3',
                'enabled' => '1',
            ],
        ];
        DB::table('approval')->insert($data);
    }
}
