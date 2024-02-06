<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class PrkParent extends Model
{
    protected $table = 'prk_parent';
    protected $fillable = ['desc_prk_parent', 'identity_prk_parent', 'name_prk_parent'];

    public function prk_inti()
    {
    	return 
    	$this->hasMany('App\Entities\PrkInti');
    }
}
