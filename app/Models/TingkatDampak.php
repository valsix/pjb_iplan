<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TingkatDampak extends Model
{
    //
    protected $table = "tingkat_dampak";

    protected $fillable = ['nama_tingkat_dampak', 'no_tingkat_dampak'];
}
