<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LevelResiko extends Model
{
    //
    protected $table = "level_resiko";

    protected $fillable = ['tingkat_kemungkinan_id', 'tingkat_dampak_id', 'nama_level_resiko', 'warna_level_resiko'];
}
