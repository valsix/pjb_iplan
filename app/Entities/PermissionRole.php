<?php

namespace App\Entities;

class PermissionRole extends Model
{
    protected $primaryKey = null;
    protected $table = 'permission_role';
    protected $fillable = ['permission_id', 'role_id'];
	public $timestamps = false;

    public function data()
    {
        return $this->belongsTo('App\Entities\Permission', 'permission_id');
    }

    public function role()
    {
        return $this->belongsTo('App\Entities\Role', 'role_id');
    }
}
