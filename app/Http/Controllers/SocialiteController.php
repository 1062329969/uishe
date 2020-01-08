<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UsersQQ;
use App\Models\UsersWeibo;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Overtrue\LaravelSocialite\Socialite;
use function Sodium\randombytes_random16;

class SocialiteController extends Controller
{

    public function socialite_login(Request $request, $socialite)
    {
        return Socialite::driver($socialite)->redirect();
    }

    public function socialite_bind(Request $request, $socialite)
    {
        $user = Auth::user();
        $url = route($socialite . '_bind').'?uid='.$user->id;
        return Socialite::driver($socialite)->with(['aaa'=>'bbbb'])->setRedirectUrl($url)->redirect();
    }

    public function qq_bind(Request $request)
    {
        $uid = $request->uid;
        $user = Socialite::driver('qq')->user();
        $qq_info = UsersQQ::where(['access_token' => $user->token, 'openid' => $user->id])->first();
        if (!empty($qq_info)) {
            return redirect(route('user'))->withErrors(['账号已经被绑定']);
        } else { // 设置绑定
            $user_qq = new UsersQQ();
            $user_qq->openid = $user->id;
            $user_qq->access_token = $user->token;
            $user_qq->user_id = $uid;
            $res = $user_qq->save();
            if ($res) {
                return  redirect(route('user'));
            } else {
                return redirect(route('user'))->withErrors(['账户绑定失败']);
            }
        }
    }

    public function weibo_bind(Request $request)
    {
        $uid = $request->uid;
        $user = Socialite::driver('weibo')->user();
        $qq_info = UsersWeibo::where(['access_token' => $user->token, 'openid' => $user->id])->first();
        if (!empty($qq_info)) {
            return redirect(route('user'))->withErrors(['账号已经被绑定']);
        } else { // 设置绑定
            $user_weibo = new UsersWeibo();
            $user_weibo->openid = $user->id;
            $user_weibo->access_token = $user->token;
            $user_weibo->user_id = $uid;
            $res = $user_weibo->save();
            if ($res) {
                return redirect(route('user'));
            } else {
                return redirect(route('user'))->withErrors(['账户绑定失败']);
            }
        }
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

            if ($user_info = User::create(['name'=>'Q'.random_string(10),'nicename' => $user->name, 'avatar_url' => $user->avatar])) {
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

            if ($user_info = User::create(['name'=>'W'.random_string(10),'nicename' => $user->name, 'avatar_url' => $user->avatar])) {
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


}
