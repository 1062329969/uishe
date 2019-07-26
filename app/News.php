<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    //
    const Status_Normal = 'normal'; //正常
    const Status_Offline = 'offline'; //下线
    const Status_Cache = 'cache'; //缓存

    const Comment_Status_On = 'on';
    const Comment_Status_Off = 'off';

    protected $table = 'news';
    protected $primaryKey = 'id';
}
