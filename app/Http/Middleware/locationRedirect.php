<?php

namespace App\Http\Middleware;

use Closure;
use App\Entities\Role;
use App\Entities\User;
use App\Entities\StrategiBisnis;
use App\Entities\GroupDivisiPembinaUnit;
use Route;
use DB;

class locationRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //LDAP
        // if (session('username')) {
        //   # code...
        //   $username = session('username');
        // }
        // else{
        //   return redirect('login');
        // }
        // echo "Middleware";

        $user_id = session('user_id');
        if ($user_id) {
            $user = User::find($user_id);
            $data['user'] = $user;
            $data['user_session'] = $user;
            $data['username'] = $user->name;
            if($user->distrik->code1 == 'UBRS' ||
                $user->distrik->code1 == 'UJL2' ||
                $user->distrik->code1 == 'UPMK' ||
                $user->distrik->code1 == 'UPMT' ||
                $user->distrik->code1 == 'PJB2' ||
                $user->distrik->code1 == 'SGRK') {
                $data['multi_sb'] = true;
            }
            else {
                $data['multi_sb'] = false;
            }
            $data['data_sb'] = StrategiBisnis::get();

            // $data['data_role_user'] = Role::all();
            $data['data_role_user'] = $user->roles()->get();
            // dd($user->roles()->get());

            $data['data_grupdiv_user']= DB::select(DB::raw(" select a.* 
                from grup_divpembinaunit a
                join grup_divpembinaunit_users b on b.grupdiv_id = a.id
                where b.user_id = ".$user_id."
                "
            ));

            if($user->current_grupdiv_id) {
                session(['grupdiv_id' => $user->current_grupdiv_id]);
                $data['grupdiv'] = GroupDivisiPembinaUnit::find($user->current_grupdiv_id);
                $data['grupdiv_id'] = $user->current_grupdiv_id;
            }
            else {
                // session(['grupdiv_id' => $data['data_grupdiv_user'][0]->id]);
                // $data['grupdiv'] = GroupDivisiPembinaUnit::find($data['data_grupdiv_user'][0]->id);
                // $data['grupdiv_id'] = $data['data_grupdiv_user'][0]->id;
                // $user->current_grupdiv_id = $data['grupdiv_id'];
                // $user->save();
            }


            if($user->current_id_roles) {
                session(['role_id' => $user->current_id_roles]);
                $data['role'] = Role::find($user->current_id_roles);
                $data['role_id'] = $user->current_id_roles;
            }
            else {
                session(['role_id' => $user->roles()->first()->id]);
                $data['role'] = $user->roles()->first();
                $data['role_id'] = $user->roles()->first()->id;
                $user->current_id_roles = $data['role_id'];
                $user->save();
            }
            view()->share($data);

            // just authenticated users
            // $currentRoute = Route::current()->getUri();
            // $currentRoute = $request->path();
            $currentRoute = Route::current()->uri();
            if ($user->hasAccess($currentRoute)) {
                // Kalau punya akses
            } else {
                // kalau ndak punya
                 //return redirect('noaccess');
            }
        }
        else{
            return redirect('login');
        }
        return $next($request);
    }

     /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/');
    }
}

// {
//        // getting user country based on ip address
//         $ip= $request->ip();
//         $user_country_code="us";
//         $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
//         if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
//              $user_country_code= @$ipdat->geoplugin_countryCode;
//          }
//          if($user_country_code!=null &&  $user_country_code !="us"){
//            $user_domain=$user_country_code."yourdomain.com";
//            return return Redirect::to($user_domain);
//          }
//         return $next($request);
//     }
