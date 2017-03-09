<?php
/**
 * Created by PhpStorm.
 * User: chenjia
 * Date: 2017/3/8
 * Time: 16:09
 */
//xml对象转换成数组
function xml2array($obj) {
    if(count($obj) >= 1) {
        $result = $key = [];
        foreach($obj as $key => $value) {
            isset($keys[$key]) ? ($keys[$key] += 1) : ($keys[$key] = 1);
            if($keys[$key] == 1) {
                $result[$key] = xml2array($value);
            } else if ($keys[$key] == 2) {
                $result[$key] = [$result[$key], xml2array($value)];
            } else if ($keys[$key] > 2) {
                $result[$key][] = xml2array($value);
            }
        }

        return $result;
    } else if(count($obj) == 0) {
        return (string)$obj;
    }
}