<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
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
                'name' => 'Feri',
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => md5('admin'),
                'distrik_id' => '21',
            ],
            [
                'name' => 'Staff Unit',
                'username' => 'staff_unit',
                'email' => 'staff_unit@gmail.com',
                'password' => md5('123456'),
                'distrik_id' => '18',
            ],
            [
                'name' => 'Manager Unit',
                'username' => 'manager_unit',
                'email' => 'manager_unit@gmail.com',
                'password' => md5('123456'),
                'distrik_id' => '18',
            ],
            [
                'name' => 'GM Unit',
                'username' => 'gm_unit',
                'email' => 'gm_unit@gmail.com',
                'password' => md5('123456'),
                'distrik_id' => '18',
            ],
            [
                'name' => 'Hisyam',
                'username' => 'staff_anggaran',
                'email' => 'staff_anggaran@gmail.com',
                'password' => md5('123456'),
                'distrik_id' => '21',
            ],
            [
                'name' => 'Hikma',
                'username' => 'manager_anggaran',
                'email' => 'manager_anggaran@gmail.com',
                'password' => md5('123456'),
                'distrik_id' => '21',
            ],
            [
                'name' => 'Bambang',
                'username' => 'kadiv_anggaran',
                'email' => 'kadiv_anggaran@gmail.com',
                'password' => md5('123456'),
                'distrik_id' => '21',
            ],
            [
                'name' => 'Tim RKAP','username' => 'tim_rkap',
                'email' => 'tim_rkap@gmail.com',
                'password' => md5('123456'),
                'distrik_id' => '21',
            ],
            [
                'name' => 'STek',
                'username' => 'stek',
                'email' => 'stek@gmail.com',
                'password' => md5('123456'),
                'distrik_id' => '21',
            ],
            [
                'name' => 'Kabid Teknologi',
                'username' => 'kabid_teknologi',
                'email' => 'kabid_teknologi@gmail.com',
                'password' => md5('123456'),
                'distrik_id' => '21',
            ],
            [
                'name' => 'Setyo Irnanto',
                'username' => '7194040JA',
                'email' => '7194040JA@gmail.com',
                'password' => md5('123456'),
                'distrik_id' => '9',
            ],
            [
                'name' => 'Muhamad Badrul, Munir Satria N',
                'username' => '8208086JA',
                'email' => '8208086JA@gmail.com',
                'password' => md5('123456'),
                'distrik_id' => '25',
            ],
            [
                'name' => 'Citra Mashita',
                'username' => '8511082JA',
                'email' => '8511082JA@gmail.com',
                'password' => md5('123456'),
                'distrik_id' => '9',
            ],
            [
                'name' => 'Henny Tri Lestari',
                'username' => '7906025JA',
                'email' => '7906025JA@gmail.com',
                'password' => md5('123456'),
                'distrik_id' => '25',
            ]
        ];

        DB::table('users')->insert($data);
    }
}
