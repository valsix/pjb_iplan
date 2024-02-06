<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class PgdlMasterStatusAiPjb extends Model
{
    protected $table = "pgdl_master_status_ai_pjb";
    public function status_kontrak()
  	{
      	return $this->hasMany('App\Entities\PgdlStatusAiPjb', 'id', 'status_kontrak_id');
  	}
    public function status_disburse()
  	{
      	return $this->hasMany('App\Entities\PgdlStatusAiPjb', 'id', 'status_disburse_id');
  	}  	
}
