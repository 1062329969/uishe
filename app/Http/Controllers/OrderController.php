<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Orders;
use App\Models\OrdersVip;
use App\Models\VipOption;
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
                $vip = VipOption::where([
                        ['status', '=', VipOption::Option_Status_On],
                        ['level', '=', $request->level],
                        ['currency_type', '=', $currency_type],
                    ])
                    ->first();
                if(!$vip){
                    return redirect(route('buyvip'))->withErrors(['status'=>'未找到会员等级']);
                }
                $price = $vip['actual_total'];
                $pay_arr = [ [
                    'pay_type' =>$request->pay_type,
                    'order_type' =>Orders::Order_Type_Vip,
                ] ];
                break;
        }
        DB::beginTransaction();
        $order = Orders::create($user->id, $request->order_type, $request->pay_type, $order_name, $request->platform, $price, $pay_arr);

        switch ($request->order_type){
            case Orders::Order_Type_Vip:
                $res = OrdersVip::create($order, $vip, $currency_type);
                if($res){
                    DB::commit();
                }else{
                    DB::rollBack();
                }
                break;
        }
    }
}
