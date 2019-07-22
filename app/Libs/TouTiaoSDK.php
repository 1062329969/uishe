<?php

namespace App\Libs;

use App\ApiLog;
use App\ErrorLog;
use App\Order;
use App\WxApp;
use App\WxAppOpen;
use App\WxAppUser;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

use App\RefundLog;

/**
 * 头条小程序接口
 */
class TouTiaoSDK
{
    const URL_jscode2session = 'https://developer.toutiao.com/api/apps/jscode2session';
    const URL_createOrder    = 'https://tp-pay.snssdk.com/gateway';

    private $appId;             // 头条小程序接口需要用到的appid。
    private $appSecret;

    private $ttpay_appid;       // 开通头条支付业务后分配的，用于支付相关接口的appid。
    private $ttpay_merchantid;  // 开通头条支付业务后分配的商户号。
    private $ttpay_secret;      // 开通头条支付业务后分配的支付secret。

    private $alipay_notify_url;

    private $risk_info;
    private $err_msg;


    public function __construct(Array $options)
    {
        $this->appId     = isset($options['app_id']) ? $options['app_id'] : '';
        $this->appSecret = isset($options['app_secret']) ? $options['app_secret'] : '';

        $this->ttpay_appid      = isset($options['ttpay_appid']) ? $options['ttpay_appid'] : '';
        $this->ttpay_merchantid = isset($options['ttpay_merchantid']) ? $options['ttpay_merchantid'] : '';
        $this->ttpay_secret     = isset($options['ttpay_secret']) ? $options['ttpay_secret'] : '';

        $this->alipay_notify_url = Config::get('app.alipay_notify');

        $this->risk_info = json_encode(['ip'=>self::get_client_ip(), 'device_id'=>''], JSON_UNESCAPED_UNICODE);
    }

    public function getErrorMsg()
    {
        return $this->err_msg;
    }

    public function jscode2session($code, $anonymous_code = null)
    {
        $url_params = [
            'appid'  => $this->appId,
            'secret' => $this->appSecret,
            'code'   => $code
        ];

        if (!empty($anonymous_code)) {
            $url_params['anonymous_code'] = $anonymous_code;
        }

        $ret = $this->http('GET', self::URL_jscode2session, $url_params);

        if (!$ret) {
            return false;
        }

        // 成功示例：{"error":0, "session_key":"H6JoamNUYOYYDfkFO2didw==", "openid":"9UIk1Gpo..eYBQMe"}
        // 失败示例：{"error":3, "message":"bad code", "errcode":40029, "errmsg":"bad code"}
        $arr = json_decode($ret, true);

        if ($arr == null) {
            $this->err_msg = '解析数据失败。' . $ret;
            return false;
        }

        if (isset($arr['error']) && ($arr['error'] != 0)) {
            $this->err_msg = $arr['message'];
            return false;
        }

        return $arr;
    }

