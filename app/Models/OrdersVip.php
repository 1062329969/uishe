<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OrdersVip extends Model
{
    //
    protected $table = 'orders_vip';
    protected $primaryKey = 'id';

    public $incrementing = false;
    protected $hidden = ['updated_at', 'deleted_at'];

    public static function create($order, $vip, $currency_type){
        $request->pay_type
        $actual_price = self::getActualPrice($order, $vip, $currency_type);
        $res = OrdersVip::insert([
            'order_id' => $order->id,
            'user_id' => $order->pay_user_id,
            'vip_level' => $vip['level'],
            'vip_id' => $vip['id'],
            'vip_name' => $vip['name'],
            'orders_pay_code' => $currency_type == Orders::Currency_Type_Credit ? '' : $order->code[Orders::Order_Pay_Type_Alipay]
        ]);
        if($res){
            $res = Orders::where('id', $order->id)->update(['actual_price' => $actual_price]);
            if($res){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }


    }

    private static function getActualPrice($order, $vip, $currency_type){
        $user = Auth::user();
        //打折做活动使用
        if ( $user->user_type == 0 ){
            $price = $vip['actual_total'];
        }else{
            $now_vip = VipOption::where([
                ['status', '=', VipOption::Option_Status_On],
                ['level', '=', $user->user_type],
                ['currency_type', '=', $currency_type],
            ])->first();
            $price = $vip['actual_total'] - $now_vip->actual_total;
        }

        return $price;
    }

    //下单之前的检查
    public static function checkVipOrder($request, $currency_type, $user){
        $vip = VipOption::where([
            ['status', '=', VipOption::Option_Status_On],
            ['level', '=', $request->vip_type],
            ['currency_type', '=', $currency_type],
        ])
            ->first()->toArray();
        if(!$vip){
            return redirect(route('buyvip'))->withErrors(['status'=>'未找到会员等级']);
        }
        if($user->user_type == $request->vip_type){
            return redirect(route('buyvip'))->withErrors(['status'=>'您已购买此会员，无需重复购买']);
        }

        $day = Carbon::now()->diffInDays($user->startTime, false);
        if($day <= VipOption::Option_Spread_Day && $day != 0){
            $now_vip = VipOption::where([
                ['status', '=', VipOption::Option_Status_On],
                ['level', '=', $user->user_type],
                ['currency_type', '=', $currency_type],
            ])->first();
            $price = $vip['actual_total'] - $now_vip->actual_total;
            $remark = $now_vip->name.'补差价'.$vip['name'];
        }else{
            $price = $vip['actual_total'];
            $remark = '';
        }

        $pay_arr = [ [
            'pay_type' =>$request->pay_type,
            'order_type' =>Orders::Order_Type_Vip,
        ] ];

        return ['price' => $price, 'pay_arr' => $pay_arr, 'vip' =>$vip, 'remark' => $remark];
    }

    //支付回调逻辑
    public static function onPay($order){
        //判断是不是已支付订单
        if($order->status==1) {
            return true;
        } else {
            $order_vip = OrdersVip::where('order_id', $order->id)->first();
            $user_info = User::where([
                ['id', '=', $order['pay_user_id']]
            ])->first();
            if($user_info['user_type'] == 0){
                $update_level = User::where([
                    ['id', '=', $order['pay_user_id']]
                ])
                    ->update([
                        'user_type' => $order_vip['vip_level'],
                        'startTime' => Carbon::now()->toDateString(),
                        'endTime' => Carbon::now()->addYear()->toDateString(),
                    ]);
                $starttime =  Carbon::now()->toDateString();
                $endtime =  Carbon::now()->addYear()->toDateString();
            }else{
                $update_level = User::where([
                        ['id', '=', $order['pay_user_id']]
                    ])
                    ->update([
                        'user_type' => $order_vip['vip_level'],
                        'startTime' => Carbon::now()->toDateString(),
                        'endTime' => Carbon::now()->addYear()->toDateString(),
                    ]);
                $starttime =  Carbon::now()->toDateString();
                $endtime =  Carbon::now()->addYear()->toDateString();
            }
            if(!$update_level) {
                return false;
            } else {
                UsersVipLog::insert([
                    'user_id' => $order['pay_user_id'],
                    'level' => $order_vip['vip_level'],
                    'starttime' => $starttime,
                    'endtime' => $endtime,
                ]);
                return true;
            }
        }
    }

    //与标签多对多关联
    public function vips()
    {
        return $this->hasOne('App\Models\VipOption','id','vip_id');
    }

}
