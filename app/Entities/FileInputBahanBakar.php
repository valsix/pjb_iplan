<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class FileInputBahanBakar extends Model
{
    protected $table = 'file_input_bahan_bakar';
    protected $fillable = [
        'name',
        'tahun',
        'filepath',
        'version',
        'uploaded_by'
    ];

    public function excel_bahan_bakar()
    {
        return $this->hasMany('App\Entities\ExcelDataInputBahanBakar');
    }

    public function uploaded()
    {
        return $this->belongsTo('App\Entities\User', 'uploaded_by');
    }
}