    // 生成头条订单，返回参数给头条小程序，以供调起支付宝支付。
    public function createOrder($open_id, $order_id, $price_yuan, $title)
    {
        // 1 头条下单。
        $timestamp = time();
        $price_fen = (int)($price_yuan * 100);

        $biz_content                 = [];
        $biz_content['out_order_no'] = $order_id;
        $biz_content['uid']          = $open_id;
        $biz_content['merchant_id']  = $this->ttpay_merchantid;
        $biz_content['total_amount'] = $price_fen;     // 头条要求价格的单位是分。
        $biz_content['currency']     = 'CNY';
        $biz_content['subject']      = $title;
        $biz_content['body']         = $title;
        $biz_content['trade_time']   = $timestamp;                  // 下单时间。
        $biz_content['valid_time']   = $timestamp + 30 * 60;        // 订单有效时间。30分钟。
        $biz_content['notify_url']   = $this->alipay_notify_url;
        $biz_content['risk_info']    = $this->risk_info;

        $url_params                = [];
        $url_params['app_id']      = $this->ttpay_appid;
        $url_params['method']      = 'tp.trade.create';
        $url_params['format']      = 'json';
        $url_params['charset']     = 'utf-8';
        $url_params['sign_type']   = 'MD5';
        $url_params['version']     = '1.0';
        $url_params['timestamp']   = $timestamp;
        $url_params['biz_content'] = json_encode($biz_content, JSON_UNESCAPED_UNICODE);
        $url_params['sign']        = $this->getSign($url_params);

        // 虽然是post调用，但参数都通过url传递，post body=null。
        $ret = $this->http('POST', self::URL_createOrder, $url_params, null);
        if (!$ret) {
            return false;
        }

        // 成功：{"response":{"code":"10000","msg":"Success","trade_no":"SP2019040417180537506462794905"},"sign":"v+aX4Qg...mSnQmpYIOsoE="}
        // 失败：{"response":{"code":"40001","msg":"Params Error","sub_code":"GW.SIGN_ERROR","sub_msg":"Sign Error"},"sign":"ao0UNbqE2z...9pbOZV13eeg="}

        $arr = json_decode($ret, true);
        if ($arr == null) {
            $this->err_msg = '解析数据失败。' . $ret;
            return false;
        }

        if ($this->checkResponseSign($arr) == false) {
            $this->err_msg = '返回数据签名验证失败。';
            return false;
        }

        $data = $arr['response'];

        if ($data['code'] != '10000') {
            $this->err_msg = $data['sub_msg'];
            return false;
        }

        $tt_trade_no = $data['trade_no'];

        // 2 支付宝下单。
        $alipay_appid = '2015122301033347';
        $alipay_rsaPrivateKey = 'MIIEogIBAAKCAQEAqXrsK9pGjHrovr6KggUsuJuI+syGaEylYKYTDq4Tqh1GAvZwYQ1rBJ65YDm9C2D7RCHiCV4XU2ZKsltOfp3v5if9pq31E7qtz8STZlmBpDUNLltIYbP3aMxNXKbzipbUZWh/RDGXAtXJCvwYIJmSkWuPX6xJOUmO4Gg7MPt9EN3uRG2jc9UYS5AY0Lo5p/MRvxzsLC6E+qf594my6vE0MBfdyo3GpYzloOsYvVAk9q+BdGQkrOumCcjzVEBkyleYRGk/5MMuWVu873Qd1O7uBS8hTu7YJjjxyh1PXmc2m31AGuxpDMaYzVj1lYpCueRWckxkwEMPPuzZM4M5KrnNGwIDAQABAoIBABiQi4izo4QbEH8NKRA9ZcUALjODz3twmIvQpnR7QLhoc8xcId+/TSKnSuEqnT73+JNggd2vuJvK+MqvN0pc4/etFjPBhQMuDKFPsbmlAYhmdBmi+3PF3PdgnVnvjN8qi3CDW+brdSLhD7m63lhjSf4v4+EPHLHj+oSGNwYbfX4jIFKH8S1/+LJ3bsb7OuG3tcqDcR18ZR2ghoU96GOLycfR9pJMAuO3kC+Bw4gdXCdOmnmWofTRoSUSYGNbztuHHqAdimYYO07Ds09Ltdbl19F9EFVcAMzcOtj/WImldymLjApuhVmRBVG9XSmQkpR7mK8LmgnPuVTa+VXIp6PR+GkCgYEA0hljB3G0X61NHFTDkVxblXj/4Wb9mjIslKUrvatmlIFgIGlxy7inyC4N9rP/aGsFwJScLEMA8XZlHuSfN3GBPUaDfGAIfq955LqheG8R9I+1y2UQ7APhMZt9CntI8BjEK6HfqqFIYzT3mPSakfMhe5FlpZX91SyJrtbpCPw5wb8CgYEAzoHClFDKnCWhCTGNxVlY65liIB3lvJ3pDVzP6dYG1BWRDj/4xzcaiHBppnkUrykIKnW6q1LwgCpnpPT5U0+RK42Xbr4ycSqK0gIFaqgyOcDNj05a6cQmp8JVvMMMJKJKHDVIJeEe47oagytcFJJPsTkg7hMEz99V37dYCn4RU6UCgYBpq38VRWkVHhLEhxV9zNf7S8pW0fgHT+kAMjrXwJdtZcy1QJONhShOFGvNgyaYnAtemuAZmvGkgInHifFSb3IzIX4MVjivjw5drkh8TFDZklY7IG/sZN8kljHDzdIXgD1aPKZEKa5Ax2kosw3zDjfu8GgRamkqiKYPG+RFMqbR/QKBgEhqjJeCNWrIb05QCE06ZcpIwXZNxbB9F6rN70MrAL4CyhlbKZyCoFVURlhQgYZjNy/clRdbQGdRd4MLPRuP/XJudropDHtO1duLReb/EI3cJLmqWos7c+rEZViKdYmrN53ouWI/LZviOUXiQKoeHMAjLn8OQFO9F9oZ60/SobTZAoGAK8RmD4SXpC5y8041GXhDzE/6GWEbZhaeB+jwHT8rFlP28otoCnjPA+yaurSxvlhUR7j8SEJ9qVGIyVg6FroW63+/O8gSQT79ldH+FlQWTBnyNN1rGKeB5mOgzr/2k0Nu+BnZwB6nH0jAjq65HLKPcC54sgvqvf9KcBsfyXKID0A=';
        $alipay_alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAlWhf20s1O7GF/psZVoburSLb1wU872msQn+b1HGwuaVRy018G//aCNu/LVBhRDH+205CyS7qk2d5efsoa7+TQOg9K1xCfjGO0R2kHp8vAH1Oxf+cubgV1R+tMHIqa/IWEhQ9B835tvGMF6OpnUexG+4B9KpWvMVr9BpYqHHNg5PbdKLb6rqnnymtyUnMaelOd8nVQo5dZ/+RbKRGY4+42urIEW4SG6xI3jzs2Phg1J/UaAskkYDDLKA3LOCIeqBOBI7dIsrZQi5mSxgMPm8JGUviQAWTvWquRP8pne/YAnFd4yQPTN8nikA2MHbc3riMmaWFNiVqUlJ7ccOpwhSSUwIDAQAB';

        $alipay = new AliPaySDK($alipay_appid, $alipay_rsaPrivateKey, $alipay_alipayrsaPublicKey);
        $rsp_alipay = $alipay->createOrder($title, $tt_trade_no, $price_yuan);

        // 3 生成头条小程序需要的支付参数。
        $alipay_params = ['url' => $rsp_alipay];

        $data                 = [];
        $data['app_id']       = $this->ttpay_appid;
        $data['sign_type']    = 'MD5';
        $data['timestamp']    = $timestamp = time() . '';
        $data['trade_no']     = $tt_trade_no;
        $data['merchant_id']  = $this->ttpay_merchantid;
        $data['uid']          = $open_id;
        $data['total_amount'] = $price_fen;
        $data['params']       = json_encode($alipay_params, JSON_UNESCAPED_UNICODE);;

        $data['sign']        = $this->getSign($data);
        $data['method']      = 'tp.trade.confirm';
        $data['pay_channel'] = 'ALIPAY_NO_SIGN';
        $data['pay_type']    = 'ALIPAY_APP';
        $data['risk_info']   = $this->risk_info;

        return $data;
    }

