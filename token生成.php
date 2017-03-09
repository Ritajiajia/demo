<?php
/**
 * Created by PhpStorm.
 * User: chenjia
 * Date: 2017/3/8
 * Time: 9:07
 */

define('SECRET', '67%$#ap28');
echo v_token(m_token());exit;

//生成token
function m_token() {
    $str = mt_rand(1000, 9999);//返回随机整数
    $str2 = dechex($_SERVER['REQUEST_TIME']-$str);//把十进制转换为十六进制。
    return $str.substr(md5($str.SECRET),0,10).$str2;
}
//比较token
function v_token($str, $delay=300) {
    $rs = substr($str,0,4);
    $middle = substr($str, 0,14);
    $rs2 = substr($str, 14, 8);
    return ($middle == $rs.substr(md5($rs.SECRET),0,10)) && ($_SERVER['REQUEST_TIME']-hexdec($rs2)-$rs<$delay);
}