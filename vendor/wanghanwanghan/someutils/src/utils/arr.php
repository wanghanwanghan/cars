<?php

namespace wanghanwanghan\someUtils\utils;

class arr
{
    //laravel的
    public static function array_random($array,$number=null)
    {
        $requested=is_null($number) ? 1 : $number;

        $count=count($array);

        if ($requested > $count) return [];

        if (is_null($number)) return $array[array_rand($array)];

        if ((int)$number === 0) return [];

        $keys=array_rand($array,$number);

        $results=[];

        foreach ((array) $keys as $key)
        {
            $results[]=$array[$key];
        }

        return $results;
    }

    //修改一维或多维数组的键名，参数一：需要修改的数组，参数二：['从什么'=>'改成什么']
    public static function changeArrKey($arr,$example)
    {
        $res = [];

        foreach ($arr as $key => $value)
        {
            if (is_array($value))
            {
                if (array_key_exists($key,$example))
                {
                    $key = $example[$key];
                }

                $res[$key] = self::changeArrKey($value,$example);

            }else
            {
                if (array_key_exists($key,$example))
                {
                    $res[$example[$key]] = $value;

                }else
                {
                    $res[$key] = $value;
                }
            }
        }

        return $res;
    }

    //二维数组按照某key排序
    public static function sortArrByKey($array,$key='id',$rule='desc')
    {
        strtolower($rule) === 'asc' ? $rule = 'SORT_ASC' : $rule = 'SORT_DESC';

        $arrSort=[];

        foreach($array as $unique => $row)
        {
            foreach($row as $key => $value)
            {
                $arrSort[$key][$unique]=$value;
            }
        }

        array_multisort($arrSort[$key],constant($rule),$array);

        return $array;
    }

    //快速排序
    public static function quickSort($array)
    {
        if (count($array)<=1) return $array;

        $key=$array[0];

        $left_arr=$right_arr=[];

        for ($i=1;$i<count($array);$i++)
        {
            if ($array[$i]<=$key)
            {
                $left_arr[]=$array[$i];
            }else
            {
                $right_arr[]=$array[$i];
            }
        }

        $left_arr=self::quickSort($left_arr);
        $right_arr=self::quickSort($right_arr);

        return array_merge($left_arr,[$key],$right_arr);
    }

    //冒泡排序
    public static function bubbleSort($array)
    {
        for ($i=0;$i<count($array);$i++)
        {
            for ($j=$i+1;$j<count($array);$j++)
            {
                if ($array[$i]>$array[$j])
                {
                    list($array[$i],$array[$j])=[$array[$j],$array[$i]];
                }
            }
        }

        return $array;
    }










}
