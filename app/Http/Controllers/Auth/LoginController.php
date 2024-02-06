<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Entities\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    // use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // return $this->sendFailedLoginResponse($request);

        /*LOGIN DATABASE*/
         $username = $request->get('username');
         $password = $request->get('password');
         // dd(md5($password));
         $user = User::where('username','like',$username)
                       ->where('password','like',md5($password))
                       ->first();

         if ($user) {
             $user_id = $user->id;
             $updated = array('user_id' => $user_id,
                               'last_login_time' => date("Y-m-d H:i") );
             DB::table('user_log')->insert($updated);

             // dd($user_id);
           session(['user_id'=>$user_id]);
           // echo "login dulu";
           // dd(session('user_id'));
           return redirect('/');
         }
         else{
           return redirect('login')->with('message', 'Maaf, Username/password yang Anda masukkan salah.');
         }

        /**==============================================*/

        /*=====================LDAP=======================*/
//        if ($request->session()->exists('username')) {
//
//            return redirect('/');
//        } else {
//            $username = $request->get('username');
//            $password = $request->get('password');
//            // $json = file_get_contents("http://login.ptpjb.com/ldap_api/auth_opendj/".$username."/".$password);
//            //$json = file_get_contents("https://login.ptpjb.com/ldap_api/auth_opendj/".$username."/".urlencode($password));
//            $json = file_get_contents("http://192.168.3.203/ldap_api/auth_opendj/" . $username . "/" . urlencode($password));
//            $array_resp = json_decode($json, true);
//
//            if ($array_resp['valid']) {
//                # code...
//                // echo "sukses";
//                $userdetail = $array_resp['userdetail'];
//                $user = User::where('username', 'like', strtoupper($username))->first();
//                if ($user) {
//                    $user_id = $user->id;
//                } else {
//                    return redirect('login')->with('message', 'Maaf Anda tidak memiliki hak akses ke IPLAN. Harap membuat tiket di helpdesk.ptpjb.com.');
//                }
//
//                $updated = array(
//                    'user_id' => $user_id,
//                    'last_login_time' => date("Y-m-d H:i")
//                );
//                DB::table('user_log')->insert($updated);
//
//                $temp_o = $userdetail['unit'];
//                $temp_distrik = $temp_o['0'];
//                // $split = trim(explode(",",$temp_distrik)[1]);
//
//                // tidak jadi code2, diganti code_ldap
//                // $distrik = explode("-",$temp_distrik)[0];
//                // $distrik_id = DB::table('distrik')->Where('code2','like',$distrik)->first()->id;
//                $distrik = $temp_distrik;
//                $distrik_id = DB::table('distrik')->Where('code_ldap', 'like', $distrik)->first()->id;
//                session([
//                    'username' => strtoupper($username),
//                    // 'roles_id' => 1,
//                    'distrik_id' => $distrik_id,
//                    'user_id' => $user_id,
//                ]);
//                return redirect('/');
//            } else {
//                return redirect('login')->with('message', 'Maaf, Username/password yang Anda masukkan salah.');
//            }
//        }
    }

    public function logout(Request $request)
    {
        $user_id = session("user_id");
        $last_login_id = DB::table('user_log')->where('user_id', $user_id)
            ->orderBy('last_login_time', 'desc')
            ->first()->id;
        $updated = array('last_logout_time' => date("Y-m-d H:i"));
        DB::table('user_log')->where('id', $last_login_id)->update($updated);
        $request->session()->invalidate();
        return redirect('login');
    }
}