    private function getSign($params)
    {
        ksort($params);
        $tmp = [];
        foreach ($params as $key => $value) {
            $tmp[] = $key . '=' . $value;
        }

        $payload = implode('&', $tmp);
        $sign    = md5($payload . $this->ttpay_secret);

        return $sign;
    }

    private function checkResponseSign($rsp_array)
    {
        return true;
    }

    private static function get_client_ip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        if ($ip == '::1') $ip = '127.0.0.1';

        return $ip;
    }

    // $url_params url后的key-value参数数组。$post_data 仅post方法有效，可以是key-value数组，也可以是字符串。
    private function http($method, $url, $url_params = null, $post_data = null)
    {
        $method = strtoupper($method);
        $url    = empty($url_params) ? $url : $url . '?' . http_build_query($url_params);

        $opts = [
            CURLOPT_URL            => $url,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ];

        $post_str = null;

        if ($method == 'POST') {
            $opts[CURLOPT_POST] = 1;

            if (!empty($post_data)) {
                if (is_array($post_data) && count($post_data) > 0) {
                    $post_str = http_build_query($post_data);
                } elseif (is_string($post_data)) {
                    $post_str = $post_data;
                }
            }

            if (!empty($post_str)) {
                $opts[CURLOPT_POSTFIELDS] = $post_str;
            }
        }

        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data    = curl_exec($ch);
        $err_no  = curl_errno($ch);
        $err_msg = curl_error($ch);

//        dump(curl_getinfo($ch));

        $rsp = ($data == false) ? ($err_no . ', ' . $err_msg) : $data;
        ApiLog::tag('TouTiao', $method, $url, $post_str, $rsp);

        curl_close($ch);

        if ($err_no > 0) {
            $this->err_msg = $err_msg;
            return false;
        } else {
            return $data;
        }
    }
}


class TouTiaoSDK_old
{
    const URL_jscode2session = 'https://developer.toutiao.com/api/apps/jscode2session';                                     // 微信小程序登陆，通过 code 换取 session_key。
    const URL_ACCESS_TOKEN   = 'https://developer.toutiao.com/api/apps/token';

    private $app_id;
    private $app_secret;
    private $error;

    public function __construct($options = array())
    {
        $this->app_id     = isset($options['appid']) ? $options['appid'] : '';
        $this->app_secret = isset($options['appsecret']) ? $options['appsecret'] : '';
    }

