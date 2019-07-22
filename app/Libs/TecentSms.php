<?php
namespace App\Libs;

use Qcloud\Sms\SmsSingleSender;
use Illuminate\Support\Facades\Log;

/**
 * 腾讯讯短信接口
 */
class TecentSms
{
    private $appid;
    private $appkey;
    
    public function __construct()
    {
        $this->appid = env('TecentSms_AppID', '');
        $this->appkey = env('TecentSms_AppKey', '');
    }
    
    public function withdrawResult($mobile, $result)
    {
        $nationCode = '86';
        $templateId = 178032;     // 短信模板ID
        $smsSign = "千载";         // 签名
        
        $params[] = ($result) ? '通过' : '拒绝';
        
        // 指定模板ID单发短信
        try {
            $ssender = new SmsSingleSender($this->appid, $this->appkey);
            $result = $ssender->sendWithParam($nationCode, $mobile, $templateId, $params, $smsSign);  // 签名参数未提供或者为空时，会使用默认签名发送短信
            
            //$result = '{"result":0,"errmsg":"OK","ext":"","sid":"8:g22H3k7krosmikxdssH20180820","fee":2}';
            //Log::info("TecentSMS rsp:".$result);
            
            $rsp = json_decode($result);
            return ($rsp->result === 0);
        } catch(\Exception $e) {
            return false;
        }
    }


    //营销支付成功
    public function salesShopPaySuccess($mobile,$params=[], $nationCode='86')
    {
        $templateId = 314515;         // 短信模板ID
        $smsSign = "千载";         // 签名
        // 指定模板ID单发短信
        try {
            $ssender = new SmsSingleSender($this->appid, $this->appkey);
            $result = $ssender->sendWithParam($nationCode, $mobile, $templateId, $params, $smsSign);

            $rsp = json_decode($result);
            return ($rsp->result === 0);
        } catch(\Exception $e) {
            return false;
        }
    }

    //营销预订成功
    public function salesConfirmSuccess($mobile,$params=[], $nationCode='86')
    {
        $templateId = 315050;         // 短信模板ID
        $smsSign = "千载";      // 签名
        // 指定模板ID单发短信
        try {
            $ssender = new SmsSingleSender($this->appid, $this->appkey);
            $result = $ssender->sendWithParam($nationCode, $mobile, $templateId, $params, $smsSign);

            $rsp = json_decode($result);
            return ($rsp->result === 0);
        } catch(\Exception $e) {
            return false;
        }
    }

    //酒店支付成功 既 预订成功
    public function paySuccess($mobile,$params=[], $nationCode='86')
    {
        $templateId = 306220;         // 短信模板ID
        $smsSign = "千载";         // 签名
        // 指定模板ID单发短信
        try {
            $ssender = new SmsSingleSender($this->appid, $this->appkey);
            $result = $ssender->sendWithParam($nationCode, $mobile, $templateId, $params, $smsSign);

            $rsp = json_decode($result);
            return ($rsp->result === 0);
        } catch(\Exception $e) {
            return false;
        }
    }

    //酒店确认订单
    public function hotelConfirmSuccess($mobile,$params=[], $nationCode='86')
    {
        $templateId = 266088;         // 短信模板ID
        $smsSign = "千载";         // 签名
        // 指定模板ID单发短信
        try {
            $ssender = new SmsSingleSender($this->appid, $this->appkey);
            $result = $ssender->sendWithParam($nationCode, $mobile, $templateId, $params, $smsSign);

            $rsp = json_decode($result);
            return ($rsp->result === 0);
        } catch(\Exception $e) {
            return false;
        }
    }

    //酒店预定成功
    public function confirmOrder($mobile, $params=[], $nationCode='86')
    {
        $templateId = 175164;         // 短信模板ID
        $smsSign = "千载";         // 签名
        // 指定模板ID单发短信
        try {
            $ssender = new SmsSingleSender($this->appid, $this->appkey);
            $result = $ssender->sendWithParam($nationCode, $mobile, $templateId, $params, $smsSign);

            $rsp = json_decode($result);
            return ($rsp->result === 0);
        } catch(\Exception $e) {
            return false;
        }
    }
    //酒店预定失败
    public function refund($mobile,$params=[],$nationCode = '86')
    {
        $templateId = 66065;     // 短信模板ID
        $smsSign = "千载";         // 签名

        
        try {
            $ssender = new SmsSingleSender($this->appid, $this->appkey);
            $result = $ssender->sendWithParam($nationCode, $mobile, $templateId, $params, $smsSign);
            
            $rsp = json_decode($result);
            return ($rsp->result === 0);
        } catch(\Exception $e) {
            return false;
        }
    }

    //酒店退款成功
    public function confirmRefund($mobile,$params=[],$nationCode = '86')
    {
        $templateId = 263296;     // 短信模板ID
        $smsSign = "千载";         // 签名


        try {
            $ssender = new SmsSingleSender($this->appid, $this->appkey);
            $result = $ssender->sendWithParam($nationCode, $mobile, $templateId, $params, $smsSign);

            $rsp = json_decode($result);
            return ($rsp->result === 0);
        } catch(\Exception $e) {
            return false;
        }
    }


