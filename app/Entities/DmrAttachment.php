<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class DmrAttachment extends Model
{
    protected $table = "dmr_attachment";

    protected $fillable = ['dmr_id','filepath'];


    public function dmr_review_status()
    {
    	return
		$this->belongsTo('App\Entities\Dmr','dmr_id','id');
    }

}
