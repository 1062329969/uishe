<?php

use Carbon\Carbon;

//use InvalidArgumentException;

if (!function_exists('http_get')) {
    function http_get($url, $async = false)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        if ($async == true) {
            curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT_MS, 200);
        }

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}

if (!function_exists('http_post')) {
    function http_post($url, $postData, $async = false)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        if ($async == true) {
            curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1);
        }

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}

// 验证请求参数，不能超出指定范围。
if (!function_exists('validateRequestInCage')) {
    function validateRequestInCage($request, $cage_fields)
    {
        if (empty($cage_fields)) {
            return;
        }

        $req_fields = $request->keys();

        foreach ($req_fields as $req_field) {
            if (!in_array($req_field, $cage_fields)) {
                throw new InvalidArgumentException('不能使用 ' . $req_field . ' 参数。');
            }
        }
    }
}

// 获取php运行时异常堆栈的前n行信息。
if (!function_exists('getExceptionInfo')) {
    function getExceptionInfo($exception, $limit = 10)
    {
        $msg = $exception->getMessage() . "\n" . $exception->getFile() . "(" . $exception->getLine() . ")\n";

        $error = $exception->getTraceAsString();
        $arr = explode("\n", $error, $limit + 1);

        for ($i = 0; $i < $limit; $i++) {
            $msg = $msg . $arr[$i] . "\n";
        }

        return $msg;
    }
}

// 指定开始、结束日期，获取完整日期数组。
if (!function_exists('getDayList')) {
    function getDayList($begin_day, $end_day)
    {
        if ($begin_day == null && $end_day == null) {
            return [Carbon::now()->toDateString()];
        }

        $begin = Carbon::parse($begin_day);
        $end = Carbon::parse($end_day);

        $list = [];

        while ($begin->lte($end)) {
            $list[] = $begin->toDateString();
            $begin->addDay();
        }

        return $list;
    }
}

// 指定开始日期和天数，获取完整日期数组。
if (!function_exists('getDayListByNums')) {
    function getDayListByNums($nums, $begin = null)
    {
        if ($begin == null) {
            $begin = Carbon::now();
        }

        $list = [];

        for ($i = 0; $i < $nums; $i++) {
            $list[] = $begin->toDateString();
            $begin->addDay();
        }

        return $list;
    }
}

if (!function_exists('random_string')) {
    function random_string($length)
    {
        $pattern = '1234567890';
        $str = '';

        for ($i = 0; $i < $length; $i++) {
            $str .= $pattern[mt_rand(0, 9)];
        }
        return $str;
    }
}

if (!function_exists('xml2Array')) {
    function xml2Array($xml)
    {
        $data = (array)simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        return array_change_key_case($data, CASE_LOWER);
    }
}

if (!function_exists('getId')) {
    function getId($prefix = '', $suffix = '')
    {
        list($ms, $s) = explode(' ', microtime());  // microtime() = "0.34528600 1522779639"
        $ms = (int)($ms * 1000000);
        $ms = substr($ms . '000000', 0, 3);

        $now = Carbon::now();
        $begin = Carbon::parse('2018-01-01 00:00:00');
        $today = Carbon::parse($now->toDateString() . ' 00:00:00');

        $days = $now->diffInDays($begin);
        $seconds = $now->diffInSeconds($today);

        $days = '0000' . $days;
        $seconds = '00000' . $seconds;

        $days = substr($days, -4);
        $seconds = substr($seconds, -5);

        $rnd = rand(10, 99);

        return $prefix . $days . $seconds . $ms . $rnd . $suffix;
    }

    /**
     * 生成唯一订单号
     * @param str 自定义内容
     */
    if (!function_exists('getRandOrderId')) {
        function getRandOrderId($str)
        {
            return $str . date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        }
    }

    /**
     * 计算价格 保留2位小数
     * $price  价格   必写
     * $decimals 余多少位 默认2位 可选
     */
    if (!function_exists('numberFormat')) {
        function numberFormat($price, $decimals = 2)
        {
            return number_format($price, $decimals, '.', '');
        }
    }

    /**
     * 按照比例获取可盈利金额，保留2位小数(返回的价格不包含原价，只包含可盈利的金额)
     * $price  价格   必写
     * $scale 盈利比例
     * $isScale 0关闭盈利 1开启 默认1开启
     */
    if (!function_exists('salesMoney')) {
          function salesMoney($price, $scale, $isScale = 1)
        {
            if ($isScale == 0) {
                return 0;
            }
            // 分销可获得的金额
            $money = number_format(($price * $scale) / 100, 2, '.', '');
            return $money;
        }
    }

    /**
     * @param int $time 0=全部;1=7天;2=30天;3=90天;4=当天;
     * @param string $startTime 自定义时间戳开始
     * @param string $endTime 自定义时间戳结束
     * @return array [timeStamp=开始和结束时间unix时间戳;timeString=开始和结束字符串时间;day开始和结束时间的差距天数]
     */
    if (!function_exists('searchTime')) {

        function searchTime($time = '', $startTime = '', $endTime = '')
        {
            $timeStamp = [];
            $timeString = [];

            //如果不搜时间
            if (empty($time) && (empty($startTime) || empty($endTime))) {
                return null;
            }

            //7天
            if ($time == 1) {
                $day = 7;
                $timeStamp = [
                    Carbon::tomorrow()->subDays($day)->timestamp,
                    Carbon::now()->timestamp,
                ];

                $timeString = [
                    Carbon::tomorrow()->subDays($day),
                    Carbon::now(),
                ];
            }

            //一个月
            if ($time == 2) {
                $day = 30;
                $timeStamp = [
                    Carbon::tomorrow()->subDays($day)->timestamp,
                    Carbon::now()->timestamp,
                ];
                $timeString = [
                    Carbon::tomorrow()->subDays($day),
                    Carbon::now(),
                ];
            }

            //3个月
            if ($time == 3) {
                $day = 90;
                $timeStamp = [
                    Carbon::tomorrow()->subDays($day)->timestamp,
                    Carbon::now()->timestamp,
                ];
                $timeString = [
                    Carbon::tomorrow()->subDays($day),
                    Carbon::now(),
                ];
            }
            //今天
            if ($time == 4) {
                $day = 1;
                $timeStamp = [
                    Carbon::today()->timestamp,
                    Carbon::tomorrow()->timestamp,
                ];

                $timeString = [
                    Carbon::today(),
                    Carbon::tomorrow(),
                ];
            }
            // 自定义时间
            if (!empty($startTime) && !empty($endTime)) {
                $day = Carbon::parse(date('Y-m-d H:i:s', $startTime))->diffInDays(date('Y-m-d H:i:s', $endTime));
                $timeStamp = [
                    $startTime,
                    $endTime,
                ];
                $timeString = [
                    date('Y-m-d H:i:s', $startTime),
                    date('Y-m-d H:i:s', $endTime),
                ];
            }
            return ['timeStamp' => $timeStamp, 'timeString' => $timeString, 'day' => $day];
        }
    }

    if (!function_exists('is_serialized')) {
        function is_serialized( $data ) {
            $data = trim( $data );
            if ( 'N;' == $data )
                return true;
            if ( !preg_match( '/^([adObis]):/', $data, $badions ) )
                return false;
            switch ( $badions[1] ) {
                case 'a' :
                case 'O' :
                case 's' :
                    if ( preg_match( "/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data ) )
                        return true;
                    break;
                case 'b' :
                case 'i' :
                case 'd' :
                    if ( preg_match( "/^{$badions[1]}:[0-9.E-]+;\$/", $data ) )
                        return true;
                    break;
            }
            return false;
        }
    }
}

