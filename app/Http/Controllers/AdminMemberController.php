<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Entities\Permission;
use App\Entities\PermissionRole;
use App\Entities\Role;
use App\Entities\User;
use App\Entities\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Entities\Lokasi;
use App\Entities\Distrik;
use App\Entities\StrategiBisnis;
use App\Entities\Jabatan;
use App\Entities\BidangDivisi;
use App\Entities\GroupDivisiPembinaUnit;
use Auth;
use DB;

class AdminMemberController extends Controller
{
    public function switchRole($role_id)
    {
        $current_user = Auth::user();
        $current_user->current_id_roles = $role_id;
        $current_user->save();
        return redirect('/');
    }

    public function getList()
    {
        // $users = DB::table('users')
        //     ->join('jabatan', 'jabatan.position_id', '=', 'users.position_id')
        //     ->where('enabled','1')
        //     ->get();

        $users = User::leftJoin('jabatan', 'jabatan.position_id', '=', 'users.position_id')
            ->where('enabled','1')->get();

        $current_id_user = session('user_id');
        // $current_id_user = Auth::user()->id;
        // dd(Auth::user());
        return view('pages.admin.user', compact('users', 'current_id_user'));
    }

    public function getAddUser()
    {
        $user = null;
        $roles = Role::get();
        $item['strategi_bisnis'] = StrategiBisnis::all();
        $item['distrik'] = Distrik::all();

        $item['bagian'] = BidangDivisi::all();
        // $seljab= Jabatan::where('position_id', $user->position_id)->first();
        $item['jabatan'] = null;
        $item['grupdiv'] = GroupDivisiPembinaUnit::get();
        $item['current_grupdiv'] = null;
// select * from user_internal where nid not in (select username from users)
        $user_internal = DB::table('user_internal')
            ->whereNotIn('user_internal.nid', function ($query) {
                $query->select('username')
                    ->from(with(new User)->getTable());
            })
            ->get();

        $item['position_id']= null;

        $Sb = StrategiBisnis::all();
        $current_roles = null;
        return view('pages.admin.add_user', $item,compact('user','Sb','roles', 'current_roles', 'user_internal'));
    }

    public function postAddUser(Request $request)
    {
        $inputRules = [
            'username' => 'required|unique:users,username',
            'password' => 'required',
            'posisi' => 'required',
            // 'nama' => 'required',
            // 'email' => 'required',
            // 'nip' => 'required',
            // 'tempat_lahir' => 'required',
            // 'tanggal_lahir' => 'required',
        ];

        $customAttributes = [
            'username' => 'Username',
            'password' => 'Password',
            'posisi' => 'Grup',
            // 'nama' => 'Nama Lengkap',
            // 'email' => 'Email',
            // 'nip' => 'NIP',
            // 'tempat_lahir' => 'Tempat Lahir',
            // 'tanggal_lahir' => 'Tanggal Lahir',
        ];

        $validator = $this->getValidationFactory()->make($request->all(), $inputRules, [], $customAttributes);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $user = new User();
        $user->name = $request->nama;
        $user->email = ($request->has('email')) ? $request->email : '';
        $user->username = strtoupper($request->username);
        // $user->password = bcrypt($request->password);
        $user->password = md5($request->password);
        $user->role = '';
        $user->distrik_id = $request->distrik_id;
        $user->position_id = $request->position_id;
        $user->enabled = 1;

        if ($user->distrik_id==28 || $user->distrik_id==21) 
        {
            $user->bidang_divisi_id = $request->bidang_divisi_id;
        }
        else
        {
            $user->bidang_divisi_id = null;
        }
        // $user->nip = ($request->has('nip')) ? $request->nip : '';
        // $user->tempat_lahir = $request->tempat_lahir;
        // $user->tanggal_lahir = date('Y-m-d', strtotime(str_replace('/', '-', $request->tanggal_lahir)));
        // $user->pangkat_golongan = $request->golongan;
        // $user->pangkat_tmt = date('Y-m-d', strtotime(str_replace('/', '-', $request->pangkat_tmt)));
        // $user->pendidikan_jurusan = ($request->has('pendidikan')) ? $request->pendidikan : '';
        // $user->pendidikan_lulus = ($request->has('lulus')) ? $request->lulus : '';
        // $user->pendidikan_ijazah = ($request->has('ijazah')) ? $request->ijazah : '';
        $user->save();

        //$admin = Role::where('id', '=', $request->posisi)->first();
        //$user->attachRole($admin);

        $i = 0;
        $posisi = $request->posisi;
        foreach ($posisi as $row) {
            $data = array('user_id' => $user->id, 'role_id' => $posisi[$i]);
            $i++;
            DB::table('role_user')->insert($data);
        }

        $g = 0;
        $data_grupdiv = $request->grupdiv;
        // dd($data_posisi);
        $data_checkgrupdiv = DB::table('grup_divpembinaunit_users')->where('user_id', $user->id)->delete();
        if ($data_grupdiv) 
        {
            // $data_checkgrupdiv = DB::table('grup_divpembinaunit_users')->where('user_id', $user->id)->delete();
            foreach ($data_grupdiv as $row) {
                //echo $data_posisi[$k].',';
                //checking data
                // DB::table('role_user')->insert(array('user_id' => $id, 'role_id' => $row[$k]));
                DB::table('grup_divpembinaunit_users')->insert(array('user_id' => $user->id, 'grupdiv_id' => $row));
            }
        }
        $g++;

        return redirect()->route('admin.user.list')->with('message', 'Data berhasil disimpan');
    }

