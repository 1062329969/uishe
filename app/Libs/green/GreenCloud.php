<?php

/*
 * 对接绿云房态放价
 *
 */
namespace App\Libs\green;

use App\ApiLog;

class GreenCloud
{


    const Http_TimeOut = 5;
//    const Server_Url            = 'http://183.129.215.114:7213/ipmsint/NWEB/';     // 绿云测试服务器
//    const Server_Url            = 'http://123.206.185.79:8103/ipmsint/NWEB/';   // 青普线上绿云服务器
    const HotelGroupId          = 2;
    const Products              = 'products';                // 3.1
    const productDetails        = 'productDetails';          // 3.1
    const OtaOrderCheckAvl      = 'otaOrderCheckAvl';        // 订单操作接口 ( 可定检查)
    const OtaOrderBook          = 'otaOrderBook';            // 订单操作接口 ( 下单 )
    const OtaOrderCancel        = 'otaOrderCancel';          // 订单操作接口 ( 取消 )
    const saveWebPay            = 'saveWebPay';              // 支付完成
    const otaOrderListService   = 'otaOrderListService';      // 订单列表  4.4.2
    const otaOrderDetailService = 'otaOrderDetailService';      // 订单详情  4.4.3
    const otaOrderStatusService = 'otaOrderStatusService';      //
    const otaOrderService = 'otaOrderService';      //订单处理

    // 测试参数
//    const srcHotelGroupCode = 'GCBZG';
//    const hotelGroupCode    = 'GCBZG';
//    const otaChannel        = 'QUNAR';
    // 线上参数
//    const srcHotelGroupCode = 'BJQPJTG';
//    const hotelGroupCode = 'BJQPJTG';
//    const otaChannel = 'MOBAPP';

    private $Server_Url;//服务器地址
    private $hotelGroupCode;//集团码
    private $otaChannel;//ota途径
    private $PayCode;
    private $UserPayCode;
    /**
        $options = [
            'srcHotelGroupCode'=>'GCBZG',
            'hotelGroupCode'=>'GCBZG',
            'otaChannel'=>'QUNAR',
            'Server_Url'=>'http://183.129.215.114:7213/ipmsint/NWEB/',
        ];
     */
    public function __construct(Array $options)
    {
        $this->srcHotelGroupCode = $options['src_hotel_group_code'] ?? '';
        $this->hotelGroupCode = $options['hotel_group_code'] ?? '';
        $this->otaChannel = $options['ota_channel'] ?? '';
        $this->Server_Url = $options['server_hotel_url'] ?? '';
        $this->PayCode = $options['pay_code'] ?? '';
        $this->UserPayCode = $options['user_pay_code'] ?? '';
//        $this->risk_info = json_encode(['ip'=>self::get_client_ip(), 'device_id'=>''], JSON_UNESCAPED_UNICODE);
    }

    //绿云测试接口 返回hello ,it's OK.
    public function Products($hotelCodes, $isHalt)
    {
        $data['hotelCodes'] = $hotelCodes;
        $data['isHalt'] = $isHalt;
        $data['hotelGroupCode'] = $this->hotelGroupCode;
        $data['otaChannel'] = $this->otaChannel;
        return $this->http_post(self::Products, $data, true);
    }

    //绿云动态获取房态
    public function productDetails($hotelCodes, $start_time, $three_end_time, $productCode=NULL)
    {
        $data['hotelCodes'] = $hotelCodes;
        $data['fromDate']   = $start_time;
        $data['toDate']     = $three_end_time;
        $data['hotelGroupCode'] = $this->hotelGroupCode;
        $data['otaChannel']     = $this->otaChannel;
        if ($productCode !== NULL) $data['productCode'] = $productCode;
        return $this->http_post(self::productDetails, $data, true);
    }

    /**
     *订单检查房态接口
     */
    public function otaOrderCheckAvl($data)
    {
        $data['srcHotelGroupCode'] = $this->srcHotelGroupCode;
        $data['hotelGroupCode']    = $this->hotelGroupCode;
        $data['otaChannel']        = $this->otaChannel;
        $data['operationType']     = 'CheckAvl';

        return $this->http_post(self::OtaOrderCheckAvl, $data, true);
    }

