<?php

namespace App\Libs;

use App\ApiLog;
use App\ErrorLog;
use App\Order;
use App\WxApp;
use App\WxAppOpen;
use App\WxAppUser;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

use App\RefundLog;

require_once(__DIR__ . '/alipay_sdk/AopSdk.php');

/**
 * 支付宝sdk       https://www.jb51.net/article/136826.htm       https://www.jianshu.com/p/a51ed4142314
 * 支付宝与laravel全局函数（encrypt、decrypt）命名冲突解决： http://www.chinacion.cn/article/2128.html
 */
class AliPaySDK
{
    const URL = 'https://openapi.alipay.com/gateway.do';

    const Trade_Status_Success   = 'TRADE_SUCCESS';     // 用户付款成功。
    const Trade_Status_Finished  = 'TRADE_FINISHED';    // 交易结束，不可退款。
    const Trade_Status_Closed    = 'TRADE_CLOSED';      // 未付款的订单超时过期。
    const Trade_Status_WaitToPay = 'WAIT_BUYER_PAY';    // 订单创建完成，等待用户付款。

    private $aop;
    public  $notify_url;

    public function __construct($appid, $rsaPrivateKey, $alipayrsaPublicKey)
    {
        $this->aop = new \AopClient();

        $this->aop->gatewayUrl = self::URL;
        $this->aop->appId      = $appid;
        $this->aop->format     = "json";
        $this->aop->signType   = "RSA2";
        $this->aop->apiVersion = "1.0";

//        $this->aop->rsaPrivateKeyFilePath = 'file_path';
//        $this->aop->rsaPublicKey = 'file_path';

        $this->aop->rsaPrivateKey      = $rsaPrivateKey;
        $this->aop->alipayrsaPublicKey = $alipayrsaPublicKey;

        $this->notify_url = Config::get('app.alipay_notify');
    }

    // https://docs.open.alipay.com/204/105465/   https://docs.open.alipay.com/api_1/alipay.trade.app.pay
    public function createOrder($order_title, $out_trade_no, $price_yuan)
    {
        $biz = json_encode([
            'body'         => strval($order_title),
            'subject'      => strval($order_title),
            'out_trade_no' => strval($out_trade_no),
            'total_amount' => strval($price_yuan),
            'product_code' => 'QUICK_MSECURITY_PAY'
        ], JSON_UNESCAPED_UNICODE);

        $request = new \AlipayTradeAppPayRequest();
        $request->setBizContent($biz);
        $request->setNotifyUrl($this->notify_url);

        $response = $this->aop->sdkExecute($request);

        ApiLog::tag('AliPay.createOrder', 'POST', self::URL, $biz, $response);

        /*  response sample:
        alipay_sdk=alipay-sdk-php-20180705&app_id=2015122301033347&biz_content=%7B%22body%22%3A%22%5Cu5934%5Cu6761%5Cu8ba2%5Cu5355%22%2C%22
        subject%22%3A%22%5Cu5934%5Cu6761%5Cu8ba2%5Cu5355%22%2C%22out_trade_no%22%3A%22SP2019040417180537506462794905%22%2C%22
        total_amount%22%3A%221%22%2C%22product_code%22%3A%22QUICK_MSECURITY_PAY%22%7D&charset=UTF-8&format=json&method=alipay.trade.app.pay&
        notify_url=http%3A%2F%2Fxr.qianzaicc.com%2Fapi%2Falipay%2Falipay_notify&sign_type=RSA2&timestamp=2019-04-08+11%3A29%3A05&version=1.0&
        sign=cJS3hXrISFQQpCH7sIjx0OafXttz1TsMRUj47SE499DyKGLC%2Beu%2BJvvX%2BGUqKOe5CrE7THERVmBp8vE09J4XsrHiogKygwHNNfVN3QXOp%2BuZdFoKVLOw9G8%2F
        KeMQ2lmbJ6IE87G%2FGfI5OCFLN4SmCTQ1GuDNzb0cwCMkh9UPQXgLcSQVPP2eQZ6mRhvJU0gn734ykliP1UvFPinJ4j%2Bg%2F3np33jLeriQfL7UfanVJJIyxWlRMwh5oNOZS
        scekIgBBtGf%2FA5j9pKlqijKPwTPc%2F6llxYuk7f3VDaiB75C%2BGQ0t%2B1XmwRF3PTGvdloduMtU1JEVJhqn3sTkYyiKI%2FW3g%3D%3D
         */

        return $response;
    }

    // 支付宝退款。  https://docs.open.alipay.com/api_1/alipay.trade.refund
    // $out_trade_no 下单时使用的商户订单号。$refund_no 退单号。$refund_price_yuan 要退的金额(单位元)。
    public function refund($out_trade_no, $refund_no, $refund_price_yuan, $reason = '')
    {
        $biz = json_encode([
            'out_trade_no'   => strval($out_trade_no),
            'out_request_no' => strval($refund_no),
            'refund_amount'  => $refund_price_yuan,
            'refund_reason'  => $reason,
//            'operator_id'    => 'OP001',
//            'store_id'       => 'NJ_S_001',
//            'terminal_id'    => 'NJ_T_001',
        ], JSON_UNESCAPED_UNICODE);

        $request = new \AlipayTradeRefundRequest();
        $request->setBizContent($biz);

        $response = $this->aop->sdkExecute($request);

        ApiLog::tag('AliPay.refund', 'POST', self::URL, $biz, $response);

        /* response success sample:
        {"alipay_trade_refund_response":{"code":"10000","msg":"Success","trade_no":"支付宝交易号","out_trade_no":"6823789339978248",
        "buyer_logon_id":"159****5620","fund_change":"Y","refund_fee":88.88,"refund_currency":"USD","gmt_refund_pay":"2014-11-27 15:45:57",
        "refund_detail_item_list":[{"fund_channel":"ALIPAYACCOUNT","amount":10,"real_amount":11.21,"fund_type":"DEBIT_CARD"}],
        "store_name":"望湘园联洋店","buyer_user_id":"2088101117955611","refund_preset_paytool_list":
        {"amount":[12.21],"assert_type_code":"盒马礼品卡:HEMA；抓猫猫红包:T_CAT_COUPON"},"refund_charge_amount":"8.88","refund_settlement_id":
        "2018101610032004620239146945","present_refund_buyer_amount":"88.88","present_refund_discount_amount":"88.88",
        "present_refund_mdiscount_amount":"88.88"},"sign":"ERITJKEIJKJHKKKKKKKHJEREEEEEEEEEEE"}

        error sample:
        {"alipay_trade_refund_response":{"code":"20000","msg":"Service Currently Unavailable",
        "sub_code":"isp.unknow-error","sub_msg":"系统繁忙"},"sign":"ERITJKEIJKJHKKKKKKKHJEREEEEEEEEEEE"}
        */

        $responseNode = 'alipay_trade_refund_response';
        $resultCode   = $response->$responseNode->code;

        if (!empty($resultCode) && $resultCode == 10000) {
            return true;
        } else {
            return $response->$responseNode->sub_msg;
        }
    }

    // 检查支付宝的回调参数是否合法。
    public function checkSign()
    {
        return $this->aop->rsaCheckV1($_POST, NULL, "RSA2");
    }
}

