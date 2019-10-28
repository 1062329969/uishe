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

    public static function create($order, $vip, $currency_type){

        $actual_total = self::getActualTotal($order, $vip, $currency_type);
        $res = OrdersVip::insert([
            'order_id' => $order->id,
            'user_id' => $order->pay_user_id,
            'vip_level' => $vip['level'],
            'vip_id' => $vip['id'],
            'vip_name' => $vip['name'],
            'orders_pay_code' => $order->code[Orders::Order_Pay_Type_Alipay]
        ]);
        if($res){
            $res = Orders::where('id', $order->id)->update(['actual_total' => $actual_total]);
            if($res){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }


    }

    private static function getActualTotal($order, $vip, $currency_type){
        //打折做活动使用
            return $vip['actual_total'];
    }

    public static function onPay($order){
        //判断是不是已支付订单
        if($order->status==1) {
            return true;
        } else {
            $order_vip = OrdersVip::where('order_id', $order->id)->first();
            $update_level = User::where([
                    ['id', '=', $order['pay_user_id']]
                ])
                ->update(['user_type' => $order_vip['vip_level']]);
            if(!$update_level) {
                return false;
            } else {
                return true;
            }
        }
    }
}
