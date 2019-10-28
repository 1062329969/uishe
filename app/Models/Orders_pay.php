<?php

namespace App\Models;


use \Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Config;

use App\Libs\WechatSDK;
use Illuminate\Support\Facades\Log;


class Orders_pay extends Model
{
    protected $table = 'orders_pay';
    protected $primaryKey = 'id';
}
