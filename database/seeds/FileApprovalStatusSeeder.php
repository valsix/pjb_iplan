<?php

use Illuminate\Database\Seeder;

class FileApprovalStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('file_approval_status')->insert([
            ['id'=>1,
             'name'=>'Drafted by'],
            ['id'=>2,
             'name'=>'Returned by'],
            ['id'=>3,
             'name'=>'Submitted by'],
            ['id'=>4,
             'name'=>'Approved by'],
            ['id'=>5,
             'name'=>'Queue'],
         ]);
    }
}
