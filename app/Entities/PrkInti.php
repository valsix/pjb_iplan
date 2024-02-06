<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class PrkInti extends Model
{
    protected $table = 'prk_inti';
    protected $fillable = ['desc_prk_inti', 'identity_prk_inti', 'prk_parent_id'];

    public function prk_parent()
    {
    	return 
    	$this->belongsTo('App\Entities\PrkParent','prk_parent_id','id');
    }
}
