<?php namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Strategi_bisnis extends Model
{
    protected $table = "strategi_bisnis";

    protected $fillable = ['name'];

    public function scopeSearchStrategiBisnis($query, $name_sb)
	{
		if ($name_sb) $query->where('name', $name_sb);
	}

     public function distrik()
    {
    	return 
    	$this->hasMany('App\Entities\Distrik');
    }
}