    // 头条小程序登录后，通过jscode获取 openid 和 session_key。
    public function jscode2session($code, $anonymous_code)
    {
        $params           = [];
        $params['appid']  = $this->app_id;
        $params['secret'] = $this->app_secret;
        $params['code']   = $code;

        if (!isEmpty($anonymous_code)) {
            $params['anonymous_code'] = $anonymous_code;
        }

        $jsonStr = $this->http(self::URL_jscode2session, $params);
        if ($jsonStr === false) {
            return false;
        }

        // 成功： { "openid": "OPENID", "session_key": "SESSIONKEY", "anonymous_openid": "xxxx" }
        // 失败： { "error": 40029, "message": "invalid code" }

        $arr = json_decode($jsonStr, true);
        if (isset($arr['error'])) {
            $this->error = $arr['message'];
            return false;
        }

        return $arr;
    }

    public function getAccessToken()
    {
        $params               = [];
        $params['appid']      = $this->app_id;
        $params['secret']     = $this->app_secret;
        $params['grant_type'] = 'client_credential';

        $jsonStr = $this->http(self::URL_ACCESS_TOKEN, $params);
        if ($jsonStr === false) {
            return false;
        }

        // 成功： { "access_token": "access_token", "expires_in": 123456 }
        // 失败： { "error": 123, "message": "invalid code" }

        $arr = json_decode($jsonStr, true);
        if (isset($arr['error'])) {
            $this->error = $arr['message'];
            return false;
        }

        return $arr;
    }

    public function getErrorMsg()
    {
        return $this->error;
    }


    public function getPropertyName($property_name)
    {
        if (isset($this->$property_name)) {
            return ($this->$property_name);
        } else {
            return (NULL);
        }
    }

    public static function wxAppDecryptData($encryptedData, $iv, $wxappid, $session_key)
    {
        if (strlen($session_key) != 24) {
            return self::Error_IllegalAesKey;
        }

        if (strlen($iv) != 24) {
            return self::Error_IllegalIv;
        }

        $aesKey    = base64_decode($session_key);
        $aesIV     = base64_decode($iv);
        $aesCipher = base64_decode($encryptedData);

        $result = openssl_decrypt($aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);

        $dataObj = json_decode($result);

        if ($dataObj == NULL) {
            return self::Error_IllegalBuffer;
        }

        if ($dataObj->watermark->appid != $wxappid) {
            return self::Error_IllegalBuffer;
        }

        return $dataObj;
    }


    // 发起退款请求。
    public function reqRefund($orderId, $total_fee, $refund_fee, $siteid = '', $notify_url = '')
    {
        $total_fee  = intval($total_fee * 100);
        $refund_fee = intval($refund_fee * 100);

        $refund = RefundLog::create($siteid, $orderId, $total_fee, $refund_fee);

        $params['appid']  = $this->appid;
        $params['mch_id'] = $this->mch_id;

        if ($this->payMode === self::PayMode_Service) {   // 服务商模式
            $params['sub_appid']  = $this->sub_appid;
            $params['sub_mch_id'] = $this->sub_mch_id;
        }

        $params['nonce_str']     = self::_getRandomStr();
        $params['out_trade_no']  = $orderId;
        $params['out_refund_no'] = $refund->refund_id;
        $params['total_fee']     = $total_fee;
        $params['refund_fee']    = $refund_fee;

        if ($notify_url != '') {
            $params['notify_url'] = $notify_url;
        }

        $params['sign'] = self::_getOrderMd5($params);

        $data   = self::_array2Xml($params);
        $strRsp = $this->http(self::PAY_REFUND_ORDER, $data, 'POST', true);

// <xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[受理机构必须传入sub_mch_id]]></return_msg></xml>

// <xml><return_code><![CDATA[SUCCESS]]></return_code>
// <return_msg><![CDATA[OK]]></return_msg>
// <appid><![CDATA[wx0dda822278f64202]]></appid>
// <mch_id><![CDATA[1509030401]]></mch_id>
// <nonce_str><![CDATA[4eReLb63GLx6Hcj9]]></nonce_str>
// <sign><![CDATA[F22F7F8B05C5ACC521539D874FE2929A]]></sign>
// <result_code><![CDATA[FAIL]]></result_code>
// <err_code><![CDATA[ORDERNOTEXIST]]></err_code>
// <err_code_des><![CDATA[订单不存在]]></err_code_des>
// </xml>

        $ret = $this->parsePayRequest($strRsp);

        if ($ret === false) {
            $refund->refund_result     = 0;
            $refund->refund_result_msg = $this->error;
        } else {
            $refund->refund_result = 1;
        }
        $refund->save();

        return $ret;
    }

    public function test()
    {
        $data = '
<xml>
<return_code><![CDATA[SUCCESS]]></return_code>
<return_msg><![CDATA[OK]]></return_msg>
<appid><![CDATA[wx0dda822278f64202]]></appid>
<mch_id><![CDATA[1509030401]]></mch_id>
<nonce_str><![CDATA[4eReLb63GLx6Hcj9]]></nonce_str>
<sign><![CDATA[F22F7F8B05C5ACC521539D874FE2929A]]></sign>
<result_code><![CDATA[FAIL]]></result_code>
<err_code><![CDATA[ORDERNOTEXIST]]></err_code>
<err_code_des><![CDATA[订单不存在]]></err_code_des>
</xml>
        ';

        $ret = $this->parsePayRequest($data);
    }

