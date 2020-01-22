<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Orders;
use App\Models\OrdersVip;
use App\Models\User;
use App\Models\Usercredit;
use App\Models\VipOption;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function set_order(OrderRequest $request){
        $user = Auth::user();
        if($request->pay_type == Orders::Order_Pay_Type_Credit){
            $currency_type = Orders::Currency_Type_Credit;
        }else{
            $currency_type = Orders::Currency_Type_RMB;
        }
        switch ($request->order_type){
            case Orders::Order_Type_Vip:
                $order_name = '购买Vip';
                $check = OrdersVip::checkVipOrder($request, $currency_type, $user);

                if($currency_type == Orders::Currency_Type_Credit && $user->credit < $check['price']){
                    return redirect(route('buyvip'))->withErrors(['status'=>'积分余额不足']);
                }

                $price = $check['price'];
                $pay_arr = $check['pay_arr'];
                $vip = $check['vip'];
                $remark = $check['remark'];
                break;
        }
        DB::beginTransaction();
        $order = Orders::createOrder($user->id, $request->order_type, $request->pay_type, $order_name, $request->platform, $price, $pay_arr, $remark);

        switch ($request->order_type){
            case Orders::Order_Type_Vip:
                $res = OrdersVip::create($order, $vip, $currency_type, $request->pay_type);
                if($res){

                    if($currency_type == Orders::Currency_Type_Credit){
                        $user->credit = $user->credit - $price;
                        $user->save();

                        $orders = Orders::where('id', $order->id)->first();
                        $orders->pay_at = Carbon::now()->toDateTimeString();
                        $orders->save();
                        $ret = OrdersVip::onPay($orders);
                        if($ret){
                            Usercredit::insert([
                                'user_id' => $user->id,
                                'content' => '购买Vip '.$vip['name'],
                                'from' => 'new',
                                'edit_credit' => -$price
                            ]);
                            $user = User::find($user->id);
                            Auth::guard('users')->login($user);
                            DB::commit();
                            return redirect(route('user'))->with(['status'=>'购买成功']);
                        }else{
                            DB::rollBack();
                            return redirect(route('buyvip'))->withErrors(['status'=>'购买失败，请联系站长']);
                        }

                    }
                    DB::commit();
                    $user = User::find($user->id);
                    Auth::guard('users')->login($user);
                    return redirect(route('alipay_getpay', ['order_no' => $order->order_no]));
                }else{
                    return redirect(route('buyvip'))->withErrors(['status'=>'创建订单失败，请联系站长']);
                    DB::rollBack();
                }
                break;
        }
    }
}
