<?php

namespace App\Entities;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, EntrustUserTrait;
    use SoftDeletes; // <-- Use This Instead Of SoftDeletingTrait

    protected $_permissions;
    protected $_menus;

    protected $_whitelistUrl = [];
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nama', 'email', 'username', 'password', 'current_id_roles','distrik_id','status_notif_email', 'current_grupdiv_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    // public function role() >> sementara diganti dengan group, karena nama function & kolom nya sama
    public function group()
    {
        return $this->hasOne('App\Entities\UserRole', 'user_id');
    }

    public function current_role()
    {
        return $this->belongsTo('App\Entities\Role', 'current_id_roles');
    }

    public function current_grupdiv()
    {
        return $this->belongsTo('App\Entities\GroupDivisiPembinaUnit', 'current_grupdiv_id');
    }

    // getPermissionsOfCurrentRole

    public function hasAccess($path = null)
    {
        if ($path) {
            // $effectivePermission = $this->current_role->permission()->where('route_permission', 'LIKE', '%' . $path . '%')->get();

            //karena di database path di tambah '/', jadinya codingan menyesuaikan karena datanya udah banyak
            if($path != '/') { //kecuali home, tidak perlu ditambah
                $path = '/'.$path;
            }

            $effectivePermission = $this->current_role->permission()->where('route_permission', 'LIKE', $path)->get();

            if ($effectivePermission->count() > 0) {
                return true;
            } else {
                return false;
            }

        } else {
            return back();
        }
    }

    // Data menu generator

    public function getMenus()
    {
        $menus = $this->current_role->permission()->where('is_menu', 1)->where('enabled', 1)->orderBy('sequence_number', 'asc')->get();
        $menu_data = [];        // data menu item
        $menu_children = [];   // data struktur menu
        $section_menu = [];     // data menu per section
        foreach ($menus as $key => $menu) {
            $id = $menu->id;
            $parent_id = $menu->parent_id;
            $section = $menu->section;
            if ($parent_id == null) {
                $parent_id = 0;
            }

            $menu_data[$id] = $menu;

            if (! isset($menu_children[$id])) {
                $menu_children[$id] = [];
            }
            if (! isset($menu_children[$parent_id])) {
                $menu_children[$parent_id] = [];
            }

            $menu_children[$parent_id][] = $menu;

            // jika section terdefinisi
            if ($section) {
                if (! isset($section_menu[$section])) {
                    $section_menu[$section] = [];
                }
                $section_menu[$section][] = $menu;
            }
        }
        return compact('menu_data', 'menu_children', 'section_menu');
    }

    public function person()
    {
        return $this->morphTo();
    }

    public function setFirstCurrentRole()
    {
        if (intval($this->current_id_roles) < 1) {
            $this->current_id_roles = $this->group->role_id;
            $this->save();
            return true;
        }
        return false;
    }

    public function setFirstCurrentGrupDiv()
    {
        if (intval($this->current_grupdiv_id) < 1) {
            // $this->current_grupdiv_id = $this->group->role_id;
            // $this->save();
            return true;
        }
        return false;
    }

    public function roles()
    {
        return $this->belongsToMany('App\Entities\Role');
    }

    public function distrik()
    {
        # code...
        return $this->belongsTo('App\Entities\Distrik');
    }

    public function bidang_divisi()
    {
        # code...
        return $this->belongsTo('App\Entities\BidangDivisi');
    }

}
