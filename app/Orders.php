<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    //
    protected $table = 'wp_chenxing_orders';
    protected $primaryKey = 'id';

    public static function getUserOrder($user_id, $page = 10) {
        return $user_order = Orders::where('user_id', $user_id)->paginate($page);
    }
}