    public function processPayNotify($str)
    {
        return $this->parsePayRequest($str);
    }

    public static function returnNotify($return_msg = true)
    {
        if ($return_msg === true) {
            $data = array(
                'return_code' => 'SUCCESS',
            );
        } else {
            $data = array(
                'return_code' => 'FAIL',
                'return_msg'  => $return_msg
            );
        }

        exit(self::_array2Xml($data));
    }

    private function parsePayRequest($strRsp)
    {
        $data = self::_extractXml($strRsp);

        if (empty($data)) {
            $this->error = '返回内容解析失败' . $strRsp;
            return false;
        }

        if ($data['return_code'] !== 'SUCCESS') {
            $this->error = $data['return_msg'];
            return false;
        }

        if (!self::_checkSign($data)) {
            return false;
        }

        if ($data['result_code'] == 'SUCCESS') {
            return $data;
            //return true;
        } else {
            $this->error = $data['err_code_des'];
            return false;
        }
    }

    private function _checkSign($data)
    {
        if (!isset($data['sign'])) {
            $this->error = '缺少签名校验参数';
            return false;
        }

        $sign = $data['sign'];
        unset($data['sign']);
        if (self::_getOrderMd5($data) != $sign) {
            $this->error = '签名校验失败';
            return false;
        } else {
            return true;
        }
    }

    private function _getRandomStr($lenght = 16)
    {
        $str_pol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        return substr(str_shuffle($str_pol), 0, $lenght);
    }

    private function _getOrderMd5($params)
    {
        ksort($params);
        $params['key'] = $this->payKey;
        return strtoupper(md5(urldecode(http_build_query($params))));
    }

    private static function _array2Xml($array)
    {
        $xml = new \SimpleXMLElement('<xml></xml>');
        self::_data2xml($xml, $array);
        return $xml->asXML();
    }

    private static function _data2xml($xml, $data, $item = 'item')
    {
        foreach ($data as $key => $value) {
            /* 指定默认的数字key */
            is_numeric($key) && $key = $item;
            /* 添加子元素 */
            if (is_array($value) || is_object($value)) {
                $child = $xml->addChild($key);
                self::_data2xml($child, $value, $item);
            } else {
                if (is_numeric($value)) {
                    $child = $xml->addChild($key, $value);
                } else {
                    $child = $xml->addChild($key);
                    $node  = dom_import_simplexml($child);
                    $node->appendChild($node->ownerDocument->createCDATASection($value));
                }
            }
        }
    }

    private function _extractXml($xml)
    {
        $data = (array)simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        return array_change_key_case($data, CASE_LOWER);
    }

    private function http($url, $params = array(), $method = 'GET', $ssl = false)
    {
        $opts = array(
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        );

        /* 根据请求类型设置特定参数 */
        switch (strtoupper($method)) {
            case 'GET':
                $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
                break;
            case 'POST':
                $opts[CURLOPT_URL]        = $url;
                $opts[CURLOPT_POST]       = 1;
                $opts[CURLOPT_POSTFIELDS] = $params;
                break;
        }

//        if ($ssl) {
//            $pemCret = $this->WxPay_Certs_Path . $this->mch_id . '_wxpay_cert.pem';
//            $pemKey = $this->WxPay_Certs_Path . $this->mch_id . '_wxpay_key.pem';
//
//            if (!file_exists($pemCret)) {
//                $this->error = '证书不存在';
//                return false;
//            }
//            if (!file_exists($pemKey)) {
//                $this->error = '密钥不存在';
//                return false;
//            }
//
//            $opts[CURLOPT_SSLCERTTYPE] = 'PEM';
//            $opts[CURLOPT_SSLCERT] = $pemCret;
//            $opts[CURLOPT_SSLKEYTYPE] = 'PEM';
//            $opts[CURLOPT_SSLKEY] = $pemKey;
//        }

        Log::info("TouTiaoSDK url:{$url} \n req:" . json_encode($params));

        /* 初始化并执行curl请求 */
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data   = curl_exec($ch);
        $err    = curl_errno($ch);
        $errmsg = curl_error($ch);
        curl_close($ch);

        Log::info('TouTiaoSDK rsp:' . $data);

        if ($err > 0) {
            $this->error = $errmsg;
            return false;
        } else {
            return $data;
        }
    }


