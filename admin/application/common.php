<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function json_exit($code, $msg, $data = [])
{
    header('Content-Type:application/json');
    echo(json_encode(['code' => $code, 'msg' => $msg, 'data' => $data]));
    
    exit;
}
function json_exit_Base64($code, $msg, $data = [])
{
    header('Content-Type:application/json');
    echo(json_encode(['code' => $code, 'msg' => $msg, 'data' => base64_encode(json_encode($data))]));
    exit;
}
function isChineseName($name){
    if (preg_match('/^([\xe4-\xe9][\x80-\xbf]{2}){2,4}$/', $name)) {
        return true;
    } else {
        return false;
    }
}
function json_table($code,$msg,$count,$data=[]){
    header('Content-Type:application/json');
    $result = [
        'code' => $code, 
        'msg' => $msg, 
        'data' => $data, 
        'count' => $count
    ];
    echo(json_encode($result));exit;
}
function getIP() {
    if (getenv('HTTP_CLIENT_IP')) {
        $ip = getenv('HTTP_CLIENT_IP');
    }elseif (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
        $ips = explode(',', $ip);
        $ip = $ips[0];
    }elseif (getenv('HTTP_X_FORWARDED')) {
        $ip = getenv('HTTP_X_FORWARDED');
    }elseif (getenv('HTTP_FORWARDED_FOR')) {
        $ip = getenv('HTTP_FORWARDED_FOR');
    }elseif (getenv('HTTP_FORWARDED')) {
        $ip = getenv('HTTP_FORWARDED');
    }else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
function GetRandStr($length){
 //字符组合
 $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
 $len = strlen($str)-1;
 $randstr = '';
 for ($i=0;$i<$length;$i++) {
  $num=mt_rand(0,$len);
  $randstr .= $str[$num];
 }
 return $randstr;
}
function userToken($userid)
{
    return md5(md5($userid));
}
function https_get($url, $params = [])
{
    if ($params) {
        $url = $url . '?' . http_build_query($params);
    }
    $response = https_request($url);
    $result = json_decode($response, true);
    return $result;
}

function https_post($url, $data = [])
{
    $header = [
        'Accept:application/json', 'Content-Type:application/json',
    ];
    $response = https_request($url, json_encode($data), $header);
    $result = json_decode($response, true);
    return $result;
}

function https_request($url, $data = null, $headers = null)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    if (!empty($headers)) {
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    }
    //设置curl默认访问为IPv4
    if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    }
    $output = curl_exec($curl);
    curl_close($curl);
    return ($output);
}