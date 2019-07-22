<?php
/*
 * 对接绿云会员接口
 * 获取用户 积分、等级、消费金（只查不用），没有推送。
 *
 */
namespace App\Libs\green;

use App\ApiLog;
use App\GreenCloudSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class GreenUser
{
    private $Sign_Url;
    private $Server_Url;
    private $HotelGroupId;
    private $HotelGroupCode;
    private $appKey;
    private $appSecret;
    private $payCode;
    private $usercode;
    private $password;
    private $UserPayCode;

//    const Sign_Url = 'http://122.224.119.138:7312/ipmsgroup/router';
//    const Server_Url = 'http://122.224.119.138:7311/ipmsmember/';
//    const HotelGroupId = 2;
//    const HotelGroupCode = 'GCBZG';
//    const appKey = 10003;
//    const appSecret = '8b3d727f1ba1cde61cef63143ebab5e5';
    const Http_TimeOut = 5;
    const GreenSessionId = 'GreenSessionId';

    const Test = 'membercard/hello';                                            // 3.1
    const VerifySmsCode = 'membercard/edmVerify';                               // 5.16.1
    const RegisterMember = 'membercard/registerMemberCardWithOutVerify';        // 4.3
    const GetMemberInfo = 'membercard/getMemberInfo';                           // 5.23.2
    const UpdateMemberBaseInfo = 'membercard/updateMemberBaseInfo';                           // 5.23.2
    const CheckDouble = 'membercard/checkDouble';                                //4.4.5
    const GetCardBalanceInfo = 'membercard/getCardBalanceInfo';                  //6.1.2
    const GetAllCardLevel = 'membercard/getAllCardLevel';                  //3.3.2  getAllCardType 获取所有会员计划 getAllCardLevel 获取所有会员等级
    const getAllCardType = 'membercard/getAllCardType';                  //3.3.2  getAllCardType 获取所有会员计划 getAllCardLevel 获取所有会员等级
    const onlinePayForCardAccount = 'membercard/onlinePayForCardAccount';  //线上充值
    const getAccountList = 'membercard/getAccountList';  //会员卡列表
    const getCardPointTotalByMemberId = 'membercard/getCardPointTotalByMemberId';//6.8  会员积分按卡汇总数据
    const getAccountListByMemberId = 'membercard/getAccountListByMemberId';//6.8  会员积分按卡汇总数据
    const getCardAccountTotalByMemberId = 'membercard/getCardAccountTotalByMemberId';//6.8  会员积分按卡汇总数据
    const CardUpgrade = 'membercard/cardUpgrade';                               //5.17  官网升降级会员
    const onlineChargeForCardAccount = 'membercard/onlineChargeForCardAccount';                               //会员卡消费


    public function __construct(Array $options)
    {
        $this->Sign_Url = $options['sign_url'] ?? '';
        $this->Server_Url = $options['server_user_url'] ?? '';
        $this->HotelGroupId = $options['hotel_group_id'] ?? '';
        $this->HotelGroupCode = $options['hotel_group_code'] ?? '';
        $this->appKey = $options['app_key'] ?? '';
        $this->appSecret = $options['app_secret'] ?? '';
        $this->payCode = $options['pay_code'] ?? '';
        $this->usercode = $options['usercode'] ?? '';
        $this->password = $options['password'] ?? '';
        $this->UserPayCode = $options['user_pay_code'] ?? '';
    }


    /** 获取所有会员等级
     *hotelGroupId  集团编号
     */
    public function GetAllCardLevel()
    {
        $data['sessionId'] = $this->getSign();
        $data['appKey'] = $this->appKey;
        $data['HotelGroupId'] = $this->HotelGroupId;
        $data['hotelGroupCode'] = $this->HotelGroupCode;
        $data['sign'] = $this->signData($data);
        $ret = $this->http('POST',$this->Server_Url.self::GetAllCardLevel ,null,$data);
        return ($ret == false) ? false : self::parseJsonString('验证是否是会员',$ret);
    }
    /** 获取所有会员等级
     *hotelGroupId  集团编号
     */
    public function getAllCardType()
    {
        $data['sessionId'] = $this->getSign();
        $data['appKey'] = $this->appKey;
        $data['HotelGroupId'] = $this->HotelGroupId;
        $data['hotelGroupCode'] = $this->HotelGroupCode;
        $data['sign'] = $this->signData($data);
        $ret = $this->http('POST',$this->Server_Url.self::getAllCardType ,null,$data);
        return ($ret == false) ? false : self::parseJsonString('验证是否是会员',$ret);
    }

    // 绿云测试接口
    public function greencloud_test()
    {
        $data['sessionId'] = $this->getSign();
        if(empty($data['sessionId'])) ajaxReturn(1, 'sessionId过期请重新申请',[]);
        $data['appKey'] = $this->appKey;
        $data['HotelGroupId'] = $this->HotelGroupId;
        $data['hotelGroupCode'] = $this->HotelGroupCode;
        $data['sign'] = $this->signData($data);
        $ret =  $this->http('POST',$this->Server_Url.self::Test ,null,$data);
        dd($ret);
        return ($ret == false) ? false : self::parseJsonString('绿云测试',$ret);
    }
    /** 判断是否注册成为会员
     *$mobile  手机号
     */
    public function checkDouble($mobile)
    {
        $data['sessionId'] = $this->getSign();
        $data['appKey'] = $this->appKey;
        $data['mobile'] = $mobile;
        $data['HotelGroupId'] = $this->HotelGroupId;
        $data['hotelGroupCode'] = $this->HotelGroupCode;
        $data['sign'] = $this->signData($data);
        $ret = $this->http('POST',$this->Server_Url.self::CheckDouble ,null,$data);
        return ($ret == false) ? false : self::parseJsonString('验证是否是会员',$ret);
    }

    /** 经过verifySmsCode方法验证成功后，程序可以自动给用户的手机进行注册操作。
     *$mobile  手机号
     */
    public function registerMember($mobile, $name = '', $cardType = '' , $cardLevel= '')
    {
        $arr_post = [];
        $arr_post['mobile'] = $mobile;
        $arr_post['name'] = $name;
        $arr_post['sex'] = '';
        $arr_post['email'] = '';
        $arr_post['idType'] = '';
        $arr_post['idNo'] = '';
        $arr_post['password'] = '';
        $arr_post['payCode'] = '';
        $arr_post['frontAccntIn'] = '';
        $arr_post['feeCode'] = '';
        $arr_post['isNotActive'] = 'F';
        $arr_post['cardType'] = $cardType;
        $arr_post['cardLevel'] = $cardLevel;
        $arr_post['hotelGroupId'] = $this->HotelGroupId;
        $arr_post['sessionId'] = $this->getSign();
        $arr_post['appKey'] = $this->appKey;
        $arr_post['sign'] = $this->signData($arr_post);
        $ret = $this->http('POST',$this->Server_Url.self::RegisterMember, null,$arr_post);
        return ($ret == false) ? false : self::parseJsonString('注册会员',$ret);
    }


    /** 如果是已经注册过的会员，通过 member_id 直接查询该用户的信息。
     *$mobile  手机号
     *$type    查询类型:默认1? 1(卡号),2(手机号)， ? 3(邮箱)，4(证件号)
     */
    public function getMemberInfo($mobile,$type)
    {
        $arr_post = [];
        $arr_post['hotelGroupId'] = $this->HotelGroupId;
        $arr_post['type'] = $type;
        $arr_post['value'] = $mobile;
        $arr_post['sessionId'] = $this->getSign();
        $arr_post['appKey'] = $this->appKey;
        $arr_post['sign'] = $this->signData($arr_post);
        $ret = $this->http('POST',$this->Server_Url.self::GetMemberInfo,null, $arr_post);
        return ($ret == false) ? false : self::parseJsonString('获取用户信息',$ret);
    }

    /**
     * 修改会员信息
     * @param $memberId
     * @param array $update_user
     * @return array|bool|mixed
     */
    public function updateMemberInfo($memberId, $update_user = [])
    {
        $arr_post = [];
        $arr_post['hotelGroupId'] = $this->HotelGroupId;
        $arr_post['memberId'] = $memberId;
        $arr_post['sessionId'] = $this->getSign();
        $arr_post['appKey'] = $this->appKey;
        $arr_post['sign'] = $this->signData($arr_post);

        $arr_post += $update_user;

        $ret = $this->http('POST',$this->Server_Url.self::UpdateMemberBaseInfo,null, $arr_post);
        return ($ret == false) ? false : self::parseJsonString('获取用户信息',$ret);
    }



    /** 如果是已经注册过的会员，查询用户的账户余额跟积分等
     *$cardId  会员卡ID
     * $tag    特殊用途( BASE:主账户 MONEY:现金帐户 TIMES: 次卡账户 )
     */
    public function GetCardBalanceInfo($cardId , $tag)
    {
        $arr_post['hotelGroupId'] = $this->HotelGroupId;
        $arr_post['cardId'] = $cardId;
        $arr_post['tag']    = $tag;
        $arr_post['sessionId'] = $this->getSign();
        $arr_post['appKey'] = $this->appKey;
        $arr_post['sign'] = $this->signData($arr_post);
        $ret = $this->http('POST',$this->Server_Url.self::GetCardBalanceInfo,null, $arr_post);
        return ($ret == false) ? false : self::parseJsonString('获取用户积分，金额等信息',$ret);
    }

    /**
     * 会员卡充值
     * @param $cardId 会员卡编号
     * @param $money 金额
     * @return array|bool|mixed
     */
    public function onlinePayForCardAccount($cardId , $money)
    {
        $arr_post['hotelGroupId'] = $this->HotelGroupId;
        $arr_post['payCode'] = $this->payCode;
        $arr_post['cardId']    = $cardId;
        $arr_post['money']    = $money;
        $arr_post['tag']    = 'BASE';
        $arr_post['sessionId'] = $this->getSign();
        $arr_post['appKey'] = $this->appKey;
        $arr_post['sign'] = $this->signData($arr_post);
        $ret = $this->http('POST',$this->Server_Url.self::onlinePayForCardAccount,null, $arr_post);
        return ($ret == false) ? false : self::parseJsonString('获取用户积分，金额等信息',$ret);
    }


    /** 会员卡升降级
     *$cardId  会员卡ID
     * $targetCardType 会员计划
     * $targetCardLevel 会员等级
     */
    public function CardUpgrade($cardId, $cardNo, $cardType, $cardLevel)
    {
        $arr_post['hotelGroupId'] = $this->HotelGroupId;
        $arr_post['hotelGroupCode'] = $this->HotelGroupCode;
        $arr_post['cardId'] = $cardId;
        $arr_post['cardNo'] = $cardNo;
        $arr_post['cardType'] = $cardType;
        $arr_post['cardLevel'] = $cardLevel;
        $arr_post['sessionId']= $this->getSign();
        $arr_post['appKey'] = $this->appKey;
        $arr_post['sign'] = $this->signData($arr_post);
        $ret = $this->http('POST',$this->Server_Url.self::CardUpgrade,null, $arr_post);
        return ($ret == false) ? false : self::parseJsonString('会员升降级',$ret);
    }

    /**
     * 会员储值消费
     * @param $cardId
     * @param $cardNo
     * @param $money
     * @return array|bool|mixed
     */
    public function onlineChargeForCardAccount($cardId, $cardNo, $money)
    {
        $arr_post['hotelGroupId'] = $this->HotelGroupId;
        $arr_post['hotelGroupCode'] = $this->HotelGroupCode;
        $arr_post['taCode'] = $this->UserPayCode;
        $arr_post['cardId'] = $cardId;
        $arr_post['cardNo'] = $cardNo;
//        $arr_post['cardType'] = $cardType;
        $arr_post['money'] = $money;
        $arr_post['sessionId']= $this->getSign();
        $arr_post['appKey'] = $this->appKey;
        $arr_post['sign'] = $this->signData($arr_post);
        $ret = $this->http('POST',$this->Server_Url.self::onlineChargeForCardAccount,null, $arr_post);
        return ($ret == false) ? false : self::parseJsonString('会员升降级',$ret);
    }


    /**curl
     *  $method  提交方式
     *  $url     路径
     *  $post_data 数据
     */
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


        $rsp = ($data == false) ? ($err_no . ', ' . $err_msg) : $data;
//        dump($err_msg);
//        Log::info("session验证", ["mession"=>$method, "url"=>$url, "post_str"=>$post_str, "rsp"=>$rsp, "data"=>$data]);
        ApiLog::tag('GreenCloud', $method, $url, $post_str, $rsp);
        curl_close($ch);

        if ($err_no > 0) {
            $this->err_msg = $err_msg;
            return false;
        } else {
            return $data;
        }
    }
    /**解析返回的json字符串，
     *  $str_json  数据
     *  $key     可以通过 $key 提取感兴趣的某一个值。
     */
    public static function parseJsonString($msg, $str_json, $key = null)
    {
        $arr = json_decode($str_json, true);

        if ($key == null) {
            return $arr;
        }

        $arr_keys = explode('.', $key);
        $tmp      = $arr;

        foreach ($arr_keys as $item) {
            $tmp = $tmp[$item];
        }

        $ret               = [];
        $ret['resultCode'] = $arr['resultCode'];
        $ret['resultMsg']  = $arr['resultMsg'];
        $ret[$key]         = $tmp;

        Log::info($msg,$ret);
        return $ret;
    }
    //签名
    public function sign()
    {
        $data['hotelGroupCode'] = $this->HotelGroupCode;
        $data['v']              = '3.0';
        $data['usercode']       = $this->usercode;
        $data['method']         = 'user.login';
        $data['local']          = 'zh_CN';
        $data['format']         = 'json';
        $data['appKey']         = $this->appKey;
        $data['password']       = $this->password;
        $signStr = $this->signData($data);
        $data['sign'] = $signStr;
        $url = $this->Sign_Url;
//        dump($url);
//        dump(json_encode($data));
        $ret= $this->http('POST',$url, null,  $data);
//dd($ret);
        $resuleInfo = self::parseJsonString('生成签名', $ret);
        if( isset($resuleInfo['resultInfo']) && !empty($resuleInfo['resultInfo']))
        {
            GreenCloudSetting::where('hotel_group_code', $this->HotelGroupCode)
                ->update( [
                    'sign'=>$resuleInfo['resultInfo'],
                    'update_at' => Carbon::now()->toDateTimeString(),
                ]);
            return $resuleInfo['resultInfo'];
        }else
            return false;
    }

    public function getSign(){
        $gcs = GreenCloudSetting::where('hotel_group_code', $this->HotelGroupCode)->first();
        if ( !$gcs || !isset($gcs['sign']) || empty($gcs['sign'])) {
            $sign = $this->sign();
        } else {
            $sign = $gcs['sign'];
        }
        return $sign;
    }
    /**生成签名
     *  $data  数据
     */
    public function signData($data)
    {
        ksort($data);

        $signStr = '';
        foreach($data as $key=>$gvalue)
        {
            $signStr .= $key.$gvalue;
        }
        $signStr = mb_strtoupper(sha1($this->appSecret.$signStr.$this->appSecret));

        return $signStr;
    }


}