<?php

namespace App\Http\Controllers;

use App\Libs\PasswordHash;
use App\Models\User;
use App\Models\UsersQQ;
use App\Models\UsersWeibo;
use App\Models\WebOption;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Overtrue\LaravelSocialite\Socialite;
use Overtrue\Socialite\SocialiteManager;
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

    public function login(Request $request)
    {

        if ($request->dologin) {
            $wp_hasher = new PasswordHash(8, TRUE);
            $user = User::where('name', $request->username)->first();

            $checkPassword = $wp_hasher->CheckPassword($request->password, $user['password']);

            if ($checkPassword) {
                if ($user->status == 'lock') {
                    return redirect('/login')->withErrors(['用户已被锁定请联系站长']);
                }
                $user->last_login_time = Carbon::now()->toDateTimeString();
                $user->save();
                Auth::guard('users')->login($user);
                return redirect(route('user'));
            } else {
                return redirect('/login')->withErrors(['用户名密码错误']);
            }
        } else {
            return view('home.login');
        }
    }

    public function reg(Request $request)
    {
        if ($request->doreg) {

            $validator = Validator::make($request->all(), [
                'username' => 'required|max:16|string',
                'password' => 'required|max:16|string',
                'password_confirmation' => 'required|max:16|string', "same:password",
                'email' => 'email',
            ]);

            if ($validator->fails()) {
                return redirect(url('login?type=reg'))->withErrors($validator)->withInput();
            }
            $wp_hasher = new PasswordHash(8, TRUE);
            $password = $wp_hasher->HashPassword($request->password);

            $user_id = User::insertGetId([
                'name' => $request->username,
                'email' => $request->email,
                'password' => $password,
                'created_at' => Carbon::now()->toDateTimeString(),
                'registered' => Carbon::now()->toDateTimeString(),
                'avatar_url' => env('Web_Avatar_Url')
            ]);
            if ($user_id) {
                $user = User::find($user_id);
                Auth::guard('users')->login($user);
                return redirect(route('user'));
            }
            return redirect('/login?type=reg')->withErrors(['注册失败']);
        } else {
            return redirect(route('login'));
        }
    }

    public function socialite_login(Request $request, $socialite)
    {
        return Socialite::driver($socialite)->redirect();

    }

    public function qq_back(Request $request)
    {
        $user = Socialite::driver('qq')->user();
        $qq_info = UsersQQ::where(['access_token' => $user->token, 'openid' => $user->id])->first();
        if (!empty($qq_info)) { // 保存登录session
            $user = User::find($qq_info['user_id']);
            if ($user->status == 'lock') {
                return redirect('/login')->withErrors(['用户已被锁定请联系站长']);
            }
            $user->last_login_time = Carbon::now()->toDateTimeString();
            $user->save();
            Auth::guard('users')->login($user);
            return redirect(route('user'));
        } else { // 注册新用户
            DB::beginTransaction();

            if ($user_info = User::create(['name' => $user->name,'avatar_url'=>$user->avatar])) {
                if ($res = $user_info->user_qq()->create(["openid" => $user->id, 'access_token' => $user->token])) {
                    DB::commit();
                    Auth::guard('users')->login($user_info);
                    return redirect(route('user'));
                } else {
                    DB::rollBack();
                    return redirect('/login')->withErrors(['系统有误']);
                }
            } else {
                return redirect('/login')->withErrors(['系统有误']);
            }


        }
    }

    public function weibo_back(Request $request)
    {
        $user = Socialite::driver('weibo')->user();

        $weibo_info = UsersWeibo::where(['access_token' => $user->token, 'openid' => $user->id])->first();
        if (!empty($weibo_info)) { // 保存登录session
            $user = User::find($weibo_info['user_id']);
            if ($user->status == 'lock') {
                return redirect('/login')->withErrors(['用户已被锁定请联系站长']);
            }
            $user->last_login_time = Carbon::now()->toDateTimeString();
            $user->save();
            Auth::guard('users')->login($user);
            return redirect(route('user'));

        } else { // 注册新用户
            DB::beginTransaction();

            if ($user_info = User::create(['name' => $user->name,'avatar_url'=>$user->avatar])) {
                if ($res = $user_info->user_weibo()->create(["openid" => $user->id, 'access_token' => $user->token])) {
                    DB::commit();
                    Auth::guard('users')->login($user_info);
                    return redirect(route('user'));
                } else {
                    DB::rollBack();
                    return redirect('/login')->withErrors(['系统有误']);
                }
            } else {
                return redirect('/login')->withErrors(['系统有误']);
            }


        }
    }

    public function test()
    {
        $index_menu = WebOption::getIndexMenu();
        $index_banner = WebOption::getBanner();
        $index_option = WebOption::getOption('index', true);
        ksort($index_option['op_value']);
        return view('test', [
            'index_menu' => $index_menu,
            'index_banner' => $index_banner,
            'index_option' => $index_option['op_value'],
        ]);
    }
}
