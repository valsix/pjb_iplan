<?php

namespace App\Entities;

class GroupDivisiPembinaUnitUsers extends Model
{
    protected $table = 'grup_divpembinaunit_users';
    protected $fillable = ['user_id', 'grupdiv_id'];
	public $timestamps = false;

    public function data()
    {
        return $this->belongsTo('App\Entities\GroupDivisiPembinaUnit', 'grupdiv_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Entities\User', 'user_id');
    }
}
