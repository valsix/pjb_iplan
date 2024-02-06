<?php
namespace App\Entities;

// use Zizaco\Entrust\EntrustRole;
use Illuminate\Database\Eloquent\Model;

class GroupDivisiPembinaUnit extends Model
{
    protected $table = 'grup_divpembinaunit';
    protected $fillable = ['name', 'display_name', 'description'];

    // public function role_user()
    // {
    //     return $this->hasMany('App\Entities\UserRole');
    // }

    // public function role_permission()
    // {
    //     return $this->hasMany('App\Entities\PermissionRole', 'role_id');
    // }

    // public function permission()
    // {
    //     return $this->belongsToMany(Permission::class, 'permission_role');
    // }

    /*public function user()
{
return $this->belongsToMany('App\Entities\User');
}*/

}
