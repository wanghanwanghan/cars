<?php

namespace wanghanwanghan\someUtils\utils;

class str
{
    //比较两个字符串是否完全相等
    public static function comparingTwoStrings($str1,$str2,$length='all')
    {
        if ($length==='all')
        {
            $length1=mb_strlen($str1);
            $length2=mb_strlen($str2);

            $length=$length1 > $length2 ? $length1 : $length2;
        }

        //等于0 - 两个字符串相等
        //小于0 - str1 长度小于 str2
        //大于0 - str1 长度大于 str2
        return strncasecmp($str1,$str2,$length);
    }

    //实现中文字串截取无乱码的方法
    public static function substrChinese($string,$start=0,$length=1)
    {
        return join('',array_slice(preg_split("//u",$string,-1,PREG_SPLIT_NO_EMPTY),$start,$length));
    }

    //实现中文字串反转
    public static function strrevChinese($string)
    {
        return join('',array_reverse(preg_split("//u",$string)));
    }

    //只含有26个字母或者数字的并且都是半角的字符串，转换成数字，用于hash分表
    public static function string2Number($str)
    {
        $j=0;

        for($i=0;$i<strlen($str);$i++)
        {
            if (is_numeric($str[$i]))
            {
                $j+=$str[$i];
            }else
            {
                $j+=ord($str[$i]);
            }
        }

        return $j;
    }

    //为字符串的指定位置添加指定字符
    public static function insertSomething($str,$offset,$delimiter='-')
    {
        foreach ($offset as $i=>$v)
        {
            $str=self::mbSubstrReplace($str,$delimiter,$i+$v,0);
        }

        return $str;
    }

    private static function mbSubstrReplace($string,$replacement,$start,$length=null)
    {
        if (is_array($string))
        {
            $num = count($string);
            // $replacement
            $replacement = is_array($replacement) ? array_slice($replacement,0,$num) : array_pad(array($replacement),$num,$replacement);
            // $start
            if (is_array($start))
            {
                $start = array_slice($start,0,$num);
                foreach ($start as $key => $value) $start[$key] = is_int($value) ? $value : 0;
            }else
            {
                $start = array_pad(array($start),$num,$start);
            }
            // $length
            if (!isset($length))
            {
                $length = array_fill(0,$num,0);
            }elseif (is_array($length))
            {
                $length = array_slice($length,0,$num);
                foreach ($length as $key => $value) $length[$key] = isset($value) ? (is_int($value) ? $value : $num) : 0;
            }else
            {
                $length = array_pad(array($length),$num,$length);
            }
            // Recursive call
            return array_map(__FUNCTION__,$string,$replacement,$start,$length);
        }
        preg_match_all('/./us', (string)$string,$smatches);
        preg_match_all('/./us', (string)$replacement,$rmatches);
        if ($length === NULL) $length = mb_strlen($string);
        array_splice($smatches[0],$start,$length,$rmatches[0]);
        return join($smatches[0]);
    }

    //中文字符串包含 source源字符串target要判断的是否包含的字符串
    public static function hasString($source,$target)
    {
        preg_match_all("/$target/sim", $source, $strResult, PREG_PATTERN_ORDER);

        return !empty(current($strResult));
    }

    //向前匹配
    public static function hasStringFront($source,$target1,$target2)
    {
        preg_match_all("/{$target1}(.*)(?={$target2})/sim", $source, $strResult, PREG_PATTERN_ORDER);

        return !empty(current($strResult));
    }





}
