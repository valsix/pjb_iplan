<?php
namespace App\Entities;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    protected $table = 'permission';
    protected $fillable = ['name', 'display_name', 'description'];

    public function role_permission()
    {
        return $this->hasMany('App\Entities\PermissionRole');
    }

    public function getChild()
    {
        return $this->where('parent_id', $this->id)->where('enabled', 1)->get();
    }

    public function getMenus()
    {
        return $this->where('is_menu', 1);
    }

}