    public function getEditUser($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::get();

        $current_roles = DB::table('roles')
            ->join('role_user', 'role_user.role_id', '=', 'roles.id')
            ->where('role_user.user_id', $id)
            ->get();

        $current_grupdiv = DB::table('grup_divpembinaunit')
            ->join('grup_divpembinaunit_users', 'grup_divpembinaunit_users.grupdiv_id', '=', 'grup_divpembinaunit.id')
            ->where('grup_divpembinaunit_users.user_id', $id)
            ->get();

        // $grupdiv = GroupDivisiPembinaUnit::get();
        //     return $grupdiv;

        $grupdiv = DB::select(DB::raw("select * from grup_divpembinaunit where id not in (select grupdiv_id from grup_divpembinaunit a join grup_divpembinaunit_users b on b.grupdiv_id = a.id where b.user_id = ".$id.")"));
        

        $strategi_bisnis_id = StrategiBisnis::find($user->distrik->strategi_bisnis_id);
        // dd($strategi_bisnis_id);

        $item['strategi_bisnis'] = StrategiBisnis::all();
        $item['distrik'] = Distrik::all();

        $item['jabatan']= null;
        $item['position_id']= null;
        $item['user_internal']= null;
        if ($user->position_id!="") 
        {
            $item['position_id']= $user->position_id;

            $seljab= Jabatan::where('position_id', $user->position_id)->first();
            $item['jabatan'] = $seljab->nama_posisi;
        }        

        $item['bagian'] = BidangDivisi::all();

        $Sb = StrategiBisnis::all();
        return view('pages.admin.add_user',$item, compact('user','Sb', 'roles', 'current_roles','strategi_bisnis_id', 'grupdiv', 'current_grupdiv'));
    }

