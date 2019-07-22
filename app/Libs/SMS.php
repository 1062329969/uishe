<?php
namespace App\Libs;

/**
 * 短信接口
 */
class SMS
{
    const Channel_Ali = 'AliSms';
    const Channel_Tecent = 'TecentSms';
    
    const Template_withdraw = 'withdraw';               // 提前结果短信
    const Template_confirm_order = 'confirm_order';     // 确认订单
    
    private $channel;
    
    public function __construct()
    {
        $sms_channel = env('SMS_Channel', self::Channel_Ali);
        
        switch ($sms_channel)
        {
            case self::Channel_Ali:
                $this->channel = new AliSms();
                break;
            case self::Channel_Tecent:
                $this->channel = new TecentSms();
                break;
        }
    }

    /**
     * 营销商家通知短信
     * @param $mobile
     * @param $result
     * @return mixed
     */
    public function salesShopPaySuccess($mobile, $result, $orderId)
    {
        $params = [$result, $orderId];
        return $this->channel->salesShopPaySuccess($mobile, $params);
    }

    /**
     * 营销用户预订通知短信
     * @param $mobile
     * @param $result
     * @return mixed
     */
    public function salesConfirmSuccess($mobile, $result, $serviceMobile)
    {
        $params = [$result, $serviceMobile];
        return $this->channel->salesConfirmSuccess($mobile, $params);
    }

    // $result true 提现申请通过；false 申请被拒绝。
    public function withdrawResult($mobile, $result)
    {
        return $this->channel->withdrawResult($mobile, $result);
    }

    //'【千载】{1}您好：{2}，工作人员将进行房间确认，预计5到15分钟，请您耐心等待，预祝您入住愉快，客服电话：{3}。'
    public function paySuccess($mobile, $username, $orderid, $hotel_mobile)
    {
        $params = [$username, $orderid, $hotel_mobile];
        return $this->channel->paySuccess($mobile, $params);
    }

    //'【千载】{用户名}您好：您已成功预订｛(1、房型，入住时间:入住时间共 晚数 晚 间数 间)},共支付总金额{总金额}元，祝您入住愉快。客服电话：{客服电话}。'
    public function hotelConfirmSuccess($mobile, $username, $room_type, $total, $servie_tel)
    {
        $params = [$username, $room_type, $total, $servie_tel];
        return $this->channel->hotelConfirmSuccess($mobile, $params);
    }

    // 尊敬的用户您好：您已成功预订{1}，数量{2}间，您的入住时间是:{3}共{4}晚，订单号{5},祝您入住愉快。客服电话：{6}。
    public function confirmOrder($mobile, $room, $nums, $checkin_date, $days, $order_id, $servie_tel)
    {
        $params = [$room, $nums, $checkin_date, $days, $order_id, $servie_tel];
    
        return $this->channel->confirmOrder($mobile, $params);
    }

    // 尊敬的用户您好：您预订的{1}，未能预订成功，订单号{2}，退款将在7个工作日内退回到您的账户，请注意查收，如需帮助，请拨打客服电话：{3}
    public function refund($mobile, $room, $order_id, $servie_tel)
    {
        return $this->channel->refund($mobile, $room, $order_id, $servie_tel);
    }

    //'尊敬的用户您好：您所预定的订单号为{1}，{房型1、房型2} ,订单金额：{3}的订单已成功取消。退款将在7个工作日内退回到您的账户，请注意查收，如需帮助，请拨打专属客服电话：{4}。
    public function confirmRefund($mobile, $orderid, $roominfo, $total, $service_tel)
    {
        $params = [$orderid, $roominfo, $total, $service_tel];
        return $this->channel->confirmRefund($mobile,$params);
    }

    // 验证码：{1}，有效期{2}分钟。 
    public function sendSmsCode($mobile, $sms_code)
    {
        return $this->channel->sendSmsCode($mobile, $sms_code);
    }

