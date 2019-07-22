<?php

namespace App\Http\Controllers;

use App\DownLog;
use App\Posts;
use App\User;
use App\Usermeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
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
        $user = Auth::user();
        //获取帖子数
        $posts_count  = Posts::countUserPosts( $user->ID );
        //获取用户和积分消费
        $user_credit = Usermeta::getUserCredit( $user->ID );
        //获取用户收藏
        $user_collect = Usermeta::getUserCollect( $user->ID, '' );
        //获取用户下载记录
        $downlog = DownLog::getDownLog(0, $user->ID, '', 4, 0);
        return view('user.index', [
            'posts_count' => $posts_count,
            'user_credit' => $user_credit,
            'user_collect' => $user_collect,
            'downlog' => $downlog,
        ]);
    }

    public function loginout(){
        Auth::logout();
        redirect('/');
    }
}
