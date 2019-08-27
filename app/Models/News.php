<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    //
    const Status_Normal = 'normal'; //正常
    const Status_Offline = 'offline'; //下线
    const Status_Cache = 'cache'; //缓存

    const Comment_Status_On = 'on';
    const Comment_Status_Off = 'off';

    const Down_Type_Close = 'close'; // 关闭
    const Down_Type_Every = 'every'; // 所有用户免费下载
    const Down_Type_Login  = 'login'; // 登录后免费下载
    const Down_Type_Integral = 'integral'; // 积分下载
    const Down_Type_Vip = 'vip'; // VIP下载

    protected $table = 'news';
    protected $primaryKey = 'id';





    public static function getDownType($key){
        $arr = [
            '0' => self::Down_Type_Close,
            '2' => self::Down_Type_Every,
            '3' => self::Down_Type_Login ,
            '4' => self::Down_Type_Integral,
            '5' => self::Down_Type_Vip,
        ];
        return $arr[$key];

    }

    public static function getRecommendNews(){
        $recommend = News::where([
                ['status', '=', 'on'],
                ['recommend', '=', 'on'],
            ])
            ->select(['title', 'id'])
            ->get()
            ->toArray();
        return $recommend;
    }

}
