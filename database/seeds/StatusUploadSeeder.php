<?php

use Illuminate\Database\Seeder;

class StatusUploadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('status_upload')->insert([
            [
                'deskripsi' => 'Belum diupload',
                'label' => 'label-warning',
            ],
            [
                'deskripsi' => 'Sedang Upload',
                'label' => 'label-info',
            ],
            [
                'deskripsi' => 'Berhasil Upload',
                'label' => 'label-success',
            ],
            [
                'deskripsi' => 'Upload Gagal',
                'label' => 'label-danger',
            ],
        ]);
    }
}
