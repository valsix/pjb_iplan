<?php

namespace App\Entities;

class RoleKkpUser extends Model
{
    protected $table = 'role_kkp_user';
    protected $fillable = ['user_id', 'role_kkp_id'];
	public $timestamps = false;

    public function data()
    {
        return $this->belongsTo('App\Entities\RoleKkp', 'role_kkp_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Entities\User', 'user_id');
    }
}
