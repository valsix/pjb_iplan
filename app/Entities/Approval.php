<?php namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    protected $table = "approval";

    protected $fillable = ['id', 'fase_id', 'role_id', 'urutan', 'enabled'];


    public function fase()
    {
    	return
    	$this->belongsTo('App\Entities\Fase','fase_id','id');
    }
    public function role()
    {
    	return
		$this->belongsTo('App\Entities\Role','role_id','id');
    }

    public function grup()
    {
    	return
    	$this->belongsTo('App\Entities\Grup','grup_id','id');
    }

}
