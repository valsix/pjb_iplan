<?php

use Illuminate\Database\Seeder;

class ApprovalDmrSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('dmr_review_status')->insert([
            [
                'id' => 4,
                'name' => 'Queue'
            ],
            [
            	'id' => 1,
                'name' => 'Approved'
            ],
            [
            	'id' => 2,
                'name' => 'Revised'
            ],
            [
            	'id' => 3,
                'name' => 'Rejected'
            ]
        ]);
        DB::table('dmr_review_phase')->insert([
            [
                'id' => 1,
                'role_id' => 3,
                'urutan' => 1

            ],
            [
                'id' => 2,
                'role_id' => 4,
                'urutan' => 2
            ],
            [
                'id' => 3,
                'role_id' => 9,
                'urutan' => 3
            ],
            [
                'id' => 4,
                'role_id' => 10,
                'urutan' => 4
            ]
        ]);
    }
}



