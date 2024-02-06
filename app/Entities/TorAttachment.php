<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class TorAttachment extends Model
{
    protected $table = "tor_attachment";

    protected $fillable = ['tor_id','filepath', 'for_review'];


    public function tor_review_status()
    {
    	return
		$this->belongsTo('App\Entities\Tor','tor_id','id');
    }

}
