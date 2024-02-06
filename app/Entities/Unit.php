<?php namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = "unit";

    protected $fillable = ['name', 'entitas_id'];

    public function entitas()
    {
    	return
		$this->belongsTo('App\Entities\Entitas','entitas_id','id');
    }
}
