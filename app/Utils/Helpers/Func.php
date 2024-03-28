<?php

namespace App\Utils\Helpers;

class Func
{
    /**
     * CURL请求
     * @param string $url 请求网址
     * @param mixed $params 请求参数
     * @param int $ispost 请求方式
     * @param int $https https协议
     * @param array $header 头信息
     * @return bool|mixed
     */
    public static function curl($url, $params = false, $ispost = 0, $https = 0, $header = [])
    {
        //$httpInfo = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //设置header
        if (!empty($header)) {
            if (isset($header['Content-Type']) && $header['Content-Type'] == 'application/json') {
                $params = json_encode($params);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-type: application/json;charset='utf-8'"]);
            }
        }
        if ($https) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        }
        if ($ispost) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            if ($params) {
                if (is_array($params)) {
                    $params = http_build_query($params);
                }
                curl_setopt($ch, CURLOPT_URL, $url . (strpos($url, '?') ? '&' : '?') . $params);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }

        $response = curl_exec($ch);

        if ($response === FALSE) {
            return false;
        }
        curl_close($ch);
        return $response;
    }

    /**
     * filter an param from the request.
     * @param  mixed $param
     * @return mixed
     */
    public static function paramFilter($param)
    {
        if (!$param) return;

        if (is_array($param)) {
            foreach ($param as &$v) {
                $v = is_array($v) ? paramFilter($v) : htmlspecialchars(strip_tags($v), ENT_QUOTES);
            }
        } else {
            $param = htmlspecialchars(strip_tags($param), ENT_QUOTES);
        }
        return $param;
    }

    /**
     * 获取客户端IP地址
     * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
     * @return mixed
     */
    public static function getClientIp($type = 0)
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos)
                unset($arr[$pos]);
            $ip = trim($arr[0]);
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u", ip2long($ip));
        $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }

}