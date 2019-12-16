<?php

namespace App\Http\Controllers;



use App\Libs\Pexels;

class CronController extends Controller
{
    const Pexels_Key = '563492ad6f91700001000001c412d9cbdfd345a19d33b97ecc12f3e1';

    public function __construct()
    {
        set_time_limit(3600);   // 最大执行时间 3600 一小时。
    }

    public function daily(){
        $pexels = new Pexels(self::Pexels_Key);

        $img = $pexels->get_photos(3353994);
//dd(json_decode($img->getBody()));
//        $img = $pexels->search('web');
        $img_list = json_decode($img->getBody())->photos;
        return response()->json($img_list);
    }
}
