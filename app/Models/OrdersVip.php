<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdersVip extends Model
{
    //
    protected $table = 'orders_vip';
    protected $primaryKey = 'id';

    public $incrementing = false;
    protected $hidden = ['updated_at', 'deleted_at'];

    public static function create($order, $vip){

        OrdersVip::insert([
            'order_id' => $order->id,
            'user_id' => $order->pay_user_id,
            'vip_level' => $vip['level'],
            'vip_id' => $vip['id'],
            'vip_name' => $vip['name'],
            'orders_pay_code' => $order->code
        ]);

    }
}
