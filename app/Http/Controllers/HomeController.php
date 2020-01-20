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
use Illuminate\Support\Facades\Storage;
use Overtrue\LaravelSocialite\Socialite;
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

    public function test()
    {
//        dd(public_path('/images/logo.png'));
        Storage::disk('ftp')
            ->writeStream(
                '/images/logo.png',
                Storage::disk('local')->readStream('..//public/images/logo.png')
            );


//        return view('test');
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

    public function t()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://cdn.oursketch.com/FSHN%20Fashion%20E-Commerce%20App%20UI.sketch",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => array(
                "authority: cdn.oursketch.com,method: GET,path: /Eggplore%20Simple%20Map%20App%20UI.sketch,scheme: https",
                "Postman-Token: 11208879-c82c-4648-93f7-6c3f008aaa98",
                "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3",
                "accept-encoding: gzip, deflate, br",
                "accept-language: zh-CN,zh;q=0.9",
                "cache-control: no-cache,no-cache",
                "cookie: Hm_lvt_70a1d60c3498fd09334af15ab61ef4d8=1576941384; Hm_lpvt_70a1d60c3498fd09334af15ab61ef4d8=1577023784",
                "pragma: no-cache",
                "referer: https://oursketch.com/resource?category=ui",
                "sec-fetch-mode: navigate",
                "sec-fetch-site: same-site",
                "sec-fetch-user: ?1",
                "upgrade-insecure-requests: 1",
                "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }
}