    public function postEditUser(Request $request, $id)
    {
      // dd('view');
        $inputRules = [
            'username' => 'required',
            'posisi' => 'required',
            // 'nama' => 'required',
            // 'email' => 'required',
            // 'nip' => 'required',
            // 'tempat_lahir' => 'required',
            // 'tanggal_lahir' => 'required',
        ];

        $customAttributes = [
            'username' => 'Username',
            'posisi' => 'Grup',
            // 'nama' => 'Nama Lengkap',
            // 'email' => 'Email',
            // 'nip' => 'NIP',
            // 'tempat_lahir' => 'Tempat Lahir',
            // 'tanggal_lahir' => 'Tanggal Lahir',
        ];

        $validator = $this->getValidationFactory()->make($request->all(), $inputRules, [], $customAttributes);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $user = User::findOrFail($id);
        $user->name = $request->nama;
        $user->email = ($request->has('email')) ? $request->email : '';
        $user->username = strtoupper($request->username);
        if ($request->has('password')) {
            if ($request->password != '') {
                // $user->password = bcrypt($request->password);
                $user->password = md5($request->password);
            }
        }
        $user->distrik_id = $request->distrik_id;
        $user->enabled = $request->status;
        if($user->enabled==0) {
            $user->username = 'deleted'.$user->id.$user->username;
            $user->deleted_at = date('Y-m-d h:i:sa');
        }

        if ($user->distrik_id==28 || $user->distrik_id==21) 
        {
            $user->bidang_divisi_id = $request->bidang_divisi_id;
        }
        else
        {
            $user->bidang_divisi_id = null;
        }
        // $user->nip = ($request->has('nip')) ? $request->nip : '';
        // $user->tempat_lahir = $request->tempat_lahir;
        // $user->tanggal_lahir = date('Y-m-d', strtotime(str_replace('/', '-', $request->tanggal_lahir)));
        // $user->pangkat_golongan = $request->golongan;
        // $user->pangkat_tmt = date('Y-m-d', strtotime(str_replace('/', '-', $request->pangkat_tmt)));
        // $user->pendidikan_jurusan = ($request->has('pendidikan')) ? $request->pendidikan : '';
        // $user->pendidikan_lulus = ($request->has('lulus')) ? $request->lulus : '';
        // $user->pendidikan_ijazah = ($request->has('ijazah')) ? $request->ijazah : '';
        // $user->status = ($request->has('status')) ? $request->status : 0;
        $user->save();

        // $admin = Role::where('id', '=', $user->role->data->id)->first();
        // $user->detachRole($admin);

        // $admin = Role::where('id', '=', $request->posisi)->first();
        // $user->attachRole($admin);

        $k = 0;
        $data_posisi = $request->posisi;
        // dd($data_posisi);
        $data_check = DB::table('role_user')->where('user_id', $id)->delete();
        foreach ($data_posisi as $row) {
            //echo $data_posisi[$k].',';
            //checking data
            // DB::table('role_user')->insert(array('user_id' => $id, 'role_id' => $row[$k]));
            DB::table('role_user')->insert(array('user_id' => $id, 'role_id' => $row));
        }
        $k++;

        $g = 0;
        $data_grupdiv = $request->grupdiv;
        $data_checkgrupdiv = DB::table('grup_divpembinaunit_users')->where('user_id', $id)->delete();
        // dd($data_posisi);
        if ($data_grupdiv) 
        {
            // $data_checkgrupdiv = DB::table('grup_divpembinaunit_users')->where('user_id', $id)->delete();
            foreach ($data_grupdiv as $row) {
                //echo $data_posisi[$k].',';
                //checking data
                // DB::table('role_user')->insert(array('user_id' => $id, 'role_id' => $row[$k]));
                DB::table('grup_divpembinaunit_users')->insert(array('user_id' => $id, 'grupdiv_id' => $row));
            }
        }
        
        $g++;

        return redirect()->route('admin.user.list')->with('message', 'Data berhasil diubah');
    }

    public function getViewUser($id)
    {
        $role_id = session('role_id');
        $role = Role::find($role_id);

        //kantor pusat
        if($role->is_kantor_pusat) {
            $user = User::findOrFail($id);
            $current_roles = DB::table('roles')
            ->join('role_user', 'role_user.role_id', '=', 'roles.id')
            ->where('role_user.user_id', $id)
            ->get();
        }
        else {
            $user_id = session('user_id');
            $user = User::findOrFail($user_id);
            $current_roles = DB::table('roles')
                ->join('role_user', 'role_user.role_id', '=', 'roles.id')
                ->where('role_user.user_id', $user_id)
                ->get();
        }

        return view('pages.admin.view_user', compact('user', 'current_roles'));
    }

    public function postEditViewUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        ($request->status_notif_email == 1) ? $user->status_notif_email = 1 : $user->status_notif_email = 0;
        $user->save();

