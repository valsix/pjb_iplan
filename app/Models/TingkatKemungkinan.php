<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TingkatKemungkinan extends Model
{
    //
    protected $table = "tingkat_kemungkinan";

    protected $fillable = ['nama_tingkat_kemungkinan', 'no_tingkat_kemungkinan'];
    // protected $fillable = ['tingkat_kemungkinan_id', 'tingkat_dampak_id', 'nama_level_resiko', 'warna_level_resiko'];
}
