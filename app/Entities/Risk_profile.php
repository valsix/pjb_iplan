<?php namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Risk_profile extends Model
{
    protected $table = "riskprofile";

    protected $fillable = ['lokasi_id', 'risk_tag', 'risk_event', 'risk_corporate', 'possibility_level', 'impact_level', 'risk_level'];

    public function lokasi()
    {
    	return 
    	$this->belongsTo('App\Entities\Lokasi','lokasi_id');
    }

}