        return redirect()->route('admin.user.view.view', ['id' => $id])->with('message', 'Data Status Notifikasi Email berhasil diubah');
    }

    // public function delete_user_role(Request $request)
    public function delete_user_role($id_user, $id_role)
    {
        // $id_user = $request->code;
        // $id_role = $request->code_role;
        DB::table('role_user')->where('user_id', $id_user)->where('role_id', $id_role)->delete();

        // return "berhasil menghapus data";
        return redirect()->route('admin.user.edit.view', ['id' => $id_user])->with('message', 'Berhasil menghapus Grup');
    }

    // public function delete_user_role(Request $request)
    public function delete_user_grupdiv($id_user, $code_role)
    {
        // $id_user = $request->code;
        // $code_role = $request->code_role;
        DB::table('grup_divpembinaunit_users')->where('user_id', $id_user)->where('grupdiv_id', $code_role)->delete();

        // return "berhasil menghapus data";
        return redirect()->route('admin.user.edit.view', ['id' => $id_user])->with('message', 'Berhasil menghapus Grup Divisi Pembina Unit');
    }


    public function postDeleteUser($id){

        $user = User::findOrFail($id);
        $user->username = 'deleted'.$user->id.$user->username;
        $user->enabled = 0;
        $user->save();
        $user->delete(); //soft deleted (di model sudah dideklarasi)

        //UserRole::where('user_id', $id)->get();
        // DB::table('role_user')->where('user_id', $id)->delete();

        return redirect()->route('admin.user.list')->with('message', 'Berhasil menghapus data');
    }

    //function Kelola Grup

    public function getRoleList()
    {
        $roles = Role::get();
        return view('pages.admin.roles.roles', compact('roles'));
    }

    public function getAddRole()
    {
        $roles = null;
        $permission = Permission::all();
        $permission_role = null;
        $users = null;
        return view('pages.admin.roles.add_roles', compact('roles', 'permission', 'permission_role', 'users'));
    }

    public function postAddRole(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required',
            'alias' => 'required',
        ], [
            'required' => 'Kolom ini tidak boleh kosong',
        ]);

        $roles = new Role();
        $roles->display_name = $request->nama;
        $roles->name = $request->alias;
        $roles->description = $request->deskripsi;
        $roles->save();

        $menu_akseses = $request->menu_akses;
        if (count($menu_akseses) > 0) {
            foreach ($menu_akseses as $menu_akses) {
                // $permissionRole = new PermissionRole();
                // $permissionRole->permission_id = $menu_akses;
                // $permissionRole->role_id = $roles->id;
                // $permissionRole->save();

                DB::table('permission_role')->insert(
                    ['permission_id' => $menu_akses, 'role_id' => $roles->id]
                );
            }
        }

        return redirect()->route('admin.role.list')->with('message', 'Data berhasil disimpan');
    }

    public function getEditRole($id)
    {
        $roles = Role::findOrFail($id);
        $permission = Permission::all();
        $permission_role = DB::table('permission_role')
            ->select('permissions.display_name', 'permissions.route_permission', 'permissions.id')
            ->join('roles', 'roles.id', '=', 'permission_role.role_id')
            ->join('permissions', 'permissions.id', '=', 'permission_role.permission_id')
            ->where('permission_role.role_id', $id)
            ->get();
        // $users = User::whereHas('role', function ($query) use ($id) { >> diganti ke group karena nama function nya diganti group
        $users = User::whereHas('group', function ($query) use ($id) {
            $query->whererole_id($id);
        })->get();

        return view('pages.admin.roles.add_roles', compact('roles', 'permission', 'permission_role', 'users'));
    }

    public function postEditRole(Request $request, $id)
    {
        $this->validate($request, [
            'nama' => 'required',
            'alias' => 'required',
        ], [
            'required' => 'Kolom ini tidak boleh kosong',
        ]);

        $roles = Role::findOrFail($id);
        $roles->display_name = $request->nama;
        $roles->name = ($request->has('alias')) ? $request->alias : '';
        $roles->description = ($request->has('deskripsi')) ? $request->deskripsi : '';
        $roles->save();

        $menu_akseses = $request->menu_akses;
        DB::table('permission_role')->where('role_id', $id)->delete();
        if (count($menu_akseses) > 0) {
            foreach ($menu_akseses as $menu_akses) {
                // $permissionRole = new PermissionRole();
                // $permissionRole->permission_id = $menu_akses;
                // $permissionRole->role_id = $id;
                // $permissionRole->save();

                DB::table('permission_role')->insert(
                    ['permission_id' => $menu_akses, 'role_id' => $roles->id]
                );
            }
        }

        return redirect()->route('admin.role.list')->with('message', 'Data berhasil diubah');
    }

    public function postDeleteRole($id)
    {
        // $roles = Role::findOrFail($id);
        // $roles->delete();
        DB::table('roles')->where('id', $id)->delete();

        DB::table('role_user')->where('role_id', $id)->delete();
        DB::table('permission_role')->where('role_id', $id)->delete();

        return redirect()->route('admin.role.list')->with('message', 'Data berhasil dihapus');
    }

    public function getViewRole($id)
    {
        $roles = Role::findOrFail($id);

        $users = DB::table('users')
                ->join('role_user','role_user.user_id','=','users.id')
                ->where('role_user.role_id',$id)
                ->get();

        // $users = User::whereHas('role', function ($query) use ($id) {
        //     $query->whererole_id($id);
        // })->get();

        $users_to_add = DB::table('users')
            ->select('users.*')
            ->whereNotIn('users.id', function ($query) use ($id) {
                $query->select('user_id')
                    ->from(with(new UserRole)->getTable())
                    ->where('role_id', $id);
            })->get();

        $permission_role = DB::table('permission_role')
            ->select('permissions.display_name', 'permissions.route_permission', 'permissions.id')
            ->join('roles', 'roles.id', '=', 'permission_role.role_id')
            ->join('permissions', 'permissions.id', '=', 'permission_role.permission_id')
            ->where('permission_role.role_id', $id)
            ->get();

        return view('pages.admin.roles.view_roles', compact('roles', 'users', 'permission_role', 'users_to_add'));
    }

    public function postDeleteRoleUser($role_id, $user_id)
    {
        $params['role_id'] = $role_id;
        $params['user_id'] = $user_id;

        $user = User::findOrFail($user_id);
        // jika current_id_roles sama dengan role yang akan dihapus, maka user akan di assign ke role lain yang dia punya
        // jika tidak punya selain itu, maka akan diset 0
        if ($user->current_id_roles == $role_id) {
            $other_roles = DB::table('role_user')->where('user_id', $user_id)->where('role_id', '!=', $role_id)->first();
            if ($other_roles != null) {
                $user->current_id_roles = $other_roles->role_id;
            } else {
                $user->current_id_roles = 0;
            }
            $user->save();
        }

        DB::table('role_user')->where($params)->delete();

        return redirect()->route('admin.role.view.view', ['id' => $role_id]);
    }

    public function postAddRoleUser()
    {
        $role_id = Input::get('role_id_to_add');
        $user_id = Input::get('user_id_to_add');

        // $roleUser = new UserRole();
        // $roleUser->role_id = $role_id;
        // $roleUser->user_id = $user_id;
        // $roleUser->save();

        $data = array('user_id' => $user_id, 'role_id' => $role_id);
        DB::table('role_user')->insert($data);

        return redirect()->route('admin.role.view.view', ['id' => $role_id]);
    }

    // end of function grup

    // start permission roles
    public function getPermissionList()
    {
        $params = null;

        $is_menu_filter = Input::get("is_menu_filter");
        if (isset($is_menu_filter) && $is_menu_filter != "") {
            $params['is_menu'] = [$is_menu_filter];
        } else {
            $is_menu_filter = null;
        }

        if (isset($params) > 0) {
            $permission = Permission::where($params)->get();
        } else {
            $permission = Permission::get();
        }

        return view('pages.admin.permission.permission', compact('permission', 'is_menu_filter'));
    }

    public function getAddPermission()
    {
        $permission = null;
        $sequence = null;
        $sequencesub = null;
        $parent = null;
        $rootparent = null;
        $children = null;
        // menu tidak tampil
        // return view('pages.admin.permission.add_permission_temp', compact('permission', 'sequence', 'sequencesub', 'parent', 'rootparent', 'allparents', 'children'));

        return view('pages.admin.permission.add_permission', compact('permission', 'sequence', 'sequencesub', 'parent', 'rootparent', 'allparents', 'children'));
    }

    public function postAddPermission(Request $request)
    {
        if ($request->menu_akses == 'akses') {
            $permission = new Permission();
            $permission->display_name = $request->nama;
            $permission->name = $request->alias;
            $permission->description = $request->deskripsi;
            $permission->route_permission = $request->route_permission;
            $permission->enabled = 1;
            $permission->is_parent = 0;
            $permission->is_menu = 0;
            $permission->sequence_number = 0;
            $permission->save();
        } else {
            //manipulate is_parent and is_menu
            if ($request->menu == 'is_parent') {

                $this->validate($request, [
                    'nama' => 'required',
                    'alias' => 'required',
                ], [
                    'required' => 'Kolom ini tidak boleh kosong',
                ]);

                $is_parent = 1;
                $is_menu = 1;

                //default value
                $enabled = 1; //aktif

                $permission = new Permission();
                $permission->display_name = $request->nama;
                $permission->name = $request->alias;
                $permission->description = $request->deskripsi;
                $permission->route_permission = '#';
                $permission->enabled = $enabled;
                $permission->is_parent = $is_parent;
                $permission->is_menu = $is_menu;
                $permission->save();

                $sequence = $request->sequence_number;
                $sequence = explode(',', $sequence);
                $i = 1;
                foreach ($sequence as $id) {
                    if ($id != 'new') {
                        $updated_permission = Permission::find($id);
                        $updated_permission->sequence_number = $i;
                        $updated_permission->save();
                    } else {
                        $permission->sequence_number = $i;
                        $permission->save();
                    }
                    $i++;
                }

            } else {

                $this->validate($request, [
                    'nama' => 'required',
                    'alias' => 'required',
                ], [
                    'required' => 'Kolom ini tidak boleh kosong',
                ]);

                $is_parent = 0;
                $is_menu = 1;

                //default value
                $enabled = 1; //aktif
                $sequence_number = 1;

                $permission = new Permission();
                $permission->display_name = $request->nama;
                $permission->name = $request->alias;
                $permission->description = $request->deskripsi;
                $permission->route_permission = $request->route_permission;
                $permission->enabled = $enabled;
                $permission->is_parent = $is_parent;
                $permission->is_menu = $is_menu;
                $permission->parent_id = $request->parent_menu;
                $permission->save();

                $last_parent_id = $permission->id;

                $sequence = $request->sequence_number_sub;
                $sequence = explode(',', $sequence);
                $i = 1;
                foreach ($sequence as $id) {
                    if ($id != 'new') {
                        $updated_permission = Permission::find($id);
                        $updated_permission->sequence_number = $i;
                        $updated_permission->save();
                    } else {
                        $permission->sequence_number = $i;
                        $permission->save();
                    }
                    $i++;
                }

                // generate apabila menu memiliki fungsi crud
                if ($request->has('crud')) {
                    $k = 0;
                    $crud = $request->crud;
                    foreach ($crud as $row) {
                        if ($crud != null) {
                            $permission = new Permission();
                            $display_name = '';
                            if (str_contains($crud[$k], 'manage')) {
                                $display_name = 'View ' . $request->nama;
                                //$permission->is_menu = 1;
                            } else if (str_contains($crud[$k], 'create')) {
                                $display_name = 'Tambah ' . $request->nama;
                                //$permission->is_menu = 1;
                            } else if (str_contains($crud[$k], 'update')) {
                                $display_name = 'Ubah ' . $request->nama;
                                //$permission->is_menu = 0;
                            } else if (str_contains($crud[$k], 'delete')) {
                                $display_name = 'Hapus ' . $request->nama;
                                //$permission->is_menu = 0;
                            } else if (str_contains($crud[$k], 'detail')) {
                                $display_name = 'Detail ' . $request->nama;
                                //$permission->is_menu = 0;
                            }

                            $permission->display_name = $display_name;
                            $permission->name = strtolower(str_replace(' ', '', $display_name));
                            $permission->route_permission = $crud[$k];
                            $permission->enabled = 1;
                            $permission->is_parent = 0;
                            $permission->is_menu = 0;
                            $permission->parent_id = $last_parent_id;
                            $permission->sequence_number = $k + 1;
                            $permission->save();

                            $k++;
                        }
                    }
                }
                // end of generating crud
            }
        }
        return redirect()->route('admin.permission.list')->with('message', 'Data berhasil disimpan');
    }

    //ajax parent id
    public function getParentId()
    {
        $data = Permission::where('is_parent', 1)->where('is_menu', 1)->orderBy('sequence_number', 'asc')->get();
        return response()->json(array('permission_data' => $data));
    }

    //ajax parent menu
    public function getParentMenu()
    {
        $data = Permission::where('parent_id', Input::get('parentID'))->where('is_menu', 1)->orderBy('sequence_number', 'asc')->get();
        return response()->json(array('permission_data' => $data));
    }

    public function getEditPermission($id)
    {
        $permission = Permission::findOrFail($id);
        $sequence = null;
        $sequencesub = null;
        $parent = null;
        $rootparent = null;
        $children = null;
        $allparents = Permission::where('is_parent', 1)->where('is_menu', 1)->orderBy('sequence_number', 'asc')->get();
        if ($permission->is_menu == 1 && $permission->is_parent == 1) {
            $sequence = Permission::where('is_parent', 1)->where('is_menu', 1)->orderBy('sequence_number', 'asc')->get();
        } else {
            if ($permission->parent_id != null) {
                $parent = Permission::findOrFail($permission->parent_id);
                if ($parent->parent_id != null) {
                    $rootparent = Permission::findOrFail($parent->parent_id);
                } else {
                    $children = Permission::where('parent_id', $permission->id)->orderBy('sequence_number', 'asc')->get();
                }
            }

            if ($permission->is_menu == 1) {
                $sequencesub = Permission::where('parent_id', $permission->parent_id)->where('is_menu', 1)->orderBy('sequence_number', 'asc')->get();
            }
        }
        // menu tidak tampil
        // return view('pages.admin.permission.add_permission_temp', compact('permission', 'sequence', 'sequencesub', 'parent', 'rootparent', 'allparents', 'children'));

        return view('pages.admin.permission.add_permission', compact('permission', 'sequence', 'sequencesub', 'parent', 'rootparent', 'allparents', 'children'));
    }

    public function postEditPermission(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $is_parent = $permission->is_parent;
        $is_menu = $permission->is_menu;
        $parent_id = $permission->parent_id;
        $old_route = $permission->route_permission;

        if ($request->menu_akses == 'akses') {
            $permission->display_name = $request->nama;
            $permission->name = $request->alias;
            $permission->description = $request->deskripsi;
            $permission->route_permission = $request->route_permission;
            $permission->is_parent = 0;
            $permission->is_menu = 0;
            $permission->parent_id = null;
            $permission->sequence_number = 0;
            $permission->save();
        } else {
            if ($request->menu == 'is_parent') {
                $permission->display_name = $request->nama;
                $permission->name = $request->alias;
                $permission->description = $request->deskripsi;
                $permission->route_permission = '#';
                $permission->is_parent = 1;
                $permission->is_menu = 1;
                $permission->parent_id = null;
                $permission->save();

                $sequence = $request->sequence_number;
                $sequence = explode(',', $sequence);
                $i = 1;
                foreach ($sequence as $id) {
                    if ($id == 'new') {
                        $id = $permission->id;
                    }
                    $updated_permission = Permission::find($id);
                    $updated_permission->sequence_number = $i;
                    $updated_permission->save();
                    $i++;
                }
            } else {
                $permission->display_name = $request->nama;
                $permission->name = $request->alias;
                $permission->description = $request->deskripsi;
                $permission->route_permission = $request->route_permission;
                if ($request->parent_menu != '') {
                    $permission->parent_id = $request->parent_menu;
                }
                $permission->is_parent = 0;
                $permission->is_menu = 1;
                $permission->save();

                $sequence = $request->sequence_number_sub;
                $sequence = explode(',', $sequence);
                $i = 1;
                foreach ($sequence as $id) {
                    if ($id == 'new') {
                        $id = $permission->id;
                    }
                    $updated_permission = Permission::find($id);
                    $updated_permission->sequence_number = $i;
                    $updated_permission->save();
                    $i++;
                }

                // generate apabila menu memiliki fungsi crud
                if ($request->has('crud')) {
                    $child_permissions = Permission::where('parent_id', $permission->id)->where('is_menu', 0)->get();
                    if (count($child_permissions) > 0) {
                        foreach ($child_permissions as $row) {
                            $new_route = str_replace($old_route, $request->route_permission, $row->route_permission);
                            $row->route_permission = $new_route;
                            $row->save();
                        }
                    } else {
                        $k = 0;
                        $crud = $request->crud;
                        foreach ($crud as $row) {
                            if ($crud != null) {
                                $new_child_permission = new Permission();
                                $display_name = '';
                                if (str_contains($crud[$k], 'manage')) {
                                    $display_name = 'View ' . $request->nama;
                                } else if (str_contains($crud[$k], 'create')) {
                                    $display_name = 'Tambah ' . $request->nama;
                                } else if (str_contains($crud[$k], 'update')) {
                                    $display_name = 'Ubah ' . $request->nama;
                                } else if (str_contains($crud[$k], 'delete')) {
                                    $display_name = 'Hapus ' . $request->nama;
                                } else if (str_contains($crud[$k], 'detail')) {
                                    $display_name = 'Detail ' . $request->nama;
                                }

                                $new_child_permission->display_name = $display_name;
                                $new_child_permission->name = strtolower(str_replace(' ', '', $display_name));
                                $new_child_permission->route_permission = $crud[$k];
                                $new_child_permission->enabled = 1;
                                $new_child_permission->is_parent = 0;
                                $new_child_permission->is_menu = 0;
                                $new_child_permission->parent_id = $permission->id;
                                $new_child_permission->sequence_number = $k + 1;
                                $new_child_permission->save();
                                $k++;
                            }
                        }
                    }
                }
                // end of generating crud
            }
        }

        /*if($is_parent==1){
        $this->validate($request, [
        'nama'          => 'required',
        'alias'          => 'required'
        ],[
        'required'          => 'Kolom ini tidak boleh kosong'
        ]);

        $permission->display_name = $request->nama;
        $permission->name = $request->alias;
        $permission->description = $request->deskripsi;
        $permission->save();

        $sequence = $request->sequence_number;
        $sequence = explode(',', $sequence);
        $i = 1;
        foreach($sequence as $id){
        if($id=='new'){
        $id = $permission->id;
        }
        $updated_permission = Permission::find($id);
        $updated_permission->sequence_number = $i;
        $updated_permission->save();
        $i++;
        }
        } else if($is_parent==0 && $is_menu==1 && $parent_id!=null){
        $this->validate($request, [
        'nama'          => 'required',
        'alias'          => 'required'
        ],[
        'required'          => 'Kolom ini tidak boleh kosong'
        ]);

        $permission->display_name = $request->nama;
        $permission->name = $request->alias;
        $permission->description = $request->deskripsi;
        if($request->has('sub_menu_id')){
        $sub_menu_permission = Permission::findOrFail($request->sub_menu_id);
        $prefix = $sub_menu_permission->route_permission;
        $suffix = $request->route_permission;
        $permission->route_permission = $prefix.$suffix;
        } else {
        $permission->route_permission = $request->route_permission;
        }
        if($request->parent_menu!=''){
        $permission->parent_id = $request->parent_menu;
        }
        $permission->save();

        $sequence = $request->sequence_number_sub;
        $sequence = explode(',', $sequence);
        $i = 1;
        foreach($sequence as $id){
        if($id=='new'){
        $id = $permission->id;
        }
        $updated_permission = Permission::find($id);
        $updated_permission->sequence_number = $i;
        $updated_permission->save();
        $i++;
        }

        // generate apabila menu memiliki fungsi crud
        if($request->has('crud')){
        $child_permissions = Permission::where('parent_id',$permission->id)->get();
        if(count($child_permissions) > 0){
        foreach($child_permissions as $row){
        $new_route = str_replace($old_route,$request->route_permission,$row->route_permission);
        $row->route_permission = $new_route;
        $row->save();
        }
        }
        }
        // end of generating crud
        } else if($is_parent==0 && $is_menu==0){
        $permission->display_name = $request->nama;
        $permission->name = $request->alias;
        $permission->description = $request->deskripsi;
        if($request->has('sub_menu_id')){
        $sub_menu_permission = Permission::findOrFail($request->sub_menu_id);
        $prefix = $sub_menu_permission->route_permission;
        $suffix = $request->route_permission;
        $permission->route_permission = $prefix.$suffix;
        } else {
        $permission->route_permission = $request->route_permission;
        }
        $permission->save();
        }*/

        return redirect()->route('admin.permission.list')->with('message', 'Data berhasil diubah');
    }

    public function postDeletePermission($id)
    {
        $permission = Permission::findOrFail($id);

        // force delete permission pada junction table permission_role
        DB::table('permission_role')->where('permission_id', $id)->delete();

        // set parent dari child menu ke null
        $children = Permission::where('parent_id', $permission->id)->get();
        foreach ($children as $child) {
            $child->parent_id = null;
            $child->save();
        }

        // finally force delete current permission
        // $permission->delete();
        DB::table('permissions')->where('id', $id)->delete();

        return redirect()->route('admin.permission.list')->with('message', 'Berhasil menghapus data');
    }

    public function getViewPermission($id)
    {
        $permission = Permission::findOrFail($id);

        $roles = DB::table('roles')
            ->select('roles.*')
            ->join('permission_role', 'roles.id', '=', 'permission_role.role_id')
            ->where('permission_role.permission_id', $id)
            ->get();

        $roles_to_add = DB::table('roles')
            ->select('roles.*')
            ->whereNotIn('roles.id', function ($query) use ($id) {
                $query->select('role_id')
                    ->from(with(new PermissionRole)->getTable())
                    ->where('permission_id', $id);
            })->get();

        return view('pages.admin.permission.view_permission', compact('roles', 'permission', 'roles_to_add'));
    }

    // end of permission role

    public function postDeletePermissionRole($permission_id, $role_id)
    {
        $params['permission_id'] = $permission_id;
        $params['role_id'] = $role_id;

        DB::table('permission_role')->where($params)->delete();

        return redirect()->route('admin.permission.view.view', ['id' => $permission_id]);
    }

    public function postAddPermissionRole()
    {
        $permission_id = Input::get('permission_id_to_add');
        $role_id = Input::get('role_id_to_add');

        $permissionRole = new PermissionRole();
        $permissionRole->permission_id = $permission_id;
        $permissionRole->role_id = $role_id;
        $permissionRole->save();

        return redirect()->route('admin.permission.view.view', ['id' => $permission_id]);
    }

}
