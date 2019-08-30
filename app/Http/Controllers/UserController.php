<?php

namespace App\Http\Controllers;

use App\Models\DownLog;
use App\Models\WpOrders;
use App\Models\Posts;
use App\Models\User;
use App\Models\Usermeta;
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
        $user_collect = Usermeta::getUserCollect( $user->ID, 'count');
        //获取用户收藏
        $user_avatar = Usermeta::getUserAvatar( $user->ID );
        //获取用户下载记录
        $downlog = DownLog::getDownLog(0, $user->ID, '', 4, 0);
        return view('user.index', [
            'posts_count' => $posts_count,
            'user_credit' => $user_credit,
            'user_collect' => $user_collect,
            'downlog' => $downlog,
            'user_avatar' => $user_avatar,
        ]);
    }

    public function collect(){
        $user = Auth::user();
        $user_collect = Usermeta::getUserCollect( 52233, false, 2);
        return view('user.collect', [
            'user_collect' => $user_collect,
        ]);
    }

    public function orders(){
        $user = Auth::user();
        $user_order = WpOrders::getUserOrder( 1 );
        return view('user.order', [
            'user_order' => $user_order,
        ]);
    }

    public function loginout(){
        Auth::logout();
        redirect('/');
    }
}
