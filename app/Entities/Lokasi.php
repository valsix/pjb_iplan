<?php namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    protected $table = "lokasi";

    protected $fillable = ['name', 'distrik_id', 'min_uploaded_form'];

    public function distrik()
    {
        return
        $this->belongsTo('App\Entities\Distrik','distrik_id','id');
    }

    public function lokasijenis()
    {
        return 
        $this->hasMany('App\Entities\LokasiJenis')->orderBy('jenis_id');
    }

    public function fileapproval()
    {
        return 
        $this->hasMany('App\Entities\FileApproval');
    }
}
