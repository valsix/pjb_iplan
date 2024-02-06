<?php

namespace App\Entities;

class UserRole extends Model
{
    protected $table = 'role_user';
    protected $fillable = ['user_id', 'role_id'];
	public $timestamps = false;

    public function data()
    {
        return $this->belongsTo('App\Entities\Role', 'role_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Entities\User', 'user_id');
    }
}