    /**
     *订单下单接口
     */
    public function otaOrderBook($data)
    {
        $data['srcHotelGroupCode'] = $this->srcHotelGroupCode;
        $data['hotelGroupCode']    = $this->hotelGroupCode;
        $data['otaChannel']        = $this->otaChannel;
        $data['operationType']     = 'Book';
//        echo json_encode($data);die;
//dd($data);
        return $this->http_post(self::otaOrderService, $data, true);

        // $gcRsvNo = $ret['resultInfo'];   // 返回绿云订单id（取消时使用）。
    }

    /**
     *订单取消接口
     */
    public function otaOrderCancel($data)
    {
        $data['srcHotelGroupCode'] = $this->srcHotelGroupCode;
        $data['hotelGroupCode']    = $this->hotelGroupCode;
        $data['otaChannel']        = $this->otaChannel;
        $data['operationType']     = 'Cancel';

        return $this->http_post(self::OtaOrderCancel, $data, true);
    }

    // 用户完成对订单的支付，通知绿云。
    public function paySuccess($data)
    {
        $data['srcHotelGroupCode'] = $this->srcHotelGroupCode;
        $data['hotelGroupCode']    = $this->hotelGroupCode;
        $data['otaChannel']        = $this->otaChannel;

        return $this->http_post(self::saveWebPay, $data, true);
    }

    // 订单查询列表
    // $mobile 预订人手机号；从第 $index 条开始，获取 $count 条数据；$log 是否记录日志。
    public function otaOrderList($data, $index = 0, $count = 10, $log = true)
    {
        //$data['srcHotelGroupCode'] = $this->srcHotelGroupCode;
        $data['hotelGroupCode'] = $this->hotelGroupCode;
        $data['firstResult']    = $index;
        $data['maxResult']      = $count;
        $data['listOrder']      = 'id';

        return $this->http_post(self::otaOrderListService, $data, true, $log);
    }

    // 查询订单详情
    public function otaOrderDetail($hotelCode, $gcRsvNo)
    {
        $data['gcRsvNo'] = $gcRsvNo;
        $data['hotelGroupCode'] = $this->hotelGroupCode;
        $data['hotelCode'] = $hotelCode;
        $data['otaChannel'] = $this->otaChannel;
        $data['gcRsvNo'] = $gcRsvNo;


        return $this->http_post(self::otaOrderDetailService, $data, true);
    }


    // 订单状态查询
    public function otaOrderStatusService($hotelCode, $gcRsvNo)
    {
        $data['hotelGroupCode'] = $this->hotelGroupCode;
        $data['hotelCode'] = $hotelCode;
        $data['crsNos'] = $gcRsvNo;
        $data['otaChannel'] = $this->otaChannel;

        return $this->http_post(self::otaOrderStatusService, $data, true);
    }

    //curl
    private function http_post($url, $data = NULL, $json = false, $log = true)
    {
        $url  = $this->Server_Url . $url;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        if (!empty($data)) {
            if ($json && is_array($data)) {
                $data = json_encode($data, JSON_UNESCAPED_UNICODE);
            }
        }
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        if ($json) { //发送JSON数据
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8', 'Content-Length:' . strlen($data)));
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($curl);
        ApiLog::tag('GreenCloud', 'POST', $url, $data, $res);

        /*if ($log) {
            DBLog::create('GreenCloud', $res, $url, $data, 'POST');
        }*/

        // {"resultCode":7031,"resultMessage":"可售房量不足"}  {"resultCode":-1,"resultMessage":"下单失败"}
        // {"resultCode":0, "resultMessage":"预订成功", "resultInfo":"QUNAR2019022700008072"}

        $errorno = curl_errno($curl);
        if ($errorno) {
            return array('resultCode' => -1, 'resultMessage' => $errorno);
        }
        curl_close($curl);

        return json_decode($res, true);
    }


}