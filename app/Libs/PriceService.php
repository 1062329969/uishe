<?php
/**
 * Created by PhpStorm.
 * User: nigger
 * Date: 2018/11/21
 * Time: 1:54 PM
 */
namespace App\Libs;
use \App\Order;
use App\Orders_pay;
use App\Sub_orders;
use App\UserLevel;
use \Illuminate\Support\Facades\DB;
use App\Libs\WechatSDK;
use APP\WxApp;
class PriceService
{
    private $uid;
    private $order_id;


    public static function refundPriceById($id, $uid=0,$type ='', $price = 0,$wxapp_id='')
    {

        if(is_array($id)){
            $order_info = $id;
            $uid = $order_info['userid'];
            $id = $order_info['id'];
        }
        if(empty($price) || $price < 0){
            return response()->error('999',"缺少price");
        }
        if(empty($type)){
            return response()->error('999',"缺少type");
        }
        if (empty($id)) {
            return response()->error('999',"缺少id");
        }

        DB::beginTransaction();
        try {
            /**
             * 获取订单信息 只有状态为1：已预订时才能申请退款
             */
            $order_info = Sub_orders::where('id' , $id)->where('pay_user_id',$uid)->where('type',$type)->whereIn('status',[1,5])->first();

            if (empty($order_info)) {
                return response()->error('999',"没有此订单信息");
            }

            $pay = Orders_pay::where('sub_order_id',$order_info->id)->get(['total','type']);
            if (empty($pay)) {
                return response()->error('999',"没有支付方式，请联系管理员");
            }

            //酒店退款
            if ($order_info->type == Order::Sub_Order_Type_Hotel) {

                foreach ($pay as $item)
                {
                    if($item->total < $price)
                    {
                        return response()->error('999',"退款金额过大,请重新输入");
                    }
                    //根据支付方式原路退款
                    switch ($item->type) {
                        case 4://微信
                            $wxApp = WxApp::where('wxapp_id', $wxapp_id)->firstOrFail();

                            $wxSDK = new WechatSDK($wxApp->getWxPayParams());
                            //微信支付id     $pay->total  实际支付总价格     $price  这个退款价格

                            $result = $wxSDK->reqRefund($order_info->id, $pay->total, $price);
                            break;
                        case 5:
                            if($order_info->membercard_id < 0)
                            {
                                return response()->error('999',"退款失败，没有消费金支付");
                            }
                            //会员权益
                            $result = UserLevel::where('id',$order_info->membercard_id)->increment('balance',$price);
                        default :
                            break;
                    }
                }
            }

            DB::commit();
            return  $result;
        } catch (\Exception $e) {
            DB::rollBack();
            return  response()->error(999, '退款失败');
        }
    }
}