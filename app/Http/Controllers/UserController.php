<?php

namespace App\Http\Controllers;

use App\Models\DownLog;
use App\Models\News;
use App\Models\Usercredit;
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
        $user_collect = UsersCollect::getCollect(0, 52233, '');
//        $user_collect = Usermeta::getUserCollect( $user->id, false, 2);
        return view('home.user.collect', [
            'user_collect' => $user_collect,
        ]);
    }
    public function downlog(){
        $user = Auth::user();
        $downlog = DownLog::getDownLog(0, 52233, '');
        foreach ($downlog as &$item){
            $item['news_info'] = News::where('id', $item['new_id'])->value('cover_img');
        }
//        $downlog = DownLog::getDownLog(0, $user->id, '', 4, 0);
        return view('home.user.downlog', [
            'downlog' => $downlog,
        ]);
    }

    public function creditlog(){
        $user = Auth::user();
//        dd($user->id);
        $creditlog = Usercredit::getCredit($user->id, '');
        foreach ($creditlog as &$item){
            $item['news_info'] = News::where('id', $item['new_id'])->value('cover_img');
        }
//        $downlog = DownLog::getDownLog(0, $user->id, '', 4, 0);
        return view('home.user.creditlog', [
            'creditlog' => $creditlog,
        ]);
    }
    public function orders(){
        $user = Auth::user();
        $user_order = WpOrders::getUserOrder( 1 );
        return view('home.user.downlog', [
            'user_order' => $user_order,
        ]);
    }

    public function selfinfo(Request $request){
        $tab = $request->tab ?? 'edit_info';

        $user = Auth::user();
//        dd($user->id);
        $selfinfo = User::find($user->id);
        return view('home.user.selfinfo', [
            'selfinfo' => $selfinfo,
            'tab' => $tab
        ]);
    }

    public function saveself(Request $request){
        if($request->tab == 'edit_info'){
            $this->validate($request,[
                'display_name'  => 'required|string',
                'email'  => 'required|string',
            ]);
            $res = User::where('id', Auth::user()->id)->update([
                'display_name' => $request->display_name,
                'email' => $request->email,
            ]);
            if($res){
                return redirect()->to(route('selfinfo'))->with(['status'=>'保存成功']);
            }else{
                return redirect()->to(route('selfinfo'))->with(['status'=>'保存失败']);
            }
        }
    }

    public function loginout(){
        Auth::logout();
       return  redirect(route('login'));
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
            $uc = UsersCollect::insert([
                'user_id' => Auth::user('users')->id,
                'collect_id' => $id,
                'title' =>$new['title'],
                'img' =>$new['cover_img'],
            ]);
        }elseif($do == 'del'){
            $uc = UsersCollect::where([
                ['user_id', '=', Auth::user('users')->id],
                ['collect_id', '=', $id],
            ])->delete();
        }elseif($do == 'check'){
            $user = Auth::user('users');
            if(!$user){
                return response()->json(['status' => 0]);
            }
            $uc = UsersCollect::where([
                ['user_id', '=', $user->id],
                ['collect_id', '=', $id],
            ])->first();
        }
        if($uc){
            return response()->json(['status' => 1]);
        }else{
            return response()->json(['status' => 0]);
        }
    }
}