    //发送验证码
    public function sendSmsCode($mobile, $sms_code)
    {
        $nationCode = '86';
        $templateId = 32386;     // 短信模板ID   验证码：{1}，有效期{2}分钟。
        $smsSign = "千载";         // 签名
        
        $params = [$sms_code, 10];
        
        try {
            $ssender = new SmsSingleSender($this->appid, $this->appkey);
            $result = $ssender->sendWithParam($nationCode, $mobile, $templateId, $params, $smsSign);
            
            $rsp = json_decode($result);
            return ($rsp->result === 0);
        } catch(\Exception $e) {
            return false;
        }
    }

    //体验预定成功
    public function expConfirmOrder($mobile,$params = [],$nationCode = '86')
    {
        $templateId = 238526;     // 短信模板ID
        $smsSign = "千载";         // 签名

        try {
            $ssender = new SmsSingleSender($this->appid, $this->appkey);
            $result = $ssender->sendWithParam($nationCode, $mobile, $templateId, $params, $smsSign);

            $rsp = json_decode($result);
            return ($rsp->result === 0);
        } catch(\Exception $e) {
            return false;
        }
    }

    //体验支付订单成功
    public function expPaySuccess($mobile,$params = [],$nationCode = '86')
    {
        $templateId = 305878;     // 短信模板ID
        $smsSign = "千载";         // 签名

        try {
            $ssender = new SmsSingleSender($this->appid, $this->appkey);
            $result = $ssender->sendWithParam($nationCode, $mobile, $templateId, $params, $smsSign);

            $rsp = json_decode($result);
            return ($rsp->result === 0);
        } catch(\Exception $e) {
            return false;
        }
    }

    //体验确认订单成功
    public function expConfirmSuccess($mobile,$params = [],$nationCode = '86')
    {
        $templateId = 305891;     // 短信模板ID
        $smsSign = "千载";         // 签名

        try {
            $ssender = new SmsSingleSender($this->appid, $this->appkey);
            $result = $ssender->sendWithParam($nationCode, $mobile, $templateId, $params, $smsSign);

            $rsp = json_decode($result);
            return ($rsp->result === 0);
        } catch(\Exception $e) {
            return false;
        }
    }

    //体验预定失败
    public function expRefund($mobile,$params = [],$nationCode = '86')
    {

        $templateId = 125871;     // 短信模板ID
        $smsSign = "千载";         // 签名

        try {
            $ssender = new SmsSingleSender($this->appid, $this->appkey);
            $result = $ssender->sendWithParam($nationCode, $mobile, $templateId, $params, $smsSign);

            $rsp = json_decode($result);
            return ($rsp->result === 0);
        } catch(\Exception $e) {
            return false;
        }
    }

    //酒店新订单通知商家
    public function newOrderNotify($mobile,$params = [],$nationCode = '86')
    {
        $templateId = 246676;     // 短信模板ID
        $smsSign = "千载";         // 签名

        try {
            $ssender = new SmsSingleSender($this->appid, $this->appkey);
            $result = $ssender->sendWithParam($nationCode, $mobile, $templateId, $params, $smsSign);

            $rsp = json_decode($result);
            return ($rsp->result === 0);
        } catch(\Exception $e) {
            return false;
        }
    }

    //用户退款通知商家
    public function newRefundNotify($mobile,$params = [],$nationCode = '86')
    {
        $templateId = 260772;     // 短信模板ID
        $smsSign = "千载";         // 签名

        try {
            $ssender = new SmsSingleSender($this->appid, $this->appkey);
            $result = $ssender->sendWithParam($nationCode, $mobile, $templateId, $params, $smsSign);

            $rsp = json_decode($result);
            return ($rsp->result === 0);
        } catch(\Exception $e) {
            return false;
        }
    }

    //体验新订单提醒
    public function newExpNotify($mobile,$params = [],$nationCode = '86')
    {
        $templateId = 246484;     // 短信模板ID
        $smsSign = "千载";         // 签名

        try {
            $ssender = new SmsSingleSender($this->appid, $this->appkey);
            $result = $ssender->sendWithParam($nationCode, $mobile, $templateId, $params, $smsSign);

            $rsp = json_decode($result);
            return ($rsp->result === 0);
        } catch(\Exception $e) {
            return false;
        }
    }

    public function sysNotify($mobile, $log_id)
    {
        $nationCode = '86';
        $templateId = 260682;     // 短信模板ID
        $smsSign = "千载";         // 签名

        $params = [$log_id];

        try {
            $ssender = new SmsSingleSender($this->appid, $this->appkey);
            $result = $ssender->sendWithParam($nationCode, $mobile, $templateId, $params, $smsSign);

            $rsp = json_decode($result);
            return ($rsp->result === 0);
        } catch(\Exception $e) {
            return false;
        }
    }

    //拒绝退款
    public function refusalRefund($mobile,$params = [],$nationCode = '86')
    {
        $templateId = 305991;     // 短信模板ID
        $smsSign = "千载";         // 签名

        try {
            $ssender = new SmsSingleSender($this->appid, $this->appkey);
            $result = $ssender->sendWithParam($nationCode, $mobile, $templateId, $params, $smsSign);

            $rsp = json_decode($result);
            return ($rsp->result === 0);
        } catch(\Exception $e) {
            return false;
        }
    }
}