<?php
namespace App\Entities;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    protected $table = 'roles';
    protected $fillable = ['name', 'display_name', 'description'];

    public function role_user()
    {
        return $this->hasMany('App\Entities\UserRole');
    }

    public function role_permission()
    {
        return $this->hasMany('App\Entities\PermissionRole', 'role_id');
    }

    public function permission()
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }

    /*public function user()
{
return $this->belongsToMany('App\Entities\User');
}*/

}
