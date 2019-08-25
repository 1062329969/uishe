<?php

namespace App\Http\Controllers;

use App\Libs\PasswordHash;
use App\WebOption;
use App\WpUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return view('index', [
            'index_menu' => $index_menu,
        ]);
    }
    public function templet()
    {
        return view('templet');
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
            return view('login');
        }
    }
}
