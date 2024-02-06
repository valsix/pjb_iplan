<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class ExcelDataInputBahanBakar extends Model
{
    protected $table = 'excel_data_input_bahan_bakar';
    protected $fillable = [
        'file_input_bahan_bakar_id',
        'distrik_id',
        'prk',
        'beban_or_cashflow_id',
        'month',
        'value'
    ];

    public function file_input_bahan_bakar()
    {
        return $this->belongsTo('App\Entities\FileInputBahanBakar', 'id', 'file_input_bahan_bakar');
    }

    public function distrik()
    {
        return $this->belongsTo('App\Entities\Distrik', 'distrik_id');
    }
}