    /**
     *  统一下单接口，生成支付请求。
     *  下单无需证书。
     * @param  $openid      string  用户OPENID相对于当前公众号
     * @param  $body        string  商品描述 少于127字节
     * @param  $orderId     string  系统中唯一订单号
     * @param  $money       integer 支付金额，单位分。
     * @param  $notify_url  string  通知URL
     * @param  $attach      string  附加参数
     * @return json|boolean json 直接可赋给JSAPI接口使用，boolean错误
     */
    public function createOrder($openid, $body, $orderId, $money, $notify_url, $attach = null)
    {
        if (strlen($body) > 127) {
            $body = substr($body, 0, 127);
        }

        $params = array(
            'appid'            => $this->appid,
            'mch_id'           => $this->mch_id,
            'nonce_str'        => self::_getRandomStr(),
            'body'             => $body,
            'out_trade_no'     => $orderId,
            'total_fee'        => $money, // 单位分
            'spbill_create_ip' => self::get_client_ip(),
            'notify_url'       => $notify_url,
            'trade_type'       => 'JSAPI',
        );

        if ($this->payMode === self::PayMode_Service)       // 服务商模式
        {
            $params['sub_appid']  = $this->sub_appid;
            $params['sub_mch_id'] = $this->sub_mch_id;
            $params['sub_openid'] = $openid;
        } else {
            $params['openid'] = $openid;
        }

        if ($attach != null) {
            $params['attach'] = $attach;
        }

        // 生成签名
        $params['sign'] = self::_getOrderMd5($params);
        $data           = self::_array2Xml($params);
        $data           = $this->http(self::UNIFIED_ORDER_URL, $data, 'POST');
        Log::write('info', 'wechat unifiedorder. rsp =' . $data);
        $data = self::_extractXml($data);

        /* 成功结果
         $data = array(10) {
         ["return_code"]=>
         string(7) "SUCCESS"
         ["return_msg"]=>
         string(2) "OK"
         ["appid"]=>
         string(18) "wxff08c4260f7f3e0b"
         ["mch_id"]=>
         string(10) "1500296262"
         ["sub_mch_id"]=>
         string(10) "1500614741"
         ["nonce_str"]=>
         string(16) "dTKi66TJd7BUwNyr"
         ["sign"]=>
         string(32) "0BF5E44C6D6EE5BC0EB70F421E966D52"
         ["result_code"]=>
         string(7) "SUCCESS"
         ["prepay_id"]=>
         string(36) "wx281833294639493ec7866fc62894628507"
         ["trade_type"]=>
         string(5) "JSAPI"
         }
         */
        //var_dump($data);
        //exit;
        if ($data) {
            if ($data['return_code'] == 'SUCCESS') {
                if ($data['result_code'] == 'SUCCESS') {
                    return $this->createPayParams($data['prepay_id']);
                } else {
                    $this->error = $data['err_code'];
                    return false;
                }
            } else {
                $this->error = $data['return_msg'];
                return false;
            }
        } else {
            $this->error = '创建订单失败';
            return false;
        }
    }

    private static function get_client_ip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        if ($ip == '::1') $ip = '127.0.0.1';