    //尊敬的用户您好：您已报名{1}，报名人数:{2}，订单号:{3}，请准时参加活动，请保持手机畅通，如需帮助，请拨打客服电话:{4}
    public function expConfirmOrder($mobile,$expname,$nums,$orderid,$servie_tel)
    {
        $params = [$expname,$nums,$orderid,$servie_tel];
        return $this->channel->expConfirmOrder($mobile,$params);
    }

    //{1}您好：{2}的体验订单支付成功，请您耐心等候订单确认，预计5到15分钟，预祝您体验愉快，客服电话：{3}。
    public function expPaySuccess($mobile, $username, $orderid, $hotel_mobile)
    {
        $params = [$username, $orderid, $hotel_mobile];
        return $this->channel->expPaySuccess($mobile,$params);
    }

    //尊敬的用户您好：您已成功支付{1}，报名人数:{2}，总金额:{3}元，订单号:{4}，请准时参加活动，请保持手机畅通，如需帮助，请拨打客服电话:{5}。
    public function expConfirmSuccess($mobile,$expname,$nums,$total,$orderid,$service_tel)
    {
        $params = [$expname,$nums,$total,$orderid,$service_tel];
        return $this->channel->expConfirmSuccess($mobile,$params);
    }

    //抱歉，未能成功报名，订单号:{1}，退款将在7个工作日内退回到您的账户，如需帮助，请拨打客服电话:{2}。
    public function expRefund($mobile,$orderid,$service_tel)
    {
        $params = [$orderid,$service_tel];
        return $this->channel->expRefund($mobile,$params);
    }

    //您有新订单，请及时处理，订单信息：{房型1，入住时间12月11日,2晚2间；房型2，入住时间12月11日,2晚2间；}联系人：{2}，电话：{3}，订单号：{4}，请到千载后台确认订单。',
    public function newOrderNotify($service_tel, $typyinfo, $user, $mobile, $orderid)
    {
        $params = [$typyinfo, $user, $mobile, $orderid];
        return $this->channel->newOrderNotify($service_tel,$params);
    }

    //用户退款申请，请及时处理。订单信息：{1}，订单总金额：{2}，联系人：{3}，电话：{4}，订单号：{5}，请到千载后台确认退款。'
    public function newRefundNotify($service_tel, $roominfo, $total, $user, $mobile, $orderid)
    {
        $params = [$roominfo, $total, $user, $mobile, $orderid];
        return $this->channel->newRefundNotify($service_tel,$params);
    }

    //有新的报名！{1}，联系人:{2}，手机号:{3}，班期:{4}，报名人数:{5}，实收金额:{6}元，订单号:{7}。
    public function newExpNotify($service_tel,$exptitle,$user,$mobile,$schedule,$nums,$total,$orderid)
    {
        $params = [$exptitle,$user,$mobile,$schedule,$nums,$total,$orderid];
        return $this->channel->newExpNotify($service_tel,$params);
    }

    //尊敬的用户您好：您已成功取消{1}的订单，订单号{2}，退款将在7个工作日内退回到您的账户，请注意查收，如需帮助，请拨打专属客服电话：{3}
    public function refusalRefund($service_tel, $order_title, $orderid, $mobile)
    {
        $params = [$order_title, $orderid, $service_tel];
        return $this->channel->refusalRefund($mobile,$params);
    }

    // 系统事件通知。系统异常：id={12345}
    public function sysNotify($mobile, $log_id)
    {
        if ($this->checkMobile($mobile) == false) {
            return false;
        }
        
        return $this->channel->sysNotify($mobile, $log_id);
    }
    
    private function checkMobile($mobile)
    {
        if (empty($mobile)) {
            return false;
        }
        
        $mobile = trim($mobile);
        
        if ((strlen($mobile) != 11) || (is_numeric($mobile) == false)) {
            return false;
        }
        
        return true;
    }
}