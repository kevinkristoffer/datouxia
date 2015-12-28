<?php

class Puppy_Core_Utility_String
{

    /*
     * 生成指定长度的随机字符串
     */
    public static function getRandomString($length)
    {
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;

        for ($i = 0; $i < $length; $i++) {
            $str .= $strPol[rand(0, $max)];    //rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }

        return $str;
    }
}