<?php

namespace App\Http\Controllers;

use App\Libs\PasswordHash;
use App\Models\User;
use App\Models\WebOption;
use App\Models\WpUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /*public function __construct()
    {
        $this->middleware('auth');
    }*/

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $index_menu = WebOption::getIndexMenu();
        $index_banner = WebOption::getBanner();
        $index_option = WebOption::getOption('index', true);
        ksort($index_option['op_value']);
        return view('home.index', [
            'index_menu' => $index_menu,
            'index_banner' => $index_banner,
            'index_option' => $index_option['op_value'],
        ]);
    }
    public function templet()
    {
        return view('home.templet');
    }

    public function login(Request $request) {

        if ($request->dologin) {

            $user = WpUsers::where('user_login', $request->user_login)->first();
            $wp_hasher=new PasswordHash(8, TRUE);
            $checkPassword=$wp_hasher->CheckPassword($request->user_pass, $user['user_pass']);

            if ($checkPassword) {
                Auth::login($user);
                return redirect('/user/index');
            }else{
                echo '登录失败';
            }
        } else {
            return view('home.login');
        }
    }

    public function reg(Request $request) {

        if ($request->doreg) {

            $validator = Validator::make($request->all(), [
                'username' => 'required|max:16|string',
                'password' => 'required|max:16|string',
                'password_confirmation'=>'required|max:16|string',"same:password",
                'email' => 'email',
            ]);

            if ($validator->fails()) {
                return redirect(url('login?type=reg'))->withErrors($validator)->withInput();
            }
            $wp_hasher=new PasswordHash(8, TRUE);
            $password = $wp_hasher->HashPassword($request->password);

            $user_id = User::insertGetId([
                 'name' => $request->username,
                 'email' => $request->email,
                 'password' => $password,
                 'created_at' => Carbon::now()->toDateTimeString(),
            ]);
            if($user_id){
                $user = User::find($user_id);
                Auth::guard('users')->login($user);
                return redirect(url('/user/index'));
            }
            return redirect('/login?type=reg')->withErrors(['注册失败']);
        } else {
            return redirect(route('login'));
        }
    }
}