        return $ip;
    }

    private function createPayParams($prepay_id)
    {
        if (empty($prepay_id)) {
            $this->error = 'prepay_id参数错误';
            return false;
        }

        $params = [];

        if ($this->payMode === self::PayMode_Service) {
            $params['appId'] = $this->sub_appid;
        } else {
            $params['appId'] = $this->appid;
        }

        $params['timeStamp'] = strval(time());//(string)NOW_TIME;
        $params['nonceStr']  = self::_getRandomStr();
        $params['package']   = 'prepay_id=' . $prepay_id;
        $params['signType']  = 'MD5';
        $params['paySign']   = self::_getOrderMd5($params);
        //return json_encode($params);
        return $params;
    }

    /**
     * 设置微信AccessToken
     * 定时更新微信小程序的AccessToken
     * $wxappid              小程序唯一appid
     * @Author / Time：Eagle / 2018-11-23
     */
    public function setAccessToken($wxappid)
    {
        $result               = '';
        $WX_Secret_List       = WxApp::where(['wxapp_id' => $wxappid])->first();//获取微信小程序信息
        $params['grant_type'] = 'client_credential';
        $params['appid']      = $WX_Secret_List->wxapp_id;
        $params['secret']     = $WX_Secret_List->wxapp_secret;//微信获取accessToken
        Log::info('WechatSDK::setAccessToken    URL::' . json_encode($params));
        try {
            $result = $this->http(self::ACCESS_TOKEN, $params);
            Log::info('WechatSDK::setAccessToken    获得结果::' . json_encode($result));
            $result = json_decode($result, true);
            $result = $result['access_token'];
        } catch (Exception $e) {
            Log::info('WechatSDK::setAccessToken    ErrorInfo::' . $e->getMessage());
        }
        //将获取到的access_token更新到数据库
        $res = WxApp::where('id', $WX_Secret_List->id)->update(['wxaccess_token' => $result, 'update_at' => date('Y-m-d H:i:s', time())]);
        Log::info('WechatSDK::setAccessToken    ErrorInfo::' . $res);

    }


    /**
     * 通过小程序消息模版给用户发送通知
     *
     * $data                    发送的数据
     *example：
     *
     * [
     * 'touser'           => $api_caller->openid, // 用户的 openID，可用过 wx.getUserInfo 获取
     * 'form_id'          => $request->formId, // 第一步里获取到的 formID
     * 'page'  =>['id'=>1,'field'=>'value']
     * 'data' => [
     * 'keyword1' => ['value' => 111111111],//订单编号
     * 'keyword2' => ['value' => 111],//入住人
     * ],
     * ]
     * $module 模块标志
     * @Author / Time：Eagle / 2018-11-23
     */
    public function sendNotice($data)
    {

        //通过传递过来的用户openid 获取对应用户的小程序appid
        $users = WxAppUser::where('openid', $data['touser'])->first(['wxapp_id']);
        //获得微信Access Token
        $access_token = WxApp::where(['wxapp_id' => $users['wxapp_id']])->first()['wxaccess_token'];
        if (empty($access_token)) {
            return '';
        }
        $url = self::SEND_TEMPLATE . '?access_token=' . $access_token;
        Log::info('WechatSDK::sendNotice 发送小程序消息模版内容======>>>' . json_encode($data));

        $post_data = $this->getTemplateMessage($data);

        $data = json_encode($post_data, true);
        Log::info('WechatSDK::sendNotice 发送小程序消息模版数据内容======>>>' . json_encode($data));
        $options = [
            'http' => [
                'method'  => 'POST',
                'header'  => 'Content-type:application/json', // header 需要设置为 JSON
                'content' => $data,
                'timeout' => 60 // 超时时间
            ]
        ];
        $context = stream_context_create($options);
        Log::info('WechatSDK::sendNotice 发送小程序消息模版数据======>>>' . json_encode($context));
        $result = file_get_contents($url, false, $context);
        return $result;
    }


    public function getTemplateMessage($data)
    {
        $temp         = $data['data'];
        $data['data'] = [];
        foreach ($temp as $k => $v) {
            $data['data']['keyword' . ($k + 1)] = ['value' => $v];
        }
        return $data;
    }


    /**
     * 生成小程序临时二维码
     */
    public function createTempSmallRoutineCode($param, $type = 'temp', $isHyaline = true, $access_token = '')
    {
        if (empty($access_token)) {
            $access_token = $this->getAccessToken($param);
        }

        $url = "https://api.weixin.qq.com/wxa/getwxacode?access_token=$access_token";
        //$url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token?access_token=$access_token";
        $resources = json_encode([
            'path'       => $param['path'],
            'width'      => $param['width'] ?? self::XcxCodeWidth,
            'is_hyaline' => $isHyaline,
        ]);

        //POST参数
        $result = $this->http($url, $resources, "POST");

        // 生成临时二维码
        if ($type == 'temp' || empty($type)) {
            $base64_image = "data:image/jpeg;base64," . base64_encode($result);
            return $result;
        }

    }

    /**
     * //生成微信小程序二维码
     * @param $model            相关model
     * @param $param            参数 [ 'width'=>'图片宽度，可以不传', 'data'=>'附加参数数组' ]  data:[ 'id'=>1, 'qitacanshu'=>'xxx' ]
     * @param bool $isHyaline
     */
    public function saveCodeImg($model, $param, $isHyaline = true)
    {

        $wxapp = WxApp::where([
            ['type', '=', 'hotel'],
            ['group_id', '=', $model->group_id]
        ])->first();
        if (!$wxapp) {
            return false;
        }
        $access_token = $wxapp->wxaccess_token;
        $data         = $param['data'];
        unset($param['data']);
        $param_arr = [];
        foreach ($data as $field => $value) {
            $param_arr[] = $field . '=' . $value;
        }
        $data_str = implode('&', $param_arr);
        $param    = ['path' => $model::Xcx_Path . "?" . $data_str];
        // 本地图片存储名称
        $img            = $this->createTempSmallRoutineCode($param, NULL, $isHyaline, $access_token);
        $code           = $this->imgUploadAliOss($img);
        $model->codeimg = $code;
        $model->save();
        return $code;
//        Room::where('id', $model->id)->update(['codeimg'=>$code]);
    }

    /**
     * 图片存储本地且上传到阿里云,用于php自己生成图片保存
     * @param $img 二进制图片流
     * @return string 文件名
     */
    public static function imgUploadAliOss($img)
    {

        // 上传本地
        $fileName = md5(uniqid(microtime(true), true)) . '.png';
        Storage::disk('public')->put($fileName, $img);

        // 获取上传oss所需要的图片m名称
        //读取本地路径图片
        $local_file = '../storage/app/public/' . $fileName;
        $file       = new File($local_file);


        // 生成oss所需文件名
        $upload_file_name = $file->path();  // getRealPath();
        $file_hash        = sha1_file($upload_file_name);  // sha1_file md5_file
        $save_as          = $file_hash . '.png';

        // 上传oss
        $ret = Storage::putFileAs('', $file, $save_as);

        if ($ret === false) {
            return false;
        }

        // 上传oss后删除本次上传到到服务的临时文件
        Storage::disk('public')->delete($fileName);

        return env('AliOSS_url_prefix') . $save_as;
    }

    /**
     * 提现到用户账户
     */
    public function cashUserAccount($openid, $trade_no, $money, $desc = '')
    {

        $money = $money * 100;

        $params['mch_appid'] = $this->appid;
        $params['mchid']     = $this->mch_id;
        $params['nonce_str'] = self::_getRandomStr();;// 随机字符串，不长于32位
        $params['partner_trade_no'] = $trade_no; // 退款自定义单号
        $params['amount']           = $money; //付款金额，单位为分
        $params['desc']             = $desc; //描述信息
        $params['openid']           = $openid; //用户openId
        $params['check_name']       = 'NO_CHECK';//是否验证用户真实姓名，这里不验证
        $params['spbill_create_ip'] = $_SERVER['SERVER_ADDR'];//获取服务器的ip

        $params['sign'] = self::_getOrderMd5($params);

        Log::info('WechatSDK::cashUserAccount 付款个人参数======>>>' . json_encode($params));

        $data = self::_array2Xml($params);

        // 发送post请求
        $res = $this->http(self::CASH_ORDER_URL, $data, "POST", true);


        if (!$res) {
            return ['status' => 1, 'msg' => '服务器连接失败'];
        }

        // 付款结果分析
        $content = self::_extractXml($res); //xml转数组

        Log::info('WechatSDK::cashUserAccount 付款个人结果=====>>>' . json_encode($content));

        return $content;
    }

    /**
     * 设置微信开放平台AccessToken
     * 定时更新微信小程序的AccessToken
     * $refresh              用来刷新token
     * @Author / Time：Eagle / 2018-11-23
     */
    public function refreshOpenToken($refresh)
    {
        $env      = env('APP_ENV');
        $opendata = WxAppOpen::where('app_env', $env)->first();
        $params   = [
            'appid'         => $opendata['wxapp_id'],
            'grant_type'    => 'refresh_token',
            'refresh_token' => $refresh
        ];

        $result = $this->http(self::REFRESH_OPEN_TOKEN, $params);
        $data   = json_decode($result, true);
        ErrorLog::create('WechatSDK::refreshOpenToken    获得结果::' . json_encode($data));
        $data = empty($data['errcode']) ? $data : '';
        return $data;
    }

    /**
     * 获取开放平台code换取access_token
     * @param $code  微信回调参数
     * @return array
     */
    public function setOpenToken($code)
    {
        $env      = env('APP_ENV');
        $opendata = WxAppOpen::where('app_env', $env)->first();
        $params   = [
            'appid'      => $opendata['wxapp_id'],
            'secret'     => $opendata['wxapp_secret'],
            'code'       => $code,
            'grant_type' => 'authorization_code'
        ];
        try {
            $result = $this->http(self::OPEN_ACCESS_TOKEN, $params);
            $result = json_decode($result, true);
            ErrorLog::create('WechatSDK::setOpenToken    获得结果::' . json_encode($result));
        } catch (\Illuminate\Database\QueryException $e) {
            ErrorLog::create('WechatSDK::setOpenToken    ErrorInfo::' . $e->getMessage());
        }
        return $result;
        ErrorLog::create('WechatSDK::setOpenToken    ErrorInfo::' . $res);
    }


    /**
     * 获取开放平台微信用户的详情
     * @param $accesstoken
     * @param $openid
     * @return array
     */
    public function wxUserInfo($accesstoken, $openid)
    {
        $params = [
            'access_token' => $accesstoken,
            'openid'       => $openid,
            'lang'         => 'zh_CH'
        ];
        $result = $this->http(self::OPEN_USER_INFO, $params);
        ErrorLog::create('WechatSDK::wxUserInfo    获得结果::' . json_encode($result));
        return $result;
    }
}