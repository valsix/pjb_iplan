<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(FileApprovalStatusSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(FaseTableSeeder::class);
        $this->call(JenisTableSeeder::class);
        $this->call(TingkatKemungkinanTableSeeder::class);
        $this->call(TingkatDampakTableSeeder::class);
        $this->call(LevelResikoTableSeeder::class);
        $this->call(LokasiSeeder::class);
        $this->call(jenis_bahan_bakar::class);
        $this->call(AlokasiSeeder::class);
        $this->call(ApprovalDmrSeeder::class);
        $this->call(rolesSeeder::class);
        $this->call(RoleUserSeeder::class);
        $this->call(ApprovalTableSeeder::class);
        $this->call(StatusUploadSeeder::class);
        $this->call(PRKSeeder::class);
    }
}
