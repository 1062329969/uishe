<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Orders extends Model
{
    //
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'order_no', 'pay_user_id', 'order_type', 'pay_type', 'order_name', 'total', 'formId', 'platform', 'mobile', 'contact', 'remark', 'coupon', 'membercard_id'];
    const Order_Type_Vip = 'vip';

    const Order_Pay_Type_Credit = 'pay_credit';
    const Order_Pay_Type_Alipay = 'pay_alipay';
    const Order_Pay_Type_Wxpay = 'pay_wxpay';

    const Currency_Type_RMB = 'rmb';
    const Currency_Type_Credit = 'credit';

    public $code;

    public static function createOrder($user_id, $order_type, $pay_type, $name, $platform, $price, $pay_array, $wxapp_formid=NULL, $mobile = null, $contact = null, $remark = null, $coupon = null, $membercard_id = 0)
    {
        $id_prefix = '';
        switch ($order_type) {
            case self::Order_Type_Vip:
                $id_prefix = 'VIP';
                break;
        }

        $order = new Orders();
        $order->order_no = self::getOrderId($id_prefix);
        $order->pay_user_id = $user_id;
        $order->order_type = $order_type;
        $order->pay_type = $pay_type;
        $order->order_name = $name;
        $order->total = $price;
        $order->formId = $wxapp_formid;
        $order->platform = $platform;
        $order->mobile = $mobile;
        $order->contact = $contact;
        $order->remark = $remark;
        $order->coupon = $coupon;
        $order->membercard_id = $membercard_id;
        $order->save();
        if ($pay_array) {
            foreach ($pay_array as $val) {
//                dump($val['pay_type'], $order->id, $val['order_type'], $val['membercard_id'] ?? NULL, self::getOrderId($id_prefix . 'OP'));die;
                $order_pay = new Orders_pay();
                $order_pay->pay_type = $val['pay_type'];
                $order_pay->order_id = $order->id;
                $order_pay->order_type = $val['order_type'];
                $order_pay->membercard_id = $val['membercard_id'] ?? NULL;
                $order_pay->orders_pay_code = self::getOrderId($id_prefix . 'OP');
                $order_pay->save();
                $order->code[$order_pay->pay_type] = $order_pay->orders_pay_code;
            }
        }
        return $order;
    }

    public static function getOrderId($Prefix =null){

        $orderSn = $Prefix . date('ymdHis',time()) . (float)sprintf('%.0f',microtime()) . rand(100,999);
        return $orderSn;
    }
}
