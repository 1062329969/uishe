<?php

namespace App\Http\Controllers;

use App\Models\DownLog;
use App\Models\News;
use App\Models\UsersCollect;
use App\Models\VipOption;
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
        /*$posts_count  = Posts::countUserPosts( $user->id );
        //获取用户收藏
        $user_collect = Usermeta::getUserCollect( $user->id, 'count');
        //获取用户收藏
        $user_avatar = Usermeta::getUserAvatar( $user->id );*/
        //获取用户下载记录
        return view('home.user.index', [
            /*'posts_count' => $posts_count,
            'user_collect' => $user_collect,
            'user_avatar' => $user_avatar,*/
        ]);
    }

    public function collect(){
        $user = Auth::user();
        $user_collect = Usermeta::getUserCollect( 52233, false, 2);
//        $user_collect = Usermeta::getUserCollect( $user->id, false, 2);
        return view('home.user.collect', [
            'user_collect' => $user_collect,
        ]);
    }
    public function downlog(){
        $user = Auth::user();
        $downlog = DownLog::getDownLog(0, 52233, '');
//        $downlog = DownLog::getDownLog(0, $user->id, '', 4, 0);
        return view('home.user.downlog', [
            'downlog' => $downlog,
        ]);
    }

    public function orders(){
        $user = Auth::user();
        $user_order = WpOrders::getUserOrder( 1 );
        return view('home.user.downlog', [
            'user_order' => $user_order,
        ]);
    }

    public function loginout(){
        Auth::logout();
        redirect('/');
    }

    public function checkvip(){
        dd(Auth::user('users'));
    }

    public function buyvip(){
        $user = Auth::user();
        $vip_option = VipOption::where('status', VipOption::Option_Status_On)->get();
        return view('home.user.buyvip', ['vip_option' => $vip_option]);
    }


    public function dofav(Request $request, $do){
        $id = $request->id;
        if($do == 'add'){
            $new = News::find($id);
            UsersCollect::insert([
                'user_id' => Auth::user('users')->id,
                'collect_id' => $id,
                'title' =>$new['title'],
                'img' =>$new['cover_img'],
            ]);
        }elseif($do == 'del'){
            UsersCollect::where([
                ['user_id', '=', Auth::user('users')->id],
                ['collect_id', '=', $id],
            ])->delete();
        }elseif($do == 'check'){
            $uc = UsersCollect::where([
                ['user_id', '=', Auth::user('users')->id],
                ['collect_id', '=', $id],
            ])->first();
            if($uc){
                return response()->json(1, '收藏成功');
            }
        }
    }
}
