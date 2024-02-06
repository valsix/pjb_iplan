<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class StrategiBisnis extends Model
{
    protected $table = "strategi_bisnis";

    protected $fillable = ['name'];

    public function scopeSearchStrategiBisnis($query, $nama_sb)
    {
        if ($nama_sb) $query->where('name', $nama_sb);
    }

    public function distrik()
    {
        return 
    	$this->hasMany('App\Entities\Distrik');
    }
}
